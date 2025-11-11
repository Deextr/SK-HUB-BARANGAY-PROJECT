<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Add closure_period_id foreign key if it doesn't exist
            if (!Schema::hasColumn('reservations', 'closure_period_id')) {
                $table->foreignId('closure_period_id')
                    ->nullable()
                    ->after('service_id')
                    ->constrained('closure_periods')
                    ->nullOnDelete();
            }

            // Optimize reference_no column size
            if (Schema::hasColumn('reservations', 'reference_no')) {
                $table->string('reference_no', 20)->change();
            }

            // Optimize units_reserved to small integer
            if (Schema::hasColumn('reservations', 'units_reserved')) {
                // First, ensure all values fit in smallint range
                DB::statement('ALTER TABLE reservations MODIFY COLUMN units_reserved SMALLINT UNSIGNED DEFAULT 1');
            }

            // Add actual_time_in if it doesn't exist
            if (!Schema::hasColumn('reservations', 'actual_time_in')) {
                $table->time('actual_time_in')->nullable()->after('end_time');
            }

            // Add actual_time_out if it doesn't exist
            if (!Schema::hasColumn('reservations', 'actual_time_out')) {
                $table->time('actual_time_out')->nullable()->after('actual_time_in');
            }
        });

        // Add indexes (Laravel will handle if they already exist)
        try {
            Schema::table('reservations', function (Blueprint $table) {
                // Index on user_id and reservation_date
                $table->index(['user_id', 'reservation_date'], 'reservations_user_id_reservation_date_index');
            });
        } catch (\Exception $e) {
            // Index might already exist, ignore
        }

        try {
            Schema::table('reservations', function (Blueprint $table) {
                // Index on status
                $table->index('status');
            });
        } catch (\Exception $e) {
            // Index might already exist, ignore
        }

        if (Schema::hasColumn('reservations', 'closure_period_id')) {
            try {
                Schema::table('reservations', function (Blueprint $table) {
                    // Index on closure_period_id
                    $table->index('closure_period_id');
                });
            } catch (\Exception $e) {
                // Index might already exist, ignore
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Drop closure_period_id foreign key
            if (Schema::hasColumn('reservations', 'closure_period_id')) {
                $table->dropForeign(['closure_period_id']);
                $table->dropColumn('closure_period_id');
            }

            // Revert reference_no to default size
            if (Schema::hasColumn('reservations', 'reference_no')) {
                $table->string('reference_no')->change();
            }

            // Revert units_reserved to integer
            if (Schema::hasColumn('reservations', 'units_reserved')) {
                DB::statement('ALTER TABLE reservations MODIFY COLUMN units_reserved INT UNSIGNED DEFAULT 1');
            }

            // Drop actual time columns
            if (Schema::hasColumn('reservations', 'actual_time_out')) {
                $table->dropColumn('actual_time_out');
            }
            if (Schema::hasColumn('reservations', 'actual_time_in')) {
                $table->dropColumn('actual_time_in');
            }
        });

        // Drop indexes (ignore if they don't exist)
        try {
            Schema::table('reservations', function (Blueprint $table) {
                $table->dropIndex('reservations_user_id_reservation_date_index');
            });
        } catch (\Exception $e) {
            // Index might not exist, ignore
        }

        try {
            Schema::table('reservations', function (Blueprint $table) {
                $table->dropIndex('reservations_status_index');
            });
        } catch (\Exception $e) {
            // Index might not exist, ignore
        }

        try {
            Schema::table('reservations', function (Blueprint $table) {
                $table->dropIndex('reservations_closure_period_id_index');
            });
        } catch (\Exception $e) {
            // Index might not exist, ignore
        }
    }
};
