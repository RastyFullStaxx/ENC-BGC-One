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
@endphp

{{-- Top application navbar --}}
<nav class="navbar navbar-expand enc-app-navbar bg-white sticky-top" role="navigation" aria-label="Shared Services Portal">
  <div class="container-fluid align-items-center px-3 px-lg-4 px-xxl-5">

    {{-- Brand --}}
    <a class="navbar-brand d-flex align-items-center gap-3 text-decoration-none py-2 me-auto" href="{{ route('user.dashboard') }}">
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
    <div class="ms-auto d-flex align-items-center gap-2">

      @if($showBookingsToggle)
        {{-- My Bookings toggle (shows/hides the right sidebar panel) --}}
        <button
          type="button"
          id="myBookingsToggle"
          class="btn btn-outline-primary position-relative"
          data-target="{{ $bookingsPanelTarget }}"
          aria-controls="{{ $bookingsPanelControlId }}"
          aria-expanded="false"
          aria-pressed="false"
          data-action="toggle-bookings"
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
      @endif

      {{-- Help/FAQ --}}
      <a
        href="{{ route('faq') }}"
        class="btn btn-light enc-nav-icon-btn"
        aria-label="Help and FAQ"
        title="Help & FAQ"
      >
        {{-- Help/Question icon --}}
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.6"/>
          <path d="M9.5 9a2.5 2.5 0 015 0c0 1.5-2.5 2-2.5 3.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
          <circle cx="12" cy="17" r="0.5" fill="currentColor"/>
        </svg>
      </a>

      {{-- Notifications --}}
      <div class="dropdown">
        <button
          class="btn btn-light position-relative enc-nav-icon-btn"
          id="appNotificationsMenu"
          data-bs-toggle="dropdown"
          aria-expanded="false"
          aria-label="Notifications"
          onclick="loadNotifications()"
        >
          {{-- Bell icon --}}
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M6 8a6 6 0 1112 0c0 5 2 6 2 6H4s2-1 2-6z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M10 20a2 2 0 004 0" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
          </svg>
          @if($notificationsCount > 0)
            <span id="notificationBadge" class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
              <span class="visually-hidden">{{ $notificationsCount }} new notifications</span>
            </span>
          @endif
        </button>
        <div class="dropdown-menu dropdown-menu-end p-0 shadow" aria-labelledby="appNotificationsMenu" style="min-width: 320px; max-height: 400px; overflow-y: auto;">
          <div class="dropdown-header d-flex justify-content-between align-items-center py-2 px-3 border-bottom">
            <span class="fw-semibold">Notifications</span>
            <span id="notificationCount" class="badge bg-primary rounded-pill">{{ $notificationsCount }}</span>
          </div>
          <div id="notificationsList" class="p-2">
            <div class="text-center py-3">
              <div class="spinner-border spinner-border-sm text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <script>
        let notificationsLoaded = false;

        function loadNotifications() {
          if (notificationsLoaded) return;
          
          fetch('{{ route('api.bookings.notifications') }}')
            .then(response => response.json())
            .then(notifications => {
              const container = document.getElementById('notificationsList');
              const countBadge = document.getElementById('notificationCount');
              
              if (notifications.length === 0) {
                container.innerHTML = '<div class="p-3 text-center text-muted small">No new notifications</div>';
              } else {
                container.innerHTML = notifications.map(notif => `
                  <a href="/user/booking/${notif.booking_id}" class="dropdown-item py-2 px-3 text-decoration-none" style="white-space: normal;">
                    <div class="d-flex gap-2">
                      <div class="flex-shrink-0">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" class="text-${getStatusColor(notif.status)}">
                          <circle cx="12" cy="12" r="10" fill="currentColor" opacity="0.2"/>
                          <circle cx="12" cy="12" r="4" fill="currentColor"/>
                        </svg>
                      </div>
                      <div class="flex-grow-1">
                        <div class="fw-semibold small">${notif.facility_name}</div>
                        <div class="small text-muted">${notif.message}</div>
                        <div class="small text-muted mt-1">
                          <span>${notif.date} at ${notif.time}</span>
                          <span class="mx-1">•</span>
                          <span>${notif.created_at}</span>
                        </div>
                      </div>
                    </div>
                  </a>
                  <hr class="dropdown-divider my-1">
                `).join('');
                
                // Remove last divider
                const lastDivider = container.querySelector('hr:last-child');
                if (lastDivider) lastDivider.remove();
              }
              
              countBadge.textContent = notifications.length;
              notificationsLoaded = true;
            })
            .catch(error => {
              console.error('Error loading notifications:', error);
              document.getElementById('notificationsList').innerHTML = 
                '<div class="p-3 text-center text-danger small">Failed to load notifications</div>';
            });
        }

        function getStatusColor(status) {
          const colors = {
            'pending': 'warning',
            'approved': 'success',
            'confirmed': 'success',
            'cancelled': 'danger',
            'rejected': 'danger'
          };
          return colors[status] || 'secondary';
        }
      </script>

      {{-- User type pill --}}
      <span class="enc-user-type badge {{ $roleClass }} fw-semibold text-uppercase">
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
