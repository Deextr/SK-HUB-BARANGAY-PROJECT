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
            $table->unsignedInteger('suspension_count')->default(0)->after('account_status');
            $table->boolean('is_suspended')->default(false)->after('suspension_count');
            $table->timestamp('suspension_end_date')->nullable()->after('is_suspended');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('suspension_count');
            $table->dropColumn('is_suspended');
            $table->dropColumn('suspension_end_date');
        });
    }
};
