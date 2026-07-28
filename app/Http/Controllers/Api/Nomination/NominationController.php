<?php

namespace App\Http\Controllers\Api\Nomination;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Nomination\StoreNominationRequest;
use App\Http\Resources\NominationResource;
use App\Services\Nomination\NominationService;

class NominationController extends BaseApiController
{
    public function __construct(
        protected NominationService $service
    ) {}

    public function store(StoreNominationRequest $request)
    {
        $nomination = $this->service->create(
            $request->validated()
        );

        return $this->successResponse(
            new NominationResource($nomination),
            'Nomination created successfully.',
            201
        );
    }
    public function index()
{
    return $this->successResponse(
        NominationResource::collection(
            $this->service->getAll()
        ),
        'Nominations retrieved successfully.'
    );
}

public function show(int $nomination)
{
    return $this->successResponse(
        new NominationResource(
            $this->service->getById($nomination)
        ),
        'Nomination retrieved successfully.'
    );
}

}
