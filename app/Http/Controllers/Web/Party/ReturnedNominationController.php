<?php

namespace App\Http\Controllers\Web\Party;

use App\Http\Controllers\Controller;
use App\Models\Candidate\CandidateDocumentReviewRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReturnedNominationController extends Controller
{
    public function index(Request $request): View
    {
        $party = auth()->user()
            ->politicalParties()
            ->wherePivot('is_active', true)
            ->where('political_parties.is_active', true)
            ->firstOrFail();


        $requests = CandidateDocumentReviewRequest::query()

            // Keep both pending and completed corrections
            ->whereIn(
                'status',
                [
                    CandidateDocumentReviewRequest::STATUS_REQUESTED,
                    CandidateDocumentReviewRequest::STATUS_RESOLVED,
                ]
            )

            // Only commissioner returns
            ->where('department', 'commissioner')

            // Only this party's nominations
            ->whereHas('nomination', function ($query) use ($party) {

                $query->where(
                    'political_party_id',
                    $party->id
                );

            })

            ->with([
                'nomination.candidate',
                'candidateDocument.documentType',
                'resolvedBy',
            ])

            ->latest('requested_at')

            ->get();


        return view('party.returned-nominations.index', [
            'party' => $party,
            'requests' => $requests,
        ]);
    }
}
