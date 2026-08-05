<?php

namespace App\Services\Party;

use App\Models\Party\PoliticalParty;
use Illuminate\Support\Facades\DB;

class PoliticalPartyService
{
    public function all()
    {
        return PoliticalParty::orderBy('name')
            ->paginate(15);
    }

    public function create(array $data): PoliticalParty
    {
        return DB::transaction(function () use ($data) {

            return PoliticalParty::create($data);

        });
    }

    public function update(
        PoliticalParty $politicalParty,
        array $data
    ): PoliticalParty {

        return DB::transaction(function () use ($politicalParty, $data) {

            $politicalParty->update($data);

            return $politicalParty->refresh();

        });
    }

    public function activate(PoliticalParty $politicalParty): void
    {
        $politicalParty->update([
            'is_active' => true,
        ]);
    }

    public function deactivate(PoliticalParty $politicalParty): void
    {
        $politicalParty->update([
            'is_active' => false,
        ]);
    }
}
