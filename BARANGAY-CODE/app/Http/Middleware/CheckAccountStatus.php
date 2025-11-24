<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only check for authenticated residents (non-admins)
        if (auth()->check() && !auth()->user()->is_admin) {
            $user = auth()->user();

            // If partially rejected, redirect to correction page
            if ($user->isPartiallyRejected()) {
                return redirect()->route('resident.correction')
                    ->with('warning', 'Your account requires corrections. Please update your information.');
            }

            // If rejected, logout and show error
            if ($user->isRejected()) {
                auth()->logout();
                return redirect()->route('login.form')
                    ->withErrors(['email' => 'Your account has been rejected.']);
            }

            // If pending, logout and show error
            if ($user->isPending()) {
                auth()->logout();
                return redirect()->route('account.pending')
                    ->with('info', 'Your account is still pending approval.');
            }

            // If archived, logout and show error
            if ($user->isArchived()) {
                auth()->logout();
                return redirect()->route('login.form')
                    ->withErrors(['email' => 'Your account has been archived.']);
            }
        }

        return $next($request);
    }
}
