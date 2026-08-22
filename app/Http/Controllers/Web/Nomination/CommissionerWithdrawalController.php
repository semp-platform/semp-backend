<?php

namespace App\Http\Controllers\Web\Nomination;

use App\Http\Controllers\Controller;
use App\Models\Candidate\CandidateWithdrawal;
use Illuminate\View\View;

class CommissionerWithdrawalController extends Controller
{
    /**
     * Display candidate withdrawal history.
     *
     * This page is read-only. Actual approve/reject decisions
     * are handled from the separate "Awaiting Decision" workflow.
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
     *
     * This page is read-only.
     * The actual Commissioner decision is handled
     * by the separate "Awaiting Decision" workflow.
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
}
