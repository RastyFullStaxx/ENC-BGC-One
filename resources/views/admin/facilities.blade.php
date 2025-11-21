@extends('layouts.app')

@section('title', 'Admin • Facilities Management')

@push('styles')
    @vite(['resources/css/admin/facilities.css'])
@endpush

@section('content')
@php
    // Prefer controller-provided data; fall back to seeded examples for preview.
    $facilities = $facilities ?? [
        [
            'id' => 1,
            'name' => 'Orion Boardroom',
            'room_number' => '1208',
            'building' => 'Building A',
            'floor' => '12F',
            'type' => 'Meeting Room',
            'capacity' => 16,
            'status' => 'Active',
            'status_key' => 'active',
            'photo' => 'https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=900&q=60',
            'equipment' => ['LED Wall', 'VC Suite', 'Coffee Station'],
            'hours' => '08:00 – 20:00',
            'notes' => 'Executive-ready room with VC suite and pantry access.',
        ],
        [
            'id' => 2,
            'name' => 'Helios Training Lab',
            'room_number' => '407',
            'building' => 'Building B',
            'floor' => '4F',
            'type' => 'Training Room',
            'capacity' => 32,
            'status' => 'Under Maintenance',
            'status_key' => 'maintenance',
            'photo' => 'https://images.unsplash.com/photo-1524758870432-af57e54afa26?auto=format&fit=crop&w=900&q=60',
            'equipment' => ['Projector', 'Whiteboards', 'Lapel Microphones'],
            'hours' => '09:00 – 18:00',
            'notes' => 'AV refresh in progress; ETA back online this week.',
        ],
        [
            'id' => 3,
            'name' => 'Summit Hall',
            'room_number' => 'G12',
            'building' => 'Building A',
            'floor' => 'GF',
            'type' => 'Event Hall',
            'capacity' => 150,
            'status' => 'Active',
            'status_key' => 'active',
            'photo' => 'https://images.unsplash.com/photo-1503420564238-11f5fe3223b4?auto=format&fit=crop&w=900&q=60',
            'equipment' => ['Stage Lighting', 'Sound System', 'Podium'],
            'hours' => '07:00 – 23:00',
            'notes' => 'Ideal for town halls and large briefings.',
        ],
        [
            'id' => 4,
            'name' => 'Nova Collaboration Hub',
            'room_number' => '715',
            'building' => 'Building B',
            'floor' => '7F',
            'type' => 'Collaboration Space',
            'capacity' => 24,
            'status' => 'Active',
            'status_key' => 'active',
            'photo' => 'https://images.unsplash.com/photo-1431540015161-0bf868a2d407?auto=format&fit=crop&w=900&q=60',
            'equipment' => ['Breakout Pods', 'TVs', 'Acoustic Panels'],
            'hours' => '08:00 – 19:00',
            'notes' => 'Great for agile pods and hybrid brainstorming.',
        ],
    ];

    $equipmentOptions = $equipmentOptions ?? ['Projector', 'LED Wall', 'Wireless Mic', 'Speakerphone', 'Whiteboard', 'Extension Cords', 'Telepresence Kit'];
    $buildings = $buildings ?? [['id' => 1, 'name' => 'Building A'], ['id' => 2, 'name' => 'Building B']];
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
                <p>Manage rooms across both buildings. Keep photos, hours, and equipment accurate.</p>
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
                <strong style="font-size: 32px;">{{ $facilitiesCount ?? count($facilities) }}</strong>
                <p class="text-muted">Across Building A & B</p>
            </div>
            <div class="fac-widget">
                <h3>Maintenance queue</h3>
                <strong style="font-size: 32px;">{{ $maintenanceQueue ?? 3 }}</strong>
                <p class="text-muted">Rooms awaiting completion</p>
            </div>
            <div class="fac-widget">
                <h3>Bookable capacity</h3>
                <strong style="font-size: 32px;">{{ $bookableCapacity ?? '1,248' }}</strong>
                <p class="text-muted">Total seats available</p>
            </div>
            <div class="fac-widget">
                <h3>Verified photos</h3>
                <strong style="font-size: 32px;">{{ $verifiedPhotos ?? 18 }}</strong>
                <p class="text-muted">Updated this quarter</p>
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
                    @foreach ($buildings as $building)
                        <button class="fac-chip" data-filter-building="{{ $building['name'] ?? $building['id'] }}">{{ $building['name'] }}</button>
                    @endforeach
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
                            @php
                                $facilityPayload = [
                                    'id' => $facility['id'] ?? null,
                                    'name' => $facility['name'],
                                    'room_number' => $facility['room_number'] ?? null,
                                    'building' => $facility['building'],
                                    'floor' => $facility['floor'],
                                    'type' => $facility['type'],
                                    'capacity' => $facility['capacity'],
                                    'status' => $facility['status'],
                                    'status_key' => $facility['status_key'] ?? 'active',
                                    'photo' => $facility['photo'],
                                    'equipment' => $facility['equipment'],
                                    'hours' => $facility['hours'] ?? null,
                                    'notes' => $facility['notes'] ?? null,
                                ];
                            @endphp
                            <tr
                                data-building="{{ $facility['building'] }}"
                                data-type="{{ $facility['type'] }}"
                                data-status="{{ $facility['status_key'] }}"
                                data-name="{{ $facility['name'] }}"
                                data-facility='@json($facilityPayload)'
                            >
                                <td>
                                    <img src="{{ $facility['photo'] }}" alt="{{ $facility['name'] }}" class="fac-photo">
                                </td>
                                <td>
                                    <button type="button" class="fac-name fac-name-link" data-modal-open="facilityDetailModal">
                                        {{ $facility['name'] }}
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                                            <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                                        </svg>
                                    </button>
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
                <span>1–20 of {{ $facilitiesCount ?? count($facilities) }}</span>
                <div class="fac-chip-group">
                    <button class="fac-chip active">1</button>
                    <button class="fac-chip">2</button>
                    <button class="fac-chip">3</button>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Add / Edit Facility Modal --}}
<div class="fac-modal-overlay" id="facilityModal">
    <div class="fac-modal fac-modal--wide">
        <header>
            <div>
                <p class="fac-breadcrumb mb-1">New entry</p>
                <h3>Add facility</h3>
                <p class="text-muted small mb-0">Capture core room details so bookings stay accurate.</p>
            </div>
            <button class="fac-action-btn" data-modal-close>&times;</button>
        </header>
        <form id="facilityForm" method="POST" action="{{ route('admin.facilities.store') }}">
            @csrf
            <div class="fac-section">
                <header><h4>Basics</h4></header>
                <div class="fac-grid fac-grid--tight">
                    <div class="fac-field">
                        <label>Name *</label>
                        <input type="text" name="name" required placeholder="e.g., Orion Boardroom">
                    </div>
                    <div class="fac-field">
                        <label>Room #</label>
                        <input type="text" name="room_number" placeholder="1208">
                    </div>
                    <div class="fac-field">
                        <label>Building *</label>
                        <select name="building_id" required>
                            <option value="">Choose building</option>
                            @foreach ($buildings as $building)
                                <option value="{{ $building['id'] }}">{{ $building['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fac-field">
                        <label>Floor *</label>
                        <input type="text" name="floor" placeholder="e.g., 12F" required>
                    </div>
                    <div class="fac-field">
                        <label>Capacity *</label>
                        <input type="number" name="capacity" min="1" placeholder="Max people" required>
                    </div>
                    <div class="fac-field">
                        <label>Type *</label>
                        <select name="type" required>
                            <option value="">Select type</option>
                            <option>Meeting Room</option>
                            <option>Training Room</option>
                            <option>Event Hall</option>
                            <option>Collaboration Space</option>
                        </select>
                    </div>
                    <div class="fac-field">
                        <label>Status</label>
                        <select name="status">
                            <option value="active">Active</option>
                            <option value="under maintenance">Under Maintenance</option>
                        </select>
                    </div>
                </div>
                <div class="fac-field">
                    <label>Description</label>
                    <textarea name="description" rows="2" placeholder="Entrance details, nearby amenities..."></textarea>
                </div>
            </div>

            <div class="fac-section">
                <header><h4>Photos & media</h4></header>
                <div class="fac-upload-grid">
                    <div class="fac-upload-slot">
                        <input type="url" name="photo_url" class="fac-upload-input" placeholder="Primary photo URL">
                    </div>
                    @for ($i = 0; $i < 3; $i++)
                        <div class="fac-upload-slot fac-upload-slot--ghost">Add gallery</div>
                    @endfor
                </div>
            </div>

            <div class="fac-section">
                <header><h4>Equipment</h4></header>
                <div class="fac-field">
                    <label>Select equipment</label>
                    <div class="fac-chip-group">
                        @foreach ($equipmentOptions as $option)
                            <label class="fac-chip fac-chip-input">
                                <input type="checkbox" name="equipment[]" value="{{ $option }}"> {{ $option }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="fac-field">
                    <label>Custom equipment</label>
                    <input type="text" name="custom_equipment" placeholder="Add custom item">
                </div>
            </div>

            <div class="fac-section">
                <header>
                    <h4>Operating hours</h4>
                    <button type="button" class="fac-action-btn" data-copy-hours="true">Copy to all days</button>
                </header>
                <div class="fac-grid fac-grid--tight">
                    <div class="fac-field">
                        <label>Opens</label>
                        <input type="time" name="open_time" value="08:00">
                    </div>
                    <div class="fac-field">
                        <label>Closes</label>
                        <input type="time" name="close_time" value="20:00">
                    </div>
                </div>
            </div>

            <div class="fac-modal-actions">
                <button type="button" class="fac-btn fac-btn-ghost" data-modal-close>Cancel</button>
                <button type="submit" class="fac-btn fac-btn-primary">Save facility</button>
            </div>
        </form>
    </div>
</div>

{{-- Full Facility Detail Modal --}}
<div class="fac-modal-overlay" id="facilityDetailModal">
    <div class="fac-modal fac-modal--wide">
        <header>
            <div>
                <p class="fac-breadcrumb mb-1">Facility overview</p>
                <h3 id="detailName">Facility name</h3>
                <p class="fac-meta" id="detailMeta">Building • Floor • Capacity</p>
            </div>
            <button class="fac-action-btn" data-modal-close>&times;</button>
        </header>

        <div class="fac-section fac-detail-hero">
            <img id="detailPhoto" src="" alt="" class="fac-detail-photo">
            <div>
                <div class="fac-detail-badges">
                    <span class="fac-status" id="detailStatus">Status</span>
                    <span class="fac-pill fac-pill-positive" id="detailHours">Hours</span>
                </div>
                <p class="fac-meta" id="detailNotes">Notes about this facility will appear here.</p>
                <div class="fac-chip-group fac-chip-compact" id="detailEquipment"></div>
            </div>
        </div>

        <div class="fac-grid fac-detail-grid">
            <div class="fac-widget">
                <h3>Location</h3>
                <p class="fac-meta mb-1" id="detailBuilding"></p>
                <p class="fac-meta mb-0" id="detailFloor"></p>
            </div>
            <div class="fac-widget">
                <h3>Type</h3>
                <p class="fac-meta mb-1" id="detailType"></p>
                <p class="fac-meta mb-0">Room # <span id="detailRoom">—</span></p>
            </div>
            <div class="fac-widget">
                <h3>Capacity</h3>
                <p class="fac-meta mb-1" id="detailCapacity"></p>
                <p class="fac-meta mb-0">Recommended setup ready</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const openers = document.querySelectorAll('[data-modal-open]');
        const closers = document.querySelectorAll('[data-modal-close]');

        openers.forEach(btn => {
            btn.addEventListener('click', () => {
                const targetId = btn.getAttribute('data-modal-open');
                const overlay = document.getElementById(targetId);
                if (!overlay) return;

                // Pre-fill facility detail modal from row data
                if (targetId === 'facilityDetailModal') {
                    const row = btn.closest('tr');
                    const payload = row?.dataset?.facility ? JSON.parse(row.dataset.facility) : null;
                    if (payload) fillFacilityDetail(payload);
                }

                overlay.classList.add('active');
            });
        });

        closers.forEach(btn => {
            btn.addEventListener('click', () => {
                btn.closest('.fac-modal-overlay')?.classList.remove('active');
            });
        });

        document.querySelectorAll('.fac-modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) overlay.classList.remove('active');
            });
        });

        function fillFacilityDetail(data) {
            const fallbackPhoto = 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?q=80&w=1200&auto=format&fit=crop';
            document.getElementById('detailName').textContent = data.name || 'Facility';
            document.getElementById('detailMeta').textContent = `${data.building ?? 'Building'} • ${data.floor ?? 'Floor'} • ${data.capacity ?? '—'} seats`;
            document.getElementById('detailBuilding').textContent = data.building ?? 'Building';
            document.getElementById('detailFloor').textContent = data.floor ?? '—';
            document.getElementById('detailType').textContent = data.type ?? 'Room';
            document.getElementById('detailRoom').textContent = data.room_number ?? '—';
            document.getElementById('detailCapacity').textContent = data.capacity ? `${data.capacity} seats` : '—';
            document.getElementById('detailStatus').textContent = data.status ?? 'Active';
            document.getElementById('detailStatus').className = `fac-status ${data.status_key === 'maintenance' ? 'maintenance' : 'active'}`;
            document.getElementById('detailHours').textContent = data.hours ?? 'Hours not set';
            document.getElementById('detailPhoto').src = data.photo || fallbackPhoto;
            document.getElementById('detailPhoto').alt = data.name || 'Facility photo';
            document.getElementById('detailNotes').textContent = data.notes ?? 'No notes yet. Add details to guide requesters.';

            const equipWrap = document.getElementById('detailEquipment');
            equipWrap.innerHTML = '';
            (data.equipment || []).forEach(item => {
                const chip = document.createElement('span');
                chip.className = 'fac-chip active';
                chip.textContent = item;
                equipWrap.appendChild(chip);
            });
        }
    });
</script>
@endpush
@endsection
