<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('marketing/landing');
})->name('landing');

Route::view('/login', 'auth.login')->name('login');       // create resources/views/auth/login.blade.php
Route::view('/register', 'auth.register')->name('register'); // create resources/views/auth/register.blade.php

// --- Booking Wizard page ---
Route::get('/book', function () {
    // Ensure the view path matches: resources/views/booking/wizard.blade.php
    return view('booking.wizard');
})->name('booking.wizard');

// --- Booking submit endpoint (placeholder) ---
Route::post('/bookings', [\App\Http\Controllers\BookingController::class, 'store'])
    ->name('bookings.store');