<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('closure_periods', function (Blueprint $table) {
            $table->id();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('reason', 200)->nullable();
            $table->boolean('is_full_day')->default(true);
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->enum('status', ['pending', 'active'])->default('pending');
            $table->softDeletes();
            $table->timestamps();
            
            $table->index(['start_date', 'end_date']);
            $table->index('status');
            $table->index('is_full_day');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('closure_periods');
    }
};

