<?php

namespace App\Models\Nomination;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NominationWorkflowHistory extends Model
{
    protected $fillable = [
        'nomination_id',
        'from_department',
        'to_department',
        'action',
        'user_id',
        'comment',
        'reason',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function nomination(): BelongsTo
    {
        return $this->belongsTo(
            Nomination::class,
            'nomination_id'
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }
}
