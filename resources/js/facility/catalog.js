// resources/js/facility-catalog.js

const setupFacilityCatalog = () => {
  const catalogData = window.facilityCatalogData || [];
  const cards = Array.from(document.querySelectorAll('[data-facility-card]'));
  const detailPanel = document.getElementById('facilityDetailPanel');
  if (!detailPanel || !catalogData.length || !cards.length) {
    return;
  }

  const facilityMap = catalogData.reduce((acc, facility) => {
    acc[facility.id] = facility;
    return acc;
  }, {});

  const selectors = {
    image: document.getElementById('facilityDetailImage'),
    status: document.getElementById('facilityDetailStatus'),
    statusCopy: document.getElementById('facilityDetailStatusCopy'),
    name: document.getElementById('facilityDetailName'),
    desc: document.getElementById('facilityDetailDesc'),
    location: document.getElementById('facilityDetailLocation'),
    capacity: document.getElementById('facilityDetailCapacity'),
    layout: document.getElementById('facilityDetailLayout'),
    lead: document.getElementById('facilityDetailLeadTime'),
    amenities: document.getElementById('facilityDetailAmenities'),
    highlights: document.getElementById('facilityDetailHighlights'),
    timeline: document.getElementById('facilityDetailTimeline'),
    bookBtn: document.getElementById('facilityDetailBookLink'),
    supportBtn: document.getElementById('facilityDetailSupportLink'),
  };

  const grid = document.getElementById('facilityCardGrid');
  const availabilityToggle = document.getElementById('facilityAvailabilityToggle');
  const availabilityPanel = document.getElementById('facilityAvailabilityPanel');
  const availabilityApply = document.getElementById('facilityAvailabilityApply');
  const availabilityClearButtons = document.querySelectorAll('[data-facility-availability-clear]');
  const availabilityActive = document.getElementById('facilityAvailabilityActive');
  const availabilityLabel = document.getElementById('facilityAvailabilityLabel');
  const availabilityEmpty = document.getElementById('facilityAvailabilityEmpty');
  const availabilityEmptyDate = document.getElementById('facilityAvailabilityEmptyDate');
  const calendarEl = document.getElementById('facilityCalendar');
  const calendarGrid = document.getElementById('facilityCalendarGrid');
  const calendarMonthLabel = document.getElementById('facilityCalendarMonth');
  const calendarSelectedLabel = document.getElementById('facilityCalendarSelectedDate');
  const calendarPrevBtn = document.getElementById('facilityCalendarPrev');
  const calendarNextBtn = document.getElementById('facilityCalendarNext');

  const statusPriority = { success: 0, warning: 1, info: 2, danger: 3 };
  const availabilityPriority = { available: 0, limited: 1, hold: 2, occupied: 3, unknown: 4 };
  let activeDate = null;
  let pendingDate = null;
  const calendarCursor = new Date();
  calendarCursor.setDate(1);

  const fallbackAvailabilityState = (facility) => {
    switch (facility?.status?.variant) {
      case 'success':
        return 'available';
      case 'warning':
        return 'limited';
      case 'info':
        return 'hold';
      case 'danger':
        return 'occupied';
      default:
        return 'unknown';
    }
  };

  const getAvailabilityState = (facility, date) => {
    if (!facility) return 'unknown';
    if (!date) return fallbackAvailabilityState(facility);
    return facility.availability?.[date]?.state ?? fallbackAvailabilityState(facility);
  };

  const getAvailabilityCopy = (facility, date) => {
    if (!facility) return '';
    if (!date) return facility.status?.copy ?? '';
    return facility.availability?.[date]?.copy ?? facility.status?.copy ?? '';
  };

  const formatDateLabel = (value) => {
    if (!value) return '';
    try {
      const dateObj = new Date(`${value}T00:00:00`);
      if (Number.isNaN(dateObj.getTime())) {
        return value;
      }
      return dateObj.toLocaleDateString(undefined, {
        weekday: 'short',
        month: 'short',
        day: 'numeric',
      });
    } catch (error) {
      return value;
    }
  };

  const pad = (value) => String(value).padStart(2, '0');

  const toISODate = (dateObj) =>
    `${dateObj.getFullYear()}-${pad(dateObj.getMonth() + 1)}-${pad(dateObj.getDate())}`;

  const sortCards = (compareFn) => {
    if (!grid) return;
    const ordered = [...cards].sort(compareFn);
    ordered.forEach((card) => grid.appendChild(card));
  };

  const defaultCompare = (cardA, cardB) => {
    const facilityA = facilityMap[cardA.dataset.facilityCard];
    const facilityB = facilityMap[cardB.dataset.facilityCard];
    const statusDiff =
      (statusPriority[facilityA?.status?.variant] ?? 99) -
      (statusPriority[facilityB?.status?.variant] ?? 99);
    if (statusDiff !== 0) {
      return statusDiff;
    }

    const bookingsDiff =
      (facilityA?.bookings_today ?? 99) - (facilityB?.bookings_today ?? 99);
    if (bookingsDiff !== 0) {
      return bookingsDiff;
    }

    return (facilityA?.name || '').localeCompare(facilityB?.name || '');
  };

  const availabilityCompare = (date) => (cardA, cardB) => {
    const facilityA = facilityMap[cardA.dataset.facilityCard];
    const facilityB = facilityMap[cardB.dataset.facilityCard];
    const stateA = getAvailabilityState(facilityA, date);
    const stateB = getAvailabilityState(facilityB, date);
    const stateDiff = (availabilityPriority[stateA] ?? 4) - (availabilityPriority[stateB] ?? 4);
    if (stateDiff !== 0) {
      return stateDiff;
    }
    return defaultCompare(cardA, cardB);
  };

  const clearAvailabilityStyles = () => {
    cards.forEach((card) => {
      card.removeAttribute('data-availability-state');
      const facility = facilityMap[card.dataset.facilityCard];
      const note = card.querySelector('.facility-card-note');
      if (note && facility) {
        note.textContent = facility.status?.copy ?? '';
      }
    });
  };

  const updateCalendarSelectionLabel = () => {
    if (!calendarSelectedLabel) return;
    if (!pendingDate) {
      calendarSelectedLabel.textContent = 'None yet';
      return;
    }
    calendarSelectedLabel.textContent = formatDateLabel(pendingDate);
  };

  const renderCalendar = () => {
    if (!calendarGrid || !calendarMonthLabel) return;
    const workingDate = new Date(calendarCursor);
    const year = workingDate.getFullYear();
    const month = workingDate.getMonth();

    calendarMonthLabel.textContent = workingDate.toLocaleDateString(undefined, {
      month: 'long',
      year: 'numeric',
    });

    const startWeekday = new Date(year, month, 1).getDay();
    const totalDays = new Date(year, month + 1, 0).getDate();
    const todayISO = toISODate(new Date());

    calendarGrid.innerHTML = '';

    for (let i = 0; i < startWeekday; i += 1) {
      const filler = document.createElement('span');
      filler.className = 'wizard-calendar-day is-muted';
      filler.setAttribute('aria-hidden', 'true');
      calendarGrid.appendChild(filler);
    }

    for (let day = 1; day <= totalDays; day += 1) {
      const dateObj = new Date(year, month, day);
      const iso = toISODate(dateObj);
      const button = document.createElement('button');
      button.type = 'button';
      button.className = 'wizard-calendar-day';
      button.dataset.date = iso;
      button.textContent = String(day);

      if (iso === todayISO) {
        button.classList.add('is-today');
      }
      if (pendingDate && iso === pendingDate) {
        button.classList.add('is-selected');
      }

      button.addEventListener('click', () => {
        pendingDate = iso;
        updateCalendarSelectionLabel();
        renderCalendar();
      });

      button.addEventListener('keydown', (event) => {
        if (event.key === 'Enter' || event.key === ' ') {
          event.preventDefault();
          pendingDate = iso;
          updateCalendarSelectionLabel();
          renderCalendar();
        }
      });

      calendarGrid.appendChild(button);
    }
  };

  const applyAvailabilityView = (date) => {
    if (!date) return;
    activeDate = date;
    pendingDate = date;
    let hasBookable = false;

    cards.forEach((card) => {
      const facility = facilityMap[card.dataset.facilityCard];
      if (!facility) return;
      const state = getAvailabilityState(facility, date);
      const note = card.querySelector('.facility-card-note');
      if (note) {
        note.textContent = getAvailabilityCopy(facility, date);
      }
      card.dataset.availabilityState = state;
      if (state === 'available' || state === 'limited') {
        hasBookable = true;
      }
    });

    sortCards(availabilityCompare(date));

    if (availabilityActive && availabilityLabel) {
      availabilityActive.hidden = false;
      availabilityLabel.textContent = formatDateLabel(date);
    }
    if (availabilityEmpty && availabilityEmptyDate) {
      availabilityEmpty.hidden = hasBookable;
      availabilityEmptyDate.textContent = formatDateLabel(date);
    }
    updateCalendarSelectionLabel();
    renderCalendar();
  };

  const clearAvailabilityView = () => {
    activeDate = null;
    pendingDate = null;
    clearAvailabilityStyles();
    sortCards(defaultCompare);
    if (availabilityActive) availabilityActive.hidden = true;
    if (availabilityEmpty) availabilityEmpty.hidden = true;
    updateCalendarSelectionLabel();
    renderCalendar();
  };

  const renderChips = (container, items = []) => {
    if (!container) return;
    container.innerHTML = '';
    items.forEach((item) => {
      const chip = document.createElement('span');
      chip.className = 'facility-chip';
      chip.textContent = item;
      container.appendChild(chip);
    });
  };

  const renderHighlights = (container, items = []) => {
    if (!container) return;
    container.innerHTML = '';
    items.forEach((item) => {
      const li = document.createElement('li');
      li.className = 'facility-highlight-item';
      li.innerHTML = `
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <span>${item}</span>
      `;
      container.appendChild(li);
    });
  };

  const renderTimeline = (container, items = []) => {
    if (!container) return;
    container.innerHTML = '';
    items.forEach((item) => {
      const block = document.createElement('div');
      block.className = 'facility-timeline-item';
      block.innerHTML = `
        <div class="facility-timeline-title">${item.label}</div>
        <div class="facility-timeline-copy">${item.copy}</div>
      `;
      container.appendChild(block);
    });
  };

  const updateStatusBadge = (facility) => {
    if (!selectors.status) return;
    selectors.status.textContent = facility.status?.label ?? 'Status';
    selectors.status.className = `status-pill facility-status ${facility.status?.variant ?? 'info'}`;
    if (selectors.statusCopy) {
      selectors.statusCopy.textContent = facility.status?.copy ?? '';
    }
  };

  const updateMeta = (facility) => {
    if (selectors.location) selectors.location.textContent = facility.location ?? '—';
    if (selectors.capacity) selectors.capacity.textContent = facility.capacity_label ?? '—';
    if (selectors.layout) selectors.layout.textContent = facility.layout ?? '—';
    if (selectors.lead) selectors.lead.textContent = facility.prep_time ?? '—';
  };

  const updateButtons = (facility) => {
    if (selectors.bookBtn) {
      selectors.bookBtn.textContent = `Book ${facility.short_label ?? facility.name}`;
    }
    if (selectors.supportBtn) {
      selectors.supportBtn.textContent = 'Request Facility Support';
      if (facility.support_contact) {
        selectors.supportBtn.href = facility.support_contact;
      }
    }
  };

  const renderFacilityDetail = (facilityId) => {
    const facility = facilityMap[facilityId];
    if (!facility) return;

    if (selectors.image) selectors.image.src = facility.image;
    updateStatusBadge(facility);
    if (selectors.name) selectors.name.textContent = facility.name;
    if (selectors.desc) selectors.desc.textContent = facility.description ?? '';

    updateMeta(facility);
    renderChips(selectors.amenities, facility.amenities);
    renderHighlights(selectors.highlights, facility.highlights);
    renderTimeline(selectors.timeline, facility.timeline);
    updateButtons(facility);

    cards.forEach((card) => {
      card.classList.remove('is-active');
      card.setAttribute('aria-pressed', 'false');
    });
    const activeCard = cards.find((card) => card.dataset.facilityCard === facilityId);
    if (activeCard) {
      activeCard.classList.add('is-active');
      activeCard.setAttribute('aria-pressed', 'true');
    }
  };

  if (availabilityToggle && availabilityPanel) {
    availabilityToggle.addEventListener('click', () => {
      availabilityPanel.hidden = !availabilityPanel.hidden;
      availabilityToggle.setAttribute('aria-expanded', String(!availabilityPanel.hidden));
    });
  }

  if (availabilityApply) {
    availabilityApply.addEventListener('click', () => {
      if (!pendingDate) {
        calendarEl?.focus();
        return;
      }
      applyAvailabilityView(pendingDate);
      if (availabilityPanel) {
        availabilityPanel.hidden = true;
      }
      if (availabilityToggle) {
        availabilityToggle.setAttribute('aria-expanded', 'false');
      }
    });
  }

  availabilityClearButtons.forEach((button) => {
    button.addEventListener('click', () => {
      clearAvailabilityView();
      if (availabilityPanel) {
        availabilityPanel.hidden = true;
      }
      if (availabilityToggle) {
        availabilityToggle.setAttribute('aria-expanded', 'false');
      }
    });
  });

  if (calendarPrevBtn) {
    calendarPrevBtn.addEventListener('click', () => {
      calendarCursor.setMonth(calendarCursor.getMonth() - 1);
      renderCalendar();
    });
  }

  if (calendarNextBtn) {
    calendarNextBtn.addEventListener('click', () => {
      calendarCursor.setMonth(calendarCursor.getMonth() + 1);
      renderCalendar();
    });
  }

  cards.forEach((card) => {
    const selectFacility = () => renderFacilityDetail(card.dataset.facilityCard);
    card.addEventListener('click', selectFacility);
    card.addEventListener('keydown', (event) => {
      if (event.key === 'Enter' || event.key === ' ') {
        event.preventDefault();
        selectFacility();
      }
    });
  });

  updateCalendarSelectionLabel();
  renderCalendar();
  sortCards(defaultCompare);
  renderFacilityDetail(catalogData[0].id);
};

document.addEventListener('DOMContentLoaded', setupFacilityCatalog);
