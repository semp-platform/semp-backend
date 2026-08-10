<?php

namespace App\Http\Controllers\Web\Nomination;

use App\Http\Controllers\Controller;
use App\Models\Nomination\Nomination;
use App\Services\Workflow\NominationWorkflowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EpmNominationController extends Controller
{
    public function __construct(
        protected NominationWorkflowService $workflowService
    ) {
    }

    /**
     * Display nominations currently assigned to EPM.
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
                NominationWorkflowService::DEPARTMENT_EPM
            )
            ->where(
                'workflow_status',
                Nomination::WORKFLOW_STATUS_UNDER_REVIEW
            )
            ->latest('received_at')
            ->paginate(15);

        return view('staff.epm.nominations.index', [
            'nominations' => $nominations,
        ]);
    }

    /**
     * Display a single nomination for EPM vetting.
     */
    public function show(
        Nomination $nomination
    ): View {
        abort_unless(
            $nomination->current_department
                === NominationWorkflowService::DEPARTMENT_EPM,
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

        return view('staff.epm.nominations.show', [
            'nomination' => $nomination,
        ]);
    }

    /**
     * Forward a reviewed EPM nomination to Legal.
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

        $this->workflowService->forwardFromEpm(
            $nomination,
            $validated['comment'] ?? null
        );

        return redirect()
            ->route('staff.epm.nominations.index')
            ->with(
                'success',
                'Nomination forwarded to Legal successfully.'
            );
    }
}
