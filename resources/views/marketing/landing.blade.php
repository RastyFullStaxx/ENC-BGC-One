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
      ['label' => 'Log In',   'href' => route('login.index')],
      ['label' => 'Sign Up',  'href' => route('signup.index')]
    ]
  ])

  @if (!view()->exists('partials.navbar'))
  <header class="sticky-top bg-white site-header header-slide" role="banner" aria-label="Primary">
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
        <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis mb-3">Smart Booking System</span>

        <h1 class="display-5 fw-bold lh-1 mb-2">
          <span class="d-block">Manage Shared Facilities</span>
          <span class="d-block text-primary">Faster. Smarter. Seamless.</span>
        </h1>

        <p class="lead text-secondary mx-auto" style="max-width: 720px;">
          Simplify how teams reserve meeting rooms, special facilities, and transport services.
          Get instant approvals, avoid conflicts, and save time with real-time scheduling.
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
             href="{{ route('booking.wizard') }}" data-analytics="cta_book_now">
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

  {{-- SECTION: Quick Availability Glance (Upgraded) --}}
  <section id="availability" class="py-5">
    <div class="container">
      <div class="text-center mb-2">
        <h2 class="fw-semibold mb-1">Quick Availability Glance</h2>
        <p class="text-secondary mb-0">See real-time availability and book instantly</p>
      </div>

      @php
        // Example data; swap with live data later
        $hours = [
          ['label'=>'8AM','value'=>30],
          ['label'=>'9AM','value'=>60],
          ['label'=>'10AM','value'=>80],
          ['label'=>'11AM','value'=>90],
          ['label'=>'12PM','value'=>50],
          ['label'=>'1PM','value'=>40],
          ['label'=>'2PM','value'=>85],
          ['label'=>'3PM','value'=>95],
          ['label'=>'4PM','value'=>70],
          ['label'=>'5PM','value'=>50],
        ];

        // Helper: map 0–100% → green(140deg) → red(0deg)
        $heatColor = function($pct) {
          $h = max(0, min(140, 140 - round($pct * 1.4))); // 0..140
          return "hsl($h 90% 45%)";
        };

        // Build insight: peak window (>=80) & best times (<40)
        $labels = array_column($hours,'label');
        $vals   = array_column($hours,'value');

        // Peak ranges (>=80) – get first..last contiguous range for readability
        $peakIdx = array_keys(array_filter($vals, fn($v)=>$v>=80));
        $peakText = count($peakIdx)
          ? ($labels[min($peakIdx)].'–'.$labels[max($peakIdx)])
          : 'None';

        // Best times (<40)
        $best = [];
        foreach ($hours as $h) if ($h['value'] < 40) $best[] = $h['label'];
        $bestText = count($best) ? implode(', ', $best) : 'Later today';

      @endphp

      {{-- Insight line --}}
      <div class="alert alert-light border d-flex align-items-center gap-2 mb-4">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" class="text-primary">
          <path d="M12 3l8 3v6c0 5-3.5 7.5-8 9-4.5-1.5-8-4-8-9V6l8-3z" stroke="currentColor" stroke-width="1.6" fill="none"/>
          <path d="M12 8v5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
        </svg>
        <div class="small">
          <strong>Today’s trend:</strong> Peak <span class="text-danger">{{ $peakText }}</span>
          • Best time(s): <span class="text-success">{{ $bestText }}</span>
        </div>
      </div>

      <div class="row g-4">
        {{-- Left: Heat bars --}}
        <div class="col-lg-6">
          <div class="card h-100 shadow-sm">
            <div class="card-body">
              <div class="d-flex align-items-center gap-2 mb-3">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" class="text-primary">
                  <path d="M3 17l6-6 4 4 7-7" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h5 class="mb-0">Peak Hours Today</h5>
              </div>

              <div class="vstack gap-3">
                @foreach ($hours as $h)
                  @php
                    $pct  = $h['value'];
                    $heat = $heatColor($pct);
                    $recommend = $pct < 40;
                  @endphp
                  <div class="d-flex align-items-center">
                    <div class="me-3 text-secondary small" style="width:52px;">{{ $h['label'] }}</div>
                    <div class="flex-grow-1">
                      <div class="progress progress-heat" style="height: 24px;">
                        <div
                          class="progress-bar heatbar"
                          role="progressbar"
                          style="--heat: {{ $heat }}; width: {{ $pct }}%;"
                          data-width="{{ $pct }}%"
                          aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"
                        ></div>
                      </div>
                    </div>
                    <div class="ms-3 small text-secondary text-nowrap">{{ $pct }}% booked
                      @if ($recommend)
                        <span class="badge bg-info-subtle text-info-emphasis ms-1">Recommended</span>
                      @endif
                    </div>
                  </div>
                @endforeach
              </div>

              <div class="d-flex align-items-center gap-3 small text-secondary mt-4">
                <span class="d-inline-flex align-items-center gap-1">
                  <span class="legend-dot" style="--legend: hsl(140 90% 45%);"></span> Low
                </span>
                <span class="d-inline-flex align-items-center gap-1">
                  <span class="legend-dot" style="--legend: hsl(40 95% 50%);"></span> Medium
                </span>
                <span class="d-inline-flex align-items-center gap-1">
                  <span class="legend-dot" style="--legend: hsl(0 90% 50%);"></span> Peak
                </span>
              </div>
            </div>
          </div>
        </div>

        {{-- Right: Next Free Slots --}}
        <div class="col-lg-6">
          <div class="card h-100 shadow-sm">
            <div class="card-body">
              <div class="d-flex align-items-center gap-2 mb-3">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" class="text-primary">
                  <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8" />
                  <path d="M12 7v6l4 2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h5 class="mb-0">Next Free Slots Today</h5>
              </div>

              <div class="vstack gap-3">
                @foreach ([
                  ['time'=>'10:00 AM','room'=>'Conference Room A','cap'=>10],
                  ['time'=>'2:00 PM','room'=>'Meeting Room C','cap'=>8],
                  ['time'=>'4:00 PM','room'=>'Conference Room B','cap'=>20],
                ] as $slot)
                  <div class="border rounded-3 p-3 bg-success-subtle">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                      <div class="d-flex align-items-center gap-2">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" class="text-success">
                          <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.6" />
                          <path d="M12 7v6l4 2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="fw-medium">{{ $slot['time'] }}</span>
                      </div>
                      <span class="badge text-bg-success">Available</span>
                    </div>
                    <div class="small">{{ $slot['room'] }}</div>
                    <div class="text-secondary small d-flex align-items-center gap-1 mt-1">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                        <path d="M16 19v-1a4 4 0 00-4-4H8a4 4 0 00-4 4v1" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                        <circle cx="10" cy="7" r="3" stroke="currentColor" stroke-width="1.6"/>
                      </svg>
                      Up to {{ $slot['cap'] }} people
                    </div>
                  </div>
                @endforeach
              </div>

              <div class="mt-3">
                <a href="{{ url('/rooms') }}" class="btn btn-primary w-100">View All Availability</a>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

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
              <a href="{{ route('register') }}" class="btn btn-light">Create Account</a>
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
