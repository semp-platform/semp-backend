<?php

namespace App\Models\Candidate;

use App\Models\DocumentType;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CandidateDocument extends Model
{
    protected $fillable = [
        'candidate_id',
        'document_type_id',
        'original_name',
        'stored_name',
        'disk',
        'path',
        'mime_type',
        'file_size',

        'uploaded_by',
        'uploaded_at',
        'verified_by',
        'verified_at',
        'remarks',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'uploaded_by'
        );
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'verified_by'
        );
    }
    public function reviewRequests(): HasMany
{
    return $this->hasMany(
        CandidateDocumentReviewRequest::class,
        'candidate_document_id'
    );
}
}
