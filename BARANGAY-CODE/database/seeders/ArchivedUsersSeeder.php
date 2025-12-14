<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ArchivedUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('123123123');
        
        // Filipino first names
        $firstNames = [
            'Juan', 'Maria', 'Jose', 'Ana', 'Pedro', 'Rosa', 'Carlos', 'Elena', 'Miguel', 'Sofia',
            'Antonio', 'Carmen', 'Luis', 'Isabel', 'Ramon', 'Teresa', 'Francisco', 'Luz', 'Manuel', 'Cristina',
            'Roberto', 'Angela', 'Ricardo', 'Patricia', 'Fernando', 'Gloria', 'Eduardo', 'Beatriz', 'Alejandro', 'Margarita',
            'Jorge', 'Dolores', 'Alberto', 'Rosario', 'Javier', 'Pilar', 'Rafael', 'Mercedes', 'Diego', 'Josefa',
            'Andres', 'Concepcion', 'Felipe', 'Esperanza', 'Sergio', 'Remedios', 'Arturo', 'Felicidad', 'Enrique', 'Milagros',
        ];
        
        // Filipino last names
        $lastNames = [
            'Santos', 'Reyes', 'Cruz', 'Bautista', 'Ocampo', 'Garcia', 'Mendoza', 'Torres', 'Gonzales', 'Lopez',
            'Flores', 'Rivera', 'Ramos', 'Castillo', 'Aquino', 'Villanueva', 'Santiago', 'Fernandez', 'Dela Cruz', 'Manalo',
            'Pascual', 'Mercado', 'Aguilar', 'Salazar', 'Navarro', 'Morales', 'Castro', 'Diaz', 'Perez', 'Hernandez',
        ];
        
        // Archive reasons
        $archiveReasons = [
            'Repeated violation of facility rules and regulations.',
            'Multiple no-show incidents without cancellation.',
            'Abusive behavior towards staff or other residents.',
            'Fraudulent reservation activities detected.',
            'Exceeded maximum suspension limit.',
            'Permanent relocation outside the barangay.',
            'Account closure requested by user.',
            'Duplicate account - consolidated with primary account.',
            'Chronic misuse of facilities and resources.',
            'Violation of terms and conditions.',
            'Inappropriate conduct during facility use.',
            'Failed to comply with suspension requirements.',
            'Account inactive for extended period - archived per policy.',
            'Banned from facility use by barangay council.',
            'Security concerns and safety violations.',
        ];
        
        $sexOptions = ['Male', 'Female'];
        $users = [];
        
        // Generate 25 archived users
        for ($i = 0; $i < 25; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $sex = $sexOptions[array_rand($sexOptions)];
            
            // Generate birth date (18-60 years old)
            $birthDate = Carbon::now()->subYears(rand(18, 60))->subDays(rand(0, 365));
            
            // Generate email
            $email = strtolower($firstName . '.' . $lastName . rand(1000, 9999) . '@example.com');
            
            // These users were previously approved before being archived
            $approvedAt = Carbon::now()->subDays(rand(180, 730)); // Approved 6 months to 2 years ago
            $archivedAt = Carbon::now()->subDays(rand(1, 180)); // Archived within last 6 months
            
            // Some users had suspension history before being archived
            $hadSuspensions = rand(0, 100) < 70; // 70% had suspensions
            $suspensionCount = $hadSuspensions ? rand(1, 5) : 0;
            
            $users[] = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'password' => $password,
                'birth_date' => $birthDate,
                'sex' => $sex,
                'is_pwd' => rand(0, 10) === 0, // 10% chance of being PWD
                'is_admin' => false,
                'account_status' => 'approved', // They were approved before being archived
                'suspension_count' => $suspensionCount,
                'is_suspended' => false, // Not currently suspended (archived instead)
                'suspension_end_date' => null,
                'is_archived' => true,
                'archive_reason' => $archiveReasons[array_rand($archiveReasons)],
                'archived_at' => $archivedAt,
                'id_image_path' => null,
                'rejection_reason' => null,
                'approved_at' => $approvedAt,
                'rejected_at' => null,
                'partially_rejected_at' => null,
                'partially_rejected_reason' => null,
                'resubmission_count' => 0,
                'email_verified_at' => $approvedAt,
                'remember_token' => null,
                'created_at' => Carbon::now()->subDays(rand(200, 800)),
                'updated_at' => $archivedAt,
            ];
        }
        
        // Insert archived users
        User::insert($users);
        
        $this->command->info('25 archived users created successfully!');
    }
}
