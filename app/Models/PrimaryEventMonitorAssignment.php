<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrimaryEventMonitorAssignment extends Model
{
    protected $fillable = [
        'primary_event_id',
        'monitor_id',
        'assigned_by',
        'assigned_at',
        'completed_at',
        'status',
        'instructions',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'completed_at' => 'datetime',
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

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'assigned_by'
        );
    }
}
