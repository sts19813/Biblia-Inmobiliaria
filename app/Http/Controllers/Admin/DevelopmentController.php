<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Development;
use Illuminate\Http\Request;
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

    public function index()
    {
        return view('admin.developments.index', [
            'developments' => Development::latest()->paginate(12),
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
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $data['description'] = $data['description'] ?? null;
        $data['amenities'] = $this->linesToArray($data['amenities']);
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

    private function validatedData(Request $request): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'developer' => ['required', 'string', 'max:255'],
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
            'amenities' => ['required', 'string'],
            'commission_percentage' => ['required', 'numeric', 'between:0,100'],
            'advisor_bonus' => ['nullable', 'numeric', 'min:0'],
            'active_promotions' => ['nullable', 'string'],
            'availability' => ['required', 'string', 'max:255'],
            'total_units' => ['nullable', 'integer', 'min:0'],
            'maintenance_fee' => ['nullable', 'numeric', 'min:0'],
            'property_details' => ['nullable', 'array'],
        ];

        foreach ($this->detailRules($request->input('property_type')) as $field => $fieldRules) {
            $rules["property_details.$field"] = $fieldRules;
        }

        $data = $request->validate($rules);

        unset($data['logo'], $data['cover_image']);

        return $data;
    }

    private function detailRules(?string $propertyType): array
    {
        if (in_array($propertyType, self::COMMERCIAL_PROPERTY_TYPES, true)) {
            return [
                'construction_m2' => ['required', 'numeric', 'min:0'],
                'front_m' => ['nullable', 'numeric', 'min:0'],
                'depth_m' => ['nullable', 'numeric', 'min:0'],
                'bathrooms' => ['required', 'integer', 'min:0'],
                'parking_spaces' => ['nullable', 'integer', 'min:0'],
                'permitted_use' => ['required', 'string', 'max:80'],
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
            ],
            'departamento' => [
                'construction_m2' => ['required', 'numeric', 'min:0'],
                'floor_level' => ['nullable', 'integer', 'min:0'],
                'bedrooms' => ['required', 'integer', 'min:0'],
                'full_bathrooms' => ['required', 'integer', 'min:0'],
                'half_bathrooms' => ['nullable', 'integer', 'min:0'],
                'parking_spaces' => ['required', 'integer', 'min:0'],
            ],
            'playa' => [
                'construction_m2' => ['required', 'numeric', 'min:0'],
                'bedrooms' => ['required', 'integer', 'min:0'],
                'full_bathrooms' => ['required', 'integer', 'min:0'],
                'half_bathrooms' => ['nullable', 'integer', 'min:0'],
                'parking_spaces' => ['nullable', 'integer', 'min:0'],
                'estimated_yield' => ['nullable', 'numeric', 'between:0,100'],
            ],
            'terreno' => [
                'land_m2' => ['required', 'numeric', 'min:0'],
                'front_m' => ['required', 'numeric', 'min:0'],
                'depth_m' => ['required', 'numeric', 'min:0'],
                'land_use' => ['required', 'string', 'max:80'],
            ],
            default => [],
        };
    }

    private function normalizeDetails(string $propertyType, array $details): array
    {
        $allowedFields = [
            'casa' => [
                'construction_m2', 'land_m2', 'front_m', 'depth_m', 'levels', 'bedrooms', 'full_bathrooms',
                'half_bathrooms', 'parking_spaces', 'ground_floor_bedroom', 'street_type', 'equipment',
                'orientation', 'service_room', 'pool', 'family_room', 'solar_panel_preparation',
                'ev_charger_preparation',
            ],
            'departamento' => [
                'construction_m2', 'floor_level', 'bedrooms', 'full_bathrooms', 'half_bathrooms',
                'parking_spaces', 'storage', 'security_24_7', 'orientation', 'equipment', 'balcony',
                'building_floors', 'elevator', 'elevators_count', 'pool', 'trash_chute',
                'covered_parking', 'solar_panel_preparation', 'ev_charger_preparation',
            ],
            'playa' => [
                'construction_m2', 'bedrooms', 'full_bathrooms', 'half_bathrooms', 'parking_spaces',
                'ocean_view', 'vacation_rental_program', 'estimated_yield', 'primary_bedroom_ocean_view',
                'elevator', 'rooftop', 'water_supply', 'sea_access', 'service_room', 'equipment',
            ],
            'terreno' => [
                'land_m2', 'front_m', 'depth_m', 'land_use', 'available_services',
                'construction_restrictions', 'street_type', 'orientation',
            ],
            'comercial' => [
                'construction_m2', 'front_m', 'depth_m', 'bathrooms', 'parking_spaces', 'permitted_use',
                'rent_option', 'delivery_condition', 'building_floors', 'elevator', 'elevators_count',
                'orientation',
            ],
        ];
        $detailGroup = in_array($propertyType, self::COMMERCIAL_PROPERTY_TYPES, true) ? 'comercial' : $propertyType;

        return collect($details)
            ->only($allowedFields[$detailGroup] ?? [])
            ->filter(fn ($value) => $value !== null && $value !== '')
            ->all();
    }

    private function linesToArray(string $value): array
    {
        return collect(preg_split('/[\r\n,]+/', $value))
            ->map(fn (string $item) => trim($item))
            ->filter()
            ->values()
            ->all();
    }
}
