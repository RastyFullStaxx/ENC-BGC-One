{{-- resources/views/partials/dashboard-navbar.blade.php --}}
@php
  /** @var int $currentStep */
  $currentStep = $currentStep ?? 1;

  /** @var array<int,array{title:string,desc?:string}> $steps */
  $steps = $steps ?? [
    ['title' => 'Select Room',  'desc' => 'Browse available rooms'],
    ['title' => 'Date & Time',  'desc' => 'Choose when to book'],
    ['title' => 'Details',      'desc' => 'Add booking information'],
    ['title' => 'Confirm',      'desc' => 'Review and submit'],
  ];

  /** Optional counts for badges */
  $bookingsCount      = $bookingsCount      ?? 0;
  $notificationsCount = $notificationsCount ?? 0;

  /** Authenticated user + fallbacks */
  $user        = auth()->user();
  $userName    = $userName    ?? ($user?->name  ?? 'Charles Ramos');
  $userEmail   = $userEmail   ?? ($user?->email ?? 'user.charles@enc.gov');
  $userRole    = $userRole    ?? ($user?->role  ?? 'User');

  // Helper for avatar initial
  $avatarSource = $userName ?: 'U';
  $avatarInitial = strtoupper(function_exists('mb_substr') ? mb_substr($avatarSource, 0, 1) : substr($avatarSource, 0, 1));
@endphp

{{-- Top application navbar --}}
<nav class="navbar navbar-expand enc-app-navbar bg-white sticky-top" role="navigation" aria-label="Shared Services Portal">
  <div class="container-fluid align-items-center px-3 px-lg-4 px-xxl-5">

    {{-- Brand --}}
    <a class="navbar-brand d-flex align-items-center gap-3 text-decoration-none py-2" href="{{ url('/') }}">
      <span class="brand-mark d-inline-flex align-items-center justify-content-center rounded-2" aria-hidden="true">
        {{-- Simple placeholder mark; replace with your SVG/logo as needed --}}
        <span class="fw-bold small">ONE</span>
      </span>
      <span class="brand-copy d-none d-md-inline text-start lh-sm">
        <span class="d-block fw-semibold">Shared Services Portal</span>
        <span class="d-block text-muted small">One-Stop Booking Platform</span>
      </span>
    </a>

    {{-- Right-side controls --}}
    <div class="ms-auto d-flex align-items-center gap-2">

      {{-- My Bookings toggle (shows/hides the right sidebar panel) --}}
      <button
        type="button"
        id="myBookingsToggle"
        class="btn btn-outline-primary position-relative"
        data-target="#wizardBookingsSidebar"
        aria-controls="wizardBookingsSidebar"
        aria-expanded="false"
        aria-pressed="false"
      >
        <span class="d-inline-flex align-items-center gap-2">
          {{-- Calendar icon --}}
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <rect x="3" y="4" width="18" height="17" rx="3" stroke="currentColor" stroke-width="1.6"/>
            <path d="M3 10h18" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
            <path d="M8 2v4M16 2v4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
          </svg>
          <span>My Bookings</span>
        </span>

        @if($bookingsCount > 0)
          <span
            id="myBookingsBadge"
            class="position-absolute top-0 start-100 translate-middle badge rounded-pill text-bg-danger"
            aria-live="polite"
          >
            {{ $bookingsCount }}
          </span>
        @endif
      </button>

      {{-- Notifications --}}
      <div class="dropdown">
        <button
          class="btn btn-light position-relative enc-nav-icon-btn"
          id="appNotificationsMenu"
          data-bs-toggle="dropdown"
          aria-expanded="false"
          aria-label="Notifications"
        >
          {{-- Bell icon --}}
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M6 8a6 6 0 1112 0c0 5 2 6 2 6H4s2-1 2-6z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M10 20a2 2 0 004 0" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
          </svg>
          @if($notificationsCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
              <span class="visually-hidden">{{ $notificationsCount }} new notifications</span>
            </span>
          @endif
        </button>
        <div class="dropdown-menu dropdown-menu-end p-0 shadow" aria-labelledby="appNotificationsMenu" style="min-width: 280px;">
          <div class="p-3 small text-muted">
            @if($notificationsCount > 0)
              You have {{ $notificationsCount }} new notification{{ $notificationsCount > 1 ? 's' : '' }}.
            @else
              No new notifications.
            @endif
          </div>
        </div>
      </div>

      {{-- User type pill --}}
      <span class="enc-user-type badge text-bg-primary fw-semibold text-uppercase">
        {{ $userRole }}
      </span>

      {{-- User Menu --}}
      <div class="dropdown">
        <button
          class="btn btn-light enc-user-menu-btn d-flex align-items-center gap-3 text-start"
          id="appUserMenu"
          data-bs-toggle="dropdown"
          aria-expanded="false"
        >
          <span class="avatar rounded-circle d-inline-flex align-items-center justify-content-center bg-light border">
            <span class="small fw-semibold text-muted">{{ $avatarInitial }}</span>
          </span>
          <span class="d-none d-sm-inline">
            <span class="d-block fw-semibold">{{ $userName }}</span>
            <span class="d-block small text-muted">{{ $userEmail }}</span>
          </span>
        </button>

        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="appUserMenu">
          <li class="dropdown-header small">
            <div class="fw-semibold">{{ $userName }}</div>
            <div class="text-muted">{{ $userEmail }}</div>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="#">Profile</a></li>
          <li><a class="dropdown-item" href="#">Settings</a></li>
          @auth
            <li><hr class="dropdown-divider"></li>
            <li>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item">Sign out</button>
              </form>
            </li>
          @endauth
        </ul>
      </div>

    </div>
  </div>
</nav>

{{-- Progress indicator (directly under the navbar, not in a card) --}}
<nav class="enc-stepper-wrap bg-body" aria-label="Booking progress">
  <div class="container-fluid px-3 px-lg-4 px-xxl-5">
    <ol class="enc-stepper list-unstyled d-flex align-items-stretch justify-content-between gap-3 m-0 py-3">
      @foreach ($steps as $i => $step)
        @php
          $n = $i + 1;
          $state = $currentStep > $n ? 'is-complete' : ($currentStep === $n ? 'is-active' : 'is-upcoming');
        @endphp

        <li
          class="enc-stepper-item d-flex align-items-center text-center flex-grow-1 {{ $state }}"
          aria-current="{{ $currentStep === $n ? 'step' : 'false' }}"
        >
          <div class="enc-stepper-node d-flex flex-column align-items-center flex-shrink-0 w-100">
            <span class="enc-stepper-circle d-inline-flex align-items-center justify-content-center" data-step-number="{{ $n }}">{{ $n }}</span>
            <span class="enc-stepper-title mt-2 fw-semibold">{{ $step['title'] }}</span>
            @if(!empty($step['desc']))
              <span class="enc-stepper-desc small text-muted">{{ $step['desc'] }}</span>
            @endif
          </div>

          {{-- Divider line to the next step (purely presentational; CSS draws the line) --}}
          @if(!$loop->last)
            <div class="enc-stepper-line flex-grow-1 ms-3" aria-hidden="true"></div>
          @endif
        </li>
      @endforeach
    </ol>
  </div>
</nav>
