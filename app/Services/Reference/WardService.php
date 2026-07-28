<?php

namespace App\Services\Reference;

use App\Models\Reference\Ward;
use Illuminate\Database\Eloquent\Collection;

class WardService
{
    public function getByLga(int $lgaId): Collection
    {
        return Ward::query()
            ->where('lga_id', $lgaId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }
}
