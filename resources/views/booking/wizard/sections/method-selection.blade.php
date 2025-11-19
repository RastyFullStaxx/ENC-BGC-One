          {{-- Booking method selection --}}
          <section
            id="wizardMethodSection"
            class="wizard-method-section mt-4"
          >
            <button
              type="button"
              class="admin-back-button wizard-method-back"
              id="wizardMethodBack"
            >
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
              </svg>
              Back to previous page
            </button>
            <div class="text-center mb-4">
              <h2 class="wizard-method-title mb-2" tabindex="-1">
                What do you need to book today?
              </h2>
              <p class="wizard-method-subtitle mb-0">
                Choose the guided flow that best matches your request. We’ll do the rest.
              </p>
            </div>

            <div class="row g-4 justify-content-center">
              {{-- Book Meeting Room --}}
              <div class="col-12 col-lg-4 d-flex">
                <button type="button"
                        class="wizard-method-card is-recommended w-100 text-start d-flex flex-column flex-grow-1"
                        data-method="manual">
                  <div class="text-center mb-3">
                    <span class="wizard-method-icon d-inline-flex align-items-center justify-content-center rounded-3 mx-auto mb-3">
                      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <rect x="3" y="5" width="18" height="14" rx="3" stroke="currentColor" stroke-width="1.6"/>
                        <path d="M7 9h10" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                        <path d="M7 13h5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                      </svg>
                    </span>
                    <div class="text-center">
                      <div class="wizard-method-name">Book Meeting Room</div>
                      <div class="wizard-method-desc">
                        Reserve conference rooms and meeting spaces with the right capacity and equipment.
                      </div>
                    </div>
                  </div>
                  <div class="wizard-method-footnote">
                    Ideal for quick team syncs, client visits, and presentations.
                  </div>
                </button>
              </div>

              {{-- SEI --}}
              <div class="col-12 col-lg-4 d-flex">
                <button type="button"
                        class="wizard-method-card is-disabled w-100 text-start d-flex flex-column flex-grow-1"
                        data-method="sei"
                        disabled
                        aria-disabled="true">
                  <div class="text-center mb-3">
                    <span class="wizard-method-icon d-inline-flex align-items-center justify-content-center rounded-3 mx-auto mb-3">
                      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M4 7h16M4 12h16M4 17h10" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                        <path d="M7 4h10v16H7z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                      </svg>
                    </span>
                    <div class="text-center">
                      <div class="wizard-method-name">SEI Facilities</div>
                      <div class="wizard-method-desc">
                        Request special facilities, infrastructure setups, and book shuttle service support.
                      </div>
                    </div>
                  </div>
                  <div class="wizard-method-footnote d-flex align-items-center gap-1">
                    <span aria-hidden="true">🛠️</span>
                    <span>Coming soon — special setups will reopen here.</span>
                  </div>
                </button>
              </div>

              {{-- Transportation & Shuttle --}}
              <div class="col-12 col-lg-4 d-flex">
                <button type="button"
                        class="wizard-method-card is-disabled w-100 text-start d-flex flex-column flex-grow-1"
                        data-method="transport"
                        disabled
                        aria-disabled="true">
                  <div class="text-center mb-3">
                    <span class="wizard-method-icon d-inline-flex align-items-center justify-content-center rounded-3 mx-auto mb-3">
                      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M3 11h18v6a2 2 0 01-2 2h-1.5a1.5 1.5 0 01-3 0H9.5a1.5 1.5 0 01-3 0H5a2 2 0 01-2-2v-6z"
                              stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M7 11L5 6h14l-2 5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                      </svg>
                    </span>
                    <div class="text-center">
                      <div class="wizard-method-name">Book Shuttle Service</div>
                      <div class="wizard-method-desc">
                        Schedule transportation or shuttle services with clear pickup and drop-off details.
                      </div>
                    </div>
                  </div>
                  <div class="wizard-method-footnote">
                    Need to move teams or equipment? Shuttle booking is paused.
                  </div>
                </button>
              </div>
            </div>
          </section>
          <div class="wizard-draft-modal d-none" id="wizardDraftModal">
            <div class="wizard-draft-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="wizardDraftModalTitle" aria-describedby="wizardDraftModalDesc">
              <button type="button" class="wizard-draft-close" id="wizardDraftDismiss" aria-label="Dismiss draft prompt">
                <span aria-hidden="true">&times;</span>
              </button>
              <div class="wizard-draft-icon" aria-hidden="true">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                  <rect x="5" y="6" width="22" height="20" rx="4" stroke="currentColor" stroke-width="1.6"/>
                  <path d="M10 12h12M10 17h8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                </svg>
              </div>
              <p class="wizard-draft-eyebrow text-uppercase small fw-semibold text-muted mb-1">
                Draft detected
              </p>
              <h3 class="wizard-draft-title h4 mb-2" id="wizardDraftModalTitle">
                Continue your booking?
              </h3>
              <p class="wizard-draft-desc text-muted mb-3" id="wizardDraftModalDesc">
                We saved your last request so you can finish it later. Pick up where you left off or start a brand-new booking.
              </p>
              <dl class="wizard-draft-summary mb-4">
                <div>
                  <dt>Last step</dt>
                  <dd id="wizardDraftStepLabel">Step 1 — Select a room</dd>
                </div>
                <div>
                  <dt>Room</dt>
                  <dd id="wizardDraftRoomLabel">No room selected</dd>
                </div>
                <div>
                  <dt>Schedule</dt>
                  <dd id="wizardDraftScheduleLabel">No date selected</dd>
                </div>
                <div>
                  <dt>Details</dt>
                  <dd id="wizardDraftDetailsLabel" class="text-muted">
                    Agenda and support requests will appear here.
                  </dd>
                </div>
                <div>
                  <dt>Saved on</dt>
                  <dd id="wizardDraftSavedAt">—</dd>
                </div>
              </dl>
              <div class="wizard-draft-actions d-flex flex-column flex-md-row gap-2">
                <button type="button" class="btn btn-light flex-fill" id="wizardDraftReset">
                  Start a new booking
                </button>
                <button type="button" class="btn btn-primary flex-fill" id="wizardDraftContinue">
                  Continue where I left off
                </button>
              </div>
            </div>
          </div>
