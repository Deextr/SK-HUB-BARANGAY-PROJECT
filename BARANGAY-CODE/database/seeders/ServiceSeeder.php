<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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

        $this->command->info('Services seeded successfully!');
    }
}
