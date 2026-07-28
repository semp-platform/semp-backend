<?php

namespace App\Models\Election;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Position extends Model
{
    protected $table = 'positions';

    protected $fillable = [
        'name',
        'code',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function electionPositions(): HasMany
{
    return $this->hasMany(ElectionPosition::class);
}


}


