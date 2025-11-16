@extends('layouts.app')

@section('title', 'Admin • Approvals Queue')

@push('styles')
    @vite([
        'resources/css/wizard/base.css',
        'resources/css/admin/approvals.css',
    ])
@endpush

@php
    $user = auth()->user();
    $approvals = [
        [
            'reference' => 'BKG-8742',
            'facility' => 'Multipurpose Hall',
            'facility_code' => 'MPH-01',
            'room' => 'G01',
            'requester' => 'Jared Mercado',
            'requester_email' => 'jared.mercado@oneservices.ph',
            'event' => 'Leaders Weekend Summit',
            'schedule' => 'Dec 2 · 8:00 AM – 10:00 AM',
            'submitted' => 'Nov 30 · 9:42 PM',
            'status' => 'pending',
            'remarks' => 'Waiting for routing to Ops approver',
        ],
        [
            'reference' => 'BKG-8790',
            'facility' => 'Studio B',
            'facility_code' => 'STB-02',
            'room' => '201',
            'requester' => 'Ava Santos',
            'requester_email' => 'ava.santos@oneservices.ph',
            'event' => 'Production Block Recording',
            'schedule' => 'Dec 2 · 2:00 PM – 4:00 PM',
            'submitted' => 'Nov 30 · 1:10 PM',
            'status' => 'approved',
            'remarks' => 'Approved with stage reset',
        ],
        [
            'reference' => 'BKG-8720',
            'facility' => 'Victory Room 2',
            'facility_code' => 'VCR-12',
            'room' => '312',
            'requester' => 'Luis Catapang',
            'requester_email' => 'luis.catapang@oneservices.ph',
            'event' => 'Leadership Sync',
            'schedule' => 'Dec 3 · 10:00 AM – 11:30 AM',
            'submitted' => 'Nov 29 · 4:30 PM',
            'status' => 'pending',
            'remarks' => 'Needs requester confirmation',
        ],
        [
            'reference' => 'BKG-8810',
            'facility' => 'Studio A',
            'facility_code' => 'STA-01',
            'room' => 'LL1',
            'requester' => 'Micah Lim',
            'requester_email' => 'micah.lim@oneservices.ph',
            'event' => 'Podcast Season Finale',
            'schedule' => 'Dec 3 · 4:00 PM – 5:30 PM',
            'submitted' => 'Nov 30 · 8:20 AM',
            'status' => 'rejected',
            'remarks' => 'Reject: schedule conflict',
        ],
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

    <section class="approvals-shell">
        <div class="admin-top-actions">
            <a href="{{ route('admin.dashboard') }}" class="admin-back-button">
                &lt; Back to dashboard
            </a>
        </div>

        <header class="approvals-hero">
            <div class="approvals-hero-content">
                <span class="admin-hero-badge">Approvals Command Center</span>
                <h1>Booking approvals queue</h1>
                <p>Monitor all live booking requests, triage escalations, and keep SLA promises across campus operations.</p>
                <div class="approvals-hero-footer">
                    <p class="approvals-hero-hint">Jumps straight to the most recent booking awaiting a decision.</p>
                    <a href="{{ route('admin.approvals.show') }}" class="approvals-hero-cta">Open latest request</a>
                </div>
            </div>
            <div class="approvals-hero-panel">
                <div class="approvals-hero-metrics">
                    <div class="metric">
                        <small>Waiting</small>
                        <strong>18</strong>
                    </div>
                    <div class="metric">
                        <small>Breaching SLA</small>
                        <strong>3</strong>
                    </div>
                    <div class="metric">
                        <small>Avg response</small>
                        <strong>42 mins</strong>
                    </div>
                </div>
                <span class="approvals-hero-updated">Live queue data · refreshed 5 mins ago</span>
            </div>
        </header>

        <div class="approvals-filters">
            <button class="approvals-chip active">All status</button>
            <button class="approvals-chip">Pending</button>
            <button class="approvals-chip">Approved</button>
            <button class="approvals-chip">Rejected</button>
        </div>

        <div class="queue-surface">
            <div class="queue-toolbar">
                <div class="queue-search">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.6"/>
                        <path d="M20 20l-3.5-3.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                    </svg>
                    <input type="search" placeholder="Search by requester, reference, or facility">
                </div>
                <div class="queue-actions">
                    <button class="btn btn-outline-secondary">Export CSV</button>
                    <button class="btn btn-outline-secondary">Bulk actions</button>
                </div>
            </div>

            <div class="queue-table-wrapper">
                <table class="queue-table">
                    <thead>
                        <tr>
                            <th>Reference</th>
                            <th>Facility</th>
                            <th>Requester</th>
                            <th>Event</th>
                            <th>Schedule</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($approvals as $row)
                            @php
                                $statusClass = match($row['status']) {
                                    'approved' => 'is-approved',
                                    'rejected' => 'is-rejected',
                                    'cancelled' => 'is-cancelled',
                                    'noshow' => 'is-noshow',
                                    default => 'is-pending',
                                };
                            @endphp
                            <tr>
                                <td>
                                    <span class="queue-primary">{{ $row['reference'] }}</span>
                                    <span class="queue-meta">Submitted {{ $row['submitted'] }}</span>
                                </td>
                                <td>
                                    <span class="queue-primary">{{ $row['facility'] }}</span>
                                    <span class="queue-meta">{{ $row['facility_code'] }} · Room {{ $row['room'] }}</span>
                                </td>
                                <td>
                                    <span class="queue-primary">{{ $row['requester'] }}</span>
                                    <span class="queue-meta">{{ $row['requester_email'] }}</span>
                                </td>
                                <td>
                                    <span class="queue-primary">{{ $row['event'] }}</span>
                                    <span class="queue-meta">Event name</span>
                                </td>
                                <td>
                                    <span class="queue-primary">{{ $row['schedule'] }}</span>
                                    <span class="queue-meta">Booking date · time block</span>
                                </td>
                                <td><span class="queue-status {{ $statusClass }}">{{ ucfirst($row['status']) }}</span></td>
                                <td>
                                    <span class="queue-primary">{{ $row['remarks'] ?? 'No remarks yet' }}</span>
                                    <span class="queue-meta">Internal notes</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.approvals.show') }}" class="btn btn-link">View booking</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
