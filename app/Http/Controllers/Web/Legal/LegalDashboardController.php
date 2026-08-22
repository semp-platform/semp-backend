<?php

namespace App\Http\Controllers\Web\Legal;

use App\Http\Controllers\Controller;
use App\Models\Nomination\Nomination;
use App\Models\Nomination\NominationWorkflowHistory;
use App\Services\Workflow\NominationWorkflowService;
use Illuminate\View\View;

class LegalDashboardController extends Controller
{
    public function index(): View
    {
        $pendingReview = Nomination::query()
            ->where(
                'current_department',
                NominationWorkflowService::DEPARTMENT_LEGAL
            )
            ->where(
                'workflow_status',
                Nomination::WORKFLOW_STATUS_UNDER_REVIEW
            )
            ->count();

        $completedReviews = NominationWorkflowHistory::query()
            ->where(
                'from_department',
                NominationWorkflowService::DEPARTMENT_LEGAL
            )
            ->whereIn('action', [
                NominationWorkflowService::ACTION_FORWARDED,
                NominationWorkflowService::ACTION_RETURNED,
            ])
            ->count();

        $recentActivity = NominationWorkflowHistory::query()
            ->with([
                'nomination.candidate',
                'nomination.politicalParty',
                'nomination.position',
                'user',
            ])
            ->where(
                'from_department',
                NominationWorkflowService::DEPARTMENT_LEGAL
            )
            ->latest('created_at')
            ->limit(5)
            ->get();

        return view('staff.legal.dashboard', [
            'pendingReview' => $pendingReview,
            'completedReviews' => $completedReviews,
            'recentActivity' => $recentActivity,
        ]);
    }
}
