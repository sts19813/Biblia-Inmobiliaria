<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\DeveloperProfile;
use App\Models\Development;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DevelopmentController extends Controller
{
    public const PROPERTY_TYPES = [
        'casa' => 'Casa',
        'departamento' => 'Departamento',
        'terreno' => 'Terreno',
        'playa' => 'Playa',
        'locales' => 'Locales',
        'oficinas' => 'Oficinas',
        'consultorios' => 'Consultorios',
        'bodegas' => 'Bodegas',
    ];

    public const COMMERCIAL_PROPERTY_TYPES = ['locales', 'oficinas', 'consultorios', 'bodegas'];

    public const STATUSES = [
        'preventa' => 'Preventa',
        'obra_iniciada' => 'Obra iniciada',
        'entrega_inmediata' => 'Entrega inmediata',
    ];

    public const DETAIL_LABELS = [
        'product_name' => 'Nombre del producto',
        'construction_m2' => 'm2 de construccion',
        'land_m2' => 'm2 de terreno',
        'front_m' => 'Frente (m)',
        'depth_m' => 'Fondo (m)',
        'levels' => 'Niveles',
        'bedrooms' => 'Recamaras',
        'full_bathrooms' => 'Banos completos',
        'half_bathrooms' => 'Medios banos',
        'parking_spaces' => 'Estacionamientos',
        'ground_floor_bedroom' => 'Recamara en planta baja',
        'street_type' => 'Privada o pie de calle',
        'equipment' => 'Equipamiento',
        'orientation' => 'Orientacion',
        'service_room' => 'Cuarto de servicio',
        'pool' => 'Alberca',
        'family_room' => 'Family room',
        'solar_panel_preparation' => 'Preparacion de paneles solares',
        'ev_charger_preparation' => 'Preparacion cargador de auto',
        'floor_level' => 'Nivel / piso',
        'storage' => 'Bodega / storage',
        'security_24_7' => 'Seguridad 24/7',
        'balcony' => 'Balcon',
        'building_floors' => 'Cantidad de pisos',
        'elevator' => 'Elevador',
        'elevators_count' => 'No. de elevadores',
        'trash_chute' => 'Shute basura',
        'covered_parking' => 'Estacionamiento techado',
        'ocean_view' => 'Vista al mar',
        'vacation_rental_program' => 'Programa renta vacacional',
        'estimated_yield' => 'Rendimiento estimado (%)',
        'primary_bedroom_ocean_view' => 'Recamara principal con vista al mar',
        'rooftop' => 'Rooftop',
        'water_supply' => 'Agua potable o pipa',
        'sea_access' => 'Acceso al mar',
        'land_use' => 'Uso de suelo',
        'available_services' => 'Servicios disponibles',
        'construction_restrictions' => 'Restricciones de construccion',
        'bathrooms' => 'Banos',
        'permitted_use' => 'Uso permitido',
        'rent_option' => 'Opcion de renta',
        'delivery_condition' => 'Entrega',
    ];

    public const DETAIL_FIELD_GROUPS = [
        'casa' => [
            'product_name',
            'construction_m2', 'land_m2', 'front_m', 'depth_m', 'levels', 'bedrooms', 'full_bathrooms',
            'half_bathrooms', 'parking_spaces', 'ground_floor_bedroom', 'street_type', 'equipment',
            'orientation', 'service_room', 'pool', 'family_room', 'solar_panel_preparation',
            'ev_charger_preparation',
        ],
        'departamento' => [
            'product_name',
            'construction_m2', 'floor_level', 'bedrooms', 'full_bathrooms', 'half_bathrooms',
            'parking_spaces', 'storage', 'security_24_7', 'orientation', 'equipment', 'balcony',
            'building_floors', 'elevator', 'elevators_count', 'pool', 'trash_chute',
            'covered_parking', 'solar_panel_preparation', 'ev_charger_preparation',
        ],
        'playa' => [
            'product_name',
            'construction_m2', 'bedrooms', 'full_bathrooms', 'half_bathrooms', 'parking_spaces',
            'ocean_view', 'vacation_rental_program', 'estimated_yield', 'primary_bedroom_ocean_view',
            'elevator', 'rooftop', 'water_supply', 'sea_access', 'service_room', 'equipment',
        ],
        'terreno' => [
            'product_name',
            'land_m2', 'front_m', 'depth_m', 'land_use', 'available_services',
            'construction_restrictions', 'street_type', 'orientation',
        ],
        'comercial' => [
            'product_name',
            'construction_m2', 'front_m', 'depth_m', 'bathrooms', 'parking_spaces', 'permitted_use',
            'rent_option', 'delivery_condition', 'building_floors', 'elevator', 'elevators_count',
            'orientation',
        ],
    ];

    public function index()
    {
        return view('admin.developments.index', [
            'developments' => Development::with('developerProfile')->latest()->paginate(12),
            'propertyTypes' => self::PROPERTY_TYPES,
            'statuses' => self::STATUSES,
        ]);
    }

    public function create()
    {
        return view('admin.developments.create', [
            'development' => new Development(['property_type' => 'casa', 'status' => 'preventa']),
            'propertyTypes' => self::PROPERTY_TYPES,
            'statuses' => self::STATUSES,
            'developerProfiles' => $this->developerProfiles(),
            'amenitiesCatalog' => $this->amenitiesCatalog(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $data['description'] = $data['description'] ?? null;
        $data['developer'] = $this->developerName($data['developer_profile_id'] ?? null);
        $data['amenities'] = $this->amenitiesToArray($data['amenities'] ?? []);
        $data['property_details'] = $this->normalizeDetails(
            $data['property_type'],
            $data['property_details'] ?? []
        );
        $data['created_by'] = $request->user()->id;

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('developments/logos', 'public');
        }

        if ($request->hasFile('cover_image')) {
            $data['cover_image_path'] = $request->file('cover_image')->store('developments/covers', 'public');
        }

        Development::create($data);

        return redirect()
            ->route('admin.developments.index')
            ->with('status', 'Desarrollo creado correctamente.');
    }

    public function show(Development $development)
    {
        $development->load('developerProfile');

        return view('admin.developments.show', [
            'development' => $development,
            'propertyTypes' => self::PROPERTY_TYPES,
            'statuses' => self::STATUSES,
            'detailLabels' => self::DETAIL_LABELS,
            'detailFields' => $this->detailFieldsFor($development->property_type),
            'productDetails' => $development->productDetailsItems(),
        ]);
    }

    public function edit(Development $development)
    {
        return view('admin.developments.edit', [
            'development' => $development,
            'propertyTypes' => self::PROPERTY_TYPES,
            'statuses' => self::STATUSES,
            'developerProfiles' => $this->developerProfiles(),
            'amenitiesCatalog' => $this->amenitiesCatalog(),
        ]);
    }

    public function update(Request $request, Development $development)
    {
        $data = $this->validatedData($request);
        $data['description'] = $data['description'] ?? null;
        $data['developer'] = $this->developerName($data['developer_profile_id'] ?? null);
        $data['amenities'] = $this->amenitiesToArray($data['amenities'] ?? []);
        $data['property_details'] = $this->normalizeDetails(
            $data['property_type'],
            $data['property_details'] ?? []
        );

        if ($request->hasFile('logo')) {
            $this->deletePublicFile($development->logo_path);
            $data['logo_path'] = $request->file('logo')->store('developments/logos', 'public');
        }

        if ($request->hasFile('cover_image')) {
            $this->deletePublicFile($development->cover_image_path);
            $data['cover_image_path'] = $request->file('cover_image')->store('developments/covers', 'public');
        }

        $development->update($data);

        return redirect()
            ->route('admin.developments.show', $development)
            ->with('status', 'Desarrollo actualizado correctamente.');
    }

    private function validatedData(Request $request): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'developer_profile_id' => ['nullable', 'exists:developer_profiles,id'],
            'property_type' => ['required', Rule::in(array_keys(self::PROPERTY_TYPES))],
            'city' => ['required', 'string', 'max:255'],
            'zone' => ['required', 'string', 'max:255'],
            'map_url' => ['required', 'url', 'max:700'],
            'logo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:8192'],
            'cover_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:8192'],
            'description' => ['nullable', 'string'],
            'price_from' => ['required', 'numeric', 'min:0'],
            'price_per_m2' => ['required', 'numeric', 'min:0'],
            'down_payment' => ['required', 'numeric', 'min:0'],
            'monthly_payments' => ['required', 'numeric', 'min:0'],
            'payment_methods' => ['required', 'string'],
            'delivery_date' => ['required', 'date'],
            'status' => ['required', Rule::in(array_keys(self::STATUSES))],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['string', 'max:120'],
            'commission_percentage' => ['required', 'numeric', 'between:0,100'],
            'advisor_bonus' => ['nullable', 'numeric', 'min:0'],
            'active_promotions' => ['nullable', 'string'],
            'availability' => ['required', 'string', 'max:255'],
            'total_units' => ['nullable', 'integer', 'min:0'],
            'maintenance_fee' => ['nullable', 'numeric', 'min:0'],
            'property_details' => ['nullable', 'array'],
            'property_details.*' => ['array'],
            'property_details.*.product_name' => ['nullable', 'string', 'max:255'],
        ];

        foreach ($this->detailRules($request->input('property_type')) as $field => $fieldRules) {
            $rules["property_details.*.$field"] = $this->optionalDetailRules($fieldRules);
        }

        $data = $request->validate($rules);

        unset($data['logo'], $data['cover_image']);

        return $data;
    }

    private function detailRules(?string $propertyType): array
    {
        $yesNo = ['si', 'no'];
        $orientations = ['norte', 'sur', 'oriente', 'poniente'];

        if (in_array($propertyType, self::COMMERCIAL_PROPERTY_TYPES, true)) {
            return [
                'construction_m2' => ['required', 'numeric', 'min:0'],
                'front_m' => ['nullable', 'numeric', 'min:0'],
                'depth_m' => ['nullable', 'numeric', 'min:0'],
                'bathrooms' => ['required', 'integer', 'min:0'],
                'parking_spaces' => ['nullable', 'integer', 'min:0'],
                'permitted_use' => ['required', Rule::in(['comercial', 'oficinas', 'mixto'])],
                'rent_option' => ['nullable', Rule::in($yesNo)],
                'delivery_condition' => ['nullable', Rule::in(['obra_gris', 'fachada_cristal', 'equipado'])],
                'building_floors' => ['nullable', 'integer', 'min:0'],
                'elevator' => ['nullable', Rule::in($yesNo)],
                'elevators_count' => ['nullable', 'integer', 'min:0'],
                'orientation' => ['nullable', Rule::in($orientations)],
            ];
        }

        return match ($propertyType) {
            'casa' => [
                'construction_m2' => ['required', 'numeric', 'min:0'],
                'land_m2' => ['required', 'numeric', 'min:0'],
                'front_m' => ['nullable', 'numeric', 'min:0'],
                'depth_m' => ['nullable', 'numeric', 'min:0'],
                'levels' => ['required', 'integer', 'min:1'],
                'bedrooms' => ['required', 'integer', 'min:0'],
                'full_bathrooms' => ['required', 'integer', 'min:0'],
                'half_bathrooms' => ['nullable', 'integer', 'min:0'],
                'parking_spaces' => ['required', 'integer', 'min:0'],
                'ground_floor_bedroom' => ['nullable', Rule::in($yesNo)],
                'street_type' => ['nullable', Rule::in(['privada', 'pie_de_calle'])],
                'equipment' => ['nullable', 'string'],
                'orientation' => ['nullable', Rule::in($orientations)],
                'service_room' => ['nullable', Rule::in($yesNo)],
                'pool' => ['nullable', Rule::in($yesNo)],
                'family_room' => ['nullable', Rule::in($yesNo)],
                'solar_panel_preparation' => ['nullable', Rule::in($yesNo)],
                'ev_charger_preparation' => ['nullable', Rule::in($yesNo)],
            ],
            'departamento' => [
                'construction_m2' => ['required', 'numeric', 'min:0'],
                'floor_level' => ['nullable', 'integer', 'min:0'],
                'bedrooms' => ['required', 'integer', 'min:0'],
                'full_bathrooms' => ['required', 'integer', 'min:0'],
                'half_bathrooms' => ['nullable', 'integer', 'min:0'],
                'parking_spaces' => ['required', 'integer', 'min:0'],
                'storage' => ['nullable', Rule::in($yesNo)],
                'security_24_7' => ['nullable', Rule::in($yesNo)],
                'orientation' => ['nullable', Rule::in($orientations)],
                'equipment' => ['nullable', 'string'],
                'balcony' => ['nullable', Rule::in($yesNo)],
                'building_floors' => ['nullable', 'integer', 'min:0'],
                'elevator' => ['nullable', Rule::in($yesNo)],
                'elevators_count' => ['nullable', 'integer', 'min:0'],
                'pool' => ['nullable', Rule::in($yesNo)],
                'trash_chute' => ['nullable', Rule::in($yesNo)],
                'covered_parking' => ['nullable', Rule::in($yesNo)],
                'solar_panel_preparation' => ['nullable', Rule::in($yesNo)],
                'ev_charger_preparation' => ['nullable', Rule::in($yesNo)],
            ],
            'playa' => [
                'construction_m2' => ['required', 'numeric', 'min:0'],
                'bedrooms' => ['required', 'integer', 'min:0'],
                'full_bathrooms' => ['required', 'integer', 'min:0'],
                'half_bathrooms' => ['nullable', 'integer', 'min:0'],
                'parking_spaces' => ['nullable', 'integer', 'min:0'],
                'ocean_view' => ['nullable', Rule::in(['frontal', 'lateral', 'sin_vista'])],
                'vacation_rental_program' => ['nullable', Rule::in($yesNo)],
                'estimated_yield' => ['nullable', 'numeric', 'between:0,100'],
                'primary_bedroom_ocean_view' => ['nullable', Rule::in($yesNo)],
                'elevator' => ['nullable', Rule::in($yesNo)],
                'rooftop' => ['nullable', Rule::in($yesNo)],
                'water_supply' => ['nullable', Rule::in(['agua_potable', 'pipa', 'mixto'])],
                'sea_access' => ['nullable', Rule::in(['primera_fila', 'segunda_fila', 'tercera_fila', 'posterior'])],
                'service_room' => ['nullable', Rule::in($yesNo)],
                'equipment' => ['nullable', 'string'],
            ],
            'terreno' => [
                'land_m2' => ['required', 'numeric', 'min:0'],
                'front_m' => ['required', 'numeric', 'min:0'],
                'depth_m' => ['required', 'numeric', 'min:0'],
                'land_use' => ['required', Rule::in(['residencial', 'mixto', 'comercial'])],
                'available_services' => ['nullable', 'string'],
                'construction_restrictions' => ['nullable', 'string'],
                'street_type' => ['nullable', Rule::in(['privada', 'pie_de_calle'])],
                'orientation' => ['nullable', Rule::in($orientations)],
            ],
            default => [],
        };
    }

    private function normalizeDetails(string $propertyType, array $details): array
    {
        $allowedFields = $this->detailFieldsFor($propertyType);
        $products = Development::productDetailsItemsFrom($details);

        return collect($products)
            ->map(function (array $product, int $index) use ($allowedFields) {
                $normalized = collect($product)
                    ->only($allowedFields)
                    ->map(fn ($value) => is_string($value) ? trim($value) : $value)
                    ->reject(fn ($value) => $value === null || $value === '')
                    ->all();

                if ($normalized === []) {
                    return null;
                }

                $normalized['product_name'] = $normalized['product_name'] ?? 'Producto '.($index + 1);

                return $normalized;
            })
            ->filter()
            ->values()
            ->all();
    }

    private function optionalDetailRules(array $fieldRules): array
    {
        $rules = collect($fieldRules)
            ->reject(fn ($rule) => is_string($rule) && str_starts_with($rule, 'required'))
            ->values()
            ->all();

        $hasNullableRule = collect($rules)
            ->contains(fn ($rule) => is_string($rule) && in_array($rule, ['nullable', 'sometimes'], true));

        if (! $hasNullableRule) {
            array_unshift($rules, 'nullable');
        }

        return $rules;
    }

    private function amenitiesToArray(array $value): array
    {
        return collect($value)
            ->map(fn (string $item) => trim($item))
            ->filter()
            ->unique(fn (string $item) => str($item)->lower()->ascii()->value())
            ->values()
            ->all();
    }

    private function developerName(?int $developerProfileId): ?string
    {
        if (! $developerProfileId) {
            return null;
        }

        return DeveloperProfile::find($developerProfileId)?->commercial_name;
    }

    private function developerProfiles()
    {
        return DeveloperProfile::orderBy('commercial_name')->get(['id', 'commercial_name', 'legal_name']);
    }

    private function amenitiesCatalog()
    {
        return Amenity::where('is_active', true)->orderBy('name')->pluck('name')->all();
    }

    private function deletePublicFile(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }

    private function detailFieldsFor(string $propertyType): array
    {
        $detailGroup = in_array($propertyType, self::COMMERCIAL_PROPERTY_TYPES, true) ? 'comercial' : $propertyType;

        return self::DETAIL_FIELD_GROUPS[$detailGroup] ?? [];
    }
}
