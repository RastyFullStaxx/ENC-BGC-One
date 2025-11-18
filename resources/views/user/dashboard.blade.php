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
                        for ($d = 1; $d <= $daysInMonth; $d++) { $calendarDays[] = $calendarMonth->copy()->day($d); }
                        $weekdays = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
                    @endphp
                    <div class="enc-my-bookings-card__header">
                        <div class="enc-my-bookings-card__heading">
                            <p class="enc-my-bookings-card__eyebrow mb-1">Global calendar</p>
                            <h3 class="enc-my-bookings-card__title mb-0">All rooms in one view</h3>
                            <p class="enc-my-bookings-card__subtitle mb-0">Quick preview of {{ $now->format('F Y') }}. Open the full calendar for details.</p>
                        </div>
                        
                    </div>
                    <div class="p-3 p-md-4">
                        <div class="wizard-calendar">
                            <div class="wizard-calendar-header">
                                <a class="wizard-calendar-nav-btn" href="{{ route('user.dashboard', ['month' => $prevMonth]) }}" aria-label="Previous month">‹</a>
                                <div class="wizard-calendar-month text-center">{{ $calendarMonth->format('F Y') }}</div>
                                <a class="wizard-calendar-nav-btn" href="{{ route('user.dashboard', ['month' => $nextMonth]) }}" aria-label="Next month">›</a>
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
                                        @php $dayEvents = ($calendarEvents[$day->day] ?? collect()); @endphp
                                        <button class="wizard-calendar-day {{ $day->isToday() ? 'is-today' : '' }}" type="button">
                                            <span class="wizard-calendar-daynumber">{{ $day->day }}</span>
                                            @foreach($dayEvents as $ev)
                                                <div class="calendar-event-pill" title="{{ $ev['facility'] }} · {{ $ev['time'] ?? '' }}">
                                                    <strong>{{ $ev['facility'] }}</strong>
                                                    @if(!empty($ev['title']))
                                                        <span class="d-block small text-muted">{{ $ev['title'] }}</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                @php
                    $favoriteRooms = ($favoriteRooms ?? null) ?: [
                        [
                            'name' => 'Conference Room A-301',
                            'capacity' => '12 seats',
                            'location' => 'Tower A · 3F',
                            'status' => 'Available now',
                            'tone' => 'success',
                            'image' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?q=80&w=1200&auto=format&fit=crop'
                        ],
                        [
                            'name' => 'Innovation Lab C-401',
                            'capacity' => '24 seats',
                            'location' => 'Tower C · 4F',
                            'status' => 'Limited slots',
                            'tone' => 'warning',
                            'image' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?q=80&w=1200&auto=format&fit=crop'
                        ],
                        [
                            'name' => 'Huddle Room B-215',
                            'capacity' => '6 seats',
                            'location' => 'Tower B · 2F',
                            'status' => 'Ready in 5 min',
                            'tone' => 'info',
                            'image' => 'https://images.unsplash.com/photo-1523419400524-fc1e0aba7895?q=80&w=1200&auto=format&fit=crop'
                        ],
                    ];

                    $whatsNew = ($whatsNew ?? null) ?: [
                        ['title' => 'Innovation Lab now supports livestream kits', 'date' => 'Updated today', 'tag' => 'Update', 'detail' => 'AV concierge can pre-stage cameras.'],
                        ['title' => 'Townhall Studio maintenance on Nov 24', 'date' => 'Notice · 2 days', 'tag' => 'Maintenance', 'detail' => 'Lighting will be recalibrated 8–11 AM.'],
                        ['title' => 'New auto-checklist for VIP visits', 'date' => 'New', 'tag' => 'New', 'detail' => 'Auto-shares prep steps to hosts.'],
                    ];

                    $toolStrip = ($toolStrip ?? null) ?: [
                        ['label' => 'Start a booking', 'href' => route('user.booking.wizard'), 'icon' => 'calendar'],
                        ['label' => 'View approvals', 'href' => route('user.booking.index'), 'icon' => 'check'],
                        ['label' => 'Facilities catalog', 'href' => route('facilities.catalog'), 'icon' => 'map'],
                        ['label' => 'Support & FAQ', 'href' => route('faq'), 'icon' => 'help'],
                    ];
                @endphp

                {{-- Favorites --}}
                <div class="card favorites-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <p class="text-uppercase small mb-1">Favorite rooms</p>
                            <h3 class="mb-0">Book again in one tap</h3>
                        </div>
                    </div>
                    <div class="favorites-grid">
                        @foreach($favoriteRooms as $room)
                            <article class="favorite-room-card">
                                <div class="favorite-room-thumb" style="background-image: url('{{ $room['image'] ?? 'https://images.unsplash.com/photo-1523419400524-fc1e0aba7895?q=80&w=1200&auto=format&fit=crop' }}');">
                                    <span class="favorite-room-status favorite-room-status--{{ $room['tone'] ?? 'neutral' }}">{{ $room['status'] }}</span>
                                </div>
                                <div class="favorite-room-content">
                                    <p class="favorite-room-name mb-1">{{ $room['name'] }}</p>
                                    <p class="favorite-room-meta mb-2">{{ $room['capacity'] }} · {{ $room['location'] }}</p>
                                    <div class="favorite-room-actions">
                                        <a href="{{ route('user.booking.wizard') }}#wizardMethodSection" class="favorite-btn favorite-btn--primary">Rebook</a>
                                        <a href="{{ route('facilities.catalog') }}" class="favorite-btn favorite-btn--ghost">Details</a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
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

<section class="announcements-shell">
    <div class="card announcements-card">
        <div class="enc-my-bookings-card__header announcements-header">
            <div class="enc-my-bookings-card__heading">
                <p class="enc-my-bookings-card__eyebrow mb-1">Announcements</p>
                <h3 class="enc-my-bookings-card__title mb-0">Latest highlights</h3>
            </div>
        </div>
        <div class="announcements-body">
            <ul class="list-unstyled announcements-list mb-0">
                @foreach(($announcements ?? []) as $item)
                    <li class="announcements-item">
                        <div>
                            <p class="mb-1 fw-semibold">{{ $item['title'] }}</p>
                            <p class="mb-0 text-muted small">{{ $item['date'] }}</p>
                            @if(!empty($item['summary']))
                                <p class="mb-0 text-muted small">{{ $item['summary'] }}</p>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const list = document.querySelector('[data-role="bookings-list"]');
    const items = list ? Array.from(list.querySelectorAll('[data-role="booking-item"]')) : [];
    const pager = document.querySelector('[data-role="bookings-pagination"]');
    if (!list || !pager) return;

    const pageLabel = pager.querySelector('[data-role="page-label"]');
    const prevBtn = pager.querySelector('[data-action="prev"]');
    const nextBtn = pager.querySelector('[data-action="next"]');
    const pageSize = 5;
    const totalPages = Math.max(1, Math.ceil(items.length / pageSize));
    let current = 0;

    if (totalPages <= 1) {
        pager.setAttribute('hidden', '');
        return;
    }
    pager.removeAttribute('hidden');

    function render(page) {
        current = Math.min(Math.max(page, 0), totalPages - 1);
        const start = current * pageSize;
        const end = start + pageSize;
        items.forEach((item, idx) => {
            item.hidden = !(idx >= start && idx < end);
        });
        pageLabel.textContent = `Page ${current + 1} of ${totalPages}`;
        prevBtn.disabled = current === 0;
        nextBtn.disabled = current >= totalPages - 1;
    }

    prevBtn?.addEventListener('click', () => render(current - 1));
    nextBtn?.addEventListener('click', () => render(current + 1));

    render(0);
});
</script>
@endpush
@endsection
