<?php

namespace App\Models\Nomination;

use App\Models\Candidate\Candidate;
use App\Models\Candidate\CandidateWithdrawal;
use App\Models\Election\Election;
use App\Models\Election\ElectionPosition;
use App\Models\Election\Position;
use App\Models\Party\PoliticalParty;
use App\Models\Reference\Lcda;
use App\Models\Reference\Lga;
use App\Models\Reference\Ward;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Candidate\CandidateDocumentReviewRequest;
use App\Models\ElectionResult;


class Nomination extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Workflow Departments
    |--------------------------------------------------------------------------
    |
    | Only OGSIEC internal departments are represented here.
    | The political party is NOT a workflow department.
    |
    */

    public const DEPARTMENT_ICT = 'ict';
    public const DEPARTMENT_COMMISSIONER = 'commissioner';
public const DEPARTMENT_EPM = 'epm';
public const DEPARTMENT_LEGAL = 'legal';

    /*
    |--------------------------------------------------------------------------
    | Nomination Statuses
    |--------------------------------------------------------------------------
    */

    public const STATUS_DRAFT = 'draft';
    public const STATUS_READY = 'ready';
    public const STATUS_BATCHED = 'batched';
    public const STATUS_UNDER_REVIEW = 'under_review';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_WITHDRAWN = 'withdrawn';
    public const STATUS_REPLACED = 'replaced';

    /*
    |--------------------------------------------------------------------------
    | Workflow Statuses
    |--------------------------------------------------------------------------
    */

    public const WORKFLOW_STATUS_SUBMITTED = 'submitted';
    public const WORKFLOW_STATUS_UNDER_REVIEW = 'under_review';
    public const WORKFLOW_STATUS_RETURNED = 'returned';
    public const WORKFLOW_STATUS_APPROVED = 'approved';

    protected $fillable = [
    'election_id',
    'political_party_id',
    'nomination_batch_id',
    'candidate_id',
    'position_id',
    'lga_id',
    'lcda_id',
    'ward_id',
    'status',
    'workflow_status',
    'current_department',
    'received_at',
    'completed_at',
];

    protected $casts = [
        'received_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    public function politicalParty(): BelongsTo
    {
        return $this->belongsTo(PoliticalParty::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(
            NominationBatch::class,
            'nomination_batch_id'
        );
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function lga(): BelongsTo
    {
        return $this->belongsTo(Lga::class);
    }

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    public function lcda(): BelongsTo
    {
        return $this->belongsTo(Lcda::class);
    }

    public function withdrawal(): HasOne
    {
        return $this->hasOne(CandidateWithdrawal::class);
    }

    public function workflowHistories(): HasMany
    {
        return $this->hasMany(
            NominationWorkflowHistory::class,
            'nomination_id'
        )->latest();
    }

    /*
    |--------------------------------------------------------------------------
    | Presentation Helpers
    |--------------------------------------------------------------------------
    */

    public function getCandidateNameAttribute(): string
    {
        return trim(implode(' ', array_filter([
            $this->candidate?->first_name,
            $this->candidate?->middle_name,
            $this->candidate?->last_name,
        ])));
    }

    public function getElectionTypeAttribute(): ?string
    {
        return $this->election?->electionType?->name;
    }

    public function getElectoralAreaAttribute(): string
    {
        if ($this->lcda) {
            return 'LCDA: ' . $this->lcda->name;
        }

        if ($this->ward) {
            return 'Ward: ' . $this->ward->name;
        }

        if ($this->lga) {
            return 'LGA: ' . $this->lga->name;
        }

        return 'Statewide';
    }

    public function getDisplayStatusAttribute(): string
    {
        if ($this->hasPendingWithdrawal()) {
            return 'withdrawal_pending';
        }

        return $this->status;
    }

    public function electionPosition(): ?ElectionPosition
    {
        return ElectionPosition::query()
            ->where('election_id', $this->election_id)
            ->where('position_id', $this->position_id)
            ->where('is_active', true)
            ->first();
    }

    public function getNominationFeeAttribute(): float
    {
        return (float) optional(
            $this->electionPosition()
        )->nomination_fee;
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

    public function isReady(): bool
    {
        return $this->status === self::STATUS_READY;
    }

    public function canBeWithdrawn(): bool
{
    if (
        $this->status === self::STATUS_WITHDRAWN ||
        $this->status === self::STATUS_REPLACED
    ) {
        return false;
    }

    if ($this->hasPendingWithdrawal()) {
        return false;
    }

    return in_array(
        $this->status,
        [
            self::STATUS_READY,
            self::STATUS_BATCHED,
            self::STATUS_UNDER_REVIEW,
            self::STATUS_APPROVED,
        ],
        true
    );
}

    public function isBatched(): bool
    {
        return $this->status === self::STATUS_BATCHED;
    }

    public function isUnderReview(): bool
    {
        return $this->status === self::STATUS_UNDER_REVIEW;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /*
    |--------------------------------------------------------------------------
    | Withdrawal Helpers
    |--------------------------------------------------------------------------
    */

    public function hasWithdrawal(): bool
    {
        return $this->withdrawal()->exists();
    }

    public function hasPendingWithdrawal(): bool
    {
        return optional($this->withdrawal)->status
            === CandidateWithdrawal::STATUS_DRAFT
            || optional($this->withdrawal)->status
            === CandidateWithdrawal::STATUS_SUBMITTED;
    }

    /*
    |--------------------------------------------------------------------------
    | Editing / Locking
    |--------------------------------------------------------------------------
    */

    public function canBeEdited(): bool
    {
        return $this->isDraft();
    }

   public function canBeDeleted(): bool
{
    return in_array(
        $this->status,
        [
            self::STATUS_DRAFT,
            self::STATUS_READY,
        ],
        true
    );
}

public function canBeMarkedReady(): bool
{
    return $this->isDraft();
}


    public function isLocked(): bool
    {
        return ! $this->isDraft();
    }

    /*
    |--------------------------------------------------------------------------
    | Workflow Helpers
    |--------------------------------------------------------------------------
    */

    public function isAtDepartment(string $department): bool
    {
        return $this->current_department === $department;
    }

    public function canBeReturnedToParty(): bool
    {
        return in_array(
            $this->current_department,
            [
                self::DEPARTMENT_ICT,
                self::DEPARTMENT_COMMISSIONER,
            ],
            true
        );
    }
    public function documentReviewRequests()
{
    return $this->hasMany(
        CandidateDocumentReviewRequest::class
    );
}
public function electionResults(): HasMany
{
    return $this->hasMany(ElectionResult::class);
}
}
