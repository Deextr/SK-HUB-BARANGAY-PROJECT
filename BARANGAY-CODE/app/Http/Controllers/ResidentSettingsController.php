<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
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
  /**
     * Update profile picture
     */
    public function updateProfilePicture(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ], [
            'profile_picture.required' => 'Please select an image file',
            'profile_picture.image' => 'The file must be an image',
            'profile_picture.mimes' => 'Only JPG, PNG, and GIF files are allowed',
            'profile_picture.max' => 'File size must not exceed 5MB',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();
        
        // Delete old profile picture if exists
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        // Store new profile picture
        $file = $request->file('profile_picture');
        $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        
        // Store in storage following the same pattern as id_images
        $path = $file->storeAs('profile_images', $filename, 'public');
        
        // Note: Image resizing functionality removed - requires intervention/image package
        // Profile pictures will be stored in original size for now

        // Update user record
        $user->profile_picture = $path;
        $user->save();

        return redirect()->back()
            ->with('success', 'Profile picture updated successfully.');
    }

    /**
     * Remove profile picture
     */
    public function removeProfilePicture(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->profile_picture) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No profile picture to remove'
                ], 400);
            }
            return redirect()->back()
                ->with('error', 'No profile picture to remove.');
        }

        // Delete the file from storage
        Storage::disk('public')->delete($user->profile_picture);
        
        // Update user record
        $user->profile_picture = null;
        $user->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile picture removed successfully'
            ]);
        }

        return redirect()->back()
            ->with('success', 'Profile picture removed successfully.');
    }
}