@extends('layouts.app')

@section('title', 'User Dashboard')

@section('content')
<section class="enc-page enc-fill-screen">
  <div class="container">
    <div class="enc-card mx-auto p-4 p-md-5" style="max-width: 960px;">

      <div class="text-center mb-5">
        <img src="{{ asset('images/enclogo.png') }}" alt="ENC Logo" class="mb-4" style="height: 72px;">
        <h1 class="enc-text-strong enc-type-title mb-2">Welcome to Your Dashboard</h1>
        <p class="enc-text-muted mb-0">Manage bookings, review approvals, and pick up where you left off.</p>
      </div>

      <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
        <div class="text-center text-md-start">
          <p class="mb-1 text-uppercase small fw-semibold enc-text-muted">Next action</p>
          <p class="mb-0 enc-text-strong">Jump into the Smart Booking Wizard to create a request.</p>
        </div>

        <a href="{{ route('booking.wizard') }}"
        class="btn btn-primary d-inline-flex align-items-center gap-2">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <rect x="3" y="4" width="18" height="17" rx="3"
                    stroke="currentColor" stroke-width="1.8" />
                <path d="M8 2v4M16 2v4M3 9h18M12 12v6M9 15h6"
                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
            </svg>
            Start a New Booking
        </a>
      </div>

    </div>
  </div>
</section>
@endsection
