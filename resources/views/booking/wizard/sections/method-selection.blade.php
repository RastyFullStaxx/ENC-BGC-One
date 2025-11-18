          {{-- Booking method selection --}}
          <section
            id="wizardMethodSection"
            class="wizard-method-section mt-4"
          >
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
                        class="wizard-method-card w-100 text-start d-flex flex-column flex-grow-1"
                        data-method="sei">
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
                    <span>Perfect for events, exhibits, and custom layouts.</span>
                  </div>
                </button>
              </div>

              {{-- Transportation & Shuttle --}}
              <div class="col-12 col-lg-4 d-flex">
                <button type="button"
                        class="wizard-method-card w-100 text-start d-flex flex-column flex-grow-1"
                        data-method="transport">
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
                    Need to move teams or equipment? We’ll plan the trip for you.
                  </div>
                </button>
              </div>
            </div>
          </section>

