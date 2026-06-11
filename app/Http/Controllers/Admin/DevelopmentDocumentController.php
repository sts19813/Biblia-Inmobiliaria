<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Development;
use App\Models\DevelopmentDocumentFile;
use App\Models\DevelopmentDocumentFolder;
use App\Models\User;
use App\Support\SystemSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DevelopmentDocumentController extends Controller
{
    public function index(Request $request, Development $development)
    {
        abort_unless($this->canViewDevelopmentDocuments($request->user(), $development), 403);

        $development->ensureDocumentShareToken();
        $development->ensureDocumentFolders();

        $folders = $development->documentFolders()
            ->with(['files' => fn ($query) => $query->latest(), 'permissions.user'])
            ->get();

        $activeFolder = $folders->firstWhere('id', (int) $request->query('folder')) ?? $folders->first();

        return view('admin.developments.documents.index', [
            'development' => $development,
            'folders' => $folders,
            'activeFolder' => $activeFolder,
            'users' => User::orderBy('name')->get(),
            'miniDriveUploadLimit' => SystemSettings::miniDriveUploadLimit(),
            'miniDriveDefaultVisibility' => SystemSettings::miniDriveDefaultVisibility(),
        ]);
    }

    public function storeFolder(Request $request, Development $development)
    {
        abort_unless($request->user()->can('subir documentos'), 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
        ]);

        $development->documentFolders()->firstOrCreate(
            ['slug' => Str::slug($data['name'])],
            [
                'name' => $data['name'],
                'sort_order' => ($development->documentFolders()->max('sort_order') ?? 0) + 1,
                'is_system' => false,
            ]
        );

        return redirect()
            ->route('admin.developments.documents.index', $development)
            ->with('status', 'Carpeta creada correctamente.');
    }

    public function upload(Request $request, Development $development, DevelopmentDocumentFolder $folder)
    {
        abort_unless($folder->development_id === $development->id, 404);
        abort_unless($this->canUploadToFolder($request->user(), $folder), 403);

        $uploadLimit = SystemSettings::miniDriveUploadLimit();

        $data = $request->validate([
            'files' => ['required', 'array'],
            'files.*' => ['file', 'max:'.$uploadLimit['effective_kilobytes']],
            'visibility' => ['required', 'in:public,private'],
            'replace_existing' => ['nullable', 'boolean'],
        ], [
            'files.required' => 'Selecciona al menos un archivo para cargar.',
            'files.array' => 'La carga debe incluir una lista de archivos.',
            'files.*.file' => 'Cada elemento debe ser un archivo valido.',
            'files.*.max' => 'El archivo no debe pesar mas de '.$uploadLimit['effective_label'].'.',
            'visibility.required' => 'Selecciona la visibilidad del archivo.',
            'visibility.in' => 'La visibilidad seleccionada no es valida.',
        ]);

        $uploadedFiles = collect();
        $replaceExisting = (bool) ($data['replace_existing'] ?? false);

        foreach ($data['files'] as $file) {
            $originalName = $file->getClientOriginalName();
            $name = pathinfo($originalName, PATHINFO_FILENAME);
            $extension = strtolower($file->getClientOriginalExtension() ?: $file->guessExtension() ?: '');
            $existingFile = $folder->files()
                ->whereRaw('LOWER(name) = ?', [Str::lower($name)])
                ->whereRaw('LOWER(extension) = ?', [Str::lower($extension)])
                ->first();

            if ($existingFile && ! $replaceExisting) {
                $message = 'Ya existe un archivo con el mismo nombre y extension: '.$existingFile->original_name.'.';

                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => $message,
                        'duplicate' => true,
                    ], 409);
                }

                return back()->withErrors(['files' => $message]);
            }

            if ($existingFile && $replaceExisting) {
                Storage::disk($existingFile->disk)->delete($existingFile->path);
                $existingFile->delete();
            }

            $storedName = Str::uuid().($extension ? '.'.$extension : '');
            $path = $file->storeAs(
                'development-documents/'.$development->id.'/'.$folder->id,
                $storedName,
                'public'
            );

            $uploadedFiles->push($folder->files()->create([
                'uploaded_by' => $request->user()->id,
                'name' => $name,
                'original_name' => $originalName,
                'path' => $path,
                'disk' => 'public',
                'mime_type' => $file->getClientMimeType(),
                'extension' => $extension,
                'size_bytes' => $file->getSize(),
                'visibility' => $data['visibility'],
            ]));
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Archivos cargados correctamente.',
                'files' => $uploadedFiles->map(fn (DevelopmentDocumentFile $file) => [
                    'id' => $file->id,
                    'name' => $file->original_name,
                    'size' => $file->humanSize(),
                    'uploaded_at' => $file->created_at?->format('d/m/Y H:i'),
                ])->values(),
            ]);
        }

        return redirect()
            ->route('admin.developments.documents.index', ['development' => $development, 'folder' => $folder->id])
            ->with('status', 'Archivos cargados correctamente.');
    }

    public function renameFile(Request $request, Development $development, DevelopmentDocumentFile $file)
    {
        $this->assertFileBelongsToDevelopment($development, $file);
        abort_unless($this->canUploadToFolder($request->user(), $file->folder), 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:180'],
        ]);

        $name = trim($data['name']);
        $extension = trim((string) $file->extension);
        $extensionSuffix = $extension ? '.'.$extension : '';

        if ($name === '') {
            return back()->withErrors(['name' => 'El nombre del archivo es obligatorio.']);
        }

        if ($extensionSuffix && Str::endsWith(Str::lower($name), '.'.Str::lower($extension))) {
            $name = substr($name, 0, -strlen($extensionSuffix));
        }

        $originalName = $extension ? $name.'.'.$extension : $name;

        $file->update([
            'name' => $name,
            'original_name' => $originalName,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Archivo renombrado correctamente.',
                'file' => [
                    'id' => $file->id,
                    'name' => $file->original_name,
                ],
            ]);
        }

        return back()->with('status', 'Archivo renombrado correctamente.');
    }

    public function toggleFeatured(Development $development, DevelopmentDocumentFile $file)
    {
        $this->assertFileBelongsToDevelopment($development, $file);
        abort_unless(request()->user()->can('subir documentos'), 403);

        $file->update(['is_featured' => ! $file->is_featured]);

        return back()->with('status', 'Archivo actualizado correctamente.');
    }

    public function toggleVisibility(Development $development, DevelopmentDocumentFile $file)
    {
        $this->assertFileBelongsToDevelopment($development, $file);
        abort_unless(request()->user()->can('subir documentos'), 403);

        $file->update(['visibility' => $file->visibility === 'public' ? 'private' : 'public']);

        return back()->with('status', 'Visibilidad actualizada correctamente.');
    }

    public function destroyFile(Development $development, DevelopmentDocumentFile $file)
    {
        $this->assertFileBelongsToDevelopment($development, $file);
        abort_unless($this->canDeleteFromFolder(request()->user(), $file->folder), 403);

        Storage::disk($file->disk)->delete($file->path);
        $file->delete();

        return back()->with('status', 'Archivo eliminado correctamente.');
    }

    public function updatePermissions(Request $request, Development $development, DevelopmentDocumentFolder $folder)
    {
        abort_unless($folder->development_id === $development->id, 404);
        abort_unless($request->user()->can('gestionar permisos documentos'), 403);

        $data = $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*.user_id' => ['required', 'exists:users,id'],
            'permissions.*.can_view' => ['nullable', 'boolean'],
            'permissions.*.can_upload' => ['nullable', 'boolean'],
            'permissions.*.can_delete' => ['nullable', 'boolean'],
        ]);

        $folder->permissions()->delete();

        foreach ($data['permissions'] ?? [] as $permission) {
            $folder->permissions()->create([
                'user_id' => $permission['user_id'],
                'can_view' => (bool) ($permission['can_view'] ?? false),
                'can_upload' => (bool) ($permission['can_upload'] ?? false),
                'can_delete' => (bool) ($permission['can_delete'] ?? false),
            ]);
        }

        return redirect()
            ->route('admin.developments.documents.index', ['development' => $development, 'folder' => $folder->id])
            ->with('status', 'Permisos actualizados correctamente.');
    }

    private function assertFileBelongsToDevelopment(Development $development, DevelopmentDocumentFile $file): void
    {
        abort_unless($file->folder?->development_id === $development->id, 404);
    }

    private function canViewDevelopmentDocuments(User $user, Development $development): bool
    {
        if ($user->can('ver documentos')) {
            return true;
        }

        return $development->documentFolders()
            ->whereHas('permissions', fn ($query) => $query
                ->where('user_id', $user->id)
                ->where('can_view', true))
            ->exists();
    }

    private function canUploadToFolder(User $user, DevelopmentDocumentFolder $folder): bool
    {
        return $user->can('subir documentos') || $folder->permissions()
            ->where('user_id', $user->id)
            ->where('can_upload', true)
            ->exists();
    }

    private function canDeleteFromFolder(User $user, DevelopmentDocumentFolder $folder): bool
    {
        return $user->can('eliminar documentos') || $folder->permissions()
            ->where('user_id', $user->id)
            ->where('can_delete', true)
            ->exists();
    }
}
