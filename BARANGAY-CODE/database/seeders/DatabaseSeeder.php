<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed admin account
        $this->call(AdminSeeder::class);

        // Seed services (must run before reservations)
        $this->call(ServiceSeeder::class);

        // Seed original 20 resident accounts (specific people)
        $this->call(ResidentSeeder::class);

        // Seed additional 100 resident accounts (30 pending, 40 approved, 30 rejected)
        $this->call(ResidentAccountsSeeder::class);

        // Seed reservations for 2025 (1000 records: 600 completed, 250 cancelled, 150 pending)
        $this->call(ReservationSeeder::class);

        // Uncomment to create test users
        // User::factory(10)->create();
    }
}
