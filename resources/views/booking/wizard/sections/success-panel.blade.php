    {{-- POST-SUBMIT SUCCESS PANEL --}}
    <section
      id="wizardSuccessPanel"
      class="wizard-success-panel container-fluid px-3 px-lg-5 d-none"
      hidden
      aria-live="polite"
    >
            <div class="wizard-success-card text-center shadow-sm border-0 mx-auto text-white">
        <p class="text-uppercase small fw-semibold wizard-success-muted mb-2">Booking success!</p>
        <h2 class="wizard-success-title mb-2">You're booked, <span id="wizardSuccessUser">{{ auth()->check() ? explode(' ', auth()->user()->name)[0] : 'User' }}</span>!</h2>
        <p class="wizard-success-muted mb-4">We’ve sent your request to the facilities team. You’ll get updates via email.</p>

        <div class="wizard-success-icon mb-4" aria-hidden="true">
          <span>✓</span>
        </div>

        <div class="wizard-success-reference mb-4">
          <p class="text-uppercase small wizard-success-muted mb-1">Reference Code</p>
          <div class="wizard-success-code" id="wizardSuccessCode">ENC-000000</div>
        </div>

        <div class="wizard-success-summary card border-0 shadow-sm text-start mb-4">
          <div class="card-body">
            <div class="wizard-success-summary-row">
              <span class="label">Room</span>
              <span class="value" id="wizardSuccessRoom">—</span>
            </div>
            <div class="wizard-success-summary-row">
              <span class="label">Date</span>
              <span class="value" id="wizardSuccessDate">—</span>
            </div>
            <div class="wizard-success-summary-row">
              <span class="label">Time</span>
              <span class="value" id="wizardSuccessTime">—</span>
            </div>
            <div class="wizard-success-summary-row">
              <span class="label">Purpose</span>
              <span class="value" id="wizardSuccessAgenda">—</span>
            </div>
          </div>
        </div>

        <div class="wizard-success-actions d-flex flex-column flex-md-row justify-content-center gap-2 mb-4">
          <button type="button" class="btn btn-light wizard-success-outline" id="wizardSuccessViewRequests">
            View my requests
          </button>
          <button type="button" class="btn btn-light wizard-success-outline" id="wizardSuccessAddCalendar">
            Add to calendar
          </button>
        </div>

        <hr class="wizard-success-divider my-4">

        <p class="wizard-success-muted mb-3">What would you like to do next?</p>
        <div class="wizard-success-next d-flex flex-column flex-md-row gap-3 justify-content-center">
          <button type="button" class="btn btn-light px-4 wizard-success-light-btn" id="wizardSuccessBookAnother">
            Make another booking
          </button>
          <button type="button" class="btn btn-room-submit px-4" id="wizardSuccessDashboard">
            Go to Dashboard
          </button>
        </div>
      </div>
    </section>