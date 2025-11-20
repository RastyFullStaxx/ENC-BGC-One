document.addEventListener('DOMContentLoaded', function() {
    // Tab + filter behaviour for My Bookings widget
    const bookingWidgets = document.querySelectorAll('[data-component="enc-my-bookings"]');
    bookingWidgets.forEach(widget => {
        const tabs = widget.querySelectorAll('.enc-my-bookings-card__tab');
        const items = widget.querySelectorAll('[data-role="booking-item"]');
        const emptyState = widget.querySelector('[data-role="empty-state"]');

        const filterList = (status) => {
            let visibleItems = 0;
            items.forEach(item => {
                const matches = status === 'all' || item.dataset.status === status;
                item.style.display = matches ? '' : 'none';
                if (matches) visibleItems++;
            });

            if (emptyState) {
                emptyState.hidden = visibleItems !== 0;
            }
        };

        widget.addEventListener('click', (event) => {
            const tab = event.target.closest('.enc-my-bookings-card__tab');
            if (!tab || !widget.contains(tab)) return;

            tabs.forEach(btn => {
                btn.classList.toggle('is-active', btn === tab);
                btn.setAttribute('aria-selected', btn === tab ? 'true' : 'false');
            });

            filterList(tab.dataset.status);
        });

        const initialTab = widget.querySelector('.enc-my-bookings-card__tab.is-active') || tabs[0];
        if (initialTab) {
            filterList(initialTab.dataset.status);
        }
    });

    // Navbar toggle controls for bookings snapshot
    const navToggle = document.querySelector('#myBookingsToggle');
    if (navToggle) {
        const panelSelector = navToggle.getAttribute('data-target');
        if (panelSelector) {
            const panel = document.querySelector(panelSelector);
            if (panel) {
                const setPanelState = (visible) => {
                    panel.dataset.panelState = visible ? 'visible' : 'hidden';
                    panel.classList.toggle('is-visible', visible);
                    navToggle.setAttribute('aria-expanded', String(visible));
                    navToggle.setAttribute('aria-pressed', String(visible));
                };

                const initialVisible = panel.dataset.panelState !== 'hidden';
                setPanelState(initialVisible);

                navToggle.addEventListener('click', () => {
                    const currentlyVisible = panel.dataset.panelState !== 'hidden';
                    setPanelState(!currentlyVisible);
                });

                document.addEventListener('keyup', (event) => {
                    if (event.key === 'Escape' && panel.dataset.panelState !== 'hidden') {
                        setPanelState(false);
                    }
                });
            }
        }
    }

    // Individual booking items click
    const bookingItems = document.querySelectorAll('[data-role="booking-item"]');
    bookingItems.forEach(item => {
        item.addEventListener('click', function() {
            alert('Booking details would be shown here');
        });
        item.style.cursor = 'pointer';
    });

    // Calendar event detail modal
    const eventModal = document.getElementById('calendarEventModal');
    if (eventModal) {
        const eventDate = document.getElementById('calendarEventDate');
        const eventTitle = document.getElementById('calendarEventTitle');
        const eventFacility = document.getElementById('calendarEventFacility');
        const eventRequester = document.getElementById('calendarEventRequester');
        const eventTime = document.getElementById('calendarEventTime');
        const body = document.body;

        const closeEventModal = () => {
            eventModal.setAttribute('hidden', '');
            body.style.removeProperty('overflow');
        };

        const openEventModal = (payload = {}) => {
            if (eventDate) eventDate.textContent = payload.date || 'Date TBA';
            if (eventTitle) eventTitle.textContent = payload.title || 'Booking details';
            if (eventFacility) eventFacility.textContent = payload.facility || 'Facility';
            if (eventRequester) eventRequester.textContent = payload.requester || 'Requester';
            const timeText = payload.time && payload.time.trim() ? payload.time : 'Timeframe TBA';
            if (eventTime) eventTime.textContent = timeText;

            eventModal.removeAttribute('hidden');
            body.style.overflow = 'hidden';

            const closeBtn = eventModal.querySelector('[data-dismiss=\"calendar-event\"]');
            if (closeBtn) {
                closeBtn.focus({ preventScroll: true });
            }
        };

        const dismissElements = eventModal.querySelectorAll('[data-dismiss=\"calendar-event\"]');
        dismissElements.forEach(el => {
            el.addEventListener('click', closeEventModal);
        });

        document.addEventListener('keyup', (event) => {
            if (event.key === 'Escape' && !eventModal.hasAttribute('hidden')) {
                closeEventModal();
            }
        });

        const calendarPills = document.querySelectorAll('.calendar-event-pill');
        calendarPills.forEach(pill => {
            const openFromPill = () => {
                openEventModal({
                    facility: pill.dataset.facility,
                    title: pill.dataset.title,
                    date: pill.dataset.date,
                    time: pill.dataset.time,
                    requester: pill.dataset.requester,
                });
            };

            pill.addEventListener('click', openFromPill);
            pill.addEventListener('keypress', (evt) => {
                if (evt.key === 'Enter' || evt.key === ' ') {
                    evt.preventDefault();
                    openFromPill();
                }
            });
        });
    }
});
