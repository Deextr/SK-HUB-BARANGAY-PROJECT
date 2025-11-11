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
        Schema::table('services', function (Blueprint $table) {
            // Optimize name column size
            if (Schema::hasColumn('services', 'name')) {
                $table->string('name', 100)->change();
            }

            // Optimize capacity_units to small integer
            if (Schema::hasColumn('services', 'capacity_units')) {
                // First, ensure all values fit in smallint range (0-65535)
                DB::statement('ALTER TABLE services MODIFY COLUMN capacity_units SMALLINT UNSIGNED DEFAULT 1');
            }

            // Add soft deletes if it doesn't exist
            if (!Schema::hasColumn('services', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // Add index on is_active (Laravel will handle if it already exists)
        try {
            Schema::table('services', function (Blueprint $table) {
                $table->index('is_active');
            });
        } catch (\Exception $e) {
            // Index might already exist, ignore
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            // Revert name to default size
            if (Schema::hasColumn('services', 'name')) {
                $table->string('name')->change();
            }

            // Revert capacity_units to integer
            if (Schema::hasColumn('services', 'capacity_units')) {
                DB::statement('ALTER TABLE services MODIFY COLUMN capacity_units INT UNSIGNED DEFAULT 1');
            }

            // Drop soft deletes
            if (Schema::hasColumn('services', 'deleted_at')) {
                $table->dropSoftDeletes();
            }

            // Drop index (ignore if it doesn't exist)
            try {
                $table->dropIndex('services_is_active_index');
            } catch (\Exception $e) {
                // Index might not exist, ignore
            }
        });
    }
};
