<?php

namespace App\Http\Controllers\Web\Election;

use App\Http\Controllers\Controller;
use App\Services\Election\ElectionService;
use Illuminate\View\View;

class ElectionController extends Controller
{
    public function __construct(
        private readonly ElectionService $electionService
    ) {}

    public function index(): View
    {
        return view('elections.index', [
            'elections' => $this->electionService->getAll(),
        ]);
    }

    public function show(int $election): View
    {
        return view('elections.show', [
            'election' => $this->electionService->getById($election),
        ]);
    }
}
