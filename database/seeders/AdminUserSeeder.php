<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@semp.local'],
            [
                'name' => 'SEMP Administrator',
                'password' => bcrypt('Password123!'),
            ]
        );

        $user->assignRole('Super Admin');
    }
}
