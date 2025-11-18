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

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(btn => {
                    btn.classList.remove('is-active');
                    btn.setAttribute('aria-selected', 'false');
                });

                tab.classList.add('is-active');
                tab.setAttribute('aria-selected', 'true');
                filterList(tab.dataset.status);
            });
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
});
