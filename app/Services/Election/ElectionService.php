<?php

namespace App\Services\Election;

use App\Models\Election\Election;
use App\Models\Election\Position;
use Illuminate\Support\Facades\DB;

class ElectionService
{
    public function create(array $data): Election
    {
        return DB::transaction(function () use ($data) {

            $positions = $data['positions'];

            unset($data['positions']);

            $election = Election::create([
                'election_type_id' => $data['election_type_id'],
                'state_id' => $data['state_id'],
                'name' => $data['name'],
                'election_date' => $data['election_date'],
                'status' => 'draft',
                'is_active' => true,
            ]);

            $vicePositionId = Position::query()
                ->where('code', 'VICE')
                ->value('id');

            foreach ($positions as $position) {

                $fee = (int) $position['position_id'] === (int) $vicePositionId
                    ? 0
                    : $position['nomination_fee'];

                $election->electionPositions()->create([
                    'position_id' => $position['position_id'],
                    'nomination_fee' => $fee,
                    'is_active' => true,
                ]);
            }

            return $election->load([
                'electionType',
                'state',
                'electionPositions.position',
            ]);
        });
    }
}
