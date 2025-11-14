<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('marketing/landing');
})->name('landing');

// --- Authentication pages ---
    // Login pages
Route::view('/login', 'auth.login.index')->name('login');     
Route::view('/login/form', 'auth.login.login')->name('login.form');

    // Signup page
Route::view('/signup', 'auth.signup.index')->name('signup.index');
Route::view('/signup/staff', 'auth.signup.staff-signup')->name('signup.staff'); // create resources/views/auth/signup.blade.php

// User Pages
    // Dashboard
Route::view('/user/dashboard', 'user.dashboard')->name('user.dashboard');

    // Booking
Route::view('/user/booking', 'user.booking.index')->name('user.booking.index');


// --- Booking Wizard page ---
Route::get('/book', function () {
    // Ensure the view path matches: resources/views/booking/wizard.blade.php
    return view('booking.wizard');
})->name('booking.wizard');


// --- Booking submit endpoint (placeholder) ---
Route::post('/bookings', [\App\Http\Controllers\BookingController::class, 'store'])
    ->name('bookings.store');
