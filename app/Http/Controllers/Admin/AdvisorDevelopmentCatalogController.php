<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Development;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class AdvisorDevelopmentCatalogController extends Controller
{
    public function index(Request $request)
    {
        $allDevelopments = Development::with(['developerProfile', 'documentFolders.files'])
            ->latest()
            ->get();
        $comparisonMin = max(1, (int) config('development_comparison.min', 2));
        $comparisonMax = max($comparisonMin, (int) config('development_comparison.max', 10));

        $allCatalogItems = $this->catalogItems($allDevelopments);
        $filtered = $this->filterCatalogItems($allCatalogItems, $request);
        $perPage = 20;
        $page = LengthAwarePaginator::resolveCurrentPage();
        $catalogItems = new LengthAwarePaginator(
            $filtered->forPage($page, $perPage)->values(),
            $filtered->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('admin.advisor-catalog.index', [
            'catalogItems' => $catalogItems,
            'totalDevelopments' => $allDevelopments->count(),
            'totalProducts' => $allCatalogItems->count(),
            'propertyTypes' => [
                'casa' => 'Casa',
                'departamento' => 'Departamento',
                'terreno' => 'Terreno',
                'playa' => 'Playa',
                'comercial' => 'Comercial',
            ],
            'statuses' => DevelopmentController::STATUSES,
            'cities' => $allDevelopments->pluck('city')->filter()->unique()->sort()->values(),
            'zonesByCity' => $allDevelopments
                ->groupBy('city')
                ->map(fn (Collection $items) => $items->pluck('zone')->filter()->unique()->sort()->values())
                ->filter(fn (Collection $zones, ?string $city) => filled($city)),
            'paymentMethods' => ['Contado', 'Credito bancario', 'Infonavit', 'Cofinavit'],
            'amenities' => $this->amenities($allDevelopments),
            'priceMin' => (float) $allDevelopments->min('price_from'),
            'priceMax' => (float) $allDevelopments->max('price_from'),
            'filters' => $request->all(),
            'selectedComparisonKeys' => $this->selectedComparisonKeys($request),
            'comparisonMin' => $comparisonMin,
            'comparisonMax' => $comparisonMax,
        ]);
    }

    private function catalogItems(Collection $developments): Collection
    {
        return $developments
            ->flatMap(function (Development $development) {
                $products = $development->productDetailsItems();
                $products = $products === [] ? [['product_name' => $development->name]] : $products;

                return collect($products)
                    ->map(fn (array $product, int $index) => [
                        'key' => $development->comparisonSelectionKey($index),
                        'development' => $development,
                        'product_index' => $index,
                        'product' => $product,
                    ]);
            })
            ->values();
    }

    private function filterCatalogItems(Collection $items, Request $request): Collection
    {
        return $items
            ->filter(fn (array $item) => $this->matchesPropertyType($item['development'], $request->input('property_types', [])))
            ->filter(fn (array $item) => $this->matchesIn($item['development']->city, $request->input('cities', [])))
            ->filter(fn (array $item) => $this->matchesIn($item['development']->zone, $request->input('zones', [])))
            ->filter(fn (array $item) => $this->matchesIn($item['development']->status, $request->input('statuses', [])))
            ->filter(fn (array $item) => $this->matchesRange((float) $item['development']->price_from, $request->input('price_min'), $request->input('price_max')))
            ->filter(fn (array $item) => $this->matchesBedrooms($item['product'], $request->input('bedrooms')))
            ->filter(fn (array $item) => $this->matchesBathrooms($item['product'], $request->input('bathrooms')))
            ->filter(fn (array $item) => $this->matchesPaymentMethods($item['development'], $request->input('payment_methods', [])))
            ->filter(fn (array $item) => $this->matchesAdvisorBonus($item['development'], $request->input('advisor_bonus')))
            ->filter(fn (array $item) => $this->matchesAmenities($item['development'], $request->input('amenities', [])))
            ->filter(fn (array $item) => $this->matchesRange($this->detailNumber($item['product'], 'construction_m2'), $request->input('construction_m2_min'), $request->input('construction_m2_max')))
            ->filter(fn (array $item) => $this->matchesRange($this->detailNumber($item['product'], 'land_m2'), $request->input('land_m2_min'), $request->input('land_m2_max')))
            ->values();
    }

    private function matchesPropertyType(Development $development, array|string|null $types): bool
    {
        $types = $this->arrayFilter($types);

        if ($types === []) {
            return true;
        }

        if (in_array('comercial', $types, true) && in_array($development->property_type, DevelopmentController::COMMERCIAL_PROPERTY_TYPES, true)) {
            return true;
        }

        return in_array($development->property_type, $types, true);
    }

    private function matchesIn(?string $value, array|string|null $needles): bool
    {
        $needles = $this->arrayFilter($needles);

        return $needles === [] || in_array($value, $needles, true);
    }

    private function matchesRange(?float $value, mixed $min, mixed $max): bool
    {
        if ($value === null) {
            return blank($min) && blank($max);
        }

        if (filled($min) && $value < (float) $min) {
            return false;
        }

        if (filled($max) && $value > (float) $max) {
            return false;
        }

        return true;
    }

    private function matchesBedrooms(array $product, mixed $value): bool
    {
        if (blank($value)) {
            return true;
        }

        $bedrooms = $this->detailNumber($product, 'bedrooms');

        return $value === '4' ? $bedrooms >= 4 : $bedrooms === (float) $value;
    }

    private function matchesBathrooms(array $product, mixed $value): bool
    {
        if (blank($value)) {
            return true;
        }

        $bathrooms = $this->detailNumber($product, 'full_bathrooms') ?? $this->detailNumber($product, 'bathrooms');

        return $value === '4' ? $bathrooms >= 4 : $bathrooms === (float) $value;
    }

    private function matchesPaymentMethods(Development $development, array|string|null $methods): bool
    {
        $methods = $this->arrayFilter($methods);

        if ($methods === []) {
            return true;
        }

        $text = str($development->payment_methods)->lower()->ascii()->value();

        foreach ($methods as $method) {
            if (str_contains($text, str($method)->lower()->ascii()->value())) {
                return true;
            }
        }

        return false;
    }

    private function matchesAdvisorBonus(Development $development, mixed $value): bool
    {
        return match ($value) {
            'with_bonus' => (float) $development->advisor_bonus > 0,
            'without_bonus' => ! $development->advisor_bonus || (float) $development->advisor_bonus <= 0,
            default => true,
        };
    }

    private function matchesAmenities(Development $development, array|string|null $amenities): bool
    {
        $amenities = $this->arrayFilter($amenities);

        if ($amenities === []) {
            return true;
        }

        $developmentAmenities = collect($development->amenities ?? [])
            ->map(fn (string $amenity) => str($amenity)->lower()->ascii()->value());

        return collect($amenities)
            ->map(fn (string $amenity) => str($amenity)->lower()->ascii()->value())
            ->every(fn (string $amenity) => $developmentAmenities->contains($amenity));
    }

    private function detailNumber(array $product, string $key): ?float
    {
        $value = $product[$key] ?? null;

        return is_numeric($value) ? (float) $value : null;
    }

    private function selectedComparisonKeys(Request $request): array
    {
        return collect($request->session()->get(DevelopmentComparisonController::sessionKey(), []))
            ->map(fn ($key) => is_numeric($key) ? ((int) $key).':0' : (string) $key)
            ->filter(fn (string $key) => preg_match('/^\d+:\d+$/', $key))
            ->map(function (string $key) {
                [$developmentId, $productIndex] = array_map('intval', explode(':', $key, 2));

                return Development::makeComparisonSelectionKey($developmentId, $productIndex);
            })
            ->unique()
            ->values()
            ->all();
    }

    private function arrayFilter(array|string|null $value): array
    {
        return collect((array) $value)->filter(fn ($item) => filled($item))->values()->all();
    }

    private function amenities(Collection $developments): Collection
    {
        return collect(Amenity::where('is_active', true)->orderBy('name')->pluck('name'))
            ->merge($developments->flatMap(fn (Development $development) => $development->amenities ?? []))
            ->filter()
            ->unique(fn (string $amenity) => str($amenity)->lower()->ascii()->value())
            ->sort()
            ->values();
    }
}
