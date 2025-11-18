<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $usersettings = auth()->user();
        return view('resident.settings', compact('usersettings'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        // Validate the request data
        $request->validate([
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        // Prepare data to update
        $data = [
            'password' => $request->filled('password') ? Hash::make($request->input('password')) : $user->password,
        ];

        // Update user details
        $user->update($data);

        return redirect()->route('resident.settings.index')->with('status', 'Settings updated successfully.');
    }
}
