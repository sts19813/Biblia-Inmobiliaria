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
        body { background: #f5f7fb; font-family: Inter, sans-serif; }
        .public-hero {
            min-height: 380px;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        .public-hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(47, 35, 155, .88), rgba(99, 75, 255, .72));
        }
        .public-hero-content { position: relative; z-index: 1; }
        .folder-tile { transition: transform .2s ease, box-shadow .2s ease; }
        .folder-tile:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(15, 23, 42, .1); }
    </style>
</head>
<body>
    <section class="public-hero d-flex align-items-center" style="background-image: url('{{ $development->displayImageUrl() ?: asset('/metronic/assets/media/misc/bg-1.jpg') }}');">
        <div class="container public-hero-content text-white py-20">
            <div class="d-flex align-items-center gap-5">
                <div class="symbol symbol-90px">
                    <div class="symbol-label bg-white">
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
                    <a href="{{ $development->map_url }}" target="_blank" class="btn btn-light mt-8">Ver ubicacion en mapa</a>
                </div>
            </div>
        </div>
    </section>

    <main class="container py-14">
        <h2 class="fw-bold text-gray-900 mb-2">Documentacion y recursos</h2>
        <p class="text-muted fs-5 mb-10">Explora y descarga la informacion disponible sobre este desarrollo.</p>

        <div class="row g-8">
            @foreach ($folders as $folder)
                @php
                    $publicFiles = $folder->files;
                @endphp
                <div class="col-md-6 col-xl-3">
                    <a href="{{ route('public.documents.folder', [$development->document_share_token, $folder]) }}"
                        class="folder-tile card card-flush text-decoration-none h-100">
                        <div class="card-body p-8">
                            <div class="symbol symbol-70px mb-8">
                                <div class="symbol-label bg-light-primary">
                                    <i class="ki-outline ki-folder fs-2x text-primary"></i>
                                </div>
                            </div>
                            <div class="fw-bold fs-3 text-gray-900 mb-2">{{ $folder->name }}</div>
                            <div class="text-muted fw-semibold">
                                {{ $publicFiles->count() }} {{ $publicFiles->count() === 1 ? 'archivo' : 'archivos' }}
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </main>
</body>
</html>
