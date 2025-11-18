<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging In...</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js"></script>
    @vite(['resources/css/auth/login/loading.css', 'resources/js/auth/login/loading.js'])
</head>
<body data-animation-path="{{ asset('css/auth/Calendar and clock.json') }}"
      data-default-redirect="{{ route('user.dashboard') }}">
    <div class="lottie-container" id="lottieAnimation"></div>
    {{-- <p class="loading-text">Logging you in...</p> --}}
</body>
</html>
