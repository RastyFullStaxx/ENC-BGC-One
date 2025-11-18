{{-- Step 2 panel --}}
          <div id="wizardDatePanel" class="d-none">
            <div class="wizard-date-card card border-0 shadow-sm h-100">
              <div class="card-body p-3 p-md-4">
                <div class="mb-3">
                  <h2 class="wizard-rooms-title h5 mb-1">
                    When will you need the room?
                  </h2>
                  <p class="text-muted small mb-0">
                    Pick a date and time range. We’ll hold the slot while you complete the request.
                  </p>
                </div>

                <div class="wizard-date-selected mb-4">
                  <span class="text-muted small d-block">Selected Room</span>
                  <span class="fw-semibold h6 mb-0" id="wizardSelectedRoomName">None selected yet</span>
                </div>

                <input type="hidden" id="bookingDate">

                <div class="row g-3 wizard-date-grid">
                  <div class="col-12 col-lg-6">
                    <div class="wizard-step-subcard h-100">
                      <div class="wizard-step-subcard-header mb-3">
                        <div>
                          <p class="text-uppercase small fw-semibold text-muted mb-1">Select Date *</p>
                          <h3 class="h6 mb-1 text-dark">Choose when you need the room</h3>
                          <p class="small text-muted mb-0">See availability at a glance and tap a date to continue.</p>
                        </div>
                      </div>
                      <div
                        id="wizardCalendar"
                        class="wizard-calendar"
                        role="application"
                        aria-label="Meeting date selector"
                      >
                        <div class="wizard-calendar-header d-flex justify-content-between align-items-center mb-3">
                          <button
                            type="button"
                            class="btn btn-outline-light wizard-calendar-nav"
                            id="wizardCalendarPrev"
                            aria-label="Previous month"
                          >
                            ‹
                          </button>
                          <div class="text-center">
                            <div class="wizard-calendar-month fw-semibold" id="wizardCalendarMonth">November 2025</div>
                            <div class="wizard-calendar-subtitle small text-muted">Tap any available date</div>
                          </div>
                          <button
                            type="button"
                            class="btn btn-outline-light wizard-calendar-nav"
                            id="wizardCalendarNext"
                            aria-label="Next month"
                          >
                            ›
                          </button>
                        </div>
                        <div class="wizard-calendar-daynames" aria-hidden="true">
                          <span>Su</span>
                          <span>Mo</span>
                          <span>Tu</span>
                          <span>We</span>
                          <span>Th</span>
                          <span>Fr</span>
                          <span>Sa</span>
                        </div>
                        <div
                          class="wizard-calendar-grid"
                          id="wizardCalendarGrid"
                          role="grid"
                          aria-labelledby="wizardCalendarMonth"
                        ></div>
                        <div class="wizard-calendar-selection small text-muted mt-3">
                          Selected date:
                          <span class="fw-semibold text-dark" id="wizardCalendarSelectedDate">None yet</span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-lg-6">
                    <div class="wizard-step-subcard h-100">
                      <div class="wizard-step-subcard-header mb-3">
                        <div>
                          <p class="text-uppercase small fw-semibold text-muted mb-1">Set Time Range *</p>
                          <h3 class="h6 mb-1 text-dark">When will your meeting start and end?</h3>
                          <p class="small text-muted mb-0">We’ll hold this slot while you review details on the next step.</p>
                        </div>
                      </div>
                      <div class="row g-3 wizard-time-range-inputs">
                        <div class="col-12 col-md-6">
                          <label for="bookingStartTime" class="form-label small text-muted mb-1">Start Time</label>
                          <div class="wizard-time-input">
                            <span aria-hidden="true">🕒</span>
                            <select id="bookingStartTime" class="form-select">
                              <option value="">Select start</option>
                              @foreach ($timeSlots as $slot)
                                <option value="{{ $slot }}">{{ \Carbon\Carbon::createFromFormat('H:i', $slot)->format('g:i A') }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-12 col-md-6">
                          <label for="bookingEndTime" class="form-label small text-muted mb-1">End Time</label>
                          <div class="wizard-time-input">
                            <span aria-hidden="true">🕒</span>
                            <select id="bookingEndTime" class="form-select">
                              <option value="">Select end</option>
                              @foreach ($timeSlots as $slot)
                                <option value="{{ $slot }}">{{ \Carbon\Carbon::createFromFormat('H:i', $slot)->format('g:i A') }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="wizard-duration-pill mt-4" id="wizardDurationPill">
                        <span class="wizard-duration-icon" aria-hidden="true">⏱</span>
                        <span id="wizardDurationLabel">Select start and end time</span>
                      </div>
                      <p class="small text-muted mt-3 mb-0">
                        Tip: Choose at least 30 minutes. You can always adjust before submitting the request.
                      </p>
                    </div>
                  </div>
                </div>

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 mt-4 pt-2">
                  <button type="button" class="btn btn-light wizard-back-button" id="wizardBackToRooms">
                    Go Back to Select Room
                  </button>
                  <button type="button" class="btn btn-room-available" id="wizardNextDetails" disabled>
                    Next: Add Details
                  </button>
                </div>
              </div>
            </div>
          </div>

          