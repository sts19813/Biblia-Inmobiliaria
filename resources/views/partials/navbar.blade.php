<div class="app-navbar flex-grow-1 justify-content-end" id="kt_app_header_navbar">
    <div class="app-navbar-item me-lg-6">
        <span class="text-muted fw-semibold fs-7 d-none d-lg-inline-flex">Administracion</span>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
