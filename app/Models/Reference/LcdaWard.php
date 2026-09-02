<?php

namespace App\Models\Reference;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LcdaWard extends Model
{
    protected $fillable = [
        'lcda_id',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function lcda(): BelongsTo
    {
        return $this->belongsTo(Lcda::class);
    }
}
