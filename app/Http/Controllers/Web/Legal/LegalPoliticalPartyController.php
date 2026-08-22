<?php

namespace App\Http\Controllers\Web\Legal;

use App\Http\Controllers\Controller;
use App\Models\Party\PoliticalParty;
use App\Models\Nomination\Nomination;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LegalPoliticalPartyController extends Controller
{
    public function index(Request $request): View
    {
        $query = PoliticalParty::query();

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                    ->orWhere('acronym', 'ilike', "%{$search}%");
            });
        }

        if ($request->filled('is_active')) {
            $query->where(
                'is_active',
                $request->input('is_active') === '1'
            );
        }

        $politicalParties = $query
            ->withCount('users')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('staff.legal.political-parties.index', [
            'politicalParties' => $politicalParties,
        ]);
    }

    public function show(PoliticalParty $politicalParty): View
    {
        $nominations = Nomination::query()
            ->with([
                'candidate',
                'position',
                'election',
            ])
            ->where(
                'political_party_id',
                $politicalParty->id
            )
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('staff.legal.political-parties.show', [
            'politicalParty' => $politicalParty,
            'nominations' => $nominations,
        ]);
    }
}
