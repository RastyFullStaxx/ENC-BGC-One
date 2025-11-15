{{-- resources/views/marketing/landing.blade.php --}}
<!doctype html>
<html lang="en" data-bs-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>ENC BGC One — Shared Services Portal</title>

  {{-- Vite entry with global design tokens + Bootstrap bundle --}}
  @vite(['resources/css/design-system.css'])
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
<body class="landing enc-type-body">

  <a href="#main" class="skip-link">Skip to content</a>

  {{-- Reusable Navbar (Bootstrap). If you build partials, this include will be used; otherwise a Bootstrap fallback renders. --}}
  @includeIf('partials.navbar', [
    'brand' => 'ONE Services',
    'actions' => [
      ['label' => 'Help/FAQ', 'href' => route('faq')],
      ['label' => 'Log In',   'href' => route('login')],
      ['label' => 'Sign Up',  'href' => route('signup.index')]
    ]
  ])

  @if (!view()->exists('partials.navbar'))
  <header class="sticky-top bg-white site-header header-slide" role="banner" aria-label="Primary">
    <nav class="navbar navbar-expand-md bg-white">
      <div class="container d-flex align-items-center justify-content-between flex-wrap flex-md-nowrap gap-3">
        <a class="navbar-brand d-flex align-items-center gap-3 text-decoration-none py-2 me-auto" href="{{ route('landing') }}">
          <span class="brand-mark d-inline-flex align-items-center justify-content-center rounded-2" aria-hidden="true">
            <span class="fw-bold small">ONE</span>
          </span>
          <span class="brand-copy d-none d-md-inline text-start lh-sm">
            <span class="d-block fw-semibold">ONE Services</span>
            <span class="d-block text-muted small">Shared Services Portal</span>
          </span>
        </a>

        <button class="navbar-toggler ms-auto d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#topNav"
                aria-controls="topNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div id="topNav" class="collapse navbar-collapse justify-content-md-end flex-grow-0">
          <ul class="navbar-nav align-items-center ms-md-auto ms-lg-0 flex-row flex-md-row gap-2">
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
    <section class="hero hero-light full-height-hero">
      <div class="container hero-grid hero-grid-light">
        <div class="hero-text">
          <span class="hero-pill hero-pill-light">ONE ENC · Shared Services Portal</span>
          <h1 class="hero-title mb-3">
            Book rooms, support, and shuttles with one modern workflow.
          </h1>
          <p class="hero-subtitle">
            Browse live availability, send requests to facilities, or launch the booking
            wizard for guided support. Everything follows the same modern workflow we use on the dashboard.
          </p>
          <div class="hero-actions" role="group" aria-label="Primary actions">
            <a class="btn btn-primary d-inline-flex align-items-center gap-2"
               href="{{ route('booking.wizard') }}" data-analytics="cta_book_now">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <rect x="3" y="4" width="18" height="17" rx="3" stroke="currentColor" stroke-width="1.6"/>
                <path d="M8 2v4M16 2v4M3 9h18M12 12v6M9 15h6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
              </svg>
              Start a Booking
            </a>
            <a class="btn btn-ghost d-inline-flex align-items-center gap-2"
               href="{{ route('facilities.catalog') }}" data-analytics="cta_facility_catalog">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M4 4h6v6H4zM14 4h6v6h-6zM14 14h6v6h-6zM4 14h6v6H4z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              Explore Facilities
            </a>
          </div>
          <ul class="hero-highlights">
            <li>Live usage signals across ENC</li>
            <li>Guided requests with draft saves</li>
            <li>Mirrors the in-app dashboard experience</li>
          </ul>
        </div>
      </div>
      <div class="scroll-cue" aria-hidden="true">
        <span>Scroll to preview</span>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
          <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>
    </section>

    @php
      $availabilityHeat = [
        ['label' => '7:00 AM', 'value' => 32],
        ['label' => '9:00 AM', 'value' => 58],
        ['label' => '11:00 AM', 'value' => 76],
        ['label' => '1:00 PM', 'value' => 41],
        ['label' => '3:00 PM', 'value' => 88],
        ['label' => '5:00 PM', 'value' => 63],
      ];

      $availabilityOpen = [
        [
          'status' => 'Calm load',
          'tone'   => 'good',
          'time'   => 'Open now',
          'window' => 'Free until 10:00 AM (60 min)',
          'room'   => 'Creative Studio B',
          'cap'    => 'Seats 6 · screen + writable wall',
          'note'   => 'Great for stand-ups, retros, and quick approvals.',
          'hold'   => 'Hold for 10 min',
        ],
        [
          'status' => 'Clearing soon',
          'tone'   => 'warn',
          'time'   => 'Starts in 25 min',
          'window' => 'Boardroom A-301 · 9:30–11:00 AM',
          'room'   => 'Boardroom A-301',
          'cap'    => 'Seats 12 · video conferencing ready',
          'note'   => 'Ideal for leadership syncs or client calls.',
          'hold'   => 'Hold for 5 min',
        ],
        [
          'status' => 'High demand',
          'tone'   => 'busy',
          'time'   => 'Opens after 4:00 PM',
          'window' => 'Innovation Lab C-401 · 4:00–6:30 PM',
          'room'   => 'Innovation Lab C-401',
          'cap'    => 'Workshop layout · writable walls + projectors',
          'note'   => 'Best for design sprints and working sessions.',
          'hold'   => 'Join waitlist',
        ],
      ];
    @endphp

    <section id="availability" class="availability-preview py-5">
      <div class="container">
        <div class="section-heading text-center mb-5">
          <span class="section-eyebrow">Quick availability glance</span>
          <h2>Today’s load at a glance</h2>
          <p class="text-secondary">Check today’s busiest streaks plus the rooms that are open long enough for your next huddle.</p>
        </div>

        <div class="row g-4 align-items-start">
          <div class="col-12 col-lg-6">
            <article class="availability-panel availability-panel-heat h-100">
              <div class="availability-panel-head">
                <h5>Peak timeline</h5>
                <span>Live utilization %</span>
              </div>
              <div class="availability-heat">
                @foreach ($availabilityHeat as $slot)
                  <div class="heat-row">
                    <span class="heat-label">{{ $slot['label'] }}</span>
                    <div class="heat-track">
                      <span class="heat-bar" style="--heat-width: {{ $slot['value'] }}%;"></span>
                    </div>
                    <span class="heat-value">{{ $slot['value'] }}%</span>
                  </div>
                @endforeach
              </div>
            </article>
          </div>

          <div class="col-12 col-lg-6">
            <article class="availability-panel availability-panel-open h-100">
              <div class="availability-panel-head">
                <h5>Ready-to-book rooms</h5>
                <span>Auto-refreshes every 3 minutes · holds for you</span>
              </div>
              <ul class="availability-open-list">
                @foreach ($availabilityOpen as $slot)
                  <li>
                    <div class="open-main">
                      <span class="open-status open-status-{{ $slot['tone'] ?? 'good' }}">
                        <span class="status-dot" aria-hidden="true"></span>
                        {{ $slot['status'] }}
                      </span>
                      <p class="open-time">{{ $slot['time'] }}</p>
                      <p class="open-room">{{ $slot['room'] }}</p>
                      <p class="open-meta">{{ $slot['cap'] }}</p>
                      <p class="open-note">{{ $slot['note'] }}</p>
                    </div>
                    <div class="open-window text-end">
                      <p class="open-duration">{{ $slot['window'] }}</p>
                      <span class="open-hold">{{ $slot['hold'] }}</span>
                    </div>
                  </li>
                @endforeach
              </ul>
              <div class="availability-cta text-center mt-3">
                <a href="{{ route('facilities.catalog') }}" class="btn btn-outline">View live board</a>
                <p class="open-footnote text-secondary small mb-0 mt-2">Green slots stay reserved for you while you complete the request.</p>
              </div>
            </article>
          </div>
        </div>
      </div>
    </section>
  </main>

  {{-- SECTION: Featured Facilities --}}
  <section id="facilities" class="py-5 bg-body-tertiary">
    <div class="container">
      <div class="text-center mb-4">
        <h2 class="fw-semibold mb-1">Featured Facilities</h2>
        <p class="text-secondary mb-0">Explore our premium meeting spaces and facilities</p>
      </div>

      <div class="row g-4">
        @foreach ([
          ['title'=>'Conference Room A','cap'=>10,'tags'=>['WiFi','Projector','Whiteboard'],'src'=>'https://images.unsplash.com/photo-1507209696998-3c532be9b2b1?q=80&w=1200&auto=format&fit=crop'],
          ['title'=>'Conference Room B','cap'=>20,'tags'=>['Video Conferencing','Large Display','Sound System'],'src'=>'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?q=80&w=1200&auto=format&fit=crop'],
          ['title'=>'Executive Boardroom','cap'=>12,'tags'=>['Premium Setup','Coffee Service','Smart Board'],'src'=>'https://images.unsplash.com/photo-1524758631624-e2822e304c36?q=80&w=1200&auto=format&fit=crop'],
        ] as $room)
          <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm overflow-hidden">
              <div class="position-relative">
                <img src="{{ $room['src'] }}" class="card-img-top" alt="{{ $room['title'] }} photo">
                <span class="position-absolute top-0 end-0 m-3 badge rounded-pill text-bg-primary">Meeting Room</span>
              </div>
              <div class="card-body">
                <h5 class="card-title mb-2">{{ $room['title'] }}</h5>
                <div class="small text-secondary d-flex align-items-center gap-2 mb-3">
                  {{-- people icon --}}
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                    <path d="M16 19v-1a4 4 0 00-4-4H8a4 4 0 00-4 4v1" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                    <circle cx="10" cy="7" r="3" stroke="currentColor" stroke-width="1.6"/>
                  </svg>
                  Capacity: {{ $room['cap'] }} people
                </div>
                <div class="d-flex flex-wrap gap-2 mb-3">
                  @foreach ($room['tags'] as $t)
                    <span class="badge rounded-pill text-bg-light border">{{ $t }}</span>
                  @endforeach
                </div>
                <a href="{{ url('/rooms') }}" class="btn btn-outline-primary w-100">
                  View Details &amp; Book
                  <span class="ms-1" aria-hidden="true">→</span>
                </a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- SECTION: How It Works --}}
  <section id="how-it-works" class="py-5 bg-primary text-white">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="fw-semibold mb-1 text-white">How It Works</h2>
        <p class="opacity-75 mb-0">Simple 3-step process to book your resources</p>
      </div>

      <div class="row g-4 text-center">
        <div class="col-12 col-md-4">
          <div class="p-4 h-100 bg-primary-subtle rounded-4 text-primary-emphasis">
            {{-- search icon --}}
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" class="mb-2">
              <circle cx="10" cy="10" r="6" stroke="currentColor" stroke-width="1.8"/>
              <path d="M14.5 14.5L21 21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
            <h5 class="mb-1">Find</h5>
            <p class="mb-0 small">Browse available rooms, facilities, and shuttle services. Check real-time availability and select your preferred option.</p>
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="p-4 h-100 bg-primary-subtle rounded-4 text-primary-emphasis">
            {{-- doc icon --}}
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" class="mb-2">
              <path d="M6 3h8l4 4v14H6z" stroke="currentColor" stroke-width="1.8" fill="none"/>
              <path d="M14 3v5h5" stroke="currentColor" stroke-width="1.8" fill="none"/>
            </svg>
            <h5 class="mb-1">Request &amp; Approve</h5>
            <p class="mb-0 small">Submit your booking with details. Our team reviews and confirms quickly.</p>
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="p-4 h-100 bg-primary-subtle rounded-4 text-primary-emphasis">
            {{-- bolt icon --}}
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" class="mb-2">
              <path d="M13 2L3 14h7l-1 8 10-12h-7l1-8z" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/>
            </svg>
            <h5 class="mb-1">Use</h5>
            <p class="mb-0 small">Receive confirmation and access your booked resource. Track everything in your dashboard.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- SECTION: Booking Policies (Teaser) --}}
  <section id="policies" class="py-5">
    <div class="container">
      <div class="card shadow-sm border-0">
        <div class="card-body p-4 p-md-5">
          <div class="text-center mb-4">
            {{-- shield icon --}}
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="text-primary mb-2">
              <path d="M12 3l8 3v6c0 5-3.5 7.5-8 9-4.5-1.5-8-4-8-9V6l8-3z" stroke="currentColor" stroke-width="1.8" fill="none"/>
            </svg>
            <h3 class="fw-semibold mb-1">Booking Policies</h3>
            <p class="text-secondary mb-0">Simple guidelines for a smooth booking experience</p>
          </div>

          <ul class="list-group list-group-flush mb-4">
            @foreach ([
              'Food & beverages allowed only in designated rooms. Light refreshments permitted in Executive Boardroom.',
              'Meeting room bookings limited to 4 hours per session. Extended time requires manager approval.',
              'Lead Times: 24 hours for meeting rooms, 48 hours for special facilities, 72 hours for shuttle services.',
              'Cancellation: at least 12 hours before schedule to avoid restrictions.',
              'Cleanup: return rooms to original condition and report equipment issues immediately.'
            ] as $rule)
              <li class="list-group-item d-flex align-items-start">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" class="text-primary me-2 mt-1 flex-shrink-0">
                  <path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="small">{{ $rule }}</span>
              </li>
            @endforeach
          </ul>

          <div class="text-center">
            <a href="{{ url('/policies') }}" class="btn btn-outline-primary">View Full Policies &amp; FAQ</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- SECTION: Ready to Get Started --}}
  <section id="cta" class="py-5 bg-primary text-white">
    <div class="container">
      <div class="row align-items-center g-3">
        <div class="col-lg-8">
          <h2 class="fw-semibold mb-1 text-white">Ready to Get Started?</h2>
          <p class="mb-0 opacity-75">Join hundreds of employees already using our smart booking system</p>
        </div>
        <div class="col-lg-4 text-lg-end">
          <div class="d-flex d-lg-inline-flex gap-2">
            @if (Route::has('register'))
              <a href="{{ route('signup.index') }}" class="btn btn-light">Create Account</a>
            @else
              <a href="{{ url('/register') }}" class="btn btn-light">Create Account</a>
            @endif
            <a href="{{ url('/rooms') }}" class="btn btn-outline-light">Browse Availability</a>
          </div>
        </div>
      </div>
    </div>
  </section>

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
  <footer class="py-3 site-footer footer-slide">
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
