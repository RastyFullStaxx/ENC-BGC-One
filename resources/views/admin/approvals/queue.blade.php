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
            'ref' => 'BKG-8742',
            'facility' => 'Multipurpose Hall',
            'requester' => 'Jared Mercado',
            'department' => 'Outreach',
            'purpose' => 'Leaders weekend summit',
            'date' => 'Dec 2 · 8:00 AM',
            'status' => 'pending',
            'priority' => 'High',
        ],
        [
            'ref' => 'BKG-8790',
            'facility' => 'Studio B',
            'requester' => 'Ava Santos',
            'department' => 'Comms',
            'purpose' => 'Production block recording',
            'date' => 'Dec 2 · 2:00 PM',
            'status' => 'review',
            'priority' => 'Medium',
        ],
        [
            'ref' => 'BKG-8720',
            'facility' => 'Victory Room 2',
            'requester' => 'Luis Catapang',
            'department' => 'Pastoral',
            'purpose' => 'Leadership sync',
            'date' => 'Dec 3 · 10:00 AM',
            'status' => 'ready',
            'priority' => 'Standard',
        ],
        [
            'ref' => 'BKG-8810',
            'facility' => 'Studio A',
            'requester' => 'Micah Lim',
            'department' => 'Media',
            'purpose' => 'Podcast taping',
            'date' => 'Dec 3 · 4:00 PM',
            'status' => 'pending',
            'priority' => 'High',
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
            <div>
                <span class="admin-hero-badge">Approvals Command Center</span>
                <h1>Booking approvals queue</h1>
                <p>Monitor all live booking requests, triage escalations, and keep SLA promises across campus operations.</p>
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
            </div>
            <div>
                <a href="{{ route('admin.approvals.show') }}" class="btn btn-light">Open latest request</a>
            </div>
        </header>

        <div class="approvals-filters">
            <button class="approvals-chip active">All status</button>
            <button class="approvals-chip">Pending</button>
            <button class="approvals-chip">Needs review</button>
            <button class="approvals-chip">Ready to release</button>
            <button class="approvals-chip">High priority</button>
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
                            <th>Facility / Purpose</th>
                            <th>Requester</th>
                            <th>Date &amp; SLA</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($approvals as $row)
                            @php
                                $statusClass = match($row['status']) {
                                    'review' => 'is-review',
                                    'ready' => 'is-ready',
                                    default => 'is-pending',
                                };
                            @endphp
                            <tr>
                                <td>
                                    <span class="queue-primary">{{ $row['ref'] }}</span>
                                    <span class="queue-meta">{{ $row['department'] }}</span>
                                </td>
                                <td>
                                    <span class="queue-primary">{{ $row['facility'] }}</span>
                                    <span class="queue-meta">{{ $row['purpose'] }}</span>
                                </td>
                                <td>
                                    <span class="queue-primary">{{ $row['requester'] }}</span>
                                    <span class="queue-meta">{{ $row['department'] }}</span>
                                </td>
                                <td>
                                    <span class="queue-primary">{{ $row['date'] }}</span>
                                    <span class="queue-meta">SLA: 60 mins</span>
                                </td>
                                <td><span class="queue-status {{ $statusClass }}">{{ ucfirst($row['status']) }}</span></td>
                                <td>{{ $row['priority'] }}</td>
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
