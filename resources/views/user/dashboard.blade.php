@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
    @vite([
        'resources/css/wizard/base.css',
        'resources/css/wizard/steps.css',
        'resources/css/dashboard/dashboard.css',
    ])
@endpush


@push('scripts')
    @vite('resources/js/dashboard/dashboard.js')
@endpush

@php
    $bookingStats = $bookingStats ?? ['pending' => 0, 'confirmed' => 0, 'cancelled' => 0, 'total' => 0];
    $upcomingBookingsCards = $upcomingBookingsCards ?? [];
@endphp

@section('app-navbar')
    @include('partials.dashboard-navbar', [
        'currentStep' => 0,
        'steps' => [],
        'bookingsCount' => $bookingStats['total'] ?? 0,
        'notificationsCount' => 2,
        'userName' => auth()->user()->name ?? 'Charles Ramos',
        'userEmail' => auth()->user()->email ?? 'user.charles@enc.gov',
        'userRole' => auth()->user()->role ?? 'staff',
        'brand' => 'ONE Services',
        'bookingsPanelTarget' => '#dashboardBookingsPanel',
        'showStepper' => false,
    ])
@endsection

@section('content')
<section class="dashboard-main">
    <div class="main-content">
        <div class="dashboard-top-actions">
            <a href="{{ route('landing') }}" class="btn-back-home">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9 12H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Back to Home
            </a>
        </div>
        <div class="hero-section">
            <div class="hero-content">
            @php
            use Carbon\Carbon;

            // Get current time in Manila
            $now = Carbon::now('Asia/Manila');
            $hour = $now->hour;

            if ($hour >= 5 && $hour < 12) {
                $greeting = 'Good morning';
            } elseif ($hour >= 12 && $hour < 18) {
                $greeting = 'Good afternoon';
            } else {
                $greeting = 'Good evening';
            }
            @endphp
                <div class="hero-left">
                    <div class="greeting">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 2.5L13.9443 8.48693L20.2929 8.48693L15.1743 12.5261L17.1187 18.5131L12 14.4739L6.88128 18.5131L8.82567 12.5261L3.70706 8.48693L10.0557 8.48693L12 2.5Z" stroke="#FFF085" stroke-width="1.5" stroke-linejoin="round"/>
                        </svg>
                        {{ $greeting }},  {{ explode(' ', trim(auth()->user()->name))[0] ?? 'User' }}
                    </div>
                    <h2 class="hero-heading">Let's plan your next booking</h2>
                    <p  class="text-white-50">Plan smarter with up-to-the-minute availability across ENC facilities.</p>
                    <div class="hero-actions">
                        <a href="{{ route('facilities.catalog') }}" class="btn-browse">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M2.66675 4.66634H13.3334" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M6.6665 2V4.66667" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M9.33325 2V4.66667" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M4.6665 7.33301H11.3332" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M4.6665 10H8.6665" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M5.33325 13.333H10.6666C12.1393 13.333 13.3333 12.139 13.3333 10.6663V4.66634H2.66659V10.6663C2.66659 12.139 3.86059 13.333 5.33325 13.333Z" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Browse Rooms
                        </a>
                        <a href="{{ route('user.booking.wizard') }}#wizardMethodSection" class="btn-new-booking">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M8 3.33301V12.6663" stroke="white" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M3.33325 8H12.6666" stroke="white" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            New Booking
                        </a>
                    </div>
                </div>
                <div class="hero-stats">
                    <div class="stat-item">
                        <p class="stat-value stat-pending">{{ $bookingStats['pending'] ?? 0 }}</p>
                        <p class="stat-label">Pending approvals</p>
                    </div>
                    <div class="stat-item">
                        <p class="stat-value stat-confirmed">{{ $bookingStats['confirmed'] ?? 0 }}</p>
                        <p class="stat-label">Confirmed today</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-grid">
            <div class="d-flex flex-column gap-3">
                {{-- Global calendar --}}
                <div class="card p-0 overflow-hidden">
                    @php
                        $now = \Carbon\Carbon::now('Asia/Manila');
                        $startOfMonth = $now->copy()->startOfMonth();
                        $daysInMonth = $now->daysInMonth;
                        $startWeekday = ($startOfMonth->dayOfWeekIso - 1); // 0-based
                        $calendarDays = [];
                        for ($i = 0; $i < $startWeekday; $i++) { $calendarDays[] = null; }
                        for ($d = 1; $d <= $daysInMonth; $d++) { $calendarDays[] = $now->copy()->day($d); }
                        $weekdays = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
                        $events = array_slice($upcomingBookingsCards ?? [], 0, 8);
                        $eventsByDay = collect($events)->groupBy(function($item) {
                            return \Carbon\Carbon::parse($item['date'])->day;
                        });
                    @endphp
                    <div class="enc-my-bookings-card__header">
                        <div class="enc-my-bookings-card__heading">
                            <p class="enc-my-bookings-card__eyebrow mb-1">Global calendar</p>
                            <h3 class="enc-my-bookings-card__title mb-0">All rooms in one view</h3>
                            <p class="enc-my-bookings-card__subtitle mb-0">Quick preview of {{ $now->format('F Y') }}. Open the full calendar for details.</p>
                        </div>
                        <a href="{{ route('admin.calendar') }}" class="enc-my-bookings-card__cta">Open calendar</a>
                    </div>
                    <div class="p-3 p-md-4">
                        <div class="wizard-calendar">
                            <div class="wizard-calendar-nav d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-semibold text-primary">{{ $now->format('F Y') }}</span>
                                <span class="text-muted small">Today: {{ $now->format('M j') }}</span>
                            </div>
                            <div class="wizard-calendar-daynames">
                                @foreach($weekdays as $day)
                                    <span>{{ $day }}</span>
                                @endforeach
                            </div>
                            <div class="wizard-calendar-grid">
                                @foreach($calendarDays as $day)
                                    @if(!$day)
                                        <button class="wizard-calendar-day is-muted" type="button" disabled></button>
                                    @else
                                        @php $dayEvents = $eventsByDay->get($day->day, collect()); @endphp
                                        <button class="wizard-calendar-day {{ $day->isToday() ? 'is-today' : '' }}" type="button">
                                            <span class="wizard-calendar-daynumber">{{ $day->day }}</span>
                                            @foreach($dayEvents as $ev)
                                                @php
                                                    $pillClass = 'mini-calendar__pill';
                                                    $status = strtolower($ev['status'] ?? '');
                                                    if (str_contains($status, 'maintenance')) $pillClass .= ' is-maint';
                                                    elseif (str_contains($status, 'occupied') || str_contains($status, 'approved') || str_contains($status, 'confirmed')) $pillClass .= ' is-occupied';
                                                    elseif (str_contains($status, 'pending')) $pillClass .= ' is-limited';
                                                    else $pillClass .= ' is-available';
                                                @endphp
                                                <span class="{{ $pillClass }} d-block mt-1" title="{{ $ev['facility'] }} · {{ $ev['time'] ?? '' }}">
                                                    {{ $ev['facility'] }}
                                                </span>
                                            @endforeach
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick actions and resources --}}
                <div class="card">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <p class="text-uppercase small text-muted mb-1">Quick actions</p>
                            <h3 class="mb-0">Get things done faster</h3>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <a href="{{ route('user.booking.wizard') }}" class="btn btn-primary btn-sm">Start a booking</a>
                        <a href="{{ route('facilities.catalog') }}" class="btn btn-outline-primary btn-sm">Explore facilities</a>
                        <a href="{{ route('faq') }}" class="btn btn-outline-secondary btn-sm">FAQ & Policies</a>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 border bg-light h-100">
                                <p class="text-uppercase small text-muted mb-1">Need help?</p>
                                <p class="mb-1 fw-semibold">Call concierge</p>
                                <p class="text-muted small mb-0">We’ll help you find a room or update a request.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 border bg-light h-100">
                                <p class="text-uppercase small text-muted mb-1">Approvals</p>
                                <p class="mb-1 fw-semibold">Track status</p>
                                <p class="text-muted small mb-0">Check pending requests and next steps.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <section id="dashboardBookingsPanel" class="dashboard-bookings-panel is-visible" data-panel-state="visible" aria-live="polite">
                @if(!empty($dashboardBookings))
                    @include('partials.my-bookings-card', $dashboardBookings)
                @endif
            </section>
        </div>
    </div>
</section>
@endsection
