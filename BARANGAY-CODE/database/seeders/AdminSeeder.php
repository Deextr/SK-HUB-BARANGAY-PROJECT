<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Check if admin already exists
        if (User::where('email', 'admin@gmail.com')->exists()) {
            $this->command->info('Admin account already exists!');
            return;
        }

        User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123123123'),
            'is_admin' => true,
            'account_status' => 'approved', // Admin account is automatically approved
        ]);

        $this->command->info('Admin account created successfully!');
        $this->command->info('Email: admin@gmail.com');
        $this->command->info('Password: 123123123');
    }
}
