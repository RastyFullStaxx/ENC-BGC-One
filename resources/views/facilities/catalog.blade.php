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
  $facilityRooms = $facilityRooms ?? [];
  $activeFacility = $activeFacility ?? ($facilityRooms[0] ?? null);
  $availableRooms = $availableRooms ?? collect($facilityRooms)->filter(fn($room) => ($room['status']['variant'] ?? null) === 'success')->count();
  $totalSeats = $totalSeats ?? collect($facilityRooms)->sum('capacity');
@endphp

@push('scripts')
  <script>
    window.facilityCatalogData = @json($facilityRooms);
  </script>
  @vite(['resources/js/facility/catalog.js'])
@endpush

@section('content')
  <div class="facility-catalog-page">
    <div class="catalog-back-nav">
      <a href="{{ route('user.dashboard') }}" class="catalog-back-link">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
          <path d="M10 12L6 8L10 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M6 8H14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Back to Dashboard
      </a>
    </div>

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
              @forelse ($facilityRooms as $room)
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
              @empty
                <div class="facility-empty mt-3 w-100">
                  No facilities are available yet. Please check back after the admin publishes rooms.
                </div>
              @endforelse
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
