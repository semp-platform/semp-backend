<?php

namespace App\Http\Controllers\Web\Nomination;

use App\Http\Controllers\Controller;
use App\Services\Nomination\NominationService;
use Illuminate\View\View;

class NominationController extends Controller
{
    public function __construct(
        private readonly NominationService $nominationService
    ) {}

    public function index(): View
    {
        return view('nominations.index', [
            'nominations' => $this->nominationService->getAll(),
        ]);
    }

    public function show(int $nomination): View
    {
        return view('nominations.show', [
            'nomination' => $this->nominationService->getById($nomination),
        ]);
    }
}
