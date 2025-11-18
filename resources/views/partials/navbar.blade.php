@php
    $user = auth()->user();
    $userName = $user?->name ?? 'Guest User';
    $userEmail = $user?->email ?? '';
    $avatarInitial = strtoupper($userName[0] ?? 'U');
@endphp

<nav class="navbar navbar-expand-md bg-white sticky-top site-header header-slide">
  <div class="container d-flex align-items-center justify-content-between flex-wrap flex-md-nowrap gap-3">
    <a class="navbar-brand d-flex align-items-center gap-3 text-decoration-none py-2 me-auto" href="{{ route('landing') }}">
      <img src="{{ asset('images/enclogo.png') }}" alt="Enclogo" style="height: 60px; width: auto;" class="d-inline-block align-middle">
      <span class="brand-copy d-none d-md-inline text-start lh-sm">
        <span class="d-block fw-semibold">{{ $brand ?? 'ONE Services' }}</span>
        <span class="d-block text-muted small">Shared Services Portal</span>
      </span>
    </a>

    <button class="navbar-toggler ms-auto d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#topNav"
            aria-controls="topNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div id="topNav" class="navbar-collapse collapse show d-md-flex justify-content-md-end flex-grow-0">
      <ul class="navbar-nav align-items-center ms-md-auto ms-lg-0 flex-row flex-md-row gap-2">
        <li class="nav-item me-2">
          <a class="btn btn-light border-0 enc-nav-icon-btn" href="{{ route('faq') }}" aria-label="FAQs">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.6"/>
              <path d="M9.5 9a2.5 2.5 0 115 0c0 1.5-2.5 2-2.5 3.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
              <circle cx="12" cy="17" r="0.5" fill="currentColor"/>
            </svg>
          </a>
        </li>

        @auth
          <li class="nav-item me-2">
            <livewire:notification-bell />
          </li>
          <li class="nav-item me-2">
            <a class="btn btn-light border-0 enc-nav-icon-btn" href="{{ route('user.dashboard') }}" aria-label="Dashboard">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M3 10l9-7 9 7v10a1 1 0 01-1 1h-5a1 1 0 01-1-1v-5H9v5a1 1 0 01-1 1H3a1 1 0 01-1-1V10z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </a>
          </li>

          <li class="nav-item dropdown">
            <button class="btn btn-light enc-user-menu-btn d-flex align-items-center gap-3 text-start"
                    id="landingUserMenu"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">
              <span class="avatar rounded-circle d-inline-flex align-items-center justify-content-center bg-light border">
                <span class="small fw-semibold text-muted">{{ $avatarInitial }}</span>
              </span>
              <span class="d-none d-sm-inline">
                <span class="d-block fw-semibold">{{ $userName }}</span>
                <span class="d-block small text-muted">{{ $userEmail }}</span>
              </span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="landingUserMenu">
              <li><a class="dropdown-item" href="{{ route('user.dashboard') }}">Dashboard</a></li>
              <li><a class="dropdown-item" href="{{ route('user.profile') }}">Profile</a></li>
              <li><a class="dropdown-item" href="{{ route('user.settings') }}">Settings</a></li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="dropdown-item">Sign out</button>
                </form>
              </li>
            </ul>
          </li>
        @else
          <li class="nav-item me-2">
            <a class="btn btn-outline-secondary" href="{{ route('login') }}">Log In</a>
          </li>
          <li class="nav-item">
            <a class="btn btn-primary" href="{{ route('signup.index') }}">Sign Up</a>
          </li>
        @endauth
      </ul>
    </div>
  </div>
</nav>
