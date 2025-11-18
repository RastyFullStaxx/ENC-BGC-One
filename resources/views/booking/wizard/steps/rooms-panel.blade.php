{{-- Step 1 panel --}}
          <div id="wizardRoomsPanel">
            <div class="wizard-rooms-card card border-0 shadow-sm h-100">
            <div class="card-body p-3 p-md-4">
              <div class="mb-3">
                <h2 class="wizard-rooms-title h5 mb-1">
                  Browse All Rooms
                </h2>
                <p class="text-muted small mb-0">
                  Browse available rooms and filters to find the best fit for your meeting.
                </p>
              </div>

              {{-- Filters row --}}
              <div class="row g-2 g-md-3 align-items-center mb-3">
                <div class="col-12 col-md-6">
                  <label for="roomSearch" class="form-label small mb-1">Search rooms…</label>
                  <div class="position-relative">
                    <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted" aria-hidden="true">
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                        <circle cx="11" cy="11" r="6" stroke="currentColor" stroke-width="1.6"/>
                        <path d="M16 16l4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                      </svg>
                    </span>
                    <input
                      type="search"
                      id="roomSearch"
                      class="form-control ps-5"
                      placeholder="Search by name, location, or feature"
                      autocomplete="off"
                    >
                  </div>
                </div>

                <div class="col-6 col-md-3">
                  <label for="roomFloor" class="form-label small mb-1">Floor</label>
                  <select id="roomFloor" class="form-select">
                    <option value="">All Floors</option>
                    <option>Ground Floor</option>
                    <option>2nd Floor</option>
                    <option>3rd Floor</option>
                  </select>
                </div>

                <div class="col-6 col-md-3">
                  <label for="roomSize" class="form-label small mb-1">Size</label>
                  <select id="roomSize" class="form-select">
                    <option value="">All Sizes</option>
                    <option value="small">Small (≤ 6)</option>
                    <option value="medium">Medium (7–12)</option>
                    <option value="large">Large (13+)</option>
                  </select>
                </div>
              </div>

              {{-- Room cards --}}
              <div class="row g-3" id="wizardRoomsGrid">
                {{-- Room cards will be loaded dynamically via JavaScript --}}
                <div class="col-12 text-center py-5">
                  <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading facilities...</span>
                  </div>
                  <p class="text-muted mt-2">Loading available facilities...</p>
                </div>
              </div>

                <div class="d-flex align-items-center flex-wrap gap-3 mt-4" id="wizardRoomsActions">
                  <button
                    type="button"
                    class="btn btn-light"
                    id="wizardBackToMethods"
                  >
                    Go Back to Booking Type
                  </button>
                  <button
                    type="button"
                    class="btn btn-room-available wizard-next-date ms-auto"
                    disabled
                  >
                    Next: Select Date &amp; Time
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div> {{-- end wizardRoomsPanel --}}

          