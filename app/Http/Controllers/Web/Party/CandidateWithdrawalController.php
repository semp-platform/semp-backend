<?php

namespace App\Http\Controllers\Web\Party;

use App\Http\Controllers\Controller;
use App\Http\Requests\Party\StoreCandidateWithdrawalRequest;
use App\Models\Nomination\Nomination;
use App\Services\Candidate\CandidateWithdrawalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Candidate\CandidateWithdrawal;
use App\Models\Reference\CandidateChangeReason;

class CandidateWithdrawalController extends Controller
{
    public function __construct(
        private readonly CandidateWithdrawalService $withdrawalService
    ) {}

    private function currentParty(Request $request)
    {
        return $request->user()
            ->politicalParties()
            ->wherePivot('is_active', true)
            ->where('political_parties.is_active', true)
            ->firstOrFail();
    }

    public function create(
        Request $request,
        Nomination $nomination
    ): View {

        $party = $this->currentParty($request);

        abort_unless(
            $nomination->political_party_id === $party->id,
            403
        );

        $reasons = CandidateChangeReason::query()
    ->where('change_type', 'withdrawal')
    ->where('is_active', true)
    ->orderBy('name')
    ->get();

return view('party.withdrawals.create', [
    'party' => $party,
    'nomination' => $nomination,
    'reasons' => $reasons,
]);
    }

    public function store(
        StoreCandidateWithdrawalRequest $request
    ): RedirectResponse {

        $party = $this->currentParty($request);

        $nomination = Nomination::findOrFail(
            $request->validated()['nomination_id']
        );

        $withdrawal = $this->withdrawalService->createForParty(
            $nomination,
            $party->id,
            $request->validated()
        );

       return redirect()
    ->route('party.withdrawals.show', $withdrawal)
    ->with(
        'success',
        'Withdrawal request created successfully.'
    );
    }
    public function show(
    Request $request,
    CandidateWithdrawal $withdrawal
): View {

    $party = $this->currentParty($request);

    abort_unless(
        $withdrawal->political_party_id === $party->id,
        403
    );

   $withdrawal->load([
    'nomination.candidate',
    'nomination.position',
    'nomination.election',
    'nomination.lga',
    'nomination.ward',
    'nomination.lcda',

    'candidateChangeReason',
]);

    return view('party.withdrawals.show', [
        'party' => $party,
        'withdrawal' => $withdrawal,
    ]);
}
}
