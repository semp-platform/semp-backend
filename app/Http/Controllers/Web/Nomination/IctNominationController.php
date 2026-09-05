<?php

namespace App\Http\Controllers\Web\Nomination;

use App\Http\Controllers\Controller;
use App\Models\Nomination\Nomination;
use App\Models\Nomination\NominationBatch;
use App\Services\Workflow\NominationWorkflowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IctNominationController extends Controller
{
    public function __construct(
        protected NominationWorkflowService $workflowService
    ) {
    }

public function dashboard(): View
{
    $pendingBatches = NominationBatch::query()
        ->whereIn('status', [
            NominationBatch::STATUS_SUBMITTED,
            NominationBatch::STATUS_UNDER_REVIEW,
        ])
        ->count();

    $nominationsUnderReview = Nomination::query()
        ->where('current_department', Nomination::DEPARTMENT_ICT)
        ->where(
            'workflow_status',
            Nomination::WORKFLOW_STATUS_UNDER_REVIEW
        )
        ->whereNotIn('status', [
            Nomination::STATUS_WITHDRAWN,
            Nomination::STATUS_REPLACED,
        ])
        ->count();

    $totalNominations = Nomination::query()
        ->count();

$publishedResults = \App\Models\ResultImport::query()
   ->where('status', 'published')
        ->count();

    return view('staff.ict.dashboard', [
        'pendingBatches' => $pendingBatches,
        'nominationsUnderReview' => $nominationsUnderReview,
        'totalNominations' => $totalNominations,
        'publishedResults' => $publishedResults,
    ]);
}

    /**
     * Display nomination batches submitted by political parties
     * and batches currently under ICT review.
     */
    public function batches(): View
{

    $batches = NominationBatch::query()
        ->with([
            'election',
            'politicalParty',
            'submittedBy',
            'nominations',
        ])
        ->whereIn('status', [
            NominationBatch::STATUS_SUBMITTED,
            NominationBatch::STATUS_UNDER_REVIEW,
        ])
        ->latest('submitted_at')
        ->paginate(15);

    return view('staff.ict.nomination-batches.index', [
        'batches' => $batches,
    ]);
}
    /**
     * Show a batch waiting for ICT intake or currently under review.
     */
    public function showBatch(
        NominationBatch $batch
    ): View {
        abort_unless(
            $batch->status === NominationBatch::STATUS_SUBMITTED
            || $batch->status === NominationBatch::STATUS_UNDER_REVIEW,
            404
        );

        $batch->load([
            'election',
            'politicalParty',
            'submittedBy',
            'nominations.candidate',
            'nominations.position',
            'nominations.workflowHistories',
        ]);

        return view('staff.ict.nomination-batches.show', [
            'batch' => $batch,
        ]);
    }

    /**
     * Receive a submitted batch at ICT.
     */
    public function receiveBatch(
        Request $request,
        NominationBatch $batch
    ): RedirectResponse {
        $this->workflowService->receiveBatchAtIct(
            $batch,
            $request->input('comment')
        );

        return redirect()
            ->route(
                'staff.ict.nomination-batches.show',
                $batch
            )
            ->with(
                'success',
                'Nomination batch received by ICT successfully.'
            );
    }

    /**
     * Show nominations currently with ICT.
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
        Nomination::DEPARTMENT_ICT
    )
    ->where(
        'workflow_status',
        Nomination::WORKFLOW_STATUS_UNDER_REVIEW
    )
    ->whereNotIn('status', [
        Nomination::STATUS_WITHDRAWN,
        Nomination::STATUS_REPLACED,
    ])
    ->latest('received_at')
    ->paginate(15);

        return view('staff.ict.nominations.index', [
            'nominations' => $nominations,
        ]);
    }

    /**
     * Show one nomination for ICT vetting.
     */
    public function show(
    Nomination $nomination
): View {
    abort_unless(
        $nomination->current_department === Nomination::DEPARTMENT_ICT
        && ! in_array(
            $nomination->status,
            [
                Nomination::STATUS_WITHDRAWN,
                Nomination::STATUS_REPLACED,
            ],
            true
        ),
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

        return view('staff.ict.nominations.show', [
            'nomination' => $nomination,
        ]);
    }

    /**
     * Forward nomination from ICT to the next workflow stage.
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

        $this->workflowService->forwardFromIct(
            $nomination,
            $validated['comment'] ?? null
        );

        return redirect()
            ->route('staff.ict.nominations.index')
            ->with(
                'success',
                'Nomination forwarded successfully.'
            );
    }
   }
