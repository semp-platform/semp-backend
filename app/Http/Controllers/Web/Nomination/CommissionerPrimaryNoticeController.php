<?php

namespace App\Http\Controllers\Web\Nomination;

use App\Http\Controllers\Controller;
use App\Models\PartyPrimaryNotice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\PrimaryEvent;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Notifications\PrimaryNoticeNotification;


class CommissionerPrimaryNoticeController extends Controller
{
    /**
     * Display party primary notices awaiting Commissioner action.
     */
    public function index(): View
    {
        $notices = PartyPrimaryNotice::query()
            ->with([
                'politicalParty',
                'election',
                'position',
                'submittedBy',
                'reviewedBy',
            ])
            ->whereIn('status', [
                PartyPrimaryNotice::STATUS_SUBMITTED,
                PartyPrimaryNotice::STATUS_RETURNED,
                PartyPrimaryNotice::STATUS_APPROVED,
                PartyPrimaryNotice::STATUS_REJECTED,
            ])
            ->latest('submitted_at')
            ->latest('id')
            ->paginate(20);

        return view(
            'staff.commissioner.primary-notices.index',
            compact('notices')
        );
    }

    /**
     * Display one party primary notice.
     */
    public function show(
        PartyPrimaryNotice $partyPrimaryNotice
    ): View {
        $partyPrimaryNotice->load([
            'politicalParty',
            'election',
            'position',
            'submittedBy',
            'reviewedBy',
        ]);

        return view(
            'staff.commissioner.primary-notices.show',
            [
                'notice' => $partyPrimaryNotice,
            ]
        );
    }

    /**
     * Approve a party primary notice for EPM.
     */
    public function approve(
    Request $request,
    PartyPrimaryNotice $partyPrimaryNotice
): RedirectResponse {
    if (
        $partyPrimaryNotice->status !==
        PartyPrimaryNotice::STATUS_SUBMITTED
    ) {
        return back()->withErrors([
            'notice' =>
                'Only submitted primary notices can be approved.',
        ]);
    }

    $validated = $request->validate([
        'review_comment' => [
            'nullable',
            'string',
            'max:5000',
        ],
    ]);

    $event = DB::transaction(function () use (
        $request,
        $partyPrimaryNotice,
        $validated
    ) {
        $partyPrimaryNotice->update([
            'status' => PartyPrimaryNotice::STATUS_APPROVED,
            'reviewed_at' => now(),
            'reviewed_by' => $request->user()->id,
            'review_comment' =>
                $validated['review_comment'] ?? null,
        ]);

        return PrimaryEvent::firstOrCreate(
            [
                'party_primary_notice_id' =>
                    $partyPrimaryNotice->id,
            ],
            [
                'election_id' =>
                    $partyPrimaryNotice->election_id,

                'political_party_id' =>
                    $partyPrimaryNotice->political_party_id,

                'position_id' =>
                    $partyPrimaryNotice->position_id,

                'lga_id' =>
                    $partyPrimaryNotice->lga_id,

                'lcda_id' =>
                    $partyPrimaryNotice->lcda_id,

                'ward_id' =>
                    $partyPrimaryNotice->ward_id,

                'primary_type' =>
                    $partyPrimaryNotice->primary_type,

                'scheduled_date' =>
                    $partyPrimaryNotice->scheduled_date,

                'scheduled_time' =>
                    $partyPrimaryNotice->scheduled_time,

                'venue' =>
                    $partyPrimaryNotice->venue,

                'notice_received_at' =>
                    now(),

                'notice_status' =>
                    PrimaryEvent::NOTICE_RECEIVED,

                'status' =>
                    PrimaryEvent::STATUS_SCHEDULED,

                'created_by' =>
                    $request->user()->id,
            ]
        );
    });

    /*
     * Notify EPM users who can view
     * primary monitoring events.
     */
    User::permission('primary-monitoring.view')
    ->get()
        ->each(function (User $user) use (
            $partyPrimaryNotice,
            $event
        ) {
            $user->notify(
                new PrimaryNoticeNotification(
                    'epm_monitoring',
                    $partyPrimaryNotice,
                    $event
                )
            );
        });

    return redirect()
        ->route(
            'staff.commissioner.primary-notices.show',
            $partyPrimaryNotice
        )
        ->with(
            'success',
            'Party primary notice approved and forwarded to EPM for monitoring.'
        );
}
    /**
     * Return a notice to the political party for correction.
     */
    public function returnToParty(
        Request $request,
        PartyPrimaryNotice $partyPrimaryNotice
    ): RedirectResponse {
        if (
            $partyPrimaryNotice->status !==
            PartyPrimaryNotice::STATUS_SUBMITTED
        ) {
            return back()->withErrors([
                'notice' =>
                    'Only submitted primary notices can be returned.',
            ]);
        }

        $validated = $request->validate([
            'review_comment' => [
                'required',
                'string',
                'max:5000',
            ],
        ]);

        $partyPrimaryNotice->update([
            'status' => PartyPrimaryNotice::STATUS_RETURNED,
            'reviewed_at' => now(),
            'reviewed_by' => $request->user()->id,
            'review_comment' => $validated['review_comment'],
        ]);

        return redirect()
            ->route(
                'staff.commissioner.primary-notices.show',
                $partyPrimaryNotice
            )
            ->with(
                'success',
                'Primary notice returned to the political party.'
            );
    }

    /**
     * Reject a party primary notice.
     */
    public function reject(
        Request $request,
        PartyPrimaryNotice $partyPrimaryNotice
    ): RedirectResponse {
        if (
            $partyPrimaryNotice->status !==
            PartyPrimaryNotice::STATUS_SUBMITTED
        ) {
            return back()->withErrors([
                'notice' =>
                    'Only submitted primary notices can be rejected.',
            ]);
        }

        $validated = $request->validate([
            'review_comment' => [
                'required',
                'string',
                'max:5000',
            ],
        ]);

        $partyPrimaryNotice->update([
            'status' => PartyPrimaryNotice::STATUS_REJECTED,
            'reviewed_at' => now(),
            'reviewed_by' => $request->user()->id,
            'review_comment' => $validated['review_comment'],
        ]);

        return redirect()
            ->route(
                'staff.commissioner.primary-notices.show',
                $partyPrimaryNotice
            )
            ->with(
                'success',
                'Primary notice rejected.'
            );
    }
}
