<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'development_id',
    'name',
    'slug',
    'sort_order',
    'is_system',
])]
class DevelopmentDocumentFolder extends Model
{
    use HasFactory;

    public function development(): BelongsTo
    {
        return $this->belongsTo(Development::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(DevelopmentDocumentFile::class);
    }

    public function permissions(): HasMany
    {
        return $this->hasMany(DevelopmentDocumentFolderPermission::class);
    }

    public function sizeBytes(): int
    {
        return (int) $this->files->sum('size_bytes');
    }

    public function humanSize(): string
    {
        return DevelopmentDocumentFile::humanBytes($this->sizeBytes());
    }
}
