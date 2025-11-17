@extends('layouts.app')

@section('title', 'My Bookings')

@push('styles')
    @vite([
        'resources/css/wizard/base.css',
        'resources/css/bookings/index.css',
    ])
@endpush

@php
    $bookingStats = $bookingStats ?? ['pending' => 0, 'confirmed' => 0, 'cancelled' => 0];
    $bookingsData = $bookingsData ?? [];
@endphp

@section('content')
    <!-- Include Dashboard Navbar -->
    @include('partials.dashboard-navbar', [
        'currentStep' => 0,
        'steps' => [],
        'bookingsCount' => ($bookingStats['pending'] ?? 0) + ($bookingStats['confirmed'] ?? 0) + ($bookingStats['cancelled'] ?? 0),
        'notificationsCount' => $notificationsCount,
        'userName' => auth()->user()->name ?? 'User',
        'userEmail' => auth()->user()->email ?? 'user@ministry.gov',
        'userRole' => auth()->user()->role ?? 'staff',
        'brand' => 'ONE Services',
        'showStepper' => false,
        'showBookingsToggle' => false,
    ])

    <div id="toast" class="toast" role="status" aria-live="polite">
        <span id="toastMessage"></span>
    </div>

    <section class="bookings-shell">
        <a href="{{ route('user.dashboard') }}" class="bookings-back-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to dashboard
        </a>
        <header class="bookings-hero">
            <div class="bookings-hero__copy">
                <p class="bookings-hero__eyebrow">One Services · Reservation hub</p>
                <h1 class="bookings-hero__title">My Bookings</h1>
                <p class="bookings-hero__subtitle">Track upcoming rooms, review approvals, and export quick summaries for your team.</p>
            </div>
            <div class="bookings-hero__actions">
                <a href="{{ route('user.booking.wizard', [], false) }}#wizardMethodSection" class="btn-pill btn-pill--primary">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
                    </svg>
                    Start a booking
                </a>
                <button type="button" class="btn-pill btn-pill--ghost" onclick="exportCalendar()">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4-4 4m0 0-4-4m4 4V4"/>
                    </svg>
                    Download summary
                </button>
            </div>
        </header>

        <div class="bookings-stats">
            <article class="stat-card">
                <p class="stat-card__label">Awaiting review</p>
                <p class="stat-card__value" id="statPending">{{ $bookingStats['pending'] ?? 0 }}</p>
                <p class="stat-card__trend">Pending confirmations</p>
            </article>
            <article class="stat-card">
                <p class="stat-card__label">Approved slots</p>
                <p class="stat-card__value" id="statConfirmed">{{ $bookingStats['confirmed'] ?? 0 }}</p>
                <p class="stat-card__trend">Ready to roll</p>
            </article>
            <article class="stat-card">
                <p class="stat-card__label">Cancelled</p>
                <p class="stat-card__value" id="statCancelled">{{ $bookingStats['cancelled'] ?? 0 }}</p>
                <p class="stat-card__trend">Released capacity</p>
            </article>
        </div>

        <div class="bookings-surface" aria-live="polite">
            <div class="bookings-tabs" role="tablist">
                <button type="button" class="bookings-tab active" onclick="switchTab(this, 'upcoming')">
                    <span>Upcoming</span>
                    <span class="bookings-tab__badge" id="upcomingCount">0</span>
                </button>
                <button type="button" class="bookings-tab" onclick="switchTab(this, 'past')">Past</button>
                <button type="button" class="bookings-tab" onclick="switchTab(this, 'cancelled')">Cancelled</button>
            </div>

            <div class="bookings-filters">
                <div class="filter-field">
                    <label for="searchInput">Search</label>
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input
                        type="text"
                        id="searchInput"
                        class="filter-input filter-input--with-icon"
                        placeholder="Search by purpose, room, or facility..."
                        oninput="filterBookings()"
                    >
                </div>
                <div class="filter-field">
                    <label for="facilityFilter">Facility</label>
                    <select id="facilityFilter" class="filter-select" onchange="filterBookings()">
                        <option value="">All Facilities</option>
                        <option value="Meeting Room">Meeting Room</option>
                        <option value="Conference Room">Conference Room</option>
                    </select>
                </div>
                <div class="filter-field">
                    <label for="statusFilter">Status</label>
                    <select id="statusFilter" class="filter-select" onchange="filterBookings()">
                        <option value="">Any status</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>

            <div class="bookings-table-wrapper">
                <table class="bookings-table">
                    <thead>
                        <tr>
                            <th>Facility</th>
                            <th>Date &amp; Time</th>
                            <th>Purpose</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="bookingsTable"></tbody>
                </table>
            </div>

            <div id="mobileCards" class="mobile-board"></div>
        </div>
    </section>

    <script>
        // Sample bookings data
        let bookings = @json($bookingsData ?? []);

        let currentTab = 'upcoming';
        let filteredBookings = [...bookings];

        // Initialize the page
        function init() {
            renderBookings();
            updateBookingCount();
        }

        // Toggle mobile menu
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            const btn = document.querySelector('.mobile-menu-btn');
            menu.classList.toggle('active');
            btn.classList.toggle('active');
        }

        // Tab switching
        function switchTab(element, tab) {
            document.querySelectorAll('.bookings-tab').forEach(t => t.classList.remove('active'));
            element.classList.add('active');
            currentTab = tab;
            filterBookings();
            updateBookingCount();
            showToast(`Switched to ${tab} bookings`, 'success');
        }

        // Filter bookings
        function filterBookings() {
            const searchQuery = document.getElementById('searchInput').value.toLowerCase();
            const facilityFilter = document.getElementById('facilityFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            filteredBookings = bookings.filter(booking => {
                // Tab filtering: upcoming, past, cancelled
                let matchesTab = true;
                const bookingDate = parseBookingDate(booking.date);
                
                if (currentTab === 'upcoming') {
                    matchesTab = bookingDate >= today && booking.status !== 'cancelled';
                } else if (currentTab === 'past') {
                    matchesTab = bookingDate < today && booking.status !== 'cancelled';
                } else if (currentTab === 'cancelled') {
                    matchesTab = booking.status === 'cancelled';
                }

                const matchesSearch = !searchQuery || 
                    booking.purpose.toLowerCase().includes(searchQuery) ||
                    booking.facility.toLowerCase().includes(searchQuery);
                
                const matchesFacility = !facilityFilter || booking.facility === facilityFilter;
                const matchesStatus = !statusFilter || booking.status === statusFilter;
                
                return matchesTab && matchesSearch && matchesFacility && matchesStatus;
            });

            renderBookings();
        }

        // Parse booking date string (format: "Day, Mon D, Y")
        function parseBookingDate(dateStr) {
            // Example: "Mon, Nov 18, 2025" -> parse to Date
            const parts = dateStr.split(', ');
            if (parts.length === 3) {
                return new Date(parts[1] + ' ' + parts[2]);
            }
            return new Date(dateStr);
        }

        // Render bookings in both table and card view
        function renderBookings() {
            const tableBody = document.getElementById('bookingsTable');
            const mobileCards = document.getElementById('mobileCards');

            tableBody.innerHTML = '';
            mobileCards.innerHTML = '';

            if (filteredBookings.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="5" class="empty-state">No bookings found</td></tr>';
                mobileCards.innerHTML = '<div class="mobile-card empty-state">No bookings found</div>';
                return;
            }

            filteredBookings.forEach(booking => {
                const statusMeta = getStatusMeta(booking.status);

                const row = document.createElement('tr');
                row.setAttribute('data-id', booking.id);
                row.innerHTML = `
                    <td>
                        <div class="booking-room">
                            <div class="booking-room__icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="booking-room__meta">
                                <h4>${booking.facility}</h4>
                                <span>${booking.facilityType}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div>
                            <strong>${booking.date}</strong>
                            <p class="booking-date-sub">${booking.time}</p>
                        </div>
                    </td>
                    <td>${booking.purpose}</td>
                    <td>
                        <span class="booking-status ${statusMeta.tone}">
                            ${statusMeta.label}
                        </span>
                    </td>
                    <td class="table-actions-cell">
                        <div class="table-actions">
                            <a class="icon-btn" href="${booking.viewUrl || '#'}" title="View booking">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <button class="icon-btn icon-btn--danger" onclick="cancelBooking(${booking.id})" title="Cancel booking">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                `;
                tableBody.appendChild(row);

                const card = document.createElement('div');
                card.className = 'mobile-card';
                card.setAttribute('data-id', booking.id);
                card.innerHTML = `
                    <div class="mobile-card__row mobile-card__row--header">
                        <div>
                            <strong>${booking.facility}</strong>
                            <div class="mobile-card__label">${booking.facilityType}</div>
                        </div>
                        <span class="booking-status ${statusMeta.tone}">
                            ${statusMeta.label}
                        </span>
                    </div>
                    <div class="mobile-card__row">
                        <span class="mobile-card__label">Date</span>
                        <span>${booking.date}</span>
                    </div>
                    <div class="mobile-card__row">
                        <span class="mobile-card__label">Time</span>
                        <span>${booking.time}</span>
                    </div>
                    <div class="mobile-card__row">
                        <span class="mobile-card__label">Purpose</span>
                        <span>${booking.purpose}</span>
                    </div>
                    <div class="table-actions">
                        <a class="icon-btn" href="${booking.viewUrl || '#'}" title="View booking">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </a>
                        <button class="icon-btn icon-btn--danger" onclick="cancelBooking(${booking.id})" title="Cancel booking">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                `;
                mobileCards.appendChild(card);
            });
        }

        function getStatusMeta(status) {
            const map = {
                pending: { label: 'Pending review', tone: 'is-warning' },
                confirmed: { label: 'Confirmed', tone: 'is-success' },
                cancelled: { label: 'Cancelled', tone: 'is-danger' },
                completed: { label: 'Completed', tone: 'is-neutral' },
            };
            return map[status] || { label: status, tone: 'is-neutral' };
        }

        // Cancel booking
        function cancelBooking(id) {
            if (confirm('Are you sure you want to cancel this booking?')) {
                const bookingIndex = bookings.findIndex(b => b.id === id);
                if (bookingIndex !== -1) {
                    bookings[bookingIndex].status = 'cancelled';
                    filterBookings();
                    updateBookingCount();
                    showToast('Booking cancelled successfully', 'success');
                    
                    // For Laravel backend:
                    // fetch(`/api/bookings/${id}/cancel`, {
                    //     method: 'POST',
                    //     headers: {
                    //         'Content-Type': 'application/json',
                    //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    //     }
                    // })
                    // .then(response => response.json())
                    // .then(data => {
                    //     showToast('Booking cancelled successfully', 'success');
                    //     filterBookings();
                    // })
                    // .catch(error => {
                    //     showToast('Failed to cancel booking', 'error');
                    // });
                }
            }
        }

        // Export calendar
        function exportCalendar() {
            showToast('Exporting calendar...', 'success');
            // For Laravel: window.location.href = '/api/bookings/export';
        }

        // Show toast notification
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            
            toastMessage.textContent = message;
            toast.className = `toast show ${type}`;
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        // Update booking count
        function updateBookingCount() {
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            const upcomingCount = bookings.filter(b => {
                const bookingDate = parseBookingDate(b.date);
                return bookingDate >= today && b.status !== 'cancelled';
            }).length;

            const pendingCount = bookings.filter(b => b.status === 'pending').length;
            const confirmedCount = bookings.filter(b => b.status === 'confirmed').length;
            const cancelledCount = bookings.filter(b => b.status === 'cancelled').length;

            document.getElementById('upcomingCount').textContent = upcomingCount;
            document.getElementById('statPending').textContent = pendingCount;
            document.getElementById('statConfirmed').textContent = confirmedCount;
            document.getElementById('statCancelled').textContent = cancelledCount;
        }

        // Toggle user menu
        function toggleUserMenu() {
            showToast('User menu clicked', 'success');
        }

        // Show notifications
        function showNotifications() {
            showToast('You have 2 new notifications', 'success');
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', init);
    </script>
@endsection
