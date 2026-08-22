<?php

namespace App\Http\Controllers\Web\Legal;

use App\Http\Controllers\Controller;
use App\Models\Nomination\NominationWorkflowHistory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LegalWorkflowHistoryController extends Controller
{
    public function index(Request $request): View
    {
        $query = NominationWorkflowHistory::query()
            ->with([
                'nomination.candidate',
                'nomination.politicalParty',
                'nomination.position',
                'nomination.election',
                'user',
            ]);

        if ($request->filled('action')) {
            $query->where(
                'action',
                $request->input('action')
            );
        }

        if ($request->filled('from_department')) {
            $query->where(
                'from_department',
                $request->input('from_department')
            );
        }

        if ($request->filled('to_department')) {
            $query->where(
                'to_department',
                $request->input('to_department')
            );
        }

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->whereHas(
                'nomination.candidate',
                function ($candidateQuery) use ($search) {
                    $candidateQuery->where(function ($q) use ($search) {
                        $q->where(
                            'first_name',
                            'ilike',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'middle_name',
                            'ilike',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'last_name',
                            'ilike',
                            "%{$search}%"
                        );
                    });
                }
            );
        }

        $histories = $query
            ->latest('created_at')
            ->paginate(25)
            ->withQueryString();

        return view('staff.legal.workflow-history.index', [
            'histories' => $histories,
        ]);
    }
}
