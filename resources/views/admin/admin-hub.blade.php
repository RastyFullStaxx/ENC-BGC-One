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
        <div class="admin-top-actions">
            <a href="{{ route('admin.dashboard') }}" class="admin-back-button">
                &lt; Back to admin dashboard
            </a>
        </div>
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
        </header>

        <div class="hub-health-row">
            <div class="hub-health-card hub-health-card--plum">
                <span class="hub-health-label">Campus utilization</span>
                <strong>86%</strong>
                <span class="hub-health-meta">↑ 4% vs last week</span>
                <div class="hub-progress">
                    <span style="width: 86%"></span>
                </div>
            </div>
            <div class="hub-health-card hub-health-card--navy">
                <span class="hub-health-label">Approvals SLA</span>
                <strong>42 mins</strong>
                <span class="hub-health-meta">Target · 45 mins</span>
                <div class="hub-progress is-info">
                    <span style="width: 93%"></span>
                </div>
            </div>
            <div class="hub-health-card hub-health-card--rust">
                <span class="hub-health-label">Live incidents</span>
                <strong>02</strong>
                <span class="hub-health-meta text-warning">1 critical · 1 maintenance</span>
                <div class="hub-progress is-warning">
                    <span style="width: 30%"></span>
                </div>
            </div>
        </div>

        <div class="hub-stack-card">
            <div class="hub-stack-grid">
                <section class="hub-stack-section">
                <div class="hub-card-heading">
                    <div>
                        <p class="hub-card-eyebrow">Logistics · Facilities · SFI</p>
                        <h3>Staff on shift</h3>
                    </div>
                    <span class="hub-chip hub-chip--success">Stable</span>
                </div>
                <ul class="admin-approvals-list">
                    <li>
                        <div>
                            <strong>Operations Desk</strong>
                            <p class="text-muted small mb-0">3 approvers · SLA 45 mins</p>
                        </div>
                        <span class="hub-mini-pill is-online">Online</span>
                    </li>
                    <li>
                        <div>
                            <strong>SFI Support</strong>
                            <p class="text-muted small mb-0">2 coordinators</p>
                        </div>
                        <span class="hub-mini-pill is-info">On call</span>
                    </li>
                </ul>
            </section>
            <section class="hub-stack-section">
                <div class="hub-card-heading">
                    <div>
                        <p class="hub-card-eyebrow">Facilities requiring attention</p>
                        <h3>Incident board</h3>
                    </div>
                    <span class="hub-chip hub-chip--warning">2 open</span>
                </div>
                <ul class="admin-approvals-list">
                    <li>
                        <div>
                            <strong>Studio B · Lighting rack</strong>
                            <p class="text-muted small mb-0">Repair scheduled at 6:00 PM</p>
                        </div>
                        <span class="hub-mini-pill is-warning">Maintenance</span>
                    </li>
                    <li>
                        <div>
                            <strong>Storage Wing · Access control</strong>
                            <p class="text-muted small mb-0">Awaiting vendor update</p>
                        </div>
                        <span class="hub-mini-pill is-info">Monitoring</span>
                    </li>
                </ul>
            </section>
            <section class="hub-stack-section">
                <div class="hub-card-heading">
                    <div>
                        <p class="hub-card-eyebrow">Latest announcements</p>
                        <h3>Policy updates</h3>
                    </div>
                    <span class="hub-chip hub-chip--info">New items</span>
                </div>
                <ul class="admin-approvals-list">
                    <li>
                        <div>
                            <strong>Equipment checkout</strong>
                            <p class="text-muted small mb-0">Effective Dec 1 · Logistics</p>
                        </div>
                        <span class="hub-mini-pill is-info">New</span>
                    </li>
                    <li>
                        <div>
                            <strong>Room prep SLA</strong>
                            <p class="text-muted small mb-0">Operations · 30 min buffer</p>
                        </div>
                        <span class="hub-mini-pill is-online">Live</span>
                    </li>
                </ul>
            </section>
            <section class="hub-stack-section">
                <div class="hub-card-heading">
                    <div>
                        <p class="hub-card-eyebrow">Average response and completion</p>
                        <h3>SLA tracker</h3>
                    </div>
                    <span class="hub-chip hub-chip--neutral">Live</span>
                </div>
                <div class="chart-placeholder">
                    <div class="sla-bars">
                        <span style="height: 60%"></span>
                        <span style="height: 80%"></span>
                        <span style="height: 45%"></span>
                        <span style="height: 90%"></span>
                        <span style="height: 72%"></span>
                    </div>
                </div>
                <p class="mt-3 mb-0"><strong>42 mins</strong> avg response · <strong>1h 55m</strong> completion</p>
            </section>
            </div>
        </div>

        <section class="admin-card">
            <h3>Admin tools</h3>
            <p class="text-muted">All administrative controls and modules live here.</p>
            <div class="tool-matrix">
                <a href="{{ route('admin.users') }}" class="tool-card tool-card--plum">
                    <span class="tool-card-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M17 21v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="1.6"/><path d="M17 11l2 2 4-4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                    </span>
                    <div>
                        <h4>User directory</h4>
                        <p class="tool-card-meta">Manage accounts, roles, access</p>
                    </div>
                </a>
                <a href="{{ route('admin.facilities') }}" class="tool-card tool-card--rust">
                    <span class="tool-card-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><rect x="3" y="5" width="18" height="14" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="M3 10h18" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><path d="M8 2v4M16 2v4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                    </span>
                    <div>
                        <h4>Facilities board</h4>
                        <p class="tool-card-meta">Maintenance, photos, availability</p>
                    </div>
                </a>
                <a href="{{ route('admin.analytics') }}" class="tool-card tool-card--navy">
                    <span class="tool-card-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M4 20V10" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><path d="M10 20V4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><path d="M16 20v-6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><path d="M22 20V8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                    </span>
                    <div>
                        <h4>Analytics & reports</h4>
                        <p class="tool-card-meta">Utilization, wait times, SLA</p>
                    </div>
                </a>
                <a href="{{ route('admin.calendar') }}" class="tool-card tool-card--teal">
                    <span class="tool-card-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><rect x="3" y="4" width="18" height="17" rx="3" stroke="currentColor" stroke-width="1.6"/><path d="M3 10h18" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><path d="M8 2v4M16 2v4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                    </span>
                    <div>
                        <h4>Global calendar</h4>
                        <p class="tool-card-meta">Cross-campus bookings</p>
                    </div>
                </a>
                <a href="{{ route('admin.policies') }}" class="tool-card tool-card--amber">
                    <span class="tool-card-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M6 2h9l3 3v15a2 2 0 01-2 2H6a2 2 0 01-2-2V4a2 2 0 012-2z" stroke="currentColor" stroke-width="1.6"/><path d="M9 8h6M9 12h4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                    </span>
                    <div>
                        <h4>Policies</h4>
                        <p class="tool-card-meta">Publish guidelines & SOPs</p>
                    </div>
                </a>
                <a href="{{ route('admin.audit') }}" class="tool-card tool-card--slate">
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
