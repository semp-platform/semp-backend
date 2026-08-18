<?php

namespace App\Models;

use App\Models\Election\Election;
use App\Models\Election\Position;
use App\Models\Nomination\Nomination;
use App\Models\Reference\Lga;
use App\Models\Reference\Ward;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ElectionResult extends Model
{
    protected $fillable = [
        'election_id',
        'position_id',
        'nomination_id',
        'lga_id',
        'ward_id',
        'total_votes',
        'is_winner',
        'published_at',
    ];

    protected $casts = [
        'total_votes' => 'integer',
        'is_winner' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function nomination(): BelongsTo
    {
        return $this->belongsTo(Nomination::class);
    }

    public function lga(): BelongsTo
    {
        return $this->belongsTo(Lga::class);
    }

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }
}
