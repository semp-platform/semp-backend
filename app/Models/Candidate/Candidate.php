<?php

namespace App\Models\Candidate;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = [
        'nin_encrypted',
        'nin_hash',
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'date_of_birth',
        'nin_verified_at',
        'is_active',
    ];

    protected $hidden = [
        'nin_encrypted',
        'nin_hash',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'nin_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];
}
