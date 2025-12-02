<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClosurePeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('closure_periods')->insert([
            // Past closure periods (active)
            [
                'start_date' => Carbon::now()->subDays(15)->format('Y-m-d'),
                'end_date' => Carbon::now()->subDays(10)->format('Y-m-d'),
                'reason' => 'Facility Maintenance',
                'status' => 'active',
                'deleted_at' => null,
                'created_at' => Carbon::now()->subDays(20),
                'updated_at' => Carbon::now()->subDays(20),
            ],
            [
                'start_date' => Carbon::now()->subDays(30)->format('Y-m-d'),
                'end_date' => Carbon::now()->subDays(28)->format('Y-m-d'),
                'reason' => 'Holiday Break',
                'status' => 'active',
                'deleted_at' => null,
                'created_at' => Carbon::now()->subDays(35),
                'updated_at' => Carbon::now()->subDays(35),
            ],
            [
                'start_date' => Carbon::now()->subDays(60)->format('Y-m-d'),
                'end_date' => Carbon::now()->subDays(55)->format('Y-m-d'),
                'reason' => 'System Upgrade',
                'status' => 'active',
                'deleted_at' => null,
                'created_at' => Carbon::now()->subDays(65),
                'updated_at' => Carbon::now()->subDays(65),
            ],
            
            // Future closure periods (pending)
            [
                'start_date' => Carbon::now()->addDays(7)->format('Y-m-d'),
                'end_date' => Carbon::now()->addDays(9)->format('Y-m-d'),
                'reason' => 'Scheduled Maintenance',
                'status' => 'pending',
                'deleted_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'start_date' => Carbon::now()->addDays(20)->format('Y-m-d'),
                'end_date' => Carbon::now()->addDays(22)->format('Y-m-d'),
                'reason' => 'Equipment Inspection',
                'status' => 'pending',
                'deleted_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'start_date' => Carbon::now()->addDays(45)->format('Y-m-d'),
                'end_date' => Carbon::now()->addDays(47)->format('Y-m-d'),
                'reason' => 'Annual Inventory',
                'status' => 'pending',
                'deleted_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
