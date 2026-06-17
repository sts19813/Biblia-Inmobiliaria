<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Development;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DevelopmentComparisonController extends Controller
{
    private const SESSION_KEY = 'development_comparison.ids';

    public function index(Request $request)
    {
        $comparisonItems = $this->selectedItems($request);

        return view('admin.development-comparison.index', [
            'comparisonItems' => $comparisonItems,
            'sections' => $this->comparisonSections($comparisonItems),
            'selectedCount' => $comparisonItems->count(),
            'comparisonMin' => $this->min(),
            'comparisonMax' => $this->max(),
        ]);
    }

    public function updateSelection(Request $request)
    {
        $data = $request->validate([
            'comparison_items' => ['nullable', 'array', 'max:'.$this->max()],
            'comparison_items.*' => ['string', 'max:60', 'regex:/^\d+:\d+$/'],
            'development_ids' => ['nullable', 'array', 'max:'.$this->max()],
            'development_ids.*' => ['integer', 'exists:developments,id'],
        ]);

        $selectedKeys = $this->normalizeSelectionKeys(
            $data['comparison_items'] ?? collect($data['development_ids'] ?? [])
                ->map(fn ($id) => ((int) $id).':0')
                ->all()
        );

        $request->session()->put(self::SESSION_KEY, $selectedKeys);

        return response()->json($this->selectionPayload($selectedKeys));
    }

    public function remove(Request $request, string $selection)
    {
        $selectedKeys = collect($this->selectedKeys($request))
            ->reject(fn (string $key) => $key === $selection)
            ->values()
            ->all();

        $request->session()->put(self::SESSION_KEY, $selectedKeys);

        return redirect()->route('admin.development-comparison.index');
    }

    public function clear(Request $request)
    {
        $request->session()->forget(self::SESSION_KEY);

        if ($request->expectsJson()) {
            return response()->json($this->selectionPayload([]));
        }

        return redirect()->route('admin.development-comparison.index');
    }

    public static function sessionKey(): string
    {
        return self::SESSION_KEY;
    }

    private function selectedKeys(Request $request): array
    {
        return $this->normalizeSelectionKeys($request->session()->get(self::SESSION_KEY, []));
    }

    private function selectedItems(Request $request): Collection
    {
        $selectedKeys = $this->selectedKeys($request);

        if ($selectedKeys === []) {
            return collect();
        }

        $developmentIds = collect($selectedKeys)
            ->map(fn (string $key) => $this->selectionParts($key)[0])
            ->unique()
            ->values();

        $developments = Development::with('developerProfile')
            ->whereIn('id', $developmentIds)
            ->get()
            ->keyBy('id');

        $items = collect($selectedKeys)
            ->map(function (string $key) use ($developments) {
                [$developmentId, $productIndex] = $this->selectionParts($key);
                $development = $developments->get($developmentId);

                if (! $development) {
                    return null;
                }

                $products = $development->productDetailsItems();
                $products = $products === [] ? [['product_name' => $development->name]] : $products;
                $product = $products[$productIndex] ?? null;

                if (! $product) {
                    return null;
                }

                return [
                    'key' => $key,
                    'development' => $development,
                    'product_index' => $productIndex,
                    'product' => $product,
                ];
            })
            ->filter()
            ->values();

        $validKeys = $items->pluck('key')->all();

        if ($validKeys !== $selectedKeys) {
            $request->session()->put(self::SESSION_KEY, $validKeys);
        }

        return $items;
    }

    private function normalizeSelectionKeys(mixed $keys): array
    {
        return collect((array) $keys)
            ->map(fn ($key) => is_numeric($key) ? ((int) $key).':0' : (string) $key)
            ->filter(fn (string $key) => preg_match('/^\d+:\d+$/', $key))
            ->map(function (string $key) {
                [$developmentId, $productIndex] = $this->selectionParts($key);

                return Development::makeComparisonSelectionKey($developmentId, $productIndex);
            })
            ->unique()
            ->take($this->max())
            ->values()
            ->all();
    }

    private function selectionParts(string $key): array
    {
        [$developmentId, $productIndex] = array_map('intval', explode(':', $key, 2));

        return [$developmentId, max(0, $productIndex)];
    }

    private function selectionPayload(array $selectedKeys): array
    {
        return [
            'items' => $selectedKeys,
            'ids' => $selectedKeys,
            'count' => count($selectedKeys),
            'min' => $this->min(),
            'max' => $this->max(),
            'compare_url' => route('admin.development-comparison.index'),
        ];
    }

    private function comparisonSections(Collection $items): array
    {
        if ($items->isEmpty()) {
            return [];
        }

        return [
            [
                'title' => null,
                'rows' => $this->mainRows($items),
            ],
            [
                'title' => 'Detalles',
                'rows' => $this->detailRows($items),
            ],
            [
                'title' => 'Amenidades',
                'rows' => $this->amenityRows($items),
            ],
        ];
    }

    private function mainRows(Collection $items): array
    {
        return [
            $this->row($items, 'Desarrollo', fn (array $item) => $item['development']->name),
            $this->row($items, 'Tipo', fn (array $item) => $this->propertyTypeLabel($item['development'])),
            $this->row($items, 'Ubicacion', fn (array $item) => $this->lines($item['development']->zone, $item['development']->city)),
            $this->row($items, 'Precio', fn (array $item) => $this->money($this->modelPrice($item)), 'price'),
            $this->row($items, 'Precio por m2', fn (array $item) => $this->money($item['development']->price_per_m2).'/m2'),
            $this->row($items, 'Fecha de entrega', fn (array $item) => $item['development']->delivery_date?->format('d/m/Y') ?: '-'),
            $this->row($items, 'Estado', fn (array $item) => $this->statusLabel($item['development'])),
            $this->row($items, 'Enganche', fn (array $item) => $this->money($item['development']->down_payment)),
            $this->row($items, 'Mensualidad', fn (array $item) => $this->money($item['development']->monthly_payments)),
            $this->row($items, 'Comision', fn (array $item) => number_format((float) $item['development']->commission_percentage, 1).'%', 'commission'),
            $this->row($items, 'Bono asesor', fn (array $item) => $item['development']->advisor_bonus ? $this->money($item['development']->advisor_bonus) : '-'),
            $this->row($items, 'Mantenimiento', fn (array $item) => $item['development']->maintenance_fee ? $this->money($item['development']->maintenance_fee) : '-'),
            $this->row($items, 'Unidades totales', fn (array $item) => $item['development']->total_units ?: '-'),
            $this->row($items, 'Disponibilidad', fn (array $item) => $item['development']->availability ?: '-'),
            $this->row($items, 'Tipo creditos', fn (array $item) => $item['development']->payment_methods ?: '-'),
            $this->row($items, 'Promociones', fn (array $item) => $item['development']->active_promotions ?: '-'),
        ];
    }

    private function detailRows(Collection $items): array
    {
        $detailOrder = array_flip(array_keys(DevelopmentController::DETAIL_LABELS));
        $detailKeys = $items
            ->flatMap(fn (array $item) => array_keys($item['product'] ?? []))
            ->reject(fn (string $key) => in_array($key, ['product_name', 'price'], true))
            ->unique()
            ->sortBy(fn (string $key) => $detailOrder[$key] ?? PHP_INT_MAX)
            ->values();

        return $detailKeys
            ->map(fn (string $key) => $this->row(
                $items,
                DevelopmentController::DETAIL_LABELS[$key] ?? str($key)->replace('_', ' ')->title()->value(),
                fn (array $item) => $this->detailValue($item['product'][$key] ?? null)
            ))
            ->all();
    }

    private function amenityRows(Collection $items): array
    {
        $amenities = collect(Amenity::where('is_active', true)->orderBy('name')->pluck('name'))
            ->merge($items->flatMap(fn (array $item) => $item['development']->amenities ?? []))
            ->filter()
            ->unique(fn (string $amenity) => str($amenity)->lower()->ascii()->value())
            ->sort()
            ->values();

        return $amenities
            ->map(fn (string $amenity) => [
                'label' => $amenity,
                'variant' => 'boolean',
                'values' => $items
                    ->mapWithKeys(fn (array $item) => [
                        $item['key'] => $this->hasAmenity($item['development'], $amenity),
                    ])
                    ->all(),
            ])
            ->all();
    }

    private function row(Collection $items, string $label, callable $value, string $variant = 'default'): array
    {
        return [
            'label' => $label,
            'variant' => $variant,
            'values' => $items
                ->mapWithKeys(fn (array $item) => [$item['key'] => $value($item)])
                ->all(),
        ];
    }

    private function hasAmenity(Development $development, string $amenity): bool
    {
        $needle = str($amenity)->lower()->ascii()->value();

        return collect($development->amenities ?? [])
            ->map(fn (string $item) => str($item)->lower()->ascii()->value())
            ->contains($needle);
    }

    private function modelPrice(array $item): mixed
    {
        return $item['product']['price'] ?? $item['development']->price_from;
    }

    private function detailValue(mixed $value): string
    {
        if ($value === null || $value === '') {
            return '-';
        }

        return match ($value) {
            'si' => 'Si',
            'no' => 'No',
            'privada' => 'Privada',
            'pie_de_calle' => 'Pie de calle',
            'frontal' => 'Frontal',
            'lateral' => 'Lateral',
            'sin_vista' => 'Sin vista',
            'primera_fila' => 'Primera fila',
            'segunda_fila' => 'Segunda fila',
            'tercera_fila' => 'Tercera fila',
            'agua_potable' => 'Agua potable',
            'pipa' => 'Pipa de agua',
            'pozo' => 'Pozo',
            'mixto' => 'Mixto',
            'comercial' => 'Comercial',
            'oficinas' => 'Oficinas',
            'obra_gris' => 'Obra gris',
            'fachada_cristal' => 'Fachada cristal',
            'equipado' => 'Equipado',
            default => is_numeric($value) ? (string) $value : str((string) $value)->replace('_', ' ')->title()->value(),
        };
    }

    private function propertyTypeLabel(Development $development): string
    {
        return DevelopmentController::PROPERTY_TYPES[$development->property_type] ?? $development->property_type;
    }

    private function statusLabel(Development $development): string
    {
        return DevelopmentController::STATUSES[$development->status] ?? $development->status;
    }

    private function lines(?string $primary, ?string $secondary): string
    {
        return collect([$primary, $secondary])->filter()->implode("\n") ?: '-';
    }

    private function money(mixed $value): string
    {
        if (! is_numeric($value)) {
            return '-';
        }

        return '$'.number_format((float) $value, 0);
    }

    private function min(): int
    {
        return max(1, (int) config('development_comparison.min', 2));
    }

    private function max(): int
    {
        return max($this->min(), (int) config('development_comparison.max', 10));
    }
}
