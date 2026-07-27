<?php

namespace App\Models\Reference;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{

    protected $fillable = [
        'name',
        'code',
        'capital',
        'geopolitical_zone',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
