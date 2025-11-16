@extends('layouts.app')

@section('title', 'Admin • Analytics & Reports')

@push('styles')
    @vite(['resources/css/admin/analytics.css'])
@endpush

@section('content')
@php
    $kpis = [
        ['label' => 'Total Bookings', 'value' => '482', 'note' => 'Last 30 days'],
        ['label' => 'Approved vs Cancelled', 'value' => '89% / 11%', 'note' => 'Green = approved'],
        ['label' => 'Most Booked Room', 'value' => 'Orion Boardroom', 'note' => '62 bookings'],
        ['label' => 'Peak Day', 'value' => 'Wednesday', 'note' => 'Most check-ins'],
    ];

    $demandRanking = [
        ['name' => 'Orion Boardroom', 'bookings' => 62, 'hours' => 188, 'conflicts' => 4, 'maintenance' => 'Aug 02', 'score' => '4.8'],
        ['name' => 'Helios Lab', 'bookings' => 54, 'hours' => 160, 'conflicts' => 6, 'maintenance' => 'Jul 26', 'score' => '4.6'],
        ['name' => 'Summit Hall', 'bookings' => 42, 'hours' => 220, 'conflicts' => 2, 'maintenance' => 'Jul 15', 'score' => '4.9'],
        ['name' => 'Nova Hub', 'bookings' => 33, 'hours' => 110, 'conflicts' => 1, 'maintenance' => 'Aug 05', 'score' => '4.4'],
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
                        <p class="text-muted small mb-0">{{ $kpi['note'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="analytics-grid">
            <div class="analytics-card">
                <h3>Facility Utilization</h3>
                <canvas id="utilizationChart" aria-label="Facility utilization chart" role="img"></canvas>
            </div>
            <div class="analytics-card">
                <h3>Peak Hours</h3>
                <canvas id="peakHoursChart" aria-label="Peak hour distribution" role="img"></canvas>
            </div>
            <div class="analytics-card">
                <h3>Bookings by Department</h3>
                <canvas id="departmentChart" aria-label="Bookings by department" role="img"></canvas>
            </div>
            <div class="analytics-card">
                <h3>Booking Status</h3>
                <canvas id="statusChart" aria-label="Booking statuses" role="img"></canvas>
            </div>
        </div>

        <div class="analytics-surface">
            <h3>Room Demand Ranking</h3>
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

        <div class="analytics-grid">
            <div class="analytics-card">
                <h3>No-show & Cancellations</h3>
                <p class="mb-1"><strong>10%</strong> cancellation rate</p>
                <p class="text-muted small mb-3">12 no-shows flagged • 7 reasons logged</p>
                <canvas id="noShowChart" aria-label="No show reasons" role="img"></canvas>
            </div>
            <div class="analytics-card">
                <h3>Recurrence Patterns</h3>
                <p class="text-muted small mb-3">Weekly + monthly bookings currently tracked</p>
                <canvas id="recurrenceChart" aria-label="Recurrence patterns" role="img"></canvas>
            </div>
            <div class="analytics-card">
                <h3>Policy Alerts</h3>
                <ul class="list-unstyled mb-0">
                    <li class="mb-3">
                        <span class="analytics-status-pill cancelled">No-show spike</span>
                        <p class="mb-0">Creative Dept had 3 no-shows this week.</p>
                    </li>
                    <li class="mb-3">
                        <span class="analytics-status-pill pending">Maintenance reminder</span>
                        <p class="mb-0">Summit Hall policy expiring in 4 days.</p>
                    </li>
                    <li>
                        <span class="analytics-status-pill approved">Balanced usage</span>
                        <p class="mb-0">Building B halls trending up by 12%.</p>
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
                labels: ['Orion', 'Helios', 'Summit', 'Nova', 'Atlas', 'Forum'],
                datasets: [{
                    label: 'Utilization %',
                    data: [89, 78, 92, 70, 64, 58],
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
                labels: ['7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20'],
                datasets: [{
                    label: 'Bookings',
                    data: [12, 24, 45, 72, 68, 50, 40, 55, 70, 80, 54, 30, 15, 5],
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
                labels: ['Creative', 'Operations', 'Admin Office', 'Mobility', 'Finance'],
                datasets: [{
                    data: [23, 31, 18, 16, 12],
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
                labels: ['Requested', 'Approved', 'Pending', 'Rejected', 'Cancelled', 'No-show'],
                datasets: [{
                    data: [60, 320, 40, 15, 35, 12],
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
                labels: ['Team conflict', 'Weather', 'Unapproved', 'No response'],
                datasets: [{
                    data: [45, 18, 22, 15],
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
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                datasets: [{
                    label: 'Recurring bookings',
                    data: [22, 28, 31, 36],
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
