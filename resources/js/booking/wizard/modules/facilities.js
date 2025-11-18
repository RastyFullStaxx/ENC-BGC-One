import { showError, showWarning } from './utils';

export const createFacilityCard = (facility) => {
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

export const loadFacilities = async (roomsGrid, filters = {}) => {
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
          <button type="button" class="btn btn-outline-primary mt-2" data-action="wizard-clear-filters">
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
        <button type="button" class="btn btn-primary mt-2" data-action="wizard-retry-facilities">
          Retry
        </button>
      </div>
    `;
  }
};

export const checkAvailability = async (facilityId, date, startTime, endTime) => {
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
        date,
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

export const submitBooking = async (wizardState) => {
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

    if (result.success) {
      wizardState.referenceCode = result.data.reference_code;
      return true;
    }

    if (result.errors) {
      const errorMessages = Object.values(result.errors).flat().join('\n');
      showError(errorMessages);
    } else {
      showError(result.message || 'Failed to submit booking');
    }
    return false;
  } catch (error) {
    console.error('Error submitting booking:', error);
    showError('Failed to submit booking. Please try again.');
    return false;
  }
};
