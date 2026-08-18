<?php

namespace App\Http\Controllers\Web\Nomination;

use App\Http\Controllers\Controller;
use App\Models\Candidate\CandidateWithdrawal;
use Illuminate\View\View;
use App\Services\Candidate\CandidateWithdrawalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommissionerWithdrawalController extends Controller
{
    public function __construct(
    protected CandidateWithdrawalService $withdrawalService
) {
}
    /**
     * Display withdrawal requests currently awaiting
     * Commissioner decision.
     */
    public function index(): View
    {
        $withdrawals = CandidateWithdrawal::query()
            ->with([
                'nomination.candidate',
                'nomination.position',
                'nomination.politicalParty',
                'nomination.election',
                'candidateChangeReason',
            ])
            ->where(
                'status',
                CandidateWithdrawal::STATUS_SUBMITTED
            )
            ->latest('submitted_at')
            ->paginate(15);

        return view('staff.commissioner.withdrawals.index', [
            'withdrawals' => $withdrawals,
        ]);
    }
    public function show(
    CandidateWithdrawal $withdrawal
): View {
    abort_unless(
        $withdrawal->status === CandidateWithdrawal::STATUS_SUBMITTED,
        404
    );

    $withdrawal->load([
        'nomination.candidate',
        'nomination.position',
        'nomination.election',
        'nomination.lga',
        'nomination.politicalParty',
        'candidateChangeReason',
    ]);

    return view('staff.commissioner.withdrawals.show', [
        'withdrawal' => $withdrawal,
    ]);
}
public function approve(
    CandidateWithdrawal $withdrawal
): RedirectResponse {

    $this->withdrawalService->approve(
        $withdrawal,
        auth()->id()
    );

    return redirect()
        ->route('staff.commissioner.withdrawals.index')
        ->with(
            'success',
            'Candidate withdrawal approved successfully.'
        );
}


public function reject(
    Request $request,
    CandidateWithdrawal $withdrawal
): RedirectResponse {

    $validated = $request->validate([
        'reason' => [
            'required',
            'string',
            'max:2000',
        ],
    ]);

    $this->withdrawalService->reject(
        $withdrawal,
        auth()->id()
    );

    return redirect()
        ->route('staff.commissioner.withdrawals.index')
        ->with(
            'success',
            'Candidate withdrawal rejected successfully.'
        );
}
}
