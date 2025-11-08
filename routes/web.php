<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('marketing/landing');
})->name('landing');

Route::view('/login', 'auth.login')->name('login');       // create resources/views/auth/login.blade.php
Route::view('/register', 'auth.register')->name('register'); // create resources/views/auth/register.blade.php
