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

class NominationService
{
    public function __construct(
        protected CandidateService $candidateService
    ) {}

    public function create(array $data): Nomination
    {
        return DB::transaction(function () use ($data) {

            $election = Election::query()
                ->where('is_active', true)
                ->findOrFail($data['election_id']);

            $position = Position::query()
                ->where('is_active', true)
                ->findOrFail($data['position_id']);

            $positionIsConfigured = $election->electionPositions()
                ->where('position_id', $position->id)
                ->where('is_active', true)
                ->exists();

            if (! $positionIsConfigured) {
                throw ValidationException::withMessages([
                    'position_id' => 'This position is not configured for the selected election.',
                ]);
            }

            $lgaId = null;
            $wardId = null;

            if (in_array($position->code, ['CHAIR', 'VICE'], true)) {

                if (empty($data['lga_id'])) {
                    throw ValidationException::withMessages([
                        'lga_id' => 'An LGA is required for this position.',
                    ]);
                }

                if (! empty($data['ward_id'])) {
                    throw ValidationException::withMessages([
                        'ward_id' => 'A ward must not be selected for this position.',
                    ]);
                }

                $lga = Lga::query()
                    ->where('id', $data['lga_id'])
                    ->where('state_id', $election->state_id)
                    ->where('is_active', true)
                    ->first();

                if (! $lga) {
                    throw ValidationException::withMessages([
                        'lga_id' => 'The selected LGA does not belong to the election state.',
                    ]);
                }

                $lgaId = $lga->id;

            } elseif ($position->code === 'COUNC') {

                if (empty($data['ward_id'])) {
                    throw ValidationException::withMessages([
                        'ward_id' => 'A ward is required for Councillorship.',
                    ]);
                }

                $ward = Ward::query()
                    ->with('lga')
                    ->where('id', $data['ward_id'])
                    ->where('is_active', true)
                    ->first();

                if (! $ward || ! $ward->lga || $ward->lga->state_id !== $election->state_id) {
                    throw ValidationException::withMessages([
                        'ward_id' => 'The selected ward does not belong to the election state.',
                    ]);
                }

                $wardId = $ward->id;
                $lgaId = $ward->lga_id;

            } else {
                throw ValidationException::withMessages([
                    'position_id' => 'Unsupported position.',
                ]);
            }

            $candidate = $this->candidateService
                ->findOrCreateFromNin($data['nin']);

            $alreadyNominated = Nomination::query()
                ->where('election_id', $election->id)
                ->where('candidate_id', $candidate->id)
                ->where('position_id', $position->id)
                ->exists();

            if ($alreadyNominated) {
                throw ValidationException::withMessages([
                    'nin' => 'This candidate has already been nominated for this position in this election.',
                ]);
            }

            return Nomination::create([
                'election_id' => $election->id,
                'political_party_id' => $data['political_party_id'],
                'candidate_id' => $candidate->id,
                'position_id' => $position->id,
                'lga_id' => $lgaId,
                'ward_id' => $wardId,
                'status' => 'draft',
            ])->load([
                'election',
                'politicalParty',
                'candidate',
                'position',
                'lga',
                'ward',
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
        ])
        ->findOrFail($id);
}
}
