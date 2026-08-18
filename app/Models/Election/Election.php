<?php

namespace App\Models\Election;

use App\Models\Reference\State;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Reference\Lga;
use App\Models\Reference\Ward;
use App\Models\Reference\Lcda;
use App\Models\ElectionResult;

use App\Models\Nomination\Nomination;


class Election extends Model
{
    protected $fillable = [
    'election_type_id',
    'state_id',
    'name',
    'election_date',

    'nomination_open_date',
    'nomination_close_date',
    'screening_date',
    'appeal_deadline',
    'result_declaration_date',

    'status',
    'is_active',
    'lga_id',
    'ward_id',
    'lcda_id',
];

    protected $casts = [
    'election_date' => 'date',
    'nomination_open_date' => 'date',
    'nomination_close_date' => 'date',
    'screening_date' => 'date',
    'appeal_deadline' => 'date',
    'result_declaration_date' => 'date',

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
    public function lga(): BelongsTo
{
    return $this->belongsTo(Lga::class);
}

public function ward(): BelongsTo
{
    return $this->belongsTo(Ward::class);
}

public function lcda(): BelongsTo
{
    return $this->belongsTo(Lcda::class);
}
public function nominations(): HasMany
{
    return $this->hasMany(Nomination::class);
}
public function results(): HasMany
{
    return $this->hasMany(ElectionResult::class);
}
}
