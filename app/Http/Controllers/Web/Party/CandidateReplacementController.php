<?php

namespace App\Http\Controllers\Web\Party;

use App\Http\Controllers\Controller;
use App\Http\Requests\Party\StorePartyNominationRequest;
use App\Models\Candidate\CandidateWithdrawal;
use App\Services\Candidate\CandidateReplacementService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CandidateReplacementController extends Controller
{
    public function __construct(
        private readonly CandidateReplacementService $replacementService
    ) {}

    private function currentParty(Request $request)
    {
        return $request->user()
            ->politicalParties()
            ->wherePivot('is_active', true)
            ->where('political_parties.is_active', true)
            ->firstOrFail();
    }

    public function index(Request $request): View
{
    $party = $this->currentParty($request);

    /*
     * Approved withdrawals that still need a replacement.
     */
    $withdrawals = CandidateWithdrawal::query()
        ->where('political_party_id', $party->id)
        ->where(
            'status',
            CandidateWithdrawal::STATUS_APPROVED
        )
        ->whereNull('replacement_nomination_id')
        ->with([
            'nomination.candidate',
            'nomination.election',
            'nomination.position',
            'nomination.lga',
            'nomination.lcda',
            'nomination.ward',
            'candidateChangeReason',
        ])
        ->latest('approved_at')
        ->get();

    /*
     * Approved withdrawals that already have a replacement.
     *
     * These are retained as a historical record.
     */
    $completedReplacements = CandidateWithdrawal::query()
        ->where('political_party_id', $party->id)
        ->where(
            'status',
            CandidateWithdrawal::STATUS_APPROVED
        )
        ->whereNotNull('replacement_nomination_id')
        ->with([
            'nomination.candidate',
            'nomination.election',
            'nomination.position',
            'nomination.lga',
            'nomination.lcda',
            'nomination.ward',

            'replacementNomination.candidate',
            'replacementNomination.position',
            'replacementNomination.election',
            'replacementNomination.lga',
            'replacementNomination.lcda',
            'replacementNomination.ward',
        ])
        ->latest('approved_at')
        ->get();

    return view('party.replacements.index', [
        'party' => $party,
        'withdrawals' => $withdrawals,
        'completedReplacements' => $completedReplacements,
    ]);
}

    public function create(
        Request $request,
        CandidateWithdrawal $withdrawal
    ): View {

        $party = $this->currentParty($request);

        abort_unless(
            $withdrawal->political_party_id === $party->id,
            403
        );

        abort_unless(
            $withdrawal->status === CandidateWithdrawal::STATUS_APPROVED,
            404
        );

        abort_unless(
            is_null($withdrawal->replacement_nomination_id),
            404
        );

        $withdrawal->load([
            'nomination.election',
            'nomination.position',
            'nomination.lga',
            'nomination.lcda',
            'nomination.ward',
            'nomination.candidate',
        ]);

        return view('party.replacements.create', [
            'party' => $party,
            'withdrawal' => $withdrawal,
            'nomination' => $withdrawal->nomination,
        ]);
    }

    public function store(
        StorePartyNominationRequest $request,
        CandidateWithdrawal $withdrawal
    ): RedirectResponse {

        $party = $this->currentParty($request);

        $replacement = $this->replacementService->createForParty(
            $withdrawal,
            $party->id,
            $request->validated()
        );

        return redirect()
            ->route(
                'party.nominations.show',
                $replacement
            )
            ->with(
                'success',
                'Replacement candidate registered successfully. The original nomination payment has been inherited.'
            );
    }
}
