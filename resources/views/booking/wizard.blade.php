{{-- resources/views/booking/wizard.blade.php --}}
@extends('layouts.app')

@section('title', 'One Services Booking — Wizard')

@push('styles')  {{-- CSS will come next --}}
  @vite(['resources/css/wizard.css'])
@endpush

@push('scripts') {{-- JS will come after CSS --}}
  @vite(['resources/js/wizard.js'])
@endpush

@php
  $wizardSampleBookings = [
    ['status'=>'Pending','room'=>'Meeting Room B','date'=>'11/29/2025','time'=>'9:00 AM - 10:00 AM'],
    ['status'=>'Pending','room'=>'Meeting Room B','date'=>'11/29/2025','time'=>'10:30 AM - 11:00 AM'],
    ['status'=>'Pending','room'=>'Meeting Room B','date'=>'11/29/2025','time'=>'11:00 AM - 12:00 PM'],
    ['status'=>'Pending','room'=>'Meeting Room B','date'=>'11/29/2025','time'=>'1:00 PM - 2:00 PM'],
    ['status'=>'Pending','room'=>'Meeting Room B','date'=>'11/29/2025','time'=>'2:30 PM - 3:30 PM'],
  ];

  $timeSlots = [];
  for ($hour = 7; $hour <= 20; $hour++) {
    foreach ([0, 30] as $minute) {
      if ($hour === 20 && $minute > 0) {
        continue;
      }
      $timeSlots[] = sprintf('%02d:%02d', $hour, $minute);
    }
  }
@endphp

@section('app-navbar')
  <div id="wizardAppNav" class="d-none">
    @include('partials.dashboard-navbar', [
      'currentStep'        => 1,
      'bookingsCount'      => count($wizardSampleBookings),
      'notificationsCount' => 2,
    ])
  </div>
@endsection

@section('content')

  <section class="wizard-shell py-4 py-md-5">
    <div class="container" id="wizardLandingShell">
      <div class="row justify-content-center">
        <div class="col-12 col-lg-10">

          {{-- GREETING PANEL (first thing user sees on the booking wizard) --}}
          <div class="wizard-greeting-panel d-flex flex-column justify-content-center align-items-center text-center p-4 p-md-5">

            {{-- Main greeting copy --}}
            <div class="wizard-greeting-body">
              <p class="wizard-greeting-meta text-uppercase fw-semibold mb-3">
                Guided booking • Takes under 2 minutes
              </p>
              <h1 class="wizard-greeting-title mb-3">
                Welcome to One Services Booking!
              </h1>

              <p class="wizard-greeting-subtitle mb-1">
                We’ll guide you step-by-step with simple questions, one at a time.
              </p>
              <p class="wizard-greeting-subsubtitle mb-4">
                We’ll only ask what matters — no long forms, promise.
              </p>
            </div>

            {{-- Primary CTA --}}
            <div class="wizard-greeting-cta">
              <button
                type="button"
                id="wizardStartButton"
                class="btn btn-primary btn-lg px-4 px-md-5 wizard-primary-cta"
              >
                Let’s Begin
              </button>

              <div class="wizard-greeting-cta-note small mt-3 text-muted">
                You’ll be asked to sign in or create an account later in the flow once authentication is ready.
              </div>
            </div>

            {{-- Optional tiny hint about what’s next --}}
            <div class="wizard-greeting-next-hint small mt-4 text-muted">
              Next: Choose whether to use <strong>Smart Book Finder</strong> or
              <strong>Browse All Rooms</strong>.
            </div>

          </div>

          {{-- Booking method selection --}}
          <section
            id="wizardMethodSection"
            class="wizard-method-section d-none mt-4"
            hidden
          >
            <div class="text-center mb-4">
              <p class="wizard-greeting-meta text-uppercase fw-semibold mb-2">
                Step 1 — Choose how to book
              </p>
              <h2 class="wizard-method-title mb-2" tabindex="-1">
                How would you like to find your room?
              </h2>
              <p class="wizard-method-subtitle mb-0">
                Pick the flow that best fits today’s request.
              </p>
            </div>

            <div class="row g-4 justify-content-center">
              {{-- Smart Book Finder (Recommended) --}}
              <div class="col-12 col-md-6 col-xl-5 d-flex">
                <button type="button"
                        class="wizard-method-card is-recommended w-100 text-start d-flex flex-column flex-grow-1"
                        data-method="smart">
                  <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center gap-2">
                      <span class="wizard-method-icon d-inline-flex align-items-center justify-content-center rounded-3">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                          <path d="M7 4l3 3-3 3M17 14l-3 3 3 3"
                                stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                          <path d="M6 18a6 6 0 010-12M18 6a6 6 0 010 12"
                                stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                        </svg>
                      </span>
                      <div>
                        <div class="wizard-method-name">Smart Book Finder</div>
                        <div class="wizard-method-desc">
                          Answer quick questions and we’ll shortlist the best room with the right capacity, equipment, and availability.
                        </div>
                      </div>
                    </div>
                    <span class="wizard-method-badge">Recommended</span>
                  </div>
                  <div class="wizard-method-footnote d-flex align-items-center gap-1">
                    <span aria-hidden="true">⚡</span>
                    <span>Saves time with tailored suggestions</span>
                  </div>
                </button>
              </div>

              {{-- Browse All Rooms --}}
              <div class="col-12 col-md-6 col-xl-5 d-flex">
                <button type="button"
                        class="wizard-method-card w-100 text-start d-flex flex-column flex-grow-1"
                        data-method="manual">
                  <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center gap-2">
                      <span class="wizard-method-icon d-inline-flex align-items-center justify-content-center rounded-3">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                          <rect x="3" y="4" width="18" height="17" rx="3"
                                stroke="currentColor" stroke-width="1.6"/>
                          <path d="M3 10h18M9 4v17M15 4v17"
                                stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                        </svg>
                      </span>
                      <div>
                        <div class="wizard-method-name">Browse All Rooms</div>
                        <div class="wizard-method-desc">
                          View the full catalog, filter manually, and pick a specific space even if it’s currently busy.
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="wizard-method-footnote d-flex align-items-center gap-1">
                    <span aria-hidden="true">👁️</span>
                    <span>Full visibility across every facility</span>
                  </div>
                </button>
              </div>
            </div>
          </section>

          {{-- TODO: Step 2 — Select Date & Time
               • Calendar component on the left to pick date
               • Time range on the right (Start Time / End Time inputs)
               • Live duration pill ("1 hour(s) duration")
               • Navigation buttons: Back to Rooms / Next: Add Details
               • Same "Your Bookings" sidebar on the right. --}}

          {{-- TODO: Step 3 — Booking Details (+ optional manpower toggle)
               • Number of attendees (with + / – controls and max capacity hint)
               • Purpose / Meeting Agenda input + priority dropdown
               • SFI Support toggle section:
                 - When ON: show fields for manpower count, equipment chips,
                   additional requirements textarea, helper text (typical services).
               • Navigation: Back to Date & Time / Next: Review & Confirm. --}}

          {{-- TODO: Step 4 — Review & Confirm
               • Summary cards: Room Details, Date & Time, Additional Details
               • If SFI support requested, show extra card with manpower info
               • "Submit Booking Request" primary button, "Back to Details" secondary.
               • Same "Your Bookings" sidebar. --}}

        </div>
      </div>
    </div>

    {{-- STEP 1 (Manual path) — Browse All Rooms --}}
    <section
      id="wizardManualStage"
      class="wizard-stage container-fluid px-3 px-lg-5 d-none"
      hidden
    >
      <div class="wizard-stage-row" id="wizardStageRow">
        {{-- Main column --}}
        <div class="wizard-stage-main" id="wizardStageMain">

          {{-- Step 1 panel --}}
          <div id="wizardRoomsPanel">
            <div class="wizard-rooms-card card border-0 shadow-sm h-100">
            <div class="card-body p-3 p-md-4">
              <div class="mb-3">
                <h2 class="wizard-rooms-title h5 mb-1">
                  Browse All Rooms
                </h2>
                <p class="text-muted small mb-0">
                  Browse available rooms and filters to find the best fit for your meeting.
                </p>
              </div>

              {{-- Filters row --}}
              <div class="row g-2 g-md-3 align-items-center mb-3">
                <div class="col-12 col-md-6">
                  <label for="roomSearch" class="form-label small mb-1">Search rooms…</label>
                  <div class="position-relative">
                    <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted" aria-hidden="true">
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                        <circle cx="11" cy="11" r="6" stroke="currentColor" stroke-width="1.6"/>
                        <path d="M16 16l4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                      </svg>
                    </span>
                    <input
                      type="search"
                      id="roomSearch"
                      class="form-control ps-5"
                      placeholder="Search by name, location, or feature"
                      autocomplete="off"
                    >
                  </div>
                </div>

                <div class="col-6 col-md-3">
                  <label for="roomFloor" class="form-label small mb-1">Floor</label>
                  <select id="roomFloor" class="form-select">
                    <option value="">All Floors</option>
                    <option>Ground Floor</option>
                    <option>2nd Floor</option>
                    <option>3rd Floor</option>
                  </select>
                </div>

                <div class="col-6 col-md-3">
                  <label for="roomSize" class="form-label small mb-1">Size</label>
                  <select id="roomSize" class="form-select">
                    <option value="">All Sizes</option>
                    <option value="small">Small (≤ 6)</option>
                    <option value="medium">Medium (7–12)</option>
                    <option value="large">Large (13+)</option>
                  </select>
                </div>
              </div>

              {{-- Room cards --}}
              <div class="row g-3" id="wizardRoomsGrid">
                @php
                  $rooms = [
                    [
                      'name' => 'Conference Room A-301',
                      'capacity' => 12,
                      'status' => 'Available Now',
                      'status_variant' => 'success',
                      'location' => 'ENC Tower A · 3rd Floor',
                      'tags' => ['Projector', 'Whiteboard', 'VC setup', 'HDMI', 'WiFi'],
                      'image' => 'https://images.unsplash.com/photo-1507209696998-3c532be9b2b1?q=80&w=1200&auto=format&fit=crop',
                      'availability' => [
                        'text' => 'Available until 5:00 PM',
                        'subtext' => null,
                        'variant' => 'success',
                      ],
                    ],
                    [
                      'name' => 'Conference Room B-305',
                      'capacity' => 10,
                      'status' => 'Occupied',
                      'status_variant' => 'danger',
                      'location' => 'ENC Tower B · 3rd Floor',
                      'tags' => ['Projector', 'Whiteboard', 'VC setup', 'HDMI'],
                      'image' => 'https://images.unsplash.com/photo-1524758631624-e2822e304c36?q=80&w=1200&auto=format&fit=crop',
                      'availability' => [
                        'text' => 'Q4 Planning Meeting',
                        'subtext' => 'by Maria Santos (Finance)',
                        'next' => 'Next available at 2:00 PM',
                        'variant' => 'danger',
                      ],
                    ],
                    [
                      'name' => 'BGC-302 Training Room',
                      'capacity' => 20,
                      'status' => 'Limited Availability',
                      'status_variant' => 'warning',
                      'location' => 'ENC Tower A · 4th Floor',
                      'tags' => ['Projector', 'TV', 'Whiteboard', 'VC setup', 'HDMI'],
                      'image' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?q=80&w=1200&auto=format&fit=crop',
                      'availability' => [
                        'text' => 'Free until 12:30 PM',
                        'subtext' => 'Next booking at 1:00 PM',
                        'variant' => 'warning',
                      ],
                    ],
                  ];
                @endphp

                @foreach ($rooms as $room)
                  <div class="col-12 col-md-6 col-xl-4">
                    <article class="wizard-room-card card h-100 border-0">
                      <div class="wizard-room-media position-relative">
                        <img
                          src="{{ $room['image'] }}"
                          alt="{{ $room['name'] }} photo"
                          class="wizard-room-image"
                        >
                        <span class="wizard-room-status badge rounded-pill text-bg-{{ $room['status_variant'] }} position-absolute top-0 end-0 m-3">
                          {{ $room['status'] }}
                        </span>
                      </div>
                      <div class="card-body wizard-room-body d-flex flex-column">
                        <div class="wizard-room-heading mb-2">
                          <h3 class="h5 mb-1">{{ $room['name'] }}</h3>
                          <p class="text-muted small mb-0">{{ $room['location'] }}</p>
                        </div>

                        <div class="wizard-room-capacity text-muted small d-flex align-items-center gap-2 mb-3">
                          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M7 10a3 3 0 116 0 3 3 0 01-6 0zM4 20c0-2.761 2.91-5 6.5-5S17 17.239 17 20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                          </svg>
                          <span>Up to {{ $room['capacity'] }} people</span>
                        </div>

                        <div class="wizard-room-tags d-flex flex-column gap-2 mb-3">
                          @foreach (array_chunk($room['tags'], 2) as $pair)
                            <div class="wizard-room-chip-row d-flex gap-2">
                              @foreach ($pair as $tag)
                                <span class="wizard-room-chip">{{ $tag }}</span>
                              @endforeach
                            </div>
                          @endforeach
                        </div>

                        @isset($room['availability'])
                          <div class="wizard-room-availability is-{{ $room['availability']['variant'] }} mb-3">
                            <div class="fw-semibold">{{ $room['availability']['text'] }}</div>
                            @if(!empty($room['availability']['subtext']))
                              <div class="small text-muted">{{ $room['availability']['subtext'] }}</div>
                            @endif
                            @if(!empty($room['availability']['next']))
                              <div class="small text-muted">{{ $room['availability']['next'] }}</div>
                            @endif
                          </div>
                        @endisset

                        <div class="wizard-room-actions mt-auto pt-2">
                          @php
                            $action = match ($room['status_variant']) {
                              'danger'  => ['label' => 'View Schedule', 'class' => 'btn btn-room-occupied', 'data-action' => 'schedule'],
                              'warning' => ['label' => 'Book for Available Time', 'class' => 'btn btn-room-limited', 'data-action' => 'limited'],
                              default   => ['label' => 'Book This Room', 'class' => 'btn btn-room-available', 'data-action' => 'book'],
                            };
                          @endphp

                          <button
                            type="button"
                            class="{{ $action['class'] }} w-100 wizard-room-select"
                            data-room-name="{{ $room['name'] }}"
                            data-room-action="{{ $action['data-action'] }}"
                            data-default-label="{{ $action['label'] }}"
                          >
                            {{ $action['label'] }}
                          </button>
                        </div>
                      </div>
                    </article>
                  </div>
                @endforeach
              </div>

                <div class="d-flex justify-content-end mt-4" id="wizardRoomsActions">
                  <button
                    type="button"
                    class="btn btn-room-available wizard-next-date"
                    disabled
                  >
                    Next: Select Date &amp; Time
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div> {{-- end wizardRoomsPanel --}}

          {{-- Step 2 panel --}}
          <div id="wizardDatePanel" class="d-none">
            <div class="wizard-date-card card border-0 shadow-sm h-100">
              <div class="card-body p-3 p-md-4">
                <div class="mb-3">
                  <p class="text-uppercase small fw-semibold text-muted mb-2">Step 2 — Date &amp; Time</p>
                  <h2 class="wizard-rooms-title h5 mb-1">
                    When will you need the room?
                  </h2>
                  <p class="text-muted small mb-0">
                    Pick a date and time range. We’ll hold the slot while you complete the request.
                  </p>
                </div>

                <div class="wizard-date-selected mb-3">
                  <span class="text-muted small">Selected Room:</span>
                  <span class="fw-semibold" id="wizardSelectedRoomName">None selected yet</span>
                </div>

                <div class="row g-3 wizard-date-grid">
                  <div class="col-12 col-lg-4">
                    <label for="bookingDate" class="form-label small text-muted mb-1">Preferred date</label>
                    <input type="date" class="form-control" id="bookingDate">
                  </div>
                  <div class="col-6 col-lg-4">
                    <label for="bookingStartTime" class="form-label small text-muted mb-1">Start time</label>
                    <select id="bookingStartTime" class="form-select">
                      <option value="">Select start</option>
                      @foreach ($timeSlots as $slot)
                        <option value="{{ $slot }}">{{ \Carbon\Carbon::createFromFormat('H:i', $slot)->format('g:i A') }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-6 col-lg-4">
                    <label for="bookingEndTime" class="form-label small text-muted mb-1">End time</label>
                    <select id="bookingEndTime" class="form-select">
                      <option value="">Select end</option>
                      @foreach ($timeSlots as $slot)
                        <option value="{{ $slot }}">{{ \Carbon\Carbon::createFromFormat('H:i', $slot)->format('g:i A') }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="wizard-duration-pill mt-3" id="wizardDurationPill">
                  Duration: <span id="wizardDurationLabel">Select start and end time</span>
                </div>

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 mt-4">
                  <button type="button" class="btn btn-light wizard-back-button" id="wizardBackToRooms">&lt; Back</button>
                  <button type="button" class="btn btn-room-available" id="wizardNextDetails" disabled>
                    Next: Add Details
                  </button>
                </div>
              </div>
            </div>
          </div>

        </div> {{-- end wizard-stage-main --}}

        {{-- Sidebar: Your Bookings (shown only when "My Bookings" is toggled in navbar) --}}
        <div class="wizard-stage-sidebar d-none" id="wizardStageSidebar">
          <aside
            id="wizardBookingsSidebar"
            class="wizard-bookings-sidebar card border-0 shadow-sm h-100"
            aria-label="Your bookings"
          >
            <div class="card-body p-3 p-md-4">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h2 class="h6 mb-0">Your Bookings</h2>
                <span class="badge rounded-pill bg-light text-secondary small">5</span>
              </div>
              <p class="small text-muted mb-3">
                Logged in as <strong>user.charles@enc.gov</strong><br>
                Total Bookings: 5
              </p>

              <ul class="nav nav-pills small mb-3" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" data-bs-toggle="pill" type="button">
                    Pending (5)
                  </button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" data-bs-toggle="pill" type="button">
                    Confirmed (0)
                  </button>
                </li>
              </ul>

              <div class="wizard-bookings-list small">
                @foreach ($wizardSampleBookings as $booking)
                  <div class="wizard-booking-item border rounded-3 p-2 mb-2 bg-warning-subtle">
                    <div class="d-flex justify-content-between">
                      <span class="badge bg-warning text-dark me-2">{{ $booking['status'] }}</span>
                      <span class="text-muted">{{ $booking['date'] }}</span>
                    </div>
                    <div class="fw-semibold mt-1">{{ $booking['room'] }}</div>
                    <div class="text-muted">{{ $booking['time'] }}</div>
                  </div>
                @endforeach
              </div>
            </div>
          </aside>
        </div>
      </div>
    </section>
  </section>
@endsection
