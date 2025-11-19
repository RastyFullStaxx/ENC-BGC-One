@extends('layouts.app')

@section('title', 'Request Booking Change')

@push('styles')
    @vite([
        'resources/css/wizard/base.css',
        'resources/css/bookings/show.css',
    ])
@endpush

@section('content')
    @include('partials.dashboard-navbar', [
        'currentStep' => 0,
        'steps' => [],
        'bookingsCount' => $bookingsCount,
        'notificationsCount' => $notificationsCount,
        'userName' => auth()->user()->name ?? 'User',
        'userEmail' => auth()->user()->email ?? 'user@ministry.gov',
        'userRole' => auth()->user()->role ?? 'staff',
        'brand' => 'ONE Services',
        'showStepper' => false,
        'showBookingsToggle' => false,
    ])

    <section class="booking-detail-shell">
        <a href="{{ route('user.booking.show', $booking['id']) }}" class="bookings-back-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Back to booking summary
        </a>

        <header class="booking-detail-hero">
            <div class="booking-hero-content">
                <span class="status-pill is-neutral">Need help?</span>
                <h1 class="booking-hero-title">Request a change</h1>
                <p class="booking-hero-subtitle">
                    Tell Shared Services what needs to be adjusted. We’ll reopen the booking for review and keep you posted.
                </p>
            </div>
            <div class="booking-meta-grid">
                <dl>
                    <dt>Reference</dt>
                    <dd>{{ $booking['reference'] }}</dd>
                    <dt>Current date</dt>
                    <dd>{{ $booking['date_label'] ?? 'TBA' }}</dd>
                    <dt>Current time</dt>
                    <dd>{{ $booking['time_label'] ?? 'TBA' }}</dd>
                </dl>
            </div>
        </header>

        @if ($existingRequest)
            <div class="alert alert-warning mb-4" role="alert">
                <strong>Change request already submitted.</strong>
                <p class="mb-1">Status: {{ ucfirst($existingRequest['status']) }}</p>
                <p class="mb-0">{{ $existingRequest['notes'] }}</p>
                @if($existingRequest['opened_at'])
                    <p class="small text-muted mb-0">Sent {{ $existingRequest['opened_at'] }}</p>
                @endif
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger mb-4" role="alert">
                <strong>We couldn’t submit your request.</strong>
                <ul class="mb-0 mt-2 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="booking-detail-grid">
            <article class="detail-card">
                <h3>Change request form</h3>
                @if(!$existingRequest)
                    <form action="{{ route('user.booking.request-change.store', $booking['id']) }}" method="POST" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label for="changeNotes" class="form-label text-uppercase small fw-semibold">What needs to change?</label>
                            <textarea
                                id="changeNotes"
                                name="notes"
                                rows="5"
                                class="form-control"
                                placeholder="Tell us what needs to be adjusted (time, duration, support, etc.)"
                                required
                            >{{ old('notes') }}</textarea>
                        </div>
                        <p class="small text-muted mb-3">
                            We’ll notify you once an admin responds. Requesting a change temporarily pauses the approved slot.
                        </p>
                        <button type="submit" class="btn btn-primary w-100">
                            Submit change request
                        </button>
                    </form>
                @else
                    <p class="mb-0 text-muted">Please wait for the admin team to respond before submitting another request.</p>
                @endif
            </article>
            <article class="detail-card">
                <h3>Tips</h3>
                <ul class="detail-list">
                    <li>
                        <span class="label">Be specific</span>
                        <span class="value">Let us know the new time, what needs to be forfeited, or additional context.</span>
                    </li>
                    <li>
                        <span class="label">Keep contacts updated</span>
                        <span class="value">If you’re looped with other teams, cc them so everyone knows a change is pending.</span>
                    </li>
                    <li>
                        <span class="label">Need urgent help?</span>
                        <span class="value">Call Shared Services for high-priority conflicts, then log the change here for traceability.</span>
                    </li>
                </ul>
            </article>
        </div>
    </section>
@endsection
