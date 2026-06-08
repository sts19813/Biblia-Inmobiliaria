<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
