<?php

namespace App\Services\Candidate;

use App\Models\Candidate\CandidateWithdrawal;
use App\Models\Nomination\Nomination;
use App\Services\Workflow\NominationWorkflowService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CandidateReplacementService
{
    public function __construct(
        private readonly CandidateService $candidateService,
        private readonly NominationWorkflowService $workflowService
    ) {}

    public function createForParty(
        CandidateWithdrawal $withdrawal,
        int $partyId,
        array $data
    ): Nomination {

        return DB::transaction(function () use (
            $withdrawal,
            $partyId,
            $data
        ) {

            $withdrawal->load([
                'nomination.election',
                'nomination.position',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Ownership
            |--------------------------------------------------------------------------
            */

            if ($withdrawal->political_party_id !== $partyId) {
                throw ValidationException::withMessages([
                    'withdrawal' =>
                        'This withdrawal does not belong to your political party.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Withdrawal must be approved
            |--------------------------------------------------------------------------
            */

            if (
                $withdrawal->status !== CandidateWithdrawal::STATUS_APPROVED
            ) {
                throw ValidationException::withMessages([
                    'withdrawal' =>
                        'Only approved candidate withdrawals can be replaced.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Prevent duplicate replacement
            |--------------------------------------------------------------------------
            */

            if ($withdrawal->replacement_nomination_id) {
                throw ValidationException::withMessages([
                    'withdrawal' =>
                        'A replacement candidate has already been registered.',
                ]);
            }

            $original = $withdrawal->nomination;

            /*
            |--------------------------------------------------------------------------
            | Original nomination must belong to a batch
            |--------------------------------------------------------------------------
            */

            if (! $original->nomination_batch_id) {
                throw ValidationException::withMessages([
                    'withdrawal' =>
                        'The original nomination is not attached to a nomination batch.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Payment must already be confirmed
            |--------------------------------------------------------------------------
            */

            $batch = $original->batch()
                ->with('payment')
                ->first();

            if (! $batch || ! $batch->payment) {
                throw ValidationException::withMessages([
                    'withdrawal' =>
                        'The original nomination does not have a payment record.',
                ]);
            }

            if (! $batch->payment->isConfirmed()) {
                throw ValidationException::withMessages([
                    'withdrawal' =>
                        'The original nomination payment has not been confirmed.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Find or create replacement candidate
            |--------------------------------------------------------------------------
            */

            $candidate = $this->candidateService
                ->findOrCreateFromNin($data['nin']);

            $candidate->update([
                'qualification' =>
                    $data['qualification'] ?? null,

                'qualification_details' =>
                    $data['qualification_details'] ?? null,

                'has_disability' =>
                    $data['has_disability'] ?? false,

                'disability_description' =>
                    $data['disability_description'] ?? null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Prevent duplicate nomination
            |--------------------------------------------------------------------------
            */

            $alreadyNominated = Nomination::query()
                ->where('election_id', $original->election_id)
                ->where('candidate_id', $candidate->id)
                ->where('position_id', $original->position_id)
                ->exists();

            if ($alreadyNominated) {
                throw ValidationException::withMessages([
                    'nin' =>
                        'This candidate has already been nominated for this position in this election.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Create replacement nomination
            |--------------------------------------------------------------------------
            |
            | IMPORTANT:
            |
            | The replacement inherits the ORIGINAL nomination batch.
            |
            | No new payment is created.
            |
            */

            $replacement = Nomination::create([
                'election_id' =>
                    $original->election_id,

                'political_party_id' =>
                    $original->political_party_id,

                'nomination_batch_id' =>
                    $original->nomination_batch_id,

                'candidate_id' =>
                    $candidate->id,

                'position_id' =>
                    $original->position_id,

                'lga_id' =>
                    $original->lga_id,

                'lcda_id' =>
                    $original->lcda_id,

                'ward_id' =>
                    $original->ward_id,

                /*
                 * The replacement has not been manually assigned
                 * to a department yet.
                 */
                'status' =>
                    Nomination::STATUS_BATCHED,

                'workflow_status' =>
                    Nomination::WORKFLOW_STATUS_SUBMITTED,

                'current_department' =>
                    null,

                'received_at' =>
                    null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Receive replacement directly into ICT
            |--------------------------------------------------------------------------
            |
            | The original batch has already passed ICT intake.
            | Therefore we must not resubmit the entire batch.
            |
            | receiveByIct() creates the proper workflow history and
            | synchronizes the replacement's current workflow state.
            |
            */

            $this->workflowService->receiveByIct(
                $replacement,
                'Replacement candidate received by ICT under the existing paid nomination.'
            );

            /*
            |--------------------------------------------------------------------------
            | Link replacement to withdrawal
            |--------------------------------------------------------------------------
            */

            $withdrawal->update([
                'replacement_nomination_id' =>
                    $replacement->id,
            ]);

            return $replacement->fresh([
                'election',
                'politicalParty',
                'candidate',
                'position',
                'lga',
                'lcda',
                'ward',
                'batch',
            ]);
        });
    }
}
