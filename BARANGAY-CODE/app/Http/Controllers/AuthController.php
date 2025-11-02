<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class AuthController extends Controller
{
    // Show registration form
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Handle registration
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'id_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file upload
        $idImagePath = null;
        if ($request->hasFile('id_image')) {
            $idImagePath = $request->file('id_image')->store('id_images', 'public');
        }

        // Create user with pending status
        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'account_status' => 'pending',
            'id_image_path' => $idImagePath,
            'is_admin' => false,
        ]);

        return redirect()->route('account.pending')
            ->with('success', 'Account created successfully! Please wait for admin approval.');
    }

    // Show login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Check account status
            if ($user->isRejected()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account has been rejected. Reason: ' . $user->rejection_reason,
                ]);
            }

            if ($user->isPending()) {
                Auth::logout();
                return redirect()->route('account.pending')
                    ->with('info', 'Your account is still pending approval. Please wait for admin verification.');
            }

            // ✅ Redirect based on role
            if ($user->is_admin) {
                return redirect()->route('dashboard')->with('success', 'Welcome Admin!');
            } elseif ($user->isApproved()) {
                return redirect()->route('resident.dashboard')->with('success', 'Welcome Resident!');
            }

            // Fallback if role missing
            return redirect()->route('login.form')->withErrors([
                'email' => 'Role not recognized.',
            ]);
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.form');
    }

    // Show pending account page
    public function showPendingPage()
    {
        return view('auth.pending');
    }
}
