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

@section('content')
    <div class="card card-flush">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4"></i>
                    <input type="text" data-kt-development-table-filter="search"
                        class="form-control form-control-solid w-250px ps-12" placeholder="Buscar desarrollo">
                </div>
            </div>
        </div>

        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_developments_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th>Desarrollo</th>
                            <th>Ubicacion</th>
                            <th>Tipo</th>
                            <th>Precio desde</th>
                            <th>Entrega</th>
                            <th>Disponibilidad</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @forelse ($developments as $development)
                            <tr>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-900 fw-bold">{{ $development->name }}</span>
                                        <span class="text-muted">{{ $development->developer }}</span>
                                    </div>
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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10">
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
