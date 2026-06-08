<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'development_document_folder_id',
    'uploaded_by',
    'name',
    'original_name',
    'path',
    'disk',
    'mime_type',
    'extension',
    'size_bytes',
    'visibility',
    'is_featured',
])]
class DevelopmentDocumentFile extends Model
{
    use HasFactory;

    public function folder(): BelongsTo
    {
        return $this->belongsTo(DevelopmentDocumentFolder::class, 'development_document_folder_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function url(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    public function humanSize(): string
    {
        return self::humanBytes((int) $this->size_bytes);
    }

    public static function humanBytes(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes . ' B';
        }

        $units = ['KB', 'MB', 'GB', 'TB'];
        $size = $bytes / 1024;

        foreach ($units as $unit) {
            if ($size < 1024) {
                return number_format($size, 1) . ' ' . $unit;
            }

            $size /= 1024;
        }

        return number_format($size, 1) . ' PB';
    }
}
