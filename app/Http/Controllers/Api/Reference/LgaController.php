<?php

namespace App\Http\Controllers\Api\Reference;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\LgaResource;
use App\Models\Reference\State;
use App\Services\Reference\LgaService;

class LgaController extends BaseApiController
{
    public function __construct(
        protected LgaService $service
    ) {}

    /**
     * Return all active LGAs.
     */
    public function index()
    {
        return $this->successResponse(
            LgaResource::collection(
                $this->service->all()
            ),
            'LGAs retrieved successfully.'
        );
    }

    /**
     * Return active LGAs belonging to a state.
     */
    public function byState(State $state)
    {
        return $this->successResponse(
            LgaResource::collection(
                $this->service->getByState($state->id)
            ),
            'LGAs retrieved successfully.'
        );
    }
}
