<nav class="navbar navbar-expand-md bg-white sticky-top site-header header-slide">
  <div class="container d-flex align-items-center justify-content-between flex-wrap gap-3">
    <a class="navbar-brand d-flex align-items-center gap-3 text-decoration-none py-2 me-auto" href="{{ route('landing') }}">
      <span class="brand-mark d-inline-flex align-items-center justify-content-center rounded-2" aria-hidden="true">
        <span class="fw-bold small">ONE</span>
      </span>
      <span class="brand-copy d-none d-md-inline text-start lh-sm">
        <span class="d-block fw-semibold">{{ $brand ?? 'ONE Services' }}</span>
        <span class="d-block text-muted small">Shared Services Portal</span>
      </span>
    </a>

    <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#topNav"
            aria-controls="topNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div id="topNav" class="collapse navbar-collapse justify-content-md-end flex-grow-0 w-100 w-md-auto">
      <ul class="navbar-nav align-items-center ms-md-auto">
        <li class="nav-item me-2"><a class="nav-link" href="{{ $actions[0]['href'] ?? url('/help') }}">Help/FAQ</a></li>
        <li class="nav-item me-2">
          <a class="btn btn-outline-secondary" href="{{ $actions[1]['href'] ?? route('login') }}">Log In</a>
        </li>
        <li class="nav-item">
          <a class="btn btn-primary" href="{{ $actions[2]['href'] ?? route('signup.index') }}">Sign Up</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
