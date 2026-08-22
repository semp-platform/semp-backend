<?php

namespace App\Services\Candidate;

use App\Models\Candidate\CandidateWithdrawal;
use App\Models\Nomination\Nomination;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\User;


class CandidateWithdrawalService
{
    public function createForParty(
        Nomination $nomination,
        int $partyId,
        array $data
    ): CandidateWithdrawal {

        return DB::transaction(function () use (
            $nomination,
            $partyId,
            $data
        ) {

            /*
            |--------------------------------------------------------------------------
            | Ownership
            |--------------------------------------------------------------------------
            */

            if ($nomination->political_party_id !== $partyId) {
                throw ValidationException::withMessages([
                    'nomination' =>
                        'This nomination does not belong to your political party.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Prevent duplicate pending withdrawal requests
            |--------------------------------------------------------------------------
            */

            $exists = CandidateWithdrawal::query()
                ->where('nomination_id', $nomination->id)
                ->whereIn('status', [
                    CandidateWithdrawal::STATUS_DRAFT,
                    CandidateWithdrawal::STATUS_SUBMITTED,
                ])
                ->exists();

            if ($exists) {
                throw ValidationException::withMessages([
                    'nomination' =>
                        'A withdrawal request is already pending for this nomination.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Prevent withdrawal of an already withdrawn/replaced nomination
            |--------------------------------------------------------------------------
            */

            if (
                $nomination->status === Nomination::STATUS_WITHDRAWN ||
                $nomination->status === Nomination::STATUS_REPLACED
            ) {
                throw ValidationException::withMessages([
                    'nomination' =>
                        'This nomination can no longer be withdrawn.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Supporting evidence
            |--------------------------------------------------------------------------
            */

            $supportingEvidencePath = null;

            if (
                isset($data['supporting_evidence']) &&
                $data['supporting_evidence'] !== null
            ) {
                $supportingEvidencePath = $data['supporting_evidence']
                    ->store(
                        'candidate-withdrawals',
                        'public'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | Determine whether nomination has entered OGSIEC workflow
            |--------------------------------------------------------------------------
            |
            | If current_department is populated, the nomination has entered
            | an OGSIEC department.
            |
            | A batched nomination has also left the normal party-only stage.
            |
            */

            $hasEnteredOgsiecWorkflow =
                ! empty($nomination->current_department)
                || in_array(
                    $nomination->status,
                    [
                        Nomination::STATUS_BATCHED,
                        Nomination::STATUS_UNDER_REVIEW,
                        Nomination::STATUS_APPROVED,
                    ],
                    true
                );

            /*
            |--------------------------------------------------------------------------
            | Withdrawal status
            |--------------------------------------------------------------------------
            |
            | Party-only nomination:
            |     draft
            |
            | Nomination already inside OGSIEC:
            |     submitted
            |
            | Submitted requests will appear in the Commissioner workflow.
            |
            */

            $status = $hasEnteredOgsiecWorkflow
                ? CandidateWithdrawal::STATUS_SUBMITTED
                : CandidateWithdrawal::STATUS_DRAFT;

            /*
            |--------------------------------------------------------------------------
            | Create withdrawal request
            |--------------------------------------------------------------------------
            */

            return CandidateWithdrawal::create([

                'nomination_id' => $nomination->id,

                'political_party_id' => $partyId,

                'candidate_change_reason_id' =>
                    $data['candidate_change_reason_id'] ?? null,

                'reason' =>
                    $data['reason']
                    ?? $data['remarks']
                    ?? 'Candidate withdrawal request.',

                'remarks' =>
                    $data['remarks'] ?? null,

                'supporting_evidence_path' =>
                    $supportingEvidencePath,

                'status' => $status,

                'submitted_at' => $status === CandidateWithdrawal::STATUS_SUBMITTED
                    ? now()
                    : null,
            ]);
        });
    }

    /**
 * Approve a candidate withdrawal request.
 */
public function approve(
    CandidateWithdrawal $withdrawal,
    int $reviewerId
): CandidateWithdrawal {

    return DB::transaction(function () use (
        $withdrawal,
        $reviewerId
    ) {

        if (
            $withdrawal->status !== CandidateWithdrawal::STATUS_SUBMITTED
        ) {
            throw ValidationException::withMessages([
                'withdrawal' =>
                    'Only submitted withdrawal requests can be approved.',
            ]);
        }

        $withdrawal->update([
            'status' => CandidateWithdrawal::STATUS_APPROVED,
            'approved_at' => now(),
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
        ]);

        $withdrawal->nomination()->update([
            'status' => Nomination::STATUS_WITHDRAWN,
        ]);

        return $withdrawal->fresh();
    });
}


/**
 * Reject a candidate withdrawal request.
 */
public function reject(
    CandidateWithdrawal $withdrawal,
    int $reviewerId,
    ?string $reason = null
): CandidateWithdrawal {

    return DB::transaction(function () use (
        $withdrawal,
        $reviewerId,
        $reason
    ) {

        if (
            $withdrawal->status !== CandidateWithdrawal::STATUS_SUBMITTED
        ) {
            throw ValidationException::withMessages([
                'withdrawal' =>
                    'Only submitted withdrawal requests can be rejected.',
            ]);
        }

        $withdrawal->update([
            'status' => CandidateWithdrawal::STATUS_REJECTED,
            'rejected_at' => now(),
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
            'review_comment' => $reason,
        ]);

        return $withdrawal->fresh();
    });
}

}
