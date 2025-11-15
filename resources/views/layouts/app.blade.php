<!doctype html>
<html lang="en" data-bs-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'ENC Booking')</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{-- Default assets --}}
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @vite([
    'resources/css/design-system.css',
    'resources/css/landing/base.css',
    'resources/css/landing/hero.css',
    'resources/css/landing/availability.css',
    'resources/css/landing/facilities.css',
    'resources/css/landing/how-it-works.css',
    'resources/css/landing/policies.css',
    'resources/css/landing/cta.css',
    'resources/js/landing.js'
  ])

  {{-- Page-specific assets --}}
  @stack('styles')
</head>
<body class="enc-type-body">

  {{-- Optional: site navbar --}}
  @hasSection('app-navbar')
    @yield('app-navbar')
  @else
    @includeIf('partials.nav')
  @endif

  <main>@yield('content')</main>

  {{-- Footer you already have --}}
  @include('partials.footer')

  {{-- Page-specific scripts --}}
  @stack('scripts')
</body>
</html>
