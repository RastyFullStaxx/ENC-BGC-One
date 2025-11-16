@extends('layouts.app')

@section('title', 'Admin Dashboard')

@push('styles')
    @vite([
        'resources/css/wizard/base.css',
        'resources/css/admin/dashboard.css',
    ])
@endpush

@php
    $user = $user ?? auth()->user();
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

    <section class="admin-shell">
        <div class="admin-top-actions">
            <a href="{{ route('landing') }}" class="admin-back-button">
                &lt; Back to landing page
            </a>
        </div>
        <header class="admin-hero">
            <div class="admin-hero-content">
                <span class="admin-hero-badge">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                        <path d="M10 6.5C10 9 8.25 10.25 6.17 10.975C6.06108 11.0119 5.94277 11.0101 5.835 10.97C3.75 10.25 2 9 2 6.5V3C2 2.86739 2.05268 2.74021 2.14645 2.64645C2.24021 2.55268 2.36739 2.5 2.5 2.5C3.5 2.5 4.75 1.9 5.62 1.14C5.72593 1.0495 5.86068 0.999775 6 0.999775C6.13932 0.999775 6.27407 1.0495 6.38 1.14C7.255 1.905 8.5 2.5 9.5 2.5C9.63261 2.5 9.75979 2.55268 9.85355 2.64645C9.94732 2.74021 10 2.86739 10 3V6.5Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Admin Console
                </span>
                <h1 class="admin-hero-title">Operations control center</h1>
                <p class="admin-hero-subtitle">
                    Monitor approvals, surface incidents, and keep tabs on utilization—everything the shared services team needs in one place.
                </p>
                <div class="admin-hero-actions">
                    <span class="hero-metric-chip">
                        <small>Approvals today</small>
                        <strong>{{ number_format($heroStats['approvalsToday'] ?? 0) }}</strong>
                    </span>
                    <span class="hero-metric-chip">
                        <small>Avg SLA</small>
                        <strong>{{ number_format($heroStats['avgSla'] ?? 0) }} mins</strong>
                    </span>
                    <span class="hero-metric-chip">
                        <small>Incidents</small>
                        <strong>{{ number_format($heroStats['openIncidents'] ?? 0) }} open</strong>
                    </span>
                </div>
            </div>
            <div class="hero-chart-card">
                <p class="mb-1">Booking volume (last 7 days)</p>
                <div class="hero-chart-placeholder">Chart placeholder</div>
            </div>
        </header>

        <div class="admin-highlight">
            <div class="admin-highlight-card admin-highlight--hub">
                <p class="small fw-semibold mb-1">Admin hub</p>
                <h2>Centralized tools & approvals</h2>
                <p class="mb-0">All admin tools, policies, and escalations live here. Launch workflows, delegate tasks, and monitor service health.</p>
                
            </div>
            <div class="admin-highlight-card admin-highlight--hub">
                <p class="small fw-semibold mb-1">Service status</p>
                <p class="mb-1">Facilities operational · last sync 2m ago.</p>
                <a href="{{ route('admin.hub') }}" class="approval-cta admin-highlight-btn">Open hub</a>
            </div>
        </div>

        <div class="admin-stats-grid">
            <div class="admin-stat-card">
                <p class="admin-stat-label">Pending approvals</p>
                <p class="admin-stat-value">{{ number_format($pendingApprovals ?? 0) }}</p>
                <p class="text-muted small mb-0">Across all facilities</p>
            </div>
            <div class="admin-stat-card">
                <p class="admin-stat-label">Today's requests</p>
                <p class="admin-stat-value">{{ number_format($todaysRequests ?? 0) }}</p>
                <p class="text-muted small mb-0">Submitted by staff</p>
            </div>
            <div class="admin-stat-card">
                <p class="admin-stat-label">Active incidents</p>
                <p class="admin-stat-value">{{ number_format($heroStats['openIncidents'] ?? 0) }}</p>
                <p class="text-muted small mb-0">Facilities notified</p>
            </div>
        </div>

        <div class="status-board">
            <div class="status-card">
                <span>Facilities online</span>
                <p class="status-value">{{ $statusBoard['facilitiesOnline'] ?? 0 }} / {{ $statusBoard['totalFacilities'] ?? 0 }}</p>
                <p class="status-note">Storage wing under maintenance</p>
            </div>
            <div class="status-card">
                <span>Approvers on duty</span>
                <p class="status-value">{{ $statusBoard['approversOnDuty'] ?? 0 }}</p>
                <p class="status-note">Operations · Logistics · SFI</p>
            </div>
            <div class="status-card">
                <span>Resolved today</span>
                <p class="status-value">{{ $statusBoard['resolvedToday'] ?? 0 }}</p>
                <p class="status-note">Processing time avg. 48 mins</p>
            </div>
        </div>

        <div class="admin-main-grid">
            <div>
                <article class="approval-spotlight">
                    <div class="approval-spotlight-header">
                        <div>
                            <p class="approval-eyebrow">Queue health</p>
                            <h3>Approval queue</h3>
                            <p class="mb-0">Critical bookings awaiting action right now.</p>
                        </div>
                        <div class="approval-spotlight-meta">
                            <div class="metric-block">
                                <span class="label">Waiting</span>
                                <strong>{{ number_format($heroStats['waiting'] ?? 0) }}</strong>
                            </div>
                            <div class="metric-block">
                                <span class="label">Breaching SLA</span>
                                <strong class="text-warning">{{ number_format($heroStats['breaches'] ?? 0) }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="approval-timeline">
                        @foreach ($approvalsQueue as $item)
                            @php
                                $tone = match($item['status']) {
                                    'approved' => 'is-success',
                                    'rejected' => 'is-danger',
                                    default => 'is-warning',
                                };
                            @endphp
                            <div class="timeline-item">
                                <div class="timeline-indicator {{ $tone }}"></div>
                                <div class="timeline-body">
                                    <div class="timeline-top">
                                        <strong>{{ $item['facility'] }} · {{ $item['title'] }}</strong>
                                        <span class="admin-tag {{ $tone }}">{{ ucfirst($item['status']) }}</span>
                                    </div>
                                    <p class="timeline-meta mb-0">{{ $item['team'] }} · {{ $item['date'] }} · {{ $item['priority'] }} priority</p>
                                <div class="timeline-actions">
                                    <a href="{{ route('admin.approvals.show', $item['id']) }}" class="timeline-link">
                                        View booking details &gt;
                                    </a>
                                    <div class="timeline-buttons">
                                        <button class="timeline-btn timeline-btn--primary">Approve</button>
                                        <button class="timeline-btn timeline-btn--secondary">Reject</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="approval-cta-wrap">
                        <a href="{{ route('admin.approvals.queue') }}" class="approval-cta">Open approvals queue</a>
                    </div>
                </article>

                <article class="admin-card">
                    <h3>Recent activity</h3>
                    <p class="text-muted">Notes from facilities and policy updates.</p>
                    <ul class="admin-approvals-list">
                        <li>
                            <div>
                                <strong>Policy release · Equipment checkout</strong>
                                <p class="text-muted mb-0 small">Effective Dec 1 · Logistics</p>
                            </div>
                            <span class="admin-tag is-info">Update</span>
                        </li>
                        <li>
                            <div>
                                <strong>Incident resolved · Studio A</strong>
                                <p class="text-muted mb-0 small">Sound desk replaced</p>
                            </div>
                            <span class="admin-tag is-success">Closed</span>
                        </li>
                    </ul>
                </article>
            </div>

            <aside class="rooms-board">
                <h3>Rooms currently booked</h3>
                <p class="text-muted">Monitor in-use venues.</p>
                <ul>
                    @foreach ($rooms as $room)
                        <li>
                            <div>
                                <strong>{{ $room['room'] }}</strong>
                                <p class="text-muted small mb-0">{{ $room['purpose'] }}</p>
                            </div>
                            <div class="text-end">
                                <p class="mb-0 fw-semibold">{{ $room['time'] }}</p>
                                <p class="text-muted small mb-0">{{ $room['owner'] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </aside>
        </div>
    </section>
@endsection
