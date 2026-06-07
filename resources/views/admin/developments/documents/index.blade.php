@extends('layouts.admin')

@section('title', 'Documentos | ' . $development->name)

@section('toolbar')
    <div>
        <a href="{{ route('admin.developments.index') }}" class="text-muted text-hover-primary fw-semibold d-inline-flex align-items-center mb-3">
            <i class="ki-outline ki-arrow-left fs-3 me-1"></i>
            Volver a desarrollos
        </a>
        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
            Modulo documental
        </h1>
        <div class="text-muted fw-semibold fs-6 mt-1">{{ $development->name }}</div>
    </div>
    <div class="d-flex gap-3">
        <a href="{{ route('public.documents.index', $development->document_share_token) }}" target="_blank"
            rel="noopener" class="btn btn-light-primary">
            <i class="ki-outline ki-share fs-2"></i>
            Compartir Mini Drive
        </a>
        @if ($activeFolder)
            <label class="btn btn-primary mb-0" for="quick_upload_input">
                <i class="ki-outline ki-file-up fs-2"></i>
                Subir archivos
            </label>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        .document-folder-link {
            border-radius: .65rem;
            color: var(--bs-gray-700);
            transition: background-color .2s ease, color .2s ease;
        }

        .document-folder-link.active,
        .document-folder-link:hover {
            background-color: var(--bs-primary-light);
            color: var(--bs-primary);
        }

        .document-dropzone {
            border: 2px dashed var(--bs-gray-300);
            border-radius: .75rem;
            min-height: 210px;
            cursor: pointer;
            transition: border-color .2s ease, background-color .2s ease;
        }

        .document-dropzone.is-dragging,
        .document-dropzone:hover {
            border-color: var(--bs-primary);
            background-color: var(--bs-primary-light);
        }

        .document-file-icon {
            width: 38px;
            height: 38px;
        }
    </style>
@endpush

@section('content')
    <div class="row g-8">
        <div class="col-xl-3">
            <div class="card card-flush">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="fw-bold mb-0">Carpetas</h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="d-flex flex-column gap-2">
                        @foreach ($folders as $folder)
                            <a href="{{ route('admin.developments.documents.index', ['development' => $development, 'folder' => $folder->id]) }}"
                                @class([
                                    'document-folder-link d-flex align-items-center justify-content-between px-4 py-3 fw-semibold',
                                    'active' => $activeFolder?->is($folder),
                                ])>
                                <span class="d-flex align-items-center gap-3">
                                    <i class="ki-outline ki-folder fs-2"></i>
                                    <span>{{ $folder->name }}</span>
                                </span>
                                <span class="text-muted fs-7">{{ $folder->files->count() }} · {{ $folder->humanSize() }}</span>
                            </a>
                        @endforeach
                    </div>

                    <form method="POST" action="{{ route('admin.developments.documents.folders.store', $development) }}" class="mt-6">
                        @csrf
                        <div class="input-group input-group-solid">
                            <input type="text" name="name" class="form-control form-control-solid" placeholder="Nueva carpeta">
                            <button type="submit" class="btn btn-light-primary">
                                <i class="ki-outline ki-plus fs-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-9">
            @if ($activeFolder)
                <div class="card card-flush mb-8">
                    <div class="card-header align-items-center">
                        <div class="card-title">
                            <div class="d-flex align-items-center gap-3">
                                <i class="ki-outline ki-folder fs-2x text-primary"></i>
                                <div>
                                    <h3 class="fw-bold mb-0">{{ $activeFolder->name }}</h3>
                                    <div class="text-muted fw-semibold fs-7">
                                        {{ $activeFolder->files->count() }} archivos · {{ $activeFolder->humanSize() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-toolbar">
                            <span class="badge badge-light-primary">Link publico: archivos publicos</span>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5">
                                <thead>
                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                        <th>Nombre</th>
                                        <th>Tipo</th>
                                        <th>Tamano</th>
                                        <th>Estado</th>
                                        <th>Subido por</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                    @forelse ($activeFolder->files as $file)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="document-file-icon rounded bg-light-danger d-flex align-items-center justify-content-center">
                                                        <i class="ki-outline ki-document fs-2 text-danger"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold text-gray-900">{{ $file->original_name }}</div>
                                                        @if ($file->is_featured)
                                                            <span class="text-warning fs-7 fw-bold">
                                                                <i class="ki-outline ki-star fs-7"></i> Destacado
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class="badge badge-light">{{ strtoupper($file->extension ?: 'file') }}</span></td>
                                            <td>{{ $file->humanSize() }}</td>
                                            <td>
                                                <form method="POST" action="{{ route('admin.developments.documents.files.visibility', [$development, $file]) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm {{ $file->visibility === 'public' ? 'btn-light-success' : 'btn-light' }}">
                                                        {{ $file->visibility === 'public' ? 'Publico' : 'Privado' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td>{{ $file->uploader?->name ?? 'Sistema' }}</td>
                                            <td class="text-end">
                                                <a href="{{ $file->url() }}" target="_blank" class="btn btn-icon btn-light btn-active-light-primary btn-sm" title="Ver">
                                                    <i class="ki-outline ki-eye fs-2"></i>
                                                </a>
                                                <a href="{{ $file->url() }}" download class="btn btn-icon btn-light btn-active-light-primary btn-sm" title="Descargar">
                                                    <i class="ki-outline ki-file-down fs-2"></i>
                                                </a>
                                                <form method="POST" action="{{ route('admin.developments.documents.files.featured', [$development, $file]) }}" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button class="btn btn-icon btn-light btn-active-light-warning btn-sm" title="Destacar">
                                                        <i class="ki-outline ki-star fs-2"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.developments.documents.files.destroy', [$development, $file]) }}" class="d-inline" onsubmit="return confirm('Seguro que deseas eliminar este archivo?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-icon btn-light btn-active-light-danger btn-sm" title="Eliminar">
                                                        <i class="ki-outline ki-trash fs-2"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-10">
                                                <i class="ki-outline ki-folder fs-3x text-gray-400 mb-4"></i>
                                                <div class="fw-bold text-gray-900 fs-4">Carpeta vacia</div>
                                                <div class="text-muted fw-semibold">Carga archivos para esta categoria.</div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card card-flush mb-8">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="fw-bold mb-0">Zona de carga rapida</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.developments.documents.files.upload', [$development, $activeFolder]) }}" enctype="multipart/form-data" data-document-upload-form>
                            @csrf
                            <input id="quick_upload_input" type="file" name="files[]" class="d-none" multiple data-document-file-input>
                            <input type="hidden" name="visibility" value="public">
                            <label for="quick_upload_input" class="document-dropzone d-flex align-items-center justify-content-center text-center p-8" data-document-dropzone>
                                <span>
                                    <i class="ki-outline ki-file-up fs-3x text-gray-500 d-block mb-4"></i>
                                    <span class="fw-bold fs-4 text-gray-900 d-block">Arrastra archivos aqui</span>
                                    <span class="text-muted fw-semibold d-block mt-2">o haz clic para seleccionar archivos</span>
                                    <span class="text-muted fs-7 d-block mt-3">PDF, imagenes, videos, Excel y mas (max. 50MB por archivo)</span>
                                </span>
                            </label>
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div class="text-muted fw-semibold" data-document-file-summary>Sin archivos seleccionados.</div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ki-outline ki-file-up fs-2"></i>
                                    Cargar seleccion
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card card-flush">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="fw-bold mb-0">Permisos de carpeta</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.developments.documents.permissions.update', [$development, $activeFolder]) }}">
                            @csrf
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed">
                                    <thead>
                                        <tr class="text-gray-500 fw-bold fs-7 text-uppercase">
                                            <th>Usuario</th>
                                            <th>Ver</th>
                                            <th>Subir</th>
                                            <th>Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $index => $user)
                                            @php
                                                $permission = $activeFolder->permissions->firstWhere('user_id', $user->id);
                                            @endphp
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="permissions[{{ $index }}][user_id]" value="{{ $user->id }}">
                                                    <div class="fw-semibold text-gray-900">{{ $user->name }}</div>
                                                    <div class="text-muted fs-7">{{ $user->email }}</div>
                                                </td>
                                                <td><input type="checkbox" class="form-check-input" name="permissions[{{ $index }}][can_view]" value="1" @checked($permission?->can_view)></td>
                                                <td><input type="checkbox" class="form-check-input" name="permissions[{{ $index }}][can_upload]" value="1" @checked($permission?->can_upload)></td>
                                                <td><input type="checkbox" class="form-check-input" name="permissions[{{ $index }}][can_delete]" value="1" @checked($permission?->can_delete)></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-primary">Guardar permisos</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var uploadForm = document.querySelector('[data-document-upload-form]');

            if (!uploadForm) {
                return;
            }

            var input = uploadForm.querySelector('[data-document-file-input]');
            var dropzone = uploadForm.querySelector('[data-document-dropzone]');
            var summary = uploadForm.querySelector('[data-document-file-summary]');

            function updateSummary(files) {
                summary.textContent = files.length ? files.length + ' archivo(s) seleccionado(s).' : 'Sin archivos seleccionados.';
            }

            input.addEventListener('change', function () {
                updateSummary(input.files);
            });

            ['dragenter', 'dragover'].forEach(function (eventName) {
                dropzone.addEventListener(eventName, function (event) {
                    event.preventDefault();
                    dropzone.classList.add('is-dragging');
                });
            });

            ['dragleave', 'drop'].forEach(function (eventName) {
                dropzone.addEventListener(eventName, function (event) {
                    event.preventDefault();
                    dropzone.classList.remove('is-dragging');
                });
            });

            dropzone.addEventListener('drop', function (event) {
                input.files = event.dataTransfer.files;
                updateSummary(input.files);
            });
        });
    </script>
@endpush
