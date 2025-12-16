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
        $this->call(AdminSeeder::class);
        $this->call(ClosurePeriodSeeder::class);
        // Seed services (must run before reservations)
        $this->call(ServiceSeeder::class);
        // Seed original 20 resident accounts (specific people)
        //$this->call(ResidentSeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(UserSeeder::class);
        // Seed additional 100 resident accounts (30 pending, 40 approved, 30 rejected)
        $this->call(ResidentAccountsSeeder::class);
        $this->call(ReservationSeeder::class);

        // Seed archived users (25 archived accounts)
        $this->call(ArchivedUsersSeeder::class);
    }
}
