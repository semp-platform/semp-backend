<?php

namespace App\Models\Candidate;

use App\Models\Nomination\Nomination;
use App\Models\Party\PoliticalParty;
use App\Models\Reference\CandidateChangeReason;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateWithdrawal extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Statuses
    |--------------------------------------------------------------------------
    */

    public const STATUS_DRAFT = 'draft';
    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'nomination_id',
        'political_party_id',
        'candidate_change_reason_id',
        'reason',
        'remarks',
        'supporting_evidence_path',
        'replacement_nomination_id',
        'status',
        'submitted_at',
        'approved_at',
        'rejected_at',
        'reviewed_by',
        'reviewed_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * The original nomination being withdrawn.
     */
    public function nomination(): BelongsTo
    {
        return $this->belongsTo(
            Nomination::class,
            'nomination_id'
        );
    }

    /**
     * The political party that initiated the withdrawal.
     */
    public function politicalParty(): BelongsTo
    {
        return $this->belongsTo(
            PoliticalParty::class,
            'political_party_id'
        );
    }

    /**
     * Reason for the withdrawal/replacement.
     */
    public function candidateChangeReason(): BelongsTo
    {
        return $this->belongsTo(
            CandidateChangeReason::class,
            'candidate_change_reason_id'
        );
    }

    /**
     * Replacement nomination created after approval.
     */
    public function replacementNomination(): BelongsTo
    {
        return $this->belongsTo(
            Nomination::class,
            'replacement_nomination_id'
        );
    }

    /**
     * Commissioner/staff member who reviewed the request.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'reviewed_by'
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

    /*
    |--------------------------------------------------------------------------
    | Workflow Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Whether this withdrawal has been reviewed by OGSIEC.
     */
    public function isReviewed(): bool
    {
        return in_array(
            $this->status,
            [
                self::STATUS_APPROVED,
                self::STATUS_REJECTED,
            ],
            true
        );
    }

    /**
     * Whether this withdrawal has an approved replacement.
     */
    public function hasReplacement(): bool
    {
        return ! is_null($this->replacement_nomination_id);
    }
}
