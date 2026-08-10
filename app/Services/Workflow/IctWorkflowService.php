<?php

namespace App\Services\Workflow;

use App\Models\Candidate\CandidateDocumentReviewRequest;
use App\Models\Nomination\Nomination;
use App\Models\Nomination\NominationWorkflowHistory;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class IctWorkflowService
{
    /*
    |--------------------------------------------------------------------------
    | Departments
    |--------------------------------------------------------------------------
    */

    public const DEPARTMENT_PARTY = 'party';
    public const DEPARTMENT_ICT = 'ict';
    public const DEPARTMENT_EPM = 'epm';
    public const DEPARTMENT_LEGAL = 'legal';
    public const DEPARTMENT_COMMISSION = 'commission';

    /*
    |--------------------------------------------------------------------------
    | Actions
    |--------------------------------------------------------------------------
    */

    public const ACTION_RECEIVED = 'received';
    public const ACTION_RETURNED_TO_PARTY = 'returned_to_party';
    public const ACTION_FORWARDED_TO_EPM = 'forwarded_to_epm';

    /**
     * Return an individual nomination from ICT to the political party.
     *
     * Only the selected documents are requested for re-upload.
     */
    public function returnToParty(
        Nomination $nomination,
        array $documentIds,
        ?string $reason = null,
        ?string $comment = null
    ): Nomination {

        return DB::transaction(function () use (
            $nomination,
            $documentIds,
            $reason,
            $comment
        ) {

            if (empty($documentIds)) {
                throw new InvalidArgumentException(
                    'At least one document must be selected when returning a candidate to the party.'
                );
            }

            $this->ensureIctCanProcess($nomination);

            $documents = $nomination->candidate
                ->documents()
                ->whereIn('id', $documentIds)
                ->get();

            if ($documents->count() !== count(array_unique($documentIds))) {
                throw new InvalidArgumentException(
                    'One or more selected documents do not belong to this candidate.'
                );
            }

            foreach ($documents as $document) {

                CandidateDocumentReviewRequest::create([
                    'nomination_id' => $nomination->id,
                    'candidate_id' => $nomination->candidate_id,
                    'candidate_document_id' => $document->id,
                    'document_type_id' => $document->document_type_id,
                    'requested_by' => auth()->id(),
                    'department' => self::DEPARTMENT_ICT,
                    'reason' => $reason,
                    'comment' => $comment,
                    'status' => CandidateDocumentReviewRequest::STATUS_REQUESTED,
                    'requested_at' => now(),
                ]);
            }

            $this->recordHistory(
                nomination: $nomination,
                from: self::DEPARTMENT_ICT,
                to: self::DEPARTMENT_PARTY,
                action: self::ACTION_RETURNED_TO_PARTY,
                reason: $reason,
                comment: $comment,
            );

            $nomination->update([
                'status' => Nomination::STATUS_UNDER_REVIEW,
            ]);

            return $nomination->fresh();
        });
    }

    /**
     * Forward one individual nomination from ICT to EPM.
     */
    public function forwardToEpm(
        Nomination $nomination,
        ?string $comment = null
    ): Nomination {

        return DB::transaction(function () use (
            $nomination,
            $comment
        ) {

            $this->ensureIctCanProcess($nomination);

            $this->ensureNoPendingDocumentRequests($nomination);

            $this->recordHistory(
                nomination: $nomination,
                from: self::DEPARTMENT_ICT,
                to: self::DEPARTMENT_EPM,
                action: self::ACTION_FORWARDED_TO_EPM,
                comment: $comment,
            );

            $nomination->update([
                'status' => Nomination::STATUS_UNDER_REVIEW,
            ]);

            return $nomination->fresh();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    protected function ensureIctCanProcess(
        Nomination $nomination
    ): void {

        if (! $nomination->isBatched() &&
            ! $nomination->isUnderReview()) {

            throw new InvalidArgumentException(
                'This nomination is not available for ICT processing.'
            );
        }
    }

    protected function ensureNoPendingDocumentRequests(
        Nomination $nomination
    ): void {

        $exists = CandidateDocumentReviewRequest::query()
            ->where('nomination_id', $nomination->id)
            ->where(
                'status',
                CandidateDocumentReviewRequest::STATUS_REQUESTED
            )
            ->exists();

        if ($exists) {
            throw new InvalidArgumentException(
                'This candidate has outstanding document re-upload requests.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Workflow History
    |--------------------------------------------------------------------------
    */

    protected function recordHistory(
        Nomination $nomination,
        string $from,
        string $to,
        string $action,
        ?string $reason = null,
        ?string $comment = null
    ): NominationWorkflowHistory {

        return NominationWorkflowHistory::create([
            'nomination_id' => $nomination->id,
            'from_department' => $from,
            'to_department' => $to,
            'action' => $action,
            'user_id' => auth()->id(),
            'comment' => $comment,
            'reason' => $reason,
        ]);
    }
}
