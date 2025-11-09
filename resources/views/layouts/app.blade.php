<!doctype html>
<html lang="en" data-bs-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'ENC Booking')</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{-- Global CSS/JS (Bootstrap via Vite) --}}
  @vite(['resources/css/landing.css', 'resources/js/landing.js'])

  {{-- Page-specific assets --}}
  @stack('styles')
</head>
<body>

  {{-- Optional: site navbar --}}
  @includeIf('partials.nav')

  <main>@yield('content')</main>

  {{-- Footer you already have --}}
  @include('partials.footer')

  {{-- Page-specific scripts --}}
  @stack('scripts')
</body>
</html>
