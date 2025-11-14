<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging In...</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            width: 100vw;
            height: 100vh;
            overflow: hidden;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        .lottie-container {
            width: 80%;
            max-width: 600px;
            height: 500px;
        }

        .loading-text {
            color: #001840;
            font-size: 1.5rem;
            font-weight: 600;
            margin-top: 2rem;
            text-align: center;
            animation: pulse 1.5s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.6;
            }
        }
    </style>
</head>
<body>
    <div class="lottie-container" id="lottieAnimation"></div>
    {{-- <p class="loading-text">Logging you in...</p> --}}

    <!-- Lottie Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js"></script>
    
    <script>
        // Load Lottie animation
        const animation = lottie.loadAnimation({
            container: document.getElementById('lottieAnimation'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: '{{ asset("css/auth/Calendar and clock.json") }}'
        });

        // Get redirect URL from query parameter
        const urlParams = new URLSearchParams(window.location.search);
        const redirectUrl = urlParams.get('redirect') || '{{ route("user.dashboard") }}';

        // Redirect after 3 seconds
        setTimeout(() => {
            window.location.href = redirectUrl;
        }, 3000);
    </script>
</body>
</html>
