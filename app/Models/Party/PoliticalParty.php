<?php

namespace App\Models\Party;

use Illuminate\Database\Eloquent\Model;

class PoliticalParty extends Model
{
    protected $fillable = [
        'name',
        'acronym',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
