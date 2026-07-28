<?php

namespace App\Models\Election;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ElectionPosition extends Model
{
    protected $fillable = [
        'election_id',
        'position_id',
        'nomination_fee',
        'is_active',
    ];

    protected $casts = [
        'nomination_fee' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }
}
