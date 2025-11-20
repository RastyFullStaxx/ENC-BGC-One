@once
  <form id="global-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
  </form>

  <script>
    document.addEventListener('click', async (event) => {
      const trigger = event.target.closest('[data-logout-trigger]');
      if (!trigger) return;

      event.preventDefault();

      // Prefer POST, but gracefully fall back to GET (route now allows both)
      const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

      try {
        const response = await fetch('{{ route('logout') }}', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': token || '',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
          },
          credentials: 'same-origin',
        });

        // If already logged out or token expired, still send to landing.
        if (response.ok || response.status === 401 || response.status === 419) {
          window.location.replace('{{ route('landing') }}');
          return;
        }
      } catch (e) {
        // Ignore and fall through to form submit.
      }

      // Fallback to GET logout to avoid stale/missing CSRF.
      window.location.replace('{{ route('logout') }}');
    });
  </script>
@endonce
