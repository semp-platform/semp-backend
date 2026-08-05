<?php

namespace App\Models\Nomination;

use App\Models\Election\Election;
use App\Models\Party\PoliticalParty;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Payment\BatchPayment;
use Illuminate\Database\Eloquent\Relations\HasOne;


class NominationBatch extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Statuses
    |--------------------------------------------------------------------------
    */

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PAYMENT_PENDING = 'payment_pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_UNDER_REVIEW = 'under_review';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_RETURNED = 'returned';

    /*
    |--------------------------------------------------------------------------
    | Payment Statuses
    |--------------------------------------------------------------------------
    */

    public const PAYMENT_PENDING = 'pending';
    public const PAYMENT_PAID = 'paid';
    public const PAYMENT_FAILED = 'failed';

    protected $fillable = [
        'batch_number',
        'election_id',
        'political_party_id',
        'nomination_batch_id',
        'candidate_count',
        'total_nomination_fee',
        'amount_paid',
        'payment_status',
        'status',
        'created_by',
        'submitted_by',
        'paid_at',
        'submitted_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'submitted_at' => 'datetime',
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
    public function nominations(): HasMany
{
    return $this->hasMany(
        Nomination::class,
        'nomination_batch_id'
    );
}

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }


public function payment(): HasOne
{
    return $this->hasOne(
        BatchPayment::class,
        'nomination_batch_id'
    );
}

/*
|--------------------------------------------------------------------------
| Workflow Helpers
|--------------------------------------------------------------------------
*/

public function hasPayment(): bool
{
    return $this->payment !== null;
}

public function canProceedToPayment(): bool
{
    return $this->payment &&
        $this->payment->isPending();
}

public function isAwaitingFinanceConfirmation(): bool
{
    return $this->payment &&
        $this->payment->isPaid();
}

public function canSubmit(): bool
{
    return $this->payment &&
        $this->payment->isConfirmed();
}
}

