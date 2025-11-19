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
        'notificationsCount' => $notificationsCount,
        'userName' => auth()->user()->name ?? 'User',
        'userEmail' => auth()->user()->email ?? 'user@ministry.gov',
        'userRole' => auth()->user()->role ?? 'staff',
        'brand' => 'ONE Services',
        'showStepper' => false,
        'showBookingsToggle' => false,
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

        @if(session('statusMessage'))
            <div class="alert alert-success mb-3" role="alert">
                {{ session('statusMessage') }}
            </div>
        @endif

        @if(($actions['can_edit'] ?? false) || ($actions['can_request_change'] ?? false))
            <div class="booking-detail-actions">
                @if($actions['can_edit'])
                    <a href="{{ $actions['edit_url'] }}" class="btn btn-primary">
                        Edit booking
                    </a>
                @endif
                @if($actions['can_request_change'])
                    <a href="{{ $actions['request_change_url'] }}" class="btn btn-outline-primary">
                        Request change
                    </a>
                @endif
            </div>
        @endif

        @if($changeRequest)
            <div id="change-request" class="booking-callout booking-callout--warning">
                <div>
                    <h3>Shared Services needs an update</h3>
                    <p class="mb-1">{{ $changeRequest['notes'] ?? 'Please review your booking details.' }}</p>
                    @if($changeRequest['opened_at'])
                        <p class="small text-muted mb-0">Requested {{ $changeRequest['opened_at'] }}</p>
                    @endif
                </div>
                <form action="{{ route('user.booking.change-request.acknowledge', $changeRequest['id']) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-light">
                        Mark as reviewed
                    </button>
                </form>
            </div>
        @elseif($userChangeRequest)
            <div class="booking-callout booking-callout--info">
                <div>
                    <h3>Change request in review</h3>
                    <p class="mb-1">{{ $userChangeRequest['notes'] ?? 'Awaiting admin response.' }}</p>
                    @if($userChangeRequest['opened_at'])
                        <p class="small text-muted mb-0">Sent {{ $userChangeRequest['opened_at'] }}</p>
                    @endif
                </div>
            </div>
        @endif

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
