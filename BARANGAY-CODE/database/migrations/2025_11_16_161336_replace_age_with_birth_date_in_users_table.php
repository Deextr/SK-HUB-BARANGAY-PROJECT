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
            // Drop age column (redundant data - violates 3NF)
            if (Schema::hasColumn('users', 'age')) {
                $table->dropColumn('age');
            }
            
            // Add birth_date column (3NF compliant - belongs to resident entity)
            if (!Schema::hasColumn('users', 'birth_date')) {
                $table->date('birth_date')->nullable()->after('last_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop birth_date column
            if (Schema::hasColumn('users', 'birth_date')) {
                $table->dropColumn('birth_date');
            }
            
            // Restore age column (for rollback purposes)
            if (!Schema::hasColumn('users', 'age')) {
                $table->unsignedSmallInteger('age')->nullable()->after('last_name');
            }
        });
    }
};
