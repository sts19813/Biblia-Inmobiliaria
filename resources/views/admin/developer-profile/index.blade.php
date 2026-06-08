@extends('layouts.admin')

@section('title', 'Perfil de desarrolladora | Biblia Inmobiliaria')

@section('toolbar')
    <div>
        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
            Perfil de desarrolladora
        </h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
            <li class="breadcrumb-item text-muted">Gestiona la informacion de las desarrolladoras</li>
        </ul>
    </div>
    <a href="{{ route('admin.developer-profile.create') }}" class="btn btn-primary">
        <i class="ki-outline ki-plus fs-2"></i>
        Nueva desarrolladora
    </a>
@endsection

@push('styles')
    <style>
        .developer-thumb {
            width: 64px;
            height: 56px;
            border-radius: .65rem;
            object-fit: cover;
        }

        .developer-thumb-placeholder {
            width: 64px;
            height: 56px;
            border-radius: .65rem;
        }

        .developer-actions .btn {
            width: 36px;
            height: 36px;
        }
    </style>
@endpush

@section('content')
    <div class="card card-flush">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title">
                <div>
                    <h3 class="fw-bold text-gray-900 mb-1">Todas las desarrolladoras</h3>
                    <div class="text-muted fw-semibold fs-7">Administra datos corporativos, contacto, direccion y redes.</div>
                </div>
            </div>
            <div class="card-toolbar">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4"></i>
                    <input type="text" data-developer-profile-search
                        class="form-control form-control-solid w-250px ps-12" placeholder="Buscar...">
                </div>
            </div>
        </div>

        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5" data-developer-profile-table>
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th>Desarrolladora</th>
                            <th>Imagen</th>
                            <th>Contacto</th>
                            <th>Ubicacion</th>
                            <th>Actualizacion</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @forelse ($profiles as $profile)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.developer-profile.show', $profile) }}"
                                        class="text-gray-900 text-hover-primary fw-bold">
                                        {{ $profile->commercial_name }}
                                    </a>
                                    <div class="text-muted">{{ $profile->legal_name }}</div>
                                </td>
                                <td>
                                    @if ($profile->logoUrl())
                                        <img src="{{ $profile->logoUrl() }}" alt="{{ $profile->commercial_name }}"
                                            class="developer-thumb bg-light">
                                    @else
                                        <div class="developer-thumb-placeholder bg-light-primary d-flex align-items-center justify-content-center">
                                            <i class="ki-outline ki-bank fs-2x text-primary"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $profile->corporate_email ?: 'Sin email' }}</div>
                                    <div class="text-muted">{{ $profile->phone ?: 'Sin telefono' }}</div>
                                </td>
                                <td>
                                    <div>{{ $profile->city ?: 'Sin ciudad' }}</div>
                                    <div class="text-muted">{{ $profile->state }}</div>
                                </td>
                                <td>{{ $profile->updated_at?->diffForHumans() }}</td>
                                <td class="text-end">
                                    <div class="developer-actions d-flex justify-content-end gap-2">
                                        <a href="{{ route('admin.developer-profile.show', $profile) }}"
                                            class="btn btn-icon btn-light btn-active-light-primary"
                                            data-bs-toggle="tooltip" title="Visualizar">
                                            <i class="ki-outline ki-eye fs-2"></i>
                                        </a>
                                        <a href="{{ route('admin.developer-profile.edit', $profile) }}"
                                            class="btn btn-icon btn-light btn-active-light-primary"
                                            data-bs-toggle="tooltip" title="Editar">
                                            <i class="ki-outline ki-pencil fs-2"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10">
                                    <i class="ki-outline ki-bank fs-3x text-gray-400 mb-5"></i>
                                    <div class="fs-4 fw-bold text-gray-900">Sin desarrolladoras registradas.</div>
                                    <div class="text-gray-500 fw-semibold mt-1">Crea el primer perfil desde el boton superior.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end pt-6">
                {{ $profiles->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var table = document.querySelector('[data-developer-profile-table]');
            var search = document.querySelector('[data-developer-profile-search]');

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
