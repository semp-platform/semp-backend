<?php

namespace App\Http\Controllers\Web\Party;

use App\Http\Controllers\Controller;
use App\Models\Candidate\Candidate;
use App\Models\Candidate\CandidateDocument;
use App\Models\DocumentType;
use App\Services\Candidate\CandidateDocumentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Nomination\Nomination;


class CandidateDocumentController extends Controller
{
    public function __construct(
        protected CandidateDocumentService $documents
    ) {
    }

    /**
     * Display candidate documents.
     */
   public function list(
    Request $request
): View {

    $party = auth()->user()
        ->politicalParties()
        ->wherePivot('is_active', true)
        ->where('political_parties.is_active', true)
        ->firstOrFail();

    $requiredDocuments = DocumentType::active()
        ->where('required', true)
        ->count();

    $requiredDocumentIds = DocumentType::active()
        ->where('required', true)
        ->pluck('id');

    $nominations = Nomination::query()
        ->where('political_party_id', $party->id)
        ->with([
            'candidate.documents',
            'position',
            'lga',
            'ward',
        ])
        ->latest()
        ->get();

    return view(
        'party.candidates.index',
        compact(
            'party',
            'nominations',
            'requiredDocuments',
            'requiredDocumentIds'
        )
    );

}
    public function index(
    Candidate $candidate
): View {

    $candidate->load([
        'documents.documentType',
    ]);

    $documentTypes = DocumentType::active()
        ->ordered()
        ->get();

    $requiredDocuments = $documentTypes
        ->where('required', true)
        ->count();

    $uploadedDocuments = $candidate->documents()
        ->whereIn(
            'document_type_id',
            $documentTypes
                ->where('required', true)
                ->pluck('id')
        )
        ->count();

    $party = auth()->user()
        ->politicalParties()
        ->wherePivot('is_active', true)
        ->where('political_parties.is_active', true)
        ->firstOrFail();

    return view(
        'party.candidates.documents',
        compact(
            'candidate',
            'documentTypes',
            'requiredDocuments',
            'uploadedDocuments',
            'party'
        )
    );

}

    /**
     * Upload or replace a document.
     */
    public function store(
        Request $request,
        Candidate $candidate
    ): RedirectResponse {

        $validated = $request->validate([

            'document_type_id' => [
                'required',
                'exists:document_types,id',
            ],

            'document' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:10240',
            ],

        ]);

        $documentType = DocumentType::findOrFail(
            $validated['document_type_id']
        );

        $this->documents->upload(

            $candidate,

            $documentType,

            $validated['document']

        );

        return back()->with(

            'success',

            $documentType->name.' uploaded successfully.'

        );

    }

    /**
     * Delete a document.
     */
    public function destroy(
        CandidateDocument $document
    ): RedirectResponse {

        $this->documents->delete(
            $document
        );

        return back()->with(

            'success',

            'Document deleted successfully.'

        );

    }

    public function complete(
    Candidate $candidate
): RedirectResponse
{
    $required = DocumentType::active()
        ->where('required', true)
        ->count();

    $uploaded = $candidate->documents()
        ->whereIn(
            'document_type_id',
            DocumentType::active()
                ->where('required', true)
                ->pluck('id')
        )
        ->count();

    if ($uploaded < $required) {

        return back()->withErrors([
            'documents' => 'Please upload all required documents before continuing.',
        ]);

    }

    return redirect()
        ->route(
            'party.nominations.show',
            $candidate->nomination
        )
        ->with(
            'success',
            'Supporting documents completed successfully.'
        );
}
}
