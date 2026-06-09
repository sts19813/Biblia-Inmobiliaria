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
        $selectedIds = $this->selectedIds($request);
        $developments = Development::with('developerProfile')
            ->whereIn('id', $selectedIds)
            ->get()
            ->sortBy(fn (Development $development) => array_search($development->id, $selectedIds, true))
            ->values();

        return view('admin.development-comparison.index', [
            'developments' => $developments,
            'sections' => $this->comparisonSections($developments),
            'selectedCount' => $developments->count(),
            'comparisonMin' => $this->min(),
            'comparisonMax' => $this->max(),
        ]);
    }

    public function updateSelection(Request $request)
    {
        $data = $request->validate([
            'development_ids' => ['nullable', 'array', 'max:' . $this->max()],
            'development_ids.*' => ['integer', 'exists:developments,id'],
        ]);

        $selectedIds = collect($data['development_ids'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values()
            ->all();

        $request->session()->put(self::SESSION_KEY, $selectedIds);

        return response()->json($this->selectionPayload($selectedIds));
    }

    public function remove(Request $request, Development $development)
    {
        $selectedIds = collect($this->selectedIds($request))
            ->reject(fn (int $id) => $id === $development->id)
            ->values()
            ->all();

        $request->session()->put(self::SESSION_KEY, $selectedIds);

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

    private function selectedIds(Request $request): array
    {
        $ids = collect($request->session()->get(self::SESSION_KEY, []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return [];
        }

        $existingIds = Development::whereIn('id', $ids)->pluck('id')->all();
        $selectedIds = $ids
            ->filter(fn (int $id) => in_array($id, $existingIds, true))
            ->values()
            ->all();

        if ($selectedIds !== $ids->all()) {
            $request->session()->put(self::SESSION_KEY, $selectedIds);
        }

        return $selectedIds;
    }

    private function selectionPayload(array $selectedIds): array
    {
        return [
            'ids' => $selectedIds,
            'count' => count($selectedIds),
            'min' => $this->min(),
            'max' => $this->max(),
            'compare_url' => route('admin.development-comparison.index'),
        ];
    }

    private function comparisonSections(Collection $developments): array
    {
        if ($developments->isEmpty()) {
            return [];
        }

        return [
            [
                'title' => null,
                'rows' => $this->mainRows($developments),
            ],
            [
                'title' => 'Detalles',
                'rows' => $this->detailRows($developments),
            ],
            [
                'title' => 'Amenidades',
                'rows' => $this->amenityRows($developments),
            ],
        ];
    }

    private function mainRows(Collection $developments): array
    {
        return [
            $this->row($developments, 'Tipo', fn (Development $development) => $this->propertyTypeLabel($development)),
            $this->row($developments, 'Ubicacion', fn (Development $development) => $this->lines($development->zone, $development->city)),
            $this->row($developments, 'Precio', fn (Development $development) => $this->money($development->price_from), 'price'),
            $this->row($developments, 'Precio por m2', fn (Development $development) => $this->money($development->price_per_m2) . '/m2'),
            $this->row($developments, 'Fecha de entrega', fn (Development $development) => $development->delivery_date?->format('d/m/Y') ?: '-'),
            $this->row($developments, 'Estado', fn (Development $development) => $this->statusLabel($development)),
            $this->row($developments, 'Enganche', fn (Development $development) => $this->money($development->down_payment)),
            $this->row($developments, 'Mensualidad', fn (Development $development) => $this->money($development->monthly_payments)),
            $this->row($developments, 'Comision', fn (Development $development) => number_format((float) $development->commission_percentage, 1) . '%', 'commission'),
            $this->row($developments, 'Bono asesor', fn (Development $development) => $development->advisor_bonus ? $this->money($development->advisor_bonus) : '-'),
            $this->row($developments, 'Mantenimiento', fn (Development $development) => $development->maintenance_fee ? $this->money($development->maintenance_fee) : '-'),
            $this->row($developments, 'Unidades totales', fn (Development $development) => $development->total_units ?: '-'),
            $this->row($developments, 'Disponibilidad', fn (Development $development) => $development->availability ?: '-'),
            $this->row($developments, 'Tipo creditos', fn (Development $development) => $development->payment_methods ?: '-'),
            $this->row($developments, 'Promociones', fn (Development $development) => $development->active_promotions ?: '-'),
        ];
    }

    private function detailRows(Collection $developments): array
    {
        $detailOrder = array_flip(array_keys(DevelopmentController::DETAIL_LABELS));
        $detailKeys = $developments
            ->flatMap(fn (Development $development) => array_keys($development->property_details ?? []))
            ->unique()
            ->sortBy(fn (string $key) => $detailOrder[$key] ?? PHP_INT_MAX)
            ->values();

        return $detailKeys
            ->map(fn (string $key) => $this->row(
                $developments,
                DevelopmentController::DETAIL_LABELS[$key] ?? str($key)->replace('_', ' ')->title()->value(),
                fn (Development $development) => $this->detailValue($development->property_details[$key] ?? null)
            ))
            ->all();
    }

    private function amenityRows(Collection $developments): array
    {
        $amenities = collect(Amenity::where('is_active', true)->orderBy('name')->pluck('name'))
            ->merge($developments->flatMap(fn (Development $development) => $development->amenities ?? []))
            ->filter()
            ->unique(fn (string $amenity) => str($amenity)->lower()->ascii()->value())
            ->sort()
            ->values();

        return $amenities
            ->map(fn (string $amenity) => [
                'label' => $amenity,
                'variant' => 'boolean',
                'values' => $developments
                    ->mapWithKeys(fn (Development $development) => [
                        $development->id => $this->hasAmenity($development, $amenity),
                    ])
                    ->all(),
            ])
            ->all();
    }

    private function row(Collection $developments, string $label, callable $value, string $variant = 'default'): array
    {
        return [
            'label' => $label,
            'variant' => $variant,
            'values' => $developments
                ->mapWithKeys(fn (Development $development) => [$development->id => $value($development)])
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

        return '$' . number_format((float) $value, 0);
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
