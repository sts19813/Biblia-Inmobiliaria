<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <title>{{ $development->name }} | Mini Drive</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700">
    <link href="{{ asset('/metronic/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet">
    <link href="{{ asset('/metronic/assets/css/style.bundle.css') }}" rel="stylesheet">
    <style>
        body {
            background: #f5f7fb;
            font-family: Inter, sans-serif;
        }

        .public-hero {
            min-height: 390px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .public-hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(47, 35, 155, .9), rgba(99, 75, 255, .72));
        }

        .public-hero-content {
            position: relative;
            z-index: 1;
        }

        .public-search {
            margin-top: -42px;
            position: relative;
            z-index: 2;
        }

        .folder-tile {
            border: 1px solid rgba(15, 23, 42, .06);
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        }

        .folder-tile:hover {
            transform: translateY(-2px);
            border-color: rgba(47, 128, 237, .22);
            box-shadow: 0 10px 28px rgba(15, 23, 42, .1);
        }

        .folder-icon {
            width: 70px;
            height: 70px;
        }

        .search-result-row:last-child {
            border-bottom: 0 !important;
        }
    </style>
</head>
<body>
    @php
        $iconClasses = ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-danger', 'bg-dark'];
        $publicFileCount = $folders->sum(fn ($folder) => $folder->files->count());
    @endphp

    <section class="public-hero d-flex align-items-center" style="background-image: url('{{ $development->displayImageUrl() ?: asset('/metronic/assets/media/misc/bg-1.jpg') }}');">
        <div class="container public-hero-content text-white py-20">
            <div class="d-flex flex-column flex-md-row align-items-md-center gap-6">
                <div class="symbol symbol-100px">
                    <div class="symbol-label bg-white shadow-sm">
                        @if ($development->logoUrl())
                            <img src="{{ $development->logoUrl() }}" alt="{{ $development->name }}" class="mw-100 mh-100 p-3">
                        @else
                            <i class="ki-outline ki-building fs-3x text-primary"></i>
                        @endif
                    </div>
                </div>
                <div>
                    <h1 class="display-5 fw-bold text-white mb-3">{{ $development->name }}</h1>
                    <div class="fs-4 fw-semibold">
                        <i class="ki-outline ki-geolocation fs-2 me-2"></i>
                        {{ $development->city }}, {{ $development->zone }}
                    </div>
                    <div class="d-flex flex-wrap gap-3 mt-8">
                        <a href="{{ $development->map_url }}" target="_blank" rel="noopener" class="btn btn-light">
                            <i class="ki-outline ki-geolocation fs-2"></i>
                            Ver ubicacion en mapa
                        </a>
                        <span class="btn btn-light-primary pe-none">
                            {{ $publicFileCount }} {{ $publicFileCount === 1 ? 'archivo publico' : 'archivos publicos' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main class="container pb-14">
        <div class="public-search card card-flush shadow-sm mb-12">
            <div class="card-body p-6">
                <div class="d-flex flex-column flex-lg-row gap-5 align-items-lg-center">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center position-relative">
                            <i class="ki-outline ki-magnifier fs-2 position-absolute ms-5 text-gray-500"></i>
                            <input type="search" class="form-control form-control-lg form-control-solid ps-14"
                                placeholder="Buscar por carpeta, archivo, PDF, planos, videos..."
                                data-public-document-search>
                        </div>
                    </div>
                    <div class="d-flex gap-3 text-nowrap">
                        <span class="badge badge-light-primary fs-7 px-4 py-3">{{ $folders->count() }} carpetas</span>
                        <span class="badge badge-light-success fs-7 px-4 py-3">{{ $publicFileCount }} archivos</span>
                    </div>
                </div>
            </div>
        </div>

        <section class="mb-10">
            <h2 class="fw-bold text-gray-900 mb-2">Documentacion y recursos</h2>
            <p class="text-muted fs-5 mb-0">Explora y descarga toda la informacion disponible sobre este desarrollo.</p>
        </section>

        <section class="card card-flush mb-10 d-none" data-search-results-card>
            <div class="card-header">
                <div class="card-title">
                    <h3 class="fw-bold mb-0">Resultados de busqueda</h3>
                </div>
                <div class="card-toolbar">
                    <span class="text-muted fw-semibold" data-search-results-count></span>
                </div>
            </div>
            <div class="card-body p-0" data-search-results>
                @foreach ($folders as $folder)
                    @foreach ($folder->files as $file)
                        <div class="search-result-row d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4 px-8 py-6 border-bottom"
                            data-search-result
                            data-search-text="{{ Str::lower($folder->name . ' ' . $file->original_name . ' ' . $file->extension . ' ' . $development->name) }}">
                            <div class="d-flex align-items-center gap-4">
                                <div class="symbol symbol-45px">
                                    <div class="symbol-label bg-light-primary">
                                        <i class="ki-outline ki-document fs-2 text-primary"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="fw-bold text-gray-900">{{ $file->original_name }}</div>
                                    <div class="text-muted fs-7">{{ $folder->name }} · {{ strtoupper($file->extension ?: 'FILE') }} · {{ $file->humanSize() }}</div>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('public.documents.files.view', [$development->document_share_token, $file]) }}" target="_blank" class="btn btn-sm btn-light">
                                    <i class="ki-outline ki-eye fs-3"></i>
                                    Ver
                                </a>
                                <a href="{{ route('public.documents.files.download', [$development->document_share_token, $file]) }}" class="btn btn-sm btn-primary">
                                    <i class="ki-outline ki-file-down fs-3"></i>
                                    Descargar
                                </a>
                            </div>
                        </div>
                    @endforeach
                @endforeach
                <div class="text-center py-10 d-none" data-search-empty>
                    <i class="ki-outline ki-magnifier fs-3x text-gray-400 mb-4"></i>
                    <div class="fw-bold text-gray-900 fs-4">Sin resultados</div>
                    <div class="text-muted fw-semibold">Intenta buscar por nombre de carpeta, archivo o tipo.</div>
                </div>
            </div>
        </section>

        <section class="row g-8" data-folder-grid>
            @foreach ($folders as $folderIndex => $folder)
                @php
                    $publicFiles = $folder->files;
                    $searchText = Str::lower($folder->name . ' ' . $publicFiles->pluck('original_name')->join(' ') . ' ' . $publicFiles->pluck('extension')->join(' '));
                    $iconClass = $iconClasses[$folderIndex % count($iconClasses)];
                @endphp
                <div class="col-md-6 col-xl-3" data-folder-card data-search-text="{{ $searchText }}">
                    <a href="{{ route('public.documents.folder', [$development->document_share_token, $folder]) }}"
                        class="folder-tile card card-flush text-decoration-none h-100">
                        <div class="card-body p-8">
                            <div class="folder-icon rounded-3 {{ $iconClass }} d-flex align-items-center justify-content-center mb-8">
                                <i class="ki-outline ki-document fs-2x text-white"></i>
                            </div>
                            <div class="fw-bold fs-3 text-gray-900 mb-2">{{ $folder->name }}</div>
                            <div class="text-muted fw-semibold">
                                {{ $publicFiles->count() }} {{ $publicFiles->count() === 1 ? 'archivo' : 'archivos' }}
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </section>

        <section class="text-center py-16 d-none" data-folder-empty>
            <i class="ki-outline ki-folder fs-4x text-gray-400 mb-5"></i>
            <div class="fw-bold fs-3 text-gray-900">No hay carpetas que coincidan</div>
            <div class="text-muted fw-semibold">Prueba con otro termino de busqueda.</div>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var input = document.querySelector('[data-public-document-search]');
            var folderCards = Array.prototype.slice.call(document.querySelectorAll('[data-folder-card]'));
            var folderEmpty = document.querySelector('[data-folder-empty]');
            var resultsCard = document.querySelector('[data-search-results-card]');
            var resultRows = Array.prototype.slice.call(document.querySelectorAll('[data-search-result]'));
            var resultEmpty = document.querySelector('[data-search-empty]');
            var resultCount = document.querySelector('[data-search-results-count]');

            if (!input) {
                return;
            }

            input.addEventListener('input', function () {
                var term = input.value.trim().toLowerCase();
                var hasTerm = term.length > 0;
                var visibleFolders = 0;
                var visibleResults = 0;

                folderCards.forEach(function (card) {
                    var isVisible = !hasTerm || card.getAttribute('data-search-text').includes(term);
                    card.classList.toggle('d-none', !isVisible);
                    visibleFolders += isVisible ? 1 : 0;
                });

                resultRows.forEach(function (row) {
                    var isVisible = hasTerm && row.getAttribute('data-search-text').includes(term);
                    row.classList.toggle('d-none', !isVisible);
                    visibleResults += isVisible ? 1 : 0;
                });

                folderEmpty.classList.toggle('d-none', visibleFolders > 0);
                resultsCard.classList.toggle('d-none', !hasTerm);
                resultEmpty.classList.toggle('d-none', visibleResults > 0);
                resultCount.textContent = visibleResults + ' resultado(s)';
            });
        });
    </script>
</body>
</html>
