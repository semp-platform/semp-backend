<?php

namespace Database\Seeders\Reference;

use App\Models\Reference\Lga;
use App\Models\Reference\State;
use Illuminate\Database\Seeder;
use RuntimeException;

class LgaSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('reference-data/lgas.json');

        if (! file_exists($path)) {
            throw new RuntimeException(
                'LGA reference data file not found: ' . $path
            );
        }

        $data = json_decode(
            file_get_contents($path),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        foreach ($data as $stateName => $lgas) {
            $state = State::where('name', $stateName)->first();

            if (! $state) {
                throw new RuntimeException(
                    "State '{$stateName}' from lgas.json was not found in the states table."
                );
            }

            foreach ($lgas as $lgaName) {
                Lga::updateOrCreate(
                    [
                        'state_id' => $state->id,
                        'name' => $lgaName,
                    ],
                    [
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
