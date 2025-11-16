@extends('layouts.app')

@section('title', 'Admin • Approvals Queue')

@push('styles')
    @vite([
        'resources/css/wizard/base.css',
        'resources/css/admin/approvals.css',
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
                    @if ($latestPending)
                        <a href="{{ route('admin.approvals.show', $latestPending) }}" class="approvals-hero-cta">Open latest request</a>
                    @else
                        <span class="approvals-hero-hint">No pending requests</span>
                    @endif
                </div>
            </div>
            <div class="approvals-hero-panel">
                <div class="approvals-hero-metrics">
                    <div class="metric">
                        <small>Waiting</small>
                        <strong>{{ number_format($queueStats['pending'] ?? 0) }}</strong>
                    </div>
                    <div class="metric">
                        <small>Breaching SLA</small>
                        <strong>{{ number_format($queueStats['breaches'] ?? 0) }}</strong>
                    </div>
                    <div class="metric">
                        <small>Avg response</small>
                        <strong>{{ number_format($queueStats['avgResponse'] ?? 0) }} mins</strong>
                    </div>
                </div>
                <span class="approvals-hero-updated">Live queue data · refreshed {{ now()->diffForHumans(null, true) }} ago</span>
            </div>
        </header>

        <div class="approvals-filters">
            <a href="{{ route('admin.approvals.queue') }}"
               class="approvals-chip {{ empty($statusFilter) ? 'active' : '' }}">
                All status
            </a>
            @foreach ($availableStatuses as $status)
                <a href="{{ route('admin.approvals.queue', ['status' => $status]) }}"
                   class="approvals-chip {{ $statusFilter === $status ? 'active' : '' }}">
                    {{ ucfirst($status) }}
                </a>
            @endforeach
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
                        @foreach ($bookings as $row)
                            @php
                                $statusClass = match($row->status) {
                                    'approved' => 'is-approved',
                                    'rejected' => 'is-rejected',
                                    'cancelled' => 'is-cancelled',
                                    'noshow' => 'is-noshow',
                                    default => 'is-pending',
                                };
                                $scheduleDate = $row->date?->format('M j, Y');
                                $startTime = $row->start_at ? \Illuminate\Support\Carbon::parse($row->start_at)->format('g:i A') : null;
                                $endTime = $row->end_at ? \Illuminate\Support\Carbon::parse($row->end_at)->format('g:i A') : null;
                            @endphp
                            <tr>
                                <td>
                                    <span class="queue-primary">{{ $row->reference_code }}</span>
                                    <span class="queue-meta">Submitted {{ $row->created_at?->format('M j · g:i A') }}</span>
                                </td>
                                <td>
                                    <span class="queue-primary">{{ $row->facility->name ?? 'Facility' }}</span>
                                    <span class="queue-meta">{{ $row->facility->facility_code ?? 'N/A' }} · Room {{ $row->facility->room_number ?? '—' }}</span>
                                </td>
                                <td>
                                    <span class="queue-primary">{{ $row->requester->name ?? 'Requester' }}</span>
                                    <span class="queue-meta">{{ $row->requester->email ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="queue-primary">{{ optional($row->details)->purpose ?? 'Booking purpose' }}</span>
                                    <span class="queue-meta">Event name</span>
                                </td>
                                <td>
                                    <span class="queue-primary">{{ trim(($scheduleDate ?? '—') . ($startTime ? ' · ' . $startTime : '') . ($endTime ? ' – ' . $endTime : '')) }}</span>
                                    <span class="queue-meta">Booking date · time block</span>
                                </td>
                                <td><span class="queue-status {{ $statusClass }}">{{ ucfirst($row->status) }}</span></td>
                                <td>
                                    <span class="queue-primary">{{ optional($row->approval)->remarks ?? 'No remarks yet' }}</span>
                                    <span class="queue-meta">Internal notes</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.approvals.show', $row) }}" class="btn btn-link">View booking</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
