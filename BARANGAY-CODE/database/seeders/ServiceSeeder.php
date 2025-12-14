<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use Carbon\Carbon;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Active services
        $services = [
            [
                'name' => 'PC',
                'description' => 'Personal Computer workstation with internet access for research, document processing, and online activities.',
                'capacity_units' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Study Area',
                'description' => 'Quiet study space with desk and chair for reading, studying, and focused work.',
                'capacity_units' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'TV',
                'description' => 'Television viewing area for entertainment, news, and educational programs.',
                'capacity_units' => 1,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        // Archived services (soft deleted)
        $archivedServices = [
            [
                'name' => 'Gaming Console',
                'description' => 'Gaming station with console and controllers. Service discontinued due to maintenance costs.',
                'capacity_units' => 2,
                'is_active' => false,
                'deleted_at' => Carbon::now()->subDays(120),
                'created_at' => Carbon::now()->subDays(500),
                'updated_at' => Carbon::now()->subDays(120),
            ],
            [
                'name' => 'Karaoke Room',
                'description' => 'Private room with karaoke equipment. Archived due to noise complaints.',
                'capacity_units' => 1,
                'is_active' => false,
                'deleted_at' => Carbon::now()->subDays(90),
                'created_at' => Carbon::now()->subDays(400),
                'updated_at' => Carbon::now()->subDays(90),
            ],
            [
                'name' => 'VR Station',
                'description' => 'Virtual Reality gaming and experience station. Service discontinued due to equipment obsolescence.',
                'capacity_units' => 1,
                'is_active' => false,
                'deleted_at' => Carbon::now()->subDays(60),
                'created_at' => Carbon::now()->subDays(300),
                'updated_at' => Carbon::now()->subDays(60),
            ],
            [
                'name' => 'Printing Service',
                'description' => 'Document printing and scanning service. Archived due to outsourcing to external provider.',
                'capacity_units' => 2,
                'is_active' => false,
                'deleted_at' => Carbon::now()->subDays(45),
                'created_at' => Carbon::now()->subDays(600),
                'updated_at' => Carbon::now()->subDays(45),
            ],
            [
                'name' => 'Boardgame Collection',
                'description' => 'Board games and table games for recreational activities. Archived due to low usage.',
                'capacity_units' => 4,
                'is_active' => false,
                'deleted_at' => Carbon::now()->subDays(30),
                'created_at' => Carbon::now()->subDays(350),
                'updated_at' => Carbon::now()->subDays(30),
            ],
            [
                'name' => 'Music Room',
                'description' => 'Practice room with musical instruments. Discontinued due to space reallocation.',
                'capacity_units' => 1,
                'is_active' => false,
                'deleted_at' => Carbon::now()->subDays(75),
                'created_at' => Carbon::now()->subDays(550),
                'updated_at' => Carbon::now()->subDays(75),
            ],
            [
                'name' => 'Art Studio',
                'description' => 'Creative space with art supplies and equipment. Archived due to facility renovation.',
                'capacity_units' => 6,
                'is_active' => false,
                'deleted_at' => Carbon::now()->subDays(100),
                'created_at' => Carbon::now()->subDays(450),
                'updated_at' => Carbon::now()->subDays(100),
            ],
            [
                'name' => 'Podcast Recording Booth',
                'description' => 'Soundproof booth with recording equipment. Service ended due to equipment malfunction.',
                'capacity_units' => 2,
                'is_active' => false,
                'deleted_at' => Carbon::now()->subDays(15),
                'created_at' => Carbon::now()->subDays(200),
                'updated_at' => Carbon::now()->subDays(15),
            ],
        ];

        foreach ($archivedServices as $service) {
            Service::create($service);
        }

        $this->command->info('Services seeded successfully! (3 active, 8 archived)');
    }
}
