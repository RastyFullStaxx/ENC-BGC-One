@extends('layouts.app')

@section('title', 'Edit Booking')

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
                <span class="status-pill is-warning">Edit request</span>
                <h1 class="booking-hero-title">Update {{ $booking['facility'] }}</h1>
                <p class="booking-hero-subtitle">
                    Adjust the schedule or meeting context before we finalize the slot. Changes are allowed up to 24 hours before the start time.
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

        @if ($errors->any())
            <div class="alert alert-danger mb-4" role="alert">
                <strong>We couldn’t save your changes.</strong>
                <ul class="mb-0 mt-2 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="booking-detail-grid">
            <article class="detail-card">
                <h3>Update schedule</h3>
                <form action="{{ route('user.booking.update', $booking['id']) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="editDate" class="form-label text-uppercase small fw-semibold">Date</label>
                        <input
                            id="editDate"
                            type="date"
                            name="date"
                            class="form-control"
                            value="{{ old('date', $booking['date_value']) }}"
                            required
                        >
                    </div>
                    <div class="mb-3 d-flex gap-3 flex-wrap">
                        <div class="flex-fill">
                            <label for="editStartTime" class="form-label text-uppercase small fw-semibold">Start time</label>
                            <input
                                id="editStartTime"
                                type="time"
                                name="start_time"
                                class="form-control"
                                value="{{ old('start_time', $booking['start_time_value']) }}"
                                required
                            >
                        </div>
                        <div class="flex-fill">
                            <label for="editEndTime" class="form-label text-uppercase small fw-semibold">End time</label>
                            <input
                                id="editEndTime"
                                type="time"
                                name="end_time"
                                class="form-control"
                                value="{{ old('end_time', $booking['end_time_value']) }}"
                                required
                            >
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editPurpose" class="form-label text-uppercase small fw-semibold">Purpose / agenda</label>
                        <textarea
                            id="editPurpose"
                            name="purpose"
                            rows="3"
                            class="form-control"
                            required
                        >{{ old('purpose', $booking['purpose']) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="editAttendees" class="form-label text-uppercase small fw-semibold">Attendees</label>
                        <input
                            id="editAttendees"
                            type="number"
                            name="attendees"
                            class="form-control"
                            min="1"
                            value="{{ old('attendees', $booking['attendees']) }}"
                            placeholder="How many participants?"
                        >
                    </div>
                    <div class="mb-3">
                        <label for="editNotes" class="form-label text-uppercase small fw-semibold">Special instructions</label>
                        <textarea
                            id="editNotes"
                            name="additional_notes"
                            rows="3"
                            class="form-control"
                            placeholder="Optional — remind us about equipment, support, or prep needed."
                        >{{ old('additional_notes', $booking['notes']) }}</textarea>
                    </div>
                    <p class="small text-muted mb-3">
                        Once submitted, the operations team will receive the updated request instantly.
                    </p>
                    <button type="submit" class="btn btn-primary w-100">
                        Save changes
                    </button>
                </form>
            </article>
            <article class="detail-card">
                <h3>Guidelines</h3>
                <ul class="detail-list">
                    <li>
                        <span class="label">Edit window</span>
                        <span class="value">24 hours before the booking</span>
                    </li>
                    <li>
                        <span class="label">What can be updated?</span>
                        <span class="value">Schedule, agenda, attendees, and additional notes.</span>
                    </li>
                    <li>
                        <span class="label">Need a different room?</span>
                        <span class="value">
                            Cancel this request and submit a new one via the booking wizard so we can re-check availability.
                        </span>
                    </li>
                </ul>
            </article>
        </div>
    </section>
@endsection
