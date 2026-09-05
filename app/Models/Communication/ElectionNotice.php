<?php

namespace App\Models\Communication;

use App\Models\Election\Election;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ElectionNotice extends Model
{
    protected $table = 'election_notices';

    protected $fillable = [
        'election_id',
        'title',
        'content',
        'attachment_path',
        'status',
        'published_at',
        'created_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
