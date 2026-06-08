<?php

namespace App\Http\Controllers;

use App\Models\Development;
use App\Models\DevelopmentDocumentFile;
use App\Models\DevelopmentDocumentFolder;
use Illuminate\Support\Facades\Storage;

class PublicDevelopmentDocumentController extends Controller
{
    public function index(string $token)
    {
        $development = $this->developmentFromToken($token);
        $development->ensureDocumentFolders();

        return view('public.development-documents.index', [
            'development' => $development,
            'folders' => $development->documentFolders()
                ->with(['files' => fn ($query) => $query->where('visibility', 'public')])
                ->get(),
        ]);
    }

    public function folder(string $token, DevelopmentDocumentFolder $folder)
    {
        $development = $this->developmentFromToken($token);
        abort_unless($folder->development_id === $development->id, 404);

        return view('public.development-documents.folder', [
            'development' => $development,
            'folder' => $folder->load(['files' => fn ($query) => $query->where('visibility', 'public')->latest()]),
        ]);
    }

    public function viewFile(string $token, DevelopmentDocumentFile $file)
    {
        $this->assertPublicFile($token, $file);

        return redirect(Storage::disk($file->disk)->url($file->path));
    }

    public function download(string $token, DevelopmentDocumentFile $file)
    {
        $this->assertPublicFile($token, $file);

        return Storage::disk($file->disk)->download($file->path, $file->original_name);
    }

    private function developmentFromToken(string $token): Development
    {
        return Development::where('document_share_token', $token)->firstOrFail();
    }

    private function assertPublicFile(string $token, DevelopmentDocumentFile $file): void
    {
        $development = $this->developmentFromToken($token);

        abort_unless(
            $file->visibility === 'public' && $file->folder?->development_id === $development->id,
            404
        );
    }
}
