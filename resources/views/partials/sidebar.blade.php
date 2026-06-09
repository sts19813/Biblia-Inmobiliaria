@php
    $user = Auth::user();
@endphp

<aside id="kt_app_sidebar" class="app-sidebar"
    data-kt-drawer="true"
    data-kt-drawer-name="app-sidebar"
    data-kt-drawer-activate="{default: true, lg: false}"
    data-kt-drawer-overlay="true"
    data-kt-drawer-width="250px"
    data-kt-drawer-direction="start"
    data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">

    <div id="kt_app_sidebar_wrapper" class="app-sidebar-wrapper">
        <div class="sidebar-shell">
            <div class="sidebar-brand">
                <button id="kt_app_sidebar_toggle"
                    type="button"
                    class="sidebar-brand-toggle app-sidebar-toggle d-none d-lg-inline-flex"
                    data-kt-toggle="true"
                    data-kt-toggle-state="active"
                    data-kt-toggle-target="body"
                    data-kt-toggle-name="app-sidebar-minimize"
                    aria-label="Contraer menu">
                    <i class="ki-outline ki-menu fs-3"></i>
                </button>

                <button type="button"
                    class="sidebar-brand-toggle d-inline-flex d-lg-none"
                    id="kt_app_sidebar_mobile_toggle"
                    aria-label="Abrir menu">
                    <i class="ki-outline ki-menu fs-3"></i>
                </button>

                <a href="{{ route('admin.dashboard') }}" class="sidebar-brand-link text-decoration-none">
                    <span class="sidebar-brand-mark">BI</span>
                    <span class="sidebar-brand-wordmark">Biblia Inmobiliaria</span>
                </a>
            </div>

            <div class="sidebar-scroll">
                <div id="kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false"
                    class="app-sidebar-menu-primary menu menu-column">

                    <div class="menu-item pt-3">
                        <div class="menu-content">
                            <span class="menu-heading fw-bold text-uppercase fs-8">Administracion</span>
                        </div>
                    </div>

                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                            href="{{ route('admin.dashboard') }}">
                            <span class="menu-icon"><i class="ki-outline ki-chart-line fs-2"></i></span>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </div>

                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('admin.advisor-catalog.*') ? 'active' : '' }}"
                            href="{{ route('admin.advisor-catalog.index') }}">
                            <span class="menu-icon"><i class="ki-outline ki-magnifier fs-2"></i></span>
                            <span class="menu-title">Catalogo de desarrollos</span>
                        </a>
                    </div>

                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('admin.developments.*') ? 'active' : '' }}"
                            href="{{ route('admin.developments.index') }}">
                            <span class="menu-icon"><i class="ki-outline ki-home-2 fs-2"></i></span>
                            <span class="menu-title">Desarrollos</span>
                        </a>
                    </div>

                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                            href="{{ route('admin.users.index') }}">
                            <span class="menu-icon"><i class="ki-outline ki-people fs-2"></i></span>
                            <span class="menu-title">Master Brokers / Usuarios</span>
                        </a>
                    </div>

                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ request()->routeIs('admin.catalogs.*') ? 'show' : '' }}">
                        <span class="menu-link {{ request()->routeIs('admin.catalogs.*') ? 'active' : '' }}">
                            <span class="menu-icon"><i class="ki-outline ki-category fs-2"></i></span>
                            <span class="menu-title">Catalogos</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item">
                                <a class="menu-link {{ request()->routeIs('admin.catalogs.amenities.*') ? 'active' : '' }}"
                                    href="{{ route('admin.catalogs.amenities.index') }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Amenidades</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('admin.developer-profile*') ? 'active' : '' }}"
                            href="{{ route('admin.developer-profile') }}">
                            <span class="menu-icon"><i class="ki-outline ki-bank fs-2"></i></span>
                            <span class="menu-title">Perfil de desarrolladora</span>
                        </a>
                    </div>

                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}"
                            href="{{ route('admin.settings') }}">
                            <span class="menu-icon"><i class="ki-outline ki-setting-3 fs-2"></i></span>
                            <span class="menu-title">Configuraciones</span>
                        </a>
                    </div>
                </div>
            </div>

            <div id="kt_app_sidebar_footer" class="app-sidebar-footer">
                <div class="sidebar-user-card">
                    <div class="sidebar-user-menu-trigger symbol symbol-circle"
                        data-kt-menu-trigger="{default: 'click', lg: 'click'}"
                        data-kt-menu-attach="body"
                        data-kt-menu-placement="top-start">
                        @if ($user->profile_photo_path || $user->google_avatar_url)
                            <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="w-100 h-100 rounded-circle" style="object-fit: cover;">
                        @else
                            <div class="symbol-label bg-primary text-white fw-bold w-100 h-100">{{ $user->initials() }}</div>
                        @endif
                    </div>

                    <div class="sidebar-user-details flex-grow-1">
                        <div class="sidebar-user-name text-truncate">{{ $user->name }}</div>
                        <div class="sidebar-user-email text-truncate">{{ $user->email }}</div>
                    </div>

                    <div class="sidebar-user-actions d-flex align-items-center gap-2">
                        <button type="button"
                            class="sidebar-user-action"
                            data-kt-menu-trigger="{default: 'click', lg: 'click'}"
                            data-kt-menu-attach="body"
                            data-kt-menu-placement="top-end"
                            aria-label="Opciones">
                            <i class="ki-outline ki-setting-4 fs-5"></i>
                        </button>
                        <a href="#"
                            class="sidebar-user-action is-danger"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            aria-label="Cerrar sesion">
                            <i class="ki-outline ki-exit-right fs-5"></i>
                        </a>
                    </div>

                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                        data-kt-menu="true">
                        <div class="menu-item px-3">
                            <div class="menu-content d-flex align-items-center px-3">
                                <div class="symbol symbol-50px me-5">
                                    @if ($user->profile_photo_path || $user->google_avatar_url)
                                        <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="w-100 h-100 rounded-circle" style="object-fit: cover;">
                                    @else
                                        <div class="symbol-label bg-primary text-white fw-bold fs-5">{{ $user->initials() }}</div>
                                    @endif
                                </div>

                                <div class="d-flex flex-column min-w-0">
                                    <div class="fw-bold fs-5 text-truncate">{{ $user->name }}</div>
                                    <a href="mailto:{{ $user->email }}" class="fw-semibold text-muted text-hover-primary fs-7 text-truncate">
                                        {{ $user->email }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="separator my-2"></div>

                        <div class="menu-item px-5">
                            <a href="{{ route('admin.users.edit', $user) }}" class="menu-link px-5">Mi perfil</a>
                        </div>

                        <div class="menu-item px-5"
                            data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                            data-kt-menu-placement="right-start"
                            data-kt-menu-offset="8px, 0">
                            <a href="#" class="menu-link px-5">
                                <span class="menu-title position-relative">Modo
                                    <span class="ms-5 position-absolute translate-middle-y top-50 end-0">
                                        <i class="ki-outline ki-night-day theme-light-show fs-2"></i>
                                        <i class="ki-outline ki-moon theme-dark-show fs-2"></i>
                                    </span>
                                </span>
                            </a>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px"
                                data-kt-menu="true" data-kt-element="theme-mode-menu">
                                <div class="menu-item px-3 my-0">
                                    <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
                                        <span class="menu-icon" data-kt-element="icon"><i class="ki-outline ki-night-day fs-2"></i></span>
                                        <span class="menu-title">Claro</span>
                                    </a>
                                </div>
                                <div class="menu-item px-3 my-0">
                                    <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
                                        <span class="menu-icon" data-kt-element="icon"><i class="ki-outline ki-moon fs-2"></i></span>
                                        <span class="menu-title">Oscuro</span>
                                    </a>
                                </div>
                                <div class="menu-item px-3 my-0">
                                    <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
                                        <span class="menu-icon" data-kt-element="icon"><i class="ki-outline ki-screen fs-2"></i></span>
                                        <span class="menu-title">Sistema</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="separator my-2"></div>

                        <div class="menu-item px-5">
                            <a href="#" class="menu-link px-5"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Cerrar sesion
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</aside>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
