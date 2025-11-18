@extends('layouts.app')

@section('content')
<div class="enc-page flex items-center justify-center min-h-screen px-4 py-10">
    <div class="enc-card w-full max-w-md p-10 sm:p-12">
        <!-- Logo -->
        <div class="flex justify-center mb-12">
            <img src="{{ asset('images/enclogo.png') }}" alt="ENC Logo" class="h-18">
        </div>

        <!-- Title -->
        <h2 class="flex items-center justify-center text-xl font-semibold enc-text-strong mb-4 space-x-2">
            <img src="{{ asset('images/login/booking.png') }}" alt="Booking Icon" class="h-8 w-8">
            <span>ENC Booking Portal</span>
        </h2>
        <p class="text-center enc-text-muted mb-6">Welcome back! Sign in to manage your bookings</p>

        <!-- Sign-in Buttons -->
        <p class="text-sm enc-text-muted mb-2 text-center">Choose your sign-in method</p>

        {{-- signin with ministry email --}}
        <a href="{{ route('login.form') }}"
        class="w-full py-2.5 rounded-md transition-colors mb-4 flex items-center justify-center space-x-2 fw-semibold text-decoration-none"
        style="background-color: var(--enc-color-brand-primary); color: var(--enc-color-light);">
            <img src="{{ asset('images/login/ministryicon.png') }}" alt="ENC Logo" class="h-4">
            <span>Sign in with Ministry Email (SSO)</span>
        </a>


        <div class="flex items-center my-4">
            <div class="flex-grow h-px bg-gray-200"></div>
            <span class="mx-3 text-sm enc-text-muted">or</span>
            <div class="flex-grow h-px bg-gray-200"></div>
        </div>

        {{-- signin with normal email--}}
        <a href="{{ route('login.form') }}"
        class="w-full border border-gray-200 py-2.5 rounded-md hover:bg-gray-50 transition-colors flex items-center justify-center space-x-2 enc-text-strong text-decoration-none">
                <img src="{{ asset('images/login/emailicon.png') }}" alt="ENC Logo" class="h-4">
            <span>Sign in with Email/Password</span>
        </a>

        <!-- Info box -->
        <div class="enc-info-panel text-sm rounded-md p-3 mt-6">
            <p class="font-medium enc-text-brand mb-1">Who can sign in:</p>
            <ul class="list-disc ml-5 space-y-1">
                <li>ENC Staff with ministry-issued email</li>
                <li>SFI personnel with verified credentials</li>
                <li>Guest accounts (limited features)</li>
            </ul>
        </div>

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
@endsection
