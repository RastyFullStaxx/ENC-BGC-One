@extends('layouts.app')

@section('title', 'Admin • Approval Details')

@push('styles')
    @vite([
        'resources/css/wizard/base.css',
        'resources/css/admin/approvals.css',
    ])
@endpush

@php
    $user = auth()->user();
    $booking = [
        'ref' => 'BKG-8742',
        'facility' => 'Multipurpose Hall',
        'purpose' => 'Leaders weekend summit',
        'requester' => 'Jared Mercado',
        'department' => 'Outreach',
        'attendees' => 120,
        'schedule' => 'Dec 2, 8:00 AM – 6:00 PM',
        'submitted' => 'Nov 30, 9:42 PM',
        'status' => 'Pending',
        'priority' => 'High',
        'notes' => 'Requesting additional breakout rooms and livestream support.',
    ];
    $timeline = [
        ['label' => 'Submitted by requester', 'time' => 'Nov 30 · 9:42 PM'],
        ['label' => 'Auto-screened · SLA warning', 'time' => 'Dec 1 · 7:00 AM'],
        ['label' => 'Assigned to Ops approver', 'time' => 'Dec 1 · 7:05 AM'],
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
            <a href="{{ route('admin.approvals.queue') }}" class="admin-back-button">
                &lt; Back to approvals queue
            </a>
        </div>

        <article class="approvals-detail-hero">
            <div class="approvals-detail-header">
                <div>
                    <p class="approval-eyebrow">Booking reference {{ $booking['ref'] }}</p>
                    <h1>{{ $booking['facility'] }}</h1>
                    <p class="mb-0">{{ $booking['purpose'] }}</p>
                </div>
                <div class="detail-meta-chips">
                    <span class="detail-chip">Status: {{ $booking['status'] }}</span>
                    <span class="detail-chip">Priority: {{ $booking['priority'] }}</span>
                    <span class="detail-chip">SLA target: 60 mins</span>
                </div>
            </div>
        </article>

        <div class="detail-layout">
            <div>
                <div class="detail-card">
                    <h3>Request details</h3>
                    <ul class="detail-list">
                        <li>
                            <span>Requested by</span>
                            <strong>{{ $booking['requester'] }} · {{ $booking['department'] }}</strong>
                        </li>
                        <li>
                            <span>Schedule</span>
                            <strong>{{ $booking['schedule'] }}</strong>
                        </li>
                        <li>
                            <span>Attendees</span>
                            <strong>{{ $booking['attendees'] }} pax</strong>
                        </li>
                        <li>
                            <span>Submitted</span>
                            <strong>{{ $booking['submitted'] }}</strong>
                        </li>
                        <li>
                            <span>Notes</span>
                            <strong>{{ $booking['notes'] }}</strong>
                        </li>
                    </ul>
                </div>

                <div class="detail-card">
                    <h3>Routing timeline</h3>
                    <div class="timeline-progress">
                        @foreach ($timeline as $node)
                            <div class="timeline-progress-item">
                                <strong>{{ $node['label'] }}</strong>
                                <p class="text-muted mb-0">{{ $node['time'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <aside class="decision-card">
                <h3>Decision panel</h3>
                <p class="text-muted">Provide your notes and select an action.</p>

                <div class="decision-actions">
                    <label for="decisionNotes" class="form-label">Notes to requester</label>
                    <textarea id="decisionNotes" placeholder="Add context, required adjustments, or escalation notes..."></textarea>
                    <div class="decision-buttons">
                        <button class="btn btn-success">Approve booking</button>
                        <button class="btn btn-outline-danger">Reject booking</button>
                        <button class="btn btn-outline-secondary">Ask for changes</button>
                    </div>
                </div>
            </aside>
        </div>
    </section>
@endsection
