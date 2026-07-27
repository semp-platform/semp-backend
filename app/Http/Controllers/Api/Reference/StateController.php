<?php

namespace App\Http\Controllers\Api\Reference;

use App\Http\Controllers\Controller;
use App\Http\Resources\StateResource;
use App\Services\Reference\StateService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class StateController extends Controller
{
    public function __construct(
        private readonly StateService $stateService
    ) {}

    /**
     * Display a listing of active states.
     */
    public function index(): AnonymousResourceCollection
    {
        return StateResource::collection(
            $this->stateService->getAll()
        );
    }
}
