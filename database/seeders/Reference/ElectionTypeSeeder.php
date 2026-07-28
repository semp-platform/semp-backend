<?php

namespace Database\Seeders\Reference;

use Illuminate\Database\Seeder;
use App\Models\Election\ElectionType;

class ElectionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $electionTypes = [

            [
                'name' => 'Local Government Election',
                'description' => 'Election conducted for Local Government Councils.',
            ],

            [
                'name' => 'LCDA Election',
                'description' => 'Election conducted for Local Council Development Areas.',
            ],

            [
                'name' => 'Bye Election',
                'description' => 'Election conducted to fill a vacant elective office.',
            ],

            [
                'name' => 'Re-run Election',
                'description' => 'Election conducted following cancellation or court order.',
            ],

            [
                'name' => 'Supplementary Election',
                'description' => 'Election conducted to conclude an inconclusive election.',
            ],

        ];

        foreach ($electionTypes as $type) {

            ElectionType::updateOrCreate(
                ['name' => $type['name']],
                [
                    'description' => $type['description'],
                    'is_active' => true,
                ]
            );

        }
    }
}
