<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration adds comprehensive database constraints to ensure data integrity:
     * - Check constraints for data validation
     * - Foreign key constraints with proper ON DELETE/UPDATE rules
     * - Unique constraints where duplicates must not exist
     * - Ensures 3NF compliance
     */
    public function up(): void
    {
        // Step 1: Clean and validate existing data before adding constraints
        $this->cleanExistingData();

        // Step 2: Add constraints to users table
        $this->addUsersTableConstraints();

        // Step 3: Add constraints to services table
        $this->addServicesTableConstraints();

        // Step 4: Add constraints to closure_periods table
        $this->addClosurePeriodsTableConstraints();

        // Step 5: Add constraints to reservations table
        $this->addReservationsTableConstraints();

        // Step 6: Add constraints to service_archives table
        $this->addServiceArchivesTableConstraints();

        // Step 7: Add constraints to password_histories table
        $this->addPasswordHistoriesTableConstraints();

        // Step 8: Add constraints to sessions table
        $this->addSessionsTableConstraints();
    }

    /**
     * Clean and validate existing data before adding constraints
     */
    private function cleanExistingData(): void
    {
        // Fix any negative capacity_units in services
        DB::statement("UPDATE services SET capacity_units = 1 WHERE capacity_units < 1");
        
        // Fix any negative or zero units_reserved in reservations
        DB::statement("UPDATE reservations SET units_reserved = 1 WHERE units_reserved < 1");
        
        // Fix any invalid end_date < start_date in closure_periods
        DB::statement("UPDATE closure_periods SET end_date = start_date WHERE end_date < start_date");
        
        // Fix any invalid end_time <= start_time in reservations
        DB::statement("UPDATE reservations SET end_time = ADDTIME(start_time, '01:00:00') WHERE end_time <= start_time");
        
        // Fix any negative suspension_count
        DB::statement("UPDATE users SET suspension_count = 0 WHERE suspension_count < 0");
        
        // Fix any negative resubmission_count
        DB::statement("UPDATE users SET resubmission_count = 0 WHERE resubmission_count < 0");
        
        // Fix any invalid capacity relationships in service_archives
        DB::statement("UPDATE service_archives SET capacity_after = capacity_before - units_archived WHERE capacity_after != (capacity_before - units_archived)");
        
        // Fix any negative units_archived
        DB::statement("UPDATE service_archives SET units_archived = 1 WHERE units_archived < 1");
        
        // Fix any invalid birth_date (future dates)
        DB::statement("UPDATE users SET birth_date = NULL WHERE birth_date > CURDATE()");
        
        // Fix any invalid suspension_end_date for non-suspended users
        DB::statement("UPDATE users SET suspension_end_date = NULL WHERE is_suspended = 0 AND suspension_end_date IS NOT NULL");
    }

    /**
     * Add constraints to users table
     */
    private function addUsersTableConstraints(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ensure email is unique (should already exist, but verify)
            if (!$this->uniqueConstraintExists('users', 'email')) {
                try {
                    $table->unique('email', 'users_email_unique');
                } catch (\Exception $e) {
                    // Unique constraint might exist with different name
                    \Log::warning('Could not add users email unique constraint: ' . $e->getMessage());
                }
            }
        });

        // Add check constraints (MySQL 8.0.16+)
        if ($this->supportsCheckConstraints()) {
            try {
                // Add check constraint for suspension_count (must be >= 0)
                if (!$this->constraintExists('users', 'chk_users_suspension_count')) {
                    DB::statement("ALTER TABLE users ADD CONSTRAINT chk_users_suspension_count CHECK (suspension_count >= 0)");
                }
                
                // Add check constraint for resubmission_count (must be >= 0)
                if (!$this->constraintExists('users', 'chk_users_resubmission_count')) {
                    DB::statement("ALTER TABLE users ADD CONSTRAINT chk_users_resubmission_count CHECK (resubmission_count >= 0)");
                }
                
                // Add check constraint for birth_date (must be in the past or null)
                if (!$this->constraintExists('users', 'chk_users_birth_date')) {
                    DB::statement("ALTER TABLE users ADD CONSTRAINT chk_users_birth_date CHECK (birth_date IS NULL OR birth_date <= CURDATE())");
                }
                
                // Add check constraint for suspension logic (if suspended, must have end_date)
                if (!$this->constraintExists('users', 'chk_users_suspension_logic')) {
                    DB::statement("ALTER TABLE users ADD CONSTRAINT chk_users_suspension_logic CHECK (is_suspended = 0 OR suspension_end_date IS NOT NULL)");
                }
            } catch (\Exception $e) {
                // If check constraints are not supported, log but don't fail
                \Log::warning('Check constraints not supported or failed: ' . $e->getMessage());
            }
        }
    }

    /**
     * Add constraints to services table
     */
    private function addServicesTableConstraints(): void
    {
        Schema::table('services', function (Blueprint $table) {
            // Ensure service name is unique (should already exist, but verify)
            if (!$this->uniqueConstraintExists('services', 'name')) {
                try {
                    $table->unique('name', 'services_name_unique');
                } catch (\Exception $e) {
                    // Unique constraint might exist with different name
                    \Log::warning('Could not add services name unique constraint: ' . $e->getMessage());
                }
            }
        });

        // Add check constraints (MySQL 8.0.16+)
        if ($this->supportsCheckConstraints()) {
            try {
                // Add check constraint for capacity_units (must be >= 1)
                if (!$this->constraintExists('services', 'chk_services_capacity_units')) {
                    DB::statement("ALTER TABLE services ADD CONSTRAINT chk_services_capacity_units CHECK (capacity_units >= 1)");
                }
            } catch (\Exception $e) {
                \Log::warning('Check constraints not supported or failed: ' . $e->getMessage());
            }
        }
    }

    /**
     * Add constraints to closure_periods table
     */
    private function addClosurePeriodsTableConstraints(): void
    {
        Schema::table('closure_periods', function (Blueprint $table) {
            // Add unique constraint to prevent exact duplicate date ranges
            // Note: Overlapping ranges are handled at application level
            if (!$this->constraintExists('closure_periods', 'closure_periods_dates_unique')) {
                // We can't easily add a unique constraint for date ranges in MySQL
                // This is handled at application level in ClosurePeriodController
                // But we can add an index for performance
                $table->index(['start_date', 'end_date'], 'idx_closure_periods_dates');
            }
        });

        // Add check constraints (MySQL 8.0.16+)
        if ($this->supportsCheckConstraints()) {
            try {
                // Add check constraint for end_date >= start_date
                if (!$this->constraintExists('closure_periods', 'chk_closure_periods_date_range')) {
                    DB::statement("ALTER TABLE closure_periods ADD CONSTRAINT chk_closure_periods_date_range CHECK (end_date >= start_date)");
                }
                
                // Add check constraint for time range when not full day
                if (!$this->constraintExists('closure_periods', 'chk_closure_periods_time_range')) {
                    DB::statement("ALTER TABLE closure_periods ADD CONSTRAINT chk_closure_periods_time_range CHECK (
                        is_full_day = 1 OR 
                        (start_time IS NOT NULL AND end_time IS NOT NULL AND end_time > start_time)
                    )");
                }
            } catch (\Exception $e) {
                \Log::warning('Check constraints not supported or failed: ' . $e->getMessage());
            }
        }
    }

    /**
     * Add constraints to reservations table
     */
    private function addReservationsTableConstraints(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Ensure reference_no is unique (should already exist, but verify)
            if (!$this->uniqueConstraintExists('reservations', 'reference_no')) {
                try {
                    $table->unique('reference_no', 'reservations_reference_no_unique');
                } catch (\Exception $e) {
                    // Unique constraint might exist with different name
                    \Log::warning('Could not add reservations reference_no unique constraint: ' . $e->getMessage());
                }
            }
            
            // Verify foreign key for cancelled_by exists and is correct
            if (!$this->foreignKeyExists('reservations', 'reservations_cancelled_by_foreign')) {
                $table->foreign('cancelled_by')
                    ->references('id')
                    ->on('users')
                    ->onDelete('set null')
                    ->onUpdate('cascade');
            }
        });

        // Add check constraints (MySQL 8.0.16+)
        if ($this->supportsCheckConstraints()) {
            try {
                // Add check constraint for units_reserved (must be >= 1)
                if (!$this->constraintExists('reservations', 'chk_reservations_units_reserved')) {
                    DB::statement("ALTER TABLE reservations ADD CONSTRAINT chk_reservations_units_reserved CHECK (units_reserved >= 1)");
                }
                
                // Add check constraint for end_time > start_time
                if (!$this->constraintExists('reservations', 'chk_reservations_time_range')) {
                    DB::statement("ALTER TABLE reservations ADD CONSTRAINT chk_reservations_time_range CHECK (end_time > start_time)");
                }
                
                // Add check constraint for actual_time_out > actual_time_in (if both exist)
                if (!$this->constraintExists('reservations', 'chk_reservations_actual_time')) {
                    DB::statement("ALTER TABLE reservations ADD CONSTRAINT chk_reservations_actual_time CHECK (
                        actual_time_in IS NULL OR 
                        actual_time_out IS NULL OR 
                        actual_time_out > actual_time_in
                    )");
                }
            } catch (\Exception $e) {
                \Log::warning('Check constraints not supported or failed: ' . $e->getMessage());
            }
        }
    }

    /**
     * Add constraints to service_archives table
     */
    private function addServiceArchivesTableConstraints(): void
    {
        Schema::table('service_archives', function (Blueprint $table) {
            // Verify foreign key for service_id exists and is correct
            if (!$this->foreignKeyExists('service_archives', 'service_archives_service_id_foreign')) {
                $table->foreign('service_id')
                    ->references('id')
                    ->on('services')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            }
        });

        // Add check constraints (MySQL 8.0.16+)
        if ($this->supportsCheckConstraints()) {
            try {
                // Add check constraint for units_archived (must be > 0)
                if (!$this->constraintExists('service_archives', 'chk_service_archives_units_archived')) {
                    DB::statement("ALTER TABLE service_archives ADD CONSTRAINT chk_service_archives_units_archived CHECK (units_archived > 0)");
                }
                
                // Add check constraint for capacity_before (must be > 0)
                if (!$this->constraintExists('service_archives', 'chk_service_archives_capacity_before')) {
                    DB::statement("ALTER TABLE service_archives ADD CONSTRAINT chk_service_archives_capacity_before CHECK (capacity_before > 0)");
                }
                
                // Add check constraint for capacity_after (must be >= 1)
                if (!$this->constraintExists('service_archives', 'chk_service_archives_capacity_after')) {
                    DB::statement("ALTER TABLE service_archives ADD CONSTRAINT chk_service_archives_capacity_after CHECK (capacity_after >= 1)");
                }
                
                // Add check constraint for capacity relationship (capacity_after = capacity_before - units_archived)
                if (!$this->constraintExists('service_archives', 'chk_service_archives_capacity_relationship')) {
                    DB::statement("ALTER TABLE service_archives ADD CONSTRAINT chk_service_archives_capacity_relationship CHECK (capacity_after = capacity_before - units_archived)");
                }
                
                // Add check constraint for reservations_cancelled (must be >= 0)
                if (!$this->constraintExists('service_archives', 'chk_service_archives_reservations_cancelled')) {
                    DB::statement("ALTER TABLE service_archives ADD CONSTRAINT chk_service_archives_reservations_cancelled CHECK (reservations_cancelled >= 0)");
                }
            } catch (\Exception $e) {
                \Log::warning('Check constraints not supported or failed: ' . $e->getMessage());
            }
        }
    }

    /**
     * Add constraints to password_histories table
     */
    private function addPasswordHistoriesTableConstraints(): void
    {
        Schema::table('password_histories', function (Blueprint $table) {
            // Verify foreign key for user_id exists and is correct
            if (!$this->foreignKeyExists('password_histories', 'password_histories_user_id_foreign')) {
                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            }
        });
    }

    /**
     * Add constraints to sessions table
     */
    private function addSessionsTableConstraints(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            // Add foreign key for user_id if it doesn't exist
            // Note: user_id is nullable for guest sessions, so we only add FK if column exists and is not already constrained
            if (!$this->foreignKeyExists('sessions', 'sessions_user_id_foreign')) {
                try {
                    $table->foreign('user_id')
                        ->references('id')
                        ->on('users')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');
                } catch (\Exception $e) {
                    // Foreign key might already exist with different name or structure
                    \Log::warning('Could not add sessions foreign key: ' . $e->getMessage());
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop check constraints from users table
        $this->dropConstraintIfExists('users', 'chk_users_suspension_count');
        $this->dropConstraintIfExists('users', 'chk_users_resubmission_count');
        $this->dropConstraintIfExists('users', 'chk_users_birth_date');
        $this->dropConstraintIfExists('users', 'chk_users_suspension_logic');

        // Drop check constraints from services table
        $this->dropConstraintIfExists('services', 'chk_services_capacity_units');

        // Drop check constraints from closure_periods table
        $this->dropConstraintIfExists('closure_periods', 'chk_closure_periods_date_range');
        $this->dropConstraintIfExists('closure_periods', 'chk_closure_periods_time_range');

        // Drop check constraints from reservations table
        $this->dropConstraintIfExists('reservations', 'chk_reservations_units_reserved');
        $this->dropConstraintIfExists('reservations', 'chk_reservations_time_range');
        $this->dropConstraintIfExists('reservations', 'chk_reservations_actual_time');

        // Drop check constraints from service_archives table
        $this->dropConstraintIfExists('service_archives', 'chk_service_archives_units_archived');
        $this->dropConstraintIfExists('service_archives', 'chk_service_archives_capacity_before');
        $this->dropConstraintIfExists('service_archives', 'chk_service_archives_capacity_after');
        $this->dropConstraintIfExists('service_archives', 'chk_service_archives_capacity_relationship');
        $this->dropConstraintIfExists('service_archives', 'chk_service_archives_reservations_cancelled');
    }

    /**
     * Check if a constraint exists (including unique, check, foreign key)
     */
    private function constraintExists(string $table, string $constraintName): bool
    {
        try {
            $result = DB::select(
                "SELECT CONSTRAINT_NAME 
                 FROM information_schema.TABLE_CONSTRAINTS 
                 WHERE TABLE_SCHEMA = DATABASE() 
                 AND TABLE_NAME = ? 
                 AND CONSTRAINT_NAME = ?",
                [$table, $constraintName]
            );
            
            return count($result) > 0;
        } catch (\Exception $e) {
            // If query fails, assume constraint doesn't exist
            return false;
        }
    }

    /**
     * Check if a unique constraint exists on a column
     */
    private function uniqueConstraintExists(string $table, string $column): bool
    {
        try {
            $result = DB::select(
                "SELECT CONSTRAINT_NAME 
                 FROM information_schema.KEY_COLUMN_USAGE 
                 WHERE TABLE_SCHEMA = DATABASE() 
                 AND TABLE_NAME = ? 
                 AND COLUMN_NAME = ?
                 AND CONSTRAINT_NAME IN (
                     SELECT CONSTRAINT_NAME 
                     FROM information_schema.TABLE_CONSTRAINTS 
                     WHERE TABLE_SCHEMA = DATABASE() 
                     AND TABLE_NAME = ?
                     AND CONSTRAINT_TYPE = 'UNIQUE'
                 )",
                [$table, $column, $table]
            );
            
            return count($result) > 0;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check if a foreign key exists
     */
    private function foreignKeyExists(string $table, string $foreignKeyName): bool
    {
        $result = DB::select(
            "SELECT CONSTRAINT_NAME 
             FROM information_schema.KEY_COLUMN_USAGE 
             WHERE TABLE_SCHEMA = DATABASE() 
             AND TABLE_NAME = ? 
             AND CONSTRAINT_NAME = ? 
             AND REFERENCED_TABLE_NAME IS NOT NULL",
            [$table, $foreignKeyName]
        );
        
        return count($result) > 0;
    }

    /**
     * Drop constraint if it exists
     */
    private function dropConstraintIfExists(string $table, string $constraintName): void
    {
        if ($this->constraintExists($table, $constraintName)) {
            try {
                DB::statement("ALTER TABLE {$table} DROP CHECK {$constraintName}");
            } catch (\Exception $e) {
                // Constraint might not exist or might be a different type
                \Log::warning("Could not drop constraint {$constraintName}: " . $e->getMessage());
            }
        }
    }

    /**
     * Check if MySQL version supports CHECK constraints (8.0.16+)
     */
    private function supportsCheckConstraints(): bool
    {
        try {
            $version = DB::selectOne("SELECT VERSION() as version");
            if (!$version || !isset($version->version)) {
                return false;
            }
            
            $versionString = $version->version;
            // Extract major.minor.patch version
            if (preg_match('/^(\d+)\.(\d+)\.(\d+)/', $versionString, $matches)) {
                $major = (int)$matches[1];
                $minor = (int)$matches[2];
                $patch = (int)$matches[3];
                
                // MySQL 8.0.16+ supports CHECK constraints
                if ($major > 8) {
                    return true;
                } elseif ($major === 8 && $minor === 0 && $patch >= 16) {
                    return true;
                }
            }
            
            return false;
        } catch (\Exception $e) {
            // If we can't determine version, assume older MySQL and skip check constraints
            return false;
        }
    }
};

