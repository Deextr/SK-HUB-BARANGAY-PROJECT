<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing admin accounts to have the required fields
        $adminUsers = User::where('is_admin', true)->get();
        
        foreach ($adminUsers as $user) {
            // If first_name is null, set it based on the name field
            if (is_null($user->first_name)) {
                $nameParts = explode(' ', $user->name);
                $user->first_name = $nameParts[0] ?? 'Admin';
                $user->last_name = isset($nameParts[1]) ? implode(' ', array_slice($nameParts, 1)) : 'User';
            }
            
            // If account_status is null, set it to approved for admin accounts
            if (is_null($user->account_status)) {
                $user->account_status = 'approved';
            }
            
            $user->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this migration
    }
};
