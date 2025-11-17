<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('closure_periods', 'is_full_day')) {
            Schema::table('closure_periods', function (Blueprint $table) {
                try {
                    $table->dropIndex('closure_periods_is_full_day_index');
                } catch (\Throwable $e) {
                    // Index might not exist; ignore.
                }
            });
        }

        Schema::table('closure_periods', function (Blueprint $table) {
            if (Schema::hasColumn('closure_periods', 'start_time')) {
                $table->dropColumn('start_time');
            }
            if (Schema::hasColumn('closure_periods', 'end_time')) {
                $table->dropColumn('end_time');
            }
            if (Schema::hasColumn('closure_periods', 'is_full_day')) {
                $table->dropColumn('is_full_day');
            }
        });
    }

    public function down(): void
    {
        Schema::table('closure_periods', function (Blueprint $table) {
            if (!Schema::hasColumn('closure_periods', 'is_full_day')) {
                $table->boolean('is_full_day')->default(true)->after('reason');
            }
            if (!Schema::hasColumn('closure_periods', 'start_time')) {
                $table->time('start_time')->nullable()->after('is_full_day');
            }
            if (!Schema::hasColumn('closure_periods', 'end_time')) {
                $table->time('end_time')->nullable()->after('start_time');
            }
        });

        Schema::table('closure_periods', function (Blueprint $table) {
            if (Schema::hasColumn('closure_periods', 'is_full_day')) {
                $table->index('is_full_day');
            }
        });
    }
};

