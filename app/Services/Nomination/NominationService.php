<?php

namespace App\Services\Nomination;

use App\Models\Election\Election;
use App\Models\Election\Position;
use App\Models\Nomination\Nomination;
use App\Models\Reference\Lga;
use App\Models\Reference\Ward;
use App\Services\Candidate\CandidateService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\Nomination\NominationBatch;
use App\Services\Nomination\NominationBatchService;
use App\Models\Reference\Lcda;

class NominationService
{
    public function __construct(
        protected CandidateService $candidateService
    ) {}

    public function create(array $data): Nomination
    {
        return DB::transaction(function () use ($data) {

            $election = $this->loadElection(
                $data['election_id']
            );
            if ($election->status !== 'nominations_open') {
    throw ValidationException::withMessages([
        'election_id' =>
            'This election is not currently accepting nominations.',
    ]);
}

            $position = $this->loadPosition(
                $data['position_id']
            );

            $this->validateElectionPosition(
                $election,
                $position
            );

            $location = $this->resolveLocation(
                $election,
                $position,
                $data
            );

            /*
             * Find the candidate by NIN or verify
             * the NIN and create the candidate.
             */
            $candidate = $this->candidateService
                ->findOrCreateFromNin(
                    $data['nin']
                );
                $candidate->update([
    'qualification' => $data['qualification'] ?? null,
    'qualification_details' => $data['qualification_details'] ?? null,
    'has_disability' => $data['has_disability'] ?? false,
    'disability_description' => $data['disability_description'] ?? null,
]);

            /*
             * Prevent duplicate nominations.
             */
            $this->ensureCandidateNotAlreadyNominated(
                $election,
                $candidate->id,

            );

            /*
             * Create draft nomination.
             */
            return Nomination::create([

                'election_id'        => $election->id,
                'political_party_id' => $data['political_party_id'],
                'candidate_id'       => $candidate->id,
                'position_id'        => $position->id,

                'lga_id'             => $location['lga_id'],
                'ward_id'            => $location['ward_id'],
                'lcda_id'            => $location['lcda_id'],
                'lcda_ward_id'       => $location['lcda_ward_id'],
                'status'             => 'draft',

            ])->load([

                'election',
                'politicalParty',
                'candidate',
                'position',
                'lga',
                'ward',
                'lcda',

            ]);

        });
    }

    public function getAll()
    {
        return Nomination::query()
            ->with([
                'election',
                'politicalParty',
                'candidate',
                'position',
                'lga',
                'ward',
                'lcda',
            ])
            ->latest()
            ->get();
    }

    public function getById(int $id): Nomination
    {
        return Nomination::query()
            ->with([
                'election',
                'politicalParty',
                'candidate',
                'position',
                'lga',
                'ward',
                'lcda',
                 'lcdaWard',
            ])
            ->findOrFail($id);
    }

    public function createForParty(
        array $data,
        int $politicalPartyId
    ): Nomination {

        $data['political_party_id'] = $politicalPartyId;

        return $this->create($data);
    }
    public function updateForParty(
    Nomination $nomination,
    array $data,
    int $politicalPartyId
): Nomination {

    return DB::transaction(function () use (
        $nomination,
        $data,
        $politicalPartyId
    ) {

        /*
         * Security check.
         */
        if ($nomination->political_party_id !== $politicalPartyId) {
            abort(404);
        }

        $nomination->loadMissing('election');

        if ($nomination->election->status !== 'nominations_open') {
            throw ValidationException::withMessages([
                'nomination' =>
                    'This election is not currently accepting nomination changes.',
            ]);
        }

        /*
         * Only draft nominations may be edited.
         */
        if ($nomination->status !== Nomination::STATUS_DRAFT) {
            throw ValidationException::withMessages([
                'nomination' =>
                    'Only draft nominations can be edited.',
            ]);
        }

        $election = $this->loadElection(
            $data['election_id']
        );

        $position = $this->loadPosition(
            $data['position_id']
        );

        $this->validateElectionPosition(
            $election,
            $position
        );

        $location = $this->resolveLocation(
            $election,
            $position,
            $data
        );

        /*
         * Ignore the nomination currently
         * being edited.
         */
        $this->ensureCandidateNotAlreadyNominated(
            $election,
            $nomination->candidate_id,
            $nomination
        );
$nomination->candidate()->update([
    'qualification' => $data['qualification'] ?? null,
    'qualification_details' => $data['qualification_details'] ?? null,
    'has_disability' => $data['has_disability'] ?? false,
    'disability_description' => $data['disability_description'] ?? null,
]);
        $nomination->update([

            'election_id' => $election->id,

            'position_id' => $position->id,

            'lga_id' => $location['lga_id'],

            'ward_id' => $location['ward_id'],

            'lcda_id' => $location['lcda_id'],
'lcda_ward_id' => $location['lcda_ward_id'],
        ]);

        return $nomination->fresh([
            'election',
            'politicalParty',
            'candidate',
            'position',
            'lga',
            'ward',
            'lcda',
        ]);

    });
}

public function markReadyForParty(
    Nomination $nomination,
    int $partyId
): Nomination {

    if ($nomination->political_party_id !== $partyId) {
        abort(
            403,
            'This nomination does not belong to your political party.'
        );
    }

    $nomination->loadMissing('election');

    if ($nomination->election->status !== 'nominations_open') {
        throw ValidationException::withMessages([
            'nomination' =>
                'This election is not currently accepting nominations.',
        ]);
    }

    if (! $nomination->canBeMarkedReady()) {
        abort(
            403,
            'This nomination cannot be marked as ready.'
        );
    }

    $nomination->update([
        'status' => Nomination::STATUS_READY,
    ]);

    return $nomination->fresh();
}


public function deleteForParty(
    Nomination $nomination,
    int $politicalPartyId
): void {

    DB::transaction(function () use (
        $nomination,
        $politicalPartyId
    ) {

        /*
        |--------------------------------------------------------------------------
        | Security check
        |--------------------------------------------------------------------------
        */

        if ($nomination->political_party_id !== $politicalPartyId) {
            abort(404);
        }

        /*
        |--------------------------------------------------------------------------
        | Only nominations still under party control
        | may be deleted directly.
        |--------------------------------------------------------------------------
        */

        if (! $nomination->canBeDeleted()) {
            throw ValidationException::withMessages([
                'nomination' =>
                    'This nomination can no longer be removed directly.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | A nomination with a withdrawal request
        | cannot be deleted directly.
        |--------------------------------------------------------------------------
        */

        if ($nomination->hasWithdrawal()) {
            throw ValidationException::withMessages([
                'nomination' =>
                    'This nomination has a withdrawal request and cannot be deleted directly.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Remember the batch.
        |--------------------------------------------------------------------------
        */

        $batch = null;

        if ($nomination->nomination_batch_id) {

            $batch = NominationBatch::query()
                ->with('payment')
                ->find($nomination->nomination_batch_id);

            /*
             * Only draft batches remain under party control.
             */
            if (
                $batch &&
                $batch->status !== NominationBatch::STATUS_DRAFT
            ) {
                throw ValidationException::withMessages([
                    'nomination' =>
                        'This nomination belongs to a batch that has already left the party draft stage.',
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Delete the nomination.
        |
        | The candidate record itself remains.
        |--------------------------------------------------------------------------
        */

        $nomination->delete();

        /*
        |--------------------------------------------------------------------------
        | Refresh the batch.
        |
        | If this was the last nomination, the batch service
        | will delete the empty draft batch and its payment.
        |--------------------------------------------------------------------------
        */

        if ($batch) {
            app(NominationBatchService::class)
                ->refreshBatchTotals($batch);
        }
    });
}

private function loadElection(int $electionId): Election
{
    return Election::query()
        ->with([
            'electionType',
            'state',
            'lga',
            'ward',
            'lcda',
        ])
        ->where('is_active', true)
        ->findOrFail($electionId);
}

private function loadPosition(int $positionId): Position
{
    return Position::query()
        ->where('is_active', true)
        ->findOrFail($positionId);
}

private function validateElectionPosition(
    Election $election,
    Position $position
): void {

    $configured = $election
        ->electionPositions()
        ->where('position_id', $position->id)
        ->where('is_active', true)
        ->exists();

    if (! $configured) {

        throw ValidationException::withMessages([
            'position_id' =>
                'This position is not configured for the selected election.',
        ]);

    }
}

private function resolveLocation(
    Election $election,
    Position $position,
    array $data
): array {

    /*
     * If the election already has a location configured,
     * use it. Otherwise, use the submitted values.
     */
    $lgaId = $election->lga_id ?? ($data['lga_id'] ?? null);
    $wardId = $election->ward_id ?? ($data['ward_id'] ?? null);
    $lcdaId = $election->lcda_id ?? ($data['lcda_id'] ?? null);
    $lcdaWardId = $election->lcda_ward_id ?? ($data['lcda_ward_id'] ?? null);

    switch ($position->code) {

        /*
         * --------------------------------------------------------------
         * LGA Chairmanship / LGA Vice Chairmanship
         * --------------------------------------------------------------
         *
         * Location:
         *     LGA
         *
         * No Ward.
         * No LCDA.
         * No LCDA Ward.
         */
        case 'CHAIR':
        case 'VICE':

            if (! $lgaId) {
                throw ValidationException::withMessages([
                    'lga_id' => 'An LGA is required.',
                ]);
            }

            $lga = Lga::query()
                ->where('id', $lgaId)
                ->where('state_id', $election->state_id)
                ->where('is_active', true)
                ->first();

            if (! $lga) {
                throw ValidationException::withMessages([
                    'lga_id' =>
                        'The selected LGA does not belong to the election state.',
                ]);
            }

            $wardId = null;
            $lcdaId = null;
            $lcdaWardId = null;

            break;


        /*
         * --------------------------------------------------------------
         * LGA Councillorship
         * --------------------------------------------------------------
         *
         * Normal LGA Councillorship:
         *
         *     LGA + Ward
         *
         * Existing LCDA Election / LCDA Bye Election behaviour
         * remains unchanged below.
         */
        case 'COUNC':

            if (! $lgaId) {
                throw ValidationException::withMessages([
                    'lga_id' =>
                        'An LGA is required for Councillorship.',
                ]);
            }

            $lga = Lga::query()
                ->where('id', $lgaId)
                ->where('state_id', $election->state_id)
                ->where('is_active', true)
                ->first();

            if (! $lga) {
                throw ValidationException::withMessages([
                    'lga_id' =>
                        'The selected LGA does not belong to the election state.',
                ]);
            }

            /*
             * LCDA elections use an LCDA Ward.
             */
            if (in_array(
                $election->electionType?->name,
                ['LCDA Election', 'LCDA Bye Election'],
                true
            )) {

                if (! $lcdaId) {
                    throw ValidationException::withMessages([
                        'lcda_id' =>
                            'An LCDA is required for Councillorship.',
                    ]);
                }

                if (! $lcdaWardId) {
                    throw ValidationException::withMessages([
                        'lcda_ward_id' =>
                            'An LCDA Ward is required for Councillorship.',
                    ]);
                }

                $lcda = Lcda::query()
                    ->where('id', $lcdaId)
                    ->where('lga_id', $lga->id)
                    ->where('is_active', true)
                    ->first();

                if (! $lcda) {
                    throw ValidationException::withMessages([
                        'lcda_id' =>
                            'The selected LCDA does not belong to the selected LGA.',
                    ]);
                }

                $lcdaWard = \App\Models\Reference\LcdaWard::query()
                    ->where('id', $lcdaWardId)
                    ->where('lcda_id', $lcda->id)
                    ->where('is_active', true)
                    ->first();

                if (! $lcdaWard) {
                    throw ValidationException::withMessages([
                        'lcda_ward_id' =>
                            'The selected LCDA Ward does not belong to the selected LCDA.',
                    ]);
                }

                $wardId = null;

            } else {

                /*
                 * Normal LGA Councillorship uses a Ward.
                 */
                if (! $wardId) {
                    throw ValidationException::withMessages([
                        'ward_id' =>
                            'A Ward is required for Councillorship.',
                    ]);
                }

                $ward = Ward::query()
                    ->where('id', $wardId)
                    ->where('lga_id', $lga->id)
                    ->where('is_active', true)
                    ->first();

                if (! $ward) {
                    throw ValidationException::withMessages([
                        'ward_id' =>
                            'The selected Ward does not belong to the selected LGA.',
                    ]);
                }

                $lcdaId = null;
                $lcdaWardId = null;
            }

            break;


        /*
         * --------------------------------------------------------------
         * LCDA Chairmanship / LCDA Vice Chairmanship
         * --------------------------------------------------------------
         *
         * Location:
         *
         *     LGA + LCDA
         *
         * No Ward.
         * No LCDA Ward.
         */
        case 'LCDA_CHAIR':
        case 'LCDA_VICE':

            if (! $lgaId) {
                throw ValidationException::withMessages([
                    'lga_id' =>
                        'An LGA is required for LCDA Chairmanship or Vice Chairmanship.',
                ]);
            }

            $lga = Lga::query()
                ->where('id', $lgaId)
                ->where('state_id', $election->state_id)
                ->where('is_active', true)
                ->first();

            if (! $lga) {
                throw ValidationException::withMessages([
                    'lga_id' =>
                        'The selected LGA does not belong to the election state.',
                ]);
            }

            if (! $lcdaId) {
                throw ValidationException::withMessages([
                    'lcda_id' =>
                        'An LCDA is required.',
                ]);
            }

            $lcda = Lcda::query()
                ->where('id', $lcdaId)
                ->where('lga_id', $lga->id)
                ->where('is_active', true)
                ->first();

            if (! $lcda) {
                throw ValidationException::withMessages([
                    'lcda_id' =>
                        'The selected LCDA does not belong to the selected LGA.',
                ]);
            }

            $wardId = null;
            $lcdaWardId = null;

            break;


        /*
         * --------------------------------------------------------------
         * LCDA Councillorship
         * --------------------------------------------------------------
         *
         * Location:
         *
         *     LGA + LCDA + LCDA Ward
         */
        case 'LCDA_COUNC':

            if (! $lgaId) {
                throw ValidationException::withMessages([
                    'lga_id' =>
                        'An LGA is required for LCDA Councillorship.',
                ]);
            }

            $lga = Lga::query()
                ->where('id', $lgaId)
                ->where('state_id', $election->state_id)
                ->where('is_active', true)
                ->first();

            if (! $lga) {
                throw ValidationException::withMessages([
                    'lga_id' =>
                        'The selected LGA does not belong to the election state.',
                ]);
            }

            if (! $lcdaId) {
                throw ValidationException::withMessages([
                    'lcda_id' =>
                        'An LCDA is required for LCDA Councillorship.',
                ]);
            }

            $lcda = Lcda::query()
                ->where('id', $lcdaId)
                ->where('lga_id', $lga->id)
                ->where('is_active', true)
                ->first();

            if (! $lcda) {
                throw ValidationException::withMessages([
                    'lcda_id' =>
                        'The selected LCDA does not belong to the selected LGA.',
                ]);
            }

            if (! $lcdaWardId) {
                throw ValidationException::withMessages([
                    'lcda_ward_id' =>
                        'An LCDA Ward is required for LCDA Councillorship.',
                ]);
            }

            $lcdaWard = \App\Models\Reference\LcdaWard::query()
                ->where('id', $lcdaWardId)
                ->where('lcda_id', $lcda->id)
                ->where('is_active', true)
                ->first();

            if (! $lcdaWard) {
                throw ValidationException::withMessages([
                    'lcda_ward_id' =>
                        'The selected LCDA Ward does not belong to the selected LCDA.',
                ]);
            }

            $wardId = null;

            break;


        default:

            throw ValidationException::withMessages([
                'position_id' => 'Unsupported position.',
            ]);
    }

    return [
        'lga_id' => $lgaId,
        'ward_id' => $wardId,
        'lcda_id' => $lcdaId,
        'lcda_ward_id' => $lcdaWardId,
    ];
}

private function ensureCandidateNotAlreadyNominated(
    Election $election,
    int $candidateId,
    ?Nomination $ignore = null
): void {
    $query = Nomination::query()
        ->where('election_id', $election->id)
        ->where('candidate_id', $candidateId);

    if ($ignore) {
        $query->whereKeyNot($ignore->id);
    }

    if ($query->exists()) {
        throw ValidationException::withMessages([
            'nin' =>
                'This candidate has already been nominated for this election and cannot be nominated for another position in the same election.',
        ]);
    }
}
}
