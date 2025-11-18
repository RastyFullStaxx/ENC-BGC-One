@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth/login/loading.css') }}">
@endpush

@section('content')
<div class="enc-page flex items-center justify-center min-h-screen px-4 py-10">
    <div class="enc-card w-full max-w-[448px] p-10 sm:p-12">
        <!-- Logo -->
        <div class="flex justify-center mb-12">
             <img src="{{ asset('images/enclogo.png') }}" alt="ENC Logo" class="w-24 h-24">
        </div>

        <!-- Title -->
        <h2 class="flex items-center justify-center text-xl font-semibold enc-text-strong mb-4 space-x-2">
            <img src="{{ asset('images/login/booking.png') }}" alt="Booking Icon" class="h-8 w-8">
            <span>ENC Booking Portal</span>
        </h2>
        <p class="text-center enc-text-muted mb-8">Welcome back! Sign in to manage your bookings</p>

        <!-- Back to login options -->
        <div class="mb-14">
        <a href="{{ route('login') }}" 
            class="flex items-center text-m enc-text-muted hover:underline transition-colors duration-150 space-x-1"
            aria-label="Back to login options">
            <svg xmlns="http://www.w3.org/2000/svg" 
                class="h-4 w-4" 
                fill="none" 
                viewBox="0 0 24 24" 
                stroke="currentColor" 
                stroke-width="1.5" 
                aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6L4 12m0 0l6 6m-6-6h16" />
            </svg>
            <span>Back to login options</span>
        </a>
        </div>

        <!-- Login Form -->
        <form id="loginForm" action="{{ route('login.submit') }}" method="POST" class="space-y-4">
            @csrf
            <!-- Email -->
            <div class="relative mb-4">
                <input type="email" id="email" name="email" placeholder="your.email@ministry.gov"
                    pattern="[a-zA-Z0-9._-]+@ministry\.gov"
                    title="Email must be in the format: your.name@ministry.gov"
                    value="{{ old('email') }}"
                    class="w-full border border-gray-300 rounded-md py-2 pl-10 pr-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 enc-text-strong"
                    required>
                <!-- Envelope/Message Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" 
                    class="absolute left-3 top-2.5 h-5 w-5 enc-text-muted" 
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m0 8V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2h14a2 2 0 002-2z" />
                </svg>
            </div>
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror

            <!-- Password -->
            <div>
                <div class="flex justify-end items-center mb-1 text-right">
                    <a href="#" class="text-sm enc-link">Forgot password?</a>
                </div>

                <div class="relative mb-4">
                    <input type="password" id="password" name="password" placeholder="Enter your password"
                        class="w-full border border-gray-300 rounded-md py-2 pl-10 pr-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 enc-text-strong"
                        required>
                    <!-- Lock Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" 
                        class="absolute left-3 top-2.5 h-5 w-5 enc-text-muted" 
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 11c0 .828-.672 1.5-1.5 1.5S9 11.828 9 11s.672-1.5 1.5-1.5S12 10.172 12 11z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 11V7a5 5 0 00-10 0v4H5a2 2 0 00-2 2v6a2 2 0 002 2h14a2 2 0 002-2v-6a2 2 0 00-2-2h-2z" />
                    </svg>
                    <!-- Eye Icon Toggle -->
                    <button type="button" class="absolute right-3 top-2.5 flex items-center" onclick="togglePassword('password')">
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

            <!-- Sign In Button -->
            <button type="submit"
                class="w-full text-white py-2.5 rounded-md transition-colors font-semibold"
                style="background-color: var(--enc-color-brand-primary);">
                Sign In
            </button>
        </form>


        <!-- Create account -->
        <p class="text-center enc-text-muted mt-6 text-sm">
            Don't have an account? 
            <a href="{{ route('signup.index') }}" class="enc-link font-medium">Create an account</a>
        </p>

        <!-- Footer -->
        <div class="mt-6 pt-4 border-t border-gray-200">
            <div class="flex items-start space-x-2 text-xs enc-text-muted">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 enc-text-muted flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <div>
                    <p class="font-medium enc-text-strong">Security & Privacy:</p>
                    <p>Protected with industry-standard encryption. By signing in, you agree to our data privacy and usage policies. All bookings are logged for audit purposes.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lottie Animation Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js"></script>

    </div>
</div>

<!-- Password Toggle Script -->
<script src="{{ asset('js/global/password.js') }}"></script>

<!-- Login Handler Script -->
<script src="{{ asset('js/auth/login-handler.js') }}"></script>
@endsection
