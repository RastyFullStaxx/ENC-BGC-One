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
  $showStepper = $showStepper ?? true;

  /** Optional counts for badges */
  $bookingsCount      = $bookingsCount      ?? 0;
  $notificationsCount = $notificationsCount ?? 0;

  /** Authenticated user + fallbacks */
  $user        = auth()->user();
  $userName    = $userName    ?? ($user?->name  ?? 'Charles Ramos');
  $userEmail   = $userEmail   ?? ($user?->email ?? 'user.charles@enc.gov');
  $userRole    = $userRole    ?? ($user?->role  ?? 'User');
  $bookingsPanelTarget = $bookingsPanelTarget ?? '#wizardBookingsSidebar';
  $bookingsPanelControlId = ltrim($bookingsPanelTarget, '#') ?: 'wizardBookingsSidebar';
  $showBookingsToggle = $showBookingsToggle ?? true;
  $roleClass = strtolower($userRole) === 'admin' ? 'text-bg-danger' : 'text-bg-primary';

  // Helper for avatar initial
  $avatarSource = $userName ?: 'U';
  $avatarInitial = strtoupper(function_exists('mb_substr') ? mb_substr($avatarSource, 0, 1) : substr($avatarSource, 0, 1));

  /** Public/guest variant toggles */
  $mode = $mode ?? 'app'; // app | public
  $isPublicNav = $mode === 'public';
  $showNotifications = $showNotifications ?? !$isPublicNav;
  $showUserMenu = $showUserMenu ?? !$isPublicNav;
  $showRolePill = $showRolePill ?? !$isPublicNav;
  $homeRoute = $homeRoute ?? ($isPublicNav ? route('landing') : route('user.dashboard'));
  $brandHomeRoute = route('landing');
  $guestActions = $guestActions ?? [
    ['label' => 'Help/FAQ', 'href' => route('faq'), 'variant' => 'ghost'],
    ['label' => 'Log In', 'href' => route('login'), 'variant' => 'outline'],
    ['label' => 'Sign Up', 'href' => route('signup.index'), 'variant' => 'primary'],
  ];
@endphp

{{-- Top application navbar --}}
<nav class="navbar navbar-expand enc-app-navbar bg-white sticky-top" role="navigation" aria-label="Shared Services Portal">
  <div class="container-fluid align-items-center px-3 px-lg-4 px-xxl-5">

    {{-- Brand --}}
    <a class="navbar-brand d-flex align-items-center gap-3 text-decoration-none py-2 me-auto" href="{{ $brandHomeRoute }}">
        <!-- Replace ONE with image -->
        <img src="{{ asset('images/enclogo.png') }}" alt="Enclogo" style="height: 60px; width: auto;" class="d-inline-block align-middle">

        <!-- Brand text -->
        <span class="brand-copy d-none d-md-inline text-start lh-sm">
            <span class="d-block fw-semibold">{{ $brand ?? 'ONE Services' }}</span>
            {{-- <span class="d-block text-muted small">Shared Services Portal</span> --}}
            <span class="d-block text-muted small">One-Stop Booking Platform</span>
        </span>
    </a>


    {{-- Right-side controls --}}
    {{-- Right-side controls --}}
    <div class="ms-auto d-flex align-items-center gap-2">

      {{-- Help/FAQ --}}
      <a
        href="{{ route('faq') }}"
        class="btn btn-light border-0 enc-nav-icon-btn"
        aria-label="Help and FAQ"
        title="Help & FAQ"
      >
        {{-- Help/Question icon --}}
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.6"/>
          <path d="M9.5 9a2.5 2.5 0 115 0c0 1.5-2.5 2-2.5 3.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
          <circle cx="12" cy="17" r="0.5" fill="currentColor"/>
        </svg>
      </a>

      @if ($showNotifications)
        {{-- Notification Bell --}}
        @livewire('notification-bell', ['count' => $notificationsCount])
      @endif

      @if(($showHomeButton ?? false) && ($homeButtonRoute ?? false))
        <a
          href="{{ $homeButtonRoute }}"
          class="btn btn-light border-0 enc-nav-icon-btn d-inline-flex align-items-center gap-2"
          aria-label="Home"
          title="Home"
        >
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M3 10.5 12 4l9 6.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M5 10v9h14v-9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M10 19v-5a2 2 0 0 1 2-2v0a2 2 0 0 1 2 2v5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          <span class="d-none d-md-inline fw-semibold">Home</span>
        </a>
      @endif

      @if ($showRolePill)
        {{-- User type pill --}}
        <span class="enc-user-type badge {{ $roleClass }} fw-semibold text-uppercase">
          {{ $userRole }}
        </span>
      @endif

      @if ($showUserMenu)
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
            <li><a class="dropdown-item" href="{{ route('user.profile') }}">Profile</a></li>
            {{-- <li><a class="dropdown-item" href="{{ route('faq') }}">Faq</a></li> --}}
            <li><a class="dropdown-item" href="{{ route('user.settings') }}">Settings</a></li>
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
      @else
        @foreach ($guestActions as $action)
          @php
            $variant = $action['variant'] ?? 'ghost';
            $classes = match($variant) {
              'primary' => 'btn btn-primary',
              'outline' => 'btn btn-outline-secondary',
              default => 'btn btn-light border-0 enc-nav-icon-btn'
            };
          @endphp
          <a class="{{ $classes }}" href="{{ $action['href'] ?? '#' }}">
            {{ $action['label'] ?? 'Action' }}
          </a>
        @endforeach
      @endif

    </div>
  </div>
</nav>

{{-- Progress indicator (directly under the navbar, not in a card) --}}
@if($showStepper && count($steps))
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
          <div class="enc-stepper-node d-flex flex-column align-items-center flex-shrink-0">
            <span class="enc-stepper-circle d-inline-flex align-items-center justify-content-center" data-step-number="{{ $n }}">{{ $n }}</span>
            <span class="enc-stepper-title mt-2 fw-semibold">{{ $step['title'] }}</span>
            @if(!empty($step['desc']))
              <span class="enc-stepper-desc small text-muted">{{ $step['desc'] }}</span>
            @endif
          </div>

          {{-- Divider line to the next step (purely presentational; CSS draws the line) --}}
          @if(!$loop->last)
            <div class="enc-stepper-line flex-grow-1" aria-hidden="true"></div>
          @endif
        </li>
      @endforeach
    </ol>
  </div>
</nav>
@endif
