<?php

namespace App\Http\Controllers\Web\Party;

use App\Http\Controllers\Controller;
use App\Models\Communication\ElectionNotice;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ElectionNoticeController extends Controller
{
    public function index(Request $request): View
{
    $party = $this->currentParty($request);

    $notices = ElectionNotice::query()
        ->with('election')
        ->latest()
        ->paginate(10);

    return view('party.election-notices.index', [
        'party' => $party,
        'notices' => $notices,
    ]);
}

   public function show(Request $request, ElectionNotice $electionNotice): View
{
    $party = $this->currentParty($request);

    abort_unless(
        $electionNotice->status === 'published',
        404
    );

    $electionNotice->load('election');

    return view('party.election-notices.show', [
        'party' => $party,
        'electionNotice' => $electionNotice,
    ]);
}

    private function currentParty(Request $request)
    {
        return $request->user()
            ->politicalParties()
            ->wherePivot('is_active', true)
            ->where('political_parties.is_active', true)
            ->firstOrFail();
    }
}
