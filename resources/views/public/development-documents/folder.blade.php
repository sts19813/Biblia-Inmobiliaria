<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <title>{{ $folder->name }} | {{ $development->name }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700">
    <link href="{{ asset('/metronic/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet">
    <link href="{{ asset('/metronic/assets/css/style.bundle.css') }}" rel="stylesheet">
    <style>
        body { background: #f5f7fb; font-family: Inter, sans-serif; }
        .folder-file-row:last-child { border-bottom: 0 !important; }
    </style>
</head>
<body>
    @php
        $archiveExtensions = ['zip', 'rar', '7z', 'tar', 'gz'];
    @endphp

    <header class="bg-white border-bottom">
        <div class="container py-10">
            <a href="{{ route('public.documents.index', $development->document_share_token) }}" class="text-primary fw-bold fs-5 d-inline-flex align-items-center mb-8">
                <i class="ki-outline ki-arrow-left fs-2 me-2"></i>
                Volver a carpetas
            </a>
            <div class="d-flex align-items-center gap-5">
                <div class="symbol symbol-80px">
                    <div class="symbol-label bg-light-primary">
                        <i class="ki-outline ki-folder fs-3x text-primary"></i>
                    </div>
                </div>
                <div>
                    <h1 class="fw-bold text-gray-900 mb-2">{{ $folder->name }}</h1>
                    <div class="text-muted fs-4">
                        {{ $folder->files->count() }} {{ $folder->files->count() === 1 ? 'archivo disponible' : 'archivos disponibles' }}
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="container py-12">
        <div class="card card-flush shadow-sm mb-8">
            <div class="card-body p-6">
                <div class="d-flex align-items-center position-relative">
                    <i class="ki-outline ki-magnifier fs-2 position-absolute ms-5 text-gray-500"></i>
                    <input type="search" class="form-control form-control-lg form-control-solid ps-14"
                        placeholder="Buscar archivo dentro de {{ $folder->name }}..."
                        data-folder-file-search>
                </div>
            </div>
        </div>

        <div class="card card-flush">
            <div class="card-body p-0">
                @forelse ($folder->files as $file)
                    @php
                        $isArchive = in_array(strtolower((string) $file->extension), $archiveExtensions, true);
                    @endphp
                    <div class="folder-file-row d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-6 px-10 py-8 border-bottom"
                        data-folder-file-row
                        data-search-text="{{ Str::lower($file->original_name . ' ' . $file->extension . ' ' . $folder->name) }}">
                        <div class="d-flex align-items-center gap-5">
                            <div class="symbol symbol-55px">
                                <div class="symbol-label bg-light-danger">
                                    <i class="ki-outline ki-document fs-2x text-danger"></i>
                                </div>
                            </div>
                            <div>
                                <div class="fw-bold fs-3 text-gray-900">{{ $file->original_name }}</div>
                                <div class="text-muted fs-5">{{ strtoupper($file->extension ?: 'FILE') }} · {{ $file->humanSize() }}</div>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            @unless ($isArchive)
                                <a href="{{ route('public.documents.files.view', [$development->document_share_token, $file]) }}" target="_blank" class="btn btn-light">
                                    <i class="ki-outline ki-eye fs-2"></i>
                                    Ver
                                </a>
                            @endunless
                            <a href="{{ route('public.documents.files.download', [$development->document_share_token, $file]) }}" class="btn btn-primary">
                                <i class="ki-outline ki-file-down fs-2"></i>
                                Descargar
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20">
                        <i class="ki-outline ki-folder fs-4x text-gray-400 mb-5"></i>
                        <div class="fw-bold fs-3 text-gray-900">Sin archivos publicos.</div>
                    </div>
                @endforelse
                <div class="text-center py-20 d-none" data-folder-search-empty>
                    <i class="ki-outline ki-magnifier fs-4x text-gray-400 mb-5"></i>
                    <div class="fw-bold fs-3 text-gray-900">Sin resultados.</div>
                    <div class="text-muted fw-semibold">Intenta con otro nombre o tipo de archivo.</div>
                </div>
            </div>
        </div>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var input = document.querySelector('[data-folder-file-search]');
            var rows = Array.prototype.slice.call(document.querySelectorAll('[data-folder-file-row]'));
            var empty = document.querySelector('[data-folder-search-empty]');

            if (!input) {
                return;
            }

            input.addEventListener('input', function () {
                var term = input.value.trim().toLowerCase();
                var visible = 0;

                rows.forEach(function (row) {
                    var isVisible = !term || row.getAttribute('data-search-text').includes(term);
                    row.classList.toggle('d-none', !isVisible);
                    visible += isVisible ? 1 : 0;
                });

                empty.classList.toggle('d-none', visible > 0);
            });
        });
    </script>
</body>
</html>
