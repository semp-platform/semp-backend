<?php

namespace App\Http\Controllers\Web\Nomination;

use App\Http\Controllers\Controller;
use App\Models\PrimaryEventMonitoringReport;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommissionerPrimaryMonitoringController extends Controller
{
    public function reports(): View
    {
        $reports = PrimaryEventMonitoringReport::query()
            ->with([
                'primaryEvent.election',
                'primaryEvent.politicalParty',
                'primaryEvent.position',
                'primaryEvent.lga',
                'primaryEvent.lcda',
                'primaryEvent.ward',
                'monitor',
            ])
            ->where(
                'status',
                PrimaryEventMonitoringReport::STATUS_SUBMITTED
            )
            ->latest('submitted_at')
            ->get();

        return view(
            'staff.commissioner.primary-monitoring.reports.index',
            [
                'reports' => $reports,
            ]
        );
    }

    public function reportShow(
        PrimaryEventMonitoringReport $report
    ): View {
        $report->load([
            'primaryEvent.election',
            'primaryEvent.politicalParty',
            'primaryEvent.position',
            'primaryEvent.lga',
            'primaryEvent.lcda',
            'primaryEvent.ward',
'monitor',
'reviewer',
'attachments',
        ]);

        abort_unless(
            $report->status === PrimaryEventMonitoringReport::STATUS_SUBMITTED,
            404
        );

        return view(
            'staff.commissioner.primary-monitoring.reports.show',
            [
                'report' => $report,
                'event' => $report->primaryEvent,
            ]
        );
    }
    public function approve(
    Request $request,
    PrimaryEventMonitoringReport $report
): RedirectResponse {
    abort_unless(
        $report->status === PrimaryEventMonitoringReport::STATUS_SUBMITTED,
        404
    );

    $validated = $request->validate([
        'comment' => [
            'nullable',
            'string',
            'max:5000',
        ],
    ]);

    DB::transaction(function () use (
        $request,
        $report,
        $validated
    ) {
        $report->update([
            'review_status' => PrimaryEventMonitoringReport::REVIEW_ACCEPTED,
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
            'review_comment' => $validated['comment'] ?? null,
        ]);
    });

    return redirect()
        ->route(
            'staff.commissioner.primary-monitoring.reports.show',
            $report
        )
        ->with(
            'success',
            'Primary monitoring report accepted successfully.'
        );
}
public function return(
    Request $request,
    PrimaryEventMonitoringReport $report
): RedirectResponse {
    abort_unless(
        $report->status === PrimaryEventMonitoringReport::STATUS_SUBMITTED,
        404
    );

    $validated = $request->validate([
        'comment' => [
            'required',
            'string',
            'max:5000',
        ],
    ]);

    DB::transaction(function () use (
        $request,
        $report,
        $validated
    ) {
        $report->update([
            'review_status' => PrimaryEventMonitoringReport::REVIEW_RETURNED,
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
            'review_comment' => $validated['comment'],
        ]);
    });

    return redirect()
        ->route(
            'staff.commissioner.primary-monitoring.reports.show',
            $report
        )
        ->with(
            'success',
            'Primary monitoring report returned for clarification.'
        );
}
}
