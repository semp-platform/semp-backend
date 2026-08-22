<?php

namespace App\Http\Controllers\Web\Party;

use App\Http\Controllers\Controller;
use App\Models\Election\Election;
use App\Models\Election\Position;
use App\Models\PartyPrimaryNotice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use App\Notifications\PrimaryNoticeNotification;

class PartyPrimaryNoticeController extends Controller
{
    private function currentParty(Request $request)
    {
        return $request->user()
            ->politicalParties()
            ->wherePivot('is_active', true)
            ->where('political_parties.is_active', true)
            ->firstOrFail();
    }

    public function index(Request $request): View
    {
        $party = $this->currentParty($request);

        $notices = PartyPrimaryNotice::query()
            ->where('political_party_id', $party->id)
            ->with([
                'election',
                'position',
                'lga',
                'lcda',
                'ward',
            ])
            ->latest()
            ->paginate(15);

        return view('party.primary-notices.index', [
            'party' => $party,
            'notices' => $notices,
        ]);
    }

    public function create(Request $request): View
    {
        $party = $this->currentParty($request);

        $elections = Election::query()
            ->where('is_active', true)
            ->orderBy('election_date')
            ->orderBy('name')
            ->get();

        $positions = Position::query()
            ->where('is_active', true)
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        return view('party.primary-notices.create', [
            'party' => $party,
            'elections' => $elections,
            'positions' => $positions,
        ]);
    }

   public function store(Request $request): RedirectResponse
{
    $party = $this->currentParty($request);

    $validated = $request->validate([
        'election_id' => [
            'required',
            'integer',
            'exists:elections,id',
        ],

        'position_id' => [
            'required',
            'integer',
            'exists:positions,id',
        ],

        'primary_type' => [
            'required',
            'in:direct,indirect,consensus',
        ],

        'scheduled_date' => [
            'required',
            'date',
        ],

        'scheduled_time' => [
            'nullable',
            'date_format:H:i',
        ],

        'venue' => [
            'required',
            'string',
            'max:255',
        ],
    ]);

    $notice = PartyPrimaryNotice::create([
        ...$validated,

        'political_party_id' => $party->id,

        'status' => PartyPrimaryNotice::STATUS_SUBMITTED,

        'submitted_at' => now(),

        'submitted_by' => $request->user()->id,
    ]);

    // Notify Commissioners who can review primary notices
    User::permission('party-primary-notices.review')
        ->get()
        ->each(function (User $user) use ($notice) {
            $user->notify(
                new PrimaryNoticeNotification(
                    'commissioner_review',
                    $notice
                )
            );
        });

    return redirect()
        ->route(
            'party.primary-notices.show',
            $notice
        )
        ->with(
            'success',
            'Primary notice submitted successfully to OGSIEC.'
        );
}

    public function show(
        Request $request,
        PartyPrimaryNotice $partyPrimaryNotice
    ): View {
        $party = $this->currentParty($request);

        abort_unless(
            $partyPrimaryNotice->political_party_id === $party->id,
            403
        );

        $partyPrimaryNotice->load([
            'election',
            'position',
            'lga',
            'lcda',
            'ward',
            'submitter',
            'reviewer',
        ]);

        return view('party.primary-notices.show', [
            'party' => $party,
            'notice' => $partyPrimaryNotice,
        ]);
    }
}
