<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'development_document_folder_id',
    'user_id',
    'can_view',
    'can_upload',
    'can_delete',
])]
class DevelopmentDocumentFolderPermission extends Model
{
    use HasFactory;

    public function folder(): BelongsTo
    {
        return $this->belongsTo(DevelopmentDocumentFolder::class, 'development_document_folder_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'can_view' => 'boolean',
            'can_upload' => 'boolean',
            'can_delete' => 'boolean',
        ];
    }
}
