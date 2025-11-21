@extends('layouts.app')

@section('title', 'Admin • Rules & Policies')

@push('styles')
    @vite(['resources/css/admin/policies.css'])
@endpush

@section('content')
<section class="admin-policies-page">
    <div class="admin-policies-shell">
        <a href="{{ route('admin.hub') }}" class="admin-back-button admin-back-button--light">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to admin hub
        </a>
        <p class="pol-breadcrumb">Admin Hub · Rules & Policies</p>
        <div class="pol-header">
            <div>
                <h1>Rules & Policies</h1>
                <p>Define, edit, and apply booking policies across Booking, SFI, and Shuttle services.</p>
            </div>
            <div class="pol-actions">
                <button class="pol-btn pol-btn-primary" data-modal-open="policyModal">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                        <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                    </svg>
                    Create Policy
                </button>
            </div>
        </div>

        <div class="pol-grid-balanced pol-guide-bar">
            <div class="pol-note small pol-guide">
                <div class="pol-guide-top">
                    <div>
                        <p class="pol-label">Quick guide</p>
                        <h3>See what is live, edit fast, keep copy simple</h3>
                    </div>
                    <div class="pol-kpi-rail-inline">
                        <div class="pol-kpi">
                            <p>Active</p>
                            <strong>{{ $activeCount }}</strong>
                        </div>
                        <div class="pol-kpi pol-kpi-outline">
                            <p>Drafts</p>
                            <strong>{{ $draftCount }}</strong>
                        </div>
                    </div>
                </div>
                <ul class="pol-note-list">
                    <li>Use tabs for Bookings, SFI, and Shuttle to view current rules.</li>
                    <li>Click a row to read the summary; use Edit to update.</li>
                    <li>Keep titles short and reminders up to date.</li>
                </ul>
            </div>
        </div>

        <div class="pol-stack">
                <div class="pol-surface pol-table-shell">
                    <div class="pol-table-head">
                        <div>
                            <p class="pol-label">Bookings</p>
                            <p class="text-muted small">All active and draft booking policies</p>
                        </div>
                        <div class="pol-table-head-actions">
                            <select id="filterStatus" class="pol-select">
                                <option value="all">All status</option>
                                <option value="active">Active</option>
                                <option value="draft">Draft</option>
                            </select>
                            <input type="search" id="policySearch" class="pol-input" placeholder="Search by name, owner, tag">
                            <button class="pol-btn pol-btn-primary" data-modal-open="policyModal">Create policy</button>
                        </div>
                    </div>
                    <div class="pol-table-wrapper">
                        <table class="pol-table pol-table-simple" data-table="bookings">
                            <thead>
                                <tr>
                                    <th>Policy</th>
                                    <th>Status</th>
                                    <th>Owner</th>
                                    <th>Reminder</th>
                                    <th>Last updated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bookings as $policy)
                                    <tr
                                        data-policy-row
                                        data-table-type="bookings"
                                        data-id="{{ $policy->id }}"
                                        data-name="{{ $policy->name }}"
                                        data-desc="{{ $policy->desc }}"
                                        data-scope="{{ $policy->domain_key }}"
                                        data-status="{{ $policy->status }}"
                                        data-owner="{{ $policy->owner }}"
                                        data-reminder="{{ $policy->reminder }}"
                                        data-updated="{{ optional($policy->updated_at)->format('M d · h:i A') }}"
                                        data-impact="{{ $policy->impact }}"
                                        data-tags="{{ implode(',', $policy->tags ?? []) }}"
                                        data-expiring="{{ $policy->expiring ? 'true' : 'false' }}"
                                        data-needs-review="{{ $policy->needs_review ? 'true' : 'false' }}"
                                        data-policy='@json($policy->toArray())'
                                    >
                                        <td>
                                            <strong>{{ $policy->name }}</strong>
                                            <div class="text-muted small">Tags: {{ implode(', ', $policy->tags ?? []) }}</div>
                                        </td>
                                        <td>
                                            <span class="pol-pill pol-pill-{{ $policy->status }}">{{ ucfirst($policy->status) }}</span>
                                        </td>
                                        <td class="text-muted small">{{ $policy->owner }}</td>
                                        <td class="text-muted small">{{ $policy->reminder }}</td>
                                        <td class="text-muted small">{{ optional($policy->updated_at)->format('M d · h:i A') }}</td>
                                        <td>
                                            <div class="pol-actions-table inline">
                                                <button class="pol-action-btn" data-modal-open="policyModal" data-action="edit">Edit</button>
                                                <button class="pol-action-btn pol-action-status" data-action="status" data-status="{{ $policy->status }}">Activate / Reactivate</button>
                                                <button class="pol-action-btn pol-danger" data-action="delete" data-confirm="Delete {{ $policy->name }}?" data-success="Policy deleted.">Delete</button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="pol-surface pol-table-shell">
                    <div class="pol-table-head">
                        <div>
                            <p class="pol-label">SFI</p>
                            <p class="text-muted small">Suites and premium rooms policies</p>
                        </div>
                    </div>
                    <div class="pol-table-wrapper">
                        <table class="pol-table pol-table-simple" data-table="sfi">
                            <thead>
                                <tr>
                                    <th>Policy</th>
                                    <th>Status</th>
                                    <th>Owner</th>
                                    <th>Reminder</th>
                                    <th>Last updated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sfi as $policy)
                                    <tr
                                        data-policy-row
                                        data-table-type="sfi"
                                        data-id="{{ $policy->id }}"
                                        data-name="{{ $policy->name }}"
                                        data-desc="{{ $policy->desc }}"
                                        data-scope="{{ $policy->domain_key }}"
                                        data-status="{{ $policy->status }}"
                                        data-owner="{{ $policy->owner }}"
                                        data-reminder="{{ $policy->reminder }}"
                                        data-updated="{{ optional($policy->updated_at)->format('M d · h:i A') }}"
                                        data-impact="{{ $policy->impact }}"
                                        data-tags="{{ implode(',', $policy->tags ?? []) }}"
                                        data-expiring="{{ $policy->expiring ? 'true' : 'false' }}"
                                        data-needs-review="{{ $policy->needs_review ? 'true' : 'false' }}"
                                        data-policy='@json($policy->toArray())'
                                    >
                                        <td>
                                            <strong>{{ $policy->name }}</strong>
                                            <div class="text-muted small">Tags: {{ implode(', ', $policy->tags ?? []) }}</div>
                                        </td>
                                        <td>
                                            <span class="pol-pill pol-pill-{{ $policy->status }}">{{ ucfirst($policy->status) }}</span>
                                        </td>
                                        <td class="text-muted small">{{ $policy->owner }}</td>
                                        <td class="text-muted small">{{ $policy->reminder }}</td>
                                        <td class="text-muted small">{{ optional($policy->updated_at)->format('M d · h:i A') }}</td>
                                        <td>
                                            <div class="pol-actions-table inline">
                                                <button class="pol-action-btn" data-modal-open="policyModal" data-action="edit">Edit</button>
                                                <button class="pol-action-btn pol-action-status" data-action="status" data-status="{{ $policy->status }}">Activate / Reactivate</button>
                                                <button class="pol-action-btn pol-danger" data-action="delete" data-confirm="Delete {{ $policy->name }}?" data-success="Policy deleted.">Delete</button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="pol-surface pol-table-shell">
                    <div class="pol-table-head">
                        <div>
                            <p class="pol-label">Shuttle</p>
                            <p class="text-muted small">Rider-facing rules</p>
                        </div>
                    </div>
                    <div class="pol-table-wrapper">
                        <table class="pol-table pol-table-simple" data-table="shuttle">
                            <thead>
                                <tr>
                                    <th>Policy</th>
                                    <th>Status</th>
                                    <th>Owner</th>
                                    <th>Reminder</th>
                                    <th>Last updated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($shuttle as $policy)
                                    <tr
                                        data-policy-row
                                        data-table-type="shuttle"
                                        data-id="{{ $policy->id }}"
                                        data-name="{{ $policy->name }}"
                                        data-desc="{{ $policy->desc }}"
                                        data-scope="{{ $policy->domain_key }}"
                                        data-status="{{ $policy->status }}"
                                        data-owner="{{ $policy->owner }}"
                                        data-reminder="{{ $policy->reminder }}"
                                        data-updated="{{ optional($policy->updated_at)->format('M d · h:i A') }}"
                                        data-impact="{{ $policy->impact }}"
                                        data-tags="{{ implode(',', $policy->tags ?? []) }}"
                                        data-expiring="{{ $policy->expiring ? 'true' : 'false' }}"
                                        data-needs-review="{{ $policy->needs_review ? 'true' : 'false' }}"
                                        data-policy='@json($policy->toArray())'
                                    >
                                        <td>
                                            <strong>{{ $policy->name }}</strong>
                                            <div class="text-muted small">Tags: {{ implode(', ', $policy->tags ?? []) }}</div>
                                        </td>
                                        <td>
                                            <span class="pol-pill pol-pill-{{ $policy->status }}">{{ ucfirst($policy->status) }}</span>
                                        </td>
                                        <td class="text-muted small">{{ $policy->owner }}</td>
                                        <td class="text-muted small">{{ $policy->reminder }}</td>
                                        <td class="text-muted small">{{ optional($policy->updated_at)->format('M d · h:i A') }}</td>
                                        <td>
                                            <div class="pol-actions-table inline">
                                                <button class="pol-action-btn" data-modal-open="policyModal" data-action="edit">Edit</button>
                                                <button class="pol-action-btn pol-action-status" data-action="status" data-status="{{ $policy->status }}">Activate / Reactivate</button>
                                                <button class="pol-action-btn pol-danger" data-action="delete" data-confirm="Delete {{ $policy->name }}?" data-success="Policy deleted.">Delete</button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
            <input type="hidden" id="policyId">
            <div class="pol-field">
                <label>Policy name *</label>
                <input type="text" id="policyName" placeholder="e.g., Lead Time · 24h" required>
            </div>
            <div class="pol-field">
                <label>Policy type *</label>
                <select id="policyScope">
                    <option value="bookings">Bookings</option>
                    <option value="sfi">SFI</option>
                    <option value="shuttle">Shuttle</option>
                </select>
            </div>
            <div class="pol-field">
                <label>Owner</label>
                <input type="text" id="policyOwner" placeholder="e.g., Legal Ops">
            </div>
            <div class="pol-field">
                <label>Reminder</label>
                <input type="text" id="policyReminder" placeholder="e.g., Review quarterly">
            </div>
            <div class="pol-field">
                <label>Description</label>
                <textarea rows="2" id="policyDesc" placeholder="Explain what this policy enforces..."></textarea>
            </div>
            <div class="pol-field">
                <label>Tags (comma separated)</label>
                <input type="text" id="policyTags" placeholder="Lead Time, Cutoff">
            </div>
            <div class="pol-field">
                <label>Impact / display note</label>
                <textarea rows="2" id="policyImpact" placeholder="Where this shows up or what it blocks (optional)"></textarea>
            </div>
            <div class="pol-field">
                <label>Active</label>
                <input type="checkbox" class="pol-toggle" id="policyActive" checked>
            </div>

            <div class="pol-rule-builder">
                <header>
                    <h4>Rules</h4>
                    <button type="button" class="pol-btn pol-btn-primary" id="addRuleBtn">Add Rule</button>
                </header>
                <div class="pol-rule-list" id="ruleList"></div>
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
        const filterStatus = document.querySelector('#filterStatus');
        const rows = Array.from(document.querySelectorAll('[data-policy-row]'));
        const policyIdInput = document.querySelector('#policyId');
        const policyNameInput = document.querySelector('#policyName');
        const policyScopeInput = document.querySelector('#policyScope');
        const policyOwnerInput = document.querySelector('#policyOwner');
        const policyReminderInput = document.querySelector('#policyReminder');
        const policyDescInput = document.querySelector('#policyDesc');
        const policyTagsInput = document.querySelector('#policyTags');
        const policyImpactInput = document.querySelector('#policyImpact');
        const policyActiveInput = document.querySelector('#policyActive');
        const preview = {
            title: document.querySelector('#previewTitle'),
            desc: document.querySelector('#previewDesc'),
            impact: document.querySelector('#previewImpact'),
            reminder: document.querySelector('#previewReminder'),
            tags: document.querySelector('#detailTags'),
        };
        const modals = document.querySelectorAll('.pol-modal-overlay');
        const modalTriggers = document.querySelectorAll('[data-modal-open]');
        const policyForm = document.querySelector('#policyForm');
        const ruleTestForm = document.querySelector('#ruleTestForm');
        const addRuleBtn = document.querySelector('#addRuleBtn');
        const ruleList = document.querySelector('#ruleList');
        const rulePreviewText = document.querySelector('#rulePreviewText');
        const filters = { status: 'all', keyword: '' };
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const policyCache = {};
        let activePolicy = null;


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
            trigger.addEventListener('click', () => {
                resetForm();
                setActivePolicy(null);
                openModal(trigger.dataset.modalOpen);
            });
        });

        const applyFilters = () => {
            const keyword = filters.keyword.trim().toLowerCase();
            rows.forEach(row => {
                const matchesStatus = filters.status === 'all' || row.dataset.status === filters.status;
                const name = (row.dataset.name || '').toLowerCase();
                const desc = (row.dataset.desc || '').toLowerCase();
                const tags = (row.dataset.tags || '').toLowerCase();
                const owner = (row.dataset.owner || '').toLowerCase();
                const matchesKeyword =
                    !keyword || name.includes(keyword) || desc.includes(keyword) || tags.includes(keyword) || owner.includes(keyword);
                row.style.display = matchesStatus && matchesKeyword ? '' : 'none';
            });
        };

        const renderTags = tagsString => {
            if (!preview.tags) return;
            preview.tags.innerHTML = '';
            (tagsString || '')
                .split(',')
                .map(tag => tag.trim())
                .filter(Boolean)
                .forEach(tag => {
                    const chip = document.createElement('span');
                    chip.className = 'pol-chip pol-chip-ghost';
                    chip.textContent = tag;
                    preview.tags.appendChild(chip);
                });
        };

        const parsePolicyFromRow = row => {
            if (row.dataset.policy) {
                try {
                    return JSON.parse(row.dataset.policy);
                } catch (e) {
                    // fall through
                }
            }
            return {
                id: row.dataset.id,
                name: row.dataset.name,
                desc: row.dataset.desc,
                domain_key: row.dataset.scope,
                status: row.dataset.status,
                owner: row.dataset.owner,
                reminder: row.dataset.reminder,
                impact: row.dataset.impact,
                tags: (row.dataset.tags || '')
                    .split(',')
                    .map(t => t.trim())
                    .filter(Boolean),
                rules: [],
            };
        };

        rows.forEach(row => {
            const policy = parsePolicyFromRow(row);
            if (policy.id) {
                policyCache[policy.id] = policy;
            }
        });

        const setPreview = policy => {
            if (!policy || !preview.title) return;
            preview.title.textContent = policy.name || 'Select a policy';
            preview.desc.textContent = policy.desc || '';
            if (preview.impact) preview.impact.textContent = policy.impact || '—';
            if (preview.reminder) preview.reminder.textContent = policy.reminder || '—';
            renderTags((policy.tags || []).join(','));
        };

        const renderRules = policy => {
            ruleList.innerHTML = '';
            const rules = policy?.rules || [];
            if (!rules.length) {
                ruleList.innerHTML = '<div class="text-muted small">No rules yet.</div>';
                rulePreviewText.textContent = 'Add rules to see the natural language preview.';
                return;
            }

            rules.forEach((rule, idx) => {
                const card = document.createElement('div');
                card.className = 'pol-rule-card';
                card.dataset.ruleId = rule.id;
                card.innerHTML = `
                    <h4>Rule #${idx + 1} · ${rule.title || 'Custom'}</h4>
                    <p>${rule.summary || ''}</p>
                    <div class="pol-actions-table" style="opacity:1;">
                        <button class="pol-action-btn" type="button" data-rule-edit>Edit</button>
                        <button class="pol-action-btn" type="button" data-rule-delete>Delete</button>
                    </div>
                `;
                ruleList.appendChild(card);
            });

            rulePreviewText.textContent = rules.map(r => r.summary || r.title).filter(Boolean).join(' ');
        };

        const setActivePolicy = policy => {
            activePolicy = policy;
            setPreview(policy);
            renderRules(policy);

            if (!policy) {
                resetForm();
                return;
            }

            policyIdInput.value = policy.id || '';
            policyNameInput.value = policy.name || '';
            policyScopeInput.value = policy.domain_key || 'bookings';
            policyOwnerInput.value = policy.owner || '';
            policyReminderInput.value = policy.reminder || '';
            policyDescInput.value = policy.desc || '';
            policyTagsInput.value = (policy.tags || []).join(', ');
            policyImpactInput.value = policy.impact || '';
            policyActiveInput.checked = policy.status === 'active';
        };

        const setPreviewFromRow = row => {
            const policy = policyCache[row.dataset.id] || parsePolicyFromRow(row);
            setActivePolicy(policy);
            rows.forEach(r => r.classList.remove('active'));
            row.classList.add('active');
        };

        const updateActionButtons = () => {
            document.querySelectorAll('.pol-action-status').forEach(btn => {
                const status = (btn.dataset.status || '').toLowerCase();
                btn.classList.remove('pol-positive', 'pol-negative');
                if (status === 'draft') {
                    btn.textContent = 'Confirm';
                    btn.classList.add('pol-positive');
                } else if (status === 'active') {
                    btn.textContent = 'Deactivate';
                    btn.classList.add('pol-negative');
                } else {
                    btn.textContent = 'Reactivate';
                    btn.classList.add('pol-positive');
                }
            });
        };

        rows.forEach(row => {
            row.addEventListener('click', () => setPreviewFromRow(row));

            row.querySelectorAll('.pol-action-btn').forEach(btn => {
                btn.addEventListener('click', async e => {
                    e.preventDefault();
                    e.stopPropagation();
                    if (e.stopImmediatePropagation) e.stopImmediatePropagation();
                    const action = btn.dataset.action;
                    const policy = policyCache[row.dataset.id] || parsePolicyFromRow(row);

                    if (action === 'edit') {
                        setActivePolicy(policy);
                        openModal('policyModal');
                        return;
                    }

                    if (action === 'status') {
                        const nextStatus = (policy.status || '').toLowerCase() === 'active' ? 'draft' : 'active';
                        await mutate(`/admin/policies/${policy.id}/status`, 'POST', { status: nextStatus });
                        window.location.reload();
                        return;
                    }

                    if (action === 'delete') {
                        const confirmed = await confirmDialog(btn.dataset.confirm || 'Delete policy?');
                        if (!confirmed) return;
                        await mutate(`/admin/policies/${policy.id}`, 'DELETE');
                        window.location.reload();
                    }
                });
            });
        });

        if (filterStatus) {
            filterStatus.addEventListener('change', () => {
                filters.status = filterStatus.value;
                applyFilters();
            });
        }

        if (searchInput) {
            searchInput.addEventListener('input', () => {
                filters.keyword = searchInput.value;
                applyFilters();
            });
        }

        if (rows.length) {
            rows[0].classList.add('active');
            setPreviewFromRow(rows[0]);
        }
        applyFilters();
        updateActionButtons();

        policyForm.addEventListener('submit', async e => {
            e.preventDefault();
            const payload = {
                name: policyNameInput.value.trim(),
                domain_key: policyScopeInput.value,
                owner: policyOwnerInput.value.trim() || null,
                reminder: policyReminderInput.value.trim() || null,
                desc: policyDescInput.value.trim() || null,
                tags: policyTagsInput.value,
                impact: policyImpactInput.value.trim() || null,
                active: policyActiveInput.checked,
            };

            if (!payload.name) {
                alert('Policy name is required.');
                return;
            }

            const policyId = policyIdInput.value;
            const method = policyId ? 'PUT' : 'POST';
            const url = policyId ? `/admin/policies/${policyId}` : '/admin/policies';

            await mutate(url, method, payload, 'Policy saved');
            window.location.reload();
        });

        ruleTestForm.addEventListener('submit', e => {
            e.preventDefault();
            document.querySelector('#testResult').textContent = 'Booking violates the Lead Time rule and Food rule.';
        });

        addRuleBtn.addEventListener('click', async () => {
            if (!activePolicy?.id) {
                alert('Save the policy first before adding rules.');
                return;
            }

            const title = prompt('Rule title', 'Custom Rule');
            if (!title) return;
            const summary = prompt('Rule summary', '') || '';

            await mutate(`/admin/policies/${activePolicy.id}/rules`, 'POST', {
                title,
                summary,
            }, 'Rule added');
            window.location.reload();
        });

        ruleList.addEventListener('click', async e => {
            const card = e.target.closest('.pol-rule-card');
            if (!card) return;

            const ruleId = card.dataset.ruleId;
            if (e.target.matches('[data-rule-edit]')) {
                const currentTitle = card.querySelector('h4')?.textContent?.replace(/^Rule #[0-9]+ · /, '') || 'Rule';
                const currentSummary = card.querySelector('p')?.textContent || '';
                const title = prompt('Rule title', currentTitle);
                if (!title) return;
                const summary = prompt('Rule summary', currentSummary) || '';
                await mutate(`/admin/policies/rules/${ruleId}`, 'PUT', { title, summary }, 'Rule updated');
                window.location.reload();
            }

            if (e.target.matches('[data-rule-delete]')) {
                const confirmed = await confirmDialog('Delete this rule?');
                if (!confirmed) return;
                await mutate(`/admin/policies/rules/${ruleId}`, 'DELETE', null, 'Rule deleted');
                window.location.reload();
            }
        });

        const resetForm = () => {
            policyIdInput.value = '';
            policyNameInput.value = '';
            policyScopeInput.value = 'bookings';
            policyOwnerInput.value = '';
            policyReminderInput.value = '';
            policyDescInput.value = '';
            policyTagsInput.value = '';
            policyImpactInput.value = '';
            policyActiveInput.checked = true;
        };

        const confirmDialog = message => {
            return Swal.fire({
                background: 'rgba(0, 11, 28, 0.96)',
                color: '#FDFDFD',
                title: 'Please confirm',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#00C950',
                cancelButtonColor: '#FF6B6B',
            }).then(result => result.isConfirmed);
        };

        async function mutate(url, method, payload = null, successMessage = 'Saved') {
            const res = await fetch(url, {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: payload ? JSON.stringify(payload) : null,
            });

            if (!res.ok) {
                const errorText = await res.text();
                Swal.fire({
                    background: 'rgba(0, 11, 28, 0.96)',
                    color: '#FDFDFD',
                    title: 'Error',
                    text: errorText || 'Something went wrong.',
                    icon: 'error',
                    confirmButtonColor: '#FF6B6B',
                });
                throw new Error(errorText);
            }

            if (successMessage) {
                Swal.fire({
                    background: 'rgba(0, 11, 28, 0.96)',
                    color: '#FDFDFD',
                    title: successMessage,
                    icon: 'success',
                    confirmButtonColor: '#00C950',
                });
            }

            try {
                return await res.json();
            } catch (e) {
                return null;
            }
        }
    });
</script>
@endpush
@endsection
