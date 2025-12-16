<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create approved users
        User::factory()->count(2)->approved()->female()->create();
        User::factory()->count(2)->approved()->male()->create();
        User::factory()->count(2)->approved()->female()->pwd()->create();
        User::factory()->count(2)->approved()->male()->pwd()->create();

        // Create pending users
        User::factory()->count(3)->pending()->male()->create();
        User::factory()->count(3)->pending()->female()->create();
        User::factory()->count(2)->pending()->female()->pwd()->create();
        User::factory()->count(2)->pending()->male()->pwd()->create();

        // Create rejected users
        User::factory()->count(2)->rejected()->male()->create();
        User::factory()->count(2)->rejected()->female()->create();
        User::factory()->count(1)->rejected()->male()->pwd()->create();
        User::factory()->count(1)->rejected()->female()->pwd()->create();

        // Create partially rejected users
        User::factory()->count(2)->partiallyRejected()->female()->create();
        User::factory()->count(2)->partiallyRejected()->male()->create();
        User::factory()->count(1)->partiallyRejected()->male()->pwd()->create();
        User::factory()->count(1)->partiallyRejected()->female()->pwd()->create();

        // Create archived users
        User::factory()->count(2)->archived()->female()->create();
        User::factory()->count(2)->archived()->male()->create();
        User::factory()->count(1)->archived()->female()->pwd()->create();
        User::factory()->count(1)->archived()->male()->pwd()->create();
    }
}
