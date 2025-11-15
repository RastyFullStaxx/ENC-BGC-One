@extends('layouts.app')

@section('title', 'Booking Details')

@push('styles')
    @vite([
        'resources/css/wizard/base.css',
        'resources/css/bookings/index.css',
        'resources/css/bookings/show.css',
    ])
@endpush

@php
    $summary = $bookingSummary;
    $facility = $summary['facility'] ?? [];
    $sfi = $summary['sfi_support'] ?? ['enabled' => false];
@endphp

@section('content')
    @include('partials.dashboard-navbar', [
        'currentStep' => 0,
        'steps' => [],
        'bookingsCount' => $bookingsCount ?? 0,
        'notificationsCount' => 2,
        'userName' => auth()->user()->name ?? 'User',
        'userEmail' => auth()->user()->email ?? 'user@ministry.gov',
        'userRole' => auth()->user()->role ?? 'staff',
        'brand' => 'ONE Services',
        'showStepper' => false,
    ])

    <section class="booking-detail-shell">
        <a href="{{ route('user.booking.index') }}" class="bookings-back-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Back to My Bookings
        </a>

        <header class="booking-detail-hero">
            <div class="booking-hero-content">
                <span class="status-pill {{ $summary['status_tone'] ?? 'is-neutral' }}">
                    {{ $summary['status_label'] ?? 'Pending' }}
                </span>
                <h1 class="booking-hero-title">{{ $facility['name'] ?? 'Facility' }}</h1>
                <p class="booking-hero-subtitle">
                    {{ $summary['purpose'] ?? 'This booking request has no description yet.' }}
                </p>
            </div>
            <div class="booking-meta-grid">
                <dl>
                    <dt>Reference</dt>
                    <dd>{{ $summary['reference'] ?? 'N/A' }}</dd>
                    <dt>Date</dt>
                    <dd>{{ $summary['date_label'] ?? 'To be announced' }}</dd>
                    <dt>Time</dt>
                    <dd>{{ $summary['time_range'] ?? 'TBA' }}</dd>
                    @if(!empty($summary['duration']))
                        <dt>Duration</dt>
                        <dd>{{ $summary['duration'] }}</dd>
                    @endif
                    <dt>Requested</dt>
                    <dd>{{ $summary['created_at'] ?? '—' }}</dd>
                </dl>
            </div>
        </header>

        <div class="booking-detail-grid">
            <article class="detail-card">
                <h3>Schedule</h3>
                <ul class="detail-list">
                    <li>
                        <span class="label">Date</span>
                        <span class="value">{{ $summary['date_label'] ?? 'TBA' }}</span>
                    </li>
                    <li>
                        <span class="label">Time</span>
                        <span class="value">{{ $summary['time_range'] ?? 'TBA' }}</span>
                    </li>
                    <li>
                        <span class="label">Location</span>
                        <span class="value">{{ $facility['location'] ?? 'To be assigned' }}</span>
                    </li>
                    @if(!empty($facility['capacity']))
                        <li>
                            <span class="label">Capacity</span>
                            <span class="value">Up to {{ $facility['capacity'] }} guests</span>
                        </li>
                    @endif
                </ul>
            </article>

            <article class="detail-card">
                <h3>Meeting Details</h3>
                <ul class="detail-list">
                    <li>
                        <span class="label">Purpose / Agenda</span>
                        <span class="value">{{ $summary['purpose'] ?? 'No agenda provided' }}</span>
                    </li>
                    <li>
                        <span class="label">Attendees</span>
                        <span class="value">
                            @if(!empty($summary['attendees']))
                                {{ $summary['attendees'] }} people expected
                            @else
                                Not specified
                            @endif
                        </span>
                    </li>
                    <li>
                        <span class="label">Special instructions</span>
                        <span class="value">
                            {{ $summary['notes'] ?? 'No additional notes' }}
                        </span>
                    </li>
                </ul>
            </article>

            <article class="detail-card">
                <h3>Support & Resources</h3>
                <p class="mb-1">Shared Facilities Support</p>
                @if($sfi['enabled'] ?? false)
                    <div class="support-chips">
                        <span class="support-chip">{{ ($sfi['count'] ?? 1) . ' staff' }}</span>
                    </div>
                @else
                    <p class="text-muted mb-2">No SFI manpower requested.</p>
                @endif

                <p class="mt-3 mb-1">Equipment</p>
                @if(!empty($summary['equipment']) && count($summary['equipment']))
                    <ul class="equipment-list">
                        @foreach($summary['equipment'] as $item)
                            <li>{{ $item['name'] }} <span aria-hidden="true">×</span> {{ $item['quantity'] }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted mb-0">No extra equipment reserved.</p>
                @endif
            </article>
        </div>
    </section>
@endsection
