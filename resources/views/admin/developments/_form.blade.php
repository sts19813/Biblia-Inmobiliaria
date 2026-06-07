@php
    $details = old('property_details', $development->property_details ?? []);
    $selectedType = old('property_type', $development->property_type ?: 'casa');
    $selectedStatus = old('status', $development->status ?: 'preventa');
    $amenitiesText = old('amenities', implode(PHP_EOL, $development->amenities ?? []));
    $detailValue = fn (string $key) => old('property_details.' . $key, $details[$key] ?? '');
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
    $isSelectedOption = function (string $key, string $optionValue, string $optionLabel) use ($detailValue, $normalizeSelectValue) {
        $currentValue = $normalizeSelectValue($detailValue($key));

        return $currentValue !== ''
            && in_array($currentValue, [
                $normalizeSelectValue($optionValue),
                $normalizeSelectValue($optionLabel),
            ], true);
    };
    $logoUrl = $development->exists ? $development->logoUrl() : null;
    $coverImageUrl = $development->exists ? $development->coverImageUrl() : null;

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

        .development-upload-zone {
            border: 2px dashed var(--bs-gray-300);
            border-radius: .75rem;
            min-height: 170px;
            cursor: pointer;
            transition: border-color .2s ease, background-color .2s ease, box-shadow .2s ease;
        }

        .development-upload-zone:hover,
        .development-upload-zone.is-dragging {
            background-color: var(--bs-primary-light);
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 .25rem rgba(47, 128, 237, .08);
        }

        .development-upload-zone img {
            display: none;
            max-height: 118px;
            max-width: 100%;
            object-fit: contain;
        }

        .development-upload-zone.has-preview img {
            display: block;
        }

        .development-upload-zone.has-preview [data-upload-empty] {
            display: none;
        }

        .tox-tinymce {
            border-color: var(--bs-gray-300) !important;
            border-radius: .75rem !important;
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
                    <h3 class="fw-bold mb-0">Imagenes</h3>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-6">
                    <div class="col-md-6">
                        <label class="form-label">Logo del Desarrollo</label>
                        <label class="development-upload-zone d-flex align-items-center justify-content-center text-center p-8 @if ($logoUrl) has-preview @endif @error('logo') border-danger @enderror"
                            data-upload-zone for="development_logo">
                            <input id="development_logo" type="file" name="logo" class="d-none"
                                accept="image/*,.svg,image/svg+xml" data-upload-input data-preview-target="development_logo_preview">
                            <img id="development_logo_preview" src="{{ $logoUrl ?: '' }}" alt="Logo del desarrollo">
                            <span data-upload-empty>
                                <i class="ki-outline ki-file-up fs-3x text-gray-500 d-block mb-4"></i>
                                <span class="fw-semibold fs-5 text-gray-700">Arrastra o haz clic para subir</span>
                                <span class="text-muted fs-7 d-block mt-2">JPG, PNG, WEBP, GIF o SVG</span>
                            </span>
                        </label>
                        @error('logo')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Imagen de Portada</label>
                        <label class="development-upload-zone d-flex align-items-center justify-content-center text-center p-8 @if ($coverImageUrl) has-preview @endif @error('cover_image') border-danger @enderror"
                            data-upload-zone for="development_cover_image">
                            <input id="development_cover_image" type="file" name="cover_image" class="d-none"
                                accept="image/*,.svg,image/svg+xml" data-upload-input data-preview-target="development_cover_image_preview">
                            <img id="development_cover_image_preview" src="{{ $coverImageUrl ?: '' }}" alt="Imagen de portada">
                            <span data-upload-empty>
                                <i class="ki-outline ki-file-up fs-3x text-gray-500 d-block mb-4"></i>
                                <span class="fw-semibold fs-5 text-gray-700">Arrastra o haz clic para subir</span>
                                <span class="text-muted fs-7 d-block mt-2">JPG, PNG, WEBP, GIF o SVG</span>
                            </span>
                        </label>
                        @error('cover_image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
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
                <label class="form-label">Descripcion del Proyecto</label>
                <textarea name="description" id="development_description" rows="10"
                    class="form-control form-control-solid @error('description') is-invalid @enderror"
                    placeholder="Describe las caracteristicas principales del desarrollo...">{{ old('description', $development->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
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
                        $isActiveSection = in_array($selectedType, $appliesTo, true);
                    @endphp
                    <div data-development-section="{{ implode(' ', $appliesTo) }}" @class(['d-none' => ! $isActiveSection])>
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
                                            data-detail-input data-detail-required="{{ $isRequired ? '1' : '0' }}"
                                            @disabled(! $isActiveSection) @required($isActiveSection && $isRequired)>
                                            <option value="">Seleccionar</option>
                                            @foreach ($field['options'] as $optionValue => $optionLabel)
                                                <option value="{{ $optionValue }}" @selected($isSelectedOption($field['name'], (string) $optionValue, (string) $optionLabel))>
                                                    {{ $optionLabel }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @elseif ($field['type'] === 'textarea')
                                        <textarea id="{{ $fieldId }}" name="{{ $fieldName }}" rows="3"
                                            class="form-control form-control-solid @error('property_details.' . $field['name']) is-invalid @enderror"
                                            data-detail-input data-detail-required="{{ $isRequired ? '1' : '0' }}"
                                            @disabled(! $isActiveSection) @required($isActiveSection && $isRequired)>{{ $detailValue($field['name']) }}</textarea>
                                    @else
                                        <input id="{{ $fieldId }}" type="{{ $field['type'] }}" name="{{ $fieldName }}"
                                            value="{{ $detailValue($field['name']) }}"
                                            class="form-control form-control-solid @error('property_details.' . $field['name']) is-invalid @enderror"
                                            min="0" step="{{ $field['step'] ?? '1' }}"
                                            data-detail-input data-detail-required="{{ $isRequired ? '1' : '0' }}"
                                            @disabled(! $isActiveSection) @required($isActiveSection && $isRequired)>
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
    <script src="{{ asset('/metronic/assets/plugins/custom/tinymce/tinymce.bundle.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var form = document.querySelector('[data-development-form]');

            if (!form) {
                return;
            }

            var sections = form.querySelectorAll('[data-development-section]');
            var typeInputs = form.querySelectorAll('input[name="property_type"]');
            var uploadZones = form.querySelectorAll('[data-upload-zone]');

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

            uploadZones.forEach(function (zone) {
                var input = zone.querySelector('[data-upload-input]');
                var preview = document.getElementById(input.getAttribute('data-preview-target'));

                function renderPreview(file) {
                    if (!file || !preview) {
                        return;
                    }

                    preview.src = URL.createObjectURL(file);
                    zone.classList.add('has-preview');
                }

                input.addEventListener('change', function () {
                    renderPreview(input.files[0]);
                });

                ['dragenter', 'dragover'].forEach(function (eventName) {
                    zone.addEventListener(eventName, function (event) {
                        event.preventDefault();
                        zone.classList.add('is-dragging');
                    });
                });

                ['dragleave', 'drop'].forEach(function (eventName) {
                    zone.addEventListener(eventName, function (event) {
                        event.preventDefault();
                        zone.classList.remove('is-dragging');
                    });
                });

                zone.addEventListener('drop', function (event) {
                    var files = event.dataTransfer.files;

                    if (!files.length) {
                        return;
                    }

                    input.files = files;
                    renderPreview(files[0]);
                });
            });

            if (typeof tinymce !== 'undefined') {
                tinymce.init({
                    selector: '#development_description',
                    height: 340,
                    menubar: false,
                    branding: false,
                    plugins: 'lists link table code autoresize',
                    toolbar: 'undo redo | blocks | bold italic underline | bullist numlist | alignleft aligncenter alignright | link table | removeformat code',
                    content_style: 'body { font-family: Inter, Arial, sans-serif; font-size: 14px; }',
                    skin: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'oxide-dark' : 'oxide',
                    content_css: false
                });

                form.addEventListener('submit', function () {
                    tinymce.triggerSave();
                });
            }
        });
    </script>
@endpush
