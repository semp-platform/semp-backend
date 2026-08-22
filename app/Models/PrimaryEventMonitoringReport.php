<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PrimaryEventMonitoringReport extends Model
{
    public const STATUS_SUBMITTED = 'submitted';
    public const REVIEW_PENDING = 'pending';
public const REVIEW_ACCEPTED = 'accepted';
public const REVIEW_RETURNED = 'returned';

    protected $fillable = [
        'primary_event_id',
        'monitor_id',
        'attendance_status',
        'accredited_voters',
        'votes_cast',
        'observations',
        'recommendations',
        'status',
        'submitted_at',
        'review_status',
'reviewed_by',
'reviewed_at',
'review_comment',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function primaryEvent(): BelongsTo
    {
        return $this->belongsTo(
            PrimaryEvent::class,
            'primary_event_id'
        );
    }

    public function monitor(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'monitor_id'
        );
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(
            PrimaryEventMonitoringReportAttachment::class,
            'primary_event_monitoring_report_id'
        );
    }
    public function reviewer(): BelongsTo
{
    return $this->belongsTo(
        User::class,
        'reviewed_by'
    );
}
}
