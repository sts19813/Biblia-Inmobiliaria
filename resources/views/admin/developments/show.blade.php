@extends('layouts.admin')

@php
    $statusClasses = [
        'preventa' => 'badge-light-warning',
        'obra_iniciada' => 'badge-light-primary',
        'entrega_inmediata' => 'badge-light-success',
    ];

    $formatValue = function ($value) {
        if (is_bool($value)) {
            return $value ? 'Si' : 'No';
        }

        if (is_array($value)) {
            return implode(', ', $value);
        }

        $normalized = (string) $value;

        return ucfirst(str_replace('_', ' ', $normalized));
    };

    $yesNoOptions = ['si' => 'Si', 'no' => 'No'];
    $detailOptions = [
        'ground_floor_bedroom' => $yesNoOptions,
        'street_type' => ['privada' => 'Privada', 'pie_de_calle' => 'Pie de calle'],
        'orientation' => [
            'norte' => 'Norte',
            'sur' => 'Sur',
            'oriente' => 'Oriente',
            'poniente' => 'Poniente',
        ],
        'service_room' => $yesNoOptions,
        'pool' => $yesNoOptions,
        'family_room' => $yesNoOptions,
        'solar_panel_preparation' => $yesNoOptions,
        'ev_charger_preparation' => $yesNoOptions,
        'storage' => $yesNoOptions,
        'security_24_7' => $yesNoOptions,
        'balcony' => $yesNoOptions,
        'elevator' => $yesNoOptions,
        'trash_chute' => $yesNoOptions,
        'covered_parking' => $yesNoOptions,
        'ocean_view' => ['frontal' => 'Frontal', 'lateral' => 'Lateral', 'sin_vista' => 'Sin vista'],
        'vacation_rental_program' => $yesNoOptions,
        'primary_bedroom_ocean_view' => $yesNoOptions,
        'rooftop' => $yesNoOptions,
        'water_supply' => ['agua_potable' => 'Agua potable', 'pipa' => 'Pipa de agua', 'mixto' => 'Mixto'],
        'sea_access' => [
            'primera_fila' => 'Primera fila',
            'segunda_fila' => 'Segunda fila',
            'tercera_fila' => 'Tercera fila',
            'posterior' => 'Posterior',
        ],
        'land_use' => ['residencial' => 'Residencial', 'mixto' => 'Mixto', 'comercial' => 'Comercial'],
        'permitted_use' => ['comercial' => 'Comercial', 'oficinas' => 'Oficinas', 'mixto' => 'Mixto'],
        'rent_option' => $yesNoOptions,
        'delivery_condition' => [
            'obra_gris' => 'Obra gris',
            'fachada_cristal' => 'Fachada de cristal',
            'equipado' => 'Equipado',
        ],
    ];
    $normalizeSelectValue = function ($value) {
        if (is_bool($value)) {
            return $value ? 'si' : 'no';
        }

        return str($value ?? '')
            ->lower()
            ->ascii()
            ->replace([' ', '-'], '_')
            ->value();
    };
    $formatDetailValue = function (string $key, $value) use ($detailOptions, $formatValue, $normalizeSelectValue) {
        $normalizedValue = $normalizeSelectValue($value);

        foreach ($detailOptions[$key] ?? [] as $optionValue => $optionLabel) {
            if (in_array($normalizedValue, [
                $normalizeSelectValue($optionValue),
                $normalizeSelectValue($optionLabel),
            ], true)) {
                return $optionLabel;
            }
        }

        return $formatValue($value);
    };
@endphp

@section('title', $development->name . ' | Biblia Inmobiliaria')

@section('toolbar')
    <div>
        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
            {{ $development->name }}
        </h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
            <li class="breadcrumb-item text-muted">
                <a href="{{ route('admin.developments.index') }}" class="text-muted text-hover-primary">Desarrollos</a>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-500 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">Detalle</li>
        </ul>
    </div>
    <div class="d-flex gap-3">
        <a href="{{ route('admin.developments.index') }}" class="btn btn-light">Volver</a>
        <a href="{{ route('admin.developments.edit', $development) }}" class="btn btn-primary">
            <i class="ki-outline ki-pencil fs-2"></i>
            Editar
        </a>
    </div>
@endsection

@push('styles')
    <style>
        .development-cover {
            min-height: 280px;
            background-size: cover;
            background-position: center;
        }

        .development-cover-empty {
            min-height: 280px;
        }

        .development-logo {
            width: 92px;
            height: 92px;
            object-fit: contain;
            border: 1px solid var(--bs-gray-200);
        }

        .development-description :where(p, ul, ol) {
            margin-bottom: .85rem;
        }
    </style>
@endpush

@section('content')
    <div class="row g-8">
        <div class="col-xl-8">
            <div class="card card-flush mb-8 overflow-hidden">
                @if ($development->coverImageUrl())
                    <div class="development-cover" style="background-image: url('{{ $development->coverImageUrl() }}');"></div>
                @else
                    <div class="development-cover-empty bg-light-primary d-flex align-items-center justify-content-center">
                        <i class="ki-outline ki-picture fs-4x text-primary"></i>
                    </div>
                @endif

                <div class="card-body">
                    <div class="d-flex flex-column flex-md-row gap-6 align-items-md-center">
                        @if ($development->logoUrl())
                            <img src="{{ $development->logoUrl() }}" alt="{{ $development->name }}"
                                class="development-logo rounded bg-white p-3">
                        @else
                            <div class="development-logo rounded bg-light d-flex align-items-center justify-content-center">
                                <i class="ki-outline ki-home-2 fs-2x text-gray-500"></i>
                            </div>
                        @endif

                        <div class="flex-grow-1">
                            <div class="d-flex flex-wrap align-items-center gap-3 mb-2">
                                <h2 class="fw-bold text-gray-900 mb-0">{{ $development->name }}</h2>
                                <span class="badge {{ $statusClasses[$development->status] ?? 'badge-light-info' }}">
                                    {{ $statuses[$development->status] ?? $development->status }}
                                </span>
                            </div>
                            <div class="text-muted fw-semibold">{{ $development->developerName() }}</div>
                            <div class="d-flex flex-wrap gap-4 mt-4 text-gray-700">
                                <span>
                                    <i class="ki-outline ki-geolocation fs-4 me-1"></i>
                                    {{ $development->city }}, {{ $development->zone }}
                                </span>
                                <span>
                                    <i class="ki-outline ki-category fs-4 me-1"></i>
                                    {{ $propertyTypes[$development->property_type] ?? $development->property_type }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-flush mb-8">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="fw-bold mb-0">Descripcion</h3>
                    </div>
                </div>
                <div class="card-body">
                    @if ($development->description)
                        <div class="development-description fs-6 text-gray-700">
                            {!! $development->description !!}
                        </div>
                    @else
                        <div class="text-muted fw-semibold">Sin descripcion capturada.</div>
                    @endif
                </div>
            </div>

            <div class="card card-flush">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="fw-bold mb-0">Datos del producto</h3>
                    </div>
                </div>
                <div class="card-body">
                    @if (! empty($productDetails) && ! empty($detailFields))
                        <div class="d-flex flex-column gap-8">
                            @foreach ($productDetails as $productIndex => $product)
                                <div>
                                    <div class="d-flex align-items-center mb-5">
                                        <div class="symbol symbol-40px me-3">
                                            <div class="symbol-label bg-light-primary">
                                                <i class="ki-outline ki-home-2 fs-2 text-primary"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="fw-bold fs-4 text-gray-900">
                                                {{ $product['product_name'] ?? 'Producto ' . ($productIndex + 1) }}
                                            </div>
                                            <div class="text-muted fw-semibold fs-8">Producto {{ $productIndex + 1 }}</div>
                                        </div>
                                    </div>

                                    <div class="row g-6">
                                        @foreach ($detailFields as $key)
                                            @continue($key === 'product_name')
                                            @php
                                                $hasValue = array_key_exists($key, $product)
                                                    && $product[$key] !== ''
                                                    && $product[$key] !== null;
                                                $value = $hasValue ? $product[$key] : null;
                                            @endphp
                                            <div class="col-md-6 col-xl-4">
                                                <div class="text-muted fw-semibold fs-7 mb-1">{{ $detailLabels[$key] ?? $formatValue($key) }}</div>
                                                <div @class(['fw-bold', 'text-gray-900' => $hasValue, 'text-muted' => ! $hasValue])>
                                                    {{ $hasValue ? $formatDetailValue($key, $value) : 'No capturado' }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-muted fw-semibold">Sin datos especificos capturados.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card card-flush mb-8">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="fw-bold mb-0">Comercializacion</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-5">
                        <div>
                            <div class="text-muted fw-semibold fs-7">Precio desde</div>
                            <div class="fw-bold fs-4 text-gray-900">${{ number_format((float) $development->price_from, 2) }}</div>
                        </div>
                        <div class="separator"></div>
                        <div class="row g-5">
                            <div class="col-6">
                                <div class="text-muted fw-semibold fs-7">Precio por m2</div>
                                <div class="fw-bold text-gray-900">${{ number_format((float) $development->price_per_m2, 2) }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted fw-semibold fs-7">Enganche</div>
                                <div class="fw-bold text-gray-900">${{ number_format((float) $development->down_payment, 2) }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted fw-semibold fs-7">Mensualidades</div>
                                <div class="fw-bold text-gray-900">${{ number_format((float) $development->monthly_payments, 2) }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted fw-semibold fs-7">Comision</div>
                                <div class="fw-bold text-gray-900">{{ number_format((float) $development->commission_percentage, 2) }}%</div>
                            </div>
                        </div>
                        <div class="separator"></div>
                        <div>
                            <div class="text-muted fw-semibold fs-7">Formas de pago</div>
                            <div class="fw-semibold text-gray-800">{!! nl2br(e($development->payment_methods)) !!}</div>
                        </div>
                        @if ($development->active_promotions)
                            <div>
                                <div class="text-muted fw-semibold fs-7">Promociones vigentes</div>
                                <div class="fw-semibold text-gray-800">{!! nl2br(e($development->active_promotions)) !!}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card card-flush mb-8">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="fw-bold mb-0">Inventario</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-5">
                        <div class="col-12">
                            <div class="text-muted fw-semibold fs-7">Disponibilidad</div>
                            <div class="fw-bold text-gray-900">{{ $development->availability }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted fw-semibold fs-7">Unidades</div>
                            <div class="fw-bold text-gray-900">{{ $development->total_units ?? 'No capturado' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted fw-semibold fs-7">Mantenimiento</div>
                            <div class="fw-bold text-gray-900">
                                {{ $development->maintenance_fee ? '$' . number_format((float) $development->maintenance_fee, 2) : 'No capturado' }}
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="text-muted fw-semibold fs-7 mb-2">Amenidades</div>
                            <div class="d-flex flex-wrap gap-2">
                                @forelse ($development->amenities ?? [] as $amenity)
                                    <span class="badge badge-light">{{ $amenity }}</span>
                                @empty
                                    <span class="text-muted fw-semibold">Sin amenidades capturadas.</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-flush">
                <div class="card-body">
                    <div class="d-flex flex-column gap-4">
                        <a href="{{ $development->map_url }}" target="_blank" rel="noopener" class="btn btn-light-primary w-100">
                            <i class="ki-outline ki-geolocation fs-2"></i>
                            Abrir ubicacion
                        </a>
                        <a href="{{ route('admin.developments.edit', $development) }}" class="btn btn-primary w-100">
                            <i class="ki-outline ki-pencil fs-2"></i>
                            Editar desarrollo
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
