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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->foreignId('closure_period_id')->nullable()->constrained('closure_periods')->nullOnDelete();
            $table->string('reference_no', 20)->unique();
            $table->date('reservation_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->time('actual_time_in')->nullable();
            $table->time('actual_time_out')->nullable();
            $table->unsignedSmallInteger('units_reserved')->default(1);
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->text('preferences')->nullable();
            $table->timestamps();

            $table->index(['reservation_date', 'service_id']);
            $table->index(['user_id', 'reservation_date']);
            $table->index('status');
            $table->index('closure_period_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
