<div class="dropdown" wire:poll.30s>
  <button class="btn btn-light border-0 enc-nav-icon-btn position-relative" type="button" id="notifBell" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications" wire:click="markSeen">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
      <path d="M12 4a5 5 0 00-5 5v3.382l-.894 2.236A1 1 0 007.03 16h9.94a1 1 0 00.924-1.382L17 12.382V9a5 5 0 00-5-5z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
      <path d="M10 19a2 2 0 104 0" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
    </svg>
    @if(($count ?? 0) > 0 && !($muteBadge ?? false))
      <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger small">
        {{ $count > 99 ? '99+' : $count }}
      </span>
    @endif
  </button>
  <div class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="notifBell" style="min-width: 320px;">
    <div class="px-3 py-2 d-flex justify-content-between align-items-center">
      <span class="fw-semibold small">Notifications</span>
      <span class="text-muted small">{{ $count ?? 0 }} total</span>
    </div>
    <div class="dropdown-divider my-1"></div>
    @if (($notifications ?? collect())->isEmpty())
      <div class="px-3 py-3 text-muted small">No notifications yet.</div>
    @else
      <div class="list-group list-group-flush">
        @foreach ($notifications as $item)
          <div class="list-group-item px-3 py-2">
            <div class="d-flex justify-content-between align-items-center">
              <span class="fw-semibold small">{{ $item['facility'] }}</span>
              <span class="badge text-bg-light border small">{{ $item['channel'] }}</span>
            </div>
            <div class="small text-muted">{{ $item['purpose'] }} @if($item['time']) • {{ $item['time'] }} @endif</div>
            <div class="d-flex justify-content-between align-items-center mt-1">
              <span class="badge bg-primary-subtle text-primary border-primary-subtle small">{{ $item['status'] }}</span>
              <span class="text-muted small">{{ $item['created_at'] }}</span>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</div>

@auth
  <script>
    (() => {
      const userId = {{ auth()->id() }};
      const dropdownEl = document.getElementById('notifBell');

      document.addEventListener('livewire:initialized', () => {
        if (window.Echo && userId) {
          window.Echo.private(`users.${userId}`)
            .listen('.notification.created', () => {
              Livewire.dispatch('refreshNotifications');
            });
        }
      });

      // Only auto-close when the navbar retracts (e.g., idle header animation)
      const hideDropdown = () => {
        if (!dropdownEl) return;
        const instance = bootstrap.Dropdown.getInstance(dropdownEl);
        if (instance) { instance.hide(); }
      };

      const header = document.querySelector('.header-slide');
      if (header) {
        const observer = new MutationObserver(() => {
          if (header.classList.contains('header-hidden')) {
            hideDropdown();
          }
        });
        observer.observe(header, { attributes: true, attributeFilter: ['class'] });
      }
    })();
  </script>
@endauth
