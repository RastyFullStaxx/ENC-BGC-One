{{-- resources/views/booking/wizard.blade.php --}}
@extends('layouts.app')

@section('title', 'One Services Booking — Wizard')

@push('styles')  {{-- CSS will come next --}}
  @vite([
    'resources/css/wizard/base.css',
    'resources/css/wizard/step1.css',
    'resources/css/wizard/steps.css',
    'resources/css/wizard/bookings.css',
  ])
@endpush

@push('scripts') {{-- JS will come after CSS --}}
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    // API endpoints configuration
    window.bookingAPI = {
      getFacilities: '{{ route('api.bookings.facilities') }}',
      checkAvailability: '{{ route('api.bookings.check-availability') }}',
      store: '{{ route('api.bookings.store') }}',
      getUserBookings: '{{ route('api.bookings.user-bookings') }}',
      csrfToken: '{{ csrf_token() }}',
    };
    
    // User info for success message
    @auth
      window.currentUser = {
        name: '{{ auth()->user()->name }}',
        firstName: '{{ explode(" ", auth()->user()->name)[0] }}',
        email: '{{ auth()->user()->email }}',
        id: {{ auth()->user()->id }}
      };
    @endauth
  </script>
  @vite(['resources/js/wizard.js'])
@endpush

@php
  // Get user bookings if authenticated
  $userBookings = [];
  if (auth()->check()) {
    $userBookings = \App\Models\Booking::with(['facility.building', 'details'])
      ->where('requester_id', auth()->id())
      ->whereIn('status', ['pending', 'approved'])
      ->orderBy('date', 'desc')
      ->orderBy('start_at', 'desc')
      ->limit(10)
      ->get()
      ->map(function($booking) {
        return [
          'status' => ucfirst($booking->status),
          'room' => $booking->facility->name,
          'date' => \Carbon\Carbon::parse($booking->date)->format('m/d/Y'),
          'time' => \Carbon\Carbon::parse($booking->start_at)->format('g:i A') . ' - ' . \Carbon\Carbon::parse($booking->end_at)->format('g:i A'),
        ];
      })->toArray();
  }

  // Generate time slots (7:00 AM to 8:00 PM, 30-minute intervals)
  $timeSlots = [];
  for ($hour = 7; $hour <= 20; $hour++) {
    foreach ([0, 30] as $minute) {
      if ($hour === 20 && $minute > 0) {
        continue;
      }
      $timeSlots[] = sprintf('%02d:%02d', $hour, $minute);
    }
  }

  // Get all equipment from database for SFI support section
  $allEquipment = \App\Models\Equipment::all();
  $wizardSupportEquipment = $allEquipment->map(function($equip) {
    return [
      'id' => $equip->id,
      'label' => $equip->name,
    ];
  })->toArray();
  
  // Fallback if no equipment in database
  if (empty($wizardSupportEquipment)) {
    $wizardSupportEquipment = [
      ['id' => 'projector',    'label' => 'Projector'],
      ['id' => 'tv-monitor',   'label' => 'TV Monitor'],
      ['id' => 'whiteboard',   'label' => 'Whiteboard'],
      ['id' => 'microphone',   'label' => 'Microphone'],
      ['id' => 'speaker',      'label' => 'Speaker System'],
      ['id' => 'refreshments', 'label' => 'Refreshments'],
    ];
  }
@endphp

@section('app-navbar')
  <div id="wizardAppNav">
    @include('partials.dashboard-navbar', [
      'currentStep'        => 1,
      'bookingsCount'      => count($userBookings),
      'notificationsCount' => 2,
    ])
  </div>
@endsection

@section('content')

  <section class="wizard-shell py-4 py-md-5">
    <div class="container" id="wizardLandingShell">
      <div class="row justify-content-center">
        <div class="col-12 col-lg-10">

          {{-- Booking method selection --}}
          <section
            id="wizardMethodSection"
            class="wizard-method-section mt-4"
          >
            <div class="text-center mb-4">
              <h2 class="wizard-method-title mb-2" tabindex="-1">
                What do you need to book today?
              </h2>
              <p class="wizard-method-subtitle mb-0">
                Choose the guided flow that best matches your request. We’ll do the rest.
              </p>
            </div>

            <div class="row g-4 justify-content-center">
              {{-- Book Meeting Room --}}
              <div class="col-12 col-lg-4 d-flex">
                <button type="button"
                        class="wizard-method-card is-recommended w-100 text-start d-flex flex-column flex-grow-1"
                        data-method="manual">
                  <div class="text-center mb-3">
                    <span class="wizard-method-icon d-inline-flex align-items-center justify-content-center rounded-3 mx-auto mb-3">
                      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <rect x="3" y="5" width="18" height="14" rx="3" stroke="currentColor" stroke-width="1.6"/>
                        <path d="M7 9h10" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                        <path d="M7 13h5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                      </svg>
                    </span>
                    <div class="text-center">
                      <div class="wizard-method-name">Book Meeting Room</div>
                      <div class="wizard-method-desc">
                        Reserve conference rooms and meeting spaces with the right capacity and equipment.
                      </div>
                    </div>
                  </div>
                  <div class="wizard-method-footnote">
                    Ideal for quick team syncs, client visits, and presentations.
                  </div>
                </button>
              </div>

              {{-- SEI --}}
              <div class="col-12 col-lg-4 d-flex">
                <button type="button"
                        class="wizard-method-card w-100 text-start d-flex flex-column flex-grow-1"
                        data-method="sei">
                  <div class="text-center mb-3">
                    <span class="wizard-method-icon d-inline-flex align-items-center justify-content-center rounded-3 mx-auto mb-3">
                      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M4 7h16M4 12h16M4 17h10" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                        <path d="M7 4h10v16H7z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                      </svg>
                    </span>
                    <div class="text-center">
                      <div class="wizard-method-name">SEI Facilities</div>
                      <div class="wizard-method-desc">
                        Request special facilities, infrastructure setups, and book shuttle service support.
                      </div>
                    </div>
                  </div>
                  <div class="wizard-method-footnote d-flex align-items-center gap-1">
                    <span aria-hidden="true">🛠️</span>
                    <span>Perfect for events, exhibits, and custom layouts.</span>
                  </div>
                </button>
              </div>

              {{-- Transportation & Shuttle --}}
              <div class="col-12 col-lg-4 d-flex">
                <button type="button"
                        class="wizard-method-card w-100 text-start d-flex flex-column flex-grow-1"
                        data-method="transport">
                  <div class="text-center mb-3">
                    <span class="wizard-method-icon d-inline-flex align-items-center justify-content-center rounded-3 mx-auto mb-3">
                      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M3 11h18v6a2 2 0 01-2 2h-1.5a1.5 1.5 0 01-3 0H9.5a1.5 1.5 0 01-3 0H5a2 2 0 01-2-2v-6z"
                              stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M7 11L5 6h14l-2 5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                      </svg>
                    </span>
                    <div class="text-center">
                      <div class="wizard-method-name">Book Shuttle Service</div>
                      <div class="wizard-method-desc">
                        Schedule transportation or shuttle services with clear pickup and drop-off details.
                      </div>
                    </div>
                  </div>
                  <div class="wizard-method-footnote">
                    Need to move teams or equipment? We’ll plan the trip for you.
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
                {{-- Room cards will be loaded dynamically via JavaScript --}}
                <div class="col-12 text-center py-5">
                  <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading facilities...</span>
                  </div>
                  <p class="text-muted mt-2">Loading available facilities...</p>
                </div>
              </div>

                <div class="d-flex align-items-center flex-wrap gap-3 mt-4" id="wizardRoomsActions">
                  <button
                    type="button"
                    class="btn btn-light"
                    id="wizardBackToMethods"
                  >
                    Go Back to Booking Type
                  </button>
                  <button
                    type="button"
                    class="btn btn-room-available wizard-next-date ms-auto"
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
                  <h2 class="wizard-rooms-title h5 mb-1">
                    When will you need the room?
                  </h2>
                  <p class="text-muted small mb-0">
                    Pick a date and time range. We’ll hold the slot while you complete the request.
                  </p>
                </div>

                <div class="wizard-date-selected mb-4">
                  <span class="text-muted small d-block">Selected Room</span>
                  <span class="fw-semibold h6 mb-0" id="wizardSelectedRoomName">None selected yet</span>
                </div>

                <input type="hidden" id="bookingDate">

                <div class="row g-3 wizard-date-grid">
                  <div class="col-12 col-lg-6">
                    <div class="wizard-step-subcard h-100">
                      <div class="wizard-step-subcard-header mb-3">
                        <div>
                          <p class="text-uppercase small fw-semibold text-muted mb-1">Select Date *</p>
                          <h3 class="h6 mb-1 text-dark">Choose when you need the room</h3>
                          <p class="small text-muted mb-0">See availability at a glance and tap a date to continue.</p>
                        </div>
                      </div>
                      <div
                        id="wizardCalendar"
                        class="wizard-calendar"
                        role="application"
                        aria-label="Meeting date selector"
                      >
                        <div class="wizard-calendar-header d-flex justify-content-between align-items-center mb-3">
                          <button
                            type="button"
                            class="btn btn-outline-light wizard-calendar-nav"
                            id="wizardCalendarPrev"
                            aria-label="Previous month"
                          >
                            ‹
                          </button>
                          <div class="text-center">
                            <div class="wizard-calendar-month fw-semibold" id="wizardCalendarMonth">November 2025</div>
                            <div class="wizard-calendar-subtitle small text-muted">Tap any available date</div>
                          </div>
                          <button
                            type="button"
                            class="btn btn-outline-light wizard-calendar-nav"
                            id="wizardCalendarNext"
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
                          id="wizardCalendarGrid"
                          role="grid"
                          aria-labelledby="wizardCalendarMonth"
                        ></div>
                        <div class="wizard-calendar-selection small text-muted mt-3">
                          Selected date:
                          <span class="fw-semibold text-dark" id="wizardCalendarSelectedDate">None yet</span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-lg-6">
                    <div class="wizard-step-subcard h-100">
                      <div class="wizard-step-subcard-header mb-3">
                        <div>
                          <p class="text-uppercase small fw-semibold text-muted mb-1">Set Time Range *</p>
                          <h3 class="h6 mb-1 text-dark">When will your meeting start and end?</h3>
                          <p class="small text-muted mb-0">We’ll hold this slot while you review details on the next step.</p>
                        </div>
                      </div>
                      <div class="row g-3 wizard-time-range-inputs">
                        <div class="col-12 col-md-6">
                          <label for="bookingStartTime" class="form-label small text-muted mb-1">Start Time</label>
                          <div class="wizard-time-input">
                            <span aria-hidden="true">🕒</span>
                            <select id="bookingStartTime" class="form-select">
                              <option value="">Select start</option>
                              @foreach ($timeSlots as $slot)
                                <option value="{{ $slot }}">{{ \Carbon\Carbon::createFromFormat('H:i', $slot)->format('g:i A') }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-12 col-md-6">
                          <label for="bookingEndTime" class="form-label small text-muted mb-1">End Time</label>
                          <div class="wizard-time-input">
                            <span aria-hidden="true">🕒</span>
                            <select id="bookingEndTime" class="form-select">
                              <option value="">Select end</option>
                              @foreach ($timeSlots as $slot)
                                <option value="{{ $slot }}">{{ \Carbon\Carbon::createFromFormat('H:i', $slot)->format('g:i A') }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="wizard-duration-pill mt-4" id="wizardDurationPill">
                        <span class="wizard-duration-icon" aria-hidden="true">⏱</span>
                        <span id="wizardDurationLabel">Select start and end time</span>
                      </div>
                      <p class="small text-muted mt-3 mb-0">
                        Tip: Choose at least 30 minutes. You can always adjust before submitting the request.
                      </p>
                    </div>
                  </div>
                </div>

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 mt-4 pt-2">
                  <button type="button" class="btn btn-light wizard-back-button" id="wizardBackToRooms">
                    Go Back to Select Room
                  </button>
                  <button type="button" class="btn btn-room-available" id="wizardNextDetails" disabled>
                    Next: Add Details
                  </button>
                </div>
              </div>
            </div>
          </div>

          {{-- Step 3 panel --}}
          <div id="wizardDetailsPanel" class="d-none">
            <div class="wizard-details-card card border-0 shadow-sm h-100">
              <div class="card-body p-3 p-md-4">
                <div class="mb-3">
                  
                    Tell us a bit more about your meeting.
                  </h2>
                  <p class="text-muted small mb-0">
                    We’ll share these details with the facility team so they can prep the room and any support you might need.
                  </p>
                </div>

                <div class="row g-3 wizard-details-grid">
                  <div class="col-12 col-lg-6">
                    <div class="wizard-step-subcard wizard-attendees-card mb-3 text-center">
                      <p class="text-uppercase small fw-semibold text-muted mb-1">Number of attendees *</p>
                      <h3 class="h5 mb-1 text-dark">How many colleagues are joining?</h3>
                      <p class="small text-muted mb-3">We’ll double-check seat count based on this number.</p>
                      <div class="wizard-counter justify-content-center w-100 mt-3" aria-live="polite">
                        <button
                          type="button"
                          class="btn btn-outline-light wizard-counter-btn"
                          id="wizardAttendeesDecrease"
                          aria-label="Decrease attendees"
                        >
                          −
                        </button>
                        <div class="wizard-counter-value">
                          <div class="wizard-counter-number" id="wizardAttendeesValue">4</div>
                          <div class="wizard-counter-label small text-muted text-uppercase">People</div>
                        </div>
                        <button
                          type="button"
                          class="btn btn-outline-light wizard-counter-btn"
                          id="wizardAttendeesIncrease"
                          aria-label="Increase attendees"
                        >
                          +
                        </button>
                      </div>
                      <div class="wizard-attendees-badge-wrap">
                        <span class="badge wizard-attendees-badge">Max 20 in-room</span>
                      </div>
                      <input type="hidden" id="wizardAttendeesInput" value="4" data-min="1" data-max="20">
                      <p class="wizard-form-tip small text-muted mt-3 mb-0">
                        Need more than 20? Leave a note so we can suggest larger venues or hybrid setups.
                      </p>
                    </div>

                    <div class="wizard-step-subcard">
                      <label for="wizardAgendaInput" class="text-uppercase small fw-semibold text-muted mb-1">Purpose / Agenda *</label>
                      <h3 class="h6 mb-2 text-dark">What’s this meeting about?</h3>
                      <textarea
                        id="wizardAgendaInput"
                        class="form-control"
                        rows="4"
                        placeholder="E.g., Quarterly planning sync with Ops and Finance teams."
                      ></textarea>
                      <p class="wizard-form-tip small text-muted mt-2 mb-0">
                        Include any context the facilities team should know (presentations, clients attending, special layout, etc.).
                      </p>
                    </div>
                  </div>

                  <div class="col-12 col-lg-6">
                    <div class="wizard-step-subcard wizard-support-card h-100">
                      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                        <div>
                          <p class="text-uppercase small fw-semibold text-muted mb-1">Request SFI Support</p>
                          <h3 class="h6 mb-1 text-dark">Need help with setup?</h3>
                          <p class="small text-muted mb-0">Our support team can assist with manpower, refreshments, or AV prep.</p>
                        </div>
                        <div class="form-check form-switch wizard-support-toggle m-0">
                          <input class="form-check-input" type="checkbox" role="switch" id="wizardSupportToggle">
                          <label class="form-check-label small text-muted" for="wizardSupportToggle"></label>
                        </div>
                      </div>

                      <div
                        id="wizardSupportFields"
                        class="wizard-support-fields d-none"
                        aria-live="polite"
                        aria-hidden="true"
                      >
                        <div class="wizard-support-field">
                          <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                            <div>
                              <p class="text-uppercase small fw-semibold text-muted mb-1 mb-md-0">SFI manpower required *</p>
                              <p class="small text-muted mb-0">Maximum 10 staff members</p>
                            </div>
                            <div class="wizard-counter wizard-counter-compact" aria-live="polite">
                              <button
                                type="button"
                                class="btn btn-outline-light wizard-counter-btn"
                                id="wizardSupportCountDecrease"
                                aria-label="Decrease support personnel"
                              >
                                −
                              </button>
                              <div class="wizard-counter-value">
                                <div class="wizard-counter-number" id="wizardSupportCountValue">1</div>
                                <div class="wizard-counter-label small text-muted">Staff</div>
                              </div>
                              <button
                                type="button"
                                class="btn btn-outline-light wizard-counter-btn"
                                id="wizardSupportCountIncrease"
                                aria-label="Increase support personnel"
                              >
                                +
                              </button>
                            </div>
                          </div>
                          <input type="hidden" id="wizardSupportCountInput" value="1" data-min="1" data-max="10">
                        </div>

                        <div class="wizard-support-field">
                          <p class="text-uppercase small fw-semibold text-muted mb-2">Equipment Needed</p>
                          <div class="wizard-equipment-grid">
                            @foreach ($wizardSupportEquipment as $equipment)
                              @php
                                $inputId = 'supportEquipment_' . $equipment['id'];
                              @endphp
                              <div class="wizard-equipment-option">
                                <input
                                  type="checkbox"
                                  class="btn-check"
                                  id="{{ $inputId }}"
                                  name="supportEquipment[]"
                                  value="{{ $equipment['id'] }}"
                                  data-equipment-name="{{ $equipment['label'] }}"
                                  autocomplete="off"
                                >
                                <label class="wizard-equipment-chip" for="{{ $inputId }}">
                                  {{ $equipment['label'] }}
                                </label>
                              </div>
                            @endforeach
                          </div>
                        </div>

                        <div class="wizard-support-field">
                          <label for="wizardSupportNotes" class="text-uppercase small fw-semibold text-muted mb-1">
                            Additional requirements / notes
                          </label>
                          <textarea
                            id="wizardSupportNotes"
                            class="form-control"
                            rows="3"
                            placeholder="E.g., Need 20 bottles of water, coffee/tea, standing mics, table arrangement, etc."
                          ></textarea>
                          <p class="wizard-form-tip small text-muted mt-2 mb-0">
                            Typical requests: refreshments, AV setup, ushering, table arrangement, remote call support.
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 mt-4 pt-2">
                  <button type="button" class="btn btn-light wizard-back-button" id="wizardBackToDate">
                    Back to Date &amp; Time
                  </button>
                  <button type="button" class="btn btn-room-available" id="wizardNextReview" disabled>
                    Next: Review &amp; Confirm
                  </button>
                </div>
              </div>
            </div>
          </div>

          {{-- Step 4 panel --}}
          <div id="wizardReviewPanel" class="d-none">
            <div class="wizard-review-card card border-0 shadow-sm h-100">
              <div class="card-body p-3 p-md-4">
                <div class="mb-3">
                  
                    Let’s make sure everything looks right.
                  </h2>
                  <p class="text-muted small mb-0">
                    Double-check the room, schedule, and details before sending the request to the facilities team.
                  </p>
                </div>

                <div class="row g-3 wizard-review-grid">
                  <div class="col-12 col-lg-6">
                    <div class="wizard-step-subcard h-100">
                      <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                          <p class="text-uppercase small fw-semibold text-muted mb-1">Room &amp; Schedule</p>
                          <h3 class="h6 mb-0 text-dark">Meeting basics</h3>
                        </div>
                        <span class="badge text-bg-light text-dark-emphasis small">Step 2</span>
                      </div>
                      <dl class="wizard-review-list">
                        <div>
                          <dt>Room</dt>
                          <dd id="wizardReviewRoom">Not selected</dd>
                        </div>
                        <div>
                          <dt>Date</dt>
                          <dd id="wizardReviewDate">—</dd>
                        </div>
                        <div>
                          <dt>Time</dt>
                          <dd id="wizardReviewTime">—</dd>
                        </div>
                        <div>
                          <dt>Duration</dt>
                          <dd id="wizardReviewDuration">—</dd>
                        </div>
                      </dl>
                    </div>
                  </div>

                  <div class="col-12 col-lg-6">
                    <div class="wizard-step-subcard h-100">
                      <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                          <p class="text-uppercase small fw-semibold text-muted mb-1">Booking Details</p>
                          <h3 class="h6 mb-0 text-dark">People &amp; purpose</h3>
                        </div>
                        <span class="badge text-bg-light text-dark-emphasis small">Step 3</span>
                      </div>
                      <dl class="wizard-review-list">
                        <div>
                          <dt>Attendees</dt>
                          <dd id="wizardReviewAttendees">—</dd>
                        </div>
                        <div>
                          <dt>Purpose / Agenda</dt>
                          <dd id="wizardReviewAgenda">—</dd>
                        </div>
                      </dl>
                    </div>
                  </div>

                  <div class="col-12">
                    <div class="wizard-step-subcard wizard-review-support">
                      <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                          <p class="text-uppercase small fw-semibold text-muted mb-1">SFI Support</p>
                          <h3 class="h6 mb-0 text-dark">Add-ons &amp; assistance</h3>
                        </div>
                        <span class="badge text-bg-light text-dark-emphasis small" id="wizardReviewSupportStatus">Not requested</span>
                      </div>
                      <div id="wizardReviewSupportSummary" class="wizard-review-support-summary text-muted small">
                        No additional support requested for this booking.
                      </div>
                    </div>
                  </div>
                </div>

                <div class="wizard-review-actions d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 mt-4 pt-2">
                  <button type="button" class="btn btn-light wizard-back-button" id="wizardBackToDetails">
                    Back to Booking Details
                  </button>
                  <div class="d-flex flex-column flex-md-row align-items-center gap-2">
                    <button type="button" class="btn btn-outline-primary">
                      Save as draft
                    </button>
                    <button type="button" class="btn btn-room-submit btn-lg" id="wizardSubmitRequest">
                      Submit booking request
                    </button>
                  </div>
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
                <span class="badge rounded-pill bg-light text-secondary small">{{ count($userBookings) }}</span>
              </div>
              <p class="small text-muted mb-3">
                @auth
                  Logged in as <strong>{{ auth()->user()->email }}</strong><br>
                  Total Bookings: {{ count($userBookings) }}
                @else
                  Please log in to view your bookings
                @endauth
              </p>

              <ul class="nav nav-pills small mb-3" role="tablist">
                @php
                  $pendingCount = collect($userBookings)->where('status', 'Pending')->count();
                  $approvedCount = collect($userBookings)->where('status', 'Approved')->count();
                @endphp
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" data-bs-toggle="pill" type="button" data-status="pending">
                    Pending ({{ $pendingCount }})
                  </button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" data-bs-toggle="pill" type="button" data-status="approved">
                    Confirmed ({{ $approvedCount }})
                  </button>
                </li>
              </ul>

              <div class="wizard-bookings-list small">
                @forelse ($userBookings as $booking)
                  @php
                    $statusClass = match(strtolower($booking['status'])) {
                      'pending' => 'bg-warning text-dark',
                      'approved' => 'bg-success text-white',
                      'rejected' => 'bg-danger text-white',
                      'cancelled' => 'bg-secondary text-white',
                      default => 'bg-light text-dark',
                    };
                    $bgClass = match(strtolower($booking['status'])) {
                      'pending' => 'bg-warning-subtle',
                      'approved' => 'bg-success-subtle',
                      'rejected' => 'bg-danger-subtle',
                      'cancelled' => 'bg-secondary-subtle',
                      default => 'bg-light',
                    };
                  @endphp
                  <div class="wizard-booking-item border rounded-3 p-2 mb-2 {{ $bgClass }}" data-booking-status="{{ strtolower($booking['status']) }}">
                    <div class="d-flex justify-content-between">
                      <span class="badge {{ $statusClass }} me-2">{{ $booking['status'] }}</span>
                      <span class="text-muted">{{ $booking['date'] }}</span>
                    </div>
                    <div class="fw-semibold mt-1">{{ $booking['room'] }}</div>
                    <div class="text-muted">{{ $booking['time'] }}</div>
                  </div>
                @empty
                  <div class="text-center py-4 text-muted">
                    <p class="mb-0">No bookings yet</p>
                    <small>Your booking requests will appear here</small>
                  </div>
                @endforelse
              </div>
            </div>
          </aside>
        </div>
      </div>
    </section>

    {{-- POST-SUBMIT SUCCESS PANEL --}}
    <section
      id="wizardSuccessPanel"
      class="wizard-success-panel container-fluid px-3 px-lg-5 d-none"
      hidden
      aria-live="polite"
    >
            <div class="wizard-success-card text-center shadow-sm border-0 mx-auto text-white">
        <p class="text-uppercase small fw-semibold wizard-success-muted mb-2">Booking success!</p>
        <h2 class="wizard-success-title mb-2">You're booked, <span id="wizardSuccessUser">{{ auth()->check() ? explode(' ', auth()->user()->name)[0] : 'User' }}</span>!</h2>
        <p class="wizard-success-muted mb-4">We’ve sent your request to the facilities team. You’ll get updates via email.</p>

        <div class="wizard-success-icon mb-4" aria-hidden="true">
          <span>✓</span>
        </div>

        <div class="wizard-success-reference mb-4">
          <p class="text-uppercase small wizard-success-muted mb-1">Reference Code</p>
          <div class="wizard-success-code" id="wizardSuccessCode">ENC-000000</div>
        </div>

        <div class="wizard-success-summary card border-0 shadow-sm text-start mb-4">
          <div class="card-body">
            <div class="wizard-success-summary-row">
              <span class="label">Room</span>
              <span class="value" id="wizardSuccessRoom">—</span>
            </div>
            <div class="wizard-success-summary-row">
              <span class="label">Date</span>
              <span class="value" id="wizardSuccessDate">—</span>
            </div>
            <div class="wizard-success-summary-row">
              <span class="label">Time</span>
              <span class="value" id="wizardSuccessTime">—</span>
            </div>
            <div class="wizard-success-summary-row">
              <span class="label">Purpose</span>
              <span class="value" id="wizardSuccessAgenda">—</span>
            </div>
          </div>
        </div>

        <div class="wizard-success-actions d-flex flex-column flex-md-row justify-content-center gap-2 mb-4">
          <button type="button" class="btn btn-light wizard-success-outline" id="wizardSuccessViewRequests">
            View my requests
          </button>
          <button type="button" class="btn btn-light wizard-success-outline" id="wizardSuccessAddCalendar">
            Add to calendar
          </button>
        </div>

        <hr class="wizard-success-divider my-4">

        <p class="wizard-success-muted mb-3">What would you like to do next?</p>
        <div class="wizard-success-next d-flex flex-column flex-md-row gap-3 justify-content-center">
          <button type="button" class="btn btn-light px-4 wizard-success-light-btn" id="wizardSuccessBookAnother">
            Make another booking
          </button>
          <button type="button" class="btn btn-room-submit px-4" id="wizardSuccessDashboard">
            Go to Dashboard
          </button>
        </div>
      </div>
    </section>
  </section>
@endsection
