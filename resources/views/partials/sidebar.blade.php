<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px"
    data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">

    <div id="kt_app_sidebar_wrapper" class="app-sidebar-wrapper">
        <div class="hover-scroll-y my-5 my-lg-2 mx-4" data-kt-scroll="true"
            data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_app_header" data-kt-scroll-wrappers="#kt_app_sidebar_wrapper"
            data-kt-scroll-offset="5px">

            <div id="kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false"
                class="app-sidebar-menu-primary menu menu-column menu-rounded menu-sub-indention menu-state-bullet-primary px-3 mb-5">

                <div class="menu-item pt-5">
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7">Administracion</span>
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

                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ request()->routeIs('admin.catalogs.*') ? 'show' : '' }}">
                    <span class="menu-link {{ request()->routeIs('admin.catalogs.*') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="ki-outline ki-category fs-2"></i></span>
                        <span class="menu-title">Catalogos</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.catalogs.amenities.*') ? 'active' : '' }}"
                                href="{{ route('admin.catalogs.amenities.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
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
    </div>
</div>
