@extends('layouts.admin')

@section('title', 'Master Brokers / Usuarios | Biblia Inmobiliaria')

@section('toolbar')
    <div>
        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
            Master Brokers / Usuarios
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
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4"></i>
                    <input type="text" data-kt-user-table-filter="search"
                        class="form-control form-control-solid w-250px ps-12" placeholder="Buscar usuario">
                </div>
            </div>
        </div>

        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_users_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th>Usuario</th>
                            <th>Contacto</th>
                            <th>Zona</th>
                            <th>Empresa</th>
                            <th>Rol</th>
                            <th class="text-end min-w-120px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @forelse ($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-circle symbol-45px me-5">
                                            @if ($user->profile_photo_path || $user->google_avatar_url)
                                                <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}"
                                                    style="object-fit: cover;">
                                            @else
                                                <div class="symbol-label bg-light-primary text-primary fw-bold">
                                                    {{ $user->initials() }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="d-flex flex-column">
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                                class="text-gray-800 text-hover-primary fw-bold">{{ $user->name }}</a>
                                            @if ($user->google_id)
                                                <span class="badge badge-light-info w-fit-content mt-1">Google</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>{{ $user->email }}</div>
                                    <div class="text-muted">{{ $user->mobile_phone ?: 'Sin celular' }}</div>
                                </td>
                                <td>{{ $user->work_zone ?: 'Sin zona' }}</td>
                                <td>{{ $user->company ?: 'Independiente' }}</td>
                                <td>
                                    <span class="badge badge-light-primary">
                                        {{ $roles[$user->role] ?? $user->role }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                        class="btn btn-icon btn-light btn-active-light-primary btn-sm me-1">
                                        <i class="ki-outline ki-pencil fs-2"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                        class="d-inline"
                                        onsubmit="return confirm('Seguro que deseas eliminar este usuario?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-icon btn-light btn-active-light-danger btn-sm">
                                            <i class="ki-outline ki-trash fs-2"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10 text-muted">Sin usuarios registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end pt-6">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var table = document.getElementById('kt_users_table');
            var search = document.querySelector('[data-kt-user-table-filter="search"]');

            if (!table || !search) {
                return;
            }

            search.addEventListener('input', function () {
                var needle = search.value.toLowerCase();

                table.querySelectorAll('tbody tr').forEach(function (row) {
                    row.style.display = row.textContent.toLowerCase().includes(needle) ? '' : 'none';
                });
            });
        });
    </script>
@endpush
