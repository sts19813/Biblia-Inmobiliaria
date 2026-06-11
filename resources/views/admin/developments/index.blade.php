@extends('layouts.admin')

@section('title', 'Desarrollos | Biblia Inmobiliaria')

@section('toolbar')
    <div>
        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
            Desarrollos
        </h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
            <li class="breadcrumb-item text-muted">Administracion</li>
        </ul>
    </div>
    <a href="{{ route('admin.developments.create') }}" class="btn btn-primary">
        <i class="ki-outline ki-plus fs-2"></i>
        Nuevo desarrollo
    </a>
@endsection

@push('styles')
    <style>
        .development-thumb {
            width: 64px;
            height: 56px;
            border-radius: .65rem;
            object-fit: cover;
        }

        .development-thumb-placeholder {
            width: 64px;
            height: 56px;
            border-radius: .65rem;
        }

        .development-actions .btn {
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
                    <h3 class="fw-bold text-gray-900 mb-1">Todos los desarrollos</h3>
                    <div class="text-muted fw-semibold fs-7">Gestiona tus proyectos inmobiliarios.</div>
                </div>
            </div>
            <div class="card-toolbar">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4"></i>
                    <input type="text" data-kt-development-table-filter="search"
                        class="form-control form-control-solid w-250px ps-12" placeholder="Buscar...">
                </div>
            </div>
        </div>

        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_developments_table">
                    <thead>
                        <tr class="text-start text-gray-700 fw-bold fs-7 text-uppercase gs-0">
                            <th>Desarrollo</th>
                            <th>Imagen</th>
                            <th>Ubicacion</th>
                            <th>Tipo</th>
                            <th>Precio desde</th>
                            <th>Entrega</th>
                            <th>Disponibilidad</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @forelse ($developments as $development)
                            <tr>
                                <td>
                                    <div class="d-flex flex-column">
                                        <a href="{{ route('admin.developments.show', $development) }}"
                                            class="text-gray-900 text-hover-primary fw-bold">
                                            {{ $development->name }}
                                        </a>
                                        <span class="text-muted">{{ $development->developerName() }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if ($development->displayImageUrl())
                                        <img src="{{ $development->displayImageUrl() }}" alt="{{ $development->name }}"
                                            class="development-thumb bg-light">
                                    @else
                                        <div class="development-thumb-placeholder bg-light-primary d-flex align-items-center justify-content-center">
                                            <i class="ki-outline ki-picture fs-2x text-primary"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $development->city }}</div>
                                    <div class="text-muted">{{ $development->zone }}</div>
                                </td>
                                <td>
                                    <span class="badge badge-light-primary">
                                        {{ $propertyTypes[$development->property_type] ?? $development->property_type }}
                                    </span>
                                </td>
                                <td>${{ number_format((float) $development->price_from, 2) }}</td>
                                <td>
                                    <div>{{ optional($development->delivery_date)->format('d/m/Y') }}</div>
                                    <span class="badge badge-light-info">
                                        {{ $statuses[$development->status] ?? $development->status }}
                                    </span>
                                </td>
                                <td>{{ $development->availability }}</td>
                                <td class="text-end">
                                    <div class="development-actions d-flex justify-content-end gap-2">
                                        <a href="{{ route('admin.developments.show', $development) }}"
                                            class="btn btn-icon btn-light btn-active-light-primary"
                                            data-bs-toggle="tooltip" title="Visualizar">
                                            <i class="ki-outline ki-eye fs-2"></i>
                                        </a>
                                        <a href="{{ route('admin.developments.edit', $development) }}"
                                            class="btn btn-icon btn-light btn-active-light-primary"
                                            data-bs-toggle="tooltip" title="Editar">
                                            <i class="ki-outline ki-pencil fs-2"></i>
                                        </a>
                                        <a href="{{ route('admin.developments.documents.index', $development) }}"
                                            class="btn btn-icon btn-light btn-active-light-primary"
                                            data-bs-toggle="tooltip" title="Documentos">
                                            <i class="ki-outline ki-folder fs-2"></i>
                                        </a>
                                        @can('eliminar desarrollo')
                                            <form method="POST" action="{{ route('admin.developments.destroy', $development) }}"
                                                class="d-inline" data-confirm-delete
                                                data-confirm-title="Eliminar desarrollo"
                                                data-confirm-text="Se eliminara {{ $development->name }} junto con sus imagenes y todos sus documentos. Escribe eliminar para confirmar.">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-icon btn-light btn-active-light-danger"
                                                    data-bs-toggle="tooltip" title="Eliminar">
                                                    <i class="ki-outline ki-trash fs-2"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-10">
                                    <i class="ki-outline ki-home-2 fs-3x text-gray-400 mb-5"></i>
                                    <div class="fs-4 fw-bold text-gray-900">Sin desarrollos registrados.</div>
                                    <div class="text-gray-500 fw-semibold mt-1">Crea el primer desarrollo desde el boton superior.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end pt-6">
                {{ $developments->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var table = document.getElementById('kt_developments_table');
            var search = document.querySelector('[data-kt-development-table-filter="search"]');

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
