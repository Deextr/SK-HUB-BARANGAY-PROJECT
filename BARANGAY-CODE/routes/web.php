<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ClosurePeriodController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserAccountController;
use App\Http\Controllers\ArchivesController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SettingsController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsResident;
use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login.form');
});

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/register/nda', [AuthController::class, 'showNdaForm'])->name('register.nda');
    Route::post('/register/nda/accept', [AuthController::class, 'acceptNda'])->name('register.nda.accept');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/check-email', [AuthController::class, 'checkEmailExists'])->name('check.email');

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    // Pending account page
    Route::get('/account/pending', [AuthController::class, 'showPendingPage'])->name('account.pending');
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
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/reservations', [ReservationController::class, 'index'])
            ->name('reservation.dashboard');
        Route::get('/reservations/today-warnings', [ReservationController::class, 'getTodayWarnings'])
            ->name('admin.reservations.today_warnings');
        Route::post('/reservations/{reservation}/actual-times', [ReservationController::class, 'setActualTimes'])
            ->name('admin.reservations.set_times');
        Route::put('/reservations/{reservation}/cancel', [ReservationController::class, 'adminCancel'])
            ->name('admin.reservations.cancel');
        // Closure periods CRUD
        Route::get('/closure-periods', [ClosurePeriodController::class, 'index'])->name('admin.closure_periods.index');
        Route::post('/closure-periods', [ClosurePeriodController::class, 'store'])->name('admin.closure_periods.store');
        Route::put('/closure-periods/{closurePeriod}', [ClosurePeriodController::class, 'update'])->name('admin.closure_periods.update');
        Route::delete('/closure-periods/{closurePeriod}', [ClosurePeriodController::class, 'destroy'])->name('admin.closure_periods.destroy');
        Route::get('/closure-periods/archives', [ClosurePeriodController::class, 'archives'])->name('admin.closure_periods.archives');
        Route::post('/closure-periods/{id}/restore', [ClosurePeriodController::class, 'restore'])->name('admin.closure_periods.restore');

        // Services CRUD
        Route::get('/services', [ServiceController::class, 'index'])->name('admin.services.index');
        Route::post('/services', [ServiceController::class, 'store'])->name('admin.services.store');
        Route::put('/services/{service}', [ServiceController::class, 'update'])->name('admin.services.update');
        Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('admin.services.destroy');
        Route::post('/services/{service}/archive-units', [ServiceController::class, 'archiveUnits'])->name('admin.services.archive_units');
        Route::get('/services/archives', [ServiceController::class, 'archives'])->name('admin.services.archives');
        Route::get('/archives', [ArchivesController::class, 'index'])->name('admin.archives');
        // old aliases removed; single entry now serves combined archives
        Route::post('/services/{id}/restore', [ServiceController::class, 'restore'])->name('admin.services.restore');

        Route::get('/reservations/modify', function () {
            return view('admin.modify');
        })->name('reservation.modify');

        Route::get('/reservations/cancel', function () {
            return view('admin.cancel');
        })->name('reservation.cancel');

        // User Account Management
        Route::resource('user-accounts', UserAccountController::class)->names([
            'index' => 'admin.user_accounts.index',
            'create' => 'admin.user_accounts.create',
            'store' => 'admin.user_accounts.store',
            'show' => 'admin.user_accounts.show',
            'edit' => 'admin.user_accounts.edit',
            'update' => 'admin.user_accounts.update',
            'destroy' => 'admin.user_accounts.destroy',
        ]);

        // User Account Status Management
        Route::post('/user-accounts/{user}/approve', [UserAccountController::class, 'approve'])->name('admin.user_accounts.approve');
        Route::post('/user-accounts/{user}/reject', [UserAccountController::class, 'reject'])->name('admin.user_accounts.reject');
        Route::post('/user-accounts/{user}/archive', [UserAccountController::class, 'archive'])->name('admin.user_accounts.archive');
        Route::post('/user-accounts/{user}/unarchive', [UserAccountController::class, 'unarchive'])->name('admin.user_accounts.unarchive');
        Route::get('/user-accounts/filter/pending', [UserAccountController::class, 'pending'])->name('admin.user_accounts.pending');
        Route::get('/user-accounts/filter/approved', [UserAccountController::class, 'approved'])->name('admin.user_accounts.approved');
        Route::get('/user-accounts/filter/rejected', [UserAccountController::class, 'rejected'])->name('admin.user_accounts.rejected');

        // Legacy users route for backward compatibility
        Route::get('/users', [UserAccountController::class, 'index'])->name('admin.users');

        // Reports
        Route::get('/reports', [ReportsController::class, 'index'])->name('admin.reports.index');
        Route::get('/reports/export/csv', [ReportsController::class, 'exportCsv'])->name('admin.reports.export.csv');
        Route::get('/reports/export/pdf', [ReportsController::class, 'exportPdf'])->name('admin.reports.export.pdf');
    });

    // Resident-only routes
    Route::middleware(IsResident::class)->prefix('resident')->group(function () {
        // Dashboard
        Route::get('/dashboard', function () {
            return view('resident.resident_dashboard');
        })->name('resident.dashboard');

        // Booking History removed

        // Reservation routes
        Route::get('/reservation', [ReservationController::class, 'residentIndex'])->name('resident.reservation');
        Route::get('/reservation/available', [ReservationController::class, 'residentAvailable'])->name('resident.reservation.available');
        Route::get('/reservation/active-services', [ReservationController::class, 'activeServices'])->name('resident.reservation.active_services');
        Route::get('/reservation/fully-booked-dates', [ReservationController::class, 'fullyBookedDates'])->name('resident.reservation.fully_booked');
        Route::get('/reservation/has-reservation', [ReservationController::class, 'hasReservationForDate'])->name('resident.reservation.has_for_date');
        Route::get('/reservation/unavailable-dates', [ReservationController::class, 'getUnavailableDates'])->name('resident.reservation.unavailable_dates');
        Route::get('/reservation/time-slots', [ReservationController::class, 'getAvailableTimeSlots'])->name('resident.reservation.time_slots');

        // Store new reservation (if you want to keep the POST route for later)
        Route::post('/reservation', [ReservationController::class, 'store'])
            ->name('resident.reservation.store');

        // Terms and Conditions page before reservation
        Route::get('/reservation/terms', [ReservationController::class, 'showTerms'])->name('resident.reservation.terms');
        Route::post('/reservation/terms', [ReservationController::class, 'acceptTerms'])->name('resident.reservation.accept_terms');

        // Add Reservation form - Wizard view (with cooldown awareness)
        Route::get('/reservation/add', [ReservationController::class, 'create'])->name('resident.reservation.add');

        // Delete Reservation
        Route::delete('/reservation/{id}', [ReservationController::class, 'destroy'])
            ->name('resident.reservation.destroy');

        // Ticket
        Route::get('/reservation/{id}/ticket', [ReservationController::class, 'ticket'])->name('resident.reservation.ticket');

        // History
        Route::get('/reservation-history', [ReservationController::class, 'history'])->name('resident.reservation.history');
        Route::get('/setting', [SettingsController::class, 'index'])->name('resident.settings.index');
        Route::post('/setting/update', [SettingsController::class, 'update'])->name('resident.settings.update');
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
