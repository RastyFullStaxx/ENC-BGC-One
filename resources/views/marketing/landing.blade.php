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
    'resources/css/landing/policies.css',
    'resources/css/landing/cta.css',
    'resources/js/landing/index.js'
  ])
  @livewireStyles

  {{-- If you prefer CDN instead of Vite, uncomment: --}}
  {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
  {{-- <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> --}}

  <meta name="description" content="Request rooms and facilities easily with real-time availability, instant confirmations, and smart approvals.">
  <link rel="icon" href="/images/favicon.png">
  <meta property="og:title" content="ENC BGC One — Shared Services Portal">
  <meta property="og:description" content="Request rooms and facilities easily with real-time availability, instant confirmations, and smart approvals.">
  <meta property="og:image" content="/images/social-card.png">
</head>
<body class="landing enc-type-body">

  <a href="#main" class="skip-link">Skip to content</a>

  @php
    $isAuthenticated = auth()->check();
    $homeButtonRoute = null;
    if ($isAuthenticated) {
      $role = strtolower(auth()->user()->role ?? '');
      $homeButtonRoute = $role === 'admin' ? route('admin.dashboard') : route('user.dashboard');
    }
  @endphp

  @include('partials.dashboard-navbar', [
    'mode' => $isAuthenticated ? 'app' : 'public',
    'showStepper' => false,
    'showBookingsToggle' => false,
    'showNotifications' => $isAuthenticated,
    'showUserMenu' => $isAuthenticated,
    'showRolePill' => false,
    'homeRoute' => route('landing'),
    'bookingsCount' => 0,
    'notificationsCount' => 0,
    'showHomeButton' => $isAuthenticated,
    'homeButtonRoute' => $homeButtonRoute,
    'guestActions' => [
      ['label' => 'Log In', 'href' => route('login'), 'variant' => 'outline'],
      ['label' => 'Sign Up', 'href' => route('signup.index'), 'variant' => 'primary'],
    ],
  ])

  <main id="main" tabindex="-1">

    {{-- HERO --}}
    <section class="hero hero-light full-height-hero">
      <div class="container hero-grid hero-grid-light">
        <div class="hero-text">
          <span class="hero-pill hero-pill-light">ONE ENC · Shared Services Portal</span>
          <h1 class="hero-title mb-3">
            Service at your fingertips.
          </h1>
          <p class="hero-subtitle">
            Book rooms, support, and shuttles with one modern workflow.
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
            <li></li>
            <li></li>
            <li></li>
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
      $scheduleStart = $scheduleStart ?? 7;  // 7 AM fallback
      $scheduleEnd   = $scheduleEnd ?? 19; // 7 PM fallback
      $scheduleBlocks = $scheduleBlocks ?? [];
      $stateLabels = [
        'available'   => 'Available',
        'limited'     => 'Limited availability',
        'occupied'    => 'Occupied',
        'maintenance' => 'Under maintenance',
      ];
      $lastSync    = $lastSync ?? now();
      $nowLabel    = $lastSync->format('g:i A');
      $range       = max(1, $scheduleEnd - $scheduleStart);
      $nowDecimal  = (int) $lastSync->format('G') + ((int) $lastSync->format('i') / 60);
      $nowInRange  = $nowDecimal >= $scheduleStart && $nowDecimal <= $scheduleEnd;
      $nowOffset   = max(0, min(100, (($nowDecimal - $scheduleStart) / $range) * 100));
    @endphp


    @if (class_exists(\Livewire\Livewire::class))
      <livewire:today-glance />
    @else
      <section id="availability" class="availability-preview py-5">
        <div class="container">
          <div class="section-heading text-center mb-5">
            <span class="section-eyebrow">Quick availability glance</span>
            <h2>Today’s load at a glance</h2>
            <p class="workflow-subtext mb-0">Slide across the day to see when spotlight rooms are booked, on hold, flipping, or wide open.</p>
          </div>

          <div class="row g-4 align-items-start">
            <div class="col-12">
              <article class="availability-panel availability-panel-schedule h-100" data-schedule-start="{{ $scheduleStart }}" data-schedule-end="{{ $scheduleEnd }}">
                <div class="availability-panel-head availability-panel-head-compact">
                  <span>Last sync {{ $lastSync->format('g:i A') }} · {{ $lastSync->format('M j, Y') }}</span>
                  <span class="text-muted small">Current time {{ $lastSync->format('g:i A') }} · {{ config('app.timezone', 'UTC') }}</span>
                </div>
                <div class="schedule-scale">
                  @for ($hour = $scheduleStart; $hour <= $scheduleEnd; $hour += 2)
                    <span>{{ $hour <= 12 ? $hour : $hour - 12 }}{{ $hour < 12 ? ' AM' : ' PM' }}</span>
                  @endfor
                </div>
                <div class="schedule-board">
                  @forelse ($scheduleBlocks as $block)
                    <div class="schedule-row">
                      <div class="schedule-meta">
                      <p class="schedule-room">{{ $block['room'] }}</p>
                      <p class="schedule-status schedule-status-{{ $block['status'] ?? 'available' }}">{{ $block['status_label'] ?? ucwords(str_replace('-', ' ', $block['status'] ?? 'available')) }}</p>
                      <p class="schedule-note">{{ $block['note'] }}</p>
                    </div>
                    <div class="schedule-track">
                      @if ($nowInRange)
                        <span class="schedule-now" data-label="Now {{ $nowLabel }}" style="--now-offset: {{ $nowOffset }}%;" aria-hidden="true"></span>
                      @endif
                      @foreach ($block['segments'] ?? [] as $segment)
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
                  @empty
                    <div class="schedule-row">
                      <div class="schedule-meta">
                        <p class="schedule-room mb-1">No facilities yet</p>
                        <p class="schedule-note">Add a booking to see live availability.</p>
                      </div>
                      <div class="schedule-track d-flex align-items-center justify-content-center text-muted small">
                        <span>Nothing scheduled today</span>
                      </div>
                    </div>
                  @endforelse
                </div>
                <div class="schedule-legend">
                  <span class="legend-item legend-available"><i class="legend-dot dot-available"></i> Available</span>
                  <span class="legend-item legend-limited"><i class="legend-dot dot-limited"></i> Limited availability</span>
                  <span class="legend-item legend-occupied"><i class="legend-dot dot-occupied"></i> Occupied</span>
                  <span class="legend-item legend-maintenance"><i class="legend-dot dot-maintenance"></i> Under maintenance</span>
                </div>
              </article>
            </div>
          </div>
        </div>
      </section>
    @endif
  </main>

  {{-- SECTION: Featured Facilities --}}
  <section id="facilities" class="py-5 facility-feature">
    @php
      $facilityHero = $facilityHero ?? [
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

      $facilityTiles = $facilityTiles ?? [
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
      $featuredServices = $featuredServices ?? [
        [
          'name' => 'Meeting room booking',
          'summary' => 'Live availability, instant approvals, and guided forms for every huddle or town hall.',
          'icon' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none"><rect x="4" y="6" width="16" height="12" rx="3" stroke="currentColor" stroke-width="1.8"/><path d="M4 10h16M10 14h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>',
        ],
        [
          'name' => 'SEI facilities',
          'summary' => 'Innovation labs, studios, and special venues with concierge prep and equipment support.',
          'icon' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none"><path d="M4 10l8-6 8 6v8a2 2 0 0 1-2 2h-1v-5h-4v5H6a2 2 0 0 1-2-2v-8z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>',
        ],
        [
          'name' => 'Shuttle service',
          'summary' => 'Reserve point-to-point or loop shuttles with passenger manifests and live tracking.',
          'icon' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none"><rect x="3" y="7" width="18" height="10" rx="3" stroke="currentColor" stroke-width="1.8"/><path d="M6 17v2M18 17v2M4 12h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>',
        ],
      ];
    @endphp
    <div class="container">
      <div class="text-center mb-5">
        <span class="section-eyebrow">Featured services &amp; spaces</span>
        <h2 class="fw-semibold mb-1">The right room with the perfect services</h2>
        <p class="text-secondary mb-0">Go straight to the booking flow you need and see the rooms we’re spotlighting today.</p>
      </div>

      <div class="facility-services mb-4">
        @foreach ($featuredServices as $service)
          <article class="service-card">
            <div class="service-icon" aria-hidden="true">{!! $service['icon'] !!}</div>
            <h4>{{ $service['name'] }}</h4>
            <p>{{ $service['summary'] }}</p>
          </article>
        @endforeach
      </div>
      <div class="facility-divider" aria-hidden="true"></div>

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
                @foreach (($facilityHero['metrics'] ?? []) as $metric)
                  <li>
                    <span class="stat-label">{{ $metric['label'] }}</span>
                    <span class="stat-value">{{ $metric['value'] }}</span>
                  </li>
                @endforeach
              </ul>
              <div class="facility-hero-tags">
                @foreach (($facilityHero['chips'] ?? []) as $chip)
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
            @forelse ($facilityTiles as $tile)
              <article class="facility-mini facility-mini-{{ $tile['tone'] ?? 'available' }}">
                <div class="facility-mini-media" style="background-image:url('{{ $tile['image'] }}');"></div>
                <div class="facility-mini-body">
                  <div class="facility-mini-head">
                    <h5>{{ $tile['title'] }}</h5>
                    <span class="mini-type">{{ $tile['type'] }}</span>
                  </div>
                  <p class="mini-status">{{ $tile['status'] }}</p>
                  <ul class="mini-details">
                    @foreach (($tile['details'] ?? []) as $detail)
                      <li>{{ $detail }}</li>
                    @endforeach
                  </ul>
                  <div class="mini-actions">
                    <a href="{{ route('facilities.catalog') }}" class="btn btn-outline-light btn-sm">See details</a>
                    <button type="button" class="btn btn-link text-white p-0">Add to plan</button>
                  </div>
                </div>
              </article>
            @empty
              <article class="facility-mini facility-mini-available">
                <div class="facility-mini-body">
                  <div class="facility-mini-head">
                    <h5>More spaces coming soon</h5>
                    <span class="mini-type">Facilities</span>
                  </div>
                  <p class="mini-status">We’ll feature active rooms here once bookings are live.</p>
                  <div class="mini-actions">
                    <a href="{{ route('facilities.catalog') }}" class="btn btn-outline-light btn-sm">View catalog</a>
                  </div>
                </div>
              </article>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- SECTION: How It Works --}}
  @php
    $workflowSteps = [
      [
        'title' => 'Browse fast',
        'summary' => 'Use live signals to see which rooms are open now.',
        'eta' => '~2 min',
        'signal' => 'Live catalog',
        'icon' => 'search'
      ],
      [
        'title' => 'Submit once',
        'summary' => 'Launch the wizard, auto-fill the form, and drop in your agenda.',
        'eta' => '~4 min',
        'signal' => 'Smart request',
        'icon' => 'form'
      ],
      [
        'title' => 'Get the go',
        'summary' => 'Instant notifications, holds, and check-in reminders keep you on track.',
        'eta' => 'Instant',
        'signal' => 'Concierge watch',
        'icon' => 'bolt'
      ],
    ];

    $workflowIcons = [
      'search' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="11" cy="11" r="6" stroke="currentColor" stroke-width="1.8"/><path d="M16 16L21 21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>',
      'form'   => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M6 3h12l2 2v16H6z" stroke="currentColor" stroke-width="1.8"/><path d="M9 8h6M9 12h6M9 16h3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>',
      'bolt'   => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M13 2L4 13h6l-1 9 9-12h-6z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>',
    ];
  @endphp
  <section id="how-it-works" class="py-5 how-it-works">
    <div class="container">
      <div class="workflow-heading text-center mb-5">
        <span class="workflow-eyebrow">How it works</span>
        <h2 class="fw-semibold mb-2">Three quick beats from idea to confirmed booking</h2>
        <p class="workflow-subtext mb-0">No heavy reading—just the cues you need.</p>
      </div>

      <div class="workflow-grid">
        @foreach ($workflowSteps as $step)
          <article class="workflow-step">
            <div class="workflow-icon" aria-hidden="true">{!! $workflowIcons[$step['icon']] ?? '' !!}</div>
            <span class="workflow-step-number">Step {{ $loop->iteration }}</span>
            <h5>{{ $step['title'] }}</h5>
            <p class="workflow-step-summary">{{ $step['summary'] }}</p>
          </article>
        @endforeach
      </div>
    </div>
  </section>

  {{-- SECTION: Booking Policies (Teaser) --}}
  @php
    $policySnapshot = $policySnapshot ?? ['policies' => [], 'stats' => [], 'highlights' => [], 'rules' => []];
    $policyCards = $policySnapshot['policies'] ?? [];
    $policyStats = $policySnapshot['stats'] ?? ($policySnapshot['highlights'] ?? []);
  @endphp
  <section id="policies" class="py-5 policy-teaser">
    <div class="container">
      <div class="policy-shell">
        <div class="policy-lede">
          <span class="policy-eyebrow">Rules &amp; Policies</span>
          <h2>Live booking policies pulled from the admin tool</h2>
          <p>Everything here updates the moment you publish changes in the Rules &amp; Policies admin experience—so requestors always see what’s current.</p>
          <div class="policy-highlights policy-stats">
            @forelse ($policyStats as $highlight)
              <div class="policy-highlight">
                <span class="policy-highlight-label">{{ $highlight['label'] }}</span>
                <span class="policy-highlight-value">{{ $highlight['value'] }}</span>
              </div>
            @empty
              <div class="policy-highlight">
                <span class="policy-highlight-label">Status</span>
                <span class="policy-highlight-value">Live &amp; synced</span>
              </div>
            @endforelse
          </div>
          <div class="policy-actions">
            <a href="{{ route('booking.wizard') }}" class="btn btn-dark">Start a booking</a>
            <a href="{{ route('faq') }}" class="btn btn-ghost">View FAQ &amp; full policy guide</a>
          </div>
        </div>
        <div class="policy-grid">
          @forelse ($policyCards as $policy)
            <article class="policy-tile {{ !empty($policy['needs_review']) ? 'policy-tile-review' : '' }}">
              <div class="policy-tile-head">
                <div class="policy-pills">
                  <span class="policy-pill">{{ strtoupper($policy['domain'] ?? 'BOOKINGS') }}</span>
                  <span class="policy-status policy-status-{{ $policy['status'] ?? 'active' }}">{{ ucfirst($policy['status'] ?? 'active') }}</span>
                </div>
                <div class="policy-flags">
                  @if (!empty($policy['expiring']))
                    <span class="policy-flag policy-flag-warn">Expiring</span>
                  @endif
                  @if (!empty($policy['needs_review']))
                    <span class="policy-flag policy-flag-review">Needs review</span>
                  @endif
                </div>
              </div>
              <h3 class="policy-title">{{ $policy['name'] }}</h3>
              <p class="policy-reminder">{{ $policy['reminder'] ?? 'Live policy' }}</p>
              <div class="policy-meta">
                <span>Owner: {{ $policy['owner'] ?? 'Admin' }}</span>
                <span>Updated {{ $policy['updated_label'] ?? 'Recently' }}</span>
              </div>
              @if (!empty($policy['impact']))
                <p class="policy-impact">{{ $policy['impact'] }}</p>
              @endif
              @if (!empty($policy['tags']))
                <div class="policy-tags">
                  @foreach ($policy['tags'] as $tag)
                    <span>{{ $tag }}</span>
                  @endforeach
                </div>
              @endif
              <ul class="policy-rule-list">
                @forelse ($policy['rules'] ?? [] as $rule)
                  <li>
                    <span class="policy-check" aria-hidden="true">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                    </span>
                    <span>{{ $rule }}</span>
                  </li>
                @empty
                  <li class="policy-rule-empty">No rules published yet.</li>
                @endforelse
              </ul>
            </article>
          @empty
            <article class="policy-tile policy-tile-empty">
              <h3 class="policy-title">Policies sync from your admin tool</h3>
              <p class="policy-reminder">Publish a rule set to replace this fallback content.</p>
              <ul class="policy-rule-list">
                @foreach ($policyRules as $rule)
                  <li>
                    <span class="policy-check" aria-hidden="true">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                    </span>
                    <span>{{ $rule }}</span>
                  </li>
                @endforeach
              </ul>
            </article>
          @endforelse
        </div>
      </div>
    </div>
  </section>

  {{-- SECTION: Ready to Get Started --}}
  <section id="cta" class="py-5 cta-band">
    <div class="container">
      <div class="row align-items-center g-3">
        <div class="col-lg-8">
          <h2 class="fw-semibold mb-1 text-white">Ready to Get Started?</h2>
          <p class="workflow-subtext mb-0">Sign up and let's find you a room quickly!</p>
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
@livewireScripts
</html>
