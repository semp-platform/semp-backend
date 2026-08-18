<?php

namespace App\Models\Candidate;

use Illuminate\Database\Eloquent\Model;
use App\Models\Candidate\CandidateDocument;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Nomination\Nomination;
use Illuminate\Database\Eloquent\Relations\HasOne;


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
        'has_disability',
        'qualification',
'qualification_details',
'disability_description',
            ];

    protected $hidden = [
        'nin_encrypted',
        'nin_hash',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'nin_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'has_disability' => 'boolean',
    ];

    public function getFullNameAttribute(): string
{
    return collect([
        $this->first_name,
        $this->middle_name,
        $this->last_name,
    ])
        ->filter()
        ->implode(' ');
}

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function documents()
{
    return $this->hasMany(CandidateDocument::class);
}

public function nomination(): HasOne
{
    return $this->hasOne(Nomination::class);
}

}
