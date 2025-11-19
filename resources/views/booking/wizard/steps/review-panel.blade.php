{{-- Step 4 panel --}}
          <div id="wizardReviewPanel" class="d-none">
            <div class="wizard-review-card card border-0 shadow-sm h-100">
              <div class="card-body p-3 p-md-4">
                <div class="mb-3">
                  
                    Let’s make sure everything looks right.
                  </h2>
                  <p class="text-muted small mb-0">
                    Double-check the room, schedule, and details before sending the request to the facilities team.
                  </p>
                </div>

                <div class="row g-3 wizard-review-grid">
                  <div class="col-12 col-lg-6">
                    <div class="wizard-step-subcard h-100">
                      <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                          <p class="text-uppercase small fw-semibold text-muted mb-1">Room &amp; Schedule</p>
                          <h3 class="h6 mb-0 text-dark">Meeting basics</h3>
                        </div>
                        <span class="badge text-bg-light text-dark-emphasis small">Step 2</span>
                      </div>
                      <dl class="wizard-review-list">
                        <div>
                          <dt>Room</dt>
                          <dd id="wizardReviewRoom">Not selected</dd>
                        </div>
                        <div>
                          <dt>Date</dt>
                          <dd id="wizardReviewDate">—</dd>
                        </div>
                        <div>
                          <dt>Time</dt>
                          <dd id="wizardReviewTime">—</dd>
                        </div>
                        <div>
                          <dt>Duration</dt>
                          <dd id="wizardReviewDuration">—</dd>
                        </div>
                      </dl>
                    </div>
                  </div>

                  <div class="col-12 col-lg-6">
                    <div class="wizard-step-subcard h-100">
                      <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                          <p class="text-uppercase small fw-semibold text-muted mb-1">Booking Details</p>
                          <h3 class="h6 mb-0 text-dark">People &amp; purpose</h3>
                        </div>
                        <span class="badge text-bg-light text-dark-emphasis small">Step 3</span>
                      </div>
                      <dl class="wizard-review-list">
                        <div>
                          <dt>Attendees</dt>
                          <dd id="wizardReviewAttendees">—</dd>
                        </div>
                        <div>
                          <dt>Purpose / Agenda</dt>
                          <dd id="wizardReviewAgenda">—</dd>
                        </div>
                      </dl>
                    </div>
                  </div>

                  <div class="col-12">
                    <div class="wizard-step-subcard wizard-review-support">
                      <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                          <p class="text-uppercase small fw-semibold text-muted mb-1">SFI Support</p>
                          <h3 class="h6 mb-0 text-dark">Add-ons &amp; assistance</h3>
                        </div>
                        <span class="badge text-bg-light text-dark-emphasis small" id="wizardReviewSupportStatus">Not requested</span>
                      </div>
                      <div id="wizardReviewSupportSummary" class="wizard-review-support-summary text-muted small">
                        No additional support requested for this booking.
                      </div>
                    </div>
                  </div>
                </div>

                <div class="wizard-review-actions d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 mt-4 pt-2">
                  <button type="button" class="btn btn-light wizard-back-button" id="wizardBackToDetails">
                    Back to Booking Details
                  </button>
                  <div class="d-flex flex-column flex-md-row align-items-center gap-2">
                    <button type="button" class="btn btn-outline-primary">
                      Save as draft
                    </button>
                    <button type="button" class="btn wizard-abort-btn" data-action="wizard-abort-booking">
                      Abort booking
                    </button>
                    <button type="button" class="btn btn-room-submit btn-lg" id="wizardSubmitRequest">
                      Submit booking request
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

        
