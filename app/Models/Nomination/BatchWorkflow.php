<?php

namespace App\Models\Nomination;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BatchWorkflow extends Model
{
    protected $table = 'nomination_batch_workflows';

    protected $fillable = [

        'nomination_batch_id',

        'department',

        'action',

        'remarks',

        'acted_by',

        'acted_at',

    ];

    protected $casts = [

        'acted_at' => 'datetime',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function batch(): BelongsTo
    {
        return $this->belongsTo(
            NominationBatch::class,
            'nomination_batch_id'
        );
    }

    public function officer(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'acted_by'
        );
    }
}
