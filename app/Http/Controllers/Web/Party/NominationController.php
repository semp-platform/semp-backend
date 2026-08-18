<?php

namespace App\Http\Controllers\Web\Party;

use App\Http\Controllers\Controller;
use App\Http\Requests\Party\StorePartyNominationRequest;
use App\Models\Election\Election;
use App\Models\Reference\Lga;
use App\Services\Nomination\NominationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\Candidate\CandidateService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Party\UpdatePartyNominationRequest;
use App\Models\Nomination\Nomination;

class NominationController extends Controller
{
    public function __construct(
    private readonly NominationService $nominationService,
    private readonly CandidateService $candidateService
) {}

public function index(Request $request): View
{
    $party = $this->currentParty($request);

    $nominations = \App\Models\Nomination\Nomination::query()
        ->where('political_party_id', $party->id)
       ->with([
    'candidate',
    'election',
    'position',
    'lga',
    'ward',
    'lcda',
])
        ->latest()
        ->get();

    return view('party.nominations.index', [
        'party' => $party,
        'nominations' => $nominations,
    ]);
}

public function show(
    Request $request,
    int $nomination
): View {
    $party = $this->currentParty($request);

    $nomination = \App\Models\Nomination\Nomination::query()
        ->where('political_party_id', $party->id)
      ->with([
    'candidate',
    'election',
    'position',
    'lga',
    'ward',
    'lcda',
    'withdrawal',
    'documentReviewRequests.candidateDocument.documentType',
])
        ->findOrFail($nomination);

        $requiredDocuments = \App\Models\DocumentType::active()
    ->where('required', true)
    ->count();

$uploadedDocuments = $nomination->candidate
    ->documents()
    ->whereIn(
        'document_type_id',
        \App\Models\DocumentType::active()
            ->where('required', true)
            ->pluck('id')
    )
    ->count();

$documentsComplete = ($uploadedDocuments >= $requiredDocuments);

    return view('party.nominations.show', [
    'party' => $party,
    'nomination' => $nomination,
    'requiredDocuments' => $requiredDocuments,
    'uploadedDocuments' => $uploadedDocuments,
    'documentsComplete' => $documentsComplete,
]);
}
    public function create(Request $request): View
    {

            $party = $this->currentParty($request);

        $elections = Election::query()
            ->where('is_active', true)
            ->with([
                'electionPositions' => function ($query) {
                    $query->where('is_active', true);
                },
                'electionPositions.position',
            ])
            ->orderByDesc('election_date')
            ->get();

        $lgas = Lga::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get([
                'id',
                'state_id',
                'name',
            ]);

        return view('party.nominations.create', [
            'party' => $party,
            'elections' => $elections,
            'lgas' => $lgas,
        ]);
    }
public function verifyNin(Request $request): JsonResponse
{
    $validated = $request->validate([
        'nin' => [
            'required',
            'string',
            'regex:/^\d{11}$/',
        ],
    ]);

    $candidate = $this->candidateService->verifyNin(
        $validated['nin']
    );

    return response()->json([
        'message' => 'NIN verified successfully.',
        'data' => $candidate,
    ]);
}
    public function store(
        StorePartyNominationRequest $request
    ): RedirectResponse {
        $party = $this->currentParty($request);

        $this->nominationService->createForParty(
            $request->validated(),
            $party->id
        );

        return redirect()
    ->route('party.nominations.index')
    ->with(
        'success',
        'Candidate nomination saved successfully as draft.'
    );
    }

    public function edit(
    Request $request,
    int $nomination
): View {
    $party = $this->currentParty($request);
    $nomination = Nomination::query()
        ->where('political_party_id', $party->id)
        ->with([
    'candidate',
    'election',
    'position',
    'lga',
    'ward',
    'lcda',
])
        ->findOrFail($nomination);

    /*
     * Submitted/approved/etc. nominations
     * cannot enter the edit screen.
     */
    abort_unless(
    $nomination->canBeEdited(),
    403,
    'This nomination can no longer be edited.'
);

    $elections = Election::query()
        ->where('is_active', true)
        ->with([
            'electionPositions' => function ($query) {
                $query->where('is_active', true);
            },
            'electionPositions.position',
        ])
        ->orderByDesc('election_date')
        ->get();

    $lgas = Lga::query()
        ->where('is_active', true)
        ->orderBy('name')
        ->get([
            'id',
            'state_id',
            'name',
        ]);

    return view('party.nominations.edit', [
        'party' => $party,
        'nomination' => $nomination,
        'elections' => $elections,
        'lgas' => $lgas,
    ]);
}

public function update(
    UpdatePartyNominationRequest $request,
    int $nomination
): RedirectResponse {
   $party = $this->currentParty($request);

    $nomination = Nomination::query()
        ->where('political_party_id', $party->id)
        ->findOrFail($nomination);

    abort_unless(
        $nomination->canBeEdited(),
        403,
        'This nomination can no longer be edited.'
    );

    $nomination = $this->nominationService->updateForParty(
        $nomination,
        $request->validated(),
        $party->id
    );

    return redirect()
        ->route('party.nominations.show', $nomination->id)
        ->with(
            'success',
            'Candidate nomination updated successfully.'
        );
}

public function destroy(
    Request $request,
    int $nomination
): RedirectResponse {

    $party = $this->currentParty($request);

    $nomination = Nomination::query()
        ->where('political_party_id', $party->id)
        ->with('withdrawal')
        ->findOrFail($nomination);

    abort_unless(
        $nomination->canBeDeleted(),
        403,
        'This nomination can no longer be removed directly.'
    );

    $this->nominationService->deleteForParty(
        $nomination,
        $party->id
    );

    return redirect()
        ->route('party.nominations.index')
        ->with(
            'success',
            'Candidate nomination removed successfully.'
        );
}
public function markReady(
    Request $request,
    int $nomination
): RedirectResponse {

    $party = $this->currentParty($request);

    $nomination = Nomination::query()
        ->where('political_party_id', $party->id)
        ->with('candidate.documents')
        ->findOrFail($nomination);

    abort_unless(
        $nomination->canBeMarkedReady(),
        403,
        'This nomination cannot be marked as ready.'
    );

    /*
    |--------------------------------------------------------------------------
    | Candidate must exist
    |--------------------------------------------------------------------------
    */

    if (! $nomination->candidate) {

        return back()->withErrors([
            'nomination' => 'Candidate information is incomplete.',
        ]);

    }

    /*
    |--------------------------------------------------------------------------
    | NIN must be verified
    |--------------------------------------------------------------------------
    */

    if (! $nomination->candidate->nin_verified_at) {

        return back()->withErrors([
            'nomination' => 'Candidate NIN has not been verified.',
        ]);

    }

    /*
    |--------------------------------------------------------------------------
    | All required documents must be uploaded
    |--------------------------------------------------------------------------
    */

    $requiredDocuments = \App\Models\DocumentType::active()
        ->where('required', true)
        ->count();

    $uploadedDocuments = $nomination->candidate
        ->documents()
        ->whereIn(
            'document_type_id',
            \App\Models\DocumentType::active()
                ->where('required', true)
                ->pluck('id')
        )
        ->count();

    if ($uploadedDocuments < $requiredDocuments) {

        return back()->withErrors([
            'nomination' => 'Please upload all required documents before marking this nomination as ready.',
        ]);

    }

    /*
    |--------------------------------------------------------------------------
    | Pending withdrawal
    |--------------------------------------------------------------------------
    */

    if ($nomination->hasPendingWithdrawal()) {

        return back()->withErrors([
            'nomination' => 'This nomination has a pending withdrawal request.',
        ]);

    }

    /*
    |--------------------------------------------------------------------------
    | Mark Ready
    |--------------------------------------------------------------------------
    */

    $nomination = $this->nominationService->markReadyForParty(
        $nomination,
        $party->id
    );

    return redirect()
        ->route('party.nominations.show', $nomination)
        ->with(
            'success',
            'Candidate nomination marked as ready for batching.'
        );

}
private function currentParty(Request $request)
{
    return $request->user()
        ->politicalParties()
        ->wherePivot('is_active', true)
        ->where('political_parties.is_active', true)
        ->firstOrFail();
}

}
