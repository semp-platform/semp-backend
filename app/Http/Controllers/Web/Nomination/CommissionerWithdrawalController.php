<?php

namespace App\Http\Controllers\Web\Nomination;

use App\Http\Controllers\Controller;
use App\Models\Candidate\CandidateWithdrawal;
use App\Services\Candidate\CandidateWithdrawalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommissionerWithdrawalController extends Controller
{
    public function __construct(
        protected CandidateWithdrawalService $withdrawalService
    ) {
    }

    /**
     * Display candidate withdrawal requests.
     */
    public function index(): View
    {
        $withdrawals = CandidateWithdrawal::query()
            ->with([
                'nomination.candidate',
                'nomination.election',
                'nomination.position',
                'nomination.politicalParty',
                'politicalParty',
                'candidateChangeReason',
                'reviewer',
            ])
            ->whereIn('status', [
                CandidateWithdrawal::STATUS_SUBMITTED,
                CandidateWithdrawal::STATUS_APPROVED,
                CandidateWithdrawal::STATUS_REJECTED,
            ])
            ->latest('submitted_at')
            ->paginate(20);

        return view(
            'staff.commissioner.withdrawals.index',
            [
                'withdrawals' => $withdrawals,
            ]
        );
    }

    /**
     * Display withdrawal details.
     */
    public function show(
        CandidateWithdrawal $withdrawal
    ): View {
        $withdrawal->load([
            'nomination.candidate',
            'nomination.position',
            'nomination.election',
            'nomination.lga',
            'nomination.politicalParty',
            'candidateChangeReason',
            'reviewer',
            'replacementNomination',
        ]);

        return view(
            'staff.commissioner.withdrawals.show',
            [
                'withdrawal' => $withdrawal,
            ]
        );
    }

    /**
     * Approve a candidate withdrawal request.
     */
    public function approve(
        Request $request,
        CandidateWithdrawal $withdrawal
    ): RedirectResponse {
        $validated = $request->validate([
            'comment' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $this->withdrawalService->approve(
            $withdrawal,
            $request->user()->id
        );

        return redirect()
            ->route(
                'staff.commissioner.withdrawals.show',
                $withdrawal
            )
            ->with(
                'success',
                'Candidate withdrawal request approved successfully.'
            );
    }

    /**
     * Reject a candidate withdrawal request.
     */
    public function reject(
        Request $request,
        CandidateWithdrawal $withdrawal
    ): RedirectResponse {
        $validated = $request->validate([
            'comment' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $this->withdrawalService->reject(
            $withdrawal,
            $request->user()->id,
            $validated['comment'] ?? null
        );

        return redirect()
            ->route(
                'staff.commissioner.withdrawals.show',
                $withdrawal
            )
            ->with(
                'success',
                'Candidate withdrawal request denied.'
            );
    }
}
