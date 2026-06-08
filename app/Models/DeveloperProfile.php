<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'commercial_name',
    'legal_name',
    'logo_path',
    'cover_image_path',
    'website',
    'corporate_email',
    'phone',
    'whatsapp',
    'address',
    'city',
    'state',
    'country',
    'short_description',
    'long_description',
    'facebook_url',
    'instagram_url',
    'linkedin_url',
    'twitter_url',
    'created_by',
])]
class DeveloperProfile extends Model
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

    public function developments(): HasMany
    {
        return $this->hasMany(Development::class);
    }
}
