{{-- resources/views/facilities/catalog.blade.php --}}
@extends('layouts.app')

@section('title', 'Facility Catalog — ENC BGC One')

@push('styles')
  @vite([
    'resources/css/facility/catalog.css',
    'resources/css/wizard/steps.css',
  ])
@endpush

@php
  $facilityRooms = [
    [
      'id' => 'enc-a301',
      'name' => 'Conference Room A-301',
      'short_label' => 'Room A-301',
      'image' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?q=80&w=1600&auto=format&fit=crop',
      'status' => [
        'label' => 'Available now',
        'variant' => 'success',
        'copy' => 'Open until 11:00 AM · Daily reset at 4:30 PM',
      ],
      'capacity' => 12,
      'capacity_label' => '12 seats',
      'layout' => 'Boardroom · VC ready',
      'location' => 'ENC Tower A · 3rd Floor',
      'size' => 'Medium',
      'prep_time' => '10 mins prep',
      'description' => 'Bright corner room ideal for leadership syncs, client reviews, and hybrid pitches. Dual displays keep both in-room and remote teams aligned.',
      'amenities' => ['Dual 75" display', 'Poly Studio X70', 'Wireless screen share', 'Writable glass wall', 'Acoustic ceiling', 'Mini pantry access'],
      'highlights' => [
        'Dedicated concierge support during peak hours',
        'Auto-dims blinds for video confidence',
        'Integrates with Teams / Zoom with a single tap',
      ],
      'timeline' => [
        ['label' => 'Current Status', 'copy' => 'Clear for booking until 11:00 AM'],
        ['label' => 'Hold Notice', 'copy' => 'Leadership sync blocked 1:30 PM – 3:00 PM'],
        ['label' => 'Care Window', 'copy' => 'Daily reset from 4:30 PM'],
      ],
      'tags' => ['Boardroom', 'Dual display', 'Hybrid ready'],
      'support_contact' => 'mailto:facilities@enc.com?subject=Facility%20Support%20-%20Room%20A-301',
      'bookings_today' => 2,
      'availability' => [
        '2025-03-20' => ['state' => 'available', 'copy' => 'Wide open 8:00 AM – 5:00 PM'],
        '2025-03-21' => ['state' => 'limited', 'copy' => 'Free before 1:30 PM'],
        '2025-03-22' => ['state' => 'occupied', 'copy' => 'Leadership sync all day'],
      ],
    ],
    [
      'id' => 'enc-b215',
      'name' => 'Huddle Room B-215',
      'short_label' => 'Room B-215',
      'image' => 'https://images.unsplash.com/photo-1524758631624-e2822e304c36?q=80&w=1600&auto=format&fit=crop',
      'status' => [
        'label' => 'Ready in 5 min',
        'variant' => 'success',
        'copy' => 'Last booking wrapping up · Auto clean queued',
      ],
      'capacity' => 6,
      'capacity_label' => '6 seats',
      'layout' => 'Informal · Corner sofa + table',
      'location' => 'ENC Tower B · 2nd Floor',
      'size' => 'Small',
      'prep_time' => '5 mins prep',
      'description' => 'Perfect for design reviews and quick stand-ups. Built-in writable wall and compact camera makes hybrid catch-ups painless.',
      'amenities' => ['4K wide-angle camera', 'Writable wall', '60" display', 'USB-C + HDMI', 'Mood lighting', 'Privacy acoustic panels'],
      'highlights' => [
        'Focus lighting presets let you switch from ideation to presentation mode',
        'Wall-to-wall acoustic panels keep conversations private',
      ],
      'timeline' => [
        ['label' => 'Current Status', 'copy' => 'Hand-off ongoing · Sanitizing until 9:05 AM'],
        ['label' => 'Next Booking', 'copy' => 'Product Design at 11:00 AM'],
        ['label' => 'Buffer', 'copy' => '15-min buffer reserved every 2 hours'],
      ],
      'tags' => ['Focus', 'Writable wall', 'USB-C'],
      'support_contact' => 'mailto:facilities@enc.com?subject=Facility%20Support%20-%20Room%20B-215',
      'bookings_today' => 1,
      'availability' => [
        '2025-03-20' => ['state' => 'available', 'copy' => 'Available all day'],
        '2025-03-21' => ['state' => 'available', 'copy' => 'Wide open for huddles'],
        '2025-03-22' => ['state' => 'limited', 'copy' => 'Reserved after 3:00 PM'],
      ],
    ],
    [
      'id' => 'enc-c401',
      'name' => 'Innovation Lab C-401',
      'short_label' => 'Innovation Lab',
      'image' => 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?q=80&w=1600&auto=format&fit=crop',
      'status' => [
        'label' => 'Limited slots',
        'variant' => 'warning',
        'copy' => 'Hold placed 2:00 PM – 6:00 PM for Hack Week',
      ],
      'capacity' => 24,
      'capacity_label' => '24 seats',
      'layout' => 'Modular · Workshop pods',
      'location' => 'ENC Tower C · 4th Floor',
      'size' => 'Large',
      'prep_time' => '25 mins prep',
      'description' => 'Split the room into pod clusters or clear the floor for demos. Includes ceiling rig for livestreams and lockable storage for prototypes.',
      'amenities' => ['Modular desks', 'Ceiling rig for AV', '4 movable displays', 'Floor power rails', 'Equipment lockers', 'On-call tech'],
      'highlights' => [
        'Room presets for workshop / gallery / hackathon modes',
        'Integrated AV booth for light livestream setups',
        'Extended booking support up to 10 hours',
      ],
      'timeline' => [
        ['label' => 'Morning Window', 'copy' => 'Open 8:00 AM – 12:30 PM'],
        ['label' => 'Afternoon Hold', 'copy' => 'Hack Week build in progress 2:00 PM – 6:00 PM'],
        ['label' => 'Reset', 'copy' => 'Full reset 6:30 PM – 7:00 PM'],
      ],
      'tags' => ['Workshop', 'Livestream', 'Modular'],
      'support_contact' => 'mailto:facilities@enc.com?subject=Facility%20Support%20-%20Innovation%20Lab%20C-401',
      'bookings_today' => 3,
      'availability' => [
        '2025-03-20' => ['state' => 'available', 'copy' => 'Free before 1:00 PM'],
        '2025-03-21' => ['state' => 'hold', 'copy' => 'Hack Week install 12:00 PM onwards'],
        '2025-03-22' => ['state' => 'occupied', 'copy' => 'Reserved for demos'],
      ],
    ],
    [
      'id' => 'enc-d510',
      'name' => 'Strategy Forum D-510',
      'short_label' => 'Forum D-510',
      'image' => 'https://images.unsplash.com/photo-1545239351-1141bd82e8a6?q=80&w=1600&auto=format&fit=crop',
      'status' => [
        'label' => 'Fully booked',
        'variant' => 'danger',
        'copy' => 'Townhall rehearsal 8:00 AM – 5:00 PM',
      ],
      'capacity' => 30,
      'capacity_label' => '30 seats',
      'layout' => 'Arena · Tiered seating',
      'location' => 'ENC Tower D · 5th Floor',
      'size' => 'Large',
      'prep_time' => '30 mins prep',
      'description' => 'Tiered seating with spotlight-ready lighting and premium audio. Ideal for trainings, leadership briefings, and investor updates.',
      'amenities' => ['140" LED wall', 'Broadcast audio rack', 'Stage lighting presets', 'Confidence monitors', 'Side lounge', 'Dedicated AV concierge'],
      'highlights' => [
        'Simultaneous interpretation booth on standby',
        'Auto-ingest recordings to your OneDrive',
      ],
      'timeline' => [
        ['label' => 'Today', 'copy' => 'Townhall rehearsal (full day)'],
        ['label' => 'Next Opening', 'copy' => 'Tomorrow 8:00 AM onwards'],
        ['label' => 'Prep Reminder', 'copy' => 'Book 48 hrs in advance for AV concierge'],
      ],
      'tags' => ['Townhall', 'AV concierge', 'Tiered seating'],
      'support_contact' => 'mailto:facilities@enc.com?subject=Facility%20Support%20-%20Strategy%20Forum%20D-510',
      'bookings_today' => 4,
      'availability' => [
        '2025-03-20' => ['state' => 'occupied', 'copy' => 'Townhall rehearsals all day'],
        '2025-03-21' => ['state' => 'occupied', 'copy' => 'All-hands prep 7:00 AM – 6:00 PM'],
        '2025-03-22' => ['state' => 'hold', 'copy' => 'Pending leadership approval'],
      ],
    ],
    [
      'id' => 'enc-lobby',
      'name' => 'Townhall Studio L-01',
      'short_label' => 'Studio L-01',
      'image' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?q=80&w=1600&auto=format&fit=crop',
      'status' => [
        'label' => 'Plan ahead',
        'variant' => 'info',
        'copy' => 'Great for all-hands and launches · Requires approvals',
      ],
      'capacity' => 80,
      'capacity_label' => '80 seats',
      'layout' => 'Bleachers + standing room',
      'location' => 'Lobby Pavilion',
      'size' => 'XL',
      'prep_time' => '45 mins prep',
      'description' => 'Immersive lobby studio with LED wall wrap, ceiling wash lights, and broadcast audio. Use it for demos, launches, or people celebrations.',
      'amenities' => ['360° lighting rig', '3-camera capture', 'Stage risers', 'Green room', 'Backline storage', 'Dedicated safety marshal'],
      'highlights' => [
        'Pair with shuttle ops for guest arrivals',
        'Safety marshal and ushering crew on standby',
      ],
      'timeline' => [
        ['label' => 'Lead Time', 'copy' => 'Minimum 5 days for layout + safety approvals'],
        ['label' => 'Dry Run', 'copy' => 'Recommended slot the day before show'],
        ['label' => 'Wrap Down', 'copy' => 'Crew teardown 2 hours post-event'],
      ],
      'tags' => ['Launch', 'Livestream', 'Large'],
      'support_contact' => 'mailto:facilities@enc.com?subject=Facility%20Support%20-%20Townhall%20Studio%20L-01',
      'bookings_today' => 5,
      'availability' => [
        '2025-03-20' => ['state' => 'hold', 'copy' => 'Safety walkthrough 9:00 AM'],
        '2025-03-21' => ['state' => 'limited', 'copy' => 'Partial setup 3:00 PM onwards'],
        '2025-03-22' => ['state' => 'occupied', 'copy' => 'Product launch 10:00 AM'],
      ],
    ],
    [
      'id' => 'enc-westhub',
      'name' => 'Training Hub West-212',
      'short_label' => 'Training Hub',
      'image' => 'https://images.unsplash.com/photo-1489515217757-5fd1be406fef?q=80&w=1600&auto=format&fit=crop',
      'status' => [
        'label' => 'Available this afternoon',
        'variant' => 'success',
        'copy' => 'Free 1:00 PM – 6:00 PM',
      ],
      'capacity' => 18,
      'capacity_label' => '18 seats',
      'layout' => 'Classroom + breakout',
      'location' => 'Training Wing · 2nd Floor',
      'size' => 'Medium',
      'prep_time' => '20 mins prep',
      'description' => 'Designed for enablement sessions with fast-switch breakouts. Comes with three movable dividers and ceiling microphones.',
      'amenities' => ['Mobile dividers', 'Ceiling mics', '4k touch display', 'Charging drawers', 'Dedicated whiteboards', 'Snack rail'],
      'highlights' => [
        'Switch between lecture and breakout in under 3 minutes',
        'Preset camera angles for instructor + class',
      ],
      'timeline' => [
        ['label' => 'Morning', 'copy' => 'Compliance refresh 8:00 AM – 11:30 AM'],
        ['label' => 'Buffer', 'copy' => 'Maintenance sweep 11:30 AM – 12:30 PM'],
        ['label' => 'Afternoon', 'copy' => 'Open slots 1:00 PM – 6:00 PM'],
      ],
      'tags' => ['Training', 'Breakout', 'Ceiling mics'],
      'support_contact' => 'mailto:facilities@enc.com?subject=Facility%20Support%20-%20Training%20Hub%20West-212',
      'bookings_today' => 2,
      'availability' => [
        '2025-03-20' => ['state' => 'available', 'copy' => 'Wide open after lunch'],
        '2025-03-21' => ['state' => 'available', 'copy' => 'Ready for enablement sessions'],
        '2025-03-22' => ['state' => 'limited', 'copy' => 'Blocked 9:00 AM – 12:00 PM'],
      ],
    ],
  ];

  $statusRank = [
    'success' => 0,
    'warning' => 1,
    'info'    => 2,
    'danger'  => 3,
  ];

  usort($facilityRooms, function ($a, $b) use ($statusRank) {
    $statusDiff = ($statusRank[$a['status']['variant']] ?? 99) <=> ($statusRank[$b['status']['variant']] ?? 99);
    if ($statusDiff !== 0) {
      return $statusDiff;
    }

    $bookingDiff = ($a['bookings_today'] ?? 99) <=> ($b['bookings_today'] ?? 99);
    if ($bookingDiff !== 0) {
      return $bookingDiff;
    }

    return strcmp($a['name'], $b['name']);
  });

  $availableRooms = array_reduce($facilityRooms, function ($carry, $room) {
    return $carry + ($room['status']['variant'] === 'success' ? 1 : 0);
  }, 0);

  $totalSeats = array_reduce($facilityRooms, function ($carry, $room) {
    return $carry + $room['capacity'];
  }, 0);

  $activeFacility = $facilityRooms[0] ?? null;
@endphp

@push('scripts')
  <script>
    window.facilityCatalogData = @json($facilityRooms);
  </script>
  @vite(['resources/js/facility-catalog.js'])
@endpush

@section('content')
  <div class="facility-catalog-page">
    <section class="facility-hero py-5">
      <div class="container">
        <div class="row align-items-center g-4">
          <div class="col-lg-6">
            <span class="hero-pill">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M4 12h16M12 4v16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
              </svg>
              Explore ENC Shared Spaces
            </span>
            <h1 class="hero-title display-5 fw-semibold mt-3 mb-3">
              Facility Catalog
            </h1>
            <p class="text-secondary mb-4">
              Browse the most requested rooms across ENC BGC. Each card shows live readiness, capacity, and the kind of support crew you can request before booking.
            </p>
            <div class="d-flex flex-wrap gap-3">
              <a href="{{ route('booking.wizard') }}" class="btn btn-facility-primary">
                Jump to Booking Wizard
              </a>
              <a href="#catalog" class="btn btn-facility-secondary">
                See Available Spaces
              </a>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="hero-metrics">
              <div class="metric-card">
                <div class="metric-label">Rooms Cataloged</div>
                <div class="metric-value">{{ count($facilityRooms) }}</div>
                <div class="text-muted small">Across 4 towers</div>
              </div>
              <div class="metric-card">
                <div class="metric-label">Ready Right Now</div>
                <div class="metric-value">{{ $availableRooms }}</div>
                <div class="text-muted small">Instant hand-off</div>
              </div>
              <div class="metric-card">
                <div class="metric-label">Total Seats</div>
                <div class="metric-value">{{ $totalSeats }}</div>
                <div class="text-muted small">Hybrid-friendly</div>
              </div>
              <div class="metric-card">
                <div class="metric-label">Avg. Support SLA</div>
                <div class="metric-value">28m</div>
                <div class="text-muted small">Concierge response</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="catalog" class="facility-catalog-shell pb-5">
      <div class="container">
        <div class="catalog-card">
          <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
              <p class="text-uppercase text-muted small fw-semibold mb-1">Browse</p>
              <h2 class="h3 mb-0">All Shared Facilities</h2>
            </div>
            <div class="text-muted small">
              Tap any card to preview full details, layout notes, and support coverage.
            </div>
          </div>

          <div class="facility-filter-bar mb-4">
            <div>
              <label for="facilitySearch" class="form-label">Search</label>
              <input id="facilitySearch" type="search" class="form-control" placeholder="Room name, floor, or feature" disabled>
            </div>
            <div>
              <label for="facilityType" class="form-label">Layout</label>
              <select id="facilityType" class="form-select" disabled>
                <option>All layouts</option>
              </select>
            </div>
            <div>
              <label for="facilityCapacity" class="form-label">Capacity</label>
              <select id="facilityCapacity" class="form-select" disabled>
                <option>Any size</option>
              </select>
            </div>
            <div>
              <label for="facilityTower" class="form-label">Tower</label>
              <select id="facilityTower" class="form-select" disabled>
                <option>All locations</option>
              </select>
            </div>
          </div>

          <div class="facility-availability-controls mb-3">
            <button
              type="button"
              class="facility-availability-toggle"
              id="facilityAvailabilityToggle"
              aria-expanded="false"
            >
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M4 4h16M4 10h16M4 16h10" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
              </svg>
              Check availability by date
            </button>
            <div class="facility-availability-panel" id="facilityAvailabilityPanel" hidden>
              <div
                id="facilityCalendar"
                class="wizard-calendar"
                role="application"
                aria-label="Facility availability calendar"
                tabindex="-1"
              >
                <div class="wizard-calendar-header d-flex justify-content-between align-items-center mb-3">
                  <button
                    type="button"
                    class="btn btn-outline-light wizard-calendar-nav"
                    id="facilityCalendarPrev"
                    aria-label="Previous month"
                  >
                    ‹
                  </button>
                  <div class="text-center">
                    <div class="wizard-calendar-month fw-semibold" id="facilityCalendarMonth"></div>
                    <div class="wizard-calendar-subtitle small">Tap a date to preview openings</div>
                  </div>
                  <button
                    type="button"
                    class="btn btn-outline-light wizard-calendar-nav"
                    id="facilityCalendarNext"
                    aria-label="Next month"
                  >
                    ›
                  </button>
                </div>
                <div class="wizard-calendar-daynames" aria-hidden="true">
                  <span>Su</span>
                  <span>Mo</span>
                  <span>Tu</span>
                  <span>We</span>
                  <span>Th</span>
                  <span>Fr</span>
                  <span>Sa</span>
                </div>
                <div
                  class="wizard-calendar-grid"
                  id="facilityCalendarGrid"
                  role="grid"
                  aria-labelledby="facilityCalendarMonth"
                ></div>
                <div class="wizard-calendar-selection small mt-3">
                  Selected date:
                  <span class="fw-semibold" id="facilityCalendarSelectedDate">None yet</span>
                </div>
              </div>
              <div class="d-flex align-items-center justify-content-between mt-3 flex-wrap gap-2">
                <button class="btn btn-facility-primary" type="button" id="facilityAvailabilityApply">
                  Show rooms for this date
                </button>
                <button
                  type="button"
                  class="facility-availability-clear btn btn-sm"
                  id="facilityAvailabilityClear"
                  data-facility-availability-clear
                >
                  Clear selection
                </button>
              </div>
            </div>
          </div>

          <div class="facility-availability-active small" id="facilityAvailabilityActive" hidden>
            <div>
              Showing availability for <strong id="facilityAvailabilityLabel"></strong>
            </div>
            <button
              type="button"
              class="facility-availability-clear btn btn-sm"
              id="facilityAvailabilityClearInline"
              data-facility-availability-clear
            >
              Reset
            </button>
          </div>

          <div class="facility-layout">
            <div class="facility-grid" id="facilityCardGrid">
              @foreach ($facilityRooms as $room)
                @php
                  $availabilityPayload = e(json_encode($room['availability']));
                @endphp
                <article
                  class="facility-card {{ $loop->first ? 'is-active' : '' }}"
                  data-facility-card="{{ $room['id'] }}"
                  data-status="{{ $room['status']['variant'] }}"
                  data-bookings="{{ $room['bookings_today'] }}"
                  data-availability="{{ $availabilityPayload }}"
                  role="button"
                  tabindex="0"
                  aria-pressed="{{ $loop->first ? 'true' : 'false' }}"
                >
                  <img src="{{ $room['image'] }}" alt="{{ $room['name'] }} interior photo">
                  <div class="facility-card-body">
                    <span class="facility-status {{ $room['status']['variant'] }}">
                      {{ $room['status']['label'] }}
                    </span>
                    <h3>{{ $room['name'] }}</h3>
                    <div class="facility-meta">
                      <span>{{ $room['location'] }}</span>
                      <span>{{ $room['capacity_label'] }}</span>
                    </div>
                    <div class="facility-tags">
                      @foreach (array_slice($room['amenities'], 0, 3) as $tag)
                        <span class="facility-tag">{{ $tag }}</span>
                      @endforeach
                    </div>
                    <p class="facility-card-note">{{ $room['status']['copy'] }}</p>
                  </div>
                </article>
              @endforeach
            </div>
            <div class="facility-empty mt-3" id="facilityAvailabilityEmpty" hidden>
              No open rooms for <span id="facilityAvailabilityEmptyDate">your selected date</span>. Try another day or tap a card to join the waitlist.
            </div>

            @if ($activeFacility)
              <aside class="facility-detail-panel" id="facilityDetailPanel">
                <div class="facility-detail-media">
                  <img
                    id="facilityDetailImage"
                    src="{{ $activeFacility['image'] }}"
                    alt="{{ $activeFacility['name'] }} hero photo"
                  >
                  <span
                    id="facilityDetailStatus"
                    class="status-pill facility-status {{ $activeFacility['status']['variant'] }}"
                  >
                    {{ $activeFacility['status']['label'] }}
                  </span>
                </div>

                <div>
                  <h3 class="facility-detail-title" id="facilityDetailName">
                    {{ $activeFacility['name'] }}
                  </h3>
                  <p class="facility-detail-desc mb-2" id="facilityDetailDesc">
                    {{ $activeFacility['description'] }}
                  </p>
                  <div class="small facility-detail-statuscopy" id="facilityDetailStatusCopy">
                    {{ $activeFacility['status']['copy'] }}
                  </div>
                </div>

                <div class="facility-detail-meta">
                  <div class="meta-box">
                    <span class="meta-label">Location</span>
                    <span class="meta-value" id="facilityDetailLocation">{{ $activeFacility['location'] }}</span>
                  </div>
                  <div class="meta-box">
                    <span class="meta-label">Capacity</span>
                    <span class="meta-value" id="facilityDetailCapacity">{{ $activeFacility['capacity_label'] }}</span>
                  </div>
                  <div class="meta-box">
                    <span class="meta-label">Layout</span>
                    <span class="meta-value" id="facilityDetailLayout">{{ $activeFacility['layout'] }}</span>
                  </div>
                  <div class="meta-box">
                    <span class="meta-label">Prep Time</span>
                    <span class="meta-value" id="facilityDetailLeadTime">{{ $activeFacility['prep_time'] }}</span>
                  </div>
                </div>

                <div class="facility-detail-section">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0 text-uppercase small fw-semibold facility-detail-heading">Amenities</h5>
                  </div>
                  <div class="facility-chip-group" id="facilityDetailAmenities">
                    @foreach ($activeFacility['amenities'] as $item)
                      <span class="facility-chip">{{ $item }}</span>
                    @endforeach
                  </div>
                </div>

                <div class="facility-detail-section">
                  <h5 class="mb-3 text-uppercase small fw-semibold facility-detail-heading">What teams love</h5>
                  <ul class="facility-highlight-list" id="facilityDetailHighlights">
                    @foreach ($activeFacility['highlights'] as $highlight)
                      <li class="facility-highlight-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                          <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>{{ $highlight }}</span>
                      </li>
                    @endforeach
                  </ul>
                </div>

                <div class="facility-detail-section">
                  <h5 class="mb-3 text-uppercase small fw-semibold facility-detail-heading">Today’s timeline</h5>
                  <div class="facility-timeline" id="facilityDetailTimeline">
                    @foreach ($activeFacility['timeline'] as $timeline)
                      <div class="facility-timeline-item">
                        <div class="facility-timeline-title">{{ $timeline['label'] }}</div>
                        <div class="facility-timeline-copy">{{ $timeline['copy'] }}</div>
                      </div>
                    @endforeach
                  </div>
                </div>

                <div class="facility-actions">
                  <a
                    href="{{ route('booking.wizard') }}"
                    class="btn btn-facility-primary"
                    id="facilityDetailBookLink"
                  >
                    Book {{ $activeFacility['short_label'] }}
                  </a>
                  <a
                    href="{{ $activeFacility['support_contact'] }}"
                    class="btn btn-facility-secondary"
                    id="facilityDetailSupportLink"
                  >
                    Request Facility Support
                  </a>
                </div>
                <div class="facility-footnote">
                  Concierge replies in under 30 minutes during office hours (8 AM – 7 PM). Need off-hours access? Add it as a note in your booking.
                </div>
              </aside>
            @endif
          </div>
        </div>
      </div>
    </section>
  </div>
@endsection
