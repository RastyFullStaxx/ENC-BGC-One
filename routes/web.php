<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\UserBookingController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminHubController;
use App\Http\Controllers\Admin\AdminApprovalController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\FacilityCatalogController;


Route::get('/', LandingPageController::class)->name('landing');

// Facility catalog (public for preview)
Route::get('/facilities/catalog', FacilityCatalogController::class)->name('facilities.catalog');

// Help/FAQ - Accessible to all users (guest and authenticated)
Route::get('/faq', function () {
    return view('faqs.faq');
})->name('faq');


// --- Authentication pages ---
// Login pages (guest middleware redirects authenticated users to dashboard)
Route::middleware(['guest'])->group(function () {
    Route::view('/login', 'auth.login.index')->name('login');     
    Route::get('/login/form', [LoginController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
    
    // Signup pages
    Route::view('/signup', 'auth.signup.index')->name('signup.index');
    Route::get('/signup/staff', [SignupController::class, 'showStaffForm'])->name('signup.staff');
    Route::post('/signup/staff', [SignupController::class, 'registerStaff'])->name('signup.staff.submit');
});

// Loading page (accessible to all)
Route::view('/login/loading', 'auth.login.loading')->name('login.loading');

// Logout (requires authentication) - allow GET fallback to avoid stale CSRF
Route::match(['GET', 'POST'], '/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');


// User Pages (Protected - requires authentication and staff role)
Route::middleware(['auth', 'role:staff'])->group(function () {
    // Dashboard
    Route::get('/user/dashboard', \App\Http\Controllers\UserDashboardController::class)->name('user.dashboard');
    
    // List of Bookings
    Route::get('/user/booking', [\App\Http\Controllers\UserBookingController::class, 'index'])->name('user.booking.index');

    // Booking Wizard (must come before {booking} route to avoid route conflict)
    Route::get('/user/booking/wizard', [\App\Http\Controllers\BookingController::class, 'index'])->name('user.booking.wizard');
    
    Route::get('/user/booking/{booking}', [\App\Http\Controllers\UserBookingController::class, 'show'])->name('user.booking.show');

    // Profile & Settings
    Route::view('/user/profile', 'profile.profile')->name('user.profile');
    Route::view('/user/settings', 'settings.settings')->name('user.settings');


    // Booking API endpoints
    Route::prefix('api/bookings')->group(function () {
        Route::get('/facilities', [\App\Http\Controllers\BookingController::class, 'getFacilities'])
            ->name('api.bookings.facilities')
            ->withoutMiddleware(['auth', 'role:staff', 'role:admin']);
        Route::post('/check-availability', [\App\Http\Controllers\BookingController::class, 'checkAvailability'])->name('api.bookings.check-availability');
        Route::post('/store', [\App\Http\Controllers\BookingController::class, 'store'])->name('api.bookings.store');
        Route::get('/user-bookings', [\App\Http\Controllers\BookingController::class, 'getUserBookings'])->name('api.bookings.user-bookings');
        Route::get('/{id}', [\App\Http\Controllers\BookingController::class, 'show'])->name('api.bookings.show');
        Route::post('/{id}/cancel', [\App\Http\Controllers\BookingController::class, 'cancel'])->name('api.bookings.cancel');
    });

    // Return Room Capacity for Booking Wizard
    Route::get('/user/booking/wizard/capacities', [\App\Http\Controllers\BookingController::class, 'returnRoomCapacity'])->name('api.bookings.capacities');
});

// Notifications API (returns empty array when not authenticated to avoid redirects)
Route::prefix('api/bookings')->group(function () {
    Route::get('/notifications', [\App\Http\Controllers\BookingController::class, 'getUserNotifications'])
        ->withoutMiddleware(['auth', 'role:staff', 'role:admin'])
        ->name('api.bookings.notifications');
});

// Admin Pages (Protected - requires authentication and admin role)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/hub', [AdminHubController::class, 'index'])->name('admin.hub');
    Route::get('/admin/approvals', [AdminApprovalController::class, 'index'])->name('admin.approvals.queue');
    Route::get('/admin/approvals/{booking}', [AdminApprovalController::class, 'show'])->name('admin.approvals.show');
    Route::post('/admin/approvals/{booking}/decision', [AdminApprovalController::class, 'decide'])->name('admin.approvals.decision');
});

// Temporary preview route for Admin Users & Roles tool
Route::view('/admin/users', 'admin.users')->name('admin.users');

// Temporary preview route for Admin Facilities Management
Route::view('/admin/facilities', 'admin.facilities')->name('admin.facilities');

// Temporary preview route for Admin Analytics
Route::view('/admin/analytics', 'admin.analytics')->name('admin.analytics');

// Temporary preview route for Admin Policies
Route::view('/admin/policies', 'admin.policies')->name('admin.policies');

// Temporary preview route for Admin Global Calendar
Route::view('/admin/calendar', 'admin.calendar')->name('admin.calendar');

// Temporary preview route for Admin Audit Log
Route::view('/admin/audit', 'admin.audit')->name('admin.audit');

// --- Public Booking Preview (for testing without auth) ---
Route::get('/book', function () {
    return view('booking.wizard');
})->name('booking.wizard');
