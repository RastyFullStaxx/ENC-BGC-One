// resources/js/wizard.js

// ============================================================================
// DOM ELEMENT REFERENCES (Module Scope)
// ============================================================================

let roomsGrid = null;

// ============================================================================
// API HELPER FUNCTIONS
// ============================================================================

const debounce = (func, wait) => {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
};

const showError = (message) => {
  if (window.Swal?.fire) {
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: message,
      confirmButtonColor: '#dc3545',
    });
  } else {
    alert(message);
  }
};

const showWarning = (message) => {
  if (window.Swal?.fire) {
    Swal.fire({
      icon: 'warning',
      title: 'Notice',
      text: message,
      confirmButtonColor: '#ffc107',
    });
  } else {
    alert(message);
  }
};

const showSuccess = (message) => {
  if (window.Swal?.fire) {
    Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: message,
      confirmButtonColor: '#00C950',
    });
  } else {
    alert(message);
  }
};

const createFacilityCard = (facility) => {
  const statusVariant = facility.availability?.variant || 'success';
  const statusBadge = facility.availability?.status || 'Available';
  const imageUrl = facility.photos?.[0] || '/images/rooms/default-room.jpg';
  
  const amenityIcons = {
    'Projector': '<svg width="16" height="16" viewBox="0 0 24 24" fill="none"><rect x="3" y="8" width="18" height="6" rx="2" stroke="currentColor" stroke-width="1.4"/><circle cx="8" cy="11" r="1" fill="currentColor"/><circle cx="12" cy="11" r="1" fill="currentColor"/></svg>',
    'Whiteboard': '<svg width="16" height="16" viewBox="0 0 24 24" fill="none"><rect x="4" y="5" width="16" height="12" rx="1.5" stroke="currentColor" stroke-width="1.4"/><path d="M4 16l-2 3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>',
    'VC setup': '<svg width="16" height="16" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="9" r="4" stroke="currentColor" stroke-width="1.4"/><path d="M4 19c0-2.761 3.582-5 8-5s8 2.239 8 5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>',
    'HDMI': '<svg width="16" height="16" viewBox="0 0 24 24" fill="none"><rect x="3" y="9" width="18" height="6" rx="1.5" stroke="currentColor" stroke-width="1.4"/><path d="M6 9v-3h12v3" stroke="currentColor" stroke-width="1.4"/></svg>',
    'WiFi': '<svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M2 8a13 13 0 0120 0" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/><path d="M5 12a8 8 0 0114 0" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/><path d="M9 16a3 3 0 016 0" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/><circle cx="12" cy="19" r="1" fill="currentColor"/></svg>',
    'TV': '<svg width="16" height="16" viewBox="0 0 24 24" fill="none"><rect x="3" y="5" width="18" height="12" rx="2" stroke="currentColor" stroke-width="1.4"/><path d="M8 21h8" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>',
  };
  
  const equipmentHtml = facility.equipment?.map(eq => {
    const icon = amenityIcons[eq] || '';
    return `
      <span class="wizard-room-amenity" title="${eq}">
        <span class="wizard-room-amenity-icon" aria-hidden="true">${icon}</span>
        <span class="wizard-room-amenity-label">${eq}</span>
      </span>
    `;
  }).join('') || '';

  const availabilityHtml = facility.availability ? `
    <div class="wizard-room-availability is-${statusVariant} mb-3">
      <div class="wizard-room-availability-icon" aria-hidden="true"></div>
      <div>
        <div class="fw-semibold">${facility.availability.text || ''}</div>
        ${facility.availability.subtext ? `<div class="small text-muted">${facility.availability.subtext}</div>` : ''}
        ${facility.availability.next ? `<div class="small text-muted">${facility.availability.next}</div>` : ''}
      </div>
    </div>
  ` : '';

  const actionClass = statusVariant === 'danger' ? 'btn-room-occupied' : 
                      statusVariant === 'warning' ? 'btn-room-limited' : 'btn-room-available';
  
  const actionLabel = statusVariant === 'danger' ? 'View Schedule' : 
                      statusVariant === 'warning' ? 'Book for Available Time' : 'Book This Room';

  return `
    <div class="col-12 col-md-6 col-xl-4">
      <article class="wizard-room-card card h-100 border-0">
        <div class="wizard-room-media position-relative">
          <img src="${imageUrl}" alt="${facility.name}" class="wizard-room-image">
          <span class="wizard-room-status badge rounded-pill text-bg-${statusVariant} position-absolute top-0 end-0 m-3">
            ${statusBadge}
          </span>
        </div>
        <div class="card-body wizard-room-body d-flex flex-column">
          <div class="wizard-room-heading mb-2">
            <h3 class="h5 mb-1">${facility.name}</h3>
          </div>
          <div class="wizard-room-meta d-flex flex-wrap gap-2 mb-3">
            <span class="wizard-room-meta-chip">${facility.location || ''}</span>
            <span class="wizard-room-meta-chip wizard-room-meta-outline">
              Up to ${facility.capacity} people
            </span>
          </div>
          <div class="wizard-room-amenities mb-3">
            ${equipmentHtml}
          </div>
          ${availabilityHtml}
          <div class="wizard-room-actions mt-auto pt-2">
            <button type="button" 
                    class="btn ${actionClass} w-100 wizard-room-select"
                    data-room-id="${facility.id}"
                    data-room-name="${facility.name}"
                    data-room-capacity="${facility.capacity}"
                    data-room-status="${statusVariant}"
                    data-default-label="${actionLabel}">
              ${actionLabel}
            </button>
          </div>
        </div>
      </article>
    </div>
  `;
};

const loadFacilities = async (filters = {}) => {
  // Initialize roomsGrid if not already set
  if (!roomsGrid) {
    roomsGrid = document.getElementById('wizardRoomsGrid');
  }
  
  if (!roomsGrid) {
    console.error('wizardRoomsGrid element not found');
    return;
  }
  
  try {
    roomsGrid.innerHTML = `
      <div class="col-12 text-center py-5">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading facilities...</span>
        </div>
        <p class="text-muted mt-2">Loading available facilities...</p>
      </div>
    `;

    const params = new URLSearchParams(filters);
    const response = await fetch(`${window.bookingAPI.getFacilities}?${params}`);
    
    if (!response.ok) {
      throw new Error('Failed to load facilities');
    }
    
    const facilities = await response.json();
    
    roomsGrid.innerHTML = '';
    
    if (facilities.length === 0) {
      roomsGrid.innerHTML = `
        <div class="col-12 text-center py-5">
          <p class="text-muted">No facilities found matching your criteria.</p>
          <button type="button" class="btn btn-outline-primary mt-2" onclick="loadFacilities()">
            Clear Filters
          </button>
        </div>
      `;
      return;
    }
    
    facilities.forEach(facility => {
      roomsGrid.insertAdjacentHTML('beforeend', createFacilityCard(facility));
    });
  } catch (error) {
    console.error('Error loading facilities:', error);
    roomsGrid.innerHTML = `
      <div class="col-12 text-center py-5">
        <p class="text-danger">Failed to load facilities. Please try again.</p>
        <button type="button" class="btn btn-primary mt-2" onclick="loadFacilities()">
          Retry
        </button>
      </div>
    `;
  }
};

const checkAvailability = async (facilityId, date, startTime, endTime) => {
  if (!window.bookingAPI?.checkAvailability) return true;
  
  try {
    const response = await fetch(window.bookingAPI.checkAvailability, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': window.bookingAPI.csrfToken,
      },
      body: JSON.stringify({
        facility_id: facilityId,
        date: date,
        start_time: startTime,
        end_time: endTime,
      }),
    });

    const data = await response.json();

    if (!data.available) {
      showWarning(data.message);
      return false;
    }

    return true;
  } catch (error) {
    console.error('Error checking availability:', error);
    showError('Failed to check availability. Please try again.');
    return false;
  }
};

const submitBooking = async (wizardState) => {
  if (!window.bookingAPI?.store) {
    showError('Booking API is not configured');
    return false;
  }

  const bookingData = {
    facility_id: wizardState.roomId,
    date: wizardState.date,
    start_time: wizardState.startTime,
    end_time: wizardState.endTime,
    purpose: wizardState.agenda,
    attendees_count: wizardState.attendees,
    sfi_support: wizardState.support.enabled,
    sfi_count: wizardState.support.enabled ? wizardState.support.count : 0,
    additional_notes: wizardState.support.notes,
    equipment: wizardState.support.equipment,
    equipment_quantities: wizardState.support.equipment.map(() => 1),
  };

  console.log('Submitting booking with data:', bookingData);

  try {
    const response = await fetch(window.bookingAPI.store, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': window.bookingAPI.csrfToken,
      },
      body: JSON.stringify(bookingData),
    });

    const result = await response.json();
    console.log('Server response:', result);

    if (result.success) {
      wizardState.referenceCode = result.data.reference_code;
      return true;
    } else {
      // Show detailed validation errors if available
      if (result.errors) {
        const errorMessages = Object.values(result.errors).flat().join('\n');
        showError(errorMessages);
      } else {
        showError(result.message || 'Failed to submit booking');
      }
      return false;
    }
  } catch (error) {
    console.error('Error submitting booking:', error);
    showError('Failed to submit booking. Please try again.');
    return false;
  }
};

// ============================================================================
// MAIN WIZARD LOGIC
// ============================================================================

document.addEventListener('DOMContentLoaded', () => {
  const methodSection     = document.getElementById('wizardMethodSection');
  const landingShell      = document.getElementById('wizardLandingShell');
  const manualStage       = document.getElementById('wizardManualStage');
  const wizardAppNavWrap  = document.getElementById('wizardAppNav');
  roomsGrid               = document.getElementById('wizardRoomsGrid'); // Set module-level reference
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
  };

  const formatTimeLabel = (value = '') => {
    if (!value) return '';
    const [hourStr, minStr] = value.split(':');
    if (hourStr === undefined || minStr === undefined) return value;
    let hour = Number(hourStr);
    const minutes = Number(minStr);
    const period = hour >= 12 ? 'PM' : 'AM';
    hour = hour % 12 || 12;
    return `${hour}:${minutes.toString().padStart(2, '0')} ${period}`;
  };

  const formatDateLabel = (value = '') => {
    if (!value) return '';
    const [year, month, day] = value.split('-').map(Number);
    if ([year, month, day].some(Number.isNaN)) return value;
    const dateObj = new Date(year, month - 1, day);
    return dateObj.toLocaleDateString('en-US', {
      weekday: 'long',
      month: 'long',
      day: 'numeric',
      year: 'numeric',
    });
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
      wizardState.support.equipment.forEach(item => {
        const li = document.createElement('li');
        li.textContent = item;
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
    updateStepperState(5);
    const top = successPanel.offsetTop || 0;
    window.scrollTo({ top: Math.max(top - 40, 0), behavior: 'smooth' });
  };

  const generateReferenceCode = () => {
    const randomSegment = Math.random().toString(36).toUpperCase().slice(2, 8);
    return `ENC-${randomSegment}`;
  };

  const showMethodSelection = () => {
    if (!methodSection) return;

    methodSection.classList.remove('d-none');
    methodSection.hidden = false;

    methodSection.querySelector('.wizard-method-title')?.focus?.();
    wizardAppNavWrap?.classList.add('d-none');
  };
  showMethodSelection();

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
    
    // Load facilities from API
    loadFacilities();
  };

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
      }
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

    if (nextDetailsBtn) {
      nextDetailsBtn.addEventListener('click', () => {
        if (nextDetailsBtn.disabled) return;
        datePanel?.classList.add('d-none');
        detailsPanel?.classList.remove('d-none');
        updateStepperState(3);
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
      const min = Number(inputEl.dataset.min || 1);
      const max = Number(inputEl.dataset.max || 50);

      const setValue = (newValue) => {
        const clamped = Math.min(Math.max(newValue, min), max);
        inputEl.value = clamped;
        valueEl.textContent = clamped;
        if (typeof onChange === 'function') onChange(clamped);
        updateDetailsNextState();
      };

      decreaseBtn.addEventListener('click', () => {
        setValue(Number(inputEl.value || min) - 1);
      });

      increaseBtn.addEventListener('click', () => {
        setValue(Number(inputEl.value || min) + 1);
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
      datePanel?.classList.remove('d-none');
      updateStepperState(2);
    });

    nextReviewBtn?.addEventListener('click', () => {
      if (nextReviewBtn.disabled) return;
      detailsPanel.classList.add('d-none');
      reviewPanel?.classList.remove('d-none');
      updateStepperState(4);
      updateReviewSummary();
    });

    updateDetailsNextState();
  }

  backToDetailsBtn?.addEventListener('click', () => {
    reviewPanel?.classList.add('d-none');
    detailsPanel?.classList.remove('d-none');
    updateStepperState(3);
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
  
  // Add filter event listeners for facilities
  const roomSearch = document.getElementById('roomSearch');
  const roomFloor = document.getElementById('roomFloor');
  const roomSize = document.getElementById('roomSize');
  
  if (roomSearch) {
    roomSearch.addEventListener('input', debounce((e) => {
      loadFacilities({ search: e.target.value });
    }, 500));
  }
  
  if (roomFloor) {
    roomFloor.addEventListener('change', (e) => {
      const floor = e.target.value.toLowerCase().replace(' floor', '');
      loadFacilities({ floor: floor });
    });
  }
  
  if (roomSize) {
    roomSize.addEventListener('change', (e) => {
      loadFacilities({ size: e.target.value });
    });
  }
});
