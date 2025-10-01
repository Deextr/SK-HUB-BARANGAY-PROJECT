<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ClosurePeriodController;
use App\Http\Controllers\ServiceController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsResident;
use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login.form');
});

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
    Route::post('/register', [AuthController::class, 'register'])->name('register');

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Resident Dashboard
    Route::get('/resident/dashboard', function () {
        return view('resident.dashboard'); 
    })->name('resident.dashboard');

    // ✅ Admin-only routes
    Route::middleware(IsAdmin::class)->prefix('admin')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.admin_dashboard');
        })->name('dashboard');

        Route::get('/reservations', [ReservationController::class, 'index'])
            ->name('reservation.dashboard');
            // Closure periods CRUD
            Route::get('/closure-periods', [ClosurePeriodController::class, 'index'])->name('admin.closure_periods.index');
            Route::post('/closure-periods', [ClosurePeriodController::class, 'store'])->name('admin.closure_periods.store');
            Route::put('/closure-periods/{closurePeriod}', [ClosurePeriodController::class, 'update'])->name('admin.closure_periods.update');
            Route::delete('/closure-periods/{closurePeriod}', [ClosurePeriodController::class, 'destroy'])->name('admin.closure_periods.destroy');

        // Services CRUD
        Route::get('/services', [ServiceController::class, 'index'])->name('admin.services.index');
        Route::post('/services', [ServiceController::class, 'store'])->name('admin.services.store');
        Route::put('/services/{service}', [ServiceController::class, 'update'])->name('admin.services.update');
        Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('admin.services.destroy');
        Route::get('/services/archives', [ServiceController::class, 'archives'])->name('admin.services.archives');
        Route::post('/services/{id}/restore', [ServiceController::class, 'restore'])->name('admin.services.restore');

        Route::get('/reservations/modify', function () {
            return view('admin.modify');
        })->name('reservation.modify');

        Route::get('/reservations/cancel', function () {
            return view('admin.cancel');
        })->name('reservation.cancel');

        Route::get('/users', function () {
            return view('admin.users');
        })->name('admin.users');

        
    });

    // Resident-only routes
    Route::middleware(IsResident::class)->prefix('resident')->group(function () {
        // Dashboard
        Route::get('/dashboard', function () {
            return view('resident.resident_dashboard');
        })->name('resident.dashboard');

        // Booking History
        Route::get('/booking-history', function () {
            return view('resident.booking_history');
        })->name('resident.booking.history');

        // Reservation routes
        Route::get('/reservation', [ReservationController::class, 'residentIndex'])->name('resident.reservation');
        Route::get('/reservation/available', [ReservationController::class, 'residentAvailable'])->name('resident.reservation.available');
        Route::get('/reservation/active-services', [ReservationController::class, 'activeServices'])->name('resident.reservation.active_services');
        Route::get('/reservation/fully-booked-dates', [ReservationController::class, 'fullyBookedDates'])->name('resident.reservation.fully_booked');

        // Store new reservation (if you want to keep the POST route for later)
        Route::post('/reservation', [ReservationController::class, 'store'])
            ->name('resident.reservation.store');

        // Add Reservation form - Wizard view
        Route::get('/reservation/add', function () {
            return view('resident.make_reservation_wizard');
        })->name('resident.reservation.add');

        // Edit Reservation form
        Route::get('/reservation/{id}/edit', [ReservationController::class, 'edit'])
            ->name('resident.reservation.edit');

        // Update Reservation
        Route::put('/reservation/{id}', [ReservationController::class, 'update'])
            ->name('resident.reservation.update');

        // Delete Reservation
        Route::delete('/reservation/{id}', [ReservationController::class, 'destroy'])
            ->name('resident.reservation.destroy');

        // Ticket
        Route::get('/reservation/{id}/ticket', [ReservationController::class, 'ticket'])->name('resident.reservation.ticket');

        // History
        Route::get('/reservation-history', [ReservationController::class, 'history'])->name('resident.reservation.history');
    });

    // Default redirect after login based on role
    Route::get('/dashboard', function () {
        if (auth()->role === 'admin') {
            return redirect()->route('dashboard');
        }
        return redirect()->route('resident.dashboard');
    })->name('dashboard');
});

// Public API for closed dates for datepicker consumption
Route::get('/api/closed-dates', [ClosurePeriodController::class, 'closedDatesApi'])->name('api.closed_dates');