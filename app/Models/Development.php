<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'name',
    'developer',
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
])]
class Development extends Model
{
    use HasFactory;

    public function logoUrl(): ?string
    {
        return $this->logo_path ? Storage::disk('public')->url($this->logo_path) : null;
    }

    public function coverImageUrl(): ?string
    {
        return $this->cover_image_path ? Storage::disk('public')->url($this->cover_image_path) : null;
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
