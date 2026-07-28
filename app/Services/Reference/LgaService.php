<?php

namespace App\Services\Reference;

use App\Models\Reference\Lga;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;

class LgaService extends BaseService
{
    public function __construct(Lga $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all active LGAs belonging to a state.
     */
    public function getByState(int $stateId): Collection
    {
        return $this->model
            ->where('state_id', $stateId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }
}
