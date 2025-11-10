<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('marketing/landing');
})->name('landing');

// --- Authentication pages ---
    // Login pages
Route::view('/login', 'auth.login.index')->name('login.index');     
Route::view('/login/form', 'auth.login.login')->name('login.form');

    // Signup page
Route::view('/register', 'auth.register')->name('register'); // create resources/views/auth/signup.blade.php

// --- Booking Wizard page ---
Route::get('/book', function () {
    // Ensure the view path matches: resources/views/booking/wizard.blade.php
    return view('booking.wizard');
})->name('booking.wizard');

// --- Booking submit endpoint (placeholder) ---
Route::post('/bookings', [\App\Http\Controllers\BookingController::class, 'store'])
    ->name('bookings.store');