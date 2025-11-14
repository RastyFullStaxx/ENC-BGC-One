@extends('layouts.app')

@section('title', 'User Dashboard')

@section('content')
        <section class="enc-page enc-fill-screen">
            <div class="container">
                <div class="enc-card mx-auto p-4 p-md-5" style="max-width: 960px;">

                    <div class="text-center mb-5">
                        <img src="{{ asset('images/enclogo.png') }}" alt="ENC Logo" class="mb-4" style="height: 72px;">
                        <h1 class="enc-text-strong enc-type-title mb-2">Admin Dashboard</h1>
                        <p class="enc-text-muted mb-0">Overview of system metrics and user management.</p>
                        
                        <!-- Logout Button -->
                        <div class="mt-3">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-2">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9" 
                                            stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
                        <div class="text-center text-md-start">
                            <p class="mb-1 text-uppercase small fw-semibold enc-text-muted">Next action</p>
                            <p class="mb-0 enc-text-strong">Manage users, review system logs, and configure settings.</p>
                        </div>

                        <button class="btn btn-primary d-inline-flex align-items-center gap-2" disabled>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <circle cx="12" cy="7" r="4"
                                    stroke="currentColor" stroke-width="1.8" />
                                <path d="M5.5 21a6.5 6.5 0 0113 0"
                                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                            </svg>
                            Manage Users (Coming Soon)
                        </button>
                    </div>

                </div>
            </div>
        </section>
@endsection