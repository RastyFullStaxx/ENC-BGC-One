@extends('layouts.app')

@section('title', 'Admin • Global Calendar')

@push('styles')
    @vite(['resources/css/admin/calendar.css'])
@endpush

@section('content')
<section class="admin-calendar-page" data-week-start="{{ $stats['week_start'] ?? now()->toDateString() }}" data-prev="{{ $stats['prev_start'] ?? '' }}" data-next="{{ $stats['next_start'] ?? '' }}">
    <div class="admin-calendar-shell">
        <a href="{{ route('admin.hub') }}" class="admin-back-button admin-back-button--light">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to admin hub
        </a>
        <p class="cal-breadcrumb">Admin Hub · Global Calendar · {{ $stats['week_range'] ?? '' }} ({{ $stats['timezone'] ?? 'Asia/Manila' }})</p>
        <div class="cal-header">
            <div>
                <h1>Global Calendar</h1>
                <p>All bookings across ENC facilities in one view. Live week view pulls from Booking records with facility + requester context.</p>
            </div>
            <div class="cal-controls">
                <div class="cal-view-tabs" id="viewSwitch">
                    <button class="active" data-view="week">Week</button>
                    <button data-view="day">Day</button>
                    <button data-view="month">Month</button>
                </div>
                <button class="cal-btn" id="prevBtn">‹ Prev</button>
                <button class="cal-btn" id="todayBtn">Today</button>
                <button class="cal-btn" id="nextBtn">Next ›</button>
                <button class="cal-btn cal-btn-primary" id="filterBtn">Filters</button>
                <a class="cal-btn" href="{{ route('admin.calendar.export.ics') }}">Export ICS</a>
            </div>
        </div>

        <div class="cal-surface">
            <div class="cal-toolbar">
                <div class="cal-legend">
                    <span><span class="cal-legend-dot" style="background: var(--cal-success)"></span> Approved</span>
                    <span><span class="cal-legend-dot" style="background: var(--cal-info)"></span> Pending</span>
                    <span><span class="cal-legend-dot" style="background: var(--cal-danger)"></span> Cancelled</span>
                    <span><span class="cal-legend-dot" style="background: var(--cal-warning)"></span> Blocked</span>
                    <span><span class="cal-legend-dot" style="background: rgba(116,143,252,0.85)"></span> Recurring</span>
                </div>
                <div class="cal-filter-chips" id="filterChips">
                    @foreach ($appliedFilters as $f)
                        <span class="cal-chip active">{{ $f }}</span>
                    @endforeach
                </div>
            </div>

            <div class="cal-grid week" id="calendarGrid">
                @foreach ($calendarDays as $day)
                    @php
                        $dayKey = $day->format('D');
                        $dayEvents = $eventsByDay[$dayKey] ?? collect();
                    @endphp
                    <div class="cal-day-column {{ $day->isWeekend() ? 'is-weekend' : '' }}" data-day="{{ $dayKey }}">
                        <div class="cal-day-header">
                            <strong>{{ $day->format('D') }}</strong>
                            <span class="text-muted small">{{ $day->isToday() ? 'Today' : $day->format('M j') }}</span>
                        </div>
                        <div class="cal-events-stack">
                            @forelse ($dayEvents as $booking)
                                <div
                                    class="cal-event {{ $booking['status'] }}"
                                    data-id="{{ $booking['id'] }}"
                                    data-tooltip="{{ $booking['tooltip'] }}"
                                    data-booking="{{ $booking['facility'] }}"
                                    data-time="{{ $booking['slot'] }}"
                                    data-status="{{ $booking['status_label'] }}"
                                    data-priority="{{ $booking['priority'] }}"
                                >
                                    <strong>{{ $booking['facility'] }}</strong>
                                    <small>{{ $booking['slot'] }}</small>
                                    <small class="text-muted">{{ $booking['status_label'] }}</small>
                                </div>
                            @empty
                                <div class="cal-empty text-muted small">No bookings</div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="cal-secondary">
                <div class="cal-stats">
                    <div class="cal-stat-card">
                        <p class="cal-stat-label">Bookings today</p>
                        <div class="cal-stat-value">{{ $stats['today'] ?? 0 }}</div>
                        <p class="cal-stat-foot text-muted">Live from booking table</p>
                    </div>
                    <div class="cal-stat-card">
                        <p class="cal-stat-label">Pending approvals</p>
                        <div class="cal-stat-value">{{ $stats['pending'] ?? 0 }}</div>
                        <p class="cal-stat-foot text-muted">Awaiting decision</p>
                    </div>
                    <div class="cal-stat-card">
                        <p class="cal-stat-label">Utilization</p>
                        <div class="cal-progress">
                            <span style="width: {{ $stats['utilization'] ?? 0 }}%"></span>
                        </div>
                        <p class="cal-stat-foot text-muted">{{ $stats['utilization'] ?? 0 }}% of 8h/day baseline</p>
                    </div>
                    <div class="cal-stat-card">
                        <p class="cal-stat-label">Cancellations</p>
                        <div class="cal-stat-value text-warning">{{ $stats['cancelled'] ?? 0 }}</div>
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
                        <button class="cal-btn" id="blockBtn">Block time</button>
                        <a class="cal-btn cal-btn-primary" href="{{ route('booking.wizard') }}">New booking</a>
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
                            <span class="cal-chip" data-filter-type="status" data-filter-value="blocked">Blocked</span>
                            <span class="cal-chip" data-filter-type="type" data-filter-value="Training">Training</span>
                            <span class="cal-chip" data-filter-type="type" data-filter-value="Event">Event</span>
                        </div>
                    </div>

                    <div class="cal-note">
                        <strong>Quick guide</strong>
                        <ul>
                            <li>Click any block to open the drawer with requester + purpose. Use chips/search to focus on status or type.</li>
                            <li>“Block time” sets a hold (no services) so teams can reserve without approvals; select room + start/end, add note, save.</li>
                            <li>Need speed? Use “Only mine” to see your queue, hide weekends for density, and tap “Resched” for quick follow-up.</li>
                        </ul>
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
                                data-id="{{ $list['id'] }}"
                                data-status="{{ strtolower($list['status']) }}"
                                data-type="{{ strtolower($list['type']) }}"
                                data-owner="{{ strtolower($list['owner']) }}"
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
                                    <a class="cal-link" href="{{ route('admin.approvals.show', $list['id']) }}">Open</a>
                                    <a class="cal-link cal-link-muted" href="{{ route('admin.approvals.show', $list['id']) }}?reschedule=1">Resched</a>
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
        <a class="cal-btn" id="drawerView" href="#">View Details</a>
        <a class="cal-btn cal-btn-primary" id="drawerApproval" href="#">Open Approvals</a>
    </div>
</aside>

{{-- Filters Modal --}}
<div class="pol-modal-overlay" id="calendarFilters">
    <div class="pol-modal" style="max-width: 520px;">
        <header>
            <h3>Calendar Filters</h3>
            <button class="pol-action-btn" data-modal-close>&times;</button>
        </header>
        <form class="pol-form" method="GET" action="{{ route('admin.calendar') }}">
            <div class="pol-field">
                <label>Building</label>
                <select name="building_id">
                    <option value="">All buildings</option>
                    @foreach ($buildings as $building)
                        <option value="{{ $building->id }}" @selected($filters['building_id'] == $building->id)>{{ $building->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="pol-field">
                <label>Facility type</label>
                <select name="type">
                    <option value="">All types</option>
                    @foreach ($facilityTypes as $type)
                        <option value="{{ $type }}" @selected($filters['type'] === $type)>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="pol-field">
                <label>Status</label>
                <select name="status">
                    <option value="">All status</option>
                    <option value="approved" @selected($filters['status'] === 'approved')>Approved</option>
                    <option value="pending" @selected($filters['status'] === 'pending')>Pending</option>
                    <option value="cancelled" @selected($filters['status'] === 'cancelled')>Cancelled</option>
                    <option value="blocked" @selected($filters['status'] === 'blocked')>Blocked</option>
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
        const blockBtn = document.getElementById('blockBtn');
        const facilities = @json($facilities ?? []);
        const blockEndpoint = @json(route('admin.calendar.block'));
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const drawerView = document.getElementById('drawerView');
        const drawerApproval = document.getElementById('drawerApproval');
        const approvalRouteTemplate = @json(route('admin.approvals.show', ['booking' => '__ID__']));
        const shell = document.querySelector('.admin-calendar-page');

        const openDrawer = (data) => {
            drawer.classList.add('open');
            drawerOverlay.classList.add('open');
            drawer.setAttribute('aria-hidden', 'false');
            document.getElementById('drawerTitle').textContent = data.facility;
            document.getElementById('drawerSubtitle').textContent = `${data.status} • ${data.time}`;
            document.getElementById('drawerWhen').textContent = data.time;
            document.getElementById('drawerWho').textContent = `Requester: ${data.requester || '—'}`;
            document.getElementById('drawerPurpose').textContent = `${data.purpose || 'Huddle'} · Attendees: ${data.attendees || '—'}`;
            if (drawerView) {
                drawerView.href = approvalRouteTemplate.replace('__ID__', data.id);
            }
            if (drawerApproval) {
                drawerApproval.href = approvalRouteTemplate.replace('__ID__', data.id);
            }
        };

        const closeDrawer = () => {
            drawer.classList.remove('open');
            drawerOverlay.classList.remove('open');
            drawer.setAttribute('aria-hidden', 'true');
        };

        events.forEach(event => {
            event.addEventListener('click', () => {
                openDrawer({
                    id: event.dataset.id,
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

        const navigateWeek = (targetDate) => {
            const url = new URL(window.location.href);
            url.searchParams.set('start', targetDate);
            window.location.href = url.toString();
        };

        const currentWeekStart = shell?.dataset.weekStart;
        const prevStart = shell?.dataset.prev;
        const nextStart = shell?.dataset.next;

        document.getElementById('prevBtn')?.addEventListener('click', () => prevStart && navigateWeek(prevStart));
        document.getElementById('nextBtn')?.addEventListener('click', () => nextStart && navigateWeek(nextStart));
        document.getElementById('todayBtn')?.addEventListener('click', () => currentWeekStart && navigateWeek(currentWeekStart));

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
                const matchesMine = state.mineOnly ? /admin|ops|me/.test(row.dataset.owner || '') : true;
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

        blockBtn?.addEventListener('click', () => {
            const facilityOptions = facilities.map(f => `<option value="${f.id}">${f.name}</option>`).join('');
            const today = new Date().toISOString().split('T')[0];
            Swal.fire({
                title: 'Block time',
                html: `
                    <div class="block-modal-form">
                        <label>Facility</label>
                        <select id="blockFacility" class="swal2-select block-field">${facilityOptions}</select>
                        <div class="block-row">
                            <div class="block-field-group">
                                <label>Date</label>
                                <input id="blockDate" type="date" class="swal2-input block-field" value="${today}">
                            </div>
                            <div class="block-field-group">
                                <label>Start</label>
                                <input id="blockStart" type="time" class="swal2-input block-field">
                            </div>
                            <div class="block-field-group">
                                <label>End</label>
                                <input id="blockEnd" type="time" class="swal2-input block-field">
                            </div>
                        </div>
                        <label>Note</label>
                        <input id="blockNote" class="swal2-input block-field" placeholder="e.g., Deep clean / maintenance window">
                        <p class="small block-helper">Blocks are holds without services or approvals. Cancel to free the slot.</p>
                    </div>
                `,
                confirmButtonText: 'Save block',
                cancelButtonText: 'Cancel',
                showCancelButton: true,
                customClass: { popup: 'block-modal', container: 'block-modal-container' },
                focusConfirm: false,
                preConfirm: () => {
                    const facility_id = document.getElementById('blockFacility').value;
                    const date = document.getElementById('blockDate').value;
                    const start_at = document.getElementById('blockStart').value;
                    const end_at = document.getElementById('blockEnd').value;
                    const note = document.getElementById('blockNote').value;

                    if (!facility_id || !date || !start_at || !end_at) {
                        Swal.showValidationMessage('Facility, date, start, and end are required.');
                        return false;
                    }

                    return fetch(blockEndpoint, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                        },
                        body: JSON.stringify({ facility_id, date, start_at, end_at, note }),
                    }).then(async (res) => {
                        if (!res.ok) {
                            const error = await res.json().catch(() => ({}));
                            throw new Error(error.message || 'Could not save block');
                        }
                        return res.json();
                    }).catch(err => {
                        Swal.showValidationMessage(err.message);
                    });
                },
            }).then(result => {
                if (result.isConfirmed) {
                    Swal.fire('Saved', 'Blocked time was added. Refresh to see it in the grid.', 'success');
                }
            });
        });

        handleWeekends(state.showWeekends);
        filterRows();
    });
</script>
@endpush
@endsection
