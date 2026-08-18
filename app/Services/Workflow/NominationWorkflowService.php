<?php

namespace App\Services\Workflow;

use App\Models\Nomination\Nomination;
use App\Models\Candidate\CandidateDocumentReviewRequest;
use App\Models\Nomination\NominationBatch;
use App\Models\Nomination\NominationWorkflowHistory;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class NominationWorkflowService
{
    /*
    |--------------------------------------------------------------------------
    | Workflow Departments
    |--------------------------------------------------------------------------
    */

   public const DEPARTMENT_ICT = 'ict';
public const DEPARTMENT_EPM = 'epm';
public const DEPARTMENT_LEGAL = 'legal';
public const DEPARTMENT_COMMISSIONER = 'commissioner';
public const DEPARTMENT_PARTY = 'party';

    /*
    |--------------------------------------------------------------------------
    | Workflow Actions
    |--------------------------------------------------------------------------
    */

    public const ACTION_RECEIVED = 'received';
public const ACTION_FORWARDED = 'forwarded';
public const ACTION_RETURNED = 'returned';
public const ACTION_RESUBMITTED = 'resubmitted';
public const ACTION_APPROVED = 'approved';



    /**
     * ICT receives an individual nomination.
     */
    public function receiveByIct(
        Nomination $nomination,
        ?string $comment = null
    ): NominationWorkflowHistory {
        return $this->recordMovement(
            nomination: $nomination,
            fromDepartment: null,
            toDepartment: self::DEPARTMENT_ICT,
            action: self::ACTION_RECEIVED,
            comment: $comment,
        );
    }

    /**
     * ICT receives an entire nomination batch.
     */
    public function receiveBatchAtIct(
        NominationBatch $batch,
        ?string $comment = null
    ): NominationBatch {
        return DB::transaction(function () use ($batch, $comment) {

            $batch->load('nominations');

            if ($batch->status !== NominationBatch::STATUS_SUBMITTED) {
                throw new RuntimeException(
                    'Only submitted nomination batches can be received by ICT.'
                );
            }

            if ($batch->nominations->isEmpty()) {
                throw new RuntimeException(
                    'This nomination batch contains no nominations.'
                );
            }

            foreach ($batch->nominations as $nomination) {

                if ($this->currentDepartment($nomination) !== null) {
                    continue;
                }

                $this->receiveByIct(
                    $nomination,
                    $comment
                );
            }

            $batch->update([
                'status' => NominationBatch::STATUS_UNDER_REVIEW,
                'received_at' => now(),
            ]);

            return $batch->fresh([
                'election',
                'politicalParty',
                'nominations',
            ]);
        });
    }

    /**
 * ICT forwards nomination to EPM
 * after completing its review/recommendation.
 */
public function forwardFromIct(
    Nomination $nomination,
    ?string $comment = null
): NominationWorkflowHistory {
    $this->ensureCurrentDepartment(
        $nomination,
        self::DEPARTMENT_ICT
    );

    return $this->recordMovement(
        nomination: $nomination,
        fromDepartment: self::DEPARTMENT_ICT,
        toDepartment: self::DEPARTMENT_EPM,
        action: self::ACTION_FORWARDED,
        comment: $comment,
    );
}

/**
 * EPM forwards nomination to Legal.
 */
public function forwardFromEpm(
    Nomination $nomination,
    ?string $comment = null
): NominationWorkflowHistory {
    $this->ensureCurrentDepartment(
        $nomination,
        self::DEPARTMENT_EPM
    );

    return $this->recordMovement(
        nomination: $nomination,
        fromDepartment: self::DEPARTMENT_EPM,
        toDepartment: self::DEPARTMENT_LEGAL,
        action: self::ACTION_FORWARDED,
        comment: $comment,
    );
}

    /**
     * Legal forwards nomination to Commissioner.
     */
    public function forwardFromLegal(
        Nomination $nomination,
        ?string $comment = null
    ): NominationWorkflowHistory {
        $this->ensureCurrentDepartment(
            $nomination,
            self::DEPARTMENT_LEGAL
        );

        return $this->recordMovement(
            nomination: $nomination,
            fromDepartment: self::DEPARTMENT_LEGAL,
            toDepartment: self::DEPARTMENT_COMMISSIONER,
            action: self::ACTION_FORWARDED,
            comment: $comment,
        );
    }

    /**
     * Commissioner returns nomination to political party.
     */
    public function returnFromCommissioner(
    Nomination $nomination,
    string $reason,
    ?string $comment = null,
    array $documentIds = []
): NominationWorkflowHistory {
        $this->ensureCurrentDepartment(
    $nomination,
    self::DEPARTMENT_COMMISSIONER
);

if (! empty($documentIds)) {

    $documents = $nomination->candidate
        ->documents()
        ->whereIn('id', $documentIds)
        ->get();

    foreach ($documents as $document) {

        CandidateDocumentReviewRequest::create([
            'nomination_id' => $nomination->id,
            'candidate_id' => $nomination->candidate_id,
            'candidate_document_id' => $document->id,
            'requested_by' => auth()->id(),
            'department' => self::DEPARTMENT_COMMISSIONER,
            'reason' => $reason,
            'comment' => $comment,
            'status' => CandidateDocumentReviewRequest::STATUS_REQUESTED,
            'requested_at' => now(),
        ]);

    }
}

return $this->recordMovement(
            nomination: $nomination,
            fromDepartment: self::DEPARTMENT_COMMISSIONER,
            toDepartment: null,
            action: self::ACTION_RETURNED,
            comment: $comment,
            reason: $reason,
        );
    }

    /**
 * Party resubmits nomination after document corrections.
 */
public function resubmitAfterCorrection(
    Nomination $nomination
): NominationWorkflowHistory {

    return $this->recordMovement(
        nomination: $nomination,
        fromDepartment: self::DEPARTMENT_PARTY,
        toDepartment: self::DEPARTMENT_COMMISSIONER,
        action: self::ACTION_RESUBMITTED,
        comment: 'Documents corrected and nomination resubmitted.',
    );
}
    /**
     * Commissioner approves nomination.
     */
    public function approve(
        Nomination $nomination,
        ?string $comment = null
    ): NominationWorkflowHistory {
        $this->ensureCurrentDepartment(
            $nomination,
            self::DEPARTMENT_COMMISSIONER
        );

        return $this->recordMovement(
            nomination: $nomination,
            fromDepartment: self::DEPARTMENT_COMMISSIONER,
            toDepartment: null,
            action: self::ACTION_APPROVED,
            comment: $comment,
        );
    }


    /**
     * Record an immutable workflow movement and synchronize
     * the nomination's current workflow state.
     */
    protected function recordMovement(
        Nomination $nomination,
        ?string $fromDepartment,
        ?string $toDepartment,
        string $action,
        ?string $comment = null,
        ?string $reason = null
    ): NominationWorkflowHistory {
        return DB::transaction(function () use (
            $nomination,
            $fromDepartment,
            $toDepartment,
            $action,
            $comment,
            $reason
        ) {

            $history = NominationWorkflowHistory::create([
                'nomination_id' => $nomination->id,
                'from_department' => $fromDepartment,
                'to_department' => $toDepartment,
                'action' => $action,
                'user_id' => auth()->id(),
                'comment' => $comment,
                'reason' => $reason,
            ]);

            $updates = [
                'status' => $this->statusForAction($action),
                'workflow_status' => $this->workflowStatusForAction($action),
                'current_department' => $toDepartment,
            ];

            if ($action === self::ACTION_RECEIVED) {
                $updates['received_at'] = now();
                $updates['completed_at'] = null;
            }

            if ($action === self::ACTION_APPROVED) {
    $updates['completed_at'] = now();
}

            if ($action === self::ACTION_RETURNED) {
                $updates['completed_at'] = null;
            }

            $nomination->update($updates);

            return $history;
        });
    }

    /**
     * Determine nomination status from workflow action.
     */
    protected function statusForAction(string $action): string
{
    return match ($action) {

      self::ACTION_RECEIVED,
self::ACTION_FORWARDED,
self::ACTION_RESUBMITTED
    => Nomination::STATUS_UNDER_REVIEW,

        self::ACTION_APPROVED
            => Nomination::STATUS_APPROVED,

        self::ACTION_RETURNED
            => Nomination::STATUS_READY,

        default => throw new RuntimeException(
            "Unsupported workflow action: {$action}"
        ),
    };
}
    /**
     * Determine workflow status from workflow action.
     */
    protected function workflowStatusForAction(string $action): string
{
    return match ($action) {

        self::ACTION_RECEIVED,
self::ACTION_FORWARDED,
self::ACTION_RESUBMITTED
    => Nomination::WORKFLOW_STATUS_UNDER_REVIEW,

        self::ACTION_RETURNED
            => Nomination::WORKFLOW_STATUS_RETURNED,

        self::ACTION_APPROVED
            => Nomination::WORKFLOW_STATUS_APPROVED,

        default => throw new RuntimeException(
            "Unsupported workflow action: {$action}"
        ),
    };
}

    /**
     * Determine the nomination's current department
     * from its latest workflow history.
     */
    public function currentDepartment(
    Nomination $nomination
): ?string {
    return $nomination->current_department;
}

    /**
     * Ensure nomination is currently assigned
     * to the expected department.
     */
    protected function ensureCurrentDepartment(
        Nomination $nomination,
        string $department
    ): void {
        $currentDepartment = $this->currentDepartment($nomination);

        if ($currentDepartment !== $department) {
            throw new RuntimeException(
                'Nomination is currently assigned to '
                . ($currentDepartment ?? 'no department')
                . ", not {$department}."
            );
        }
    }
}
