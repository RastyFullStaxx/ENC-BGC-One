@php
    use Carbon\Carbon;
    $now = $now ?? Carbon::now('Asia/Manila');
    $calendarDays = $calendarDays ?? [];
    $weekdays = $weekdays ?? ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
@endphp

<div class="card p-0 overflow-hidden">
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
                <a class="wizard-calendar-nav-btn" href="{{ route('user.dashboard', ['month' => $prevMonth, 'calendar_scope' => $calendarScope]) }}" aria-label="Previous month">‹</a>
                <div class="wizard-calendar-month text-center">{{ $calendarMonth->format('F Y') }}</div>
                <a class="wizard-calendar-nav-btn" href="{{ route('user.dashboard', ['month' => $nextMonth, 'calendar_scope' => $calendarScope]) }}" aria-label="Next month">›</a>
            </div>
            <div class="wizard-calendar-scope">
                <span class="wizard-calendar-scope__label">Showing</span>
                <div class="wizard-calendar-scope__toggle" role="group" aria-label="Calendar scope">
                    <a
                        class="wizard-calendar-scope__btn {{ $calendarScope === 'mine' ? 'is-active' : '' }}"
                        href="{{ route('user.dashboard', ['month' => $calendarMonth->format('Y-m-01'), 'calendar_scope' => 'mine']) }}"
                    >
                        My bookings
                    </a>
                    <a
                        class="wizard-calendar-scope__btn {{ $calendarScope === 'global' ? 'is-active' : '' }}"
                        href="{{ route('user.dashboard', ['month' => $calendarMonth->format('Y-m-01'), 'calendar_scope' => 'global']) }}"
                    >
                        All bookings
                    </a>
                </div>
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
                                @php
                                    $pillPalette = ['variant-mint','variant-sand','variant-lilac','variant-sky','variant-rose','variant-lemon'];
                                    $pillClass = $pillPalette[$loop->index % count($pillPalette)];
                                @endphp
                                <div
                                    class="calendar-event-pill {{ $pillClass }}"
                                    title="{{ $ev['facility'] }} · {{ $ev['time'] ?? '' }}"
                                    data-facility="{{ $ev['facility'] }}"
                                    data-title="{{ $ev['title'] }}"
                                    data-date="{{ $ev['date_display'] ?? '' }}"
                                    data-time="{{ $ev['time'] ?? '' }}"
                                    data-requester="{{ $ev['requester'] ?? '' }}"
                                    tabindex="0"
                                    role="button"
                                    aria-label="{{ $ev['title'] ?? 'Booking' }} in {{ $ev['facility'] }}"
                                >
                                    <strong>{{ $ev['facility'] }}</strong>
                                    @if(!empty($ev['title']))
                                        <span class="d-block small text-muted">{{ $ev['title'] }}</span>
                                    @endif
                                    @if(!empty($ev['time']))
                                        <span class="d-block small text-muted">{{ $ev['time'] }}</span>
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
