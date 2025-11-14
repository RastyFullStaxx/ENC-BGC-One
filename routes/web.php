<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\Auth\LoginController;


Route::get('/', function () {
    return view('marketing/landing');
})->name('landing');

// Facility catalog (public for preview)
Route::view('/facilities/catalog', 'facilities.catalog')->name('facilities.catalog');

// Help/FAQ - Accessible to all users (guest and authenticated)
Route::get('/faq', function () {
    return view('user.faq');
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

// Logout (requires authentication)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');


// User Pages (Protected - requires authentication and staff role)
Route::middleware(['auth', 'role:staff'])->group(function () {
    // Dashboard
    Route::view('/user/dashboard', 'user.dashboard')->name('user.dashboard');
    
    // List of Bookings
    Route::view('/user/booking', 'user.booking.index')->name('user.booking.index');

    // Profile & Settings
    Route::view('/user/profile', 'user.profile')->name('user.profile');
    Route::view('/user/settings', 'user.settings')->name('user.settings');
});

// Admin Pages (Protected - requires authentication and admin role)
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard
    Route::view('/admin/dashboard', 'admin.dashboard')->name('admin.dashboard');
});


// --- Booking Wizard page ---
Route::get('/book', function () {
    // Ensure the view path matches: resources/views/booking/wizard.blade.php
    return view('booking.wizard');
})->name('booking.wizard');


// --- Booking submit endpoint (placeholder) ---
Route::post('/bookings', [\App\Http\Controllers\BookingController::class, 'store'])
    ->name('bookings.store');
