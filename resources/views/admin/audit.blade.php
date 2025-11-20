@extends('layouts.app')

@section('title', 'Admin • Audit Log')

@push('styles')
    @vite(['resources/css/admin/audit.css'])
@endpush

@section('content')
@php
    $entries = collect($entries ?? []);
    $groupedEntries = $entries->groupBy('day');
    $metrics = $metrics ?? ['today' => 0, 'highRisk' => 0, 'failed' => 0, 'topModule' => '—'];
@endphp

<section class="admin-audit-page">
    <div class="admin-audit-shell">
        <a href="{{ route('admin.hub') }}" class="admin-back-button admin-back-button--light">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to admin hub
        </a>
        <p class="audit-breadcrumb">Observability • Audit Log</p>
        <div class="audit-header">
            <div>
                <h1>Audit Log</h1>
                <p>Track every change, anomaly, and approval across ENC.</p>
                <div class="audit-pills">
                    <span class="audit-pill">Live view</span>
                    <span class="audit-pill">Diff-ready</span>
                    <span class="audit-pill">Security-aware</span>
                </div>
            </div>
            <div class="audit-controls">
                <a href="{{ route('admin.audit.export.csv') }}" class="audit-btn">Export CSV</a>
                <a href="{{ route('admin.audit.export.json') }}" class="audit-btn">Export JSON</a>
                <button class="audit-btn audit-btn-primary" type="button" id="copyPermalink">Copy permalink</button>
            </div>
        </div>

        <div class="audit-metrics">
            <article class="audit-metric">
                <div>
                    <p class="text-muted small mb-1">Events today</p>
                    <h3>{{ $metrics['today'] }}</h3>
                    <p class="audit-trend up">Freshest events first</p>
                </div>
                <div class="audit-mini-bars">
                    <span style="height: 46%"></span>
                    <span style="height: 62%"></span>
                    <span style="height: 58%"></span>
                    <span style="height: 82%"></span>
                    <span style="height: 75%"></span>
                </div>
            </article>
            <article class="audit-metric">
                <div>
                    <p class="text-muted small mb-1">High-risk events</p>
                    <h3>6</h3>
                    <p class="audit-trend">3 require review</p>
                </div>
                <span class="audit-badge risk-high">High-signal</span>
            </article>
            <article class="audit-metric">
                <div>
                    <p class="text-muted small mb-1">Failed actions</p>
                    <h3>{{ $metrics['failed'] }}</h3>
                    <p class="audit-trend down">Action needed</p>
                </div>
                <span class="audit-badge risk-medium">Watchlist</span>
            </article>
            <article class="audit-metric">
                <div>
                    <p class="text-muted small mb-1">Top module</p>
                    <h3>{{ $metrics['topModule'] }}</h3>
                    <p class="audit-trend">Most active</p>
                </div>
                <span class="audit-badge">Live</span>
            </article>
        </div>

        <div class="audit-layout">
            <aside class="audit-panel">
                <div class="panel-row">
                    <label for="auditSearch">Search</label>
                    <div class="audit-search">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 4a6 6 0 014.8 9.6l3.3 3.3a1 1 0 01-1.4 1.4l-3.3-3.3A6 6 0 1110 4zm0 2a4 4 0 100 8 4 4 0 000-8z" fill="currentColor"/></svg>
                        <input type="search" id="auditSearch" placeholder="Actor, action, target, IP">
                    </div>
                </div>

                <div class="panel-row">
                    <label>Timeframe</label>
                    <div class="audit-chip-row">
                        <button class="audit-chip active" data-filter-timeframe="all">All</button>
                        <button class="audit-chip" data-filter-timeframe="today">Today</button>
                        <button class="audit-chip" data-filter-timeframe="week">This week</button>
                        <button class="audit-chip" data-filter-timeframe="30d">Last 30d</button>
                    </div>
                </div>

                <div class="panel-row">
                    <label>Module</label>
                    <div class="audit-chip-row">
                        <button class="audit-chip active" data-filter-module="all">All</button>
                        <button class="audit-chip" data-filter-module="Bookings">Bookings</button>
                        <button class="audit-chip" data-filter-module="Facilities">Facilities</button>
                        <button class="audit-chip" data-filter-module="Users">Users</button>
                        <button class="audit-chip" data-filter-module="Policies">Policies</button>
                        <button class="audit-chip" data-filter-module="Approvals">Approvals</button>
                        <button class="audit-chip" data-filter-module="Auth">Auth</button>
                    </div>
                </div>

                <div class="panel-row">
                    <label>Actor type</label>
                    <div class="audit-chip-row">
                        <button class="audit-chip active" data-filter-actor="all">All</button>
                        <button class="audit-chip" data-filter-actor="admin">Admin</button>
                        <button class="audit-chip" data-filter-actor="user">User</button>
                        <button class="audit-chip" data-filter-actor="system">System</button>
                        <button class="audit-chip" data-filter-actor="api">API</button>
                    </div>
                </div>

                <div class="panel-row">
                    <label>Risk</label>
                    <div class="audit-chip-row">
                        <button class="audit-chip active" data-filter-risk="all">Any</button>
                        <button class="audit-chip" data-filter-risk="high">High</button>
                        <button class="audit-chip" data-filter-risk="medium">Medium</button>
                        <button class="audit-chip" data-filter-risk="low">Low</button>
                    </div>
                </div>

                <div class="panel-row">
                    <label>Status</label>
                    <div class="audit-chip-row">
                        <button class="audit-chip active" data-filter-status="all">Any</button>
                        <button class="audit-chip" data-filter-status="success">Success</button>
                        <button class="audit-chip" data-filter-status="failed">Failed</button>
                    </div>
                </div>

                <div class="panel-row">
                    <label>Saved views</label>
                    <div class="audit-saved-views">
                        <button class="audit-saved" data-saved-view="all">All events</button>
                        <button class="audit-saved" data-saved-view="security">Security-sensitive</button>
                        <button class="audit-saved" data-saved-view="myteam">My team changes</button>
                        <button class="audit-saved" data-saved-view="failures">Failures only</button>
                    </div>
                </div>

                <div class="panel-row panel-row--inline">
                    <div>
                        <p class="text-muted small mb-1">Live mode</p>
                        <p id="liveStatus" class="audit-live-copy">Streaming (simulated)</p>
                    </div>
                    <button class="audit-toggle" id="liveToggle" aria-pressed="true"><span></span></button>
                </div>

                <div class="panel-row panel-row--inline">
                    <div>
                        <p class="text-muted small mb-1">Anomaly filter</p>
                        <p class="audit-live-copy">Surface unusual IPs, failures, high risk.</p>
                    </div>
                    <label class="audit-switch">
                        <input type="checkbox" id="anomalyToggle">
                        <span></span>
                    </label>
                </div>

                <div class="panel-footer">
                    <p class="text-muted small mb-1">Last updated</p>
                    <p id="lastUpdated">Just now</p>
                </div>
            </aside>

            <div class="audit-main">
                <div class="audit-toolbar">
                    <div class="audit-toolbar-left">
                        <div class="audit-label">View</div>
                        <div class="audit-view-pills">
                            <span class="audit-pill neutral">Timeline</span>
                            <span class="audit-pill neutral">Diff-on-open</span>
                            <span class="audit-pill neutral">Group by day</span>
                        </div>
                    </div>
                    <div class="audit-toolbar-right">
                        <button class="audit-btn">Share view</button>
                        <button class="audit-btn audit-btn-primary">Create alert</button>
                    </div>
                </div>

                <div class="audit-timeline" id="auditTimeline">
                    @foreach ($groupedEntries as $day => $items)
                        @php $dayKey = strtolower(str_replace(' ', '-', $day)); @endphp
                        <div class="audit-day" data-day-label="{{ $dayKey }}">
                            <div class="audit-day-header">
                                <div>
                                    <p class="text-muted small mb-1">{{ $day }}</p>
                                    <strong>{{ count($items) }} events</strong>
                                </div>
                                <div class="audit-day-chip">Grouped by {{ $day }}</div>
                            </div>
                            <div class="audit-day-line"></div>
                            <div class="audit-day-body">
                                @foreach ($items as $entry)
                                    <article
                                        class="audit-card"
                                        data-audit-card
                                        data-module="{{ $entry['module'] }}"
                                        data-risk="{{ $entry['risk'] }}"
                                        data-status="{{ $entry['status'] }}"
                                        data-actor="{{ strtolower($entry['actor']) }}"
                                        data-action="{{ strtolower($entry['action']) }}"
                                        data-target="{{ strtolower($entry['target']) }}"
                                        data-day="{{ strtolower(str_replace(' ', '', $entry['day'])) }}"
                                        data-actor-type="{{ strtolower($entry['actor_type'] ?? 'admin') }}"
                                        data-flag-url="{{ route('admin.audit.flag', $entry['id']) }}"
                                        data-export-url="{{ route('admin.audit.export.entry', $entry['id']) }}"
                                        data-detail='@json($entry)'
                                    >
                                        <div class="audit-card-header">
                                            <div class="audit-card-meta">
                                                <span class="audit-badge">{{ $entry['module'] }}</span>
                                                <span class="audit-dot {{ $entry['risk'] }}"></span>
                                                <span class="audit-label risk-{{ $entry['risk'] }}">{{ ucfirst($entry['risk']) }} risk</span>
                                                <span class="audit-label status-{{ $entry['status'] }}">{{ ucfirst($entry['status']) }}</span>
                                            </div>
                                            <div class="audit-card-time">
                                                <span>{{ $entry['timestamp'] }}</span>
                                                <button class="audit-btn audit-ghost" data-detail-btn>Open</button>
                                            </div>
                                        </div>
                                        <div class="audit-card-body">
                                            <div class="audit-actor">
                                                <span class="audit-avatar">{{ strtoupper(substr($entry['actor'], 0, 1)) }}</span>
                                                <div>
                                                    <strong>{{ $entry['actor'] }}</strong>
                                                    <p class="text-muted small mb-0">{{ $entry['email'] }} • {{ $entry['source'] }}</p>
                                                </div>
                                            </div>
                                            <div class="audit-action">
                                                <h4>{{ $entry['action'] }}</h4>
                                                <p class="text-muted small mb-0">Target: {{ $entry['target'] }}</p>
                                            </div>
                                            <div class="audit-card-grid">
                                                <div>
                                                    <p class="text-muted small mb-1">Context</p>
                                                    <p class="audit-context">{{ $entry['notes'] }}</p>
                                                </div>
                                                <div class="audit-meta-grid">
                                                    <span class="audit-subpill">Env: {{ $entry['environment'] }}</span>
                                                    <span class="audit-subpill">IP: {{ $entry['ip'] }}</span>
                                                    <span class="audit-subpill">Loc: {{ $entry['location'] }}</span>
                                                    <span class="audit-subpill">Device: {{ $entry['device'] }}</span>
                                                </div>
                                            </div>
                                            <div class="audit-changes">
                                                @foreach ($entry['changes'] as $change)
                                                    <span class="audit-change-chip">{{ $change }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<div class="audit-overlay" id="auditOverlay"></div>
<aside class="audit-drawer" id="auditDrawer" aria-hidden="true">
    <header>
        <div>
            <h3 id="drawerTitle">Action Summary</h3>
            <p class="text-muted small mb-0" id="drawerSubtitle">Module • Timestamp</p>
        </div>
        <button class="audit-btn" id="closeAuditDrawer">×</button>
    </header>
    <div class="audit-drawer-meta">
        <span class="audit-label" id="drawerModule">—</span>
        <span class="audit-label" id="drawerRisk">—</span>
        <span class="audit-label" id="drawerStatus">—</span>
    </div>
    <div class="audit-drawer-section">
        <h4>Actor</h4>
        <p id="drawerActor">—</p>
        <p class="text-muted small mb-0" id="drawerSource">—</p>
    </div>
    <div class="audit-drawer-section">
        <h4>Action</h4>
        <p id="drawerAction">—</p>
    </div>
    <div class="audit-drawer-section">
        <h4>Entity</h4>
        <p id="drawerEntity">—</p>
    </div>
    <div class="audit-drawer-section audit-drawer-grid">
        <div>
            <h4>IP / Location</h4>
            <p id="drawerIp">—</p>
            <p class="text-muted small mb-0" id="drawerLocation">—</p>
        </div>
        <div>
            <h4>Device</h4>
            <p id="drawerDevice">—</p>
            <p class="text-muted small mb-0" id="drawerEnv">—</p>
        </div>
    </div>
    <div class="audit-drawer-section">
        <h4>Before</h4>
        <pre class="audit-diff" id="drawerBefore"><code>—</code></pre>
    </div>
    <div class="audit-drawer-section">
        <h4>After</h4>
        <pre class="audit-diff" id="drawerAfter"><code>—</code></pre>
    </div>
    <div class="audit-drawer-section">
        <h4>Notes</h4>
        <p id="drawerNotes">—</p>
    </div>
    <div class="audit-drawer-section audit-drawer-grid">
        <div>
            <h4>Session</h4>
            <p id="drawerSession">—</p>
        </div>
        <div>
            <h4>Correlation</h4>
            <p id="drawerCorrelation">—</p>
        </div>
    </div>
    <div class="audit-drawer-actions">
        <form method="POST" id="flagForm" style="display:inline;">
            @csrf
            <button class="audit-btn" type="submit">Flag for review</button>
        </form>
        <a class="audit-btn audit-btn-primary" id="exportEntryBtn" href="#">Export entry</a>
    </div>
</aside>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const cards = document.querySelectorAll('[data-audit-card]');
        const searchInput = document.querySelector('#auditSearch');
        const moduleChips = document.querySelectorAll('[data-filter-module]');
        const riskChips = document.querySelectorAll('[data-filter-risk]');
        const statusChips = document.querySelectorAll('[data-filter-status]');
        const timeframeChips = document.querySelectorAll('[data-filter-timeframe]');
        const actorChips = document.querySelectorAll('[data-filter-actor]');
        const savedViews = document.querySelectorAll('[data-saved-view]');
        const anomalyToggle = document.querySelector('#anomalyToggle');
        const liveToggle = document.querySelector('#liveToggle');
        const liveStatus = document.querySelector('#liveStatus');
        const lastUpdated = document.querySelector('#lastUpdated');
        const drawer = document.querySelector('#auditDrawer');
        const overlay = document.querySelector('#auditOverlay');
        const closeDrawerBtn = document.querySelector('#closeAuditDrawer');
        const copyPermalink = document.querySelector('#copyPermalink');
        const flagForm = document.querySelector('#flagForm');
        const exportEntryBtn = document.querySelector('#exportEntryBtn');

        let filters = {
            module: 'all',
            risk: 'all',
            status: 'all',
            timeframe: 'all',
            keyword: '',
            actorType: 'all',
        };

        const savedViewsConfig = {
            all: { module: 'all', risk: 'all', status: 'all', timeframe: 'all', keyword: '' },
            security: { module: 'Policies', risk: 'high', status: 'all', timeframe: '30d', keyword: '' },
            myteam: { module: 'Facilities', risk: 'all', status: 'all', timeframe: 'week', keyword: '' },
            failures: { module: 'all', risk: 'all', status: 'failed', timeframe: 'all', keyword: '' },
        };

        const openDrawer = (detail) => {
            drawer.classList.add('open');
            overlay.classList.add('open');
            drawer.setAttribute('aria-hidden', 'false');
            document.querySelector('#drawerTitle').textContent = detail.action || 'Action';
            document.querySelector('#drawerSubtitle').textContent = `${detail.module} • ${detail.timestamp}`;
            document.querySelector('#drawerActor').textContent = `${detail.actor} (${detail.email})`;
            document.querySelector('#drawerSource').textContent = `${detail.source} • ${detail.environment}`;
            document.querySelector('#drawerAction').textContent = detail.action;
            document.querySelector('#drawerEntity').textContent = detail.target;
            document.querySelector('#drawerBefore').innerHTML = renderDiff(detail.before, detail.after) || '—';
            document.querySelector('#drawerAfter').innerHTML = renderDiff(detail.after, detail.before) || '—';
            document.querySelector('#drawerModule').textContent = detail.module;
            document.querySelector('#drawerRisk').textContent = `${(detail.risk || '—')} risk`;
            document.querySelector('#drawerStatus').textContent = detail.status ? detail.status.toUpperCase() : '—';
            document.querySelector('#drawerIp').textContent = detail.ip || '—';
            document.querySelector('#drawerLocation').textContent = detail.location || '—';
            document.querySelector('#drawerDevice').textContent = detail.device || '—';
            document.querySelector('#drawerEnv').textContent = detail.environment || '—';
            document.querySelector('#drawerNotes').textContent = detail.notes || '—';
            document.querySelector('#drawerSession').textContent = detail.session || '—';
            document.querySelector('#drawerCorrelation').textContent = detail.correlation || '—';
            if (flagForm) {
                flagForm.action = detail.flag_url || `{{ url('/admin/audit') }}/${detail.id}/flag`;
            }
            if (exportEntryBtn) {
                exportEntryBtn.href = detail.export_url || `{{ url('/admin/audit') }}/${detail.id}/export`;
            }
        };

        const renderDiff = (current, other) => {
            if (!current && !other) return '—';

            // Scalar comparison
            if (typeof current !== 'object' || current === null || Array.isArray(current)) {
                if (current === other || JSON.stringify(current) === JSON.stringify(other)) {
                    return `<span class="diff-line diff-unchanged">${escapeHtml(formatValue(current))}</span>`;
                }
                return [
                    `<span class="diff-line diff-removed">- ${escapeHtml(formatValue(other))}</span>`,
                    `<span class="diff-line diff-added">+ ${escapeHtml(formatValue(current))}</span>`
                ].join('');
            }

            const beforeObj = (typeof other === 'object' && other !== null) ? other : {};
            const keys = Array.from(new Set([...Object.keys(current || {}), ...Object.keys(beforeObj)]));
            const lines = [];

            keys.forEach(key => {
                const beforeVal = beforeObj[key];
                const afterVal = current[key];
                const beforeStr = formatValue(beforeVal);
                const afterStr = formatValue(afterVal);

                if (JSON.stringify(beforeVal) === JSON.stringify(afterVal)) {
                    lines.push(`<span class="diff-line diff-unchanged">${escapeHtml(key)}: ${escapeHtml(afterStr)}</span>`);
                } else {
                    if (beforeVal !== undefined) {
                        lines.push(`<span class="diff-line diff-removed">- ${escapeHtml(key)}: ${escapeHtml(beforeStr)}</span>`);
                    }
                    if (afterVal !== undefined) {
                        lines.push(`<span class="diff-line diff-added">+ ${escapeHtml(key)}: ${escapeHtml(afterStr)}</span>`);
                    }
                }
            });

            return lines.join('') || '—';
        };

        const formatValue = (value) => {
            if (value === null || value === undefined) return '—';
            if (Array.isArray(value)) return value.join(', ');
            if (typeof value === 'object') return JSON.stringify(value);
            return String(value);
        };

        const escapeHtml = (unsafe) => {
            return String(unsafe)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        };

        const closeDrawer = () => {
            drawer.classList.remove('open');
            overlay.classList.remove('open');
            drawer.setAttribute('aria-hidden', 'true');
        };

        cards.forEach(card => {
            const detailBtn = card.querySelector('[data-detail-btn]');
            detailBtn.addEventListener('click', () => {
                const detail = JSON.parse(card.dataset.detail);
                detail.flag_url = card.dataset.flagUrl;
                detail.export_url = card.dataset.exportUrl;
                openDrawer(detail);
            });
        });

        overlay.addEventListener('click', closeDrawer);
        closeDrawerBtn.addEventListener('click', closeDrawer);

        const matchTimeframe = (card) => {
            if (filters.timeframe === 'all') return true;
            const day = card.dataset.day;
            if (filters.timeframe === 'today') return day === 'today';
            if (filters.timeframe === 'week') return day === 'thisweek' || day === 'today' || day === 'yesterday';
            if (filters.timeframe === '30d') return true; // placeholder until backend dates arrive
            return true;
        };

        const filterRows = () => {
            const keyword = filters.keyword;
            cards.forEach(card => {
                const moduleMatch = filters.module === 'all' || card.dataset.module === filters.module;
                const riskMatch = filters.risk === 'all' || card.dataset.risk === filters.risk;
                const statusMatch = filters.status === 'all' || card.dataset.status === filters.status;
                const actorTypeMatch = filters.actorType === 'all' || card.dataset.actorType === filters.actorType;
                const keywordMatch = !keyword
                    || card.dataset.actor.includes(keyword)
                    || card.dataset.action.includes(keyword)
                    || card.dataset.target.includes(keyword);
                const timeframeMatch = matchTimeframe(card);
                const anomalyMatch = anomalyToggle.checked ? (card.dataset.risk === 'high' || card.dataset.status === 'failed') : true;
                const visible = moduleMatch && riskMatch && statusMatch && actorTypeMatch && keywordMatch && timeframeMatch && anomalyMatch;
                card.style.display = visible ? '' : 'none';
            });
        };

        const setActiveChip = (list, valueKey, value) => {
            list.forEach(chip => {
                chip.classList.toggle('active', chip.dataset[valueKey] === value);
            });
        };

        const applyFiltersToUi = () => {
            searchInput.value = filters.keyword;
            setActiveChip(moduleChips, 'filterModule', filters.module);
            setActiveChip(riskChips, 'filterRisk', filters.risk);
            setActiveChip(statusChips, 'filterStatus', filters.status);
            setActiveChip(timeframeChips, 'filterTimeframe', filters.timeframe);
            setActiveChip(actorChips, 'filterActor', filters.actorType);
            filterRows();
        };

        const readFiltersFromQuery = () => {
            const params = new URLSearchParams(window.location.search);
            const paramOr = (key, fallback = '') => params.get(key) ?? fallback;
            filters.module = paramOr('module', filters.module);
            filters.risk = paramOr('risk', filters.risk);
            filters.status = paramOr('status', filters.status);
            filters.timeframe = paramOr('timeframe', filters.timeframe);
            filters.keyword = paramOr('q', '').toLowerCase();
            filters.actorType = paramOr('actor', filters.actorType);
            anomalyToggle.checked = params.get('anomaly') === '1';
            applyFiltersToUi();
        };

        const buildPermalink = () => {
            const url = new URL(window.location.href);
            url.searchParams.set('module', filters.module);
            url.searchParams.set('risk', filters.risk);
            url.searchParams.set('status', filters.status);
            url.searchParams.set('timeframe', filters.timeframe);
            url.searchParams.set('q', filters.keyword || '');
            url.searchParams.set('actor', filters.actorType);
            if (anomalyToggle.checked) {
                url.searchParams.set('anomaly', '1');
            } else {
                url.searchParams.delete('anomaly');
            }
            return url.toString();
        };

        moduleChips.forEach(chip => {
            chip.addEventListener('click', () => {
                filters.module = chip.dataset.filterModule;
                applyFiltersToUi();
            });
        });

        riskChips.forEach(chip => {
            chip.addEventListener('click', () => {
                filters.risk = chip.dataset.filterRisk;
                applyFiltersToUi();
            });
        });

        statusChips.forEach(chip => {
            chip.addEventListener('click', () => {
                filters.status = chip.dataset.filterStatus;
                applyFiltersToUi();
            });
        });

        timeframeChips.forEach(chip => {
            chip.addEventListener('click', () => {
                filters.timeframe = chip.dataset.filterTimeframe;
                applyFiltersToUi();
            });
        });

        actorChips.forEach(chip => {
            chip.addEventListener('click', () => {
                filters.actorType = chip.dataset.filterActor;
                applyFiltersToUi();
            });
        });

        savedViews.forEach(viewBtn => {
            viewBtn.addEventListener('click', () => {
                const config = savedViewsConfig[viewBtn.dataset.savedView] || savedViewsConfig.all;
                filters = { ...config };
                applyFiltersToUi();
            });
        });

        anomalyToggle.addEventListener('change', filterRows);

        searchInput.addEventListener('input', (e) => {
            filters.keyword = e.target.value.trim().toLowerCase();
            filterRows();
        });

        const refreshUpdatedTime = () => {
            const now = new Date();
            lastUpdated.textContent = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        };

        refreshUpdatedTime();
        setInterval(refreshUpdatedTime, 45000);

        liveToggle.addEventListener('click', () => {
            const isOn = liveToggle.classList.toggle('active');
            liveToggle.setAttribute('aria-pressed', isOn ? 'true' : 'false');
            liveStatus.textContent = isOn ? 'Streaming (simulated)' : 'Paused';
        });

        if (copyPermalink) {
            copyPermalink.addEventListener('click', async () => {
                const permalink = buildPermalink();
                try {
                    await navigator.clipboard.writeText(permalink);
                    copyPermalink.textContent = 'Copied!';
                    setTimeout(() => (copyPermalink.textContent = 'Copy permalink'), 1500);
                } catch (e) {
                    copyPermalink.textContent = 'Copy failed';
                    setTimeout(() => (copyPermalink.textContent = 'Copy permalink'), 1500);
                }
            });
        }

        readFiltersFromQuery();
    });
</script>
@endpush
@endsection
