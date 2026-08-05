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
    'election_type_id'        => $data['election_type_id'],
'state_id'                => $data['state_id'],
'lga_id'                  => $data['lga_id'] ?? null,
'ward_id'                 => $data['ward_id'] ?? null,
'lcda_id'                 => $data['lcda_id'] ?? null,
'name'                    => $data['name'],
    'election_date'          => $data['election_date'],

    'nomination_open_date'   => $data['nomination_open_date'] ?? null,
    'nomination_close_date'  => $data['nomination_close_date'] ?? null,
    'screening_date'         => $data['screening_date'] ?? null,
    'appeal_deadline'        => $data['appeal_deadline'] ?? null,
    'result_declaration_date'=> $data['result_declaration_date'] ?? null,

    'status' => 'draft',
    'is_active'              => true,
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
'lga',
'ward',
'lcda',
'electionPositions.position',
            ]);
        });
    }

    public function getAll()
{
    return Election::query()
        ->with([
           'electionType',
'state',
'lga',
'ward',
'lcda',
'electionPositions.position',
        ])
        ->orderByDesc('election_date')
        ->get();
}

public function getById(int $id): Election
{
    return Election::query()
        ->with([
            'electionType',
'state',
'lga',
'ward',
'lcda',
'electionPositions.position',
        ])
        ->findOrFail($id);
}
public function update(int $id, array $data): Election
{
    return DB::transaction(function () use ($id, $data) {

        $election = Election::query()
            ->with('electionPositions')
            ->findOrFail($id);

        $positions = $data['positions'];

        unset($data['positions']);

        $election->update([
   'election_type_id'        => $data['election_type_id'],
'state_id'                => $data['state_id'],
'lga_id'                  => $data['lga_id'] ?? null,
'ward_id'                 => $data['ward_id'] ?? null,
'lcda_id'                 => $data['lcda_id'] ?? null,
'name'                    => $data['name'],
    'election_date'           => $data['election_date'],

    'nomination_open_date'    => $data['nomination_open_date'] ?? null,
    'nomination_close_date'   => $data['nomination_close_date'] ?? null,
    'screening_date'          => $data['screening_date'] ?? null,
    'appeal_deadline'         => $data['appeal_deadline'] ?? null,
    'result_declaration_date' => $data['result_declaration_date'] ?? null,


]);

        $vicePositionId = Position::query()
            ->where('code', 'VICE')
            ->value('id');

        // Remove existing election positions
        $election->electionPositions()->delete();

        // Re-create the selected positions
        foreach ($positions as $position) {

            $fee = (int) $position['position_id'] === (int) $vicePositionId
                ? 0
                : $position['nomination_fee'];

            $election->electionPositions()->create([
                'position_id'     => $position['position_id'],
                'nomination_fee'  => $fee,
                'is_active'       => true,
            ]);
        }

        return $election->load([
            'electionType',
'state',
'lga',
'ward',
'lcda',
'electionPositions.position',
        ]);
    });
}
public function openNominations(Election $election): Election
{
    $election->update([
        'status' => 'nominations_open',
    ]);

    return $election->refresh();
}

public function startScreening(Election $election): Election
{
    $election->update([
        'status' => 'screening',
    ]);

    return $election->refresh();
}

public function complete(Election $election): Election
{
    $election->update([
        'status' => 'completed',
    ]);

    return $election->refresh();
}

public function archive(Election $election): Election
{
    $election->update([
        'status' => 'archived',
    ]);

    return $election->refresh();
}
}
