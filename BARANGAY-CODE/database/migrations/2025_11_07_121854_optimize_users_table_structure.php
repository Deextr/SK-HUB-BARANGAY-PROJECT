<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove redundant 'name' column if it exists (3NF compliance)
            if (Schema::hasColumn('users', 'name')) {
                $table->dropColumn('name');
            }

            // Add first_name if it doesn't exist
            if (!Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name', 50)->nullable()->after('id');
            } else {
                // Modify existing first_name to proper size
                $table->string('first_name', 50)->change();
            }

            // Add last_name if it doesn't exist
            if (!Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name', 50)->nullable()->after('first_name');
            } else {
                // Modify existing last_name to proper size
                $table->string('last_name', 50)->change();
            }

            // Optimize email column size
            if (Schema::hasColumn('users', 'email')) {
                $table->string('email', 100)->change();
            }

            // Add account_status if it doesn't exist
            if (!Schema::hasColumn('users', 'account_status')) {
                $table->enum('account_status', ['pending', 'approved', 'rejected'])->default('pending')->after('is_admin');
            }

            // Add other profiling fields if they don't exist
            if (!Schema::hasColumn('users', 'id_image_path')) {
                $table->string('id_image_path', 255)->nullable()->after('account_status');
            }

            if (!Schema::hasColumn('users', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('id_image_path');
            }

            if (!Schema::hasColumn('users', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('rejection_reason');
            }

            if (!Schema::hasColumn('users', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('approved_at');
            }
        });

        // Add index on account_status (Laravel will handle if it already exists)
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->index('account_status');
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
        Schema::table('users', function (Blueprint $table) {
            // Restore name column if needed
            if (!Schema::hasColumn('users', 'name')) {
                $table->string('name')->nullable()->after('id');
            }

            // Revert email to default size
            if (Schema::hasColumn('users', 'email')) {
                $table->string('email')->change();
            }

            // Drop index (ignore if it doesn't exist)
            try {
                $table->dropIndex('users_account_status_index');
            } catch (\Exception $e) {
                // Index might not exist, ignore
            }
        });
    }
};
