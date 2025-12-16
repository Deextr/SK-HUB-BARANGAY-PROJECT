<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ResidentAccountsSeeder extends Seeder
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
            'Gabriel', 'Victoria', 'Daniel', 'Soledad', 'Oscar', 'Amparo', 'Raul', 'Consuelo', 'Cesar', 'Purificacion',
            'Mario', 'Encarnacion', 'Pablo', 'Asuncion', 'Alfredo', 'Trinidad', 'Ernesto', 'Paz', 'Guillermo', 'Fe',
            'Hector', 'Caridad', 'Armando', 'Gracia', 'Ruben', 'Natividad', 'Salvador', 'Presentacion', 'Rodrigo', 'Visitacion',
            'Gerardo', 'Perpetua', 'Marcos', 'Salvacion', 'Leonardo', 'Resurreccion', 'Emilio', 'Consolacion', 'Julio', 'Adoracion',
            'Domingo', 'Encarnita', 'Agustin', 'Rosalinda', 'Tomas', 'Angelita', 'Vicente', 'Teresita', 'Esteban', 'Carmencita'
        ];
        
        // Filipino last names
        $lastNames = [
            'Santos', 'Reyes', 'Cruz', 'Bautista', 'Ocampo', 'Garcia', 'Mendoza', 'Torres', 'Gonzales', 'Lopez',
            'Flores', 'Rivera', 'Ramos', 'Castillo', 'Aquino', 'Villanueva', 'Santiago', 'Fernandez', 'Dela Cruz', 'Manalo',
            'Pascual', 'Mercado', 'Aguilar', 'Salazar', 'Navarro', 'Morales', 'Castro', 'Diaz', 'Perez', 'Hernandez',
            'Valdez', 'Alvarez', 'Gutierrez', 'Jimenez', 'Romero', 'Soriano', 'Velasco', 'Medina', 'Domingo', 'Espiritu',
            'Marquez', 'Vargas', 'Cortez', 'Rojas', 'Solis', 'Ortega', 'Guerrero', 'Cabrera', 'Nunez', 'Alonzo',
            'Ramirez', 'Chavez', 'Herrera', 'Padilla', 'Luna', 'Estrada', 'Rosales', 'Sandoval', 'Fuentes', 'Campos',
            'Delgado', 'Carrillo', 'Vega', 'Guzman', 'Contreras', 'Mejia', 'Ruiz', 'Pena', 'Suarez', 'Calderon',
            'Zamora', 'Ibarra', 'Cordero', 'Miranda', 'Maldonado', 'Figueroa', 'Leon', 'Galvez', 'Cervantes', 'Rios',
            'Molina', 'Velasquez', 'Pacheco', 'Montes', 'Lim', 'Tan', 'Sy', 'Go', 'Ong', 'Chua',
            'Lee', 'Chan', 'Wong', 'Ng', 'Yu', 'Co', 'Yap', 'Lao', 'Tiu', 'Ang'
        ];
        
        $sexOptions = ['Male', 'Female'];
        $users = [];
        
        // Generate 100 users
        for ($i = 0; $i < 100; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $sex = $sexOptions[array_rand($sexOptions)];
            
            // Generate birth date (12-24 years old - students)
            $birthDate = Carbon::now()->subYears(rand(12, 24))->subDays(rand(0, 365));
            
            // Generate email
            $email = strtolower($firstName . '.' . $lastName . rand(1, 999) . '@example.com');
            
            // Determine account status
            // 30 pending, 40 approved, 30 rejected
            if ($i < 30) {
                $accountStatus = 'pending';
                $approvedAt = null;
                $rejectedAt = null;
                $rejectionReason = null;
            } elseif ($i < 70) {
                $accountStatus = 'approved';
                $approvedAt = Carbon::now()->subDays(rand(1, 365));
                $rejectedAt = null;
                $rejectionReason = null;
            } else {
                $accountStatus = 'rejected';
                $approvedAt = null;
                $rejectedAt = Carbon::now()->subDays(rand(1, 30));
                $rejectionReasons = [
                    'Invalid ID document provided.',
                    'ID photo is unclear or blurry.',
                    'Information does not match ID.',
                    'Incomplete registration information.',
                    'Duplicate account detected.',
                ];
                $rejectionReason = $rejectionReasons[array_rand($rejectionReasons)];
            }
            
            $users[] = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'password' => $password,
                'birth_date' => $birthDate,
                'sex' => $sex,
                'is_pwd' => rand(0, 10) === 0, // 10% chance of being PWD
                'is_admin' => false,
                'account_status' => $accountStatus,
                'suspension_count' => 0,
                'is_suspended' => false,
                'suspension_end_date' => null,
                'is_archived' => false,
                'archive_reason' => null,
                'archived_at' => null,
                'id_image_path' => 'images/id/test_id.jpg',
                'rejection_reason' => $rejectionReason,
                'approved_at' => $approvedAt,
                'rejected_at' => $rejectedAt,
                'partially_rejected_at' => null,
                'partially_rejected_reason' => null,
                'resubmission_count' => 0,
                'created_at' => Carbon::now()->subDays(rand(1, 365)),
                'updated_at' => Carbon::now()->subDays(rand(0, 30)),
            ];
        }
        
        // Insert all users
        User::insert($users);
        
        $this->command->info('100 resident accounts seeded successfully!');
        $this->command->info('- 30 pending accounts');
        $this->command->info('- 40 approved accounts');
        $this->command->info('- 30 rejected accounts');
        $this->command->info('Password for all accounts: 123123123');
    }
}
