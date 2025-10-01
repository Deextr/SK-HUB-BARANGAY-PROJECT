<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('closure_periods', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('end_time'); // pending|active
        });
    }

    public function down(): void
    {
        Schema::table('closure_periods', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};


