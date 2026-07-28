<?php

namespace App\Http\Controllers\Api\Election;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Election\StoreElectionRequest;
use App\Http\Resources\ElectionResource;
use App\Services\Election\ElectionService;

class ElectionController extends BaseApiController
{
    public function __construct(
        protected ElectionService $service
    ) {}

    public function store(StoreElectionRequest $request)
    {
        $election = $this->service->create(
            $request->validated()
        );

        return $this->successResponse(
            new ElectionResource($election),
            'Election created successfully.',
            201
        );
    }
}
