<?php

namespace App\Http\Controllers\Web\Party;

use App\Http\Controllers\Controller;
use App\Models\Election\Election;
use App\Models\Nomination\NominationBatch;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\Nomination\NominationBatchService;
use Illuminate\Http\RedirectResponse;


class NominationBatchController extends Controller
{
    protected NominationBatchService $batchService;

public function __construct(
    NominationBatchService $batchService
) {
    $this->batchService = $batchService;
}
   private function currentParty(Request $request)
{
    return $request->user()
        ->politicalParties()
        ->wherePivot('is_active', true)
        ->where('political_parties.is_active', true)
        ->firstOrFail();
}

    /**
     * Display all nomination batches.
     */
    public function index(Request $request): View
    {
        $party = $this->currentParty($request);

        $batches = NominationBatch::query()
            ->where('political_party_id', $party->id)
            ->with('election')
            ->latest()
            ->paginate(15);

        return view('party.nomination-batches.index', [
            'party' => $party,
            'batches' => $batches,
        ]);
    }

    /**
     * Show the batch preview screen.
     */
    public function create(Request $request): View
{
    $party = $this->currentParty($request);

    $elections = $this->batchService
        ->availableElections($party);

    $selectedElection = null;
    $readyNominations = collect();
    $totalFee = 0;

    if ($request->filled('election_id')) {

        $selectedElection = Election::findOrFail(
            $request->integer('election_id')
        );

        $readyNominations = $this->batchService
            ->readyNominations(
                $party,
                $selectedElection
            );

        $totalFee = $this->batchService
            ->calculateFee($readyNominations);
    }

    return view('party.nomination-batches.create', [
        'party' => $party,
        'elections' => $elections,
        'selectedElection' => $selectedElection,
        'readyNominations' => $readyNominations,
        'totalFee' => $totalFee,
    ]);
}

    /**
     * Store a new nomination batch.
     */
    public function store(Request $request): RedirectResponse
{
    $party = $this->currentParty($request);

    $request->validate([
        'election_id' => ['required', 'exists:elections,id'],
    ]);

    $batch = $this->batchService->createBatch(
        $party,
        Election::findOrFail($request->election_id)
    );

    return redirect()
        ->route('party.nomination-batches.show', $batch)
        ->with(
            'success',
            'Nomination batch created successfully.'
        );
}

    /**
     * Display a nomination batch.
     */
    public function show(
        Request $request,
        NominationBatch $nominationBatch
    ): View {

        $party = $this->currentParty($request);

        abort_unless(
            $nominationBatch->political_party_id === $party->id,
            403
        );

        $nominationBatch->load([
    'election',
    'payment',
    'nominations.candidate',
    'nominations.position',
]);

        return view('party.nomination-batches.show', [
            'party' => $party,
            'batch' => $nominationBatch,
        ]);
    }

    public function submit(
    Request $request,
    NominationBatch $nominationBatch
): RedirectResponse {

    $party = $this->currentParty($request);

    abort_unless(
        $nominationBatch->political_party_id === $party->id,
        403
    );

    $this->batchService->submitBatch(
        $nominationBatch,
        $request->user()->id
    );

    return redirect()
        ->route('party.nomination-batches.show', $nominationBatch)
        ->with(
            'success',
            'Nomination batch submitted to OGSIEC successfully.'
        );
}
}
