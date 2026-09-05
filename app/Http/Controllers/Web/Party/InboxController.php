<?php

namespace App\Http\Controllers\Web\Party;

use App\Http\Controllers\Controller;
use App\Models\Communication\ElectionNotice;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InboxController extends Controller
{
    public function index(Request $request): View
    {
        $party = $this->currentParty($request);

        $notices = ElectionNotice::query()
            ->where('status', 'published')
            ->with('election')
            ->latest('published_at')
            ->get();

        return view('party.inbox.index', [
            'party' => $party,
            'notices' => $notices,
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
