// resources/js/wizard.js

document.addEventListener('DOMContentLoaded', () => {
  const startButton       = document.getElementById('wizardStartButton');
  const greetingPanel     = document.querySelector('.wizard-greeting-panel');
  const methodSection     = document.getElementById('wizardMethodSection');
  const landingShell      = document.getElementById('wizardLandingShell');
  const manualStage       = document.getElementById('wizardManualStage');
  const wizardAppNavWrap  = document.getElementById('wizardAppNav');
  const roomsGrid         = document.getElementById('wizardRoomsGrid');
  const nextDateBtn       = document.querySelector('.wizard-next-date');
  const bookingsSidebar   = document.getElementById('wizardBookingsSidebar');
  const stageSidebarWrap  = document.getElementById('wizardStageSidebar');
  const stageRow          = document.getElementById('wizardStageRow');
  const myBookingsToggle  = document.getElementById('myBookingsToggle');

  const showMethodSelection = () => {
    if (!methodSection) return;

    if (greetingPanel) {
      greetingPanel.classList.add('d-none');
    }

    methodSection.classList.remove('d-none');
    methodSection.hidden = false;

    methodSection.querySelector('.wizard-method-title')?.focus?.();
    wizardAppNavWrap?.classList.add('d-none');
  };

  const showManualStage = () => {
    if (!manualStage) return;

    if (methodSection) {
      methodSection.classList.add('d-none');
      methodSection.hidden = true;
    }

    landingShell?.classList.add('d-none');
    manualStage.classList.remove('d-none');
    manualStage.hidden = false;
    wizardAppNavWrap?.classList.remove('d-none');
    stageSidebarWrap?.classList.add('d-none');
    stageRow?.classList.remove('has-sidebar');
    if (myBookingsToggle) {
      myBookingsToggle.setAttribute('aria-expanded', 'false');
      myBookingsToggle.setAttribute('aria-pressed', 'false');
      myBookingsToggle.classList.remove('btn-bookings-active');
    }

    manualStage.querySelector('.wizard-rooms-title')?.focus?.();
  };

  // Greeting → Method selection
  if (startButton) {
    startButton.addEventListener('click', () => {
      showMethodSelection();
      myBookingsToggle.classList.toggle('btn-bookings-active', nextState);
    });
  }

  // Method selection → branch to Smart or Manual
  document.querySelectorAll('.wizard-method-card').forEach(card => {
    card.addEventListener('click', () => {
      const method = card.dataset.method;

      if (method === 'manual') {
        showManualStage();
        return;
      }

      if (method === 'smart') {
        // TODO: show Smart Book Finder flow (question-based)
        // eslint-disable-next-line no-console
        console.debug('Smart booking flow not wired yet.');
      }
    });
  });

  // Sidebar toggle (My Bookings)
  if (myBookingsToggle && bookingsSidebar && stageSidebarWrap && stageRow) {
    myBookingsToggle.addEventListener('click', () => {
      const expanded = myBookingsToggle.getAttribute('aria-expanded') === 'true';
      const nextState = !expanded;
      myBookingsToggle.setAttribute('aria-expanded', String(nextState));
      myBookingsToggle.setAttribute('aria-pressed', String(nextState));
      stageSidebarWrap.classList.toggle('d-none', !nextState);
      stageRow.classList.toggle('has-sidebar', nextState);
    });
  }

  // Step 1 (manual): enable "Next: Select Date & Time" after selecting a room
  if (roomsGrid && nextDateBtn) {
    roomsGrid.addEventListener('click', (e) => {
      const btn = e.target.closest('.wizard-room-select');
      if (!btn) return;

      const card = btn.closest('.wizard-room-card');
      const isCurrentlySelected = card?.classList.contains('is-selected');

      document.querySelectorAll('.wizard-room-card').forEach(roomCard => {
        roomCard.classList.remove('is-selected');
        roomCard.querySelectorAll('.wizard-room-select').forEach(actionBtn => {
          if (actionBtn.dataset.defaultLabel) {
            actionBtn.textContent = actionBtn.dataset.defaultLabel;
          }
        });
      });

      if (isCurrentlySelected) {
        nextDateBtn.disabled = true;
        return;
      }

      card?.classList.add('is-selected');
      btn.textContent = 'Selected';
      nextDateBtn.disabled = false;

      // TODO: store selected room info for later steps.
      // eslint-disable-next-line no-console
      console.debug('Selected room:', btn.dataset.roomName);
    });
  }
});
