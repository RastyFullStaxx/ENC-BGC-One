@extends('layouts.app')

@section('title', 'Admin • Rules & Policies')

@push('styles')
    @vite(['resources/css/admin/policies.css'])
@endpush

@section('content')
<a href="{{ route('user.dashboard') }}" class="admin-back-button mb-3">
    <svg viewBox="0 0 16 16" fill="none" aria-hidden="true">
        <path d="M10 12L6 8L10 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M6 8H14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
    Back to Dashboard
</a>
@php
    $policies = [
        [
            'name' => 'Lead Time · 24h',
            'scope' => 'Global',
            'scope_key' => 'global',
            'desc' => 'All bookings must be made ≥ 24 hours before start',
            'active' => true,
            'updated' => 'Aug 12 · 09:42 AM',
        ],
        [
            'name' => 'Executive Floors · Access',
            'scope' => 'Facility-specific',
            'scope_key' => 'facility',
            'desc' => 'Only directors can book Building A 15F rooms',
            'active' => true,
            'updated' => 'Aug 10 · 04:15 PM',
        ],
        [
            'name' => 'No Food · Labs',
            'scope' => 'Facility-specific',
            'scope_key' => 'facility',
            'desc' => 'Food not allowed inside Helios Training Lab',
            'active' => false,
            'updated' => 'Aug 08 · 11:02 AM',
        ],
        [
            'name' => 'Recurring Booking Guard',
            'scope' => 'Global',
            'scope_key' => 'global',
            'desc' => 'Limit recurring bookings to 6 weeks at a time',
            'active' => true,
            'updated' => 'Aug 05 · 02:33 PM',
        ],
    ];

    $ruleTemplates = [
        ['kind' => 'Lead Time', 'operator' => 'GTE', 'value' => '24 hours'],
        ['kind' => 'Eligibility', 'operator' => 'IN', 'value' => 'Staff Only'],
        ['kind' => 'Food', 'operator' => 'BOOL', 'value' => 'False'],
        ['kind' => 'Duration Limit', 'operator' => 'LTE', 'value' => '4 hours'],
    ];
@endphp

<section class="admin-policies-page">
    <div class="admin-policies-shell">
        <p class="pol-breadcrumb">Admin Hub · Rules & Policies</p>
        <div class="pol-header">
            <div>
                <h1>Rules & Policies</h1>
                <p>Define, edit, and apply booking policies.</p>
            </div>
            <div class="pol-actions">
                <button class="pol-btn" data-modal-open="testRuleModal">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                        <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                    </svg>
                    Test Rule
                </button>
                <button class="pol-btn pol-btn-primary" data-modal-open="policyModal">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                        <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                    </svg>
                    Create Policy
                </button>
            </div>
        </div>

        <div class="pol-surface">
            <div class="pol-filters">
                <button class="pol-chip active" data-filter-scope="all">All scopes</button>
                <button class="pol-chip" data-filter-scope="global">Global</button>
                <button class="pol-chip" data-filter-scope="facility">Facility-specific</button>

                <div class="pol-chip-divider">
                    <button class="pol-chip active" data-filter-status="all">All status</button>
                    <button class="pol-chip" data-filter-status="active">Active</button>
                    <button class="pol-chip" data-filter-status="inactive">Inactive</button>
                </div>

                <input type="search" class="pol-btn" id="policySearch" placeholder="Search policies">
            </div>

            <div class="pol-table-wrapper">
                <table class="pol-table" id="policyTable">
                    <thead>
                        <tr>
                            <th>Policy Name</th>
                            <th>Scope</th>
                            <th>Description</th>
                            <th>Active?</th>
                            <th>Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($policies as $policy)
                            <tr
                                data-scope="{{ $policy['scope_key'] }}"
                                data-status="{{ $policy['active'] ? 'active' : 'inactive' }}"
                                data-name="{{ strtolower($policy['name']) }}"
                                data-description="{{ strtolower($policy['desc']) }}"
                            >
                                <td>
                                    <strong>{{ $policy['name'] }}</strong>
                                </td>
                                <td>
                                    <span class="pol-scope-chip {{ $policy['scope_key'] === 'global' ? 'global' : 'facility' }}">
                                        {{ $policy['scope'] }}
                                    </span>
                                </td>
                                <td class="text-muted">{{ $policy['desc'] }}</td>
                                <td>
                                    <input type="checkbox" class="pol-toggle" {{ $policy['active'] ? 'checked' : '' }}>
                                </td>
                                <td class="text-muted small">{{ $policy['updated'] }}</td>
                                <td>
                                    <div class="pol-actions-table">
                                        <button class="pol-action-btn" data-modal-open="policyModal">Edit</button>
                                        <button class="pol-action-btn">Clone</button>
                                        <button class="pol-action-btn" data-confirm="Delete {{ $policy['name'] }}?" data-success="Policy deleted.">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pol-card-layout">
            <div class="pol-preview">
                <h3>Policy Preview</h3>
                <p class="text-muted small mb-3">Updates as you edit or select a policy.</p>
                <div class="pol-preview-panel" id="policyPreview">
                    <p class="mb-2"><strong>Lead Time · 24h</strong></p>
                    <p class="text-muted small mb-3">All bookings must be made ≥ 24 hours before start</p>
                    <p class="text-muted small mb-1">Applies to: <strong>All Facilities</strong></p>
                    <div class="pol-rule-card">
                        <h4>Rule #1 · Lead Time</h4>
                        <p>Bookings must be created at least 24 hours before start time.</p>
                    </div>
                    <div class="pol-rule-card">
                        <h4>Rule #2 · Duration</h4>
                        <p>Max session length is 4 hours per booking.</p>
                    </div>
                </div>
            </div>
            <div class="pol-preview">
                <h3>Rule Templates</h3>
                <p class="text-muted small mb-3">Frequently used rule cards.</p>
                @foreach ($ruleTemplates as $index => $rule)
                    <div class="pol-rule-card">
                        <h4>Rule Template {{ $index + 1 }}</h4>
                        <p>{{ $rule['kind'] }} · {{ $rule['operator'] }} · {{ $rule['value'] }}</p>
                    </div>
                @endforeach
                <button class="pol-btn pol-btn-primary mt-2" data-modal-open="policyModal">Use template</button>
            </div>
        </div>
    </div>
</section>

{{-- Create / Edit Policy Modal --}}
<div class="pol-modal-overlay" id="policyModal">
    <div class="pol-modal">
        <header>
            <h3>Policy Builder</h3>
            <button class="pol-action-btn" data-modal-close>&times;</button>
        </header>
        <form class="pol-form" id="policyForm">
            <div class="pol-field">
                <label>Policy name *</label>
                <input type="text" id="policyName" placeholder="e.g., Lead Time · 24h" required>
            </div>
            <div class="pol-field">
                <label>Scope *</label>
                <select id="policyScope">
                    <option value="global">Global · applies to all facilities</option>
                    <option value="facility">Facility-specific</option>
                </select>
            </div>
            <div class="pol-field">
                <label>Description</label>
                <textarea rows="2" id="policyDesc" placeholder="Explain what this policy enforces..."></textarea>
            </div>
            <div class="pol-field">
                <label>Active</label>
                <input type="checkbox" class="pol-toggle" checked>
            </div>

            <div class="pol-rule-builder">
                <header>
                    <h4>Rules</h4>
                    <button type="button" class="pol-btn pol-btn-primary" id="addRuleBtn">Add Rule</button>
                </header>
                <div class="pol-rule-list" id="ruleList">
                    <div class="pol-rule-card" data-rule="1">
                        <h4>Rule #1 · Lead Time</h4>
                        <p>Bookings must be created at least 24 hours before the start time.</p>
                        <div class="pol-actions-table" style="opacity:1;">
                            <button class="pol-action-btn" type="button">Edit</button>
                            <button class="pol-action-btn" type="button">Delete</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pol-field" id="facilityPicker">
                <label>Apply to facilities</label>
                <select multiple>
                    <option>Orion Boardroom</option>
                    <option>Helios Lab</option>
                    <option>Summit Hall</option>
                    <option>Nova Hub</option>
                </select>
            </div>

            <div class="pol-preview-panel">
                <p class="text-muted small mb-2">Natural language preview</p>
                <div id="rulePreviewText" class="pol-json">
                    Bookings must be created at least 24 hours before the event start. Maximum session length is 4 hours.
                </div>
            </div>

            <div class="pol-modal-actions">
                <button type="button" class="pol-btn" data-modal-close>Cancel</button>
                <button type="submit" class="pol-btn pol-btn-primary">Save policy</button>
            </div>
        </form>
    </div>
</div>

{{-- Test Rule Modal --}}
<div class="pol-modal-overlay" id="testRuleModal">
    <div class="pol-modal" style="max-width: 640px;">
        <header>
            <h3>Test Rule Scenario</h3>
            <button class="pol-action-btn" data-modal-close>&times;</button>
        </header>
        <form id="ruleTestForm" class="pol-form">
            <div class="pol-field">
                <label>Facility</label>
                <select>
                    <option>Orion Boardroom</option>
                    <option>Helios Lab</option>
                    <option>Summit Hall</option>
                </select>
            </div>
            <div class="pol-field">
                <label>Date & Time</label>
                <input type="datetime-local">
            </div>
            <div class="pol-field">
                <label>Attendees</label>
                <input type="number" min="1" placeholder="Number of attendees">
            </div>
            <div class="pol-field">
                <label>User role</label>
                <select>
                    <option>Staff</option>
                    <option>Approver</option>
                    <option>Director</option>
                    <option>Guest</option>
                </select>
            </div>
            <div class="pol-field">
                <label>Recurring booking?</label>
                <select>
                    <option>No</option>
                    <option>Weekly</option>
                    <option>Monthly</option>
                </select>
            </div>
            <div class="pol-modal-actions">
                <button type="button" class="pol-btn" data-modal-close>Close</button>
                <button type="submit" class="pol-btn pol-btn-primary">Run test</button>
            </div>
            <div class="pol-test-output" id="testResult">
                Select parameters above and run a test scenario.
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.querySelector('#policySearch');
        const rows = document.querySelectorAll('#policyTable tbody tr');
        const scopeChips = document.querySelectorAll('[data-filter-scope]');
        const statusChips = document.querySelectorAll('[data-filter-status]');
        const modals = document.querySelectorAll('.pol-modal-overlay');
        const modalTriggers = document.querySelectorAll('[data-modal-open]');
        const policyForm = document.querySelector('#policyForm');
        const ruleTestForm = document.querySelector('#ruleTestForm');
        const addRuleBtn = document.querySelector('#addRuleBtn');
        const ruleList = document.querySelector('#ruleList');
        const facilityPicker = document.querySelector('#facilityPicker');
        const rulePreviewText = document.querySelector('#rulePreviewText');
        let scopeFilter = 'all';
        let statusFilter = 'all';

        const closeModal = overlay => overlay.classList.remove('active');
        const openModal = id => document.getElementById(id).classList.add('active');

        modals.forEach(overlay => {
            overlay.addEventListener('click', e => {
                if (e.target === overlay) closeModal(overlay);
            });
        });

        document.querySelectorAll('[data-modal-close]').forEach(btn => {
            btn.addEventListener('click', e => closeModal(e.target.closest('.pol-modal-overlay')));
        });

        modalTriggers.forEach(trigger => {
            trigger.addEventListener('click', () => openModal(trigger.dataset.modalOpen));
        });

        const filterRows = () => {
            const keyword = searchInput.value.trim().toLowerCase();
            rows.forEach(row => {
                const matchesScope = scopeFilter === 'all' || row.dataset.scope === scopeFilter;
                const matchesStatus = statusFilter === 'all' || row.dataset.status === statusFilter;
                const matchesKeyword = !keyword || row.dataset.name.includes(keyword) || row.dataset.description.includes(keyword);
                row.style.display = matchesScope && matchesStatus && matchesKeyword ? '' : 'none';
            });
        };

        scopeChips.forEach(chip => {
            chip.addEventListener('click', () => {
                scopeFilter = chip.dataset.filterScope;
                scopeChips.forEach(c => c.classList.remove('active'));
                chip.classList.add('active');
                filterRows();
            });
        });

        statusChips.forEach(chip => {
            chip.addEventListener('click', () => {
                statusFilter = chip.dataset.filterStatus;
                statusChips.forEach(c => c.classList.remove('active'));
                chip.classList.add('active');
                filterRows();
            });
        });

        searchInput.addEventListener('input', filterRows);

        policyForm.addEventListener('submit', e => {
            e.preventDefault();
            closeModal(document.querySelector('#policyModal'));
            Swal.fire({
                background: 'rgba(0, 11, 28, 0.96)',
                color: '#FDFDFD',
                title: 'Policy saved',
                text: 'Changes will affect new bookings immediately.',
                icon: 'success',
                confirmButtonColor: '#00C950',
            });
        });

        ruleTestForm.addEventListener('submit', e => {
            e.preventDefault();
            document.querySelector('#testResult').textContent = 'Booking violates the Lead Time rule and Food rule.';
        });

        addRuleBtn.addEventListener('click', () => {
            const count = ruleList.querySelectorAll('.pol-rule-card').length + 1;
            const card = document.createElement('div');
            card.className = 'pol-rule-card';
            card.innerHTML = `
                <h4>Rule #${count} · Custom</h4>
                <p>Rule details will appear here once saved.</p>
                <div class="pol-actions-table" style="opacity:1;">
                    <button class="pol-action-btn" type="button">Edit</button>
                    <button class="pol-action-btn" type="button">Delete</button>
                </div>
            `;
            ruleList.appendChild(card);
            rulePreviewText.textContent = `Rule #${count} pending configuration.`;
        });

        document.querySelectorAll('[data-confirm]').forEach(btn => {
            btn.addEventListener('click', () => {
                Swal.fire({
                    background: 'rgba(0, 11, 28, 0.96)',
                    color: '#FDFDFD',
                    title: 'Please confirm',
                    text: btn.dataset.confirm,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#00C950',
                    cancelButtonColor: '#FF6B6B',
                }).then(result => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            background: 'rgba(0, 11, 28, 0.96)',
                            color: '#FDFDFD',
                            title: 'Action completed',
                            text: btn.dataset.success || 'Policy updated.',
                            icon: 'success',
                            confirmButtonColor: '#00C950',
                        });
                    }
                });
            });
        });

        document.querySelector('#policyScope').addEventListener('change', e => {
            facilityPicker.style.display = e.target.value === 'facility' ? 'block' : 'none';
        });
    });
</script>
@endpush
@endsection
