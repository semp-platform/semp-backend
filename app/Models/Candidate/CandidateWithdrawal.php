<?php

namespace App\Models\Candidate;

use App\Models\Nomination\Nomination;
use App\Models\Party\PoliticalParty;
use App\Models\Reference\CandidateChangeReason;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateWithdrawal extends Model
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'nomination_id',
        'political_party_id',
        'candidate_change_reason_id',
'reason',
'remarks',
'supporting_evidence_path',
        'status',
        'submitted_at',
        'approved_at',
        'rejected_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function nomination(): BelongsTo
    {
        return $this->belongsTo(Nomination::class);
    }

    public function politicalParty(): BelongsTo
    {
        return $this->belongsTo(PoliticalParty::class);
    }

    public function candidateChangeReason(): BelongsTo
{
    return $this->belongsTo(
        CandidateChangeReason::class
    );
}

    /*
    |--------------------------------------------------------------------------
    | Status Helpers
    |--------------------------------------------------------------------------
    */

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isSubmitted(): bool
    {
        return $this->status === self::STATUS_SUBMITTED;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }
}
