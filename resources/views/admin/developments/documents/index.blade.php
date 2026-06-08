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

        .document-upload-item {
            border: 1px solid var(--bs-gray-200);
            border-radius: .75rem;
            padding: 1rem;
            background-color: var(--bs-body-bg);
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
                                        <th>Fecha de subida</th>
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
                                            <td>{{ $file->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                                            <td class="text-end">
                                                <button type="button"
                                                    class="btn btn-icon btn-light btn-active-light-primary btn-sm"
                                                    title="Renombrar"
                                                    data-rename-file
                                                    data-action="{{ route('admin.developments.documents.files.rename', [$development, $file]) }}"
                                                    data-name="{{ $file->name }}">
                                                    <i class="ki-outline ki-pencil fs-2"></i>
                                                </button>
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
                                            <td colspan="7" class="text-center py-10">
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
                        <form method="POST"
                            action="{{ route('admin.developments.documents.files.upload', [$development, $activeFolder]) }}"
                            enctype="multipart/form-data"
                            data-document-upload-form
                            data-max-upload-size="{{ 50 * 1024 * 1024 }}"
                            data-max-upload-label="50 MB"
                            data-upload-url="{{ route('admin.developments.documents.files.upload', [$development, $activeFolder]) }}">
                            @csrf
                            <input id="quick_upload_input" type="file" name="files[]" class="d-none" multiple data-document-file-input>
                            <input type="hidden" name="visibility" value="public">
                            <label for="quick_upload_input" class="document-dropzone d-flex align-items-center justify-content-center text-center p-8" data-document-dropzone>
                                <span>
                                    <i class="ki-outline ki-file-up fs-3x text-gray-500 d-block mb-4"></i>
                                    <span class="fw-bold fs-4 text-gray-900 d-block">Arrastra archivos aqui</span>
                                    <span class="text-muted fw-semibold d-block mt-2">o haz clic para seleccionar y cargar automaticamente</span>
                                    <span class="text-muted fs-7 d-block mt-3">PDF, imagenes, videos, Excel y mas (max. 50MB por archivo)</span>
                                </span>
                            </label>
                            <div class="mt-4">
                                <div class="text-muted fw-semibold mb-3" data-document-file-summary>Sin cargas activas.</div>
                                <div class="d-flex flex-column gap-3" data-document-upload-progress-list></div>
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

    <div class="modal fade" id="rename_document_file_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" data-rename-file-form>
                    @csrf
                    @method('PATCH')
                    <div class="modal-header">
                        <h3 class="modal-title">Renombrar archivo</h3>
                        <button type="button" class="btn btn-icon btn-sm btn-active-light-primary" data-bs-dismiss="modal" aria-label="Cerrar">
                            <i class="ki-outline ki-cross fs-1"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for="document_file_name" class="form-label fw-semibold">Nombre del archivo</label>
                        <input id="document_file_name" type="text" name="name" class="form-control form-control-solid" required maxlength="180">
                        <div class="form-text">La extension se conserva automaticamente.</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar nombre</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var uploadForm = document.querySelector('[data-document-upload-form]');
            var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            var existingDocumentNames = @js($activeFolder ? $activeFolder->files->map(fn ($file) => strtolower($file->name . ($file->extension ? '.' . $file->extension : '')))->values() : []);

            function showToast(icon, title) {
                if (window.Swal) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: icon,
                        title: title,
                        showConfirmButton: false,
                        timer: 4200,
                        timerProgressBar: true
                    });
                    return;
                }

                window.alert(title);
            }

            var pendingToast = window.sessionStorage.getItem('document_upload_toast');

            if (pendingToast) {
                window.sessionStorage.removeItem('document_upload_toast');

                try {
                    pendingToast = JSON.parse(pendingToast);
                    showToast(pendingToast.icon || 'success', pendingToast.title || 'Archivos cargados correctamente.');
                } catch (error) {
                    showToast('success', 'Archivos cargados correctamente.');
                }
            }

            document.querySelectorAll('[data-rename-file]').forEach(function (button) {
                button.addEventListener('click', function () {
                    var modalElement = document.getElementById('rename_document_file_modal');
                    var renameForm = document.querySelector('[data-rename-file-form]');
                    var nameInput = document.getElementById('document_file_name');

                    renameForm.action = button.dataset.action;
                    nameInput.value = button.dataset.name || '';

                    if (window.bootstrap && bootstrap.Modal) {
                        bootstrap.Modal.getOrCreateInstance(modalElement).show();
                    }
                });
            });

            if (!uploadForm) {
                return;
            }

            var input = uploadForm.querySelector('[data-document-file-input]');
            var dropzone = uploadForm.querySelector('[data-document-dropzone]');
            var summary = uploadForm.querySelector('[data-document-file-summary]');
            var progressList = uploadForm.querySelector('[data-document-upload-progress-list]');
            var uploadUrl = uploadForm.dataset.uploadUrl || uploadForm.action;
            var maxUploadSize = parseInt(uploadForm.dataset.maxUploadSize || '52428800', 10);
            var maxUploadLabel = uploadForm.dataset.maxUploadLabel || '50 MB';
            var activeUploads = 0;
            var finishedUploads = 0;
            var successfulUploads = 0;
            var failedUploads = 0;
            var cancelledUploads = 0;
            var firstUploadError = '';

            function formatBytes(bytes) {
                if (!bytes) {
                    return '0 B';
                }

                var units = ['B', 'KB', 'MB', 'GB'];
                var size = bytes;
                var unitIndex = 0;

                while (size >= 1024 && unitIndex < units.length - 1) {
                    size = size / 1024;
                    unitIndex++;
                }

                return (unitIndex === 0 ? size : size.toFixed(1)) + ' ' + units[unitIndex];
            }

            function createProgressItem(file) {
                var item = document.createElement('div');
                item.className = 'document-upload-item';
                item.innerHTML =
                    '<div class="d-flex justify-content-between align-items-center mb-2">' +
                        '<div class="min-w-0">' +
                            '<div class="fw-bold text-gray-900 text-truncate"></div>' +
                            '<div class="text-muted fs-7"></div>' +
                        '</div>' +
                        '<span class="badge badge-light-primary" data-upload-status>0%</span>' +
                    '</div>' +
                    '<div class="progress h-6px">' +
                        '<div class="progress-bar bg-primary" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>' +
                    '</div>';

                item.querySelector('.fw-bold').textContent = file.name;
                item.querySelector('.text-muted').textContent = formatBytes(file.size);
                progressList.prepend(item);

                return item;
            }

            function setProgress(item, percent, statusText, statusClass) {
                var progressBar = item.querySelector('.progress-bar');
                var status = item.querySelector('[data-upload-status]');

                progressBar.style.width = percent + '%';
                progressBar.setAttribute('aria-valuenow', percent);
                status.textContent = statusText || percent + '%';

                if (statusClass) {
                    status.className = 'badge ' + statusClass;
                }
            }

            function canonicalFileName(fileName) {
                return (fileName || '').trim().toLowerCase();
            }

            function fileExists(file) {
                return existingDocumentNames.indexOf(canonicalFileName(file.name)) !== -1;
            }

            function rememberUploadedFile(file) {
                var canonicalName = canonicalFileName(file.name);

                if (existingDocumentNames.indexOf(canonicalName) === -1) {
                    existingDocumentNames.push(canonicalName);
                }
            }

            function confirmReplace(file) {
                var message = 'Ya existe un archivo con el mismo nombre y extension: ' + file.name + '.';

                if (window.Swal) {
                    return Swal.fire({
                        title: 'Archivo duplicado',
                        text: message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Reemplazar',
                        cancelButtonText: 'Cancelar',
                        buttonsStyling: false,
                        customClass: {
                            confirmButton: 'btn btn-primary',
                            cancelButton: 'btn btn-light'
                        }
                    }).then(function (result) {
                        return result.isConfirmed;
                    });
                }

                return Promise.resolve(window.confirm(message + ' Deseas reemplazarlo?'));
            }

            function finishUpload(item, ok, message, statusText, statusClass, isCancelled) {
                finishedUploads++;
                successfulUploads += ok ? 1 : 0;
                cancelledUploads += isCancelled ? 1 : 0;
                failedUploads += !ok && !isCancelled ? 1 : 0;

                if (!ok && !isCancelled && message && !firstUploadError) {
                    firstUploadError = message;
                }

                setProgress(
                    item,
                    100,
                    statusText || (ok ? 'Cargado' : 'Error'),
                    statusClass || (ok ? 'badge-light-success' : 'badge-light-danger')
                );

                if (!ok && message) {
                    var errorText = document.createElement('div');
                    errorText.className = 'text-danger fs-7 mt-2';
                    errorText.textContent = message;
                    item.appendChild(errorText);
                }

                summary.textContent = finishedUploads + ' de ' + activeUploads + ' archivo(s) procesado(s).';

                if (finishedUploads === activeUploads) {
                    if (successfulUploads > 0) {
                        var toastTitle = successfulUploads === 1
                            ? 'Archivo cargado correctamente.'
                            : successfulUploads + ' archivos cargados correctamente.';

                        if (failedUploads > 0 || cancelledUploads > 0) {
                            toastTitle += ' ' + (failedUploads + cancelledUploads) + ' no se cargaron.';
                        }

                        summary.textContent = 'Carga finalizada. Actualizando listado...';
                        window.sessionStorage.setItem('document_upload_toast', JSON.stringify({
                            icon: failedUploads > 0 ? 'warning' : 'success',
                            title: toastTitle
                        }));

                        window.setTimeout(function () {
                            window.location.reload();
                        }, 700);
                    } else if (cancelledUploads > 0 && failedUploads === 0) {
                        summary.textContent = 'Carga cancelada.';
                        showToast('warning', cancelledUploads === 1
                            ? 'Carga cancelada.'
                            : 'Cargas canceladas.');
                    } else {
                        summary.textContent = 'No se cargaron archivos. Revisa los errores e intenta de nuevo.';
                        showToast('error', failedUploads === 1 && firstUploadError
                            ? firstUploadError
                            : 'No se pudieron cargar los archivos. Revisa el detalle de cada error.');
                    }
                }
            }

            function firstValidationError(response) {
                if (!response || !response.errors) {
                    return response && response.message ? response.message : null;
                }

                for (var key in response.errors) {
                    if (Object.prototype.hasOwnProperty.call(response.errors, key) && response.errors[key].length) {
                        return response.errors[key][0];
                    }
                }

                return response.message || null;
            }

            function extractUploadError(request) {
                if (request.status === 413) {
                    return 'El archivo supera el limite permitido por el servidor. Maximo: ' + maxUploadLabel + '.';
                }

                if (request.status === 401 || request.status === 419) {
                    return 'Tu sesion expiro. Actualiza la pagina e inicia sesion nuevamente.';
                }

                if (request.status === 403) {
                    return 'No tienes permisos para subir archivos en esta carpeta.';
                }

                if (request.status === 404) {
                    return 'La carpeta ya no esta disponible. Actualiza la pagina.';
                }

                if (request.responseText) {
                    try {
                        var response = JSON.parse(request.responseText);
                        return firstValidationError(response) || 'No se pudo cargar el archivo.';
                    } catch (error) {
                        return request.status
                            ? 'No se pudo cargar el archivo. Codigo HTTP ' + request.status + '.'
                            : 'No se pudo cargar el archivo.';
                    }
                }

                return request.status
                    ? 'No se pudo cargar el archivo. Codigo HTTP ' + request.status + '.'
                    : 'No se pudo cargar el archivo.';
            }

            function uploadSingleFile(file, replaceExisting) {
                var item = createProgressItem(file);

                if (file.size > maxUploadSize) {
                    finishUpload(item, false, 'El archivo pesa ' + formatBytes(file.size) + '. El maximo permitido es ' + maxUploadLabel + '.');
                    return;
                }

                var request = new XMLHttpRequest();
                var formData = new FormData();

                formData.append('files[]', file);
                formData.append('visibility', 'public');

                if (replaceExisting) {
                    formData.append('replace_existing', '1');
                }

                request.open('POST', uploadUrl, true);
                request.setRequestHeader('X-CSRF-TOKEN', csrfToken);
                request.setRequestHeader('Accept', 'application/json');

                request.upload.addEventListener('progress', function (event) {
                    if (!event.lengthComputable) {
                        return;
                    }

                    var percent = Math.min(99, Math.round((event.loaded / event.total) * 100));
                    setProgress(item, percent);
                });

                request.addEventListener('load', function () {
                    var ok = request.status >= 200 && request.status < 300;
                    var message = ok ? '' : extractUploadError(request);

                    if (ok) {
                        rememberUploadedFile(file);
                    }

                    finishUpload(item, ok, message);
                });

                request.addEventListener('error', function () {
                    finishUpload(item, false, 'Error de conexion al cargar el archivo.');
                });

                request.send(formData);
            }

            function uploadFiles(fileList) {
                var files = Array.prototype.slice.call(fileList || []);

                if (!files.length) {
                    return;
                }

                if (finishedUploads === activeUploads) {
                    activeUploads = 0;
                    finishedUploads = 0;
                    successfulUploads = 0;
                    failedUploads = 0;
                    cancelledUploads = 0;
                    firstUploadError = '';
                    progressList.innerHTML = '';
                }

                activeUploads += files.length;
                summary.textContent = 'Cargando ' + activeUploads + ' archivo(s)...';

                files.forEach(function (file) {
                    if (!fileExists(file)) {
                        uploadSingleFile(file, false);
                        return;
                    }

                    confirmReplace(file).then(function (replaceConfirmed) {
                        if (replaceConfirmed) {
                            uploadSingleFile(file, true);
                            return;
                        }

                        var item = createProgressItem(file);
                        finishUpload(
                            item,
                            false,
                            'Carga cancelada. Ya existe un archivo con el mismo nombre y extension.',
                            'Cancelado',
                            'badge-light-warning',
                            true
                        );
                    });
                });
                input.value = '';
            }

            uploadForm.addEventListener('submit', function (event) {
                event.preventDefault();
            });

            input.addEventListener('change', function () {
                uploadFiles(input.files);
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
                uploadFiles(event.dataTransfer.files);
            });
        });
    </script>
@endpush
