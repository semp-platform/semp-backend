<?php

namespace Database\Seeders\Reference;

use Illuminate\Database\Seeder;
use App\Models\Election\Position;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [

            [
                'name' => 'Chairmanship',
                'code' => 'CHAIR',
                'display_order' => 1,
            ],

            [
                'name' => 'Vice Chairmanship',
                'code' => 'VICE',
                'display_order' => 2,
            ],

            [
                'name' => 'Councillorship',
                'code' => 'COUNC',
                'display_order' => 3,
            ],

        ];

        foreach ($positions as $position) {

            Position::updateOrCreate(

                [
                    'code' => $position['code'],
                ],

                [
                    'name' => $position['name'],
                    'display_order' => $position['display_order'],
                    'is_active' => true,
                ]

            );

        }
    }
}
