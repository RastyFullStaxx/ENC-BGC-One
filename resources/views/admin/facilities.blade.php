@extends('layouts.app')

@section('title', 'Admin • Facilities Management')

@push('styles')
    @vite(['resources/css/admin/facilities.css'])
@endpush

@section('content')
@php
    $facilities = [
        [
            'name' => 'Orion Boardroom',
            'building' => 'Building A',
            'floor' => '12F',
            'type' => 'Meeting Room',
            'capacity' => 16,
            'status' => 'Active',
            'status_key' => 'active',
            'photo' => 'https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=900&q=60',
            'equipment' => ['LED Wall', 'VC Suite', 'Coffee Station'],
        ],
        [
            'name' => 'Helios Training Lab',
            'building' => 'Building B',
            'floor' => '4F',
            'type' => 'Training Room',
            'capacity' => 32,
            'status' => 'Under Maintenance',
            'status_key' => 'maintenance',
            'photo' => 'https://images.unsplash.com/photo-1524758870432-af57e54afa26?auto=format&fit=crop&w=900&q=60',
            'equipment' => ['Projector', 'Whiteboards', 'Lapel Microphones'],
        ],
        [
            'name' => 'Summit Hall',
            'building' => 'Building A',
            'floor' => 'GF',
            'type' => 'Event Hall',
            'capacity' => 150,
            'status' => 'Active',
            'status_key' => 'active',
            'photo' => 'https://images.unsplash.com/photo-1503420564238-11f5fe3223b4?auto=format&fit=crop&w=900&q=60',
            'equipment' => ['Stage Lighting', 'Sound System', 'Podium'],
        ],
        [
            'name' => 'Nova Collaboration Hub',
            'building' => 'Building B',
            'floor' => '7F',
            'type' => 'Collaboration Space',
            'capacity' => 24,
            'status' => 'Active',
            'status_key' => 'active',
            'photo' => 'https://images.unsplash.com/photo-1431540015161-0bf868a2d407?auto=format&fit=crop&w=900&q=60',
            'equipment' => ['Breakout Pods', 'TVs', 'Acoustic Panels'],
        ],
    ];

    $policies = [
        ['title' => 'Food & Beverage', 'desc' => 'Allowed only for rooms with pantry access'],
        ['title' => 'Session Duration', 'desc' => 'Max 4 hours for training rooms'],
        ['title' => 'Approval Needed', 'desc' => 'Executive floors require director approval'],
    ];

    $equipmentOptions = ['Projector', 'LED Wall', 'Wireless Mic', 'Speakerphone', 'Whiteboard', 'Extension Cords', 'Telepresence Kit'];
@endphp

<section class="admin-facilities-page">
    <div class="admin-facilities-shell">
        <a href="{{ route('admin.hub') }}" class="admin-back-button admin-back-button--light">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to admin hub
        </a>
        <p class="fac-breadcrumb">Admin Hub · Facilities</p>
        <div class="fac-header">
            <div>
                <h1>Facilities Management</h1>
                <p>Manage rooms across both buildings. Keep photos, hours, and policies accurate.</p>
            </div>
            <button class="fac-btn fac-btn-primary" data-modal-open="facilityModal">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                    <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
                Add Facility
            </button>
        </div>

        <div class="fac-grid" style="margin-bottom: 32px;">
            <div class="fac-widget">
                <h3>Total facilities</h3>
                <strong style="font-size: 32px;">42</strong>
                <p class="text-muted">Across Building A & B</p>
            </div>
            <div class="fac-widget">
                <h3>Maintenance Queue</h3>
                <strong style="font-size: 32px;">3</strong>
                <p class="text-muted">Rooms awaiting completion</p>
            </div>
            <div class="fac-widget">
                <h3>Bookable capacity</h3>
                <strong style="font-size: 32px;">1,248</strong>
                <p class="text-muted">Total seats available</p>
            </div>
            <div class="fac-widget">
                <h3>Policies updated</h3>
                <strong style="font-size: 32px;">8</strong>
                <p class="text-muted">In the last 30 days</p>
            </div>
        </div>

        <div class="fac-surface">
            <div class="fac-toolbar">
                <div class="fac-search">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.6"/>
                        <path d="M20 20l-3.5-3.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                    </svg>
                    <input type="search" id="facilitySearch" placeholder="Search by name, building, or type">
                </div>
                <div class="fac-chip-group">
                    <button class="fac-chip active" data-filter-building="all">All buildings</button>
                    <button class="fac-chip" data-filter-building="Building A">Building A</button>
                    <button class="fac-chip" data-filter-building="Building B">Building B</button>
                </div>
                <div class="fac-chip-group fac-chip-divider">
                    <button class="fac-chip active" data-filter-type="all">All types</button>
                    <button class="fac-chip" data-filter-type="Meeting Room">Meeting</button>
                    <button class="fac-chip" data-filter-type="Training Room">Training</button>
                    <button class="fac-chip" data-filter-type="Event Hall">Hall</button>
                </div>
                <div class="fac-chip-group fac-chip-divider">
                    <button class="fac-chip active" data-filter-status="all">All status</button>
                    <button class="fac-chip" data-filter-status="active">Active</button>
                    <button class="fac-chip" data-filter-status="maintenance">Maintenance</button>
                </div>
                <button class="fac-btn fac-btn-primary" data-modal-open="filtersDrawer">Advanced filters</button>
            </div>

            <div class="fac-table-wrapper">
                <table class="fac-table" id="facilitiesTable">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Facility Name</th>
                            <th>Building</th>
                            <th>Floor</th>
                            <th>Type</th>
                            <th>Capacity</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($facilities as $facility)
                            <tr
                                data-building="{{ $facility['building'] }}"
                                data-type="{{ $facility['type'] }}"
                                data-status="{{ $facility['status_key'] }}"
                                data-name="{{ $facility['name'] }}"
                            >
                                <td>
                                    <img src="{{ $facility['photo'] }}" alt="{{ $facility['name'] }}" class="fac-photo">
                                </td>
                                <td>
                                    <div class="fac-name">
                                        {{ $facility['name'] }}
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                                            <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                                        </svg>
                                    </div>
                                    <p class="text-muted small mb-0">{{ implode(' • ', $facility['equipment']) }}</p>
                                </td>
                                <td>{{ $facility['building'] }}</td>
                                <td>{{ $facility['floor'] }}</td>
                                <td>{{ $facility['type'] }}</td>
                                <td>{{ $facility['capacity'] }}</td>
                                <td>
                                    <span class="fac-status {{ $facility['status_key'] === 'active' ? 'active' : 'maintenance' }}">
                                        {{ $facility['status'] }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fac-actions">
                                        <button class="fac-action-btn" data-modal-open="facilityModal" data-facility="{{ $facility['name'] }}">Edit</button>
                                        <button
                                            class="fac-action-btn"
                                            data-confirm="Deactivate {{ $facility['name'] }}?"
                                            data-success="{{ $facility['name'] }} is now hidden from catalog."
                                        >
                                            Deactivate
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="fac-pagination">
                <span>1–20 of 42</span>
                <div class="fac-chip-group">
                    <button class="fac-chip active">1</button>
                    <button class="fac-chip">2</button>
                    <button class="fac-chip">3</button>
                </div>
            </div>
        </div>

        <div class="fac-grid">
            <div class="fac-widget">
                <h3>Upcoming maintenance</h3>
                <ul>
                    <li><strong>Helios Training Lab</strong> — HVAC calibration · Aug 16</li>
                    <li><strong>Summit Hall</strong> — Lighting upgrade · Aug 19</li>
                    <li><strong>Nova Hub</strong> — Furniture refresh · Aug 22</li>
                </ul>
            </div>
            <div class="fac-widget">
                <h3>Policies snapshot</h3>
                <ul>
                    @foreach ($policies as $policy)
                        <li>
                            <strong>{{ $policy['title'] }}</strong>
                            <span class="d-block text-muted">{{ $policy['desc'] }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="fac-widget">
                <h3>Quick actions</h3>
                <div class="fac-quick-panel">
                    <div class="fac-quick-card">
                        <p class="text-muted small mb-1">Conflict check</p>
                        <strong>2 bookings impacted</strong>
                        <p class="text-muted mb-0">Review before editing Orion Boardroom.</p>
                    </div>
                    <div class="fac-quick-card">
                        <p class="text-muted small mb-1">Policies pending</p>
                        <strong>3 rooms</strong>
                        <p class="text-muted mb-0">Require updated security policy.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Add / Edit Facility Modal --}}
<div class="fac-modal-overlay" id="facilityModal">
    <div class="fac-modal">
        <header>
            <h3>Facility Details</h3>
            <button class="fac-action-btn" data-modal-close>&times;</button>
        </header>
        <form id="facilityForm">
            <div class="fac-section">
                <header><h4>Basic details</h4></header>
                <div class="fac-grid">
                    <div class="fac-field">
                        <label>Name *</label>
                        <input type="text" required>
                    </div>
                    <div class="fac-field">
                        <label>Building *</label>
                        <select required>
                            <option value="">Choose building</option>
                            <option>Building A</option>
                            <option>Building B</option>
                        </select>
                    </div>
                    <div class="fac-field">
                        <label>Floor *</label>
                        <input type="text" placeholder="e.g., 12F">
                    </div>
                    <div class="fac-field">
                        <label>Capacity *</label>
                        <input type="number" min="1" placeholder="Max people">
                    </div>
                    <div class="fac-field">
                        <label>Type *</label>
                        <select>
                            <option>Meeting Room</option>
                            <option>Training Room</option>
                            <option>Event Hall</option>
                            <option>Collaboration Space</option>
                        </select>
                    </div>
                    <div class="fac-field">
                        <label>Status</label>
                        <select>
                            <option>Active</option>
                            <option>Under Maintenance</option>
                        </select>
                    </div>
                </div>
                <div class="fac-field">
                    <label>Location description</label>
                    <textarea rows="2" placeholder="Entrance details, nearby amenities..."></textarea>
                </div>
            </div>

            <div class="fac-section">
                <header><h4>Photos</h4></header>
                <div class="fac-upload-grid">
                    @for ($i = 0; $i < 6; $i++)
                        <div class="fac-upload-slot">Upload</div>
                    @endfor
                </div>
            </div>

            <div class="fac-section">
                <header><h4>Equipment</h4></header>
                <div class="fac-field">
                    <label>Select equipment</label>
                    <div class="fac-chip-group">
                        @foreach ($equipmentOptions as $option)
                            <span class="fac-chip" data-equipment="{{ $option }}">{{ $option }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="fac-field">
                    <label>Custom equipment</label>
                    <input type="text" placeholder="Add custom item">
                </div>
            </div>

            <div class="fac-section">
                <header>
                    <h4>Operating hours</h4>
                    <button type="button" class="fac-action-btn" data-copy-hours="true">Copy to all days</button
