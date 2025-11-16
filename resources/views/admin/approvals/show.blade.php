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
        'reference' => 'BKG-8742',
        'facility' => 'Multipurpose Hall',
        'purpose' => 'Leaders weekend summit',
        'requester' => 'Jared Mercado',
        'requester_email' => 'jared.mercado@oneservices.ph',
        'department' => 'Outreach',
        'date' => 'Dec 2, 2025',
        'start' => '8:00 AM',
        'end' => '6:00 PM',
        'submitted' => 'Nov 30, 9:42 PM',
        'status' => 'pending',
        'attendees' => 120,
        'notes' => 'Requesting additional breakout rooms and livestream support.',
    ];
    $bookingDetail = [
        'sfi_support' => true,
        'sfi_count' => 4,
        'purpose' => 'Weekend leadership summit and volunteers training for ENC BGC leaders.',
        'additional_notes' => 'Need camera riser and rehearsal block before 7:00 AM.',
    ];
    $approval = [
        'status' => 'pending',
        'approver' => 'Ops Desk',
        'remarks' => 'Awaiting confirmation from requester on breakout configuration.',
        'updated_at' => 'Dec 1, 7:22 AM',
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

        <article class="approval-detail-hero">
            <div>
                <span class="hero-badge">Reference {{ $booking['reference'] }}</span>
                <h1>{{ $booking['facility'] }}</h1>
                <p class="mb-0">{{ $booking['purpose'] }}</p>
            </div>
            <div class="hero-metrics">
                <div>
                    <small>Schedule</small>
                    <strong>{{ $booking['date'] }} · {{ $booking['start'] }} – {{ $booking['end'] }}</strong>
                </div>
                <div>
                    <small>Status</small>
                    <strong>{{ ucfirst($booking['status']) }}</strong>
                </div>
                <div>
                    <small>Attendees</small>
                    <strong>{{ $booking['attendees'] }} pax</strong>
                </div>
                <div>
                    <small>Submitted</small>
                    <strong>{{ $booking['submitted'] }}</strong>
                </div>
            </div>
        </article>

        <div class="detail-quick-facts">
            <div class="fact-card">
                <small>Reference code</small>
                <strong>{{ $booking['reference'] }}</strong>
            </div>
            <div class="fact-card">
                <small>Requester contact</small>
                <strong>{{ $booking['requester_email'] }}</strong>
            </div>
            <div class="fact-card">
                <small>Approval updated</small>
                <strong>{{ $approval['updated_at'] }}</strong>
            </div>
        </div>

        <div class="detail-grid">
            <div class="detail-column">
                <section class="detail-card">
                    <h3>Request summary</h3>
                    <ul class="detail-list">
                        <li>
                            <span>Requester</span>
                            <strong>{{ $booking['requester'] }}</strong>
                            <p>{{ $booking['department'] }} · {{ $booking['requester_email'] }}</p>
                        </li>
                        <li>
                            <span>Facility</span>
                            <strong>{{ $booking['facility'] }}</strong>
                        </li>
                        <li>
                            <span>Schedule</span>
                            <strong>{{ $booking['date'] }}</strong>
                            <p>{{ $booking['start'] }} – {{ $booking['end'] }}</p>
                        </li>
                        <li>
                            <span>Status</span>
                            <strong>{{ ucfirst($booking['status']) }}</strong>
                            <p>Current approver: {{ $approval['approver'] }}</p>
                        </li>
                    </ul>
                    <div class="summary-meta-chips">
                        <span>{{ $booking['reference'] }}</span>
                        <span>{{ $booking['department'] }} team</span>
                        <span>Submitted {{ $booking['submitted'] }}</span>
                    </div>
                </section>

                <section class="detail-card">
                    <h3>Purpose &amp; notes</h3>
                    <div class="detail-text">
                        <p class="eyebrow">Purpose</p>
                        <p>{{ $bookingDetail['purpose'] }}</p>
                        <p class="eyebrow">Requester notes</p>
                        <p>{{ $booking['notes'] }}</p>
                        <p class="eyebrow">Additional notes</p>
                        <p>{{ $bookingDetail['additional_notes'] }}</p>
                    </div>
                </section>

                <section class="detail-card">
                    <h3>Support requirements</h3>
                    <div class="support-grid">
                        <div>
                            <span>SFI support</span>
                            <strong>{{ $bookingDetail['sfi_support'] ? 'Yes' : 'No' }}</strong>
                        </div>
                        <div>
                            <span>SFI team count</span>
                            <strong>{{ $bookingDetail['sfi_count'] }}</strong>
                        </div>
                    </div>
                </section>
            </div>

            <aside class="detail-column detail-column--narrow">
                <section class="detail-card approval-status-card">
                    <h3>Approval status</h3>
                    <div class="status-stack">
                        <div>
                            <span>Current status</span>
                            <strong>{{ ucfirst($approval['status']) }}</strong>
                        </div>
                        <div>
                            <span>Approver</span>
                            <strong>{{ $approval['approver'] }}</strong>
                        </div>
                        <div>
                            <span>Last updated</span>
                            <strong>{{ $approval['updated_at'] }}</strong>
                        </div>
                    </div>
                    <div class="status-remarks">
                        <p class="eyebrow">Remarks</p>
                        <p>{{ $approval['remarks'] }}</p>
                    </div>
                </section>

                <section class="detail-card decision-card">
                    <h3>Decision panel</h3>
                    <label for="decisionNotes" class="form-label">Notes to requester</label>
                    <textarea id="decisionNotes" placeholder="Add context, approvals, or requests for changes..."></textarea>
                    <div class="decision-buttons">
                        <button class="btn btn-success flex-1">Approve</button>
                        <button class="btn btn-outline-danger flex-1">Reject</button>
                        <button class="btn btn-outline-secondary flex-1">Request changes</button>
                    </div>
                </section>
            </aside>
        </div>
    </section>
@endsection
