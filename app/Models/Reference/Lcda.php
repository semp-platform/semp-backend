<?php

namespace App\Models\Reference;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Lcda extends Model
{
    protected $fillable = [
        'lga_id',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function lga(): BelongsTo
    {
        return $this->belongsTo(Lga::class);
    }
}
