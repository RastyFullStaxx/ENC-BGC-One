@extends('layouts.app')

@section('title', 'Admin • Audit Log')

@push('styles')
    @vite(['resources/css/admin/audit.css'])
@endpush

@section('content')
<a href="{{ route('admin.dashboard') }}" class="admin-back-link">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
    </svg>
    Back to admin dashboard
</a>
@php
    $entries = [
        [
            'timestamp' => 'Aug 15, 2024 · 2:41 PM',
            'actor' => 'Ava Santos',
            'email' => 'ava.santos@enc.gov',
            'action' => 'Updated facility capacity',
            'module' => 'Facilities',
            'target' => 'Orion Boardroom',
            'before' => ['capacity' => 12],
            'after' => ['capacity' => 16],
            'icon' => '',
        ],
        [
            'timestamp' => 'Aug 15, 2024 · 1:10 PM',
            'actor' => 'Brian Lopez',
            'email' => 'brian.lopez@enc.gov',
            'action' => 'Approved booking BKG-8742',
            'module' => 'Bookings',
            'target' => 'BKG-8742',
            'before' => ['status' => 'Pending'],
            'after' => ['status' => 'Approved'],
            'icon' => '',
        ],
        [
            'timestamp' => 'Aug 14, 2024 · 6:02 PM',
            'actor' => 'Cheska Lim',
            'email' => 'cheska.lim@enc.gov',
            'action' => 'Created new rule “No Food · Labs”',
            'module' => 'Policies',
            'target' => 'Rule #452',
            'before' => ['rule' => null],
            'after' => ['rule' => 'No food allowed'],
            'icon' => '',
        ],
        [
            'timestamp' => 'Aug 14, 2024 · 5:10 PM',
            'actor' => 'Diego Ramos',
            'email' => 'diego.ramos@enc.gov',
            'action' => 'Deactivated user guest.torres@enc.gov',
            'module' => 'Users',
            'target' => 'guest.torres@enc.gov',
            'before' => ['active' => true],
            'after' => ['active' => false],
            'icon' => '',
        ],
        [
            'timestamp' => 'Aug 13, 2024 · 10:45 AM',
            'actor' => 'System Automation',
            'email' => 'system@enc.gov',
            'action' => 'Logged policy violation alert',
            'module' => 'Approvals',
            'target' => 'BKG-8670',
            'before' => ['status' => 'Pending'],
            'after' => ['status' => 'Flagged'],
            'icon' => '',
        ],
    ];
@endphp

<section class="admin-audit-page">
    <div class="admin-audit-shell">
        <p class="audit-breadcrumb">Admin Hub · Audit Log</p>
        <div class="audit-header">
            <div>
                <h1>Audit Log</h1>
                <p>A record of actions performed across the system.</p>
            </div>
            <div class="audit-controls">
                <button class="audit-btn">Download CSV</button>
                <button class="audit-btn">Download PDF</button>
            </div>
        </div>

        <div class="audit-surface">
            <div class="audit-filters">
                <input type="search" class="audit-btn" id="auditSearch" placeholder="Search actor, action, module">
                <button class="audit-chip active" data-filter-module="all">All modules</button>
                <button class="audit-chip" data-filter-module="Bookings">Bookings</button>
                <button class="audit-chip" data-filter-module="Facilities">Facilities</button>
                <button class="audit-chip" data-filter-module="Users">Users</button>
                <button class="audit-chip" data-filter-module="Policies">Policies</button>
                <button class="audit-chip" data-filter-module="Approvals">Approvals</button>
                <button class="audit-btn" id="dateRangeBtn">Date Range</button>
            </div>

            <div class="audit-table-wrapper">
                <table class="audit-table" id="auditTable">
                    <thead>
                        <tr>
                            <th>Timestamp</th>
                            <th>Actor</th>
                            <th>Action</th>
                            <th>Module</th>
                            <th>Target</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($entries as $entry)
                            <tr
                                data-module="{{ $entry['module'] }}"
                                data-actor="{{ strtolower($entry['actor']) }}"
                                data-action="{{ strtolower($entry['action']) }}"
                                data-detail='@json($entry)'
                            >
                                <td>{{ $entry['timestamp'] }}</td>
                                <td>
                                    <div class="audit-actor">
                                        <span class="audit-avatar">{{ strtoupper(substr($entry['actor'], 0, 1)) }}</span>
                                        <div>
                                            <strong>{{ $entry['actor'] }}</strong>
                                            <p class="text-muted small mb-0">{{ $entry['email'] }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $entry['icon'] }} {{ $entry['action'] }}</td>
                                <td><span class="audit-module-chip">{{ $entry['module'] }}</span></td>
                                <td class="text-muted">{{ $entry['target'] }}</td>
                                <td>
                                    <button class="audit-btn" data-detail-btn>View details</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<div class="audit-overlay" id="auditOverlay"></div>
<aside class="audit-drawer" id="auditDrawer" aria-hidden="true">
    <header>
        <div>
            <h3 id="drawerTitle">Action Summary</h3>
            <p class="text-muted small mb-0" id="drawerSubtitle">Module • Timestamp</p>
        </div>
        <button class="audit-btn" id="closeAuditDrawer">×</button>
    </header>
    <div class="audit-drawer-section">
        <h4>Actor</h4>
        <p id="drawerActor">—</p>
    </div>
    <div class="audit-drawer-section">
        <h4>Action</h4>
        <p id="drawerAction">—</p>
    </div>
    <div class="audit-drawer-section">
        <h4>Entity</h4>
        <p id="drawerEntity">—</p>
    </div>
    <div class="audit-drawer-section">
        <h4>Before</h4>
        <pre class="audit-diff" id="drawerBefore"><code>—</code></pre>
    </div>
    <div class="audit-drawer-section">
        <h4>After</h4>
        <pre class="audit-diff" id="drawerAfter"><code>—</code></pre>
    </div>
    <div class="audit-drawer-actions">
        <button class="audit-btn">Open Entity</button>
        <button class="audit-btn audit-btn-primary">Export Entry</button>
    </div>
</aside>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const rows = document.querySelectorAll('#auditTable tbody tr');
        const searchInput = document.querySelector('#auditSearch');
        const moduleChips = document.querySelectorAll('[data-filter-module]');
        const drawer = document.querySelector('#auditDrawer');
        const overlay = document.querySelector('#auditOverlay');
        const closeDrawerBtn = document.querySelector('#closeAuditDrawer');

        let activeModule = 'all';

        const openDrawer = (detail) => {
            drawer.classList.add('open');
            overlay.classList.add('open');
            drawer.setAttribute('aria-hidden', 'false');
            document.querySelector('#drawerTitle').textContent = detail.action;
            document.querySelector('#drawerSubtitle').textContent = detail.module + ' • ' + detail.timestamp;
            document.querySelector('#drawerActor').textContent = detail.actor + ' (' + detail.email + ')';
            document.querySelector('#drawerAction').textContent = detail.action;
            document.querySelector('#drawerEntity').textContent = detail.target;
            document.querySelector('#drawerBefore').textContent = JSON.stringify(detail.before, null, 2) || '—';
            document.querySelector('#drawerAfter').textContent = JSON.stringify(detail.after, null, 2) || '—';
        };

        const closeDrawer = () => {
            drawer.classList.remove('open');
            overlay.classList.remove('open');
            drawer.setAttribute('aria-hidden', 'true');
        };

        rows.forEach(row => {
            const detailBtn = row.querySelector('[data-detail-btn]');
            detailBtn.addEventListener('click', () => {
                const detail = JSON.parse(row.dataset.detail);
                openDrawer(detail);
            });
        });

        overlay.addEventListener('click', closeDrawer);
        closeDrawerBtn.addEventListener('click', closeDrawer);

        const filterRows = () => {
            const keyword = searchInput.value.trim().toLowerCase();
            rows.forEach(row => {
                const matchModule = activeModule === 'all' || row.dataset.module === activeModule;
                const matchKeyword = !keyword || row.dataset.actor.includes(keyword) || row.dataset.action.includes(keyword);
                row.style.display = matchModule && matchKeyword ? '' : 'none';
            });
        };

        moduleChips.forEach(chip => {
            chip.addEventListener('click', () => {
                activeModule = chip.dataset.filterModule;
                moduleChips.forEach(c => c.classList.remove('active'));
                chip.classList.add('active');
                filterRows();
            });
        });

        searchInput.addEventListener('input', filterRows);
    });
</script>
@endpush
@endsection
