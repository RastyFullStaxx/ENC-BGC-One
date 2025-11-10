@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-50 px-2">
    <div class="bg-white shadow-md rounded-lg w-full max-w-md p-16">
        <!-- Logo -->
        <div class="flex justify-center mb-12">
            <img src="{{ asset('images/enclogo.png') }}" alt="ENC Logo" class="h-18">
        </div>

        <!-- Title -->
        <h2 class="text-center text-xl font-semibold text-gray-800 mb-2">ENC Booking Portal</h2>
        <p class="text-center text-gray-500 mb-6">Welcome back! Sign in to manage your bookings</p>

        <!-- Sign-in Buttons -->
        <p class="text-sm text-gray-600 mb-2 text-center">Choose your sign-in method</p>

        <button class="w-full bg-blue-900 text-white py-2.5 rounded-md hover:bg-blue-800 transition-colors mb-4 flex items-center justify-center space-x-2">
                <img src="{{ asset('images/login/ministryicon.png') }}" alt="ENC Logo" class="h-6">
            <span>Sign in with Ministry Email (SSO)</span>
        </button>

        <div class="flex items-center my-4">
            <div class="flex-grow h-px bg-gray-200"></div>
            <span class="mx-3 text-sm text-gray-400">or</span>
            <div class="flex-grow h-px bg-gray-200"></div>
        </div>

        <button class="w-full border border-gray-300 py-2.5 rounded-md hover:bg-gray-50 transition-colors flex items-center justify-center space-x-2">
                <img src="{{ asset('images/login/emailicon.png') }}" alt="ENC Logo" class="h-6">
            <span>Sign in with Email/Password</span>
        </button>

        <!-- Info box -->
        <div class="bg-blue-50 border border-blue-100 text-blue-900 text-sm rounded-md p-3 mt-6">
            <p class="font-medium mb-1">Who can sign in:</p>
            <ul class="list-disc ml-5 space-y-1">
                <li>ENC Staff with ministry-issued email</li>
                <li>SFI personnel with verified credentials</li>
                <li>Guest accounts (limited features)</li>
            </ul>
        </div>

        <!-- Create account -->
        <p class="text-center text-gray-600 mt-6 text-sm">
            Don't have an account? 
            <a href="#" class="text-blue-700 hover:underline font-medium">Create an account</a>
        </p>

        <!-- Footer -->
        <div class="mt-6 pt-4 border-t border-gray-200">
            <div class="flex items-start space-x-2 text-xs text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <div>
                    <p class="font-medium text-gray-600">Security & Privacy:</p>
                    <p>Protected with industry-standard encryption. By signing in, you agree to our data privacy and usage policies. All bookings are logged for audit purposes.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
