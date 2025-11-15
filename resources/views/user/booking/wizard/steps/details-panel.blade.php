{{-- Step 3 panel --}}
          <div id="wizardDetailsPanel" class="d-none">
            <div class="wizard-details-card card border-0 shadow-sm h-100">
              <div class="card-body p-3 p-md-4">
                <div class="mb-3">
                  
                    Tell us a bit more about your meeting.
                  </h2>
                  <p class="text-muted small mb-0">
                    We’ll share these details with the facility team so they can prep the room and any support you might need.
                  </p>
                </div>

                <div class="row g-3 wizard-details-grid">
                  <div class="col-12 col-lg-6">
                    <div class="wizard-step-subcard wizard-attendees-card mb-3 text-center">
                      <p class="text-uppercase small fw-semibold text-muted mb-1">Number of attendees *</p>
                      <h3 class="h5 mb-1 text-dark">How many colleagues are joining?</h3>
                      <p class="small text-muted mb-3">We’ll double-check seat count based on this number.</p>
                      <div class="wizard-counter justify-content-center w-100 mt-3" aria-live="polite">
                        <button
                          type="button"
                          class="btn btn-outline-light wizard-counter-btn"
                          id="wizardAttendeesDecrease"
                          aria-label="Decrease attendees"
                        >
                          −
                        </button>
                        <div class="wizard-counter-value">
                          <div class="wizard-counter-number" id="wizardAttendeesValue">4</div>
                          <div class="wizard-counter-label small text-muted text-uppercase">People</div>
                        </div>
                        <button
                          type="button"
                          class="btn btn-outline-light wizard-counter-btn"
                          id="wizardAttendeesIncrease"
                          aria-label="Increase attendees"
                        >
                          +
                        </button>
                      </div>
                      <div class="wizard-attendees-badge-wrap">
                        <span class="badge wizard-attendees-badge">Max 20 in-room</span>
                      </div>
                      <input type="hidden" id="wizardAttendeesInput" value="4" data-min="1" data-max="20">
                      <p class="wizard-form-tip small text-muted mt-3 mb-0">
                        Need more than 20? Leave a note so we can suggest larger venues or hybrid setups.
                      </p>
                    </div>

                    <div class="wizard-step-subcard">
                      <label for="wizardAgendaInput" class="text-uppercase small fw-semibold text-muted mb-1">Purpose / Agenda *</label>
                      <h3 class="h6 mb-2 text-dark">What’s this meeting about?</h3>
                      <textarea
                        id="wizardAgendaInput"
                        class="form-control"
                        rows="4"
                        placeholder="E.g., Quarterly planning sync with Ops and Finance teams."
                      ></textarea>
                      <p class="wizard-form-tip small text-muted mt-2 mb-0">
                        Include any context the facilities team should know (presentations, clients attending, special layout, etc.).
                      </p>
                    </div>
                  </div>

                  <div class="col-12 col-lg-6">
                    <div class="wizard-step-subcard wizard-support-card h-100">
                      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                        <div>
                          <p class="text-uppercase small fw-semibold text-muted mb-1">Request SFI Support</p>
                          <h3 class="h6 mb-1 text-dark">Need help with setup?</h3>
                          <p class="small text-muted mb-0">Our support team can assist with manpower, refreshments, or AV prep.</p>
                        </div>
                        <div class="form-check form-switch wizard-support-toggle m-0">
                          <input class="form-check-input" type="checkbox" role="switch" id="wizardSupportToggle">
                          <label class="form-check-label small text-muted" for="wizardSupportToggle"></label>
                        </div>
                      </div>

                      <div
                        id="wizardSupportFields"
                        class="wizard-support-fields d-none"
                        aria-live="polite"
                        aria-hidden="true"
                      >
                        <div class="wizard-support-field">
                          <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                            <div>
                              <p class="text-uppercase small fw-semibold text-muted mb-1 mb-md-0">SFI manpower required *</p>
                              <p class="small text-muted mb-0">Maximum 10 staff members</p>
                            </div>
                            <div class="wizard-counter wizard-counter-compact" aria-live="polite">
                              <button
                                type="button"
                                class="btn btn-outline-light wizard-counter-btn"
                                id="wizardSupportCountDecrease"
                                aria-label="Decrease support personnel"
                              >
                                −
                              </button>
                              <div class="wizard-counter-value">
                                <div class="wizard-counter-number" id="wizardSupportCountValue">1</div>
                                <div class="wizard-counter-label small text-muted">Staff</div>
                              </div>
                              <button
                                type="button"
                                class="btn btn-outline-light wizard-counter-btn"
                                id="wizardSupportCountIncrease"
                                aria-label="Increase support personnel"
                              >
                                +
                              </button>
                            </div>
                          </div>
                          <input type="hidden" id="wizardSupportCountInput" value="1" data-min="1" data-max="10">
                        </div>

                        <div class="wizard-support-field">
                          <p class="text-uppercase small fw-semibold text-muted mb-2">Equipment Needed</p>
                          <div class="wizard-equipment-grid">
                            @foreach ($wizardSupportEquipment as $equipment)
                              @php
                                $inputId = 'supportEquipment_' . $equipment['id'];
                              @endphp
                              <div class="wizard-equipment-option">
                                <input
                                  type="checkbox"
                                  class="btn-check"
                                  id="{{ $inputId }}"
                                  name="supportEquipment[]"
                                  value="{{ $equipment['id'] }}"
                                  data-equipment-name="{{ $equipment['label'] }}"
                                  autocomplete="off"
                                >
                                <label class="wizard-equipment-chip" for="{{ $inputId }}">
                                  {{ $equipment['label'] }}
                                </label>
                              </div>
                            @endforeach
                          </div>
                        </div>

                        <div class="wizard-support-field">
                          <label for="wizardSupportNotes" class="text-uppercase small fw-semibold text-muted mb-1">
                            Additional requirements / notes
                          </label>
                          <textarea
                            id="wizardSupportNotes"
                            class="form-control"
                            rows="3"
                            placeholder="E.g., Need 20 bottles of water, coffee/tea, standing mics, table arrangement, etc."
                          ></textarea>
                          <p class="wizard-form-tip small text-muted mt-2 mb-0">
                            Typical requests: refreshments, AV setup, ushering, table arrangement, remote call support.
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 mt-4 pt-2">
                  <button type="button" class="btn btn-light wizard-back-button" id="wizardBackToDate">
                    Back to Date &amp; Time
                  </button>
                  <button type="button" class="btn btn-room-available" id="wizardNextReview" disabled>
                    Next: Review &amp; Confirm
                  </button>
                </div>
              </div>
            </div>
          </div>

          