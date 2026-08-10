<?php

namespace App\Http\Controllers\Web\Nomination;

use App\Http\Controllers\Controller;
use App\Models\Nomination\Nomination;
use App\Services\Workflow\NominationWorkflowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommissionerNominationController extends Controller
{
    public function __construct(
        protected NominationWorkflowService $workflowService
    ) {
    }

    /**
     * Display nominations currently awaiting Commissioner decision.
     */
    public function index(): View
    {
        $nominations = Nomination::query()
            ->with([
                'candidate',
                'position',
                'politicalParty',
                'election',
                'batch',
            ])
            ->where(
                'current_department',
                NominationWorkflowService::DEPARTMENT_COMMISSIONER
            )
            ->where(
                'workflow_status',
                Nomination::WORKFLOW_STATUS_UNDER_REVIEW
            )
            ->latest('received_at')
            ->paginate(15);

        return view('staff.commissioner.nominations.index', [
            'nominations' => $nominations,
        ]);
    }

    /**
     * Display a single nomination for Commissioner decision.
     */
    public function show(
        Nomination $nomination
    ): View {
        abort_unless(
            $nomination->current_department
                === NominationWorkflowService::DEPARTMENT_COMMISSIONER,
            404
        );

        $nomination->load([
            'candidate',
            'candidate.documents',
            'position',
            'politicalParty',
            'election',
            'batch',
            'workflowHistories',
        ]);

        return view('staff.commissioner.nominations.show', [
            'nomination' => $nomination,
        ]);
    }

    /**
     * Approve a nomination.
     */
    public function approve(
        Request $request,
        Nomination $nomination
    ): RedirectResponse {
        $validated = $request->validate([
            'comment' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $this->workflowService->approve(
            $nomination,
            $validated['comment'] ?? null
        );

        return redirect()
            ->route('staff.commissioner.nominations.index')
            ->with(
                'success',
                'Nomination approved successfully.'
            );
    }

    /**
     * Return a nomination based on screening recommendations.
     */
    public function return(
        Request $request,
        Nomination $nomination
    ): RedirectResponse {
        $validated = $request->validate([
            'reason' => [
                'required',
                'string',
                'max:2000',
            ],
            'comment' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $this->workflowService->returnFromCommissioner(
            $nomination,
            $validated['reason'],
            $validated['comment'] ?? null
        );

        return redirect()
            ->route('staff.commissioner.nominations.index')
            ->with(
                'success',
                'Nomination returned successfully.'
            );
    }
}
