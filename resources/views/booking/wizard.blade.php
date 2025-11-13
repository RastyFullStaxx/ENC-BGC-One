{{-- resources/views/booking/wizard.blade.php --}}
@extends('layouts.app')

@section('title', 'One Services Booking — Wizard')

@push('styles')  {{-- CSS will come next --}}
  @vite(['resources/css/wizard.css'])
@endpush

@push('scripts') {{-- JS will come after CSS --}}
  @vite(['resources/js/wizard.js'])
@endpush

@section('content')

@php
    $wizardAutoStart = request()->query('start') === 'method' && auth()->check();
@endphp

  {{-- TODO: Replace default layout navbar with a dedicated "logged-in app" navbar
       (Shared Services Portal: logo, My Bookings, notifications, user menu) once
       the new partial is created, e.g. partials.nav-app.blade.php. --}}

  <section class="wizard-shell py-4 py-md-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 col-lg-10">

          {{-- GREETING PANEL (first thing user sees on the booking wizard) --}}
          <div class="wizard-greeting-panel d-flex flex-column justify-content-center align-items-center text-center p-4 p-md-5 {{ $wizardAutoStart ? 'd-none' : '' }}">

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
                data-auth="{{ auth()->check() ? '1' : '0' }}"
              >
                Let’s Begin
              </button>

              <div class="wizard-greeting-cta-note small mt-3 text-muted">
                @guest
                  You’ll be asked to sign in or create an account after this step.
                @else
                  You’re signed in as
                  <span class="fw-semibold">
                    {{ auth()->user()->name ?? auth()->user()->email }}
                  </span>.
                @endguest
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
            class="wizard-method-section {{ $wizardAutoStart ? '' : 'd-none' }} mt-4"
            data-auto-start="{{ $wizardAutoStart ? '1' : '0' }}"
          >
            <div class="text-center mb-4">
              <h2 class="wizard-method-title mb-2" tabindex="-1">
                How would you like to find your room?
              </h2>
              <p class="wizard-method-subtitle mb-0">
                Choose the method that works best for you
              </p>
            </div>

            <div class="row g-4 justify-content-center">
              {{-- Smart Book Finder (Recommended) --}}
              <div class="col-12 col-md-6 col-xl-5">
                <button type="button"
                        class="wizard-method-card is-recommended w-100 text-start"
                        data-method="smart">
                  <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center gap-2">
                      <span class="wizard-method-icon d-inline-flex align-items-center justify-content-center rounded-3">
                        {{-- tiny spark/automation icon --}}
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
                          Answer a few quick questions and we’ll suggest the best available room for your needs.
                          Fast and personalized.
                        </div>
                      </div>
                    </div>

                    <span class="wizard-method-badge">Recommended</span>
                  </div>

                  <div class="wizard-method-footnote d-flex align-items-center gap-1">
                    <span aria-hidden="true">⚡</span>
                    <span>Saves time by filtering based on your needs</span>
                  </div>
                </button>
              </div>

              {{-- Browse All Rooms --}}
              <div class="col-12 col-md-6 col-xl-5">
                <button type="button"
                        class="wizard-method-card w-100 text-start"
                        data-method="manual">
                  <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center gap-2">
                      <span class="wizard-method-icon d-inline-flex align-items-center justify-content-center rounded-3">
                        {{-- tiny grid/calendar icon --}}
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
                          See the complete catalog of rooms manually and filter by your preferences.
                          Perfect if you already know what you’re looking for.
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="wizard-method-footnote d-flex align-items-center gap-1">
                    <span aria-hidden="true">👁️</span>
                    <span>View all rooms, even if currently unavailable</span>
                  </div>
                </button>
              </div>
            </div>
          </section>

          {{-- ==============================
               FUTURE WIZARD CONTENT (TODOs)
             =============================== --}}

          {{-- TODO: Step 1 (Manual path) — Browse All Rooms
               Layout similar to Figma:
               • Left main column:
                 - Filters row: search input, floor dropdown, size dropdown
                 - Grid of room cards with photo, name, capacity, location, badges
                 - "Next: Select Date & Time" button at bottom
               • Right sidebar:
                 - "Your Bookings" panel with Pending/Confirmed tabs and list items. --}}

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
  </section>
@endsection
