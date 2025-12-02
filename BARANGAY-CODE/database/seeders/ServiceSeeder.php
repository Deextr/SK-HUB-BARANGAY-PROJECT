<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('services')->insert([
            [
                'id' => 1,
                'name' => 'PC TEST',
                'description' => 'testing',
                'capacity_units' => 2,
                'is_active' => 0,
                'deleted_at' => null,
                'created_at' => '2025-11-30 08:31:39',
                'updated_at' => '2025-11-30 08:35:01',
            ],
            [
                'id' => 2,
                'name' => 'PC',
                'description' => 'high-end PC',
                'capacity_units' => 8,
                'is_active' => 1,
                'deleted_at' => null,
                'created_at' => '2025-11-30 08:31:48',
                'updated_at' => '2025-11-30 08:31:48',
            ],
            [
                'id' => 3,
                'name' => 'TV',
                'description' => 'For presentation',
                'capacity_units' => 1,
                'is_active' => 1,
                'deleted_at' => null,
                'created_at' => '2025-11-30 08:32:01',
                'updated_at' => '2025-11-30 08:32:01',
            ],
            [
                'id' => 4,
                'name' => 'Study Table',
                'description' => 'it depends on chairs available',
                'capacity_units' => 8,
                'is_active' => 1,
                'deleted_at' => null,
                'created_at' => '2025-11-30 08:32:28',
                'updated_at' => '2025-11-30 08:32:28',
            ],
            [
                'id' => 5,
                'name' => 'discussion area',
                'description' => 'For group study or meetings',
                'capacity_units' => 5,
                'is_active' => 1,
                'deleted_at' => null,
                'created_at' => '2025-11-30 08:32:39',
                'updated_at' => '2025-11-30 08:32:39',
            ],
             [
                'id' => 6,
                'name' => 'Projector',
                'description' => 'For presentations and events',
                'capacity_units' => 1,
                'is_active' => 1,
                'deleted_at' => null,
                'created_at' => '2025-11-30 08:33:12',
                'updated_at' => '2025-11-30 08:33:12',
             ],
             
        ]);
    }
}
