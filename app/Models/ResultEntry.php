<?php

namespace App\Models;

use App\Models\Party\PoliticalParty;
use App\Models\Reference\Lga;
use App\Models\Reference\Ward;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResultEntry extends Model
{
    protected $fillable = [
        'result_import_id',
        'lga_id',
        'ward_id',
        'political_party_id',
        'polling_unit_code',
        'polling_unit_name',
        'votes',
    ];

    protected $casts = [
        'votes' => 'integer',
    ];

    public function resultImport(): BelongsTo
    {
        return $this->belongsTo(ResultImport::class);
    }

    public function lga(): BelongsTo
    {
        return $this->belongsTo(Lga::class);
    }

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    public function politicalParty(): BelongsTo
    {
        return $this->belongsTo(PoliticalParty::class);
    }
}
