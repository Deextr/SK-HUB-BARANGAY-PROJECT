<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ResidentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            //Men residents (10 total)
        $men = [
            // Specified names
            [
                'first_name' => 'Dexter',
                'last_name' => 'Ramos',
                'email' => 'dexter@gmail.com',
                'birth_date' => Carbon::createFromDate(1990, 5, 15),
                'sex' => 'Male',
            ],
            [
                'first_name' => 'Nathaniel',
                'last_name' => 'Bayona',
                'email' => 'nathaniel@gmail.com',
                'birth_date' => Carbon::createFromDate(1992, 8, 22),
                'sex' => 'Male',
            ],
            [
                'first_name' => 'Park',
                'last_name' => 'Gica',
                'email' => 'park@gmail.com',
                'birth_date' => Carbon::createFromDate(1988, 3, 10),
                'sex' => 'Male',
            ],
            [
                'first_name' => 'Christian',
                'last_name' => 'Torion',
                'email' => 'chan@gmail.com',
                'birth_date' => Carbon::createFromDate(1995, 11, 5),
                'sex' => 'Male',
            ],
            [
                'first_name' => 'Rocky',
                'last_name' => 'Adaya',
                'email' => 'rocky@gmail.com',
                'birth_date' => Carbon::createFromDate(1991, 7, 18),
                'sex' => 'Male',
            ],
            // Additional men
            [
                'first_name' => 'Juan',
                'last_name' => 'Santos',
                'email' => 'juan@gmail.com',
                'birth_date' => Carbon::createFromDate(1993, 2, 14),
                'sex' => 'Male',
            ],
            [
                'first_name' => 'Miguel',
                'last_name' => 'Cruz',
                'email' => 'miguel@gmail.com',
                'birth_date' => Carbon::createFromDate(1989, 9, 28),
                'sex' => 'Male',
            ],
            [
                'first_name' => 'Carlos',
                'last_name' => 'Reyes',
                'email' => 'carlos@gmail.com',
                'birth_date' => Carbon::createFromDate(1994, 4, 3),
                'sex' => 'Male',
            ],
            [
                'first_name' => 'Antonio',
                'last_name' => 'Flores',
                'email' => 'antonio@gmail.com',
                'birth_date' => Carbon::createFromDate(1987, 12, 20),
                'sex' => 'Male',
            ],
            [
                'first_name' => 'Roberto',
                'last_name' => 'Morales',
                'email' => 'roberto@gmail.com',
                'birth_date' => Carbon::createFromDate(1996, 6, 11),
                'sex' => 'Male',
            ],
        ];

        // Women residents (10 total)
        $women = [
            [
                'first_name' => 'Maria',
                'last_name' => 'Garcia',
                'email' => 'maria@gmail.com',
                'birth_date' => Carbon::createFromDate(1991, 1, 8),
                'sex' => 'Female',
            ],
            [
                'first_name' => 'Rosa',
                'last_name' => 'Lopez',
                'email' => 'rosa@gmail.com',
                'birth_date' => Carbon::createFromDate(1993, 5, 19),
                'sex' => 'Female',
            ],
            [
                'first_name' => 'Ana',
                'last_name' => 'Martinez',
                'email' => 'ana@gmail.com',
                'birth_date' => Carbon::createFromDate(1990, 10, 25),
                'sex' => 'Female',
            ],
            [
                'first_name' => 'Sofia',
                'last_name' => 'Hernandez',
                'email' => 'sofia@gmail.com',
                'birth_date' => Carbon::createFromDate(1994, 3, 12),
                'sex' => 'Female',
            ],
            [
                'first_name' => 'Isabella',
                'last_name' => 'Perez',
                'email' => 'isabella@gmail.com',
                'birth_date' => Carbon::createFromDate(1992, 7, 30),
                'sex' => 'Female',
            ],
            [
                'first_name' => 'Carmen',
                'last_name' => 'Sanchez',
                'email' => 'carmen@gmail.com',
                'birth_date' => Carbon::createFromDate(1988, 11, 6),
                'sex' => 'Female',
            ],
            [
                'first_name' => 'Elena',
                'last_name' => 'Ramirez',
                'email' => 'elena@gmail.com',
                'birth_date' => Carbon::createFromDate(1995, 2, 17),
                'sex' => 'Female',
            ],
            [
                'first_name' => 'Lucia',
                'last_name' => 'Torres',
                'email' => 'lucia@gmail.com',
                'birth_date' => Carbon::createFromDate(1989, 8, 9),
                'sex' => 'Female',
            ],
            [
                'first_name' => 'Daniela',
                'last_name' => 'Rivera',
                'email' => 'daniela@gmail.com',
                'birth_date' => Carbon::createFromDate(1996, 4, 21),
                'sex' => 'Female',
            ],
            [
                'first_name' => 'Valentina',
                'last_name' => 'Castillo',
                'email' => 'valentina@gmail.com',
                'birth_date' => Carbon::createFromDate(1991, 9, 14),
                'sex' => 'Female',
            ],
        ];

        // Merge all residents
        $allResidents = array_merge($men, $women);

        // Create each resident account
        foreach ($allResidents as $resident) {
            // Check if user already exists
            if (User::where('email', $resident['email'])->exists()) {
                $this->command->info("Resident {$resident['first_name']} {$resident['last_name']} already exists. Skipping...");
                continue;
            }

            User::create([
                'first_name' => $resident['first_name'],
                'last_name' => $resident['last_name'],
                'email' => $resident['email'],
                'password' => Hash::make('123123123'),
                'birth_date' => $resident['birth_date'],
                'sex' => $resident['sex'],
                'is_pwd' => false,
                'is_admin' => false,
                'account_status' => 'approved', // Auto-approve for testing
                'suspension_count' => 0,
                'is_suspended' => false,
                'is_archived' => false,
            ]);

            $this->command->info("Created resident: {$resident['first_name']} {$resident['last_name']} ({$resident['email']})");
        }

        $this->command->info('Resident seeder completed! Created 20 resident accounts.');
    }
}
 