<?php

namespace App\Http\Controllers\Web\Election;

use App\Http\Controllers\Controller;
use App\Http\Requests\Election\StoreElectionRequest;
use App\Models\Election\Election;
use App\Models\Election\ElectionType;
use App\Models\Election\Position;
use App\Models\Reference\State;
use App\Services\Election\ElectionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Requests\Election\UpdateElectionRequest;

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

    public function create(): View
    {
        return view('elections.create', [
            'electionTypes' => ElectionType::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),

            'states' => State::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),

            'positions' => Position::query()
                ->where('is_active', true)
                ->orderBy('display_order')
                ->get(),
        ]);
    }

    public function store(StoreElectionRequest $request): RedirectResponse
    {
        $election = $this->electionService->create(
            $request->validated()
        );

        return redirect()
            ->route('elections.show', $election->id)
            ->with('success', 'Election created successfully.');
    }

    public function show(int $election): View
    {
        return view('elections.show', [
            'election' => $this->electionService->getById($election),
        ]);
    }
    public function edit(int $election): View
{
    return view('elections.edit', [
        'election' => $this->electionService->getById($election),

        'electionTypes' => ElectionType::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(),

        'states' => State::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(),

        'positions' => Position::query()
            ->where('is_active', true)
            ->orderBy('display_order')
            ->get(),
    ]);
}

public function update(
    UpdateElectionRequest $request,
    int $election
): RedirectResponse {
    $this->electionService->update(
        $election,
        $request->validated()
    );

    return redirect()
        ->route('elections.show', $election)
        ->with('success', 'Election updated successfully.');
}
public function openNominations(Election $election): RedirectResponse
{
    $this->electionService->openNominations($election);

    return redirect()
        ->route('elections.show', $election)
        ->with('success', 'Nominations have been opened.');
}

public function startScreening(Election $election): RedirectResponse
{
    $this->electionService->startScreening($election);

    return redirect()
        ->route('elections.show', $election)
        ->with('success', 'Screening has started.');
}

public function complete(Election $election): RedirectResponse
{
    $this->electionService->complete($election);

    return redirect()
        ->route('elections.show', $election)
        ->with('success', 'Election marked as completed.');
}

public function archive(Election $election): RedirectResponse
{
    $this->electionService->archive($election);

    return redirect()
        ->route('elections.show', $election)
        ->with('success', 'Election archived successfully.');
}
}
