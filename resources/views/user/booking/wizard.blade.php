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
      'notificationsCount' => $notificationsCount,
      'showBookingsToggle' => false,
    ])
  </div>
@endsection

@section('content')

  <section class="wizard-shell py-4 py-md-5">
    <div class="container" id="wizardLandingShell">
      <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
          @include('user.booking.wizard.sections.method-selection')
        </div>
      </div>
    </div>
  </section>

  @include('user.booking.wizard.sections.manual-stage', [
    'timeSlots' => $timeSlots,
    'wizardSupportEquipment' => $wizardSupportEquipment,
    'userBookings' => $userBookings,
  ])

  @include('user.booking.wizard.sections.success-panel')
@endsection
