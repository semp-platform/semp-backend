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
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasOne;



class PrimaryEvent extends Model
{
    public const TYPE_DIRECT = 'direct';
    public const TYPE_INDIRECT = 'indirect';
    public const TYPE_CONSENSUS = 'consensus';

    public const NOTICE_PENDING = 'pending';
    public const NOTICE_RECEIVED = 'received';
    public const NOTICE_LATE = 'late';
    public const NOTICE_INCOMPLETE = 'incomplete';
    public const NOTICE_VERIFIED = 'verified';

    public const STATUS_SCHEDULED = 'scheduled';
    public const STATUS_MONITOR_ASSIGNED = 'monitor_assigned';
    public const STATUS_MONITORING = 'monitoring';
    public const STATUS_REPORT_PENDING = 'report_pending';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FLAGGED = 'flagged';
    public const STATUS_CLOSED = 'closed';

    protected $fillable = [
        'party_primary_notice_id',
        'election_id',
        'political_party_id',
        'position_id',
        'lga_id',
        'lcda_id',
        'ward_id',
        'primary_type',
        'scheduled_date',
        'scheduled_time',
        'venue',
        'notice_received_at',
        'notice_status',
        'status',
        'created_by',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'notice_received_at' => 'datetime',
    ];

    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    public function politicalParty(): BelongsTo
    {
        return $this->belongsTo(
            PoliticalParty::class,
            'political_party_id'
        );
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

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function partyPrimaryNotice(): BelongsTo
{
    return $this->belongsTo(
        \App\Models\PartyPrimaryNotice::class,
        'party_primary_notice_id'
    );
}

    public function assignments(): HasMany
{
    return $this->hasMany(
        PrimaryEventMonitorAssignment::class,
        'primary_event_id'
    )->latest('assigned_at');
}

public function currentAssignment()
{
    return $this->hasOne(
        PrimaryEventMonitorAssignment::class,
        'primary_event_id'
    )
        ->where('status', 'assigned')
        ->latestOfMany('assigned_at');
}
public function monitoringReport(): HasOne
{
    return $this->hasOne(
        PrimaryEventMonitoringReport::class,
        'primary_event_id'
    );
}
public function monitoringReports(): HasMany
{
    return $this->hasMany(
        PrimaryEventMonitoringReport::class,
        'primary_event_id'
    );
}
}

