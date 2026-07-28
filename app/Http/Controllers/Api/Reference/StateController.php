<?php

namespace App\Http\Controllers\Api\Reference;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\StateResource;
use App\Services\Reference\StateService;

class StateController extends BaseApiController
{
    public function __construct(
        protected StateService $service
    ) {}

    /**
     * Display a listing of states.
     */
    public function index()
    {
        return $this->successResponse(
            StateResource::collection(
                $this->service->all()
            ),
            'States retrieved successfully.'
        );
    }
}
