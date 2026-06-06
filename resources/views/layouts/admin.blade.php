<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Dashboard | Biblia Inmobiliaria')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Administracion de Biblia Inmobiliaria" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('/metronic/assets/media/logos/favicon.ico') }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="{{ asset('/metronic/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" />
    <link href="{{ asset('/metronic/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" />
    <link href="{{ asset('/metronic/assets/css/style.bundle.css') }}" rel="stylesheet" />

    <style>
        :root {
            --bs-primary: #2f80ed;
            --bs-primary-active: #1b64c7;
            --bs-primary-light: #eaf3ff;
        }

        .brand-wordmark {
            color: var(--bs-gray-900);
            font-size: 1.05rem;
            font-weight: 700;
            letter-spacing: 0;
            white-space: nowrap;
        }
    </style>

    @stack('styles')
</head>

<body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true"
    data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
    data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" class="app-default">

    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            themeMode = localStorage.getItem("data-bs-theme") || defaultThemeMode;
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>

    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
            @include('partials.header')

            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                @include('partials.sidebar')

                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <div class="d-flex flex-column flex-column-fluid">
                        @hasSection('toolbar')
                            <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                                <div id="kt_app_toolbar_container"
                                    class="app-container container-fluid d-flex flex-stack flex-wrap gap-3">
                                    @yield('toolbar')
                                </div>
                            </div>
                        @endif

                        <div id="kt_app_content" class="app-content flex-column-fluid">
                            <div id="kt_app_content_container" class="app-container container-fluid">
                                @if (session('status'))
                                    <div class="alert alert-success d-flex align-items-center mb-6">
                                        <i class="ki-outline ki-check-circle fs-2hx text-success me-4"></i>
                                        <div class="fw-semibold">{{ session('status') }}</div>
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger mb-6">
                                        <div class="fw-bold mb-1">Revisa la informacion capturada.</div>
                                        <ul class="mb-0 ps-4">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @yield('content')
                            </div>
                        </div>
                    </div>

                    @include('partials.footer')
                </div>
            </div>
        </div>
    </div>

    <script>var hostUrl = "{{ asset('/metronic/assets') }}/";</script>
    <script src="{{ asset('/metronic/assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('/metronic/assets/js/scripts.bundle.js') }}"></script>
    <script src="{{ asset('/metronic/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('/metronic/assets/js/widgets.bundle.js') }}"></script>
    <script src="{{ asset('/metronic/assets/js/custom/widgets.js') }}"></script>
    @stack('scripts')
</body>

</html>
