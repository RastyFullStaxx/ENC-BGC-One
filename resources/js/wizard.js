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
  const roomsPanel        = document.getElementById('wizardRoomsPanel');
  const datePanel         = document.getElementById('wizardDatePanel');
  const roomsActions      = document.getElementById('wizardRoomsActions');
  const backToRoomsBtn    = document.getElementById('wizardBackToRooms');
  const nextDetailsBtn    = document.getElementById('wizardNextDetails');
  const bookingDateInput  = document.getElementById('bookingDate');
  const bookingStartTime  = document.getElementById('bookingStartTime');
  const bookingEndTime    = document.getElementById('bookingEndTime');
  const durationLabel     = document.getElementById('wizardDurationLabel');
  const selectedRoomLabel = document.getElementById('wizardSelectedRoomName');
  const stepperItems      = document.querySelectorAll('.enc-stepper-item');

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

  const updateStepperState = (currentStep = 1) => {
    stepperItems.forEach((item, index) => {
      item.classList.remove('is-active', 'is-complete', 'is-upcoming');
      const circle = item.querySelector('.enc-stepper-circle');
      const stepNumber = Number(circle?.dataset.stepNumber || index + 1);

      if (stepNumber < currentStep) {
        item.classList.add('is-complete');
        if (circle) circle.textContent = '✓';
      } else if (stepNumber === currentStep) {
        item.classList.add('is-active');
        if (circle) circle.textContent = stepNumber;
      } else {
        item.classList.add('is-upcoming');
        if (circle) circle.textContent = stepNumber;
      }
    });
  };
  updateStepperState(1);

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
    updateStepperState(1);
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
    let selectedRoomName = '';

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
        selectedRoomName = '';
        if (selectedRoomLabel) selectedRoomLabel.textContent = 'None selected yet';
        return;
      }

      card?.classList.add('is-selected');
      btn.textContent = 'Selected';
      nextDateBtn.disabled = false;
      selectedRoomName = btn.dataset.roomName || '';
      if (selectedRoomLabel && selectedRoomName) {
        selectedRoomLabel.textContent = selectedRoomName;
      }

      // TODO: store selected room info for later steps.
      // eslint-disable-next-line no-console
      console.debug('Selected room:', btn.dataset.roomName);
    });

    if (nextDateBtn) {
      nextDateBtn.addEventListener('click', () => {
        if (nextDateBtn.disabled) return;
        roomsPanel?.classList.add('d-none');
        datePanel?.classList.remove('d-none');
        roomsActions?.classList.add('d-none');
        updateStepperState(2);
      });
    }

    if (backToRoomsBtn) {
      backToRoomsBtn.addEventListener('click', () => {
        datePanel?.classList.add('d-none');
        roomsPanel?.classList.remove('d-none');
        roomsActions?.classList.remove('d-none');
        updateStepperState(1);
        if (nextDetailsBtn) nextDetailsBtn.disabled = true;
      });
    }

    const calculateDuration = () => {
      const startValue = bookingStartTime?.value;
      const endValue = bookingEndTime?.value;
      const dateValue = bookingDateInput?.value;

      const setStatus = (text, valid) => {
        if (durationLabel) durationLabel.textContent = text;
        if (nextDetailsBtn) nextDetailsBtn.disabled = !valid;
      };

      if (!startValue || !endValue) {
        setStatus('Select start and end time', false);
        return;
      }

      const parseMinutes = (value) => {
        const [h, m] = value.split(':').map(Number);
        return h * 60 + m;
      };

      const diffMinutes = parseMinutes(endValue) - parseMinutes(startValue);
      if (diffMinutes <= 0) {
        setStatus('End time must be later than start time', false);
        return;
      }

      const hours = Math.floor(diffMinutes / 60);
      const minutes = diffMinutes % 60;
      const parts = [];
      if (hours > 0) parts.push(`${hours} hr${hours > 1 ? 's' : ''}`);
      if (minutes > 0) parts.push(`${minutes} min`);
      const label = parts.join(' ') || 'Less than 1 minute';
      setStatus(label, Boolean(dateValue));
    };

    [bookingDateInput, bookingStartTime, bookingEndTime].forEach(input => {
      input?.addEventListener('change', () => {
        if (input === bookingDateInput && nextDetailsBtn && !bookingStartTime?.value && !bookingEndTime?.value) {
          nextDetailsBtn.disabled = true;
        }
        calculateDuration();
      });
    });

    calculateDuration();
  }
});
