          {{-- Flow preference selection --}}
          <section
            id="wizardFlowPreferenceSection"
            class="wizard-flow-preference d-none"
            hidden
          >
            <div class="wizard-flow-backwrap d-none" id="wizardFlowBackWrap">
              <button
                type="button"
                class="admin-back-button wizard-flow-back"
                id="wizardFlowPreferenceBack"
              >
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to service selection
              </button>
            </div>
            <div class="text-center mb-4">
              <p class="wizard-flow-pretitle text-uppercase small fw-semibold mb-2">
                Smarter booking
              </p>
              <h2 class="wizard-flow-title mb-2" tabindex="-1">
                Start where you're ready
              </h2>
              <p class="wizard-flow-subtitle mb-0">
                Tell us if you want to begin by locking the room or by securing the schedule
            </div>

            <div class="row g-4 justify-content-center">
              <div class="col-12 col-md-6 col-lg-4 d-flex justify-content-center">
                <button
                  type="button"
                  class="wizard-flow-card w-100 d-flex flex-column text-center"
                  data-flow-preference="room-first"
                >
                  <span class="wizard-flow-chip text-center">
                    Room first
                  </span>
                  <div class="wizard-flow-card-body">
                    <div class="wizard-flow-icon mx-auto" aria-hidden="true">
                      <svg width="44" height="44" viewBox="0 0 32 32" fill="none">
                        <defs>
                          <linearGradient id="wizardRoomGradient" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="#155DFC"/>
                            <stop offset="100%" stop-color="#001840"/>
                          </linearGradient>
                        </defs>
                        <rect x="4" y="9" width="24" height="16" rx="4" stroke="url(#wizardRoomGradient)" stroke-width="1.8" />
                        <path d="M10 14h12" stroke="url(#wizardRoomGradient)" stroke-width="1.8" stroke-linecap="round" />
                        <path d="M10 18h7" stroke="url(#wizardRoomGradient)" stroke-width="1.8" stroke-linecap="round" />
                      </svg>
                    </div>
                    <h3 class="wizard-flow-card-title mb-2">
                      Lock the room first
                    </h3>
                    <p class="wizard-flow-card-desc mb-0">
                      Jump straight to the rooms you already prefer, then we’ll check the open slots for that space.
                    </p>
                  </div>
                </button>
              </div>

              <div class="col-12 col-md-6 col-lg-4 d-flex justify-content-center">
                <button
                  type="button"
                  class="wizard-flow-card wizard-flow-card--accent w-100 d-flex flex-column text-center"
                  data-flow-preference="date-first"
                >
                  <span class="wizard-flow-chip text-center">
                    Date &amp; time first
                  </span>
                  <div class="wizard-flow-card-body">
                    <div class="wizard-flow-icon mx-auto" aria-hidden="true">
                      <svg width="44" height="44" viewBox="0 0 32 32" fill="none">
                        <defs>
                          <linearGradient id="wizardDateGradient" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="#155DFC"/>
                            <stop offset="100%" stop-color="#001840"/>
                          </linearGradient>
                        </defs>
                        <rect x="5" y="7" width="22" height="20" rx="4" stroke="url(#wizardDateGradient)" stroke-width="1.8" />
                        <path d="M11 5v6" stroke="url(#wizardDateGradient)" stroke-width="1.8" stroke-linecap="round" />
                        <path d="M21 5v6" stroke="url(#wizardDateGradient)" stroke-width="1.8" stroke-linecap="round" />
                        <path d="M8 13h16" stroke="url(#wizardDateGradient)" stroke-width="1.8" stroke-linecap="round" />
                        <rect x="11" y="16" width="4" height="4" rx="1" fill="url(#wizardDateGradient)" />
                        <rect x="17" y="19" width="4" height="4" rx="1" fill="url(#wizardDateGradient)" />
                      </svg>
                    </div>
                    <h3 class="wizard-flow-card-title mb-2">
                      Secure the schedule first
                    </h3>
                    <p class="wizard-flow-card-desc mb-0">
                      Working around a fixed date? Pick it now and we’ll surface rooms that can host you at that time.
                    </p>
                  </div>
                </button>
              </div>
            </div>
          </section>
