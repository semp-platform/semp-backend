<?php

namespace App\Models\Election;

use App\Models\Reference\State;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Election extends Model
{
    protected $fillable = [
        'election_type_id',
        'state_id',
        'name',
        'election_date',
        'status',
        'is_active',
    ];

    protected $casts = [
        'election_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function electionType(): BelongsTo
    {
        return $this->belongsTo(ElectionType::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }
    public function electionPositions(): HasMany
{
    return $this->hasMany(ElectionPosition::class);
}

}
