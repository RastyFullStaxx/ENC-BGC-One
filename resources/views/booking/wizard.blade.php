{{-- resources/views/booking/wizard.blade.php --}}
@extends('layouts.app')

@section('title', 'Book a Room')

@push('styles')  {{-- CSS will come next --}}
  @vite(['resources/css/wizard.css'])
@endpush

@push('scripts') {{-- JS will come after CSS --}}
  @vite(['resources/js/wizard.js'])
@endpush

@section('content')
<div class="container my-4 my-md-5">

  {{-- STEPPER HEADER (minimal; JS updates progress + labels) --}}
  <div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="flex-grow-1 me-lg-4">
          <div class="progress" style="height:8px;">
            <div id="wizardProgress" class="progress-bar bg-primary" role="progressbar"
                 style="width: 20%;" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
          <div class="d-flex small text-secondary mt-2" id="wizardLabels" aria-live="polite">
            <div class="me-auto">
              <strong id="wizardStepTitle">Step 1 — Smart Room Finder</strong>
              <span class="ms-2" id="wizardStepHelp">Answer a few quick questions.</span>
            </div>
            <div><span id="wizardStepCounter">Step 1 of 5</span></div>
          </div>
        </div>
        <nav class="wizard-nav" aria-label="Booking steps">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item active" aria-current="step"><span class="badge rounded-pill text-bg-primary">1</span></li>
            <li class="breadcrumb-item"><span class="badge rounded-pill text-bg-light border">2</span></li>
            <li class="breadcrumb-item"><span class="badge rounded-pill text-bg-light border">3</span></li>
            <li class="breadcrumb-item"><span class="badge rounded-pill text-bg-light border">4</span></li>
            <li class="breadcrumb-item"><span class="badge rounded-pill text-bg-light border">5</span></li>
          </ol>
        </nav>
      </div>
    </div>
  </div>

  <form id="bookingForm" method="POST" action="{{ route('bookings.store') }}" novalidate>
    @csrf

    <div class="row g-4">

      {{-- MAIN COLUMN --}}
      <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
          <div class="card-body p-3 p-md-4">

            {{-- =========================
                 STEP 1 — SMART ROOM FINDER
               ========================= --}}
            <fieldset class="wizard-step" id="step-1" role="group" aria-labelledby="step1-title">
              <legend class="visually-hidden" id="step1-title">Smart Room Finder</legend>

              {{-- Friendly greeting --}}
              <div class="mb-3 pb-2 border-bottom">
                <h4 class="mb-1">Hi there 👋</h4>
                <p class="text-secondary mb-0">Let’s find the right room — one quick question at a time.</p>
              </div>

              <h5 class="mb-3">Smart Room Finder</h5>

              {{-- Q1: Room type --}}
              <div class="ask" data-ask="1">
                <label class="form-label fw-semibold">What kind of room do you need?</label>
                <div class="d-flex gap-2 flex-wrap" role="group" aria-label="Room type">
                  @php
                    $types = [
                      ['id'=>'meeting','label'=>'Meeting Room'],
                      ['id'=>'board','label'=>'Boardroom'],
                      ['id'=>'training','label'=>'Training / Workshop'],
                      ['id'=>'other','label'=>'Other Facility'],
                    ];
                  @endphp
                  @foreach($types as $t)
                    <input type="radio" class="btn-check" name="room_type" id="type-{{ $t['id'] }}" value="{{ $t['label'] }}" autocomplete="off" required>
                    <label class="btn btn-outline-primary" for="type-{{ $t['id'] }}">{{ $t['label'] }}</label>
                  @endforeach
                </div>
                <div class="invalid-feedback d-block mt-1" data-error="room_type" style="display:none;">Please choose a room type.</div>
                <div class="mt-3 d-flex justify-content-end">
                  <button type="button" class="btn btn-primary" data-next-ask>Next</button>
                </div>
              </div>

              {{-- Q2: Capacity --}}
              <div class="ask d-none" data-ask="2" aria-hidden="true">
                <label for="capacity" class="form-label fw-semibold">How many people are coming?</label>
                <div class="row g-2 align-items-end">
                  <div class="col-8 col-sm-6 col-md-4">
                    <input type="number" class="form-control" id="capacity" name="capacity" min="1" step="1" placeholder="e.g., 8" required>
                    <div class="invalid-feedback">Please enter a number.</div>
                  </div>
                  <div class="col-4">
                    <button type="button" class="btn btn-primary" data-next-ask>Next</button>
                  </div>
                </div>
              </div>

              {{-- Q3: Preferred building(s) --}}
              <div class="ask d-none" data-ask="3" aria-hidden="true">
                <label class="form-label fw-semibold">Preferred building(s)</label>
                <div class="d-flex gap-2 flex-wrap" role="group" aria-label="Preferred buildings">
                  @php $buildings = ['Building 1','Building 2','Annex']; @endphp
                  @foreach($buildings as $b)
                    <input type="checkbox" class="btn-check" name="buildings[]" id="bld-{{ Str::slug($b) }}" value="{{ $b }}">
                    <label class="btn btn-outline-primary" for="bld-{{ Str::slug($b) }}">{{ $b }}</label>
                  @endforeach
                </div>
                <div class="form-text">Optional — we’ll still show other buildings if they fit.</div>
                <div class="mt-3 d-flex justify-content-end">
                  <button type="button" class="btn btn-primary" data-next-ask>Next</button>
                </div>
              </div>

              {{-- Q4: Must-have equipment --}}
              <div class="ask d-none" data-ask="4" aria-hidden="true">
                <label class="form-label fw-semibold">Any must-have equipment?</label>
                <div class="row g-2">
                  @php $equip = ['Projector','Large Display','Whiteboard','Video Conferencing','Sound System']; @endphp
                  @foreach($equip as $i => $label)
                    <div class="col-12 col-sm-6">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="{{ $label }}" id="eq{{ $i }}" name="equipment[]">
                        <label class="form-check-label" for="eq{{ $i }}">{{ $label }}</label>
                      </div>
                    </div>
                  @endforeach
                </div>

                {{-- Actions: Find or Manual --}}
                <div class="mt-4 d-flex flex-wrap gap-2">
                  <button type="button" class="btn btn-primary" data-find-rooms>Find me a room</button>
                  <button type="button" class="btn btn-outline-secondary" data-manual-rooms>I’ll pick manually</button>
                </div>
              </div>

              {{-- MATCHING RESULTS (hidden until "Find me a room") --}}
              <div id="finderResults" class="d-none mt-4" aria-live="polite" aria-label="Matching rooms">
                <div class="d-flex align-items-center justify-content-between mb-2">
                  <h6 class="mb-0">Matching rooms</h6>
                  <button type="button" class="btn btn-sm btn-outline-secondary" data-edit-answers>Edit answers</button>
                </div>

                <div class="row g-3" id="resultsGrid" data-empty-text="No perfect matches. Try editing answers or pick manually.">
                  {{-- JS will inject results; we also keep a small fallback list --}}
                  @php
                    $rooms = $rooms ?? [
                      ['id'=>1,'name'=>'Conference Room A','cap'=>10,'amenities'=>['Projector','Whiteboard'],'photo'=>'https://images.unsplash.com/photo-1507209696998-3c532be9b2b1?q=80&w=1200&auto=format&fit=crop'],
                      ['id'=>2,'name'=>'Meeting Room C','cap'=>8,'amenities'=>['Display','VC'],'photo'=>'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?q=80&w=1200&auto=format&fit=crop'],
                      ['id'=>3,'name'=>'Conference Room B','cap'=>20,'amenities'=>['Sound','Large Display'],'photo'=>'https://images.unsplash.com/photo-1524758631624-e2822e304c36?q=80&w=1200&auto=format&fit=crop'],
                    ];
                  @endphp
                  @foreach ($rooms as $room)
                    <div class="col-12 col-md-6">
                      <div class="card h-100 room-card" data-room-id="{{ $room['id'] }}" data-capacity="{{ $room['cap'] }}">
                        <div class="position-relative">
                          <img src="{{ $room['photo'] }}" alt="{{ $room['name'] }}" class="card-img-top">
                          <span class="badge position-absolute top-0 end-0 m-2 text-bg-primary">Up to {{ $room['cap'] }}</span>
                        </div>
                        <div class="card-body">
                          <h6 class="card-title mb-1">{{ $room['name'] }}</h6>
                          <div class="small text-secondary mb-2">
                            @foreach ($room['amenities'] as $a)
                              <span class="badge rounded-pill text-bg-light border me-1">{{ $a }}</span>
                            @endforeach
                          </div>
                          <div class="form-check">
                            <input class="form-check-input choose-room" type="radio" name="room_id" id="room-{{ $room['id'] }}" value="{{ $room['id'] }}">
                            <label class="form-check-label" for="room-{{ $room['id'] }}">Select this room</label>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>

                <div class="mt-4 d-flex justify-content-end">
                  <button type="button" class="btn btn-primary" data-next>Next</button>
                </div>
              </div>

              {{-- MANUAL PICK (hidden until “I’ll pick manually”) --}}
              <div id="manualPicker" class="d-none mt-4" aria-live="polite">
                <div class="d-flex align-items-center justify-content-between mb-2">
                  <h6 class="mb-0">Browse all rooms</h6>
                  <button type="button" class="btn btn-sm btn-outline-secondary" data-edit-answers>Change filters</button>
                </div>

                <div class="row g-3" id="roomsGridManual" data-empty-text="No rooms available.">
                  {{-- reuse the same sample rooms --}}
                  @foreach ($rooms as $room)
                    <div class="col-12 col-md-6">
                      <div class="card h-100 room-card" data-room-id="{{ $room['id'] }}" data-capacity="{{ $room['cap'] }}">
                        <div class="position-relative">
                          <img src="{{ $room['photo'] }}" alt="{{ $room['name'] }}" class="card-img-top">
                          <span class="badge position-absolute top-0 end-0 m-2 text-bg-primary">Up to {{ $room['cap'] }}</span>
                        </div>
                        <div class="card-body">
                          <h6 class="card-title mb-1">{{ $room['name'] }}</h6>
                          <div class="small text-secondary mb-2">
                            @foreach ($room['amenities'] as $a)
                              <span class="badge rounded-pill text-bg-light border me-1">{{ $a }}</span>
                            @endforeach
                          </div>
                          <div class="form-check">
                            <input class="form-check-input choose-room" type="radio" name="room_id" id="roomM-{{ $room['id'] }}" value="{{ $room['id'] }}">
                            <label class="form-check-label" for="roomM-{{ $room['id'] }}">Select this room</label>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>

                <div class="mt-4 d-flex justify-content-between">
                  <button type="button" class="btn btn-outline-secondary" data-edit-answers>Back to questions</button>
                  <button type="button" class="btn btn-primary" data-next>Next</button>
                </div>
              </div>
            </fieldset>

            {{-- =========================
                 STEP 2 — DATE & TIME
               ========================= --}}
            <fieldset class="wizard-step d-none" id="step-2" role="group" aria-hidden="true" aria-labelledby="step2-title">
              <legend class="visually-hidden" id="step2-title">Date & Time</legend>

              <h5 class="mb-3">Date &amp; Time</h5>

              {{-- ask-by-ask mini flow --}}
              <div class="ask" data-ask="2-1">
                <label for="date" class="form-label fw-semibold">When do you need it?</label>
                <input type="date" class="form-control" id="date" name="date" required>
                <div class="invalid-feedback">Please choose a date.</div>
                <div class="mt-3 d-flex justify-content-end">
                  <button type="button" class="btn btn-primary" data-next-ask>Next</button>
                </div>
              </div>

              <div class="ask d-none" data-ask="2-2" aria-hidden="true">
                <div class="row g-3">
                  <div class="col-6 col-md-4">
                    <label for="start_time" class="form-label fw-semibold">Start time</label>
                    <input type="time" class="form-control" id="start_time" name="start_time" required>
                    <div class="invalid-feedback">Select a start time.</div>
                  </div>
                  <div class="col-6 col-md-4">
                    <label for="end_time" class="form-label fw-semibold">End time</label>
                    <input type="time" class="form-control" id="end_time" name="end_time" required>
                    <div class="invalid-feedback">Select an end time.</div>
                  </div>
                </div>
                <div class="mt-3 d-flex justify-content-end">
                  <button type="button" class="btn btn-primary" data-next-ask>Next</button>
                </div>
              </div>

              <div class="ask d-none" data-ask="2-3" aria-hidden="true">
                <label class="form-label fw-semibold d-block">Repeat this booking?</label>
                <div class="form-check form-switch mb-2">
                  <input class="form-check-input" type="checkbox" id="repeatToggle" name="repeat_toggle">
                  <label class="form-check-label" for="repeatToggle">Yes, make it recurring</label>
                </div>
                <div id="repeatOptions" class="d-none">
                  <div class="d-flex gap-2 flex-wrap" role="group" aria-label="Recurrence presets">
                    <input type="radio" class="btn-check" name="rrule" id="rec-daily" value="DAILY">
                    <label class="btn btn-outline-primary" for="rec-daily">Daily</label>
                    <input type="radio" class="btn-check" name="rrule" id="rec-weekly" value="WEEKLY">
                    <label class="btn btn-outline-primary" for="rec-weekly">Weekly</label>
                    <input type="radio" class="btn-check" name="rrule" id="rec-custom" value="CUSTOM">
                    <label class="btn btn-outline-primary" for="rec-custom">Custom…</label>
                  </div>
                  <div class="form-text mt-2">We’ll show a simple custom dialog if needed.</div>
                </div>
                <div class="mt-3 d-flex justify-content-between">
                  <button type="button" class="btn btn-outline-secondary" data-prev>Back</button>
                  <button type="button" class="btn btn-primary" data-next>Next</button>
                </div>
              </div>

              {{-- conflict helper --}}
              <div id="conflictBlock" class="d-none mt-3" aria-live="polite">
                <div class="alert alert-warning mb-0">
                  <strong>Heads up:</strong> This time conflicts with another booking.
                  <div class="small mt-1">Closest free alternatives: <a href="#" class="alert-link" data-alt-time="09:30">9:30 AM</a>, <a href="#" class="alert-link" data-alt-time="4:30 PM">4:30 PM</a></div>
                </div>
              </div>
            </fieldset>

            {{-- =========================
                 STEP 3 — PURPOSE & DETAILS (incl. manpower)
               ========================= --}}
            <fieldset class="wizard-step d-none" id="step-3" role="group" aria-hidden="true" aria-labelledby="step3-title">
              <legend class="visually-hidden" id="step3-title">Purpose & Details</legend>
              <h5 class="mb-3">Purpose &amp; Details</h5>

              <div class="ask" data-ask="3-1">
                <label for="purpose" class="form-label fw-semibold">What’s this booking for?</label>
                <input type="text" class="form-control" id="purpose" name="purpose" placeholder="e.g., Project kickoff">
                <div class="mt-3 d-flex justify-content-end"><button type="button" class="btn btn-primary" data-next-ask>Next</button></div>
              </div>

              <div class="ask d-none" data-ask="3-2" aria-hidden="true">
                <div class="row g-3">
                  <div class="col-6 col-md-4">
                    <label for="attendees" class="form-label fw-semibold">Expected attendees</label>
                    <input type="number" class="form-control" id="attendees" name="attendees" min="1" step="1" placeholder="e.g., 8">
                  </div>
                  <div class="col-6 col-md-4">
                    <label for="layout" class="form-label fw-semibold">Room setup</label>
                    <select class="form-select" id="layout" name="layout">
                      <option value="" selected>Standard</option>
                      <option value="boardroom">Boardroom</option>
                      <option value="classroom">Classroom</option>
                      <option value="u-shape">U-shape</option>
                    </select>
                  </div>
                </div>
                <div class="mt-3 d-flex justify-content-end"><button type="button" class="btn btn-primary" data-next-ask>Next</button></div>
              </div>

              <div class="ask d-none" data-ask="3-3" aria-hidden="true">
                <label class="form-label fw-semibold d-block">Need manpower (Building Attendants)?</label>
                <div class="row g-3 align-items-end">
                  <div class="col-6 col-md-4">
                    <label for="manpower_count" class="form-label">How many?</label>
                    <input type="number" class="form-control" id="manpower_count" name="manpower_count" min="0" step="1" value="0">
                  </div>
                  <div class="col-6 col-md-4">
                    <label for="manpower_role" class="form-label">Role</label>
                    <select id="manpower_role" name="manpower_role" class="form-select">
                      <option value="" selected>General Support</option>
                      <option value="av">A/V Support</option>
                      <option value="usher">Ushering</option>
                    </select>
                  </div>
                </div>

                <div class="mt-3">
                  <label for="notes" class="form-label">Special requests (optional)</label>
                  <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any equipment prep or instructions?"></textarea>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                  <button type="button" class="btn btn-outline-secondary" data-prev>Back</button>
                  <button type="button" class="btn btn-primary" data-next>Next</button>
                </div>
              </div>
            </fieldset>

            {{-- =========================
                 STEP 4 — ATTENDEES
               ========================= --}}
            <fieldset class="wizard-step d-none" id="step-4" role="group" aria-hidden="true" aria-labelledby="step4-title">
              <legend class="visually-hidden" id="step4-title">Attendees</legend>
              <h5 class="mb-3">Attendees</h5>

              <div class="ask" data-ask="4-1">
                <label class="form-label fw-semibold">Invite people (internal)</label>
                <input type="text" class="form-control" id="internal_search" placeholder="Type a name or department…">
                <div class="form-text">We’ll suggest from your directory (mocked for now).</div>
                <div class="mt-3 d-flex justify-content-end"><button type="button" class="btn btn-primary" data-next-ask>Next</button></div>
              </div>

              <div class="ask d-none" data-ask="4-2" aria-hidden="true">
                <label for="external_emails" class="form-label fw-semibold">External emails (optional)</label>
                <textarea id="external_emails" name="external_emails" class="form-control" rows="2" placeholder="name@example.com, another@example.com"></textarea>
                <div class="mt-3 d-flex justify-content-end"><button type="button" class="btn btn-primary" data-next-ask>Next</button></div>
              </div>

              <div class="ask d-none" data-ask="4-3" aria-hidden="true">
                <label class="form-label fw-semibold d-block">Visibility</label>
                <div class="d-flex gap-2">
                  <input type="radio" class="btn-check" name="visibility" id="vis-private" value="private" checked>
                  <label class="btn btn-outline-primary" for="vis-private">Private</label>
                  <input type="radio" class="btn-check" name="visibility" id="vis-public" value="public">
                  <label class="btn btn-outline-primary" for="vis-public">Public (internal)</label>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                  <button type="button" class="btn btn-outline-secondary" data-prev>Back</button>
                  <button type="button" class="btn btn-primary" data-next>Next</button>
                </div>
              </div>
            </fieldset>

            {{-- =========================
                 STEP 5 — POLICIES & REVIEW
               ========================= --}}
            <fieldset class="wizard-step d-none" id="step-5" role="group" aria-hidden="true" aria-labelledby="step5-title">
              <legend class="visually-hidden" id="step5-title">Policies & Review</legend>
              <h5 class="mb-3">Review &amp; Policies</h5>

              <div class="row g-3">
                <div class="col-12 col-lg-6">
                  <div class="card bg-body-tertiary border-0 h-100">
                    <div class="card-body">
                      <h6 class="mb-2">Booking Policies</h6>
                      <ul class="small mb-0">
                        <li>No meals in standard meeting rooms. Light refreshments in Executive Boardroom only.</li>
                        <li>Max 4 hours per session (longer requires approval).</li>
                        <li>Lead times: 24h rooms • 48h special facilities • 72h shuttle.</li>
                        <li>Cancel ≥ 12h before start time.</li>
                      </ul>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-lg-6">
                  <div class="card border-0 h-100">
                    <div class="card-body">
                      <h6 class="mb-2">Your Summary</h6>
                      <dl class="row small mb-0" id="reviewSummary" aria-live="polite">
                        <dt class="col-5 text-secondary">Room</dt>
                        <dd class="col-7 mb-2"><span data-bind="room_name">—</span></dd>
                        <dt class="col-5 text-secondary">When</dt>
                        <dd class="col-7 mb-2"><span data-bind="date">—</span>, <span data-bind="start_time">—</span>–<span data-bind="end_time">—</span></dd>
                        <dt class="col-5 text-secondary">Attendees</dt>
                        <dd class="col-7 mb-2"><span data-bind="attendees_label">—</span></dd>
                        <dt class="col-5 text-secondary">Setup</dt>
                        <dd class="col-7 mb-2"><span data-bind="layout_label">Standard</span></dd>
                        <dt class="col-5 text-secondary">Manpower</dt>
                        <dd class="col-7 mb-2"><span data-bind="manpower_label">None</span></dd>
                        <dt class="col-5 text-secondary">Visibility</dt>
                        <dd class="col-7"><span data-bind="visibility_label">Private</span></dd>
                      </dl>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-check my-3">
                <input class="form-check-input" type="checkbox" value="1" id="agree" name="agree" required>
                <label class="form-check-label" for="agree">I agree to the Booking Policies.</label>
                <div class="invalid-feedback">Please agree to continue.</div>
              </div>

              <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary" data-prev>Back</button>
                <button type="submit" class="btn btn-primary">Submit Request</button>
              </div>
            </fieldset>

            {{-- SUCCESS STATE --}}
            <section id="step-success" class="d-none text-center py-4" aria-live="polite">
              <div class="display-6 mb-2">🎉 Request Sent</div>
              <p class="text-secondary">Your booking is submitted. Status: <strong data-bind="status_label">Pending Approval</strong>.</p>
              <div class="d-flex justify-content-center gap-2 mt-3">
                <a class="btn btn-outline-primary" href="{{ url('/bookings') }}">Go to My Bookings</a>
                <a class="btn btn-primary" href="{{ url('/rooms') }}">Start another booking</a>
              </div>
            </section>

          </div>
        </div>
      </div>

      {{-- LIVE SUMMARY SIDEBAR (kept very small to reduce load) --}}
      <aside class="col-lg-4">
        <div class="card border-0 shadow-sm sticky-top" style="top:1rem;">
          <div class="card-body">
            <h6 class="mb-2">Quick Summary</h6>
            <dl class="row small mb-0" id="summary" aria-live="polite">
              <dt class="col-5 text-secondary">Room</dt>
              <dd class="col-7 mb-2"><span data-bind="room_name">—</span></dd>
              <dt class="col-5 text-secondary">When</dt>
              <dd class="col-7 mb-2"><span data-bind="date">—</span>, <span data-bind="start_time">—</span>–<span data-bind="end_time">—</span></dd>
              <dt class="col-5 text-secondary">People</dt>
              <dd class="col-7 mb-2"><span data-bind="capacity">—</span></dd>
              <dt class="col-5 text-secondary">Visibility</dt>
              <dd class="col-7"><span data-bind="visibility_label">Private</span></dd>
            </dl>
            <hr>
            <div class="small text-secondary">
              Progress is saved as you go. You can always go back.
            </div>
          </div>
        </div>
      </aside>

    </div>
  </form>
</div>
@endsection
