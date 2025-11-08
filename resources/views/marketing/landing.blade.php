{{-- resources/views/marketing/landing.blade.php --}}
<!doctype html>
<html lang="en" data-bs-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>ENC BGC One — Shared Services Portal</title>

  {{-- Vite entry should include Bootstrap CSS & JS --}}
  @vite(['resources/css/landing.css', 'resources/js/landing.js'])

  {{-- If you prefer CDN instead of Vite, uncomment: --}}
  {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
  {{-- <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> --}}

  <meta name="description" content="Request rooms and facilities easily with real-time availability, instant confirmations, and smart approvals.">
  <link rel="icon" href="/images/favicon.png">
  <meta property="og:title" content="ENC BGC One — Shared Services Portal">
  <meta property="og:description" content="Request rooms and facilities easily with real-time availability, instant confirmations, and smart approvals.">
  <meta property="og:image" content="/images/social-card.png">

  <style>
    /* keep skip-link always available */
    .skip-link{position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden}
    .skip-link:focus{left:12px;top:12px;width:auto;height:auto;padding:.625rem .875rem;background:#111;color:#fff;border-radius:.6rem;z-index:1055}
  </style>
</head>
<body class="landing bg-white text-body">

  <a href="#main" class="skip-link">Skip to content</a>

  {{-- Reusable Navbar (Bootstrap). If you build partials, this include will be used; otherwise a Bootstrap fallback renders. --}}
  @includeIf('partials.navbar', [
    'brand' => 'ONE Services',
    'actions' => [
      ['label' => 'Help/FAQ', 'href' => url('/help')],
      ['label' => 'Log In',   'href' => route('login')],
      ['label' => 'Sign Up',  'href' => url('/register'), 'variant' => 'primary']
    ]
  ])

  @if (!view()->exists('partials.navbar'))
  <header class="sticky-top bg-white border-bottom" role="banner" aria-label="Primary">
    <nav class="navbar navbar-expand-md bg-white">
      <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="{{ route('landing') }}">
          <span class="d-inline-flex align-items-center justify-content-center rounded-3 border p-1 text-primary" aria-hidden="true">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
              <rect x="3" y="4" width="18" height="17" rx="3" stroke="currentColor" stroke-width="1.6"/>
              <path d="M8 2v4M16 2v4M3 9h18" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
            </svg>
          </span>
          ONE Services
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNav"
                aria-controls="topNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div id="topNav" class="collapse navbar-collapse">
          <ul class="navbar-nav ms-auto align-items-center">
            <li class="nav-item me-2">
              <a class="nav-link" href="{{ url('/help') }}">Help/FAQ</a>
            </li>
            <li class="nav-item me-2">
              <a class="btn btn-outline-secondary" href="{{ route('login') }}">Log In</a>
            </li>
            <li class="nav-item">
              <a class="btn btn-primary" href="{{ url('/register') }}">Sign Up</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>
  @endif

  <main id="main" tabindex="-1">

    {{-- HERO --}}
    <section class="py-5 py-lg-5 text-center hero-full">
      <div class="container" style="max-width: 900px;">
        <span class="badge rounded-pill text-secondary-emphasis bg-primary-subtle border border-primary-subtle mb-3">
          Smart Booking System
        </span>

        <h1 class="display-5 fw-bold lh-1 mb-2">
          <span class="d-block">Shared Services Portal</span>
          <span class="d-block text-primary">Request Rooms Easily</span>
        </h1>

        <p class="lead text-secondary mx-auto" style="max-width: 720px;">
          Your one-stop shop for booking meeting rooms, special facilities, and shuttle services.
          Real-time availability, instant confirmations, and a seamless booking experience.
        </p>

        <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center align-items-center mt-3" role="group" aria-label="Primary actions">
          <a class="btn btn-outline-secondary btn-lg d-inline-flex align-items-center gap-2"
             href="{{ url('/rooms') }}" data-analytics="cta_find_room">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8"/>
              <path d="M20 20l-3.5-3.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
            Find a Room
          </a>

          <a class="btn btn-primary btn-lg d-inline-flex align-items-center gap-2"
             href="{{ url('/book') }}" data-analytics="cta_book_now">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <rect x="3" y="4" width="18" height="17" rx="3" stroke="currentColor" stroke-width="1.8"/>
              <path d="M8 2v4M16 2v4M3 9h18M12 12v6M9 15h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
            Book Now
          </a>
        </div>

        <ul class="list-unstyled d-flex flex-wrap justify-content-center gap-3 text-secondary mt-3 small" aria-label="Key assurances">
          <li>Real-time availability</li>
          <li>Approval workflows</li>
          <li>Full audit history</li>
        </ul>
      </div>
    </section>

    {{-- Secondary content placeholder --}}
    {{-- <section class="py-5 bg-light">…</section> --}}
  </main>

  {{-- Reusable Footer include; Bootstrap fallback below if not present --}}
  @includeIf('partials.footer', [
    'links' => [
      ['label'=>'Privacy','href'=>url('/privacy')],
      ['label'=>'Terms','href'=>url('/terms')],
      ['label'=>'Contact','href'=>url('/contact')],
    ],
    'copyright' => '© '.date('Y').' ENC BGC One'
  ])

  @if (!view()->exists('partials.footer'))
  <footer class="border-top py-3">
    <div class="container d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2">
      <nav class="nav">
        <a class="nav-link px-0 me-3 text-secondary" href="{{ url('/privacy') }}">Privacy</a>
        <a class="nav-link px-0 me-3 text-secondary" href="{{ url('/terms') }}">Terms</a>
        <a class="nav-link px-0 text-secondary" href="{{ url('/contact') }}">Contact</a>
      </nav>
      <p class="mb-0 text-secondary small">© {{ date('Y') }} ENC BGC One</p>
    </div>
  </footer>
  @endif

</body>
</html>
