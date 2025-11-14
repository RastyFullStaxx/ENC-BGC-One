@extends('layouts.app')

@section('content')
<!-- Loading Overlay -->
<div id="loadingOverlay" class="loading-overlay hidden">
    <div class="spinner-container">
        <div class="spinner"></div>
        <p class="loading-text">Creating your account...</p>
    </div>
</div>

<div class="enc-page flex items-center justify-center min-h-screen px-4 py-10">
    <div class="enc-card w-full max-w-xl p-6 sm:p-10">

        <!-- Logo -->
        <div class="flex justify-center mb-2">
            <img src="{{ asset('images/enclogo.png') }}" alt="ENC Logo" class="h-18">
        </div>

        <!-- Title -->
        <div class="text-center mb-2">
         <h2 class="flex items-center justify-center text-xl font-semibold enc-text-strong mb-4 space-x-2">
            <img src="{{ asset('images/login/booking.png') }}" alt="Booking Icon" class="h-8 w-8">
            <span>Create Account</span>
        </h2>
        <p class="text-center enc-text-muted mb-8">Welcome back! Sign in to manage your bookings</p>

        </div>

        <!-- Account Type Badge & Change Link -->
        <div class="flex items-center justify-between mb-2">
            <a href="{{ route('signup.index') }}" class="enc-link text-sm flex items-center gap-1">
                <svg class="w-4 h-4 enc-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Change account type
            </a>
        <span class="text-white text-xs font-medium px-3 py-1 rounded" style="background-color: var(--enc-color-brand-secondary);">
            Staff/SFI Account
        </span>
        </div>

        <!-- Form -->
        <form id="staffSignupForm" action="{{ route("signup.staff.submit") }}" method="POST">
            @csrf
            <!-- Full Name -->
            <div class="mb-2">
                <label for="name" class="block text-sm font-medium enc-text-strong mb-1">
                    Full Name <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 enc-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <input type="text" id="name" name="name" 
                           class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm enc-text-strong"
                           placeholder="Enter your full name" value="{{ old('name') }}" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Ministry Email -->
            <div class="mb-3">
                <label for="email" class="block text-sm font-medium enc-text-strong mb-1">
                    Ministry Email <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 enc-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <input type="email" id="email" name="email" 
                           class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm enc-text-strong"
                           placeholder="your.name@ministry.gov" 
                           pattern="[a-zA-Z0-9._-]+@ministry\.gov"
                           title="Email must be in the format: your.name@ministry.gov"
                           value="{{ old('email') }}" required>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Department -->
            <div class="mb-3">
                <label for="department_id" class="block text-sm font-medium enc-text-strong mb-1">
                    Department <span class="text-red-500">*</span>
                </label>
                <select id="department_id" name="department_id" 
                        class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm enc-text-muted"
                        required>
                    <option value="">Select your department</option>
                    <option value="administration" {{ old('department_id') == 'administration' ? 'selected' : '' }}>Administration</option>
                    <option value="finance" {{ old('department_id') == 'finance' ? 'selected' : '' }}>Finance</option>
                    <option value="hr" {{ old('department_id') == 'hr' ? 'selected' : '' }}>Human Resources</option>
                    <option value="it" {{ old('department_id') == 'it' ? 'selected' : '' }}>Information Technology</option>
                    <option value="operations" {{ old('department_id') == 'operations' ? 'selected' : '' }}>Operations</option>
                    <option value="legal" {{ old('department_id') == 'legal' ? 'selected' : '' }}>Legal</option>
                    <option value="other" {{ old('department_id') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('department_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone Number -->
            <div class="mb-3">
                <label for="phone" class="block text-sm font-medium enc-text-strong mb-1">
                    Phone Number <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 enc-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <input type="tel" id="phone" name="phone" 
                           class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm enc-text-strong"
                           placeholder="+63 912 345 6789" value="{{ old('phone') }}">
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="block text-sm font-medium enc-text-strong mb-1">
                    Password <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 enc-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input type="password" id="password" name="password" 
                           class="block w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm enc-text-strong"
                           placeholder="Create a password (min 6 characters)" required>
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePassword('password')">
                        <svg class="w-5 h-5 enc-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <label for="password_confirmation" class="block text-sm font-medium enc-text-strong mb-1">
                    Confirm Password <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 enc-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input type="password" id="password_confirmation" name="password_confirmation" 
                           class="block w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm enc-text-strong"
                           placeholder="Confirm your password" required>
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePassword('password_confirmation')">
                        <svg class="w-5 h-5 enc-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                @error('password_confirmation')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Terms & Conditions -->
            <div class="mb-3">
                <label class="flex items-start">
                    <input type="checkbox" name="terms" class="mt-1 rounded border-gray-300" style="accent-color: var(--enc-color-brand-primary);" required>
                    <span class="ml-2 text-xs enc-text-muted">
                        I agree to the 
                        <a href="#" class="enc-link">Terms & Conditions</a> and 
                        <a href="#" class="enc-link">Privacy Policy</a>. My contact details and booking management may be shared with relevant departments.
                    </span>
                </label>
            </div>

            <!-- reCAPTCHA -->
            <div class="mb-2">
                <div class="border rounded-lg p-3 flex items-center" style="border-color: var(--enc-color-gray-light); background-color: rgba(0, 24, 64, 0.02);">
                    <input type="checkbox" id="recaptcha" name="recaptcha" class="rounded border-gray-300" style="accent-color: var(--enc-color-brand-primary);">
                    <label for="recaptcha" class="ml-2 text-sm enc-text-strong">I'm not a robot (Verification)</label>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full text-white font-medium px-4 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2"
                    style="background-color: var(--enc-color-brand-primary); height: 48px;">
                Create Account
            </button>

        </form>

        <!-- Footer -->
        <p class="text-center enc-text-muted mt-4 text-sm">
            Already have an account? 
            <a href="{{ route('login.index') }}" class="enc-link font-medium">Back to Login</a>
        </p>
    </div>
</div>

<!-- SweetAlert2 Local -->
<link rel="stylesheet" href="{{ asset('css/vendor/sweetalert2.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/auth/sweetalert-custom.css') }}">
<script src="{{ asset('js/vendor/sweetalert2.min.js') }}"></script>

<!-- Loading CSS -->
<link rel="stylesheet" href="{{ asset('css/auth/loading.css') }}">

<!-- Password Toggle Script -->
<script src="{{ asset('js/global/password.js') }}"></script>

<!-- Signup Handler Script -->
<script src="{{ asset('js/auth/signup-handler.js') }}"></script>
@endsection
