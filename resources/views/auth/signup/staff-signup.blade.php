@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-50 px-4 py-6">
    <div class="bg-white shadow-lg rounded-2xl w-full max-w-xl p-4 sm:p-8">

        <!-- Logo -->
        <div class="flex justify-center mb-2">
            <img src="{{ asset('images/enclogo.png') }}" alt="ENC Logo" class="h-18">
        </div>

        <!-- Title -->
        <div class="text-center mb-2">
         <h2 class="flex items-center justify-center text-xl font-semibold text-gray-800 mb-4 space-x-2">
            <img src="{{ asset('images/login/booking.png') }}" alt="Booking Icon" class="h-8 w-8">
            <span>Create Account</span>
        </h2>
        <p class="text-center text-gray-500 mb-8">Welcome back! Sign in to manage your bookings</p>

        </div>

        <!-- Account Type Badge & Change Link -->
        <div class="flex items-center justify-between mb-2">
            <a href="{{ route('signup.index') }}" class="text-gray-600 text-sm hover:text-blue-600 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Change account type
            </a>
        <span class="text-white text-xs font-medium px-3 py-1 rounded" style="background-color: #1C398E;">
            Staff/SFI Account
        </span>
        </div>

        <!-- Form -->
        <form action="#" method="POST">
            @csrf

            <!-- Full Name -->
            <div class="mb-2">
                <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">
                    Full Name <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <input type="text" id="full_name" name="full_name" 
                           class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                           placeholder="Enter your full name" required>
                </div>
            </div>

            <!-- Ministry Email -->
            <div class="mb-3">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                    Ministry Email <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <input type="email" id="email" name="email" 
                           class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                           placeholder="your.name@ministry.gov" required>
                </div>
            </div>

            <!-- Department -->
            <div class="mb-3">
                <label for="department" class="block text-sm font-medium text-gray-700 mb-1">
                    Department <span class="text-red-500">*</span>
                </label>
                <select id="department" name="department" 
                        class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-500" required>
                    <option value="">Select your department</option>
                    <option value="administration">Administration</option>
                    <option value="finance">Finance</option>
                    <option value="hr">Human Resources</option>
                    <option value="it">Information Technology</option>
                    <option value="operations">Operations</option>
                    <option value="legal">Legal</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <!-- Phone Number -->
            <div class="mb-3">
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                    Phone Number <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <input type="tel" id="phone" name="phone" 
                           class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                           placeholder="+63 912 345 6789" required>
                </div>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                    Password <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input type="password" id="password" name="password" 
                           class="block w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                           placeholder="Create a password (min 6 characters)" required>
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePassword('password')">
                        <svg class="w-5 h-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                    Confirm Password <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input type="password" id="password_confirmation" name="password_confirmation" 
                           class="block w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                           placeholder="Confirm your password" required>
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePassword('password_confirmation')">
                        <svg class="w-5 h-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Terms & Conditions -->
            <div class="mb-3">
                <label class="flex items-start">
                    <input type="checkbox" name="terms" class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500" required>
                    <span class="ml-2 text-xs text-gray-600">
                        I agree to the 
                        <a href="#" class="text-blue-600 hover:underline">Terms & Conditions</a> and 
                        <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a>. My contact details and booking management may be shared with relevant departments.
                    </span>
                </label>
            </div>

            <!-- reCAPTCHA -->
            <div class="mb-2">
                <div class="border border-gray-300 rounded-lg p-3 bg-gray-50 flex items-center">
                    <input type="checkbox" id="recaptcha" name="recaptcha" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="recaptcha" class="ml-2 text-sm text-gray-700">I'm not a robot (Verification)</label>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full text-white font-medium px-4 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2"
                    style="background-color: #1E3A8A; height: 48px;">
                Create Account
            </button>

        </form>

        <!-- Footer -->
        <p class="text-center text-gray-600 mt-4 text-sm">
            Already have an account? 
            <a href="{{ route('login.index') }}" class="text-blue-600 hover:underline font-medium">Back to Login</a>
        </p>
    </div>
</div>

<script src="{{ asset('js/global/password.js') }}">
</script>
@endsection
