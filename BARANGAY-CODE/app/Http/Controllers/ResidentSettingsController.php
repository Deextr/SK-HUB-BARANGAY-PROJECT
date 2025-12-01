<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ResidentSettingsController extends Controller
{
    /**
     * Show the resident settings page
     */
    public function index()
    {
        $user = Auth::user();
        return view('resident.settings', compact('user'));
    }

    /**
     * Update the resident's password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'min:8', 'confirmed', Password::defaults()],
        ], [
            'current_password.required' => 'Current password is required.',
            'current_password.current_password' => 'The current password is incorrect.',
            'password.required' => 'New password is required.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.confirmed' => 'The passwords do not match.',
            'password.regex' => 'Password must contain uppercase, lowercase, number, and special character.',
        ]);

        $user = Auth::user();

        // Check if password has been used before
        if ($user->hasUsedPassword($request->password)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['password' => 'Please provide a stronger password that has not been used before.']);
        }

        // Record current password in history before updating
        $user->recordPasswordHistory();

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('resident.settings.index')
            ->with('success', 'Password updated successfully!');
    }
}
