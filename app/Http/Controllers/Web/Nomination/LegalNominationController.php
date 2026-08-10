<?php

namespace App\Http\Controllers\Web\Nomination;

use App\Http\Controllers\Controller;
use App\Models\Nomination\Nomination;
use App\Services\Workflow\NominationWorkflowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LegalNominationController extends Controller
{
    public function __construct(
        protected NominationWorkflowService $workflowService
    ) {
    }

    /**
     * Display nominations currently assigned to Legal.
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
                NominationWorkflowService::DEPARTMENT_LEGAL
            )
            ->where(
                'workflow_status',
                Nomination::WORKFLOW_STATUS_UNDER_REVIEW
            )
            ->latest('received_at')
            ->paginate(15);

        return view('staff.legal.nominations.index', [
            'nominations' => $nominations,
        ]);
    }

    /**
     * Display a single nomination for Legal review.
     */
    public function show(
        Nomination $nomination
    ): View {
        abort_unless(
            $nomination->current_department
                === NominationWorkflowService::DEPARTMENT_LEGAL,
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

        return view('staff.legal.nominations.show', [
            'nomination' => $nomination,
        ]);
    }

    /**
     * Forward a reviewed Legal nomination to Commissioner.
     */
    public function forward(
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

        $this->workflowService->forwardFromLegal(
            $nomination,
            $validated['comment'] ?? null
        );

        return redirect()
            ->route('staff.legal.nominations.index')
            ->with(
                'success',
                'Nomination forwarded to the Commissioner successfully.'
            );
    }
}
