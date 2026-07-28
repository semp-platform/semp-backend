<?php

namespace Database\Seeders\Reference;

use App\Models\Reference\Lga;
use App\Models\Reference\State;
use App\Models\Reference\Ward;
use Illuminate\Database\Seeder;
use RuntimeException;

class WardSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('reference-data/wards.json');

        if (! file_exists($path)) {
            throw new RuntimeException(
                'Ward reference data file not found: ' . $path
            );
        }

        $wards = json_decode(
            file_get_contents($path),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        foreach ($wards as $wardData) {
            $stateName = trim($wardData['State']);
            $lgaName = trim($wardData['LGA']);
            $wardName = trim($wardData['Ward']);

            // Correct known spelling mismatch in the source dataset.
            if ($stateName === 'Nassarawa') {
                $stateName = 'Nasarawa';
            }

            // Normalize known LGA naming differences in the source dataset.
$lgaNameMappings = [
    'Ogun' => [
        'Egbado North' => 'Yewa North',
        'Egbado South' => 'Yewa South',
    ],
];

$lgaName = $lgaNameMappings[$stateName][$lgaName] ?? $lgaName;

            $state = State::where('name', $stateName)->first();

            if (! $state) {
                throw new RuntimeException(
                    "State '{$stateName}' from wards.json was not found."
                );
            }

            $lga = Lga::where('state_id', $state->id)
                ->where('name', $lgaName)
                ->first();

            if (! $lga) {
                throw new RuntimeException(
                    "LGA '{$lgaName}' in state '{$stateName}' from wards.json was not found."
                );
            }

            Ward::updateOrCreate(
                [
                    'lga_id' => $lga->id,
                    'name' => $wardName,
                ],
                [
                    'is_active' => true,
                ]
            );
        }
    }
}
