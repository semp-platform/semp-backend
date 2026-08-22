<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrimaryEventMonitoringReportAttachment extends Model
{
    protected $fillable = [
        'primary_event_monitoring_report_id',
        'uploaded_by',
        'category',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(
            PrimaryEventMonitoringReport::class,
            'primary_event_monitoring_report_id'
        );
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'uploaded_by'
        );
    }
}
