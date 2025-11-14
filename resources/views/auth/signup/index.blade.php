@extends('layouts.app')

@section('content')
<div class="enc-page flex items-center justify-center min-h-screen px-4">
    <div class="enc-card w-full max-w-2xl p-10 sm:p-12">

        <!-- Logo -->
        <div class="flex justify-center mb-8">
            <img src="{{ asset('images/enclogo.png') }}" alt="ENC Logo" class="h-18">
        </div>

        <!-- Title -->
        <h2 class="enc-text-strong text-center text-lg font-semibold flex items-center justify-center space-x-2 mb-2">
            <img src="{{ asset('images/login/booking.png') }}" alt="Booking Icon" class="h-8 w-8">
            <span>Create Account</span>
        </h2>

        <p class="enc-text-muted text-center mb-10 enc-type-body">
            Register to access the ENC Booking Portal
        </p>

        <!-- Select Account Type -->
        <h3 class="enc-text-strong text-center text-md font-semibold mb-2 enc-type-title">Select Account Type</h3>
        <p class="enc-text-muted text-center mb-8 enc-type-subheading">
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
                        <h4 class="enc-text-strong font-semibold mb-1 text-sm">ENC Staff / SFI</h4>
                        <p class="enc-text-muted text-xs mb-2 leading-relaxed">For employees with ministry-issued email</p>
                        <div class="flex flex-wrap gap-1.5">
                            <span class="enc-chip enc-chip-success">Instant Verify</span>
                            <span class="enc-chip enc-chip-info">Full Access</span>
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
                        <h4 class="enc-text-strong font-semibold mb-1 text-sm">Guest</h4>
                        <p class="enc-text-muted text-xs mb-2 leading-relaxed">For external partners and visitors</p>
                        <div class="flex flex-wrap gap-1.5">
                            <span class="enc-chip enc-chip-warning">Approval Required</span>
                            <span class="enc-chip enc-chip-neutral">Limited Features</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Info box -->
        <div class="enc-info-panel text-sm rounded-md p-4">
            <p class="font-medium enc-text-brand mb-1">Which account type should I choose?</p>
            <ul class="space-y-1">
                <li><span class="font-semibold enc-text-strong">Staff/SFI:</span> If you work for ENC or affiliated agencies and have a ministry email.</li>
                <li><span class="font-semibold enc-text-strong">Guest:</span> If you're an external partner, contractor, or visitor needing temporary access.</li>
            </ul>
        </div>

        <!-- Footer -->
        <p class="enc-text-muted text-center mt-6 text-sm">
            Already have an account? 
            <a href="{{ route('login.index') }}" class="enc-link">Back to Login</a>
        </p>
    </div>
</div>
@endsection
