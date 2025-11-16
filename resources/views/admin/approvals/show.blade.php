@extends('layouts.app')

@section('title', 'Admin • Approval Details')

@push('styles')
    @vite([
        'resources/css/wizard/base.css',
        'resources/css/admin/approvals.css',
    ])
@endpush

@php
    $user = $user ?? auth()->user();
    $detail = $detail ?? $booking->details;
    $approvalRecord = $approvalRecord ?? $booking->approval;
    $scheduleDate = $booking->date?->format('M j, Y');
    $startTime = $booking->start_at ? \Illuminate\Support\Carbon::parse($booking->start_at)->format('g:i A') : null;
    $endTime = $booking->end_at ? \Illuminate\Support\Carbon::parse($booking->end_at)->format('g:i A') : null;
    $requester = $booking->requester;
    $department = optional($requester?->department)->name;
    $reference = $booking->reference_code;
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
                <span class="hero-badge">Reference {{ $reference }}</span>
                <h1>{{ $booking->facility->name ?? 'Facility' }}</h1>
                <p class="mb-0">{{ $detail->purpose ?? 'Booking request' }}</p>
            </div>
            <div class="hero-metrics">
                <div>
                    <small>Schedule</small>
                    <strong>{{ trim(($scheduleDate ?? '—') . ($startTime ? ' · ' . $startTime : '') . ($endTime ? ' – ' . $endTime : '')) }}</strong>
                </div>
                <div>
                    <small>Status</small>
                    <strong>{{ ucfirst($booking->status) }}</strong>
                </div>
                <div>
                    <small>Attendees</small>
                    <strong>{{ $detail->attendees_count ?? '—' }} pax</strong>
                </div>
                <div>
                    <small>Submitted</small>
                    <strong>{{ $booking->created_at?->format('M j · g:i A') }}</strong>
                </div>
            </div>
        </article>

        <div class="detail-quick-facts">
            <div class="fact-card">
                <small>Reference code</small>
                <strong>{{ $reference }}</strong>
            </div>
            <div class="fact-card">
                <small>Requester contact</small>
                <strong>{{ $requester->email ?? 'N/A' }}</strong>
            </div>
            <div class="fact-card">
                <small>Approval updated</small>
                <strong>{{ $approvalRecord?->updated_at?->format('M j · g:i A') ?? 'Pending' }}</strong>
            </div>
        </div>

        <div class="detail-grid">
            <div class="detail-column">
                <section class="detail-card">
                    <h3>Request summary</h3>
                    <ul class="detail-list">
                        <li>
                            <span>Requester</span>
                            <strong>{{ $requester->name ?? 'Requester' }}</strong>
                            <p>{{ $department ?? 'Department' }} · {{ $requester->email ?? 'N/A' }}</p>
                        </li>
                        <li>
                            <span>Facility</span>
                            <strong>{{ $booking->facility->name ?? 'Facility' }}</strong>
                        </li>
                        <li>
                            <span>Schedule</span>
                            <strong>{{ $scheduleDate ?? '—' }}</strong>
                            <p>{{ $startTime }} @if($endTime) – {{ $endTime }} @endif</p>
                        </li>
                        <li>
                            <span>Status</span>
                            <strong>{{ ucfirst($booking->status) }}</strong>
                            <p>Current approver: {{ $approvalRecord?->approver->name ?? 'Unassigned' }}</p>
                        </li>
                    </ul>
                    <div class="summary-meta-chips">
                        <span>{{ $reference }}</span>
                        <span>{{ $department ?? 'Shared Services' }}</span>
                        <span>Submitted {{ $booking->created_at?->format('M j · g:i A') }}</span>
                    </div>
                </section>

                <section class="detail-card">
                    <h3>Purpose &amp; notes</h3>
                    <div class="detail-text">
                        <p class="eyebrow">Purpose</p>
                        <p>{{ $detail->purpose ?? 'Not provided' }}</p>
                        <p class="eyebrow">Requester notes</p>
                        <p>{{ $detail->additional_notes ?? 'No requester notes' }}</p>
                        <p class="eyebrow">Additional notes</p>
                        <p>{{ $detail->additional_notes ?? '—' }}</p>
                    </div>
                </section>

                <section class="detail-card">
                    <h3>Support requirements</h3>
                    <div class="support-grid">
                        <div>
                            <span>SFI support</span>
                            <strong>{{ ($detail->sfi_support ?? false) ? 'Yes' : 'No' }}</strong>
                        </div>
                        <div>
                            <span>SFI team count</span>
                            <strong>{{ $detail->sfi_count ?? 0 }}</strong>
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
                            <strong>{{ ucfirst($approvalRecord->status ?? $booking->status) }}</strong>
                        </div>
                        <div>
                            <span>Approver</span>
                            <strong>{{ $approvalRecord?->approver->name ?? 'Unassigned' }}</strong>
                        </div>
                        <div>
                            <span>Last updated</span>
                            <strong>{{ $approvalRecord?->updated_at?->format('M j · g:i A') ?? '—' }}</strong>
                        </div>
                    </div>
                    <div class="status-remarks">
                        <p class="eyebrow">Remarks</p>
                        <p>{{ $approvalRecord->remarks ?? 'No remarks captured yet.' }}</p>
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
