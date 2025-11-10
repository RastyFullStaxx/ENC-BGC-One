<nav class="navbar navbar-expand-md bg-white sticky-top site-header header-slide">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="{{ route('landing') }}">
      <span class="d-inline-flex align-items-center justify-content-center rounded-3 border p-1 text-primary" aria-hidden="true">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
          <rect x="3" y="4" width="18" height="17" rx="3" stroke="currentColor" stroke-width="1.6"/>
          <path d="M8 2v4M16 2v4M3 9h18" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
        </svg>
      </span>
      {{ $brand ?? 'ONE Services' }}
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNav"
            aria-controls="topNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div id="topNav" class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item me-2"><a class="nav-link" href="{{ $actions[0]['href'] ?? url('/help') }}">Help/FAQ</a></li>
        <li class="nav-item me-2">
          <a class="btn btn-outline-secondary" href="{{ $actions[1]['href'] ?? route('login.index') }}">Log In</a>
        </li>
        <li class="nav-item">
          <a class="btn btn-primary" href="{{ $actions[2]['href'] ?? url('/register') }}">Sign Up</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
