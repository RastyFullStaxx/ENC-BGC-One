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
  @vite([
    'resources/css/landing/base.css',
    'resources/css/landing/hero.css',
    'resources/css/landing/availability.css',
    'resources/css/landing/facilities.css',
    'resources/css/landing/how-it-works.css',
    'resources/js/landing.js'
  ])

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
      $scheduleStart = 7;  // 7 AM
      $scheduleEnd   = 19; // 7 PM
      $scheduleBlocks = [
        [
          'room' => 'Creative Studio B',
          'status' => 'available',
          'status_label' => 'Available',
          'note' => 'Available until 10:00 AM',
          'segments' => [
            ['start' => 7,   'end' => 8.5, 'status' => 'occupied'],
            ['start' => 8.5, 'end' => 10,  'status' => 'available'],
            ['start' => 10,  'end' => 14,  'status' => 'limited'],
            ['start' => 14,  'end' => 15,  'status' => 'occupied'],
            ['start' => 15,  'end' => 17.5,'status' => 'available'],
          ],
        ],
        [
          'room' => 'Boardroom A-301',
          'status' => 'limited-availability',
          'status_label' => 'Limited availability',
          'note' => 'Leadership sync until 12:30 PM',
          'segments' => [
            ['start' => 7,   'end' => 9,   'status' => 'available'],
            ['start' => 9,   'end' => 12.5,'status' => 'occupied'],
            ['start' => 12.5,'end' => 13.5,'status' => 'limited'],
            ['start' => 13.5,'end' => 15,  'status' => 'limited'],
            ['start' => 16,  'end' => 18.5,'status' => 'occupied'],
          ],
        ],
        [
          'room' => 'Innovation Lab C-401',
          'status' => 'occupied',
          'status_label' => 'Occupied',
          'note' => 'Design sprint until 2:00 PM',
          'segments' => [
            ['start' => 7,   'end' => 9.5, 'status' => 'maintenance'],
            ['start' => 9.5, 'end' => 12,  'status' => 'occupied'],
            ['start' => 12,  'end' => 14,  'status' => 'occupied'],
            ['start' => 14,  'end' => 15,  'status' => 'limited'],
            ['start' => 15,  'end' => 19,  'status' => 'available'],
          ],
        ],
        [
          'room' => 'Atrium Lounge D-214',
          'status' => 'under-maintenance',
          'status_label' => 'Under maintenance',
          'note' => 'Deep clean ongoing until 4:00 PM · reopening after QA.',
          'segments' => [
            ['start' => 7,   'end' => 11,  'status' => 'maintenance'],
            ['start' => 11,  'end' => 14,  'status' => 'maintenance'],
            ['start' => 14,  'end' => 16,  'status' => 'maintenance'],
            ['start' => 16,  'end' => 19,  'status' => 'available'],
          ],
        ],
      ];

      $lastSync    = now();
      $range       = max(1, $scheduleEnd - $scheduleStart);
      $nowDecimal  = (int) $lastSync->format('G') + ((int) $lastSync->format('i') / 60);
      $nowInRange  = $nowDecimal >= $scheduleStart && $nowDecimal <= $scheduleEnd;
      $nowOffset   = max(0, min(100, (($nowDecimal - $scheduleStart) / $range) * 100));
    @endphp

    <section id="availability" class="availability-preview py-5">
      <div class="container">
        <div class="section-heading text-center mb-5">
          <span class="section-eyebrow">Quick availability glance</span>
          <h2>Today’s load at a glance</h2>
          <p class="text-secondary">Slide across the day to see when spotlight rooms are booked, on hold, flipping, or wide open.</p>
        </div>

        <div class="row g-4 align-items-start">
          <div class="col-12">
            <article class="availability-panel availability-panel-schedule h-100">
              <div class="availability-panel-head availability-panel-head-compact">
                <span>Last sync {{ $lastSync->format('g:i A') }}</span>
              </div>
              <div class="schedule-scale">
                @for ($hour = $scheduleStart; $hour <= $scheduleEnd; $hour += 2)
                  <span>{{ $hour <= 12 ? $hour : $hour - 12 }}{{ $hour < 12 ? ' AM' : ' PM' }}</span>
                @endfor
              </div>
              @php
                $stateLabels = [
                  'available'   => 'Available',
                  'limited'     => 'Limited availability',
                  'occupied'    => 'Occupied',
                  'maintenance' => 'Under maintenance',
                ];
              @endphp
              <div class="schedule-board">
                @foreach ($scheduleBlocks as $block)
                  <div class="schedule-row">
                    <div class="schedule-meta">
                      <p class="schedule-room">{{ $block['room'] }}</p>
                      <p class="schedule-status schedule-status-{{ $block['status'] ?? 'available' }}">{{ $block['status_label'] ?? ucwords(str_replace('-', ' ', $block['status'] ?? 'available')) }}</p>
                      <p class="schedule-note">{{ $block['note'] }}</p>
                    </div>
                    <div class="schedule-track">
                      @if ($nowInRange)
                        <span class="schedule-now" style="--now-offset: {{ $nowOffset }}%;" aria-hidden="true"></span>
                      @endif
                      @foreach ($block['segments'] as $segment)
                        @php
                          $state = $segment['status'] ?? 'available';
                          $offset = (($segment['start'] - $scheduleStart) / $range) * 100;
                          $width = (($segment['end'] - $segment['start']) / $range) * 100;
                          $offset = max(0, min(100, $offset));
                          $width = max(1, min(100, $width));
                          $label = $stateLabels[$state] ?? ucwords($state);
                        @endphp
                        <span class="schedule-block schedule-block-{{ $state }}" style="--segment-offset: {{ $offset }}%; --segment-width: {{ $width }}%;" data-label="{{ $label }}" aria-label="{{ $label }}"></span>
                      @endforeach
                    </div>
                  </div>
                @endforeach
              </div>
              <div class="schedule-legend">
                <span><i class="legend-dot dot-available"></i> Available</span>
                <span><i class="legend-dot dot-limited"></i> Limited availability</span>
                <span><i class="legend-dot dot-occupied"></i> Occupied</span>
                <span><i class="legend-dot dot-maintenance"></i> Under maintenance</span>
              </div>
            </article>
          </div>
        </div>
      </div>
    </section>
  </main>

  {{-- SECTION: Featured Facilities --}}
  <section id="facilities" class="py-5 facility-feature">
    @php
      $facilityHero = [
        'title' => 'Innovation Lab C-401',
        'type' => 'Innovation Lab',
        'status' => 'Limited availability this morning',
        'window' => 'Wide open for workshops after 3:00 PM',
        'image' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?q=80&w=1600&auto=format&fit=crop',
        'summary' => 'Modular seating, writable walls, dual projectors, and embedded sensors to keep teams synced without tech anxiety.',
        'chips' => ['Hybrid-ready', 'Workshop layout', 'Natural light'],
        'metrics' => [
          ['label' => 'Capacity', 'value' => '24 seats'],
          ['label' => 'Amenities', 'value' => '2 displays · 4 mics'],
          ['label' => 'Next slot', 'value' => 'Today · 3:15 PM'],
        ],
      ];

      $facilityTiles = [
        [
          'title' => 'Creative Studio B',
          'type' => 'Podcast & media suite',
          'status' => 'Available until 10:00 AM',
          'tone' => 'available',
          'image' => 'https://images.unsplash.com/photo-1489515217757-5fd1be406fef?q=80&w=1200&auto=format&fit=crop',
          'details' => ['Seats 6', '4K capture', 'Acoustic walls'],
        ],
        [
          'title' => 'Executive Boardroom',
          'type' => 'Leadership hub',
          'status' => 'Occupied until 1:00 PM',
          'tone' => 'occupied',
          'image' => 'https://images.unsplash.com/photo-1523475472560-d2df97ec485c?q=80&w=1200&auto=format&fit=crop',
          'details' => ['Seats 12', 'Coffee service', 'Poly Studio'],
        ],
        [
          'title' => 'Atrium Lounge D-214',
          'type' => 'Casual collaboration',
          'status' => 'Under maintenance · back at 4:00 PM',
          'tone' => 'maintenance',
          'image' => 'https://images.unsplash.com/photo-1497366754035-f200968a6e72?q=80&w=1200&auto=format&fit=crop',
          'details' => ['Hoteling', 'Soft seating', 'Town hall ready'],
        ],
      ];
    @endphp
    <div class="container">
      <div class="text-center mb-5">
        <span class="section-eyebrow">Featured facilities</span>
        <h2 class="fw-semibold mb-1">Spotlight spaces handpicked for today</h2>
        <p class="text-secondary mb-0">Curated from the live catalog so you can skip browsing and jump straight to the best-fit rooms.</p>
      </div>

      <div class="row g-4 facility-grid">
        <div class="col-12 col-lg-7">
          <article class="facility-hero-card" style="--facility-hero-img: url('{{ $facilityHero['image'] }}');">
            <div class="facility-hero-overlay"></div>
            <div class="facility-hero-content">
              <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                <span class="facility-chip">{{ $facilityHero['type'] }}</span>
                <span class="facility-chip chip-status">{{ $facilityHero['status'] }}</span>
              </div>
              <div class="facility-hero-headings">
                <h3 class="facility-hero-title">{{ $facilityHero['title'] }}</h3>
                <p class="facility-hero-window">{{ $facilityHero['window'] }}</p>
              </div>
              <p class="facility-hero-summary">{{ $facilityHero['summary'] }}</p>
              <ul class="facility-hero-stats">
                @foreach ($facilityHero['metrics'] as $metric)
                  <li>
                    <span class="stat-label">{{ $metric['label'] }}</span>
                    <span class="stat-value">{{ $metric['value'] }}</span>
                  </li>
                @endforeach
              </ul>
              <div class="facility-hero-tags">
                @foreach ($facilityHero['chips'] as $chip)
                  <span>{{ $chip }}</span>
                @endforeach
              </div>
              <div class="facility-hero-actions">
                <a href="{{ route('booking.wizard') }}" class="btn btn-light">Book this room</a>
                <a href="{{ route('facilities.catalog') }}" class="btn btn-ghost text-white">View catalog</a>
              </div>
            </div>
          </article>
        </div>
        <div class="col-12 col-lg-5">
          <div class="facility-mini-stack">
            @foreach ($facilityTiles as $tile)
              <article class="facility-mini facility-mini-{{ $tile['tone'] ?? 'available' }}">
                <div class="facility-mini-media" style="background-image:url('{{ $tile['image'] }}');"></div>
                <div class="facility-mini-body">
                  <div class="facility-mini-head">
                    <h5>{{ $tile['title'] }}</h5>
                    <span class="mini-type">{{ $tile['type'] }}</span>
                  </div>
                  <p class="mini-status">{{ $tile['status'] }}</p>
                  <ul class="mini-details">
                    @foreach ($tile['details'] as $detail)
                      <li>{{ $detail }}</li>
                    @endforeach
                  </ul>
                  <div class="mini-actions">
                    <a href="{{ route('facilities.catalog') }}" class="btn btn-outline-light btn-sm">See details</a>
                    <button type="button" class="btn btn-link text-white p-0">Add to plan</button>
                  </div>
                </div>
              </article>
            @endforeach
          </div>
        </div>
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
