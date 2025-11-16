@extends('layouts.app')

@section('title', 'Admin Hub')

@push('styles')
    @vite([
        'resources/css/wizard/base.css',
        'resources/css/admin/dashboard.css',
        'resources/css/admin/hub.css',
    ])
@endpush

@php
    $user = auth()->user();
    $automations = [
        ['title' => 'Auto-confirm low-risk bookings', 'detail' => 'Enabled for rooms under 20 pax', 'status' => 'Running'],
        ['title' => 'Nightly backup reminders', 'detail' => 'Facilities team 9:00 PM', 'status' => 'Scheduled'],
        ['title' => 'Incident escalation', 'detail' => 'Escalate if unresolved in 2 hrs', 'status' => 'Monitoring'],
    ];

    $notifications = [
        ['title' => 'Policy draft ready', 'detail' => 'Equipment checkout rules, review by Dec 4'],
        ['title' => 'Vendor onsite', 'detail' => 'AC maintenance · Multipurpose Hall · 3:00 PM'],
        ['title' => 'Training reminder', 'detail' => 'Facilities safety orientation · tomorrow 9:00 AM'],
    ];

    $checklist = [
        ['task' => 'Upload December facility photos', 'status' => 'In progress'],
        ['task' => 'Confirm catering partners', 'status' => 'Pending'],
        ['task' => 'Reconcile incident log', 'status' => 'In progress'],
    ];
@endphp

@section('content')
    @include('partials.dashboard-navbar', [
        'currentStep' => 0,
        'steps' => [],
        'bookingsCount' => 0,
        'notificationsCount' => 4,
        'userName' => $user?->name ?? 'Administrator',
        'userEmail' => $user?->email ?? 'admin@enc.gov',
        'userRole' => 'ADMIN',
        'brand' => 'ONE Services',
        'showBookingsToggle' => false,
        'showStepper' => false,
    ])

    <section class="hub-shell">
        <header class="hub-hero">
            <div>
                <span class="admin-hero-badge">Admin hub</span>
                <h1 class="hub-hero-title">Shared Services control tower</h1>
                <p class="hub-hero-subtitle">Launch admin tools, monitor approvals, and view campus-wide bookings from a single cockpit.</p>
                <div class="hub-hero-cta">
                    <a href="{{ route('admin.dashboard') }}" class="btn-pill btn-pill--light">View dashboard</a>
                    <a href="{{ route('admin.analytics') }}" class="btn-pill">Analytics</a>
                </div>
            </div>
            <div class="hub-chart-card">
                <p class="mb-1">Live request load</p>
                <p class="h4 mb-3">27 open approvals</p>
                <div class="chart-placeholder">Chart placeholder</div>
            </div>
        </header>

        <div class="hub-grid">
            <div class="hub-grid-card">
                <h3>Staff on shift</h3>
                <p class="text-muted">Logistics · Facilities · SFI</p>
                <ul class="admin-approvals-list">
                    <li>
                        <div>
                            <strong>Operations Desk</strong>
                            <p class="text-muted small mb-0">3 approvers · SLA 45 mins</p>
                        </div>
                        <span class="admin-tag is-success">Online</span>
                    </li>
                    <li>
                        <div>
                            <strong>SFI Support</strong>
                            <p class="text-muted small mb-0">2 coordinators</p>
                        </div>
                        <span class="admin-tag is-info">On call</span>
                    </li>
                </ul>
            </div>
            <div class="hub-grid-card">
                <h3>Incident board</h3>
                <p class="text-muted">Facilities requiring attention.</p>
                <ul class="admin-approvals-list">
                    <li>
                        <div>
                            <strong>Studio B · Lighting rack</strong>
                            <p class="text-muted small mb-0">Repair scheduled at 6:00 PM</p>
                        </div>
                        <span class="admin-tag is-warning">Maintenance</span>
                    </li>
                    <li>
                        <div>
                            <strong>Storage Wing · Access control</strong>
                            <p class="text-muted small mb-0">Awaiting vendor update</p>
                        </div>
                        <span class="admin-tag is-info">Monitoring</span>
                    </li>
                </ul>
            </div>
            <div class="hub-grid-card">
                <h3>Policy updates</h3>
                <p class="text-muted">Latest announcements for teams.</p>
                <ul class="admin-approvals-list">
                    <li>
                        <div>
                            <strong>Equipment checkout</strong>
                            <p class="text-muted small mb-0">Effective Dec 1 · Logistics</p>
                        </div>
                        <span class="admin-tag is-info">New</span>
                    </li>
                    <li>
                        <div>
                            <strong>Room prep SLA</strong>
                            <p class="text-muted small mb-0">Operations · 30 min buffer</p>
                        </div>
                        <span class="admin-tag is-success">Live</span>
                    </li>
                </ul>
            </div>
            <div class="hub-grid-card">
                <h3>SLA tracker</h3>
                <p class="text-muted">Average response and completion.</p>
                <div class="chart-placeholder">SLA chart placeholder</div>
                <p class="mt-3 mb-0"><strong>42 mins</strong> avg response · <strong>1h 55m</strong> completion</p>
            </div>
        </div>

        <article class="hub-approval-panel">
            <div class="approval-header">
                <div>
                    <h2>Automations & reminders</h2>
                    <p class="text-muted mb-0">Compact view of scheduled workflows.</p>
                </div>
                <a href="{{ route('admin.analytics') }}" class="btn btn-outline-primary btn-sm">View workflows</a>
            </div>
            <ul class="approval-list">
                @foreach ($automations as $automation)
                    <li class="approval-item">
                        <div>
                            <strong>{{ $automation['title'] }}</strong>
                            <p class="text-muted small mb-0">{{ $automation['detail'] }}</p>
                        </div>
                        <span class="admin-tag is-info">{{ $automation['status'] }}</span>
                    </li>
                @endforeach
            </ul>
        </article>

        <div class="hub-grid">
            <div class="rooms-card">
                <h2>Notifications feed</h2>
                <ul class="rooms-list">
                    @foreach ($notifications as $note)
                        <li>
                            <div>
                                <strong>{{ $note['title'] }}</strong>
                                <p class="text-muted small mb-0">{{ $note['detail'] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="rooms-card">
                <h2>Resource checklist</h2>
                <ul class="rooms-list">
                    @foreach ($checklist as $item)
                        <li>
                            <div>
                                <strong>{{ $item['task'] }}</strong>
                                <p class="text-muted small mb-0">{{ $item['status'] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <section class="admin-card">
            <h3>Admin tools</h3>
            <p class="text-muted">All administrative controls and modules live here.</p>
            <div class="tool-matrix">
                <a href="{{ route('admin.users') }}" class="tool-card">
                    <span class="tool-card-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M17 21v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="1.6"/><path d="M17 11l2 2 4-4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                    </span>
                    <div>
                        <h4>User directory</h4>
                        <p class="tool-card-meta">Manage accounts, roles, access</p>
                    </div>
                </a>
                <a href="{{ route('admin.facilities') }}" class="tool-card">
                    <span class="tool-card-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><rect x="3" y="5" width="18" height="14" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="M3 10h18" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><path d="M8 2v4M16 2v4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                    </span>
                    <div>
                        <h4>Facilities board</h4>
                        <p class="tool-card-meta">Maintenance, photos, availability</p>
                    </div>
                </a>
                <a href="{{ route('admin.analytics') }}" class="tool-card">
                    <span class="tool-card-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M4 20V10" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><path d="M10 20V4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><path d="M16 20v-6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><path d="M22 20V8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                    </span>
                    <div>
                        <h4>Analytics & reports</h4>
                        <p class="tool-card-meta">Utilization, wait times, SLA</p>
                    </div>
                </a>
                <a href="{{ route('admin.calendar') }}" class="tool-card">
                    <span class="tool-card-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><rect x="3" y="4" width="18" height="17" rx="3" stroke="currentColor" stroke-width="1.6"/><path d="M3 10h18" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><path d="M8 2v4M16 2v4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                    </span>
                    <div>
                        <h4>Global calendar</h4>
                        <p class="tool-card-meta">Cross-campus bookings</p>
                    </div>
                </a>
                <a href="{{ route('admin.policies') }}" class="tool-card">
                    <span class="tool-card-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M6 2h9l3 3v15a2 2 0 01-2 2H6a2 2 0 01-2-2V4a2 2 0 012-2z" stroke="currentColor" stroke-width="1.6"/><path d="M9 8h6M9 12h4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                    </span>
                    <div>
                        <h4>Policies</h4>
                        <p class="tool-card-meta">Publish guidelines & SOPs</p>
                    </div>
                </a>
                <a href="{{ route('admin.audit') }}" class="tool-card">
                    <span class="tool-card-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M5 21h14v-2a7 7 0 00-14 0v2z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><path d="M12 13a5 5 0 100-10 5 5 0 000 10z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><path d="M17 11l4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                    </span>
                    <div>
                        <h4>Audit log</h4>
                        <p class="tool-card-meta">Trace approvals & changes</p>
                    </div>
                </a>
            </div>
        </section>
    </section>
@endsection
