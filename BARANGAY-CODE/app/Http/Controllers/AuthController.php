<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use App\Models\User;

class AuthController extends Controller
{
    // Show NDA form
    public function showNdaForm()
    {
        return view('auth.nda');
    }

    // Handle NDA acceptance
    public function acceptNda(Request $request)
    {
        // Store NDA acceptance in session
        $request->session()->put('nda_accepted', true);
        $request->session()->put('nda_accepted_at', now());
        
        return redirect()->route('register.form');
    }

    // Show registration form
    public function showRegisterForm(Request $request)
    {
        // Check if NDA has been accepted
        if (!$request->session()->has('nda_accepted')) {
            return redirect()->route('register.nda')
                ->with('error', 'You must accept the Non-Disclosure Agreement before registering.');
        }
        
        return view('auth.register');
    }

    // Handle registration
    public function register(Request $request)
    {
        // Verify NDA acceptance
        if (!$request->session()->has('nda_accepted')) {
            return redirect()->route('register.nda')
                ->with('error', 'You must accept the Non-Disclosure Agreement before registering.');
        }

        $request->validate([
            'first_name' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\'\.]+$/',
            'last_name' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\'\.]+$/',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'birth_date' => 'required|date|before:today|after:' . date('Y-m-d', strtotime('-150 years')),
            'sex' => 'required|in:Male,Female',
            'is_pwd' => 'nullable|boolean',
            'id_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'first_name.regex' => 'First name can only contain letters, spaces, hyphens, apostrophes, and periods.',
            'last_name.regex' => 'Last name can only contain letters, spaces, hyphens, apostrophes, and periods.',
            'password.min' => 'Password must be at least 8 characters long.',
        ]);

        // Check for duplicate first_name + last_name combination
        $duplicateName = User::where('first_name', $request->first_name)
            ->where('last_name', $request->last_name)
            ->exists();

        if ($duplicateName) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['name' => 'A resident with this first and last name combination already exists.']);
        }

        // Handle file upload
        $idImagePath = null;
        if ($request->hasFile('id_image')) {
            $idImagePath = $request->file('id_image')->store('id_images', 'public');
        }

        // Create user with pending status
        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'birth_date' => $request->birth_date,
            'sex' => $request->sex,
            'is_pwd' => $request->filled('is_pwd') && $request->is_pwd == '1' ? 1 : 0,
            'account_status' => 'pending',
            'id_image_path' => $idImagePath,
            'is_admin' => false,
        ]);

        // Clear NDA session after successful registration
        $request->session()->forget('nda_accepted');
        $request->session()->forget('nda_accepted_at');

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
            
            // Check if account is archived
            if ($user->isArchived()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account has been archived. Reason: ' . $user->archive_reason,
                ]);
            }

            // Check account status
            if ($user->isRejected()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account has been rejected. Reason: ' . $user->rejection_reason,
                ]);
            }

            if ($user->isPartiallyRejected()) {
                // Allow login but redirect to partial rejection page
                return redirect()->route('account.partial_rejection');
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

    // Show partial rejection correction page
    public function showPartialRejectionPage()
    {
        $user = Auth::user();
        
        // Check if user is partially rejected
        if (!$user || !$user->isPartiallyRejected()) {
            return redirect()->route('resident.dashboard');
        }
        
        return view('auth.partial_rejection', [
            'correction_reason' => $user->partially_rejected_reason
        ]);
    }
    
    // Check if email exists
    public function checkEmailExists(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        
        $email = $request->input('email');
        $exists = User::where('email', $email)->exists();
        
        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'Email is already in use.' : 'Email is available.'
        ]);
    }

    // Reset registration workflow
    public function resetRegistration(Request $request)
    {
        // Clear NDA session to allow user to start fresh
        $request->session()->forget('nda_accepted');
        $request->session()->forget('nda_accepted_at');
        
        return redirect()->route('register.nda');
    }

    // Show forgot password form
    public function showForgotPasswordForm()
    {
        return view('auth.forgot_password');
    }

    // Send password reset link
    public function sendPasswordResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'No account found with this email address.',
        ]);

        // Send the password reset link using Laravel's Password facade
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'Password reset link sent to your email. Please check your inbox.')
            : back()->withErrors(['email' => __($status)]);
    }

    // Show reset password form
    public function showResetPasswordForm($token)
    {
        return view('auth.reset_password', ['token' => $token]);
    }

    // Handle password reset
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Attempt to reset the password using Laravel's Password facade
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login.form')->with('status', 'Password reset successfully! You can now log in with your new password.')
            : back()->withErrors(['email' => [__($status)]]);
    }
}
