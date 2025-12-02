<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Service;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if we have users and services
        $usersCount = User::where('account_status', 'approved')->where('is_admin', 0)->count();
        $servicesCount = Service::where('is_active', 1)->whereNull('deleted_at')->count();
        
        if ($usersCount === 0 || $servicesCount === 0) {
            $this->command->warn('Please seed Users and Services first!');
            return;
        }

        // Create future pending reservations
        Reservation::factory()->count(15)->future()->pending()->create();

        // Create future cancelled reservations
        Reservation::factory()->count(5)->future()->cancelled()->create();

        // Create past completed reservations (with time logs)
        Reservation::factory()->count(40)->past()->completed()->create();

        // Create past cancelled reservations
        Reservation::factory()->count(15)->past()->cancelled()->create();

        // Create no-show reservations (cancelled with suspension)
        Reservation::factory()->count(5)->past()->noShow()->create();

        // Create some with specific reasons
        Reservation::factory()->count(5)->past()->completed()->withReason('Surfing')->create();
        Reservation::factory()->count(5)->past()->completed()->withReason('Reading')->create();
        Reservation::factory()->count(5)->future()->pending()->withReason('Making Activity')->create();
        Reservation::factory()->count(3)->past()->cancelled()->withOtherReason('change of plan')->create();

        $this->command->info('Reservations seeded successfully!');
    }
}
