<?php

namespace App\Models\Reference;

use Illuminate\Database\Eloquent\Model;

class CandidateChangeReason extends Model
{
    protected $fillable = [
        'change_type',
        'code',
        'name',
        'requires_document',
        'is_active',
    ];

    protected $casts = [
        'requires_document' => 'boolean',
        'is_active' => 'boolean',
    ];
}
