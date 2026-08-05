<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormTemplate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'document_type_id',
        'title',
        'version',
        'file_path',
        'mime_type',
        'file_size',
        'effective_date',
        'is_active',
        'uploaded_by',
    ];

    protected function casts(): array
    {
        return [
            'effective_date' => 'date',
            'file_size' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function fileSizeInKb(): float
    {
        return round($this->file_size / 1024, 2);
    }

    public function fileSizeInMb(): float
    {
        return round($this->file_size / 1048576, 2);
    }
}
