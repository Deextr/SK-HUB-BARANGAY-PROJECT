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
        Schema::table('users', function (Blueprint $table) {
            // Modify account_status enum to include 'partially_rejected'
            $table->enum('account_status', ['pending', 'approved', 'partially_rejected', 'rejected'])
                  ->change();

            // Add partially_rejected_at timestamp
            if (!Schema::hasColumn('users', 'partially_rejected_at')) {
                $table->timestamp('partially_rejected_at')->nullable()->after('rejected_at');
            }

            // Add partially_rejected_reason text
            if (!Schema::hasColumn('users', 'partially_rejected_reason')) {
                $table->text('partially_rejected_reason')->nullable()->after('partially_rejected_at');
            }

            // Add resubmission_count for audit trail
            if (!Schema::hasColumn('users', 'resubmission_count')) {
                $table->integer('resubmission_count')->default(0)->after('partially_rejected_reason');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Revert account_status enum to original values
            $table->enum('account_status', ['pending', 'approved', 'rejected'])
                  ->change();

            // Drop new columns
            if (Schema::hasColumn('users', 'partially_rejected_at')) {
                $table->dropColumn('partially_rejected_at');
            }

            if (Schema::hasColumn('users', 'partially_rejected_reason')) {
                $table->dropColumn('partially_rejected_reason');
            }

            if (Schema::hasColumn('users', 'resubmission_count')) {
                $table->dropColumn('resubmission_count');
            }
        });
    }
};
