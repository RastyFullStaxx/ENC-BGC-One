@extends('layouts.app')

{{-- resources/views/booking/wizard.blade.php --}}

@push('styles')
  @vite(['resources/css/wizard.css'])
@endpush

@push('scripts')
  @vite(['resources/js/wizard.js'])
@endpush

@section('title', 'Book a Resource')

@section('content')
<div class="container my-4 my-md-5">

  {{-- Stepper header --}}
  <div class="wizard-stepper card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        {{-- Progress meter (aria-live updated by JS) --}}
        <div class="flex-grow-1 me-lg-4">
          <div class="progress" style="height: 8px;" aria-hidden="true">
            <div class="progress-bar bg-primary" role="progressbar"
                 style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"
                 id="wizardProgress"></div>
          </div>
          <div class="d-flex small text-secondary mt-2" id="wizardLabels" aria-live="polite">
            <div class="me-auto">
              <strong id="wizardStepTitle">1. Choose Resource</strong>
              <span class="ms-2" id="wizardStepHelp" class="text-secondary">Pick date, time and a room.</span>
            </div>
            <div><span id="wizardStepCounter">Step 1 of 3</span></div>
          </div>
        </div>

        {{-- Clickable step dots (progressive disclosure; JS will manage) --}}
        <nav class="wizard-nav" aria-label="Booking steps">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item active" aria-current="step">
              <span class="badge rounded-pill text-bg-primary">1</span>
              <span class="ms-1 d-none d-sm-inline">Choose</span>
            </li>
            <li class="breadcrumb-item">
              <span class="badge rounded-pill text-bg-light border">2</span>
              <span class="ms-1 d-none d-sm-inline">Details</span>
            </li>
            <li class="breadcrumb-item">
              <span class="badge rounded-pill text-bg-light border">3</span>
              <span class="ms-1 d-none d-sm-inline">Review</span>
            </li>
          </ol>
        </nav>
      </div>
    </div>
  </div>

  <form id="bookingForm" method="POST" action="{{ route('bookings.store') }}" novalidate>
    @csrf

    <div class="row g-4">

      {{-- Main flow --}}
      <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
          <div class="card-body p-3 p-md-4">

            {{-- STEP 1: Choose Resource --}}
            <fieldset class="wizard-step" id="step-1" aria-labelledby="step1-title" role="group">
              <legend class="visually-hidden" id="step1-title">Choose resource</legend>

              <div class="mb-3">
                <h5 class="mb-1">Choose Resource</h5>
                <p class="text-secondary small mb-0">Pick date, time, capacity and preferred room.</p>
              </div>

              <div class="row g-3">
                <div class="col-12 col-md-4">
                  <label for="date" class="form-label">Date</label>
                  <input type="date" class="form-control" id="date" name="date" required>
                  <div class="invalid-feedback">Please choose a date.</div>
                </div>
                <div class="col-6 col-md-4">
                  <label for="start_time" class="form-label">Start time</label>
                  <input type="time" class="form-control" id="start_time" name="start_time" required>
                  <div class="invalid-feedback">Select a start time.</div>
                </div>
                <div class="col-6 col-md-4">
                  <label for="duration" class="form-label">Duration</label>
                  <select id="duration" name="duration" class="form-select" required>
                    <option value="" selected disabled>Choose…</option>
                    <option value="30">30 mins</option>
                    <option value="60">1 hour</option>
                    <option value="90">1.5 hours</option>
                    <option value="120">2 hours</option>
                    <option value="180">3 hours</option>
                    <option value="240">4 hours</option>
                  </select>
                  <div class="invalid-feedback">Pick a duration.</div>
                </div>

                <div class="col-12 col-md-4">
                  <label for="capacity" class="form-label">Attendees</label>
                  <input type="number" class="form-control" id="capacity" name="capacity" min="1" step="1" placeholder="e.g., 8" required>
                  <div class="invalid-feedback">How many people?</div>
                </div>

                <div class="col-12 col-md-8">
                  <label for="room_type" class="form-label">Room type</label>
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
                  <div class="form-text">We’ll filter rooms based on capacity & type.</div>
                  <div class="invalid-feedback d-block" data-error-roomtype style="display:none;">Please choose a room type.</div>
                </div>
              </div>

              {{-- Available rooms (server can prefill; JS will filter) --}}
              <hr class="my-4">

              <div class="d-flex align-items-center justify-content-between mb-2">
                <h6 class="mb-0">Available Rooms</h6>
                <div class="d-flex gap-2">
                  <select class="form-select form-select-sm" id="sortRooms" style="width:auto;">
                    <option value="soonest">Soonest start</option>
                    <option value="capacity">Capacity</option>
                    <option value="name">Name (A–Z)</option>
                  </select>
                </div>
              </div>

              <div class="row g-3" id="roomsGrid" data-empty-text="No rooms match your filters.">
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
                          <label class="form-check-label" for="room-{{ $room['id'] }}">
                            Select this room
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>

              <div class="mt-4 d-flex justify-content-end">
                <button type="button" class="btn btn-primary" data-next>Continue</button>
              </div>
            </fieldset>

            {{-- STEP 2: Booking Details --}}
            <fieldset class="wizard-step d-none" id="step-2" aria-labelledby="step2-title" role="group" aria-hidden="true">
              <legend class="visually-hidden" id="step2-title">Booking details</legend>

              <div class="mb-3">
                <h5 class="mb-1">Booking Details</h5>
                <p class="text-secondary small mb-0">Tell us what you need—we’ll prep the room.</p>
              </div>

              <div class="row g-3">
                <div class="col-12">
                  <label for="purpose" class="form-label">Meeting purpose</label>
                  <input type="text" class="form-control" id="purpose" name="purpose" placeholder="e.g., Quarterly planning">
                </div>

                <div class="col-6 col-md-4">
                  <label for="layout" class="form-label">Room setup</label>
                  <select class="form-select" id="layout" name="layout">
                    <option value="" selected>Standard</option>
                    <option value="boardroom">Boardroom</option>
                    <option value="classroom">Classroom</option>
                    <option value="u-shape">U-shape</option>
                  </select>
                </div>

                <div class="col-6 col-md-4">
                  <label for="refreshments" class="form-label">Refreshments</label>
                  <select class="form-select" id="refreshments" name="refreshments">
                    <option value="" selected>None</option>
                    <option value="coffee">Coffee/Tea</option>
                    <option value="snacks">Snacks</option>
                  </select>
                </div>

                <div class="col-12 col-md-4">
                  <label for="equipment" class="form-label">Extra equipment</label>
                  <select class="form-select" id="equipment" name="equipment" multiple>
                    <option value="adapter">HDMI Adapter</option>
                    <option value="mic">Microphone</option>
                    <option value="speaker">Portable Speaker</option>
                    <option value="flipchart">Flip Chart</option>
                  </select>
                  <div class="form-text">Hold Ctrl/Cmd for multi-select.</div>
                </div>

                <div class="col-12">
                  <label for="notes" class="form-label">Notes for Facilities (optional)</label>
                  <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any special instructions?"></textarea>
                </div>
              </div>

              <div class="mt-4 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary" data-prev>Back</button>
                <button type="button" class="btn btn-primary" data-next>Continue</button>
              </div>
            </fieldset>

            {{-- STEP 3: Review & Confirm --}}
            <fieldset class="wizard-step d-none" id="step-3" aria-labelledby="step3-title" role="group" aria-hidden="true">
              <legend class="visually-hidden" id="step3-title">Review and confirm</legend>

              <div class="mb-3">
                <h5 class="mb-1">Review &amp; Confirm</h5>
                <p class="text-secondary small mb-0">Please verify the details below and accept the policies.</p>
              </div>

              <div class="card bg-body-tertiary border-0 mb-3">
                <div class="card-body">
                  <div class="row g-3 small">
                    <div class="col-sm-6">
                      <div class="text-secondary">Date &amp; Time</div>
                      <div><span data-bind="date">—</span>, <span data-bind="start_time">—</span> • <span data-bind="duration_label">—</span></div>
                    </div>
                    <div class="col-sm-6">
                      <div class="text-secondary">Room</div>
                      <div><span data-bind="room_name">—</span> • Up to <span data-bind="capacity">—</span> people</div>
                    </div>
                    <div class="col-sm-6">
                      <div class="text-secondary">Setup</div>
                      <div><span data-bind="layout_label">Standard</span></div>
                    </div>
                    <div class="col-sm-6">
                      <div class="text-secondary">Extras</div>
                      <div><span data-bind="extras_label">None</span></div>
                    </div>
                    <div class="col-12">
                      <div class="text-secondary">Purpose</div>
                      <div><span data-bind="purpose">—</span></div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" value="1" id="agree" name="agree" required>
                <label class="form-check-label" for="agree">
                  I have read and agree to the <a href="{{ url('/policies') }}" target="_blank" rel="noopener">Booking Policies</a>.
                </label>
                <div class="invalid-feedback">You must agree before submitting.</div>
              </div>

              <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary" data-prev>Back</button>
                <button type="submit" class="btn btn-primary">Confirm Booking</button>
              </div>
            </fieldset>

            {{-- STEP 4 (template): Success --}}
            <section id="step-success" class="d-none text-center py-4" aria-live="polite">
              <div class="display-6 mb-2">🎉 Booked!</div>
              <p class="text-secondary">Your reservation has been created. A confirmation email is on its way.</p>
              <div class="d-flex justify-content-center gap-2 mt-3">
                <a class="btn btn-outline-primary" href="{{ url('/bookings') }}">View My Bookings</a>
                <a class="btn btn-primary" href="{{ url('/rooms') }}">Book Another</a>
              </div>
            </section>

          </div>
        </div>
      </div>

      {{-- Live summary (right) --}}
      <aside class="col-lg-4">
        <div class="card border-0 shadow-sm sticky-top" style="top: 1rem;">
          <div class="card-body">
            <div class="d-flex align-items-center gap-2 mb-2">
              {{-- calendar icon --}}
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" class="text-primary">
                <rect x="3" y="4" width="18" height="17" rx="3" stroke="currentColor" stroke-width="1.6"/>
                <path d="M8 2v4M16 2v4M3 9h18" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
              </svg>
              <h6 class="mb-0">Your Booking</h6>
            </div>

            <dl class="row small mb-0" id="summary" aria-live="polite">
              <dt class="col-5 text-secondary">Date</dt>
              <dd class="col-7 mb-2"><span data-bind="date">—</span></dd>

              <dt class="col-5 text-secondary">Time</dt>
              <dd class="col-7 mb-2"><span data-bind="start_time">—</span> • <span data-bind="duration_label">—</span></dd>

              <dt class="col-5 text-secondary">Room</dt>
              <dd class="col-7 mb-2"><span data-bind="room_name">—</span></dd>

              <dt class="col-5 text-secondary">People</dt>
              <dd class="col-7 mb-2"><span data-bind="capacity">—</span></dd>

              <dt class="col-5 text-secondary">Setup</dt>
              <dd class="col-7 mb-2"><span data-bind="layout_label">Standard</span></dd>

              <dt class="col-5 text-secondary">Extras</dt>
              <dd class="col-7"><span data-bind="extras_label">None</span></dd>
            </dl>

            <hr>
            <div class="d-grid gap-2">
              <button class="btn btn-outline-primary" type="button" data-jump="1">Edit Choose</button>
              <button class="btn btn-outline-primary" type="button" data-jump="2">Edit Details</button>
            </div>
          </div>
        </div>
      </aside>
    </div>
  </form>
</div>
@endsection
