<?php

namespace App\Models\Payment;

use App\Models\Nomination\NominationBatch;
use App\Models\Party\PoliticalParty;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class BatchPayment extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Statuses
    |--------------------------------------------------------------------------
    */

    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_FAILED = 'failed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_CONFIRMED = 'confirmed';


    protected $fillable = [
        'nomination_batch_id',
        'political_party_id',
        'payment_reference',
        'gateway_reference',
        'amount',
        'currency',
        'gateway',
        'status',
        'paid_at',
        'gateway_response',
        'verified_by',
        'verified_at',

        'receipt_number',
'confirmed_by',
'confirmed_at',
'receipt_generated_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'verified_at' => 'datetime',
        'gateway_response' => 'array',
        'confirmed_at' => 'datetime',
        'receipt_generated_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function batch(): BelongsTo
    {
        return $this->belongsTo(
            NominationBatch::class,
            'nomination_batch_id'
        );
    }

    public function politicalParty(): BelongsTo
    {
        return $this->belongsTo(
            PoliticalParty::class
        );
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'verified_by'
        );
    }

    public function confirmedBy(): BelongsTo
{
    return $this->belongsTo(
        User::class,
        'confirmed_by'
    );
}
    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }
    public function isConfirmed(): bool
{
    return $this->status === self::STATUS_CONFIRMED;
}
}
