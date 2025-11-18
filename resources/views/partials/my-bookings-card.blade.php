{{-- resources/views/partials/my-bookings-card.blade.php --}}
@php
    /** Basic metadata */
    $title    = $title    ?? 'My Bookings';
    $eyebrow  = $eyebrow  ?? 'Shared Services Overview';
    $subtitle = $subtitle ?? 'Track recent activity and jump back to your bookings.';

    /** CTA */
    $ctaLabel = $ctaLabel ?? 'View all bookings';
    $viewAllUrl = $viewAllUrl
        ?? (function_exists('route') && \Illuminate\Support\Facades\Route::has('user.booking.index')
            ? route('user.booking.index')
            : '#');

    /** User context */
    $user      = auth()->user();
    $userName  = $userName  ?? ($user?->name  ?? 'Charles Ramos');
    $userEmail = $userEmail ?? ($user?->email ?? 'user.charles@enc.gov');

    /** Booking data */
    $bookings = collect($bookings ?? []);
    $totalBookings = $totalBookings ?? $bookings->count();

    /** Tabs (prepend All if missing) */
    $tabs = collect($tabs ?? []);
    if ($tabs->where('key', 'all')->isEmpty()) {
        $tabs->prepend([
            'key'   => 'all',
            'label' => 'All',
            'count' => $totalBookings,
        ]);
    }
    if ($tabs->isEmpty()) {
        $tabs = collect([
            ['key' => 'all', 'label' => 'All', 'count' => $totalBookings],
        ]);
    }

    /** Status helpers */
    $statusMeta = [
        'pending'   => ['label' => 'Pending',   'class' => 'is-pending'],
        'confirmed' => ['label' => 'Confirmed', 'class' => 'is-confirmed'],
        'approved'  => ['label' => 'Approved',  'class' => 'is-confirmed'],
        'completed' => ['label' => 'Completed', 'class' => 'is-completed'],
        'cancelled' => ['label' => 'Cancelled', 'class' => 'is-cancelled'],
    ];
@endphp

<div class="card enc-my-bookings-card" data-component="enc-my-bookings">
    <div class="enc-my-bookings-card__header">
        <div class="enc-my-bookings-card__heading">
            <p class="enc-my-bookings-card__eyebrow">{{ $eyebrow }}</p>
            <h3>{{ $title }}</h3>
            @if($subtitle)
                <p class="enc-my-bookings-card__subtitle">{{ $subtitle }}</p>
            @endif
        </div>
        <a href="{{ $viewAllUrl }}" class="enc-my-bookings-card__cta" @if($viewAllUrl === '#') role="button" @endif>
            <span>{{ $ctaLabel }}</span>
            <svg width="16" height="16" viewBox="0 0 16 16" aria-hidden="true" focusable="false">
                <path d="M3.5 8h9" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M8.5 4l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>
    </div>

    <div class="enc-my-bookings-card__body">
        <div class="enc-my-bookings-card__identity">
            <div>
                <p class="enc-my-bookings-card__meta-label">Logged in as</p>
                <p class="enc-my-bookings-card__identity-name">{{ $userName }}</p>
                <p class="enc-my-bookings-card__identity-email">{{ $userEmail }}</p>
            </div>
            <div class="enc-my-bookings-card__total">
                <span>Total Bookings</span>
                <strong>{{ $totalBookings }}</strong>
            </div>
        </div>

        <div class="enc-my-bookings-card__tabs" role="tablist">
            @foreach($tabs as $index => $tab)
                @php
                    $isActive = ($index === 0);
                @endphp
                <button
                    type="button"
                    class="enc-my-bookings-card__tab {{ $isActive ? 'is-active' : '' }}"
                    data-status="{{ $tab['key'] }}"
                    role="tab"
                    aria-selected="{{ $isActive ? 'true' : 'false' }}"
                >
                    <span>{{ $tab['label'] }}</span>
                    <span class="enc-my-bookings-card__tab-pill">{{ $tab['count'] }}</span>
                </button>
            @endforeach
        </div>

        <div class="enc-bookings-list" data-role="bookings-list">
            @forelse($bookings as $booking)
                @php
                    $statusKey = strtolower($booking['status'] ?? 'pending');
                    $statusData = $statusMeta[$statusKey] ?? ['label' => ucfirst($statusKey), 'class' => 'is-pending'];
                    $statusClass = $statusData['class'];
                    $statusLabel = $booking['status_label'] ?? $statusData['label'];
                @endphp
                <article class="enc-bookings-list__item" data-status="{{ $statusKey }}" data-role="booking-item">
                    <div class="enc-bookings-list__item-header">
                        <span class="enc-booking-status {{ $statusClass }}">{{ $statusLabel }}</span>
                        <span class="enc-booking-date">{{ $booking['date'] ?? 'TBD' }}</span>
                    </div>
                    <div class="enc-bookings-list__item-body">
                        <div class="enc-bookings-list__icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <rect x="3.5" y="5" width="17" height="15" rx="3" stroke="#155DFC" stroke-width="1.6"/>
                                <path d="M3.5 10h17" stroke="#155DFC" stroke-width="1.6" stroke-linecap="round"/>
                                <path d="M9 2v4M15 2v4" stroke="#155DFC" stroke-width="1.6" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <div>
                            <p class="enc-booking-title">{{ $booking['title'] ?? 'Facility Booking' }}</p>
                            <p class="enc-booking-meta">
                                <span>{{ $booking['time'] ?? 'Time TBA' }}</span>
                                @if(!empty($booking['location']))
                                    <span aria-hidden="true">•</span>
                                    <span>{{ $booking['location'] }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </article>
            @empty
                <div class="enc-bookings-empty-message">
                    <p>You have no bookings yet. Start by exploring available rooms.</p>
                </div>
            @endforelse
        </div>

        <div class="enc-bookings-pagination" data-role="bookings-pagination" hidden>
            <button type="button" class="enc-page-btn" data-action="prev" aria-label="Previous page">‹ Prev</button>
            <span class="enc-page-label" data-role="page-label">Page 1</span>
            <button type="button" class="enc-page-btn" data-action="next" aria-label="Next page">Next ›</button>
        </div>

        <div
            class="enc-bookings-empty-message"
            data-role="empty-state"
            @if($bookings->isNotEmpty()) hidden @endif
        >
            <p>No bookings found for this filter.</p>
        </div>
    </div>
</div>
