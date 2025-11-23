@extends('layouts.app')

@section('title', 'Admin • Facilities Management')

@push('styles')
    @vite(['resources/css/admin/facilities.css'])
@endpush

@section('content')
@php
    // Prefer controller-provided data; fall back to seeded examples for preview.
    $facilities = $facilities ?? [];
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
            <form class="fac-toolbar" id="facFilterForm" method="GET" action="{{ route('admin.facilities') }}">
                @php
                    $activeBuilding = $filters['building'] ?? '';
                    $activeType = $filters['type'] ?? '';
                    $activeStatus = $filters['status'] ?? '';
                @endphp
                <input type="hidden" name="building" id="filterBuilding" value="{{ $activeBuilding }}">
                <input type="hidden" name="type" id="filterType" value="{{ $activeType }}">
                <input type="hidden" name="status" id="filterStatus" value="{{ $activeStatus }}">
                <div class="fac-search">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.6"/>
                        <path d="M20 20l-3.5-3.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                    </svg>
                    <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search by name, building, or type">
                </div>
                <div class="fac-chip-group">
                    <button class="fac-chip {{ $activeBuilding === '' ? 'active' : '' }}" data-filter-building="">All buildings</button>
                    @foreach ($buildings as $building)
                        <button class="fac-chip {{ $activeBuilding === ($building['name'] ?? '') ? 'active' : '' }}" data-filter-building="{{ $building['name'] }}">{{ $building['name'] }}</button>
                    @endforeach
                </div>
                <div class="fac-chip-group fac-chip-divider">
                    <button class="fac-chip {{ $activeType === '' ? 'active' : '' }}" data-filter-type="">All types</button>
                    <button class="fac-chip {{ $activeType === 'Meeting Room' ? 'active' : '' }}" data-filter-type="Meeting Room">Meeting</button>
                    <button class="fac-chip {{ $activeType === 'Training Room' ? 'active' : '' }}" data-filter-type="Training Room">Training</button>
                    <button class="fac-chip {{ $activeType === 'Event Hall' ? 'active' : '' }}" data-filter-type="Event Hall">Hall</button>
                </div>
                <div class="fac-chip-group fac-chip-divider">
                    <button class="fac-chip {{ $activeStatus === '' ? 'active' : '' }}" data-filter-status="">All status</button>
                    <button class="fac-chip {{ $activeStatus === 'active' ? 'active' : '' }}" data-filter-status="active">Active</button>
                    <button class="fac-chip {{ $activeStatus === 'maintenance' ? 'active' : '' }}" data-filter-status="maintenance">Maintenance</button>
                </div>
                <button class="fac-btn fac-btn-primary" type="submit">Apply</button>
            </form>

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
                            'building_id' => $facility['building_id'] ?? null,
                            'building' => $facility['building'],
                            'floor' => $facility['floor'],
                            'type' => $facility['type'],
                            'capacity' => $facility['capacity'],
                            'status' => $facility['status'],
                            'status_key' => $facility['status_key'] ?? 'active',
                            'photo' => $facility['photo'],
                            'equipment' => $facility['equipment'],
                            'photos' => $facility['photos'] ?? [],
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
                                    <span class="fac-status {{ $facility['status_key'] === 'maintenance' ? 'maintenance' : 'active' }}">
                                        {{ $facility['status'] }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fac-actions">
                                        <button
                                            class="fac-action-btn fac-action-edit"
                                            data-modal-open="facilityModal"
                                            data-facility='@json($facility)'
                                        >Edit</button>
                                        @php $isInactive = ($facility['status_key'] ?? 'active') !== 'active'; @endphp
                                        <form method="POST" action="{{ route('admin.facilities.status', $facility['id']) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button
                                                type="submit"
                                                class="fac-action-btn {{ $isInactive ? 'fac-action-reactivate' : 'fac-action-deactivate' }}"
                                            >
                                                {{ $isInactive ? 'Reactivate' : 'Deactivate' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @php
                $start = ($facilities->currentPage() - 1) * $facilities->perPage() + 1;
                $end = min($facilities->total(), $facilities->currentPage() * $facilities->perPage());
            @endphp
            @if ($facilities->lastPage() > 1)
                <div class="fac-pagination">
                    <span>{{ $start }}–{{ $end }} of {{ $facilities->total() }}</span>
                    <div class="fac-chip-group">
                        @for ($page = 1; $page <= $facilities->lastPage(); $page++)
                            <a class="fac-chip {{ $page === $facilities->currentPage() ? 'active' : '' }}" href="{{ $facilities->url($page) }}">{{ $page }}</a>
                        @endfor
                    </div>
                </div>
            @endif
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
                <p class="fac-modal-subtitle small mb-0">Capture core room details so bookings stay accurate.</p>
            </div>
            <button class="fac-action-btn" data-modal-close>&times;</button>
        </header>
        <form id="facilityForm" method="POST" action="{{ route('admin.facilities.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="POST" id="facilityFormMethod">
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
                            <option value="occupied">Occupied</option>
                            <option value="limited">Limited Availability</option>
                            <option value="maintenance">Under Maintenance</option>
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
                <div class="fac-preview-grid" id="photoPreviewGrid"></div>
                <div class="fac-upload-grid">
                    <label class="fac-upload-slot fac-upload-slot--button">
                        <input type="file" name="photos[]" accept="image/*" class="fac-upload-input-file">
                        <span class="fac-upload-label">Upload primary</span>
                        <small class="fac-meta">JPG, PNG up to 4MB</small>
                    </label>
                    <label class="fac-upload-slot fac-upload-slot--button fac-upload-slot--ghost">
                        <input type="file" name="photos[]" accept="image/*" class="fac-upload-input-file">
                        <span class="fac-upload-label">Upload gallery</span>
                        <small class="fac-meta">Secondary</small>
                    </label>
                    <label class="fac-upload-slot fac-upload-slot--button fac-upload-slot--ghost">
                        <input type="file" name="photos[]" accept="image/*" class="fac-upload-input-file">
                        <span class="fac-upload-label">Upload gallery</span>
                        <small class="fac-meta">Secondary</small>
                    </label>
                    <label class="fac-upload-slot fac-upload-slot--button fac-upload-slot--ghost">
                        <input type="file" name="photos[]" accept="image/*" class="fac-upload-input-file">
                        <span class="fac-upload-label">Upload gallery</span>
                        <small class="fac-meta">Secondary</small>
                    </label>
                </div>
                <div class="fac-field mt-2">
                    <label>Paste URLs (optional)</label>
                    <div class="fac-upload-grid fac-upload-grid--urls">
                        <input type="text" name="photo_url" class="fac-upload-input" placeholder="Primary photo URL (optional)" inputmode="url">
                        @for ($i = 0; $i < 3; $i++)
                            <input type="text" name="photo_urls[]" class="fac-upload-input fac-upload-input--ghost" placeholder="Additional photo URL (optional)" inputmode="url">
                        @endfor
                    </div>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const openers = document.querySelectorAll('[data-modal-open]');
        const closers = document.querySelectorAll('[data-modal-close]');
        const facilityForm = document.getElementById('facilityForm');
            const facilityFormMethod = document.getElementById('facilityFormMethod');
            const facilityFormAction = facilityForm?.getAttribute('action');
            const filterForm = document.getElementById('facFilterForm');
            const filterBuilding = document.getElementById('filterBuilding');
            const filterType = document.getElementById('filterType');
            const filterStatus = document.getElementById('filterStatus');
            const previewGrid = document.getElementById('photoPreviewGrid');

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

                if (targetId === 'facilityModal') {
                    const payload = btn.dataset.facility ? JSON.parse(btn.dataset.facility) : null;
                    setupFacilityForm(payload);
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

            // Filters
            document.querySelectorAll('[data-filter-building]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    if (filterBuilding) filterBuilding.value = btn.dataset.filterBuilding || '';
                    filterForm?.submit();
                });
            });
            document.querySelectorAll('[data-filter-type]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    if (filterType) filterType.value = btn.dataset.filterType || '';
                    filterForm?.submit();
                });
            });
            document.querySelectorAll('[data-filter-status]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    if (filterStatus) filterStatus.value = btn.dataset.filterStatus || '';
                    filterForm?.submit();
                });
            });

            // Sweet alerts for status forms and session feedback
            document.querySelectorAll('form[action*="/admin/facilities/"][action$="/status"]').forEach(form => {
                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    const btn = form.querySelector('button[type="submit"]');
                    const isReactivate = btn?.classList.contains('fac-action-reactivate');
                    Swal.fire({
                        title: isReactivate ? 'Reactivate facility?' : 'Deactivate facility?',
                        text: isReactivate ? 'This will make the room visible again.' : 'This will hide the room from catalog.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: isReactivate ? 'Reactivate' : 'Deactivate',
                        cancelButtonText: 'Cancel'
                    }).then(result => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });

            const statusMsg = @json(session('status'));
            if (statusMsg) {
                Swal.fire({ icon: 'success', title: 'Success', text: statusMsg, timer: 1800, showConfirmButton: false });
            }

            function renderPreviews(images = []) {
                if (!previewGrid) return;
                previewGrid.innerHTML = '';
                images.filter(Boolean).forEach(src => {
                    const thumb = document.createElement('div');
                    thumb.className = 'fac-preview-thumb';
                    const img = document.createElement('img');
                    img.src = src;
                    img.alt = 'Facility photo';
                    thumb.appendChild(img);
                    previewGrid.appendChild(thumb);
                });
            }

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

        function setupFacilityForm(data) {
            if (!facilityForm) return;
            facilityForm.reset();
            facilityForm.setAttribute('action', facilityFormAction);
            facilityFormMethod.value = 'POST';
            const inputs = {
                name: facilityForm.querySelector('[name="name"]'),
                room_number: facilityForm.querySelector('[name="room_number"]'),
                building_id: facilityForm.querySelector('[name="building_id"]'),
                floor: facilityForm.querySelector('[name="floor"]'),
                capacity: facilityForm.querySelector('[name="capacity"]'),
                type: facilityForm.querySelector('[name="type"]'),
                status: facilityForm.querySelector('[name="status"]'),
                description: facilityForm.querySelector('[name="description"]'),
                open_time: facilityForm.querySelector('[name="open_time"]'),
                close_time: facilityForm.querySelector('[name="close_time"]'),
                photo_url: facilityForm.querySelector('[name="photo_url"]'),
                photo_urls: facilityForm.querySelectorAll('[name="photo_urls[]"]'),
            };

            // clear checkboxes
            facilityForm.querySelectorAll('[name="equipment[]"]').forEach(cb => cb.checked = false);
            renderPreviews([]);

            if (data) {
                facilityForm.setAttribute('action', `{{ url('/admin/facilities') }}/${data.id}`);
                facilityFormMethod.value = 'PATCH';
                if (inputs.name) inputs.name.value = data.name || '';
                if (inputs.room_number) inputs.room_number.value = data.room_number || '';
                if (inputs.building_id) inputs.building_id.value = data.building_id || '';
                if (inputs.floor) inputs.floor.value = data.floor || '';
                if (inputs.capacity) inputs.capacity.value = data.capacity || '';
                if (inputs.type) inputs.type.value = data.type || '';
                if (inputs.status) inputs.status.value = data.status || 'Active';
                if (inputs.description) inputs.description.value = data.notes || '';
                if (inputs.open_time && data.hours) {
                    const parts = data.hours.split('–').map(p => p.trim());
                    if (parts[0]) inputs.open_time.value = parts[0];
                    if (parts[1]) inputs.close_time.value = parts[1];
                }
                const photos = data.photos || [];
                if (inputs.photo_url) inputs.photo_url.value = photos[0] || '';
                inputs.photo_urls?.forEach((input, idx) => input.value = photos[idx + 1] || '');
                renderPreviews(photos);

                const equipment = new Set(data.equipment || []);
                facilityForm.querySelectorAll('[name="equipment[]"]').forEach(cb => {
                    if (equipment.has(cb.value)) cb.checked = true;
                });

                if (inputs.status) inputs.status.value = data.status_key || 'active';
            }

            // file + URL preview updates
            const fileInputs = facilityForm.querySelectorAll('.fac-upload-input-file');
            fileInputs.forEach(input => {
                input.onchange = () => {
                    const files = Array.from(fileInputs).flatMap(fi => Array.from(fi.files || []));
                    const urls = files.map(file => URL.createObjectURL(file));
                    const urlFields = Array.from(facilityForm.querySelectorAll('[name="photo_url"], [name="photo_urls[]"]')).map(f => f.value).filter(Boolean);
                    renderPreviews([...urls, ...urlFields]);
                };
            });
            const urlInputs = facilityForm.querySelectorAll('[name="photo_url"], [name="photo_urls[]"]');
            urlInputs.forEach(input => {
                input.addEventListener('input', () => {
                    const urlFields = Array.from(urlInputs).map(f => f.value).filter(Boolean);
                    const files = Array.from(fileInputs).flatMap(fi => Array.from(fi.files || [])).map(file => URL.createObjectURL(file));
                    renderPreviews([...files, ...urlFields]);
                });
            });
        }
    });
</script>
@endpush
@endsection
