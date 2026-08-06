<?php

namespace App\Http\Controllers\Web\Party;

use App\Http\Controllers\Controller;
use App\Models\Election\Election;
use App\Models\Nomination\NominationBatch;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\Nomination\NominationBatchService;
use Illuminate\Http\RedirectResponse;
use App\Enums\Department;
use App\Enums\WorkflowAction;
use App\Models\Nomination\BatchWorkflow;

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

    /**
 * Submit a nomination batch to OGSIEC.
 */
public function submit(
    Request $request,
    NominationBatch $nominationBatch
): RedirectResponse {

    $party = $this->currentParty($request);

    abort_unless(
        $nominationBatch->political_party_id === $party->id,
        403
    );

    if ($nominationBatch->status === NominationBatch::STATUS_SUBMITTED) {

    return back()->with(
        'success',
        'This nomination batch has already been submitted.'
    );

}

    $nominationBatch->load('payment');

    if (! $nominationBatch->canSubmit()) {

        return back()->withErrors([
            'batch' => 'This batch cannot be submitted yet.',
        ]);

    }

    $nominationBatch->status = NominationBatch::STATUS_SUBMITTED;

$nominationBatch->current_department = Department::ICT->value;

$nominationBatch->submitted_by = $request->user()->id;

$nominationBatch->submitted_at = now();

$nominationBatch->save();


    BatchWorkflow::create([

        'nomination_batch_id' => $nominationBatch->id,

        'department' => Department::Party->value,

        'action' => WorkflowAction::Submitted->value,

        'remarks' => 'Nomination batch submitted to OGSIEC.',

        'acted_by' => $request->user()->id,

        'acted_at' => now(),

    ]);

    return redirect()
        ->route(
            'party.nomination-batches.show',
            $nominationBatch
        )
        ->with(
            'success',
            'Nomination batch submitted successfully.'
        );
}


}



