<?php

namespace App\Models\Party;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function users(): BelongsToMany
{
    return $this->belongsToMany(
        User::class,
        'political_party_users'
    )
        ->withPivot('is_active')
        ->withTimestamps();
}

}
