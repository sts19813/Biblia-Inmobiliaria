@php
    $details = old('property_details', $development->property_details ?? []);
    $selectedType = old('property_type', $development->property_type ?: 'casa');
    $selectedStatus = old('status', $development->status ?: 'preventa');
    $amenitiesText = old('amenities', implode(PHP_EOL, $development->amenities ?? []));
    $detailValue = fn (string $key) => old('property_details.' . $key, $details[$key] ?? '');

    $typeIcons = [
        'casa' => 'ki-home-2',
        'departamento' => 'ki-bank',
        'terreno' => 'ki-map',
        'playa' => 'ki-picture',
        'locales' => 'ki-shop',
        'oficinas' => 'ki-briefcase',
        'consultorios' => 'ki-heart',
        'bodegas' => 'ki-parcel',
    ];

    $yesNoOptions = ['si' => 'Si', 'no' => 'No'];
    $orientationOptions = [
        'norte' => 'Norte',
        'sur' => 'Sur',
        'oriente' => 'Oriente',
        'poniente' => 'Poniente',
    ];

    $sections = [
        'casa' => [
            'title' => 'Casa',
            'fields' => [
                ['name' => 'construction_m2', 'label' => 'm2 de construccion', 'type' => 'number', 'required' => true, 'step' => '0.01'],
                ['name' => 'land_m2', 'label' => 'm2 de terreno', 'type' => 'number', 'required' => true, 'step' => '0.01'],
                ['name' => 'front_m', 'label' => 'Frente (m)', 'type' => 'number', 'step' => '0.01'],
                ['name' => 'depth_m', 'label' => 'Fondo (m)', 'type' => 'number', 'step' => '0.01'],
                ['name' => 'levels', 'label' => 'Niveles', 'type' => 'number', 'required' => true, 'step' => '1'],
                ['name' => 'bedrooms', 'label' => 'Recamaras', 'type' => 'number', 'required' => true, 'step' => '1'],
                ['name' => 'full_bathrooms', 'label' => 'Banos completos', 'type' => 'number', 'required' => true, 'step' => '1'],
                ['name' => 'half_bathrooms', 'label' => 'Medios banos', 'type' => 'number', 'step' => '1'],
                ['name' => 'parking_spaces', 'label' => 'Estacionamientos', 'type' => 'number', 'required' => true, 'step' => '1'],
                ['name' => 'ground_floor_bedroom', 'label' => 'Recamara en planta baja', 'type' => 'select', 'options' => $yesNoOptions],
                ['name' => 'street_type', 'label' => 'Privada o pie de calle', 'type' => 'select', 'options' => ['privada' => 'Privada', 'pie_de_calle' => 'Pie de calle']],
                ['name' => 'orientation', 'label' => 'Orientacion', 'type' => 'select', 'options' => $orientationOptions],
                ['name' => 'service_room', 'label' => 'Cuarto de servicio', 'type' => 'select', 'options' => $yesNoOptions],
                ['name' => 'pool', 'label' => 'Alberca', 'type' => 'select', 'options' => $yesNoOptions],
                ['name' => 'family_room', 'label' => 'Family room', 'type' => 'select', 'options' => $yesNoOptions],
                ['name' => 'solar_panel_preparation', 'label' => 'Preparacion de paneles solares', 'type' => 'select', 'options' => $yesNoOptions],
                ['name' => 'ev_charger_preparation', 'label' => 'Preparacion cargador de auto', 'type' => 'select', 'options' => $yesNoOptions],
                ['name' => 'equipment', 'label' => 'Equipamiento', 'type' => 'textarea', 'cols' => 'col-12'],
            ],
        ],
        'departamento' => [
            'title' => 'Departamento',
            'fields' => [
                ['name' => 'construction_m2', 'label' => 'm2 de construccion', 'type' => 'number', 'required' => true, 'step' => '0.01'],
                ['name' => 'floor_level', 'label' => 'Nivel / piso', 'type' => 'number', 'step' => '1'],
                ['name' => 'bedrooms', 'label' => 'Recamaras', 'type' => 'number', 'required' => true, 'step' => '1'],
                ['name' => 'full_bathrooms', 'label' => 'Banos completos', 'type' => 'number', 'required' => true, 'step' => '1'],
                ['name' => 'half_bathrooms', 'label' => 'Medios banos', 'type' => 'number', 'step' => '1'],
                ['name' => 'parking_spaces', 'label' => 'Estacionamientos', 'type' => 'number', 'required' => true, 'step' => '1'],
                ['name' => 'storage', 'label' => 'Bodega / storage', 'type' => 'select', 'options' => $yesNoOptions],
                ['name' => 'security_24_7', 'label' => 'Seguridad 24/7', 'type' => 'select', 'options' => $yesNoOptions],
                ['name' => 'orientation', 'label' => 'Orientacion', 'type' => 'select', 'options' => $orientationOptions],
                ['name' => 'balcony', 'label' => 'Balcon', 'type' => 'select', 'options' => $yesNoOptions],
                ['name' => 'building_floors', 'label' => 'Cantidad de pisos', 'type' => 'number', 'step' => '1'],
                ['name' => 'elevator', 'label' => 'Elevador', 'type' => 'select', 'options' => $yesNoOptions],
                ['name' => 'elevators_count', 'label' => 'No. de elevadores', 'type' => 'number', 'step' => '1'],
                ['name' => 'pool', 'label' => 'Alberca', 'type' => 'select', 'options' => $yesNoOptions],
                ['name' => 'trash_chute', 'label' => 'Shute basura', 'type' => 'select', 'options' => $yesNoOptions],
                ['name' => 'covered_parking', 'label' => 'Estacionamiento techado', 'type' => 'select', 'options' => $yesNoOptions],
                ['name' => 'solar_panel_preparation', 'label' => 'Preparacion de paneles solares', 'type' => 'select', 'options' => $yesNoOptions],
                ['name' => 'ev_charger_preparation', 'label' => 'Preparacion cargador de auto', 'type' => 'select', 'options' => $yesNoOptions],
                ['name' => 'equipment', 'label' => 'Equipamiento', 'type' => 'textarea', 'cols' => 'col-12'],
            ],
        ],
        'playa' => [
            'title' => 'Playa',
            'fields' => [
                ['name' => 'construction_m2', 'label' => 'm2 de construccion', 'type' => 'number', 'required' => true, 'step' => '0.01'],
                ['name' => 'bedrooms', 'label' => 'Recamaras', 'type' => 'number', 'required' => true, 'step' => '1'],
                ['name' => 'full_bathrooms', 'label' => 'Banos completos', 'type' => 'number', 'required' => true, 'step' => '1'],
                ['name' => 'half_bathrooms', 'label' => 'Medios banos', 'type' => 'number', 'step' => '1'],
                ['name' => 'parking_spaces', 'label' => 'Estacionamientos', 'type' => 'number', 'step' => '1'],
                ['name' => 'ocean_view', 'label' => 'Vista al mar', 'type' => 'select', 'options' => ['frontal' => 'Frontal', 'lateral' => 'Lateral', 'sin_vista' => 'Sin vista']],
                ['name' => 'vacation_rental_program', 'label' => 'Programa renta vacacional', 'type' => 'select', 'options' => $yesNoOptions],
                ['name' => 'estimated_yield', 'label' => 'Rendimiento estimado (%)', 'type' => 'number', 'step' => '0.01'],
                ['name' => 'primary_bedroom_ocean_view', 'label' => 'Recamara principal con vista al mar', 'type' => 'select', 'options' => $yesNoOptions],
                ['name' => 'elevator', 'label' => 'Elevador', 'type' => 'select', 'options' => $yesNoOptions],
                ['name' => 'rooftop', 'label' => 'Rooftop', 'type' => 'select', 'options' => $yesNoOptions],
                ['name' => 'water_supply', 'label' => 'Agua potable o pipa', 'type' => 'select', 'options' => ['agua_potable' => 'Agua potable', 'pipa' => 'Pipa de agua', 'mixto' => 'Mixto']],
                ['name' => 'sea_access', 'label' => 'Acceso al mar', 'type' => 'select', 'options' => ['primera_fila' => 'Primera fila', 'segunda_fila' => 'Segunda fila', 'tercera_fila' => 'Tercera fila', 'posterior' => 'Posterior']],
                ['name' => 'service_room', 'label' => 'Cuarto de servicio', 'type' => 'select', 'options' => $yesNoOptions],
                ['name' => 'equipment', 'label' => 'Equipamiento', 'type' => 'textarea', 'cols' => 'col-12'],
            ],
        ],
        'terreno' => [
            'title' => 'Terreno',
            'fields' => [
                ['name' => 'land_m2', 'label' => 'm2 de terreno', 'type' => 'number', 'required' => true, 'step' => '0.01'],
                ['name' => 'front_m', 'label' => 'Frente (m)', 'type' => 'number', 'required' => true, 'step' => '0.01'],
                ['name' => 'depth_m', 'label' => 'Fondo (m)', 'type' => 'number', 'required' => true, 'step' => '0.01'],
                ['name' => 'land_use', 'label' => 'Uso de suelo', 'type' => 'select', 'required' => true, 'options' => ['residencial' => 'Residencial', 'mixto' => 'Mixto', 'comercial' => 'Comercial']],
                ['name' => 'street_type', 'label' => 'Privada o pie de calle', 'type' => 'select', 'options' => ['privada' => 'Privada', 'pie_de_calle' => 'Pie de calle']],
                ['name' => 'orientation', 'label' => 'Orientacion', 'type' => 'select', 'options' => $orientationOptions],
                ['name' => 'available_services', 'label' => 'Servicios disponibles', 'type' => 'textarea', 'cols' => 'col-12'],
                ['name' => 'construction_restrictions', 'label' => 'Restricciones de construccion', 'type' => 'textarea', 'cols' => 'col-12'],
            ],
        ],
        'comercial' => [
            'title' => 'Comercial',
            'applies_to' => ['locales', 'oficinas', 'consultorios', 'bodegas'],
            'fields' => [
                ['name' => 'construction_m2', 'label' => 'm2 de construccion', 'type' => 'number', 'required' => true, 'step' => '0.01'],
                ['name' => 'front_m', 'label' => 'Frente (m)', 'type' => 'number', 'step' => '0.01'],
                ['name' => 'depth_m', 'label' => 'Fondo (m)', 'type' => 'number', 'step' => '0.01'],
                ['name' => 'bathrooms', 'label' => 'Banos', 'type' => 'number', 'required' => true, 'step' => '1'],
                ['name' => 'parking_spaces', 'label' => 'Estacionamientos', 'type' => 'number', 'step' => '1'],
                ['name' => 'permitted_use', 'label' => 'Uso permitido', 'type' => 'select', 'required' => true, 'options' => ['comercial' => 'Comercial', 'oficinas' => 'Oficinas', 'mixto' => 'Mixto']],
                ['name' => 'rent_option', 'label' => 'Opcion de renta', 'type' => 'select', 'options' => $yesNoOptions],
                ['name' => 'delivery_condition', 'label' => 'Entrega', 'type' => 'select', 'options' => ['obra_gris' => 'Obra gris', 'fachada_cristal' => 'Fachada de cristal', 'equipado' => 'Equipado']],
                ['name' => 'building_floors', 'label' => 'Cantidad de pisos', 'type' => 'number', 'step' => '1'],
                ['name' => 'elevator', 'label' => 'Elevador', 'type' => 'select', 'options' => $yesNoOptions],
                ['name' => 'elevators_count', 'label' => 'No. de elevadores', 'type' => 'number', 'step' => '1'],
                ['name' => 'orientation', 'label' => 'Orientacion', 'type' => 'select', 'options' => $orientationOptions],
            ],
        ],
    ];
@endphp

@push('styles')
    <style>
        .development-type-option {
            border: 1px solid var(--bs-gray-300);
            min-height: 78px;
            transition: border-color .2s ease, background-color .2s ease, color .2s ease;
        }

        .btn-check:checked + .development-type-option {
            background-color: var(--bs-primary-light);
            border-color: var(--bs-primary);
            color: var(--bs-primary);
        }

        .development-sticky {
            top: 100px;
        }
    </style>
@endpush

<div class="row g-8">
    <div class="col-xl-8">
        <div class="card card-flush mb-8">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="fw-bold mb-0">Datos del desarrollo</h3>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-6">
                    <div class="col-md-6">
                        <label class="required form-label">Nombre del desarrollo</label>
                        <input type="text" name="name" value="{{ old('name', $development->name) }}"
                            class="form-control form-control-solid @error('name') is-invalid @enderror"
                            placeholder="Nombre comercial del proyecto" required>
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="required form-label">Desarrollador</label>
                        <input type="text" name="developer" value="{{ old('developer', $development->developer) }}"
                            class="form-control form-control-solid @error('developer') is-invalid @enderror"
                            placeholder="Empresa o grupo constructor" required>
                        @error('developer')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="required form-label">Ciudad</label>
                        <input type="text" name="city" value="{{ old('city', $development->city) }}"
                            class="form-control form-control-solid @error('city') is-invalid @enderror" required>
                        @error('city')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="required form-label">Zona / colonia</label>
                        <input type="text" name="zone" value="{{ old('zone', $development->zone) }}"
                            class="form-control form-control-solid @error('zone') is-invalid @enderror" required>
                        @error('zone')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="required form-label">Ubicacion en mapa</label>
                        <div class="input-group input-group-solid">
                            <span class="input-group-text">
                                <i class="ki-outline ki-geolocation fs-2"></i>
                            </span>
                            <input type="url" name="map_url" value="{{ old('map_url', $development->map_url) }}"
                                class="form-control form-control-solid @error('map_url') is-invalid @enderror"
                                placeholder="Link de Google Maps" required>
                        </div>
                        @error('map_url')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="required form-label">Tipo de propiedad</label>
                        <div class="row g-3">
                            @foreach ($propertyTypes as $value => $label)
                                <div class="col-sm-6 col-lg">
                                    <input type="radio" class="btn-check" name="property_type"
                                        id="property_type_{{ $value }}" value="{{ $value }}"
                                        @checked($selectedType === $value) required>
                                    <label class="development-type-option btn btn-outline btn-outline-dashed d-flex align-items-center justify-content-start w-100 text-start"
                                        for="property_type_{{ $value }}">
                                        <i class="ki-outline {{ $typeIcons[$value] ?? 'ki-category' }} fs-2x me-3"></i>
                                        <span class="fw-bold">{{ $label }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('property_type')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-flush mb-8">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="fw-bold mb-0">Comercializacion</h3>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-6">
                    <div class="col-md-4">
                        <label class="required form-label">Precio desde</label>
                        <input type="number" name="price_from" value="{{ old('price_from', $development->price_from) }}"
                            class="form-control form-control-solid @error('price_from') is-invalid @enderror"
                            min="0" step="0.01" required>
                        @error('price_from')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="required form-label">Precio por m2</label>
                        <input type="number" name="price_per_m2"
                            value="{{ old('price_per_m2', $development->price_per_m2) }}"
                            class="form-control form-control-solid @error('price_per_m2') is-invalid @enderror"
                            min="0" step="0.01" required>
                        @error('price_per_m2')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="required form-label">Enganche</label>
                        <input type="number" name="down_payment"
                            value="{{ old('down_payment', $development->down_payment) }}"
                            class="form-control form-control-solid @error('down_payment') is-invalid @enderror"
                            min="0" step="0.01" required>
                        @error('down_payment')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="required form-label">Mensualidades</label>
                        <input type="number" name="monthly_payments"
                            value="{{ old('monthly_payments', $development->monthly_payments) }}"
                            class="form-control form-control-solid @error('monthly_payments') is-invalid @enderror"
                            min="0" step="0.01" required>
                        @error('monthly_payments')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="required form-label">Comision (%)</label>
                        <input type="number" name="commission_percentage"
                            value="{{ old('commission_percentage', $development->commission_percentage) }}"
                            class="form-control form-control-solid @error('commission_percentage') is-invalid @enderror"
                            min="0" max="100" step="0.01" required>
                        @error('commission_percentage')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Bono asesor</label>
                        <input type="number" name="advisor_bonus"
                            value="{{ old('advisor_bonus', $development->advisor_bonus) }}"
                            class="form-control form-control-solid @error('advisor_bonus') is-invalid @enderror"
                            min="0" step="0.01">
                        @error('advisor_bonus')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="required form-label">Fecha de entrega</label>
                        <input type="date" name="delivery_date"
                            value="{{ old('delivery_date', optional($development->delivery_date)->format('Y-m-d')) }}"
                            class="form-control form-control-solid @error('delivery_date') is-invalid @enderror"
                            required>
                        @error('delivery_date')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="required form-label">Estado</label>
                        <select name="status"
                            class="form-select form-select-solid @error('status') is-invalid @enderror"
                            data-control="select2" data-hide-search="true" required>
                            @foreach ($statuses as $value => $label)
                                <option value="{{ $value }}" @selected($selectedStatus === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="required form-label">Formas de pago</label>
                        <textarea name="payment_methods" rows="4"
                            class="form-control form-control-solid @error('payment_methods') is-invalid @enderror"
                            placeholder="Contado, credito, Infonavit, Cofinavit..." required>{{ old('payment_methods', $development->payment_methods) }}</textarea>
                        @error('payment_methods')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Promociones vigentes</label>
                        <textarea name="active_promotions" rows="4"
                            class="form-control form-control-solid @error('active_promotions') is-invalid @enderror">{{ old('active_promotions', $development->active_promotions) }}</textarea>
                        @error('active_promotions')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-flush">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="fw-bold mb-0">Datos del producto</h3>
                </div>
            </div>
            <div class="card-body">
                @foreach ($sections as $type => $section)
                    @php
                        $appliesTo = $section['applies_to'] ?? [$type];
                    @endphp
                    <div data-development-section="{{ implode(' ', $appliesTo) }}" @class(['d-none' => ! in_array($selectedType, $appliesTo, true)])>
                        <div class="d-flex align-items-center mb-6">
                            <div class="symbol symbol-45px me-4">
                                <div class="symbol-label bg-light-primary">
                                    <i class="ki-outline {{ $typeIcons[$type] ?? 'ki-category' }} fs-2 text-primary"></i>
                                </div>
                            </div>
                            <div>
                                <div class="fw-bold fs-4 text-gray-900">{{ $section['title'] }}</div>
                                <div class="text-muted fw-semibold fs-7">Campos especificos del tipo seleccionado</div>
                            </div>
                        </div>

                        <div class="row g-6">
                            @foreach ($section['fields'] as $field)
                                @php
                                    $fieldName = 'property_details[' . $field['name'] . ']';
                                    $fieldId = 'property_details_' . $type . '_' . $field['name'];
                                    $isRequired = $field['required'] ?? false;
                                    $colClass = $field['cols'] ?? 'col-md-6 col-xl-4';
                                @endphp

                                <div class="{{ $colClass }}">
                                    <label @class(['form-label', 'required' => $isRequired]) for="{{ $fieldId }}">
                                        {{ $field['label'] }}
                                    </label>

                                    @if ($field['type'] === 'select')
                                        <select id="{{ $fieldId }}" name="{{ $fieldName }}"
                                            class="form-select form-select-solid @error('property_details.' . $field['name']) is-invalid @enderror"
                                            data-detail-input data-detail-required="{{ $isRequired ? '1' : '0' }}">
                                            <option value="">Seleccionar</option>
                                            @foreach ($field['options'] as $optionValue => $optionLabel)
                                                <option value="{{ $optionValue }}" @selected($detailValue($field['name']) === $optionValue)>
                                                    {{ $optionLabel }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @elseif ($field['type'] === 'textarea')
                                        <textarea id="{{ $fieldId }}" name="{{ $fieldName }}" rows="3"
                                            class="form-control form-control-solid @error('property_details.' . $field['name']) is-invalid @enderror"
                                            data-detail-input data-detail-required="{{ $isRequired ? '1' : '0' }}">{{ $detailValue($field['name']) }}</textarea>
                                    @else
                                        <input id="{{ $fieldId }}" type="{{ $field['type'] }}" name="{{ $fieldName }}"
                                            value="{{ $detailValue($field['name']) }}"
                                            class="form-control form-control-solid @error('property_details.' . $field['name']) is-invalid @enderror"
                                            min="0" step="{{ $field['step'] ?? '1' }}"
                                            data-detail-input data-detail-required="{{ $isRequired ? '1' : '0' }}">
                                    @endif

                                    @error('property_details.' . $field['name'])
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="position-xl-sticky development-sticky">
            <div class="card card-flush mb-8">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="fw-bold mb-0">Inventario</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-6">
                        <div class="col-12">
                            <label class="required form-label">Disponibilidad</label>
                            <input type="text" name="availability"
                                value="{{ old('availability', $development->availability) }}"
                                class="form-control form-control-solid @error('availability') is-invalid @enderror"
                                placeholder="Unidades o lotes disponibles" required>
                            @error('availability')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Numero de unidades del proyecto</label>
                            <input type="number" name="total_units"
                                value="{{ old('total_units', $development->total_units) }}"
                                class="form-control form-control-solid @error('total_units') is-invalid @enderror"
                                min="0" step="1">
                            @error('total_units')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Cuota de mantenimiento</label>
                            <input type="number" name="maintenance_fee"
                                value="{{ old('maintenance_fee', $development->maintenance_fee) }}"
                                class="form-control form-control-solid @error('maintenance_fee') is-invalid @enderror"
                                min="0" step="0.01">
                            @error('maintenance_fee')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="required form-label">Amenidades</label>
                            <textarea name="amenities" rows="8"
                                class="form-control form-control-solid @error('amenities') is-invalid @enderror"
                                placeholder="Alberca&#10;Gimnasio&#10;Caseta de vigilancia" required>{{ $amenitiesText }}</textarea>
                            @error('amenities')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-flush">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-6">
                        <div class="symbol symbol-45px me-4">
                            <div class="symbol-label bg-light-success">
                                <i class="ki-outline ki-check fs-2 text-success"></i>
                            </div>
                        </div>
                        <div>
                            <div class="fw-bold fs-5 text-gray-900">Listo para publicar</div>
                            <div class="text-muted fw-semibold fs-7">Se guardara como desarrollo activo.</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3">
                        <a href="{{ route('admin.developments.index') }}" class="btn btn-light">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ki-outline ki-check fs-2"></i>
                            Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var form = document.querySelector('[data-development-form]');

            if (!form) {
                return;
            }

            var sections = form.querySelectorAll('[data-development-section]');
            var typeInputs = form.querySelectorAll('input[name="property_type"]');

            function setActiveType(type) {
                sections.forEach(function (section) {
                    var sectionTypes = section.getAttribute('data-development-section').split(' ');
                    var isActive = sectionTypes.indexOf(type) !== -1;
                    section.classList.toggle('d-none', !isActive);

                    section.querySelectorAll('[data-detail-input]').forEach(function (input) {
                        input.disabled = !isActive;
                        input.required = isActive && input.getAttribute('data-detail-required') === '1';
                    });
                });
            }

            typeInputs.forEach(function (input) {
                input.addEventListener('change', function () {
                    setActiveType(input.value);
                });

                if (input.checked) {
                    setActiveType(input.value);
                }
            });
        });
    </script>
@endpush
