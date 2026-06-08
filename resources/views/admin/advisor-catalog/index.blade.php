@extends('layouts.admin')

@php
    $selected = fn (string $key) => collect((array) ($filters[$key] ?? []))->filter()->values()->all();
    $value = fn (string $key, $default = null) => $filters[$key] ?? $default;
    $statusClasses = [
        'preventa' => 'badge-light-primary',
        'obra_iniciada' => 'badge-light-warning',
        'entrega_inmediata' => 'badge-light-success',
    ];
    $detail = fn ($development, string $key, $fallback = '-') => $development->property_details[$key] ?? $fallback;
@endphp

@section('title', 'Catalogo de desarrollos | Biblia Inmobiliaria')

@section('toolbar')
    <div>
        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
            Catalogo de desarrollos
        </h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
            <li class="breadcrumb-item text-muted">Busqueda avanzada para asesores</li>
        </ul>
    </div>
    <div class="d-flex gap-3">
        <button type="submit" form="advisor_catalog_filters" class="btn btn-primary">
            <i class="ki-outline ki-filter fs-2"></i>
            Filtrar
        </button>
        <a href="{{ route('admin.advisor-catalog.index') }}" class="btn btn-light">Limpiar</a>
    </div>
@endsection

@push('styles')
    <style>
        .advisor-catalog-table {
            min-width: 2350px;
        }

        .advisor-filter-card {
            top: 100px;
        }

        .advisor-dev-thumb {
            width: 44px;
            height: 44px;
            border-radius: .55rem;
            object-fit: cover;
        }

        .advisor-doc-icon {
            width: 28px;
            height: 28px;
        }
    </style>
@endpush

@section('content')
    <form id="advisor_catalog_filters" method="GET" action="{{ route('admin.advisor-catalog.index') }}">
        <div class="row g-6">
            <div class="col-xl-3 col-xxl-2">
                <div class="card card-flush position-xl-sticky advisor-filter-card">
                    <div class="card-header align-items-center">
                        <div class="card-title">
                            <h3 class="fw-bold mb-0">Filtros avanzados</h3>
                        </div>
                        <div class="card-toolbar">
                            <a href="{{ route('admin.advisor-catalog.index') }}" class="btn btn-sm btn-light-primary">Limpiar</a>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="mb-5">
                            <label class="form-label fw-semibold">Tipo de propiedad</label>
                            <select name="property_types[]" class="form-select form-select-solid" data-control="select2"
                                data-placeholder="Todos" multiple>
                                @foreach ($propertyTypes as $type => $label)
                                    <option value="{{ $type }}" @selected(in_array($type, $selected('property_types'), true))>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-semibold">Ciudad</label>
                            <select name="cities[]" class="form-select form-select-solid" data-control="select2"
                                data-placeholder="Todas" multiple data-city-filter>
                                @foreach ($cities as $city)
                                    <option value="{{ $city }}" @selected(in_array($city, $selected('cities'), true))>{{ $city }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-semibold">Zona / colonia</label>
                            <select name="zones[]" class="form-select form-select-solid" data-control="select2"
                                data-placeholder="Todas" multiple data-zone-filter>
                                @foreach ($zonesByCity as $city => $zones)
                                    @foreach ($zones as $zone)
                                        <option value="{{ $zone }}" data-city="{{ $city }}" @selected(in_array($zone, $selected('zones'), true))>{{ $zone }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-semibold">Estado del proyecto</label>
                            <div class="d-flex flex-column gap-3">
                                @foreach ($statuses as $status => $label)
                                    <label class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" name="statuses[]" value="{{ $status }}"
                                            @checked(in_array($status, $selected('statuses'), true))>
                                        <span class="form-check-label">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-semibold">Rango de precio</label>
                            <div class="row g-3">
                                <div class="col-6">
                                    <input type="number" name="price_min" class="form-control form-control-solid"
                                        value="{{ $value('price_min') }}" min="0" step="10000" placeholder="Min">
                                </div>
                                <div class="col-6">
                                    <input type="number" name="price_max" class="form-control form-control-solid"
                                        value="{{ $value('price_max') }}" min="0" step="10000" placeholder="Max">
                                </div>
                            </div>
                            <div class="text-muted fs-8 mt-2">
                                Base: ${{ number_format($priceMin, 0) }} - ${{ number_format($priceMax, 0) }}
                            </div>
                        </div>

                        <div class="row g-3 mb-5">
                            <div class="col-6">
                                <label class="form-label fw-semibold">Recamaras</label>
                                <select name="bedrooms" class="form-select form-select-solid" data-control="select2" data-hide-search="true">
                                    <option value="">Todas</option>
                                    @foreach ([1, 2, 3, 4] as $number)
                                        <option value="{{ $number }}" @selected((string) $value('bedrooms') === (string) $number)>{{ $number === 4 ? '4+' : $number }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">Banos</label>
                                <select name="bathrooms" class="form-select form-select-solid" data-control="select2" data-hide-search="true">
                                    <option value="">Todos</option>
                                    @foreach ([1, 2, 3, 4] as $number)
                                        <option value="{{ $number }}" @selected((string) $value('bathrooms') === (string) $number)>{{ $number === 4 ? '4+' : $number }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-semibold">Formas de pago</label>
                            <select name="payment_methods[]" class="form-select form-select-solid" data-control="select2"
                                data-placeholder="Todas" multiple>
                                @foreach ($paymentMethods as $method)
                                    <option value="{{ $method }}" @selected(in_array($method, $selected('payment_methods'), true))>{{ $method }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-semibold">Bono asesor</label>
                            <select name="advisor_bonus" class="form-select form-select-solid" data-control="select2" data-hide-search="true">
                                <option value="">Todos</option>
                                <option value="with_bonus" @selected($value('advisor_bonus') === 'with_bonus')>Con bono</option>
                                <option value="without_bonus" @selected($value('advisor_bonus') === 'without_bonus')>Sin bono</option>
                            </select>
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-semibold">Amenidades</label>
                            <select name="amenities[]" class="form-select form-select-solid" data-control="select2"
                                data-placeholder="Todas" multiple>
                                @foreach ($amenities as $amenity)
                                    <option value="{{ $amenity }}" @selected(in_array($amenity, $selected('amenities'), true))>{{ $amenity }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-semibold">M2 de propiedad</label>
                            <div class="row g-3">
                                <div class="col-6">
                                    <input type="number" name="construction_m2_min" class="form-control form-control-solid"
                                        value="{{ $value('construction_m2_min') }}" min="0" placeholder="Min">
                                </div>
                                <div class="col-6">
                                    <input type="number" name="construction_m2_max" class="form-control form-control-solid"
                                        value="{{ $value('construction_m2_max') }}" min="0" placeholder="Max">
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="form-label fw-semibold">M2 terreno</label>
                            <div class="row g-3">
                                <div class="col-6">
                                    <input type="number" name="land_m2_min" class="form-control form-control-solid"
                                        value="{{ $value('land_m2_min') }}" min="0" placeholder="Min">
                                </div>
                                <div class="col-6">
                                    <input type="number" name="land_m2_max" class="form-control form-control-solid"
                                        value="{{ $value('land_m2_max') }}" min="0" placeholder="Max">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ki-outline ki-magnifier fs-2"></i>
                            Buscar desarrollos
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-xl-9 col-xxl-10">
                <div class="card card-flush">
                    <div class="card-header align-items-center py-5 gap-3">
                        <div class="card-title">
                            <div>
                                <h3 class="fw-bold text-gray-900 mb-1">Mostrando {{ $developments->total() }} desarrollos</h3>
                                <div class="text-muted fw-semibold fs-7">De {{ $totalDevelopments }} desarrollos disponibles en sistema.</div>
                            </div>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex align-items-center position-relative">
                                <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4"></i>
                                <input type="search" class="form-control form-control-solid w-250px ps-12"
                                    placeholder="Filtrar tabla..." data-advisor-table-search>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-7 gy-4 advisor-catalog-table" data-advisor-catalog-table>
                                <thead>
                                    <tr class="text-start text-gray-500 fw-bold fs-8 text-uppercase gs-0">
                                        <th>Desarrollo</th>
                                        <th>Ubicacion</th>
                                        <th>Precio desde</th>
                                        <th>Fecha entrega</th>
                                        <th>M2 construccion</th>
                                        <th>M2 superficie</th>
                                        <th>Frente x fondo</th>
                                        <th>No. recamaras</th>
                                        <th>No. banos</th>
                                        <th>Medio banos</th>
                                        <th>No. estacionamientos</th>
                                        <th>Niveles</th>
                                        <th>Tipo creditos</th>
                                        <th>Estado</th>
                                        <th>Comision</th>
                                        <th>Bono asesor</th>
                                        <th>Disponibilidad</th>
                                        <th>Enganche</th>
                                        <th>Mensualidad</th>
                                        <th>Precio/m2</th>
                                        <th>Documentos</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-700">
                                    @forelse ($developments as $development)
                                        @php
                                            $fullBathrooms = $detail($development, 'full_bathrooms', $detail($development, 'bathrooms'));
                                            $documentsCount = $development->documentFolders->sum(fn ($folder) => $folder->files->count());
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    @if ($development->displayImageUrl())
                                                        <img src="{{ $development->displayImageUrl() }}" alt="{{ $development->name }}" class="advisor-dev-thumb">
                                                    @else
                                                        <div class="advisor-dev-thumb bg-light-primary d-flex align-items-center justify-content-center">
                                                            <i class="ki-outline ki-home-2 fs-2 text-primary"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <a href="{{ route('admin.developments.show', $development) }}" class="fw-bold text-gray-900 text-hover-primary">
                                                            {{ $development->name }}
                                                        </a>
                                                        <div class="text-muted fs-8">{{ $development->developerName() }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $development->zone }}<div class="text-muted fs-8">{{ $development->city }}</div></td>
                                            <td class="fw-bold text-gray-900">${{ number_format((float) $development->price_from, 0) }}</td>
                                            <td>{{ $development->delivery_date?->format('M Y') }}</td>
                                            <td>{{ $detail($development, 'construction_m2') !== '-' ? $detail($development, 'construction_m2') . ' m2' : '-' }}</td>
                                            <td>{{ $detail($development, 'land_m2') !== '-' ? $detail($development, 'land_m2') . ' m2' : '-' }}</td>
                                            <td>{{ $detail($development, 'front_m') !== '-' && $detail($development, 'depth_m') !== '-' ? $detail($development, 'front_m') . ' x ' . $detail($development, 'depth_m') : '-' }}</td>
                                            <td>{{ $detail($development, 'bedrooms') }}</td>
                                            <td>{{ $fullBathrooms }}</td>
                                            <td>{{ $detail($development, 'half_bathrooms') }}</td>
                                            <td>{{ $detail($development, 'parking_spaces') }}</td>
                                            <td>{{ $detail($development, 'levels', $detail($development, 'building_floors')) }}</td>
                                            <td class="mw-175px">{{ $development->payment_methods }}</td>
                                            <td>
                                                <span class="badge {{ $statusClasses[$development->status] ?? 'badge-light' }}">
                                                    {{ $statuses[$development->status] ?? $development->status }}
                                                </span>
                                            </td>
                                            <td class="text-success fw-bold">{{ number_format((float) $development->commission_percentage, 1) }}%</td>
                                            <td>{{ $development->advisor_bonus ? '$' . number_format((float) $development->advisor_bonus, 0) : '-' }}</td>
                                            <td>{{ $development->availability }}</td>
                                            <td>${{ number_format((float) $development->down_payment, 0) }}</td>
                                            <td>${{ number_format((float) $development->monthly_payments, 0) }}</td>
                                            <td>${{ number_format((float) $development->price_per_m2, 0) }}</td>
                                            <td>
                                                <span class="badge badge-light-primary">{{ $documentsCount }}</span>
                                            </td>
                                            <td class="text-end">
                                                <div class="d-flex justify-content-end gap-2">
                                                    <a href="{{ route('admin.developments.show', $development) }}" class="btn btn-sm btn-light" title="Ver">
                                                        <i class="ki-outline ki-eye fs-3"></i>
                                                        Ver
                                                    </a>
                                                    <a href="{{ route('admin.developments.documents.index', $development) }}" class="btn btn-sm btn-primary btn-icon" title="Documentos">
                                                        <i class="ki-outline ki-folder fs-3"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="22" class="text-center py-12">
                                                <i class="ki-outline ki-magnifier fs-3x text-gray-400 mb-4"></i>
                                                <div class="fw-bold fs-4 text-gray-900">Sin desarrollos encontrados.</div>
                                                <div class="text-muted fw-semibold">Ajusta los filtros para ampliar la busqueda.</div>
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
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var citySelect = document.querySelector('[data-city-filter]');
            var zoneSelect = document.querySelector('[data-zone-filter]');
            var table = document.querySelector('[data-advisor-catalog-table]');
            var search = document.querySelector('[data-advisor-table-search]');

            function selectedCities() {
                return Array.prototype.slice.call(citySelect?.selectedOptions || []).map(function (option) {
                    return option.value;
                });
            }

            function syncZones() {
                if (!citySelect || !zoneSelect) {
                    return;
                }

                var cities = selectedCities();
                var hasCities = cities.length > 0;

                Array.prototype.slice.call(zoneSelect.options).forEach(function (option) {
                    var visible = !hasCities || cities.indexOf(option.dataset.city) !== -1;
                    option.hidden = !visible;

                    if (!visible) {
                        option.selected = false;
                    }
                });

                if (window.jQuery && jQuery.fn.select2) {
                    jQuery(zoneSelect).trigger('change.select2');
                }
            }

            if (citySelect) {
                citySelect.addEventListener('change', syncZones);
                if (window.jQuery) {
                    jQuery(citySelect).on('select2:select select2:unselect', syncZones);
                }
                syncZones();
            }

            search?.addEventListener('input', function () {
                var needle = search.value.trim().toLowerCase();

                table?.querySelectorAll('tbody tr').forEach(function (row) {
                    row.style.display = row.textContent.toLowerCase().includes(needle) ? '' : 'none';
                });
            });
        });
    </script>
@endpush
