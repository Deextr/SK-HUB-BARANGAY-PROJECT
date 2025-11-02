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
            if (!Schema::hasColumn('reservations', 'user_id')) {
                $table->foreignId('user_id')->after('id')->constrained()->cascadeOnDelete();
            }
            if (!Schema::hasColumn('reservations', 'service_id')) {
                $table->foreignId('service_id')->after('user_id')->constrained('services');
            }
            if (!Schema::hasColumn('reservations', 'reference_no')) {
                $table->string('reference_no')->unique()->after('service_id');
            }
            if (!Schema::hasColumn('reservations', 'reservation_date')) {
                $table->date('reservation_date')->after('reference_no');
            }
            if (!Schema::hasColumn('reservations', 'start_time')) {
                $table->time('start_time')->nullable()->after('reservation_date');
            }
            if (!Schema::hasColumn('reservations', 'end_time')) {
                $table->time('end_time')->nullable()->after('start_time');
            }
            if (!Schema::hasColumn('reservations', 'units_reserved')) {
                $table->unsignedInteger('units_reserved')->default(1)->after('end_time');
            }
            if (!Schema::hasColumn('reservations', 'status')) {
                $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending')->after('units_reserved');
            }
            if (!Schema::hasColumn('reservations', 'preferences')) {
                $table->text('preferences')->nullable()->after('status');
            }

            if (!Schema::hasColumn('reservations', 'reservation_date')) {
                $table->index(['reservation_date', 'service_id']);
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('reservations')) {
            return;
        }

        Schema::table('reservations', function (Blueprint $table) {
            // No-op: keeping columns as part of schema evolution
        });
    }
};


