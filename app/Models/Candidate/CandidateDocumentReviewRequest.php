<?php

namespace App\Models\Candidate;

use App\Models\Nomination\Nomination;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateDocumentReviewRequest extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Statuses
    |--------------------------------------------------------------------------
    */

    public const STATUS_REQUESTED = 'requested';
    public const STATUS_RESOLVED = 'resolved';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'candidate_id',
        'nomination_id',
        'candidate_document_id',
        'requested_by',
        'department',
        'status',
        'reason',
        'comment',
        'requested_at',
        'resolved_at',
        'resolved_by',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(
            Candidate::class,
            'candidate_id'
        );
    }

    public function nomination(): BelongsTo
    {
        return $this->belongsTo(
            Nomination::class,
            'nomination_id'
        );
    }

    public function candidateDocument(): BelongsTo
    {
        return $this->belongsTo(
            CandidateDocument::class,
            'candidate_document_id'
        );
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'requested_by'
        );
    }

    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'resolved_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isRequested(): bool
    {
        return $this->status === self::STATUS_REQUESTED;
    }

    public function isResolved(): bool
    {
        return $this->status === self::STATUS_RESOLVED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }
}
