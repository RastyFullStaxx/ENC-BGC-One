@extends('layouts.app')

@section('title', 'Admin • Analytics & Reports')

@push('styles')
    @vite(['resources/css/admin/analytics.css'])
@endpush

@section('content')
@php
    $kpis = $kpis ?? [
        ['label' => 'Total Bookings', 'value' => '482', 'note' => 'Last 30 days'],
        ['label' => 'Approved vs Cancelled', 'value' => '89% / 11%', 'note' => 'Green = approved'],
        ['label' => 'Most Booked Room', 'value' => 'Orion Boardroom', 'note' => '62 bookings'],
        ['label' => 'Peak Day', 'value' => 'Wednesday', 'note' => 'Most check-ins'],
    ];

    $demandRanking = $demandRanking ?? [
        ['name' => 'Orion Boardroom', 'bookings' => 62, 'hours' => 188, 'conflicts' => 4, 'maintenance' => 'Aug 02', 'score' => '4.8'],
        ['name' => 'Helios Lab', 'bookings' => 54, 'hours' => 160, 'conflicts' => 6, 'maintenance' => 'Jul 26', 'score' => '4.6'],
        ['name' => 'Summit Hall', 'bookings' => 42, 'hours' => 220, 'conflicts' => 2, 'maintenance' => 'Jul 15', 'score' => '4.9'],
        ['name' => 'Nova Hub', 'bookings' => 33, 'hours' => 110, 'conflicts' => 1, 'maintenance' => 'Aug 05', 'score' => '4.4'],
    ];

    $utilizationStats = $utilizationStats ?? [
        'labels' => ['Orion', 'Helios', 'Summit', 'Nova', 'Atlas', 'Forum'],
        'values' => [89, 78, 92, 70, 64, 58],
    ];

    $peakHoursStats = $peakHoursStats ?? [
        'labels' => ['7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20'],
        'values' => [12, 24, 45, 72, 68, 50, 40, 55, 70, 80, 54, 30, 15, 5],
    ];

    $departmentShare = $departmentShare ?? [
        'labels' => ['Creative', 'Operations', 'Admin Office', 'Mobility', 'Finance'],
        'values' => [23, 31, 18, 16, 12],
    ];

    $statusBreakdown = $statusBreakdown ?? [
        'labels' => ['Requested', 'Approved', 'Pending', 'Rejected', 'Cancelled', 'No-show'],
        'values' => [60, 320, 40, 15, 35, 12],
    ];

    $noShowReasons = $noShowReasons ?? [
        'labels' => ['Team conflict', 'Weather', 'Unapproved', 'No response'],
        'values' => [45, 18, 22, 15],
    ];

    $recurrenceStats = $recurrenceStats ?? [
        'labels' => ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
        'values' => [22, 28, 31, 36],
    ];

    $pairs = fn($labels, $values) => collect($labels)->zip($values)->map(fn($set) => ['name' => $set[0], 'value' => $set[1]]);

    $utilPairs = $pairs($utilizationStats['labels'], $utilizationStats['values']);
    $utilTop = $utilPairs->sortByDesc('value')->first() ?? ['name' => 'N/A', 'value' => 0];
    $utilBottom = $utilPairs->sortBy('value')->take(2)->pluck('name')->filter()->implode(' and ');
    $utilBottomText = $utilBottom ?: 'Lower-performing rooms';

    $peakPairs = $pairs($peakHoursStats['labels'], $peakHoursStats['values'])->sortByDesc('value')->values();
    $peakTop = $peakPairs->get(0) ?? ['name' => 'N/A', 'value' => 0];
    $peakSecond = $peakPairs->get(1) ?? ['name' => 'N/A', 'value' => 0];
    $peakLow = $peakPairs->sortBy('value')->first() ?? ['name' => 'N/A', 'value' => 0];

    $deptPairs = $pairs($departmentShare['labels'], $departmentShare['values'])->sortByDesc('value')->values();
    $deptTop = $deptPairs->first() ?? ['name' => 'N/A', 'value' => 0];
    $deptLow = $deptPairs->last() ?? ['name' => 'N/A', 'value' => 0];

    $statusPairs = $pairs($statusBreakdown['labels'], $statusBreakdown['values']);
    $statusTotal = max($statusPairs->sum('value'), 1);
    $statusCancelled = data_get($statusPairs->firstWhere('name', 'Cancelled'), 'value', 0);
    $statusNoShow = data_get($statusPairs->firstWhere('name', 'No-show'), 'value', 0);
    $statusCancelledPct = round((($statusCancelled + $statusNoShow) / $statusTotal) * 100);

    $noShowPairs = $pairs($noShowReasons['labels'], $noShowReasons['values'])->sortByDesc('value')->values();
    $noShowTop = $noShowPairs->first() ?? ['name' => 'N/A', 'value' => 0];

    $recurrenceValues = collect($recurrenceStats['values']);
    $recurrenceFirst = $recurrenceValues->first() ?? 0;
    $recurrenceLast = $recurrenceValues->last() ?? 0;
    $recurrenceDeltaPct = $recurrenceFirst ? round((($recurrenceLast - $recurrenceFirst) / $recurrenceFirst) * 100) : 0;

    $insights = [
        'utilization' => [
            "<strong>{$utilTop['name']}</strong> leads at {$utilTop['value']}% utilization — keep current scheduling.",
            "<strong>{$utilBottomText}</strong> trail utilization; reroute recurring bookings here to balance load.",
            "<strong>Action:</strong> apply soft holds to underused rooms during mid-week peaks.",
        ],
        'peakHours' => [
            "Highest load at <strong>{$peakTop['name']}:00</strong> ({$peakTop['value']} bookings); watch for collisions.",
            "Secondary peak at <strong>{$peakSecond['name']}:00</strong>; batch approvals to avoid stacking.",
            "Off-peak near <strong>{$peakLow['name']}:00</strong> — best window for maintenance or rebooked slots.",
        ],
        'department' => [
            "<strong>{$deptTop['name']}</strong> drives the most bookings — consider nudging them to alternate buildings.",
            "<strong>{$deptLow['name']}</strong> holds the smallest share; opportunities to free inventory there.",
            "<strong>Action:</strong> share weekly slot inventory with heavy users to reduce ad-hoc conflicts.",
        ],
        'status' => [
            "Combined <strong>cancelled + no-show</strong> at {$statusCancelledPct}% of requests — track reductions weekly.",
            "<strong>Pending</strong> volume stays low; approvals are flowing as expected.",
            "<strong>Action:</strong> auto-email teams with multiple declines to rebook off-peak times.",
        ],
        'noShow' => [
            "<strong>{$noShowTop['name']}</strong> is the top no-show driver — target coaching with approvers.",
            "<strong>Action:</strong> auto-release slots 15 minutes post start if no check-in is recorded.",
            "<strong>Action:</strong> require reason codes for same-day cancellations to capture patterns.",
        ],
        'recurrence' => [
            "Recurring bookings are up <strong>{$recurrenceDeltaPct}%</strong> vs. week 1; growth looks healthy.",
            "<strong>Action:</strong> lock recurring series into underutilized rooms first.",
            "<strong>Action:</strong> flag weeks with >20% jump so facilities can pre-stage equipment.",
        ],
    ];
@endphp

<section class="admin-analytics-page">
    <div class="admin-analytics-shell">
        <a href="{{ route('admin.hub') }}" class="admin-back-button admin-back-button--light">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to admin hub
        </a>
        <p class="analytics-breadcrumb">Admin Hub · Analytics</p>
        <div class="analytics-header">
            <div>
                <h1>Analytics & Reports</h1>
                <p>Insights on facility usage, bookings, and peak demand.</p>
            </div>
            <div class="analytics-actions">
                <input type="date" class="analytics-btn" value="{{ now()->subDays(30)->format('Y-m-d') }}">
                <input type="date" class="analytics-btn" value="{{ now()->format('Y-m-d') }}">
                <button class="analytics-btn analytics-btn-primary" id="downloadCsvBtn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                        <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                    </svg>
                    Download CSV
                </button>
                <button class="analytics-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                        <path d="M5 4h14v16H5z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M8 4v3h8V4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                    </svg>
                    Download PDF
                </button>
            </div>
        </div>

        <div class="analytics-surface analytics-guide">
            <div>
                <p class="analytics-overline">How to read this page</p>
                <h3 class="mb-2">Start with filters, then scan charts + notes together.</h3>
                <p class="text-muted mb-0">Pick a date range, skim the KPI cards, then read each chart beside its interpretation. Look for the green or yellow callouts to spot risks or wins fast.</p>
            </div>
            <div class="guide-steps">
                <div class="guide-step">
                    <span>1</span>
                    <p class="mb-0">Set timelines and scope using the chips and date inputs.</p>
                </div>
                <div class="guide-step">
                    <span>2</span>
                    <p class="mb-0">Compare chart + interpretation box together; they’re paired on purpose.</p>
                </div>
                <div class="guide-step">
                    <span>3</span>
                    <p class="mb-0">Use the quick actions on each note to jump to remediation.</p>
                </div>
            </div>
        </div>

        <div class="analytics-surface">
            <div class="analytics-filters">
                <div class="analytics-chip-group">
                    <button class="analytics-chip active" data-filter="date">Last 30 days</button>
                    <button class="analytics-chip" data-filter="date">This quarter</button>
                    <button class="analytics-chip" data-filter="date">Year to date</button>
                </div>
                <div class="analytics-chip-group analytics-divider">
                    <button class="analytics-chip active" data-filter="department">All departments</button>
                    <button class="analytics-chip" data-filter="department">Creative</button>
                    <button class="analytics-chip" data-filter="department">Operations</button>
                    <button class="analytics-chip" data-filter="department">Admin Office</button>
                </div>
                <div class="analytics-chip-group analytics-divider">
                    <button class="analytics-chip active" data-filter="building">All buildings</button>
                    <button class="analytics-chip" data-filter="building">Building A</button>
                    <button class="analytics-chip" data-filter="building">Building B</button>
                </div>
                <div class="analytics-chip-group analytics-divider">
                    <button class="analytics-chip active" data-filter="type">All types</button>
                    <button class="analytics-chip" data-filter="type">Meeting Rooms</button>
                    <button class="analytics-chip" data-filter="type">Training Rooms</button>
                    <button class="analytics-chip" data-filter="type">Event Halls</button>
                </div>
            </div>

            <div class="analytics-kpis">
                @foreach ($kpis as $kpi)
                    <div class="analytics-kpi-card">
                        <span>{{ $kpi['label'] }}</span>
                        <strong>{{ $kpi['value'] }}</strong>
                        <p class="text-muted small mb-0 kpi-note">{{ $kpi['note'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="analytics-visual-grid">
            <div class="analytics-card analytics-split-card">
                <div class="analytics-card-header">
                    <div>
                        <p class="analytics-overline">Capacity vs usage</p>
                        <h3>Facility Utilization</h3>
                        <p class="text-muted small mb-3">Top 6 rooms by percent of available hours consumed.</p>
                    </div>
                    <span class="analytics-pill analytics-pill--success">Target ≥ 75%</span>
                </div>
                <div class="analytics-visual-body">
                    <div class="chart-area">
                        <canvas id="utilizationChart" aria-label="Facility utilization chart" role="img"></canvas>
                    </div>
                    <div class="analytics-interpretation">
                        <p class="analytics-overline mb-1">Interpretation</p>
                        <ul class="analytics-note-list">
                            @foreach ($insights['utilization'] as $line)
                                <li>{!! $line !!}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="analytics-card analytics-split-card">
                <div class="analytics-card-header">
                    <div>
                        <p class="analytics-overline">Time-of-day load</p>
                        <h3>Peak Hours</h3>
                        <p class="text-muted small mb-3">Bookings per hour, weekdays only.</p>
                    </div>
                    <span class="analytics-pill analytics-pill--warning">Watch 10–16H band</span>
                </div>
                <div class="analytics-visual-body">
                    <div class="chart-area">
                        <canvas id="peakHoursChart" aria-label="Peak hour distribution" role="img"></canvas>
                    </div>
                    <div class="analytics-interpretation">
                        <p class="analytics-overline mb-1">Interpretation</p>
                        <ul class="analytics-note-list">
                            @foreach ($insights['peakHours'] as $line)
                                <li>{!! $line !!}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="analytics-card analytics-split-card">
                <div class="analytics-card-header">
                    <div>
                        <p class="analytics-overline">Demand by function</p>
                        <h3>Bookings by Department</h3>
                        <p class="text-muted small mb-3">Share of all bookings by department.</p>
                    </div>
                    <span class="analytics-pill analytics-pill--info">Pulse check</span>
                </div>
                <div class="analytics-visual-body">
                    <div class="chart-area">
                        <canvas id="departmentChart" aria-label="Bookings by department" role="img"></canvas>
                    </div>
                    <div class="analytics-interpretation">
                        <p class="analytics-overline mb-1">Interpretation</p>
                        <ul class="analytics-note-list">
                            @foreach ($insights['department'] as $line)
                                <li>{!! $line !!}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="analytics-card analytics-split-card">
                <div class="analytics-card-header">
                    <div>
                        <p class="analytics-overline">Health of requests</p>
                        <h3>Booking Status</h3>
                        <p class="text-muted small mb-3">Volume by approval status for the selected window.</p>
                    </div>
                    <span class="analytics-pill analytics-pill--neutral">Monitor declines</span>
                </div>
                <div class="analytics-visual-body">
                    <div class="chart-area">
                        <canvas id="statusChart" aria-label="Booking statuses" role="img"></canvas>
                    </div>
                    <div class="analytics-interpretation">
                        <p class="analytics-overline mb-1">Interpretation</p>
                        <ul class="analytics-note-list">
                            @foreach ($insights['status'] as $line)
                                <li>{!! $line !!}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="analytics-surface">
            <div class="analytics-card-header mb-2">
                <div>
                    <p class="analytics-overline">Prioritize attention</p>
                    <h3 class="mb-0">Room Demand Ranking</h3>
                </div>
                <span class="analytics-pill analytics-pill--info">Sort by conflicts to triage</span>
            </div>
            <p class="text-muted small mb-3">Use this list to decide which rooms need schedule smoothing, additional capacity, or maintenance windows.</p>
            <div class="analytics-table-wrapper">
                <table class="analytics-table" id="demandTable">
                    <thead>
                        <tr>
                            <th>Facility Name</th>
                            <th>Bookings</th>
                            <th>Total Hours</th>
                            <th>Conflicts</th>
                            <th>Last Maintenance</th>
                            <th>Satisfaction</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($demandRanking as $row)
                            <tr>
                                <td>{{ $row['name'] }}</td>
                                <td>{{ $row['bookings'] }}</td>
                                <td>{{ $row['hours'] }}</td>
                                <td>{{ $row['conflicts'] }}</td>
                                <td>{{ $row['maintenance'] }}</td>
                                <td>{{ $row['score'] }}/5</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="analytics-visual-grid">
            <div class="analytics-card analytics-split-card">
                <div class="analytics-card-header">
                    <div>
                        <p class="analytics-overline">Attendance risk</p>
                        <h3>No-show & Cancellations</h3>
                        <p class="mb-1"><strong>10%</strong> cancellation rate</p>
                        <p class="text-muted small mb-0">12 no-shows flagged • 7 reasons logged</p>
                    </div>
                    <span class="analytics-pill analytics-pill--warning">Mitigate now</span>
                </div>
                <div class="analytics-visual-body">
                    <div class="chart-area">
                        <canvas id="noShowChart" aria-label="No show reasons" role="img"></canvas>
                    </div>
                    <div class="analytics-interpretation">
                        <p class="analytics-overline mb-1">Interpretation</p>
                        <ul class="analytics-note-list">
                            @foreach ($insights['noShow'] as $line)
                                <li>{!! $line !!}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="analytics-card analytics-split-card">
                <div class="analytics-card-header">
                    <div>
                        <p class="analytics-overline">Patterns worth keeping</p>
                        <h3>Recurrence Patterns</h3>
                        <p class="text-muted small mb-0">Weekly + monthly bookings currently tracked.</p>
                    </div>
                    <span class="analytics-pill analytics-pill--success">Healthy climb</span>
                </div>
                <div class="analytics-visual-body">
                    <div class="chart-area">
                        <canvas id="recurrenceChart" aria-label="Recurrence patterns" role="img"></canvas>
                    </div>
                    <div class="analytics-interpretation">
                        <p class="analytics-overline mb-1">Interpretation</p>
                        <ul class="analytics-note-list">
                            @foreach ($insights['recurrence'] as $line)
                                <li>{!! $line !!}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="analytics-card analytics-stack-card">
                <div class="analytics-card-header">
                    <div>
                        <p class="analytics-overline">Things to address</p>
                        <h3>Policy Alerts</h3>
                        <p class="text-muted small mb-0">Pins the key interpretations you should respond to.</p>
                    </div>
                </div>
                <ul class="list-unstyled mb-0 analytics-alert-list">
                    <li>
                        <div class="analytics-alert">
                            <span class="analytics-status-pill cancelled">No-show spike</span>
                            <div>
                                <p class="mb-0">Creative Dept had 3 no-shows this week.</p>
                                <p class="text-muted small mb-0">Pair with “No-show & Cancellations” chart to see root causes.</p>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="analytics-alert">
                            <span class="analytics-status-pill pending">Maintenance reminder</span>
                            <div>
                                <p class="mb-0">Summit Hall policy expiring in 4 days.</p>
                                <p class="text-muted small mb-0">Schedule after 18:00 (off-peak window above).</p>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="analytics-alert">
                            <span class="analytics-status-pill approved">Balanced usage</span>
                            <div>
                                <p class="mb-0">Building B halls trending up by 12%.</p>
                                <p class="text-muted small mb-0">Keep pushing Operations to book there (see dept share chart).</p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="analytics-surface">
            <h3>Reports & exports</h3>
            <div class="analytics-downloads">
                <button class="analytics-ghost-btn">Download CSV</button>
                <button class="analytics-ghost-btn">Download PDF</button>
                <button class="analytics-ghost-btn">Export charts</button>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const datasets = {
            utilization: @json($utilizationStats),
            peakHours: @json($peakHoursStats),
            department: @json($departmentShare),
            status: @json($statusBreakdown),
            noShow: @json($noShowReasons),
            recurrence: @json($recurrenceStats),
        };

        const createChart = (id, config) => {
            const ctx = document.getElementById(id);
            if (!ctx) return null;
            return new Chart(ctx, config);
        };

        const gradientBar = (ctx, color) => {
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, color);
            gradient.addColorStop(1, 'rgba(255,255,255,0.05)');
            return gradient;
        };

        createChart('utilizationChart', {
            type: 'bar',
            data: {
                labels: datasets.utilization.labels,
                datasets: [{
                    label: 'Utilization %',
                    data: datasets.utilization.values,
                    backgroundColor: (ctx) => gradientBar(ctx.chart.ctx, 'rgba(21,93,252,0.8)'),
                    borderRadius: 12
                }]
            },
            options: {
                scales: {
                    y: { ticks: { color: '#FDFDFD' }, grid: { color: 'rgba(255,255,255,0.08)' } },
                    x: { ticks: { color: '#FDFDFD' }, grid: { display: false } }
                },
                plugins: { legend: { display: false } }
            }
        });

        createChart('peakHoursChart', {
            type: 'line',
            data: {
                labels: datasets.peakHours.labels,
                datasets: [{
                    label: 'Bookings',
                    data: datasets.peakHours.values,
                    borderColor: '#FFD66B',
                    backgroundColor: 'rgba(255,214,107,0.2)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                scales: {
                    y: { ticks: { color: '#FDFDFD' }, grid: { color: 'rgba(255,255,255,0.08)' } },
                    x: { ticks: { color: '#FDFDFD' }, grid: { display: false } }
                },
                plugins: { legend: { display: false } }
            }
        });

        createChart('departmentChart', {
            type: 'doughnut',
            data: {
                labels: datasets.department.labels,
                datasets: [{
                    data: datasets.department.values,
                    backgroundColor: ['#4CB4FF', '#00C950', '#FFC857', '#FF6B6B', '#748FFC'],
                    borderWidth: 0
                }]
            },
            options: {
                plugins: {
                    legend: { position: 'bottom', labels: { color: '#FDFDFD' } }
                }
            }
        });

        createChart('statusChart', {
            type: 'bar',
            data: {
                labels: datasets.status.labels,
                datasets: [{
                    data: datasets.status.values,
                    backgroundColor: ['#748FFC', '#00C950', '#4CB4FF', '#FF6B6B', '#FFC857', '#A78BFA'],
                    borderRadius: 10
                }]
            },
            options: {
                scales: {
                    y: { ticks: { color: '#FDFDFD' }, grid: { color: 'rgba(255,255,255,0.08)' } },
                    x: { ticks: { color: '#FDFDFD' }, grid: { display: false } }
                },
                plugins: { legend: { display: false } }
            }
        });

        createChart('noShowChart', {
            type: 'pie',
            data: {
                labels: datasets.noShow.labels,
                datasets: [{
                    data: datasets.noShow.values,
                    backgroundColor: ['#FF6B6B', '#FFC857', '#748FFC', '#4CB4FF'],
                }]
            },
            options: {
                plugins: { legend: { position: 'bottom', labels: { color: '#FDFDFD' } } }
            }
        });

        createChart('recurrenceChart', {
            type: 'line',
            data: {
                labels: datasets.recurrence.labels,
                datasets: [{
                    label: 'Recurring bookings',
                    data: datasets.recurrence.values,
                    borderColor: '#748FFC',
                    backgroundColor: 'rgba(116,143,252,0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: {
                    y: { ticks: { color: '#FDFDFD' }, grid: { color: 'rgba(255,255,255,0.08)' } },
                    x: { ticks: { color: '#FDFDFD' }, grid: { display: false } }
                }
            }
        });

        document.querySelectorAll('.analytics-chip').forEach(chip => {
            chip.addEventListener('click', () => {
                chip.parentElement.querySelectorAll('.analytics-chip').forEach(c => c.classList.remove('active'));
                chip.classList.add('active');
            });
        });

        document.querySelectorAll('[data-confirm]').forEach(btn => {
            btn.addEventListener('click', () => {
                Swal.fire({
                    background: 'rgba(0, 11, 28, 0.96)',
                    color: '#FDFDFD',
                    title: 'Action completed',
                    text: btn.dataset.success || 'Report has been downloaded.',
                    icon: 'success',
                    confirmButtonColor: '#00C950',
                });
            });
        });
    });
</script>
@endpush
@endsection
