<?php

namespace App\Http\Controllers\Web\Legal;

use App\Http\Controllers\Controller;
use App\Models\Candidate\Candidate;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LegalCandidateController extends Controller
{
    public function index(Request $request): View
    {
        $query = Candidate::query()
            ->with([
                'nomination.politicalParty',
                'nomination.position',
                'nomination.election',
            ]);

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'ilike', "%{$search}%")
                    ->orWhere('middle_name', 'ilike', "%{$search}%")
                    ->orWhere('last_name', 'ilike', "%{$search}%");
            });
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->input('gender'));
        }

        if ($request->filled('is_active')) {
            $query->where(
                'is_active',
                $request->input('is_active') === '1'
            );
        }

        $candidates = $query
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('staff.legal.candidates.index', [
            'candidates' => $candidates,
        ]);
    }

    public function show(Candidate $candidate): View
    {
        $candidate->load([
            'documents.documentType',
            'nomination.politicalParty',
            'nomination.position',
            'nomination.election',
            'nomination.lga',
            'nomination.ward',
            'nomination.lcda',
        ]);

        return view('staff.legal.candidates.show', [
            'candidate' => $candidate,
            'nomination' => $candidate->nomination,
        ]);
    }
}
