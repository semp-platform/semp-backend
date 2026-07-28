<?php

namespace App\Models\Election;

use Illuminate\Database\Eloquent\Model;


class ElectionType extends Model
{


    protected $table = 'election_types';

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
