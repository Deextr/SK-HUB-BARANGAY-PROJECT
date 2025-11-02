<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('reservations')) {
            return;
        }

        Schema::table('reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('reservations', 'actual_time_in')) {
                $table->time('actual_time_in')->nullable()->after('end_time');
            }
            if (!Schema::hasColumn('reservations', 'actual_time_out')) {
                $table->time('actual_time_out')->nullable()->after('actual_time_in');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('reservations')) {
            return;
        }

        Schema::table('reservations', function (Blueprint $table) {
            if (Schema::hasColumn('reservations', 'actual_time_out')) {
                $table->dropColumn('actual_time_out');
            }
            if (Schema::hasColumn('reservations', 'actual_time_in')) {
                $table->dropColumn('actual_time_in');
            }
        });
    }
};


