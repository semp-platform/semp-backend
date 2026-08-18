<?php

namespace App\Models;

use App\Models\Election\Election;
use App\Models\Election\Position;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResultImport extends Model
{
    protected $fillable = [
        'election_id',
        'position_id',
        'uploaded_by',
        'original_filename',
        'status',
        'worksheet_count',
        'polling_unit_count',
        'result_entry_count',
        'analysed_at',
        'imported_at',
        'published_at',
    ];

    protected $casts = [
        'analysed_at' => 'datetime',
        'imported_at' => 'datetime',
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

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function entries(): HasMany
    {
        return $this->hasMany(ResultEntry::class);
    }
}
