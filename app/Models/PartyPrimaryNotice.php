<?php

namespace App\Models;

use App\Models\Election\Election;
use App\Models\Election\Position;
use App\Models\Party\PoliticalParty;
use App\Models\Reference\Lga;
use App\Models\Reference\Lcda;
use App\Models\Reference\Ward;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\PrimaryEvent;


class PartyPrimaryNotice extends Model
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_RECEIVED = 'received';
    public const STATUS_UNDER_REVIEW = 'under_review';
    public const STATUS_RETURNED = 'returned';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_CANCELLED = 'cancelled';

    public const TYPE_DIRECT = 'direct';
    public const TYPE_INDIRECT = 'indirect';
    public const TYPE_CONSENSUS = 'consensus';

    protected $fillable = [
        'political_party_id',
         'party_primary_notice_id',
        'election_id',
        'position_id',

        'primary_type',

        'scheduled_date',
        'scheduled_time',
        'venue',

        'lga_id',
        'lcda_id',
        'ward_id',

        'status',

        'submitted_at',
        'submitted_by',

        'received_at',

        'reviewed_at',
        'reviewed_by',
        'review_comment',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'submitted_at' => 'datetime',
        'received_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function politicalParty(): BelongsTo
    {
        return $this->belongsTo(
            PoliticalParty::class,
            'political_party_id'
        );
    }

    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function lga(): BelongsTo
    {
        return $this->belongsTo(Lga::class);
    }

    public function lcda(): BelongsTo
    {
        return $this->belongsTo(Lcda::class);
    }

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'submitted_by'
        );
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'reviewed_by'
        );
    }
public function submittedBy(): BelongsTo
{
    return $this->belongsTo(
        User::class,
        'submitted_by'
    );
}

public function reviewedBy(): BelongsTo
{
    return $this->belongsTo(
        User::class,
        'reviewed_by'
    );
}
public function primaryEvent(): HasOne
{
    return $this->hasOne(
        PrimaryEvent::class,
        'party_primary_notice_id'
    );
}

}
