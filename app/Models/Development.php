<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Fillable([
    'name',
    'developer',
    'developer_profile_id',
    'property_type',
    'city',
    'zone',
    'map_url',
    'logo_path',
    'cover_image_path',
    'description',
    'price_from',
    'price_per_m2',
    'down_payment',
    'monthly_payments',
    'payment_methods',
    'delivery_date',
    'status',
    'amenities',
    'commission_percentage',
    'advisor_bonus',
    'active_promotions',
    'availability',
    'total_units',
    'maintenance_fee',
    'property_details',
    'created_by',
    'document_share_token',
])]
class Development extends Model
{
    use HasFactory;

    public const DOCUMENT_FOLDERS = [
        'Brochure',
        'Ficha tecnica',
        'Memoria descriptiva',
        'Terminos y condiciones',
        'Galeria de imagenes',
        'Avance de obra',
        'Videos',
        'Carpeta Drive',
        'Legal',
        'Lista de Precios',
        'Cv Desarrollador',
        'Cv Contructor',
        'Planos',
        'Chepinas',
        'Ubicacion',
        'Faqs',
        'Cta Bancaria',
        'Recorrido Virtual',
    ];

    public function logoUrl(): ?string
    {
        return $this->logo_path ? Storage::disk('public')->url($this->logo_path) : null;
    }

    public function coverImageUrl(): ?string
    {
        return $this->cover_image_path ? Storage::disk('public')->url($this->cover_image_path) : null;
    }

    public function displayImageUrl(): ?string
    {
        return $this->coverImageUrl() ?: $this->logoUrl();
    }

    public function documentFolders(): HasMany
    {
        return $this->hasMany(DevelopmentDocumentFolder::class)->orderBy('sort_order')->orderBy('name');
    }

    public function developerProfile(): BelongsTo
    {
        return $this->belongsTo(DeveloperProfile::class);
    }

    public function developerName(): string
    {
        return $this->developerProfile?->commercial_name ?: ($this->developer ?: 'Sin desarrolladora');
    }

    public function productDetailsItems(): array
    {
        return self::productDetailsItemsFrom($this->property_details ?? [], $this->name);
    }

    public static function productDetailsItemsFrom(mixed $details, ?string $fallbackName = null): array
    {
        if (! is_array($details) || $details === []) {
            return [];
        }

        if (isset($details['products']) && is_array($details['products'])) {
            $details = $details['products'];
        }

        $items = array_is_list($details) ? $details : [$details];

        return collect($items)
            ->filter(fn ($item) => is_array($item))
            ->values()
            ->map(function (array $item, int $index) use ($fallbackName) {
                if (isset($item['details']) && is_array($item['details'])) {
                    $item = array_merge($item['details'], Arr::except($item, ['details']));
                }

                if (! array_key_exists('product_name', $item) && array_key_exists('name', $item)) {
                    $item['product_name'] = $item['name'];
                }

                $normalized = collect($item)
                    ->mapWithKeys(fn ($value, $key) => [(string) $key => is_string($value) ? trim($value) : $value])
                    ->reject(fn ($value) => $value === null || $value === '')
                    ->all();

                if ($normalized === []) {
                    return null;
                }

                $normalized['product_name'] = $normalized['product_name']
                    ?? $fallbackName
                    ?? 'Modelo '.($index + 1);

                return $normalized;
            })
            ->filter()
            ->values()
            ->all();
    }

    public function productDetailsAt(int $index): array
    {
        return $this->productDetailsItems()[$index] ?? [];
    }

    public function productNameAt(int $index): string
    {
        $product = $this->productDetailsAt($index);

        return (string) ($product['product_name'] ?? 'Modelo '.($index + 1));
    }

    public function productPriceAt(int $index): mixed
    {
        $product = $this->productDetailsAt($index);

        return $product['price'] ?? $this->price_from;
    }

    public function comparisonSelectionKey(int $productIndex): string
    {
        return self::makeComparisonSelectionKey($this->id, $productIndex);
    }

    public static function makeComparisonSelectionKey(int|string $developmentId, int|string $productIndex): string
    {
        return (int) $developmentId.':'.max(0, (int) $productIndex);
    }

    public function ensureDocumentShareToken(): string
    {
        if (! $this->document_share_token) {
            $this->forceFill(['document_share_token' => Str::random(40)])->save();
        }

        return $this->document_share_token;
    }

    public function ensureDocumentFolders(): void
    {
        foreach (self::DOCUMENT_FOLDERS as $index => $name) {
            $this->documentFolders()->firstOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'sort_order' => $index + 1,
                    'is_system' => true,
                ]
            );
        }
    }

    protected function casts(): array
    {
        return [
            'amenities' => 'array',
            'property_details' => 'array',
            'delivery_date' => 'date',
            'price_from' => 'decimal:2',
            'price_per_m2' => 'decimal:2',
            'down_payment' => 'decimal:2',
            'monthly_payments' => 'decimal:2',
            'commission_percentage' => 'decimal:2',
            'advisor_bonus' => 'decimal:2',
            'maintenance_fee' => 'decimal:2',
        ];
    }
}
