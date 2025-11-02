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
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('username')->unique()->nullable()->after('last_name');
            $table->string('gmail')->nullable()->after('username');
            $table->enum('account_status', ['pending', 'approved', 'rejected'])->default('pending')->after('is_admin');
            $table->string('id_image_path')->nullable()->after('account_status');
            $table->text('rejection_reason')->nullable()->after('id_image_path');
            $table->timestamp('approved_at')->nullable()->after('rejection_reason');
            $table->timestamp('rejected_at')->nullable()->after('approved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'last_name', 
                'username',
                'gmail',
                'account_status',
                'id_image_path',
                'rejection_reason',
                'approved_at',
                'rejected_at'
            ]);
        });
    }
};
