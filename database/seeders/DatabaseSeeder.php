<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\Reference\StateSeeder;
use Database\Seeders\Reference\ElectionTypeSeeder;
use Database\Seeders\Reference\PositionSeeder;
use Database\Seeders\Reference\LgaSeeder;
use Database\Seeders\Reference\LcdaSeeder;
use Database\Seeders\Reference\WardSeeder;
use Database\Seeders\CandidateChangeReasonSeeder;
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Seed the application's database.
     */
 public function run(): void
{
    $this->call([
    RolesAndPermissionsSeeder::class,
    CandidateChangeReasonSeeder::class,
    AdminUserSeeder::class,
    StateSeeder::class,
    ElectionTypeSeeder::class,
    PositionSeeder::class,
    LgaSeeder::class,
    WardSeeder::class,
    LcdaSeeder::class,
]);
}
}

