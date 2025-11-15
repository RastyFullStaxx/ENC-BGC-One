    {{-- STEP 1 (Manual path) — Browse All Rooms --}}
    <section
      id="wizardManualStage"
      class="wizard-stage container-fluid px-3 px-lg-5 d-none"
      hidden
    >
      <div class="wizard-stage-row" id="wizardStageRow">
        <div class="wizard-stage-main" id="wizardStageMain">
          @include('user.booking.wizard.steps.rooms-panel')
          @include('user.booking.wizard.steps.date-panel', ['timeSlots' => $timeSlots])
          @include('user.booking.wizard.steps.details-panel', ['wizardSupportEquipment' => $wizardSupportEquipment])
          @include('user.booking.wizard.steps.review-panel')
        </div>

        {{-- Sidebar: Your Bookings (shown only when toggled) --}}
        <div class="wizard-stage-sidebar d-none" id="wizardStageSidebar">
          @include('user.booking.wizard.components.bookings-sidebar')
        </div>
      </div>
    </section>
