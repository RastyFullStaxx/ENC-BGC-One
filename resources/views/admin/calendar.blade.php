@extends('layouts.app')

@section('title', 'Admin • Global Calendar')

@push('styles')
    @vite(['resources/css/admin/calendar.css'])
@endpush

@section('content')
@php
    $bookings = [
        [
            'facility' => 'Orion Boardroom',
            'time' => '2024-08-12 09:00 AM – 11:00 AM',
            'status' => 'approved',
            'status_label' => 'Approved',
            'day' => 'Mon',
            'tooltip' => "Leadership Sync\nRequester: J. Mercado\nAttendees: 14",
            'priority' => 'Leadership',
        ],
        [
            'facility' => 'Helios Lab',
            'time' => '1:00 PM – 3:00 PM',
            'status' => 'pending',
            'status_label' => 'Pending',
            'day' => 'Mon',
            'tooltip' => "Training Session\nRequester: A. Santos\nAttendees: 24",
            'priority' => 'Training',
        ],
        [
            'facility' => 'Summit Hall',
            'time' => '10:00 AM – 4:00 PM',
            'status' => 'approved',
            'status_label' => 'Approved',
            'day' => 'Tue',
            'tooltip' => "Town Hall\nRequester: Exec Office\nAttendees: 120",
            'priority' => 'Event',
        ],
        [
            'facility' => 'Nova Hub',
            'time' => '2:00 PM – 3:30 PM',
            'status' => 'cancelled',
            'status_label' => 'Cancelled',
            'day' => 'Wed',
            'tooltip' => "Design Sprint\nRequester: Creative Team\nAttendees: 8",
            'priority' => 'Team',
        ],
        [
            'facility' => 'Orion Boardroom',
            'time' => '4:00 PM – 6:00 PM',
            'status' => 'approved',
            'status_label' => 'Approved',
            'day' => 'Wed',
            'tooltip' => "Project Update\nRequester: Strategy\nAttendees: 12",
            'priority' => 'Project',
        ],
        [
            'facility' => 'Helios Lab',
            'time' => '9:00 AM – 12:00 PM',
            'status' => 'pending',
            'status_label' => 'Pending',
            'day' => 'Thu',
            'tooltip' => "Systems Training\nRequester: Ops\nAttendees: 20",
            'priority' => 'Training',
        ],
        [
            'facility' => 'Summit Hall',
            'time' => '1:00 PM – 5:00 PM',
            'status' => 'approved',
            'status_label' => 'Approved',
            'day' => 'Thu',
            'tooltip' => "Ministry Event\nRequester: External Affairs\nAttendees: 150",
            'priority' => 'Event',
        ],
    ];

    $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

    $listBookings = [
        [
            'title' => 'Leadership Sync',
            'facility' => 'Orion Boardroom',
            'status' => 'approved',
            'status_label' => 'Approved',
            'date' => 'Aug 12',
            'time' => '09:00 – 11:00',
            'duration' => '2h',
            'requester' => 'J. Mercado',
            'owner' => 'Ops • N. Cruz',
            'type' => 'Leadership',
        ],
        [
            'title' => 'Training Session',
            'facility' => 'Helios Lab',
            'status' => 'pending',
            'status_label' => 'Pending',
            'date' => 'Aug 12',
            'time' => '13:00 – 15:00',
            'duration' => '2h',
            'requester' => 'A. Santos',
            'owner' => 'L&D • C. Uy',
            'type' => 'Training',
        ],
        [
            'title' => 'Town Hall',
            'facility' => 'Summit Hall',
            'status' => 'approved',
            'status_label' => 'Approved',
            'date' => 'Aug 13',
            'time' => '10:00 – 16:00',
            'duration' => '6h',
            'requester' => 'Exec Office',
            'owner' => 'Events • L. Garcia',
            'type' => 'Event',
        ],
        [
            'title' => 'Design Sprint',
            'facility' => 'Nova Hub',
            'status' => 'cancelled',
            'status_label' => 'Cancelled',
            'date' => 'Aug 14',
            'time' => '14:00 – 15:30',
            'duration' => '1.5h',
            'requester' => 'Creative Team',
            'owner' => 'Product • K. David',
            'type' => 'Team',
        ],
        [
            'title' => 'Project Update',
            'facility' => 'Orion Boardroom',
            'status' => 'approved',
            'status_label' => 'Approved',
            'date' => 'Aug 14',
            'time' => '16:00 – 18:00',
            'duration' => '2h',
            'requester' => 'Strategy',
            'owner' => 'Ops • D. Cruz',
            'type' => 'Project',
        ],
        [
            'title' => 'Systems Training',
            'facility' => 'Helios Lab',
            'status' => 'pending',
            'status_label' => 'Pending',
            'date' => 'Aug 15',
            'time' => '09:00 – 12:00',
            'duration' => '3h',
            'requester' => 'Ops',
            'owner' => 'L&D • C. Uy',
            'type' => 'Training',
        ],
        [
            'title' => 'Ministry Event',
            'facility' => 'Summit Hall',
            'status' => 'approved',
            'status_label' => 'Approved',
            'date' => 'Aug 15',
            'time' => '13:00 – 17:00',
            'duration' => '4h',
            'requester' => 'External Affairs',
            'owner' => 'Events • L. Garcia',
            'type' => 'Event',
        ],
    ];
@endphp

<section class="admin-calendar-page">
    <div class="admin-calendar-shell">
        <a href="{{ route('admin.hub') }}" class="admin-back-button admin-back-button--light">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to admin hub
        </a>
        <p class="cal-breadcrumb">Admin Hub · Global Calendar</p>
        <div class="cal-header">
            <div>
                <h1>Global Calendar</h1>
                <p>All bookings across ENC facilities in one view.</p>
            </div>
            <div class="cal-controls">
                <div class="cal-view-tabs" id="viewSwitch">
                    <button class="active" data-view="week">Week</button>
                    <button data-view="day">Day</button>
                    <button data-view="month">Month</button>
                </div>
                <button class="cal-btn" id="prevBtn">‹ Prev</button>
                <button class="cal-btn">Today</button>
                <button class="cal-btn" id="nextBtn">Next ›</button>
                <button class="cal-btn cal-btn-primary" id="filterBtn">Filters</button>
                <button class="cal-btn">Export ICS</button>
            </div>
        </div>

        <div class="cal-surface">
            <div class="cal-toolbar">
                <div class="cal-legend">
                    <span><span class="cal-legend-dot" style="background: var(--cal-success)"></span> Approved</span>
                    <span><span class="cal-legend-dot" style="background: var(--cal-info)"></span> Pending</span>
                    <span><span class="cal-legend-dot" style="background: var(--cal-danger)"></span> Cancelled</span>
                    <span><span class="cal-legend-dot" style="background: rgba(116,143,252,0.85)"></span> Recurring</span>
                </div>
                <div class="cal-filter-chips" id="filterChips">
                    <span class="cal-chip">Building A</span>
                    <span class="cal-chip">Meeting Rooms</span>
                </div>
            </div>

            <div class="cal-grid week" id="calendarGrid">
                @foreach ($days as $day)
                    <div class="cal-day-column {{ in_array($day, ['Sat', 'Sun']) ? 'is-weekend' : '' }}" data-day="{{ $day }}">
                        <div class="cal-day-header">
                            <strong>{{ $day }}</strong>
                            <span class="text-muted small">Today</span>
                        </div>
                        <div class="cal-events-stack">
                            @foreach ($bookings as $booking)
                                @if ($booking['day'] === $day)
                                    <div
                                        class="cal-event {{ $booking['status'] }}"
                                        data-tooltip="{{ $booking['tooltip'] }}"
                                        data-booking="{{ $booking['facility'] }}"
                                        data-time="{{ $booking['time'] }}"
                                        data-status="{{ $booking['status_label'] }}"
                                        data-priority="{{ $booking['priority'] }}"
                                    >
                                        <strong>{{ $booking['facility'] }}</strong>
                                        <small>{{ $booking['time'] }}</small>
                                        <small class="text-muted">{{ $booking['status_label'] }}</small>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="cal-secondary">
                <div class="cal-stats">
                    <div class="cal-stat-card">
                        <p class="cal-stat-label">Bookings today</p>
                        <div class="cal-stat-value">18</div>
                        <p class="cal-stat-foot text-muted">+4 vs yesterday</p>
                    </div>
                    <div class="cal-stat-card">
                        <p class="cal-stat-label">Pending approvals</p>
                        <div class="cal-stat-value">6</div>
                        <p class="cal-stat-foot text-muted">2 urgent</p>
                    </div>
                    <div class="cal-stat-card">
                        <p class="cal-stat-label">Utilization</p>
                        <div class="cal-progress">
                            <span style="width: 72%"></span>
                        </div>
                        <p class="cal-stat-foot text-muted">72% of bookable hours</p>
                    </div>
                    <div class="cal-stat-card">
                        <p class="cal-stat-label">Cancellations</p>
                        <div class="cal-stat-value text-warning">2</div>
                        <p class="cal-stat-foot text-muted">This week</p>
                    </div>
                </div>

                <div class="cal-list-bar">
                    <div class="cal-list-filters">
                        <div class="cal-search">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                                <circle cx="11" cy="11" r="7"/>
                                <path d="M21 21l-4.35-4.35"/>
                            </svg>
                            <input type="search" id="bookingSearch" placeholder="Search by title, room, requester...">
                        </div>
                        <div class="cal-toggle-group">
                            <label class="cal-toggle">
                                <input type="checkbox" id="mineToggle">
                                <span>Only mine</span>
                            </label>
                            <label class="cal-toggle">
                                <input type="checkbox" id="weekendsToggle" checked>
                                <span>Show weekends</span>
                            </label>
                        </div>
                    </div>
                    <div class="cal-actions">
                        <button class="cal-btn">Block time</button>
                        <button class="cal-btn cal-btn-primary">New booking</button>
                    </div>
                </div>

                <div class="cal-list-shell">
                    <div class="cal-list-header">
                        <div>
                            <h3>Upcoming bookings</h3>
                            <p class="text-muted small mb-0">Sorted by soonest start · Inline actions for quick response</p>
                        </div>
                        <div class="cal-chip-row">
                            <span class="cal-chip" data-filter-type="status" data-filter-value="approved">Approved</span>
                            <span class="cal-chip" data-filter-type="status" data-filter-value="pending">Pending</span>
                            <span class="cal-chip" data-filter-type="status" data-filter-value="cancelled">Cancelled</span>
                            <span class="cal-chip" data-filter-type="type" data-filter-value="Training">Training</span>
                            <span class="cal-chip" data-filter-type="type" data-filter-value="Event">Event</span>
                        </div>
                    </div>

                    <div class="cal-table" id="bookingTable">
                        <div class="cal-table-head">
                            <span>Title</span>
                            <span>Facility</span>
                            <span>Status</span>
                            <span>When</span>
                            <span>Duration</span>
                            <span>Requester</span>
                            <span>Owner</span>
                            <span class="text-end">Action</span>
                        </div>
                        @foreach ($listBookings as $list)
                            <div class="cal-table-row"
                                data-status="{{ strtolower($list['status']) }}"
                                data-type="{{ $list['type'] }}"
                                data-owner="{{ $list['owner'] }}"
                            >
                                <span>
                                    <div class="cal-table-title">{{ $list['title'] }}</div>
                                    <div class="text-muted small">{{ $list['type'] }}</div>
                                </span>
                                <span>{{ $list['facility'] }}</span>
                                <span><span class="cal-status {{ strtolower($list['status']) }}">{{ $list['status_label'] }}</span></span>
                                <span>{{ $list['date'] }} · {{ $list['time'] }}</span>
                                <span>{{ $list['duration'] }}</span>
                                <span>{{ $list['requester'] }}</span>
                                <span>{{ $list['owner'] }}</span>
                                <span class="text-end">
                                    <button class="cal-link">Open</button>
                                    <button class="cal-link cal-link-muted">Resched</button>
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="cal-overlay" id="drawerOverlay"></div>
<aside class="cal-drawer" id="bookingDrawer" aria-hidden="true">
    <header>
        <div>
            <h3 id="drawerTitle">Booking Title</h3>
            <p class="text-muted small mb-0" id="drawerSubtitle">Room • Status</p>
        </div>
        <button class="cal-btn" id="closeDrawer">×</button>
    </header>
    <div class="cal-drawer-section">
        <h4>When</h4>
        <p id="drawerWhen">Aug 16 · 9:00 AM – 11:00 AM · Weekly</p>
    </div>
    <div class="cal-drawer-section">
        <h4>Who</h4>
        <p id="drawerWho">Requester: J. Mercado · Leadership</p>
    </div>
    <div class="cal-drawer-section">
        <h4>Purpose & Manpower</h4>
        <p id="drawerPurpose">Leadership Sync · Attendees: 14</p>
    </div>
    <div class="cal-drawer-section">
        <h4>Policies triggered</h4>
        <ul class="text-muted small mb-0" id="drawerPolicies">
            <li>Lead time satisfied</li>
            <li>No food allowed</li>
        </ul>
    </div>
    <div class="cal-drawer-actions">
        <button class="cal-btn">View Details</button>
        <button class="cal-btn cal-btn-primary">Open Approvals</button>
    </div>
</aside>

{{-- Filters Modal --}}
<div class="pol-modal-overlay" id="calendarFilters">
    <div class="pol-modal" style="max-width: 520px;">
        <header>
            <h3>Calendar Filters</h3>
            <button class="pol-action-btn" data-modal-close>&times;</button>
        </header>
        <form class="pol-form">
            <div class="pol-field">
                <label>Building</label>
                <select>
                    <option>All buildings</option>
                    <option>Building A</option>
                    <option>Building B</option>
                </select>
            </div>
            <div class="pol-field">
                <label>Facility type</label>
                <select>
                    <option>All types</option>
                    <option>Meeting Rooms</option>
                    <option>Training Rooms</option>
                    <option>Event Halls</option>
                </select>
            </div>
            <div class="pol-field">
                <label>Status</label>
                <select>
                    <option>All status</option>
                    <option>Approved</option>
                    <option>Pending</option>
                    <option>Cancelled</option>
                </select>
            </div>
            <div class="pol-field">
                <label>Department</label>
                <select>
                    <option>All departments</option>
                    <option>Creative</option>
                    <option>Operations</option>
                    <option>Admin Office</option>
                </select>
            </div>
            <div class="pol-modal-actions">
                <button type="button" class="pol-btn" data-modal-close>Cancel</button>
                <button type="submit" class="pol-btn pol-btn-primary">Apply filters</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const drawer = document.getElementById('bookingDrawer');
        const drawerOverlay = document.getElementById('drawerOverlay');
        const closeDrawerBtn = document.getElementById('closeDrawer');
        const events = document.querySelectorAll('.cal-event');
        const filterModal = document.getElementById('calendarFilters');
        const filterBtn = document.getElementById('filterBtn');
        const viewSwitch = document.getElementById('viewSwitch');
        const calendarGrid = document.getElementById('calendarGrid');
        const bookingSearch = document.getElementById('bookingSearch');
        const bookingRows = Array.from(document.querySelectorAll('.cal-table-row'));
        const chips = Array.from(document.querySelectorAll('.cal-chip[data-filter-type]'));
        const mineToggle = document.getElementById('mineToggle');
        const weekendsToggle = document.getElementById('weekendsToggle');

        const openDrawer = (data) => {
            drawer.classList.add('open');
            drawerOverlay.classList.add('open');
            drawer.setAttribute('aria-hidden', 'false');
            document.getElementById('drawerTitle').textContent = data.facility;
            document.getElementById('drawerSubtitle').textContent = `${data.status} • ${data.time}`;
            document.getElementById('drawerWhen').textContent = data.time;
            document.getElementById('drawerWho').textContent = `Requester: ${data.requester || '—'}`;
            document.getElementById('drawerPurpose').textContent = `${data.purpose || 'Huddle'} · Attendees: ${data.attendees || '—'}`;
        };

        const closeDrawer = () => {
            drawer.classList.remove('open');
            drawerOverlay.classList.remove('open');
            drawer.setAttribute('aria-hidden', 'true');
        };

        events.forEach(event => {
            event.addEventListener('click', () => {
                openDrawer({
                    facility: event.dataset.booking,
                    time: event.dataset.time,
                    status: event.dataset.status,
                    requester: 'A. Santos',
                    purpose: event.dataset.priority,
                    attendees: Math.floor(Math.random() * 20) + 5,
                });
            });
        });

        drawerOverlay.addEventListener('click', closeDrawer);
        closeDrawerBtn.addEventListener('click', closeDrawer);

        filterBtn.addEventListener('click', () => filterModal.classList.add('active'));
        filterModal.addEventListener('click', e => {
            if (e.target === filterModal || e.target.hasAttribute('data-modal-close')) {
                filterModal.classList.remove('active');
            }
        });

        viewSwitch.querySelectorAll('button').forEach(btn => {
            btn.addEventListener('click', () => {
                viewSwitch.querySelectorAll('button').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                const view = btn.dataset.view;
                calendarGrid.className = 'cal-grid ' + view;
            });
        });

        const state = {
            status: null,
            type: null,
            mineOnly: false,
            showWeekends: true,
            search: '',
        };

        const filterRows = () => {
            bookingRows.forEach(row => {
                const matchesStatus = state.status ? row.dataset.status === state.status : true;
                const matchesType = state.type ? row.dataset.type === state.type : true;
                const matchesMine = state.mineOnly ? row.dataset.owner.toLowerCase().includes('ops') : true;
                const text = row.textContent.toLowerCase();
                const matchesSearch = state.search ? text.includes(state.search) : true;
                const visible = matchesStatus && matchesType && matchesMine && matchesSearch;
                row.style.display = visible ? 'grid' : 'none';
            });
        };

        const toggleChip = (chip) => {
            const type = chip.dataset.filterType;
            const value = chip.dataset.filterValue.toLowerCase();
            const isActive = chip.classList.contains('active');
            chips
                .filter(c => c.dataset.filterType === type)
                .forEach(c => c.classList.remove('active'));
            if (!isActive) {
                chip.classList.add('active');
                state[type] = value;
            } else {
                state[type] = null;
            }
            filterRows();
        };

        chips.forEach(chip => chip.addEventListener('click', () => toggleChip(chip)));

        bookingSearch?.addEventListener('input', (e) => {
            state.search = e.target.value.trim().toLowerCase();
            filterRows();
        });

        mineToggle?.addEventListener('change', (e) => {
            state.mineOnly = e.target.checked;
            filterRows();
        });

        const handleWeekends = (show) => {
            document.querySelectorAll('.cal-day-column.is-weekend').forEach(col => {
                col.style.display = show ? 'flex' : 'none';
            });
        };

        weekendsToggle?.addEventListener('change', (e) => {
            state.showWeekends = e.target.checked;
            handleWeekends(state.showWeekends);
        });

        handleWeekends(state.showWeekends);
        filterRows();
    });
</script>
@endpush
@endsection
