@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-50 px-4">
    <div class="bg-white shadow-md rounded-2xl w-full max-w-2xl p-10 sm:p-12">

        <!-- Logo -->
        <div class="flex justify-center mb-8">
            <img src="{{ asset('images/enclogo.png') }}" alt="ENC Logo" class="h-18">
        </div>

        <!-- Title -->
        <h2 class="text-center text-lg font-semibold text-gray-800 flex items-center justify-center space-x-2 mb-2">
            <img src="{{ asset('images/login/booking.png') }}" alt="Booking Icon" class="h-8 w-8">
            <span>Create Account</span>
        </h2>

        <p class="text-center text-gray-500 mb-10">
            Register to access the ENC Booking Portal
        </p>

        <!-- Select Account Type -->
        <h3 class="text-center text-md font-medium text-gray-800 mb-2">Select Account Type</h3>
        <p class="text-center text-gray-500 mb-8 text-sm">
            Choose the type that best describes you
        </p>

        <!-- Account Options -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">

            <!-- ENC Staff / SFI -->
            <a href="{{ route('signup.staff') }}" 
               class="border border-gray-300 rounded-lg p-4 hover:border-blue-600 hover:shadow-lg transition-all duration-200 block bg-white">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <img src="{{ asset('images/signup/staff.png') }}" alt="ENC Staff" class="w-10 h-10">
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-semibold text-gray-900 mb-1 text-sm">ENC Staff / SFI</h4>
                        <p class="text-gray-500 text-xs mb-2 leading-relaxed">For employees with ministry-issued email</p>
                        <div class="flex flex-wrap gap-1.5">
                            <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs font-medium">Instant Verify</span>
                            <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs font-medium">Full Access</span>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Guest -->
            <a href="#" 
               class="border border-gray-300 rounded-lg p-4 hover:border-blue-600 hover:shadow-lg transition-all duration-200 block bg-white">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <img src="{{ asset('images/signup/guest.png') }}" alt="Guest" class="w-10 h-10">
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-semibold text-gray-900 mb-1 text-sm">Guest</h4>
                        <p class="text-gray-500 text-xs mb-2 leading-relaxed">For external partners and visitors</p>
                        <div class="flex flex-wrap gap-1.5">
                            <span class="bg-orange-100 text-orange-700 px-2 py-0.5 rounded text-xs font-medium">Approval Required</span>
                            <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-xs font-medium">Limited Features</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Info box -->
        <div class="bg-blue-50 border border-blue-100 text-blue-900 text-sm rounded-md p-4">
            <p class="font-medium mb-1">Which account type should I choose?</p>
            <ul class="space-y-1">
                <li><span class="font-semibold">Staff/SFI:</span> If you work for ENC or affiliated agencies and have a ministry email.</li>
                <li><span class="font-semibold">Guest:</span> If you're an external partner, contractor, or visitor needing temporary access.</li>
            </ul>
        </div>

        <!-- Footer -->
        <p class="text-center text-gray-600 mt-6 text-sm">
            Already have an account? 
            <a href="{{ route('login.index') }}" class="text-blue-700 hover:underline font-medium">Back to Login</a>
        </p>
    </div>
</div>
@endsection
