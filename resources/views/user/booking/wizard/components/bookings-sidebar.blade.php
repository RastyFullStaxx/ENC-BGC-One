          <aside
            id="wizardBookingsSidebar"
            class="wizard-bookings-sidebar card border-0 shadow-sm h-100"
            aria-label="Your bookings"
          >
            <div class="card-body p-3 p-md-4">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h2 class="h6 mb-0">Your Bookings</h2>
                <span class="badge rounded-pill bg-light text-secondary small">{{ count($userBookings) }}</span>
              </div>
              <p class="small text-muted mb-3">
                @auth
                  Logged in as <strong>{{ auth()->user()->email }}</strong><br>
                  Total Bookings: {{ count($userBookings) }}
                @else
                  Please log in to view your bookings
                @endauth
              </p>

              <ul class="nav nav-pills small mb-3" role="tablist">
                @php
                  $pendingCount = collect($userBookings)->where('status', 'Pending')->count();
                  $approvedCount = collect($userBookings)->where('status', 'Approved')->count();
                @endphp
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" data-bs-toggle="pill" type="button" data-status="pending">
                    Pending ({{ $pendingCount }})
                  </button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" data-bs-toggle="pill" type="button" data-status="approved">
                    Confirmed ({{ $approvedCount }})
                  </button>
                </li>
              </ul>

              <div class="wizard-bookings-list small">
                @forelse ($userBookings as $booking)
                  @php
                    $statusClass = match(strtolower($booking['status'])) {
                      'pending' => 'bg-warning text-dark',
                      'approved' => 'bg-success text-white',
                      'rejected' => 'bg-danger text-white',
                      'cancelled' => 'bg-secondary text-white',
                      default => 'bg-light text-dark',
                    };
                    $bgClass = match(strtolower($booking['status'])) {
                      'pending' => 'bg-warning-subtle',
                      'approved' => 'bg-success-subtle',
                      'rejected' => 'bg-danger-subtle',
                      'cancelled' => 'bg-secondary-subtle',
                      default => 'bg-light',
                    };
                  @endphp
                  <div class="wizard-booking-item border rounded-3 p-2 mb-2 {{ $bgClass }}" data-booking-status="{{ strtolower($booking['status']) }}">
                    <div class="d-flex justify-content-between">
                      <span class="badge {{ $statusClass }} me-2">{{ $booking['status'] }}</span>
                      <span class="text-muted">{{ $booking['date'] }}</span>
                    </div>
                    <div class="fw-semibold mt-1">{{ $booking['room'] }}</div>
                    <div class="text-muted">{{ $booking['time'] }}</div>
                  </div>
                @empty
                  <div class="text-center py-4 text-muted">
                    <p class="mb-0">No bookings yet</p>
                    <small>Your booking requests will appear here</small>
                  </div>
                @endforelse
              </div>
            </div>
          </aside>
