import { debounce, showError, showWarning, showSuccess, formatDateLabel, formatTimeLabel } from './utils';
import { loadFacilities as fetchFacilities, checkAvailability, submitBooking } from './facilities';

export const initBookingWizard = () => {
  const methodSection     = document.getElementById('wizardMethodSection');
  const landingShell      = document.getElementById('wizardLandingShell');
  const landingSection    = landingShell?.closest('.wizard-shell');
  const manualStage       = document.getElementById('wizardManualStage');
  const wizardAppNavWrap  = document.getElementById('wizardAppNav');
  const flowPreferenceSection = document.getElementById('wizardFlowPreferenceSection');
  const flowPreferenceBackWrap = document.getElementById('wizardFlowBackWrap');
  const flowPreferenceBackBtn = document.getElementById('wizardFlowPreferenceBack');
  const methodBackBtn    = document.getElementById('wizardMethodBack');
  const roomsGrid         = document.getElementById('wizardRoomsGrid');
  const loadFacilities = (filters = {}) => fetchFacilities(roomsGrid, filters);
  let facilitiesLoaded = false;
  const nextDateBtn       = document.querySelector('.wizard-next-date');
  const bookingsSidebar   = document.getElementById('wizardBookingsSidebar');
  const stageSidebarWrap  = document.getElementById('wizardStageSidebar');
  const stageRow          = document.getElementById('wizardStageRow');
  const backToDashboardBtn = document.getElementById('wizardBackToDashboard');
  const myBookingsToggle  = document.getElementById('myBookingsToggle');
  const roomsPanel        = document.getElementById('wizardRoomsPanel');
  const datePanel         = document.getElementById('wizardDatePanel');
  const detailsPanel      = document.getElementById('wizardDetailsPanel');
  const roomsActions      = document.getElementById('wizardRoomsActions');
  const backToRoomsBtn    = document.getElementById('wizardBackToRooms');
  const backToMethodsBtn  = document.getElementById('wizardBackToMethods');
  const nextDetailsBtn    = document.getElementById('wizardNextDetails');
  const backToDateBtn     = document.getElementById('wizardBackToDate');
  const nextReviewBtn     = document.getElementById('wizardNextReview');
  const reviewPanel       = document.getElementById('wizardReviewPanel');
  const backToDetailsBtn  = document.getElementById('wizardBackToDetails');
  const submitRequestBtn  = document.getElementById('wizardSubmitRequest');
  const successPanel      = document.getElementById('wizardSuccessPanel');
  const successUserLabel  = document.getElementById('wizardSuccessUser');
  const successCodeLabel  = document.getElementById('wizardSuccessCode');
  const successRoomLabel  = document.getElementById('wizardSuccessRoom');
  const successDateLabel  = document.getElementById('wizardSuccessDate');
  const successTimeLabel  = document.getElementById('wizardSuccessTime');
  const successAgendaLabel = document.getElementById('wizardSuccessAgenda');
  const successBookAnother = document.getElementById('wizardSuccessBookAnother');
  const successDashboardBtn = document.getElementById('wizardSuccessDashboard');
  const successViewRequestsBtn = document.getElementById('wizardSuccessViewRequests');
  const successAddCalendarBtn = document.getElementById('wizardSuccessAddCalendar');
  const bookingDateInput  = document.getElementById('bookingDate');
  const bookingStartTime  = document.getElementById('bookingStartTime');
  const bookingEndTime    = document.getElementById('bookingEndTime');
  const durationLabel     = document.getElementById('wizardDurationLabel');
  const selectedRoomLabel = document.getElementById('wizardSelectedRoomName');
  const stepperItems      = document.querySelectorAll('.enc-stepper-item');
  const flowPreferenceCards = document.querySelectorAll('[data-flow-preference]');
  const abortButtons        = document.querySelectorAll('[data-action="wizard-abort-booking"]');
  const calendarGrid      = document.getElementById('wizardCalendarGrid');
  const calendarMonthLbl  = document.getElementById('wizardCalendarMonth');
  const calendarPrevBtn   = document.getElementById('wizardCalendarPrev');
  const calendarNextBtn   = document.getElementById('wizardCalendarNext');
  const calendarSelected  = document.getElementById('wizardCalendarSelectedDate');
  const attendeesValue    = document.getElementById('wizardAttendeesValue');
  const attendeesInput    = document.getElementById('wizardAttendeesInput');
  const attendeesDecrease = document.getElementById('wizardAttendeesDecrease');
  const attendeesIncrease = document.getElementById('wizardAttendeesIncrease');
  const agendaInput       = document.getElementById('wizardAgendaInput');
  const supportToggle     = document.getElementById('wizardSupportToggle');
  const supportFields     = document.getElementById('wizardSupportFields');
  const supportCountValue = document.getElementById('wizardSupportCountValue');
  const supportCountInput = document.getElementById('wizardSupportCountInput');
  const supportDecrease   = document.getElementById('wizardSupportCountDecrease');
  const supportIncrease   = document.getElementById('wizardSupportCountIncrease');
  const supportEquipmentInputs = document.querySelectorAll('input[name="supportEquipment[]"]');
  const supportNotesInput = document.getElementById('wizardSupportNotes');
  const reviewRoom        = document.getElementById('wizardReviewRoom');
  const reviewDate        = document.getElementById('wizardReviewDate');
  const reviewTime        = document.getElementById('wizardReviewTime');
  const reviewDuration    = document.getElementById('wizardReviewDuration');
  const reviewAttendees   = document.getElementById('wizardReviewAttendees');
  const reviewAgenda      = document.getElementById('wizardReviewAgenda');
  const reviewSupportStatus  = document.getElementById('wizardReviewSupportStatus');
  const reviewSupportSummary = document.getElementById('wizardReviewSupportSummary');
  const roomSearch        = document.getElementById('roomSearch');
  const roomFloor         = document.getElementById('roomFloor');
  const roomSize          = document.getElementById('roomSize');

  const defaultStepPositions = {
    rooms: 1,
    date: 2,
    details: 3,
    review: 4,
    success: 5,
  };

  const wizardState = {
    roomId: null,
    roomName: '',
    roomCapacity: 0,
    date: bookingDateInput?.value || '',
    startTime: bookingStartTime?.value || '',
    endTime: bookingEndTime?.value || '',
    durationText: '',
    attendees: Number(attendeesInput?.value) || 0,
    agenda: '',
    support: {
      enabled: Boolean(supportToggle?.checked),
      count: Number(supportCountInput?.value) || 1,
      equipment: Array.from(supportEquipmentInputs || []).filter(input => input.checked).map(input => input.value),
      notes: supportNotesInput?.value || '',
    },
    userName: window.currentUser?.firstName || successUserLabel?.textContent?.trim() || 'User',
    referenceCode: '',
    flowPreference: 'room-first',
    stepPositions: { ...defaultStepPositions },
  };

  const getStepPosition = (key) => wizardState.stepPositions?.[key] || defaultStepPositions[key] || 1;

  const applyStepPositions = () => {
    stepperItems.forEach(item => {
      const key = item.dataset.stepKey;
      if (!key) return;
      const order = getStepPosition(key);
      // Use flex order so items visually rearrange when preference changes
      item.style.order = order;
      const circle = item.querySelector('.enc-stepper-circle');
      if (circle) {
        circle.dataset.stepNumber = order;
      }
    });
  };

  const updateFlowCopy = () => {
    if (wizardState.flowPreference === 'date-first') {
      if (backToMethodsBtn) backToMethodsBtn.textContent = 'Back to Date & Time';
      if (nextDateBtn) nextDateBtn.textContent = 'Next: Meeting Details';
      if (backToRoomsBtn) backToRoomsBtn.textContent = 'Back to flow choices';
      if (nextDetailsBtn) nextDetailsBtn.textContent = 'Next: Choose a Room';
      if (backToDateBtn) backToDateBtn.textContent = 'Back to Room Selection';
    } else {
      if (backToMethodsBtn) backToMethodsBtn.textContent = 'Change start preference';
      if (nextDateBtn) nextDateBtn.textContent = 'Next: Select Date & Time';
      if (backToRoomsBtn) backToRoomsBtn.textContent = 'Go Back to Select Room';
      if (nextDetailsBtn) nextDetailsBtn.textContent = 'Next: Add Details';
      if (backToDateBtn) backToDateBtn.textContent = 'Back to Date & Time';
    }
  };

  const setFlowPreference = (preference = 'room-first') => {
    const normalized = preference === 'date-first' ? 'date-first' : 'room-first';
    wizardState.flowPreference = normalized;
    wizardState.stepPositions = normalized === 'date-first'
      ? { date: 1, rooms: 2, details: 3, review: 4, success: 5 }
      : { ...defaultStepPositions };
    applyStepPositions();
    updateFlowCopy();
  };

  const showFlowPreference = () => {
    if (!flowPreferenceSection) {
      setFlowPreference('room-first');
      showManualStage();
      return;
    }
    methodSection?.classList.add('d-none');
    methodSection.hidden = true;
    flowPreferenceSection.classList.remove('d-none');
    flowPreferenceSection.hidden = false;
    flowPreferenceBackWrap?.classList.remove('d-none');
    landingShell?.classList.remove('d-none');
    landingSection?.classList.remove('d-none');
    wizardAppNavWrap?.classList.add('d-none');
    flowPreferenceSection.querySelector('.wizard-flow-title')?.focus?.();
  };

  const hideFlowPreference = () => {
    if (!flowPreferenceSection) return;
    flowPreferenceSection.classList.add('d-none');
    flowPreferenceSection.hidden = true;
    flowPreferenceBackWrap?.classList.add('d-none');
  };

  const updateReviewSummary = () => {
    if (!reviewPanel
      || !reviewRoom
      || !reviewDate
      || !reviewTime
      || !reviewDuration
      || !reviewAttendees
      || !reviewAgenda
      || !reviewSupportStatus
      || !reviewSupportSummary) {
      return;
    }
    reviewRoom.textContent = wizardState.roomName || 'Not selected';
    reviewDate.textContent = formatDateLabel(wizardState.date) || '—';
    const timeRange = wizardState.startTime && wizardState.endTime
      ? `${formatTimeLabel(wizardState.startTime)} – ${formatTimeLabel(wizardState.endTime)}`
      : '—';
    reviewTime.textContent = timeRange;
    reviewDuration.textContent = wizardState.durationText || '—';
    reviewAttendees.textContent = wizardState.attendees
      ? `${wizardState.attendees} people expected`
      : '—';
    reviewAgenda.textContent = wizardState.agenda || '—';

    reviewSupportSummary.replaceChildren();
    if (!wizardState.support.enabled) {
      reviewSupportStatus.textContent = 'Not requested';
      reviewSupportSummary.textContent = 'No additional support requested for this booking.';
      return;
    }

    reviewSupportStatus.textContent = 'Requested';
    const frag = document.createDocumentFragment();

    const manpower = document.createElement('p');
    manpower.className = 'mb-1 fw-semibold text-dark';
    manpower.textContent = `${wizardState.support.count} staff member${wizardState.support.count > 1 ? 's' : ''} requested`;
    frag.appendChild(manpower);

    if (wizardState.support.equipment.length > 0) {
      const equipmentTitle = document.createElement('p');
      equipmentTitle.className = 'small text-muted mb-1';
      equipmentTitle.textContent = 'Equipment needed:';
      frag.appendChild(equipmentTitle);

      const list = document.createElement('ul');
      // Get equipment names from checked inputs for display
      const equipmentNames = Array
        .from(supportEquipmentInputs)
        .filter(input => input.checked)
        .map(input => input.dataset.equipmentName || input.value);
      
      equipmentNames.forEach(name => {
        const li = document.createElement('li');
        li.textContent = name;
        list.appendChild(li);
      });
      frag.appendChild(list);
    }

    if (wizardState.support.notes.trim()) {
      const notes = document.createElement('p');
      notes.className = 'small mb-0';
      notes.textContent = `Notes: ${wizardState.support.notes.trim()}`;
      frag.appendChild(notes);
    }

    if (!wizardState.support.equipment.length && !wizardState.support.notes.trim()) {
      const helper = document.createElement('p');
      helper.className = 'small text-muted mb-0';
      helper.textContent = 'You can add specific instructions in the notes above.';
      frag.appendChild(helper);
    }

    reviewSupportSummary.appendChild(frag);
  };

  const populateSuccessPanel = () => {
    if (!successPanel) return;
    if (successUserLabel) successUserLabel.textContent = wizardState.userName;
    if (successRoomLabel) successRoomLabel.textContent = wizardState.roomName || '—';
    if (successDateLabel) successDateLabel.textContent = formatDateLabel(wizardState.date) || '—';
    const timeRange = wizardState.startTime && wizardState.endTime
      ? `${formatTimeLabel(wizardState.startTime)} – ${formatTimeLabel(wizardState.endTime)}`
      : '—';
    if (successTimeLabel) successTimeLabel.textContent = timeRange;
    if (successAgendaLabel) successAgendaLabel.textContent = wizardState.agenda || '—';
    if (successCodeLabel) successCodeLabel.textContent = wizardState.referenceCode || 'ENC-000000';
  };

  const showSuccessPanel = () => {
    if (!successPanel) return;
    manualStage?.classList.add('d-none');
    successPanel.classList.remove('d-none');
    successPanel.hidden = false;
    populateSuccessPanel();
    updateStepperState(getStepPosition('success'));
    const top = successPanel.offsetTop || 0;
    window.scrollTo({ top: Math.max(top - 40, 0), behavior: 'smooth' });
  };
  const showMethodSelection = () => {
    if (!methodSection) return;

    methodSection.classList.remove('d-none');
    methodSection.hidden = false;
    hideFlowPreference();
    landingShell?.classList.remove('d-none');
    landingSection?.classList.remove('d-none');

    methodSection.querySelector('.wizard-method-title')?.focus?.();
    wizardAppNavWrap?.classList.add('d-none');
  };
  setFlowPreference('room-first');
  showMethodSelection();

  const updateStepperState = (currentStep = getStepPosition('rooms')) => {
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
  updateStepperState(getStepPosition('rooms'));

  const showRoomsStep = () => {
    roomsPanel?.classList.remove('d-none');
    roomsActions?.classList.remove('d-none');
    datePanel?.classList.add('d-none');
    detailsPanel?.classList.add('d-none');
    updateStepperState(getStepPosition('rooms'));
  };

  const showDateStep = () => {
    datePanel?.classList.remove('d-none');
    roomsPanel?.classList.add('d-none');
    roomsActions?.classList.add('d-none');
    detailsPanel?.classList.add('d-none');
    updateStepperState(getStepPosition('date'));
  };

  const showManualStage = () => {
    if (!manualStage) return;

    if (methodSection) {
      methodSection.classList.add('d-none');
      methodSection.hidden = true;
    }
    hideFlowPreference();

    landingShell?.classList.add('d-none');
    landingSection?.classList.add('d-none');
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

    const focusTarget = wizardState.flowPreference === 'date-first'
      ? datePanel?.querySelector('.wizard-rooms-title')
      : manualStage.querySelector('.wizard-rooms-title');
    focusTarget?.focus?.();
    if (wizardState.flowPreference === 'date-first') {
      showDateStep();
    } else {
      showRoomsStep();
    }
    
    // Load facilities from API
    if (!facilitiesLoaded) {
      loadFacilities();
      facilitiesLoaded = true;
    }
  };

  // Method selection → branch to Smart or Manual
  document.querySelectorAll('.wizard-method-card').forEach(card => {
    card.addEventListener('click', () => {
      const method = card.dataset.method;
      if (card.classList.contains('is-disabled') || card.disabled) {
        return;
      }

      if (method === 'manual') {
        showFlowPreference();
        return;
      }

      if (method === 'smart') {
        // TODO: show Smart Book Finder flow (question-based)
        // eslint-disable-next-line no-console
        console.debug('Smart booking flow not wired yet.');
      }
    });
  });

  flowPreferenceCards.forEach(card => {
    card.addEventListener('click', () => {
      const preference = card.dataset.flowPreference || 'room-first';
      setFlowPreference(preference);
      hideFlowPreference();
      showManualStage();
    });
  });

  if (flowPreferenceBackBtn) {
    flowPreferenceBackBtn.addEventListener('click', () => {
      hideFlowPreference();
      showMethodSelection();
    });
  }

  // Sidebar toggle (My Bookings)
  if (myBookingsToggle && bookingsSidebar && stageSidebarWrap && stageRow) {
    myBookingsToggle.addEventListener('click', () => {
      const expanded = myBookingsToggle.getAttribute('aria-expanded') === 'true';
      const nextState = !expanded;
      myBookingsToggle.setAttribute('aria-expanded', String(nextState));
      myBookingsToggle.setAttribute('aria-pressed', String(nextState));
      myBookingsToggle.classList.toggle('btn-bookings-active', nextState);
      stageSidebarWrap.classList.toggle('d-none', !nextState);
      stageRow.classList.toggle('has-sidebar', nextState);
    });
  }

  const promptBackToDashboard = () => {
    Swal.fire({
      title: 'Return to dashboard?',
      text: 'We will save your booking progress as a draft so you can pick up where you left off.',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Save draft & go back',
      cancelButtonText: 'Stay here',
      customClass: {
        popup: 'enc-swal-popup',
        confirmButton: 'btn btn-primary',
        cancelButton: 'btn btn-link text-dark',
      },
      buttonsStyling: false,
    }).then(result => {
      if (result.isConfirmed) {
        // TODO: Hook draft persistence here.
        window.location.href = '/user/dashboard';
      }
    });
  };

  if (backToDashboardBtn) {
    backToDashboardBtn.addEventListener('click', promptBackToDashboard);
  }

  const navigateToPreviousPage = () => {
    const referrer = document.referrer;
    if (referrer && referrer !== window.location.href) {
      window.location.href = referrer;
      return;
    }
    if (window.history.length > 1) {
      window.history.back();
      return;
    }
    window.location.href = '/user/dashboard';
  };

  const promptAbortBooking = () => {
    if (window.Swal?.fire) {
      Swal.fire({
        title: 'Abort this booking?',
        text: 'All progress will be discarded and you will return to your dashboard.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Abort booking',
        cancelButtonText: 'Stay here',
        customClass: {
          popup: 'enc-swal-popup',
          confirmButton: 'btn btn-outline-danger',
          cancelButton: 'btn btn-light',
        },
        buttonsStyling: false,
      }).then(result => {
        if (result.isConfirmed) {
          window.location.href = '/user/dashboard';
        }
      });
      return;
    }
    window.location.href = '/user/dashboard';
  };

  if (backToMethodsBtn) {
    backToMethodsBtn.addEventListener('click', () => {
      if (wizardState.flowPreference === 'date-first') {
        showDateStep();
        return;
      }
      manualStage?.classList.add('d-none');
      if (manualStage) manualStage.hidden = true;
      showFlowPreference();
    });
  }

  abortButtons.forEach(btn => {
    btn.addEventListener('click', promptAbortBooking);
  });

  if (methodBackBtn) {
    methodBackBtn.addEventListener('click', navigateToPreviousPage);
  }

  // Step 1 (manual): enable "Next: Select Date & Time" after selecting a room
  if (roomsGrid && nextDateBtn) {
    let selectedRoomName = '';

    roomsGrid.addEventListener('click', (e) => {
      const clearFiltersBtn = e.target.closest('[data-action=\"wizard-clear-filters\"]');
      if (clearFiltersBtn) {
        if (roomSearch) roomSearch.value = '';
        if (roomFloor) roomFloor.value = '';
        if (roomSize) roomSize.value = '';
        loadFacilities();
        return;
      }

      const retryBtn = e.target.closest('[data-action=\"wizard-retry-facilities\"]');
      if (retryBtn) {
        loadFacilities(buildFacilityFilters());
        return;
      }

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
        wizardState.roomName = '';
        return;
      }

      card?.classList.add('is-selected');
      btn.textContent = 'Selected';
      nextDateBtn.disabled = false;
      selectedRoomName = btn.dataset.roomName || '';
      wizardState.roomId = parseInt(btn.dataset.roomId) || null;
      wizardState.roomName = selectedRoomName;
      wizardState.roomCapacity = parseInt(btn.dataset.roomCapacity) || 0;
      
      if (selectedRoomLabel && selectedRoomName) {
        selectedRoomLabel.textContent = selectedRoomName;
      }

      // Update attendees max based on room capacity
      if (wizardState.roomCapacity > 0 && attendeesInput) {
        attendeesInput.dataset.max = wizardState.roomCapacity;
        const currentValue = parseInt(attendeesInput.value) || 1;
        if (currentValue > wizardState.roomCapacity) {
          attendeesInput.value = wizardState.roomCapacity;
          if (attendeesValue) attendeesValue.textContent = wizardState.roomCapacity;
        }
        
        // Update max capacity badge
        const maxBadge = document.getElementById('wizardAttendeesMaxBadge');
        if (maxBadge) {
          maxBadge.textContent = `Max in-room: ${wizardState.roomCapacity}`;
        }
        
        // Update help text
        const helpText = document.getElementById('wizardAttendeesHelp');
        if (helpText) {
          helpText.textContent = `Need more than ${wizardState.roomCapacity}? Leave a note so we can suggest larger venues or hybrid setups.`;
        }
        
        // Update button states to reflect new capacity
        if (attendeesIncrease) {
          attendeesIncrease.disabled = currentValue >= wizardState.roomCapacity;
        }
        if (attendeesDecrease) {
          attendeesDecrease.disabled = currentValue <= 1;
        }
      }
    });

    if (nextDateBtn) {
      nextDateBtn.addEventListener('click', () => {
        if (nextDateBtn.disabled) return;
        if (wizardState.flowPreference === 'date-first') {
          roomsPanel?.classList.add('d-none');
          roomsActions?.classList.add('d-none');
          detailsPanel?.classList.remove('d-none');
          updateStepperState(getStepPosition('details'));
          return;
        }
        datePanel?.classList.remove('d-none');
        roomsPanel?.classList.add('d-none');
        roomsActions?.classList.add('d-none');
        updateStepperState(getStepPosition('date'));
      });
    }

    if (nextDetailsBtn) {
      nextDetailsBtn.addEventListener('click', () => {
        if (nextDetailsBtn.disabled) return;
        if (wizardState.flowPreference === 'date-first') {
          showRoomsStep();
          return;
        }
        datePanel?.classList.add('d-none');
        detailsPanel?.classList.remove('d-none');
        updateStepperState(getStepPosition('details'));
        
        // Update capacity badge and help text when entering details panel
        if (wizardState.roomCapacity > 0) {
          const maxBadge = document.getElementById('wizardAttendeesMaxBadge');
          if (maxBadge) {
            maxBadge.textContent = `Max in-room: ${wizardState.roomCapacity}`;
          }
          
          const helpText = document.getElementById('wizardAttendeesHelp');
          if (helpText) {
            helpText.textContent = `Need more than ${wizardState.roomCapacity}? Leave a note so we can suggest larger venues or hybrid setups.`;
          }
          
          // Reset attendees count to a reasonable default (half capacity or minimum 4)
          if (attendeesInput && attendeesValue) {
            const defaultAttendees = Math.max(4, Math.floor(wizardState.roomCapacity / 2));
            const clampedDefault = Math.min(defaultAttendees, wizardState.roomCapacity);
            attendeesInput.value = clampedDefault;
            attendeesValue.textContent = clampedDefault;
            wizardState.attendees = clampedDefault;
            
            // Update button states based on new capacity
            if (attendeesDecrease) {
              attendeesDecrease.disabled = clampedDefault <= 1;
            }
            if (attendeesIncrease) {
              attendeesIncrease.disabled = clampedDefault >= wizardState.roomCapacity;
            }
          }
        }
      });
    }

    if (backToRoomsBtn) {
      backToRoomsBtn.addEventListener('click', () => {
        if (wizardState.flowPreference === 'date-first') {
          manualStage?.classList.add('d-none');
          if (manualStage) manualStage.hidden = true;
          showFlowPreference();
          return;
        }
        showRoomsStep();
        if (nextDetailsBtn) nextDetailsBtn.disabled = true;
      });
    }

    const calculateDuration = async () => {
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
      
      if (!dateValue) {
        setStatus(`${label} — select a date to continue`, false);
        return;
      }
      
      // Check availability with API
      if (wizardState.roomId && dateValue && startValue && endValue) {
        const isAvailable = await checkAvailability(
          wizardState.roomId,
          dateValue,
          startValue,
          endValue
        );
        
        if (!isAvailable) {
          setStatus('This time slot is not available', false);
          return;
        }
      }
      
      wizardState.durationText = label;
      wizardState.date = dateValue || wizardState.date;
      wizardState.startTime = bookingStartTime?.value || wizardState.startTime;
      wizardState.endTime = bookingEndTime?.value || wizardState.endTime;
      setStatus(label, true);
    };

    if (calendarGrid && calendarMonthLbl) {
      const today = new Date();
      const toDisplayDate = (date) => date.toLocaleDateString('en-US', {
        weekday: 'short',
        month: 'long',
        day: 'numeric',
        year: 'numeric',
      });
      const pad = (value) => String(value).padStart(2, '0');
      const toInputValue = (date) => `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}`;
      const parseExistingDate = (value) => {
        if (!value) return null;
        const [year, month, day] = value.split('-').map(Number);
        if ([year, month, day].some(num => Number.isNaN(num))) return null;
        return new Date(year, month - 1, day);
      };

      const calendarState = {
        viewMonth: today.getMonth(),
        viewYear: today.getFullYear(),
        selectedDate: parseExistingDate(bookingDateInput?.value),
      };

      if (calendarState.selectedDate) {
        calendarState.viewMonth = calendarState.selectedDate.getMonth();
        calendarState.viewYear = calendarState.selectedDate.getFullYear();
      }

      const renderCalendar = () => {
        const { viewMonth, viewYear, selectedDate } = calendarState;
        const firstDay = new Date(viewYear, viewMonth, 1);
        const daysInMonth = new Date(viewYear, viewMonth + 1, 0).getDate();
        const startOffset = firstDay.getDay();
        const totalCells = Math.ceil((startOffset + daysInMonth) / 7) * 7;

        calendarMonthLbl.textContent = firstDay.toLocaleDateString('en-US', {
          month: 'long',
          year: 'numeric',
        });

        calendarGrid.innerHTML = '';
        const todayReference = new Date();

        for (let cellIndex = 0; cellIndex < totalCells; cellIndex += 1) {
          const dayNumber = cellIndex - startOffset + 1;
          const isCurrentMonth = dayNumber >= 1 && dayNumber <= daysInMonth;
          const cellDate = new Date(viewYear, viewMonth, dayNumber);
          const dayButton = document.createElement('button');
          dayButton.type = 'button';
          dayButton.classList.add('wizard-calendar-day');
          dayButton.setAttribute('role', 'gridcell');
          dayButton.textContent = cellDate.getDate();

          if (!isCurrentMonth) {
            dayButton.classList.add('is-muted');
            dayButton.disabled = true;
            dayButton.setAttribute('aria-disabled', 'true');
          } else {
            if (todayReference.toDateString() === cellDate.toDateString()) {
              dayButton.classList.add('is-today');
            }

            const isSelected = selectedDate && selectedDate.toDateString() === cellDate.toDateString();
            if (isSelected) {
              dayButton.classList.add('is-selected');
            }

            dayButton.setAttribute('aria-pressed', isSelected ? 'true' : 'false');
            dayButton.setAttribute(
              'aria-label',
              cellDate.toLocaleDateString('en-US', {
                weekday: 'long',
                month: 'long',
                day: 'numeric',
                year: 'numeric',
              }),
            );

            dayButton.addEventListener('click', () => {
              calendarState.selectedDate = cellDate;
              if (bookingDateInput) {
                bookingDateInput.value = toInputValue(cellDate);
                bookingDateInput.dispatchEvent(new Event('change', { bubbles: true }));
              }
              wizardState.date = toInputValue(cellDate);
              renderCalendar();
            });
          }

          calendarGrid.appendChild(dayButton);
        }

        if (calendarSelected) {
          calendarSelected.textContent = calendarState.selectedDate
            ? toDisplayDate(calendarState.selectedDate)
            : 'None yet';
        }
      };

      calendarPrevBtn?.addEventListener('click', () => {
        calendarState.viewMonth -= 1;
        if (calendarState.viewMonth < 0) {
          calendarState.viewMonth = 11;
          calendarState.viewYear -= 1;
        }
        renderCalendar();
      });

      calendarNextBtn?.addEventListener('click', () => {
        calendarState.viewMonth += 1;
        if (calendarState.viewMonth > 11) {
          calendarState.viewMonth = 0;
          calendarState.viewYear += 1;
        }
        renderCalendar();
      });

      renderCalendar();
    }

    [bookingDateInput, bookingStartTime, bookingEndTime].forEach(input => {
      input?.addEventListener('change', () => {
        if (input === bookingDateInput && nextDetailsBtn && !bookingStartTime?.value && !bookingEndTime?.value) {
          nextDetailsBtn.disabled = true;
        }
        if (input === bookingDateInput) {
          wizardState.date = bookingDateInput.value;
        }
        if (input === bookingStartTime) {
          wizardState.startTime = bookingStartTime.value;
        }
        if (input === bookingEndTime) {
          wizardState.endTime = bookingEndTime.value;
        }
        calculateDuration();
      });
    });

    calculateDuration();
  }

  if (detailsPanel) {
    const updateDetailsNextState = () => {
      const attendeeCount = Number(attendeesInput?.value || 0);
      const hasAgenda = Boolean(agendaInput?.value.trim());
      const supportEnabled = supportToggle?.checked;
      const supportCount = Number(supportCountInput?.value || 0);

      const isValid = attendeeCount >= Number(attendeesInput?.dataset.min || 1)
        && attendeeCount <= Number(attendeesInput?.dataset.max || attendeeCount)
        && hasAgenda
        && (!supportEnabled || (supportCount >= Number(supportCountInput?.dataset.min || 1)
          && supportCount <= Number(supportCountInput?.dataset.max || supportCount)));

      if (nextReviewBtn) {
        nextReviewBtn.disabled = !isValid;
      }
    };

    const attachCounter = (decreaseBtn, increaseBtn, valueEl, inputEl, onChange) => {
      if (!decreaseBtn || !increaseBtn || !valueEl || !inputEl) return;

      const updateButtonStates = (currentValue) => {
        // Read min/max dynamically from data attributes to support capacity changes
        const min = Number(inputEl.dataset.min || 1);
        const max = Number(inputEl.dataset.max || 50);
        // Disable decrease button if at minimum
        decreaseBtn.disabled = currentValue <= min;
        // Disable increase button if at maximum
        increaseBtn.disabled = currentValue >= max;
      };

      const setValue = (newValue) => {
        // Read min/max dynamically to support capacity changes
        const min = Number(inputEl.dataset.min || 1);
        const max = Number(inputEl.dataset.max || 50);
        const clamped = Math.min(Math.max(newValue, min), max);
        inputEl.value = clamped;
        valueEl.textContent = clamped;
        updateButtonStates(clamped);
        if (typeof onChange === 'function') onChange(clamped);
        updateDetailsNextState();
      };

      decreaseBtn.addEventListener('click', () => {
        const min = Number(inputEl.dataset.min || 1);
        const currentValue = Number(inputEl.value || min);
        if (currentValue > min) {
          setValue(currentValue - 1);
        }
      });

      increaseBtn.addEventListener('click', () => {
        const max = Number(inputEl.dataset.max || 50);
        const currentValue = Number(inputEl.value || min);
        if (currentValue < max) {
          setValue(currentValue + 1);
        }
      });

      setValue(Number(inputEl.value || min));
    };

    attachCounter(attendeesDecrease, attendeesIncrease, attendeesValue, attendeesInput, (value) => {
      wizardState.attendees = value;
    });
    attachCounter(supportDecrease, supportIncrease, supportCountValue, supportCountInput, (value) => {
      wizardState.support.count = value;
    });

    agendaInput?.addEventListener('input', () => {
      wizardState.agenda = agendaInput.value.trim();
      updateDetailsNextState();
    });

    if (supportToggle && supportFields) {
      supportToggle.addEventListener('change', () => {
        const enabled = supportToggle.checked;
        supportFields.classList.toggle('d-none', !enabled);
        supportFields.setAttribute('aria-hidden', enabled ? 'false' : 'true');
        wizardState.support.enabled = enabled;
        updateDetailsNextState();
      });
    }

    if (supportEquipmentInputs.length) {
      const refreshEquipment = () => {
        // Store equipment IDs for database submission
        wizardState.support.equipment = Array
          .from(supportEquipmentInputs)
          .filter(input => input.checked)
          .map(input => input.value);
        updateDetailsNextState();
      };

      supportEquipmentInputs.forEach(input => {
        input.addEventListener('change', refreshEquipment);
      });
      
      // Initial equipment state
      refreshEquipment();
    }

    supportNotesInput?.addEventListener('input', () => {
      wizardState.support.notes = supportNotesInput.value;
      updateDetailsNextState();
    });

    backToDateBtn?.addEventListener('click', () => {
      detailsPanel.classList.add('d-none');
      if (wizardState.flowPreference === 'date-first') {
        roomsPanel?.classList.remove('d-none');
        roomsActions?.classList.remove('d-none');
        datePanel?.classList.add('d-none');
        updateStepperState(getStepPosition('rooms'));
      } else {
        datePanel?.classList.remove('d-none');
        roomsPanel?.classList.add('d-none');
        roomsActions?.classList.add('d-none');
        updateStepperState(getStepPosition('date'));
      }
      if (nextReviewBtn) nextReviewBtn.disabled = true;
    });

    nextReviewBtn?.addEventListener('click', () => {
      if (nextReviewBtn.disabled) return;
      detailsPanel.classList.add('d-none');
      reviewPanel?.classList.remove('d-none');
      updateStepperState(getStepPosition('review'));
      updateReviewSummary();
    });

    updateDetailsNextState();
  }

  backToDetailsBtn?.addEventListener('click', () => {
    reviewPanel?.classList.add('d-none');
    detailsPanel?.classList.remove('d-none');
    updateStepperState(getStepPosition('details'));
  });

  submitRequestBtn?.addEventListener('click', () => {
    const proceedSubmission = async () => {
      if (submitRequestBtn) {
        submitRequestBtn.disabled = true;
        submitRequestBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';
      }

      const success = await submitBooking(wizardState);

      if (success) {
        showSuccessPanel();
      } else {
        if (submitRequestBtn) {
          submitRequestBtn.disabled = false;
          submitRequestBtn.innerHTML = 'Submit booking request';
        }
      }
    };

    if (window.Swal?.fire) {
      Swal.fire({
        title: 'Submit booking request?',
        text: "We'll notify the facilities team and keep you updated.",
        icon: 'question',
        confirmButtonText: 'Yes, submit',
        cancelButtonText: 'Review again',
        showCancelButton: true,
        focusCancel: true,
        customClass: {
          popup: 'wizard-swal',
          confirmButton: 'wizard-swal-confirm',
          cancelButton: 'wizard-swal-cancel',
        },
        confirmButtonColor: '#00C950',
        cancelButtonColor: '#001840',
      }).then(result => {
        if (result.isConfirmed) {
          proceedSubmission();
        }
      });
      return;
    }

    if (window.confirm('Submit your booking request?')) {
      proceedSubmission();
    }
  });

  successBookAnother?.addEventListener('click', () => {
    window.location.reload();
  });

  successDashboardBtn?.addEventListener('click', () => {
    window.location.href = '/user/dashboard';
  });

  successViewRequestsBtn?.addEventListener('click', () => {
    window.location.href = '/user/booking';
  });

  successAddCalendarBtn?.addEventListener('click', () => {
    // Generate ICS calendar file
    const booking = {
      title: `Meeting: ${wizardState.agenda || 'Booking'}`,
      location: wizardState.roomName,
      description: `Room: ${wizardState.roomName}\\nAttendees: ${wizardState.attendees}\\nReference: ${wizardState.referenceCode}`,
      start: `${wizardState.date}T${wizardState.startTime}:00`,
      end: `${wizardState.date}T${wizardState.endTime}:00`,
    };
    
    showSuccess('Calendar export feature coming soon!');
  });
  
  const buildFacilityFilters = () => {
    const normalizedFloor = (roomFloor?.value || '')
      .toLowerCase()
      .replace(' floor', '')
      .trim();
      
    return {
      search: roomSearch?.value?.trim() || '',
      floor: normalizedFloor,
      size: roomSize?.value || '',
    };
  };
  
  if (roomSearch) {
    roomSearch.addEventListener('input', debounce((e) => {
      loadFacilities(buildFacilityFilters());
    }, 500));
  }
  
  if (roomFloor) {
    roomFloor.addEventListener('change', (e) => {
      roomFloor.value = e.target.value;
      loadFacilities(buildFacilityFilters());
    });
  }
  
  if (roomSize) {
    roomSize.addEventListener('change', (e) => {
      roomSize.value = e.target.value;
      loadFacilities(buildFacilityFilters());
    });
  }
};
