<?php

namespace App\Services\Candidate;

use App\Models\Candidate\CandidateWithdrawal;
use App\Models\Nomination\Nomination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

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
             * Ownership check.
             */
            if ($nomination->political_party_id !== $partyId) {

                throw ValidationException::withMessages([
                    'nomination' =>
                        'This nomination does not belong to your political party.',
                ]);

            }

            /*
             * Prevent duplicate withdrawal requests.
             */
            $exists = CandidateWithdrawal::query()
    ->where('nomination_id', $nomination->id)
    ->exists();

if ($exists) {

    throw ValidationException::withMessages([
        'nomination' =>
            'A withdrawal request already exists for this nomination.',
    ]);

}

            /*
             * Only submitted nominations may be withdrawn.
             */
            if (! $nomination->isReady()) {

                throw ValidationException::withMessages([
                    'nomination' =>
                        'Only ready nominations may be withdrawn.',
                ]);

            }

            return CandidateWithdrawal::create([

    'nomination_id' => $nomination->id,

    'political_party_id' => $partyId,

    /*
     * New Candidate Change Framework.
     */
    'candidate_change_reason_id' =>
        $data['candidate_change_reason_id'] ?? null,

    'remarks' =>
        $data['remarks'] ?? null,

        'supporting_evidence_path' => $supportingEvidencePath,

    /*
     * Temporary compatibility.
     * Remove after the migration is complete.
     */
    'reason' =>
        $data['remarks'] ?? $data['reason'],

    'status' => CandidateWithdrawal::STATUS_DRAFT,

]);
        });

    }
}
