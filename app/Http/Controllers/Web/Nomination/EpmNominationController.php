<?php

namespace App\Http\Controllers\Web\Nomination;

use App\Http\Controllers\Controller;
use App\Models\Nomination\Nomination;
use App\Services\Workflow\NominationWorkflowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Nomination\NominationWorkflowHistory;
use App\Models\Election\Election;
use App\Models\Election\Position;


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
/**
 * Display a retained historical EPM record.
 *
 * This page remains accessible after the nomination
 * has moved to another department.
 */
public function records(Request $request): View
{
    $search = trim((string) $request->input('search'));
    $workflowStatus = $request->input('workflow_status');
    $department = $request->input('department');
    $electionId = $request->input('election_id');
    $positionId = $request->input('position_id');

    $nominations = Nomination::query()
        ->with([
            'candidate',
            'position',
            'politicalParty',
            'election',
            'batch',
            'workflowHistories',
        ])

        /*
        |--------------------------------------------------------------------------
        | EPM historical records
        |--------------------------------------------------------------------------
        |
        | Keep nominations that have actually passed through EPM.
        | This is what makes the archive persistent after forwarding.
        |
        */
        ->whereHas('workflowHistories', function ($query) {
            $query->where(
                'from_department',
                NominationWorkflowService::DEPARTMENT_EPM
            );
        })

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        ->when($search !== '', function ($query) use ($search) {
            $query->where(function ($query) use ($search) {

                $query
                    ->where('id', $search)

                    ->orWhereHas('candidate', function ($query) use ($search) {
                        $query->where('first_name', 'like', "%{$search}%")
                            ->orWhere('middle_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    })

                    ->orWhereHas('politicalParty', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    });
            });
        })

        /*
        |--------------------------------------------------------------------------
        | Workflow status
        |--------------------------------------------------------------------------
        */
        ->when(
            in_array(
                $workflowStatus,
                [
                    Nomination::WORKFLOW_STATUS_SUBMITTED,
                    Nomination::WORKFLOW_STATUS_UNDER_REVIEW,
                    Nomination::WORKFLOW_STATUS_RETURNED,
                    Nomination::WORKFLOW_STATUS_APPROVED,
                ],
                true
            ),
            function ($query) use ($workflowStatus) {
                $query->where('workflow_status', $workflowStatus);
            }
        )

        /*
        |--------------------------------------------------------------------------
        | Current department
        |--------------------------------------------------------------------------
        */
        ->when($department, function ($query) use ($department) {
            $query->where('current_department', $department);
        })

        /*
        |--------------------------------------------------------------------------
        | Election
        |--------------------------------------------------------------------------
        */
        ->when($electionId, function ($query) use ($electionId) {
            $query->where('election_id', $electionId);
        })

        /*
        |--------------------------------------------------------------------------
        | Position
        |--------------------------------------------------------------------------
        */
        ->when($positionId, function ($query) use ($positionId) {
            $query->where('position_id', $positionId);
        })

        ->latest('updated_at')
        ->paginate(15)
        ->withQueryString();

$elections = Election::query()
    ->orderBy('name')
    ->get();

$positions = Position::query()
    ->orderBy('name')
    ->get();

return view('staff.epm.records.index', [
    'nominations' => $nominations,
    'elections' => $elections,
    'positions' => $positions,
    'search' => $search,
    'workflowStatus' => $workflowStatus,
    'department' => $department,
    'electionId' => $electionId,
    'positionId' => $positionId,
]);
}

/**
 * Display a retained historical EPM record.
 *
 * This page remains accessible after the nomination
 * has moved to another department.
 */
public function record(
    Nomination $nomination
): View {
    abort_unless(
        $nomination->workflowHistories()
            ->where(
                'from_department',
                NominationWorkflowService::DEPARTMENT_EPM
            )
            ->exists(),
        404
    );

    $nomination->load([
        'candidate',
        'candidate.documents',
        'position',
        'politicalParty',
        'election',
        'batch',
        'workflowHistories.user',
    ]);

    return view('staff.epm.records.show', [
        'nomination' => $nomination,
    ]);
}

/**
 * EPM dashboard.
 */
public function dashboard(): View
{
    /*
    |--------------------------------------------------------------------------
    | Current EPM workload
    |--------------------------------------------------------------------------
    */

    $pendingReview = Nomination::query()
        ->where(
            'current_department',
            NominationWorkflowService::DEPARTMENT_EPM
        )
        ->where(
            'workflow_status',
            Nomination::WORKFLOW_STATUS_UNDER_REVIEW
        )
        ->count();


    /*
    |--------------------------------------------------------------------------
    | Nominations processed by EPM
    |--------------------------------------------------------------------------
    |
    | A nomination is considered processed by EPM once it has
    | an immutable workflow history entry showing EPM as the
    | originating department.
    |
    */

    $processedByEpm = NominationWorkflowHistory::query()
        ->where(
            'from_department',
            NominationWorkflowService::DEPARTMENT_EPM
        )
        ->distinct('nomination_id')
        ->count('nomination_id');


    /*
    |--------------------------------------------------------------------------
    | Forwarded to Legal
    |--------------------------------------------------------------------------
    */

    $forwardedToLegal = NominationWorkflowHistory::query()
        ->where(
            'from_department',
            NominationWorkflowService::DEPARTMENT_EPM
        )
        ->where(
            'to_department',
            NominationWorkflowService::DEPARTMENT_LEGAL
        )
        ->where(
            'action',
            NominationWorkflowService::ACTION_FORWARDED
        )
        ->distinct('nomination_id')
        ->count('nomination_id');


    /*
    |--------------------------------------------------------------------------
    | Completed
    |--------------------------------------------------------------------------
    |
    | Completed means the nomination has ultimately reached the
    | approved state.
    |
    */

    $completed = Nomination::query()
        ->where('status', Nomination::STATUS_APPROVED)
        ->whereHas('workflowHistories', function ($query) {
            $query->where(
                'from_department',
                NominationWorkflowService::DEPARTMENT_EPM
            );
        })
        ->count();


    /*
    |--------------------------------------------------------------------------
    | Returned
    |--------------------------------------------------------------------------
    |
    | These are EPM-processed nominations whose current workflow
    | status is returned.
    |
    */

    $returned = Nomination::query()
        ->where(
            'workflow_status',
            Nomination::WORKFLOW_STATUS_RETURNED
        )
        ->whereHas('workflowHistories', function ($query) {
            $query->where(
                'from_department',
                NominationWorkflowService::DEPARTMENT_EPM
            );
        })
        ->count();


    /*
    |--------------------------------------------------------------------------
    | Recent EPM activity
    |--------------------------------------------------------------------------
    */

    $recentActivity = NominationWorkflowHistory::query()
        ->where(
            'from_department',
            NominationWorkflowService::DEPARTMENT_EPM
        )
        ->with([
            'nomination.candidate',
            'nomination.position',
            'nomination.politicalParty',
        ])
        ->latest()
        ->take(10)
        ->get();


    return view('staff.epm.dashboard', [
        'pendingReview' => $pendingReview,
        'processedByEpm' => $processedByEpm,
        'forwardedToLegal' => $forwardedToLegal,
        'completed' => $completed,
        'returned' => $returned,
        'recentActivity' => $recentActivity,
    ]);
}

}
