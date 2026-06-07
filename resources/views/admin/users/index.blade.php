@extends('layouts.admin')

@php
    $activeTab = request('tab', 'users');
    $roleName = fn ($role) => $roleLabels[$role->name] ?? ucfirst(str_replace('_', ' ', $role->name));
@endphp

@section('title', 'Usuarios, roles y permisos | Biblia Inmobiliaria')

@section('toolbar')
    <div>
        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
            Usuarios, roles y permisos
        </h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
            <li class="breadcrumb-item text-muted">Administracion</li>
        </ul>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="ki-outline ki-plus fs-2"></i>
        Nuevo usuario
    </a>
@endsection

@section('content')
    <div class="card card-flush">
        <div class="card-header">
            <div class="card-title">
                <ul class="nav nav-tabs nav-line-tabs fs-6 border-0">
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'users' ? 'active' : '' }}" href="{{ route('admin.users.index', ['tab' => 'users']) }}">Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'roles' ? 'active' : '' }}" href="{{ route('admin.users.index', ['tab' => 'roles']) }}">Roles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'permissions' ? 'active' : '' }}" href="{{ route('admin.users.index', ['tab' => 'permissions']) }}">Permisos</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card-body pt-0">
            @if ($activeTab === 'users')
                <div class="d-flex align-items-center position-relative my-5">
                    <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4"></i>
                    <input type="text" data-kt-user-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Buscar usuario">
                </div>
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_users_table">
                        <thead>
                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                <th>Usuario</th>
                                <th>Contacto</th>
                                <th>Empresa</th>
                                <th>Rol</th>
                                <th>Permisos directos</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                            @forelse ($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-circle symbol-45px me-5">
                                                @if ($user->profile_photo_path || $user->google_avatar_url)
                                                    <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" style="object-fit: cover;">
                                                @else
                                                    <div class="symbol-label bg-light-primary text-primary fw-bold">{{ $user->initials() }}</div>
                                                @endif
                                            </div>
                                            <div>
                                                <a href="{{ route('admin.users.edit', $user) }}" class="text-gray-900 text-hover-primary fw-bold">{{ $user->name }}</a>
                                                <div class="text-muted fs-7">{{ $user->work_zone ?: 'Sin zona' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>{{ $user->email }}</div>
                                        <div class="text-muted">{{ $user->mobile_phone ?: 'Sin celular' }}</div>
                                    </td>
                                    <td>{{ $user->company ?: 'Independiente' }}</td>
                                    <td>
                                        @foreach ($user->roles as $role)
                                            <span class="badge badge-light-primary me-1">{{ $roleName($role) }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @forelse ($user->permissions as $permission)
                                            <span class="badge badge-light me-1 mb-1">{{ $permission->name }}</span>
                                        @empty
                                            <span class="text-muted">Sin permisos directos</span>
                                        @endforelse
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-icon btn-light btn-active-light-primary btn-sm me-1">
                                            <i class="ki-outline ki-pencil fs-2"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline" onsubmit="return confirm('Seguro que deseas eliminar este usuario?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-icon btn-light btn-active-light-danger btn-sm">
                                                <i class="ki-outline ki-trash fs-2"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center py-10 text-muted">Sin usuarios registrados.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end pt-6">{{ $users->links() }}</div>
            @elseif ($activeTab === 'roles')
                <div class="row g-8">
                    <div class="col-xl-4">
                        <form method="POST" action="{{ route('admin.roles.store') }}" class="card card-flush">
                            @csrf
                            <div class="card-header"><div class="card-title"><h3 class="fw-bold mb-0">Nuevo rol</h3></div></div>
                            <div class="card-body">
                                <label class="required form-label">Nombre</label>
                                <input name="name" class="form-control form-control-solid mb-6" required>
                                <label class="form-label">Permisos</label>
                                <div class="d-flex flex-column gap-3">
                                    @foreach ($permissions as $permission)
                                        <label class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}">
                                            <span class="form-check-label">{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="card-footer text-end"><button class="btn btn-primary">Crear rol</button></div>
                        </form>
                    </div>
                    <div class="col-xl-8">
                        <div class="d-flex flex-column gap-6">
                            @foreach ($roles as $role)
                                <form method="POST" action="{{ route('admin.roles.update', $role) }}" class="card card-flush">
                                    @csrf
                                    @method('PATCH')
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between gap-5 mb-5">
                                            <input name="name" value="{{ $role->name }}" class="form-control form-control-solid w-300px" required>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-light-primary">Guardar</button>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            @foreach ($permissions as $permission)
                                                <div class="col-md-6">
                                                    <label class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}" @checked($role->permissions->contains('name', $permission->name))>
                                                        <span class="form-check-label">{{ $permission->name }}</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </form>
                                <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" class="text-end mt-n4 me-6 mb-4" onsubmit="return confirm('Seguro que deseas eliminar este rol?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-light-danger">
                                        <i class="ki-outline ki-trash fs-3"></i>
                                        Eliminar rol
                                    </button>
                                </form>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <form method="POST" action="{{ route('admin.permissions.store') }}" class="d-flex gap-3 mb-8">
                    @csrf
                    <input name="name" class="form-control form-control-solid w-350px" placeholder="Nombre del permiso" required>
                    <button class="btn btn-primary">Crear permiso</button>
                </form>
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed">
                        <thead><tr class="text-gray-500 fw-bold fs-7 text-uppercase"><th>Permiso</th><th class="text-end">Acciones</th></tr></thead>
                        <tbody>
                            @foreach ($permissions as $permission)
                                <tr>
                                    <td class="fw-semibold text-gray-900">{{ $permission->name }}</td>
                                    <td class="text-end">
                                        <form method="POST" action="{{ route('admin.permissions.destroy', $permission) }}" onsubmit="return confirm('Seguro que deseas eliminar este permiso?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-icon btn-light btn-active-light-danger btn-sm"><i class="ki-outline ki-trash fs-2"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var table = document.getElementById('kt_users_table');
            var search = document.querySelector('[data-kt-user-table-filter="search"]');

            if (!table || !search) return;

            search.addEventListener('input', function () {
                var needle = search.value.toLowerCase();
                table.querySelectorAll('tbody tr').forEach(function (row) {
                    row.style.display = row.textContent.toLowerCase().includes(needle) ? '' : 'none';
                });
            });
        });
    </script>
@endpush
