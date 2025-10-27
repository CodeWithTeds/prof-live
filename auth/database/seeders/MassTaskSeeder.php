<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Services\Seed\MassTaskSeederService;

class MassTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $count = (int) (env('MASS_TASK_SEED_COUNT', 50000000));
        $batch = (int) (env('MASS_TASK_SEED_BATCH', 10000));

        // Ensure there is at least one user to assign tasks to
        $user = User::query()->first();
        if (!$user) {
            $user = User::forceCreate([
                'name' => 'Seed User',
                'email' => 'seed@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        // Use the service for high-throughput seeding
        $service = new MassTaskSeederService();
        $service->seed($count, $batch, $user->id);
    }
}