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
            $table->string('cancellation_reason')->nullable()->after('status');
            $table->boolean('suspension_applied')->default(false)->after('cancellation_reason');
            $table->timestamp('cancelled_at')->nullable()->after('suspension_applied');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('cancellation_reason');
            $table->dropColumn('suspension_applied');
            $table->dropColumn('cancelled_at');
        });
    }
};
