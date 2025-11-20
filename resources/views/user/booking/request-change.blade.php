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
                <span class="status-pill is-warning">Change requested</span>
                <h1 class="booking-hero-title">Respond to Shared Services</h1>
                <p class="booking-hero-subtitle">
                    Review the notes below, adjust the schedule if needed, or leave a message for the operations team. Finish the change or cancel the booking from here.
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

        @if($adminRequest)
            <div class="booking-callout booking-callout--warning mb-4">
                <div>
                    <h3 class="mb-1">Shared Services needs an update</h3>
                    <p class="mb-1">{{ $adminRequest['notes'] ?? 'Please review the scheduling details.' }}</p>
                    @if($adminRequest['opened_at'])
                        <p class="small text-muted mb-0">Requested {{ $adminRequest['opened_at'] }}</p>
                    @endif
                </div>
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
                <h3>{{ $canEditBooking ? 'Update booking' : 'Booking overview' }}</h3>
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
                            @disabled(!$canEditBooking)
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
                                @disabled(!$canEditBooking)
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
                                @disabled(!$canEditBooking)
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
                            @disabled(!$canEditBooking)
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
                            @disabled(!$canEditBooking)
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
                            @disabled(!$canEditBooking)
                        >{{ old('additional_notes', $booking['notes']) }}</textarea>
                    </div>
                    <p class="small text-muted mb-3">
                        @if($canEditBooking)
                            Updating will send the revised schedule back to Shared Services.
                        @else
                            This booking can’t be edited right now. Leave a note to request admin assistance.
                        @endif
                    </p>
                    <button type="submit" class="btn btn-primary w-100" @disabled(!$canEditBooking)>
                        Finish change
                    </button>
                </form>
                <form action="{{ route('user.booking.cancel', $booking['id']) }}" method="POST" class="mt-3">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100">
                        Cancel booking
                    </button>
                </form>
            </article>
            <article class="detail-card">
                <h3>Send additional context</h3>
                @if(!$existingRequest)
                    <form action="{{ route('user.booking.request-change.store', $booking['id']) }}" method="POST" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label for="changeNotes" class="form-label text-uppercase small fw-semibold">Message to Shared Services</label>
                            <textarea
                                id="changeNotes"
                                name="notes"
                                rows="5"
                                class="form-control"
                                placeholder="Explain what needs to be adjusted or forfeited."
                                required
                            >{{ old('notes') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-outline-primary w-100">
                            Send request
                        </button>
                    </form>
                @else
                    <div class="alert alert-info mb-0" role="alert">
                        <strong>Request submitted.</strong>
                        <p class="mb-1">Status: {{ ucfirst($existingRequest['status']) }}</p>
                        <p class="mb-0">{{ $existingRequest['notes'] }}</p>
                        @if($existingRequest['opened_at'])
                            <p class="small text-muted mb-0">Sent {{ $existingRequest['opened_at'] }}</p>
                        @endif
                    </div>
                @endif
            </article>
        </div>
    </section>
@endsection
