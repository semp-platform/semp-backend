<?php

namespace App\Services\Reference;

use App\Models\Reference\State;

class StateService
{
    /**
     * Get all active states.
     */
    public function getAll()
    {
        return State::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }
}
