<?php

namespace App\Http\Controllers\Web\Nomination;

use App\Http\Controllers\Controller;
use App\Models\Nomination\Nomination;
use App\Models\DocumentType;
use App\Services\Workflow\NominationWorkflowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Nomination\NominationWorkflowHistory;
use App\Models\Candidate\Candidate;
use App\Models\Candidate\CandidateDocument;
use Illuminate\Support\Facades\Storage;
use App\Exports\FinalPublicationExport;

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
    'workflowHistories',
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
 * Display candidate records available to the Commissioner.
 */
public function candidates(): View
{
    $candidates = \App\Models\Candidate\Candidate::query()
        ->with([
            'nomination.politicalParty',
            'nomination.position',
            'nomination.election',
            'nomination.lga',
            'nomination.ward',
            'nomination.lcda',
        ])
        ->whereHas('nomination')
        ->latest('id')
        ->paginate(15);

    return view('staff.commissioner.candidates.index', [
        'candidates' => $candidates,
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
           'workflowHistories' => function ($query) {
    $query->latest('created_at');
},
        ]);
        $requiredDocuments = DocumentType::active()
    ->where('required', true)
    ->with([
        'candidateDocuments' => function ($query) use ($nomination) {
            $query->where(
                'candidate_id',
                $nomination->candidate_id
            );
        }
    ])
    ->get();

        return view('staff.commissioner.nominations.show', [
    'nomination' => $nomination,
    'requiredDocuments' => $requiredDocuments,
]);
    }

    /**
 * Display the full read-only candidate record.
 */
public function candidate(
    \App\Models\Candidate\Candidate $candidate
): View {
    $candidate->load([
        'documents.documentType',
        'nomination.position',
        'nomination.politicalParty',
        'nomination.election',
        'nomination.lga',
        'nomination.ward',
        'nomination.lcda',
    ]);

    return view('staff.commissioner.candidates.show', [
        'candidate' => $candidate,
        'nomination' => $candidate->nomination,
    ]);
}

/**
 * View a candidate document from the Commissioner candidate record.
 */
public function viewCandidateDocument(
    Candidate $candidate,
    CandidateDocument $document
) {
    /*
    |--------------------------------------------------------------------------
    | Security
    |--------------------------------------------------------------------------
    |
    | The document must belong to the candidate being viewed.
    |
    */

    abort_unless(
        $document->candidate_id === $candidate->id,
        404
    );

    /*
    |--------------------------------------------------------------------------
    | Candidate must have a nomination
    |--------------------------------------------------------------------------
    */

    $nomination = $candidate->nomination;

    abort_unless(
        $nomination !== null,
        404
    );

    /*
    |--------------------------------------------------------------------------
    | Commissioner may only view candidates that have reached
    | the Commissioner workflow stage or have already passed it.
    |--------------------------------------------------------------------------
    */

    abort_unless(
        in_array(
            $nomination->current_department,
            [
                NominationWorkflowService::DEPARTMENT_COMMISSIONER,
                NominationWorkflowService::DEPARTMENT_ICT,
            ],
            true
        )
        ||
        $nomination->workflow_status === Nomination::WORKFLOW_STATUS_APPROVED,
        404
    );

    /*
    |--------------------------------------------------------------------------
    | File must exist
    |--------------------------------------------------------------------------
    */

    abort_unless(
        Storage::disk($document->disk)->exists($document->path),
        404
    );

    return Storage::disk($document->disk)->response(
        $document->path,
        $document->original_name,
        [
            'Content-Type' => $document->mime_type,
            'Content-Disposition' =>
                'inline; filename="' .
                addslashes($document->original_name) .
                '"',
        ]
    );
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

    'issue_documents' => [
        'nullable',
        'array',
    ],

    'issue_documents.*' => [
        'integer',
        'exists:document_types,id',
    ],
]);
        $this->workflowService->returnFromCommissioner(
    $nomination,
    $validated['reason'],
    $validated['comment'] ?? null,
    $validated['issue_documents'] ?? []
);
        return redirect()
            ->route('staff.commissioner.nominations.index')
            ->with(
                'success',
                'Nomination returned successfully.'
            );
    }
    /**
 * Display completed Commissioner decisions.
 */
public function history(): View
{
    $decisions = NominationWorkflowHistory::query()
        ->with([
            'nomination.candidate',
            'nomination.position',
            'nomination.politicalParty',
            'user',
        ])
        ->where(
            'from_department',
            NominationWorkflowService::DEPARTMENT_COMMISSIONER
        )
        ->whereIn(
            'action',
            [
                NominationWorkflowService::ACTION_APPROVED,
                NominationWorkflowService::ACTION_RETURNED,
            ]
        )
        ->latest()
        ->paginate(15);

    return view('staff.commissioner.decisions.index', [
        'decisions' => $decisions,
    ]);
}
/**
 * Display candidates approved by the Commissioner.
 */
public function approvedCandidates(): View
{
    $candidates = Candidate::query()
        ->with([
            'nomination.politicalParty',
            'nomination.position',
            'nomination.election',
        ])
        ->whereHas('nomination', function ($query) {
            $query->where(
                'status',
                Nomination::STATUS_APPROVED
            );
        })
        ->latest('id')
        ->paginate(15);

    return view('staff.commissioner.approved-candidates.index', [
        'candidates' => $candidates,
    ]);
}

/**
 * Display the approved candidates for final publication review.
 */
public function finalPublication(Request $request): View
{
    $query = Nomination::query()
        ->with([
            'candidate',
            'politicalParty',
            'position',
            'election',
            'lga',
        ])
        ->where(
            'status',
            Nomination::STATUS_APPROVED
        );

    /*
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    */

    if ($request->filled('political_party_id')) {
        $query->where(
            'political_party_id',
            $request->integer('political_party_id')
        );
    }

    if ($request->filled('position_id')) {
        $query->where(
            'position_id',
            $request->integer('position_id')
        );
    }

    if ($request->filled('lga_id')) {
        $query->where(
            'lga_id',
            $request->integer('lga_id')
        );
    }

    if ($request->filled('gender')) {
        $query->whereHas('candidate', function ($candidateQuery) use ($request) {
            $candidateQuery->where(
                'gender',
                $request->input('gender')
            );
        });
    }

    if ($request->filled('qualification')) {
    $query->whereHas('candidate', function ($candidateQuery) use ($request) {
        $candidateQuery->where(
            'qualification',
            $request->input('qualification')
        );
    });
}

    if ($request->filled('election_id')) {
        $query->where(
            'election_id',
            $request->integer('election_id')
        );
    }

    $nominations = $query
        ->latest('id')
        ->paginate(20)
        ->withQueryString();

    /*
    |--------------------------------------------------------------------------
    | Filter options
    |--------------------------------------------------------------------------
    */

    $politicalParties = \App\Models\Party\PoliticalParty::query()
    ->orderBy('name')
    ->get();

$positions = \App\Models\Election\Position::query()
    ->orderBy('name')
    ->get();

$lgas = \App\Models\Reference\Lga::query()
    ->whereHas('state', function ($query) {
        $query->where('name', 'Ogun');
    })
    ->orderBy('name')
    ->get();

$elections = \App\Models\Election\Election::query()
    ->latest('id')
    ->get();

    $qualifications = \App\Models\Candidate\Candidate::query()
    ->whereNotNull('qualification')
    ->where('qualification', '!=', '')
    ->distinct()
    ->orderBy('qualification')
    ->pluck('qualification');

    return view('staff.commissioner.final-publication.index', [
        'nominations' => $nominations,
        'politicalParties' => $politicalParties,
        'positions' => $positions,
        'lgas' => $lgas,
        'elections' => $elections,
        'qualifications' => $qualifications,
    ]);
}
/**
 * Export the approved candidate list to Excel.
 */
public function exportFinalPublicationExcel(Request $request)
{
    return (new FinalPublicationExport(
        $request->only([
            'political_party_id',
            'position_id',
            'lga_id',
            'gender',
            'qualification',
            'election_id',
        ])
    ))->download();
}
/**
 * Export the approved candidate list to PDF.
 */
public function exportFinalPublicationPdf(Request $request)
{
    $query = Nomination::query()
        ->with([
            'candidate',
            'politicalParty',
            'position',
            'election',
            'lga',
        ])
        ->where(
            'status',
            Nomination::STATUS_APPROVED
        );

    if ($request->filled('political_party_id')) {
        $query->where(
            'political_party_id',
            $request->integer('political_party_id')
        );
    }

    if ($request->filled('position_id')) {
        $query->where(
            'position_id',
            $request->integer('position_id')
        );
    }

    if ($request->filled('lga_id')) {
        $query->where(
            'lga_id',
            $request->integer('lga_id')
        );
    }

    if ($request->filled('gender')) {
        $query->whereHas('candidate', function ($candidateQuery) use ($request) {
            $candidateQuery->where(
                'gender',
                $request->input('gender')
            );
        });
    }

    if ($request->filled('qualification')) {
        $query->whereHas('candidate', function ($candidateQuery) use ($request) {
            $candidateQuery->where(
                'qualification',
                $request->input('qualification')
            );
        });
    }

    if ($request->filled('election_id')) {
        $query->where(
            'election_id',
            $request->integer('election_id')
        );
    }

    $nominations = $query
        ->latest('id')
        ->get();

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
        'staff.commissioner.final-publication.pdf',
        [
            'nominations' => $nominations,
        ]
    );

    $pdf->setPaper('a4', 'landscape');

    return $pdf->download(
        'final-approved-candidates-' .
        now()->format('Y-m-d-His') .
        '.pdf'
    );
}
}
