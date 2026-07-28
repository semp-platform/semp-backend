<?php

namespace App\Http\Controllers\Api\Reference;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\WardResource;
use App\Models\Reference\Lga;
use App\Services\Reference\WardService;

class WardController extends BaseApiController
{
    public function __construct(
        protected WardService $service
    ) {}

    public function byLga(Lga $lga)
    {
        return $this->successResponse(
            WardResource::collection(
                $this->service->getByLga($lga->id)
            ),
            'Wards retrieved successfully.'
        );
    }
}
