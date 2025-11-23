@extends('layouts.app')

@section('title', 'Admin • Facilities Management')

@push('styles')
    @vite(['resources/css/admin/facilities.css'])
@endpush

@section('content')
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
                <strong style="font-size: 32px;">{{ $facilitiesCount ?? 0 }}</strong>
                <p class="text-muted">Across Building A & B</p>
            </div>
            <div class="fac-widget">
                <h3>Maintenance queue</h3>
                <strong style="font-size: 32px;">{{ $maintenanceQueue ?? 0 }}</strong>
                <p class="text-muted">Rooms awaiting completion</p>
            </div>
            <div class="fac-widget">
                <h3>Bookable capacity</h3>
                <strong style="font-size: 32px;">{{ $bookableCapacity ?? 0 }}</strong>
                <p class="text-muted">Total seats available</p>
            </div>
            <div class="fac-widget">
                <h3>Verified photos</h3>
                <strong style="font-size: 32px;">{{ $verifiedPhotos ?? 0 }}</strong>
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
                        <button class="fac-chip" data-filter-building="{{ $building['name'] }}">{{ $building['name'] }}</button>
                    @endforeach
                </div>
                <div class="fac-chip-group fac-chip-divider">
                    <button class="fac-chip active" data-filter-type="all">All types</button>
                    <button class="fac-chip" data-filter-type="meeting">Meeting</button>
                    <button class="fac-chip" data-filter-type="training">Training</button>
                    <button class="fac-chip" data-filter-type="multipurpose">Multipurpose</button>
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
                        @forelse ($facilities as $facility)
                            @php
                                $facilityPayload = [
                                    'id' => $facility['id'],
                                    'facility_code' => $facility['facility_code'] ?? null,
                                    'name' => $facility['name'],
                                    'room_number' => $facility['room_number'] ?? null,
                                    'building' => $facility['building'],
                                    'building_id' => $facility['building_id'] ?? null,
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
                                data-type="{{ strtolower($facility['type']) }}"
                                data-status="{{ $facility['status_key'] }}"
                                data-name="{{ strtolower($facility['name']) }}"
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
                                <td>{{ ucfirst($facility['type']) }}</td>
                                <td>{{ $facility['capacity'] }}</td>
                                <td>
                                    <span class="fac-status {{ $facility['status_key'] === 'active' ? 'active' : 'maintenance' }}">
                                        {{ $facility['status'] }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fac-actions">
                                        <button class="fac-action-btn fac-action-edit" data-edit-facility="{{ $facility['id'] }}">Edit</button>
                                        <form method="POST" action="{{ route('admin.facilities.destroy', $facility['id']) }}" style="display: inline;" onsubmit="return confirm('Delete {{ $facility['name'] }}? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="fac-action-btn fac-action-deactivate">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 40px;">
                                    <p class="text-muted">No facilities found. Click "Add Facility" to create one.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="fac-pagination">
                <span>Showing {{ count($facilities) }} of {{ $facilitiesCount ?? count($facilities) }}</span>
                <div class="fac-chip-group">
                    @php
                        $totalPages = ceil(($facilitiesCount ?? count($facilities)) / 20);
                        $currentPage = 1;
                    @endphp
                    @for ($i = 1; $i <= min($totalPages, 5); $i++)
                        <button class="fac-chip {{ $i === $currentPage ? 'active' : '' }}" data-page="{{ $i }}">{{ $i }}</button>
                    @endfor
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
                <p class="fac-breadcrumb mb-1" id="modalBreadcrumb">New entry</p>
                <h3 id="modalTitle">Add facility</h3>
                <p class="fac-modal-subtitle small mb-0">Capture core room details so bookings stay accurate.</p>
            </div>
            <button class="fac-action-btn" data-modal-close>&times;</button>
        </header>
        <form id="facilityForm" method="POST" action="{{ route('admin.facilities.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <input type="hidden" name="facility_id" id="facilityId">
            
            <div class="fac-section">
                <header><h4>Basics</h4></header>
                <div class="fac-grid fac-grid--tight">
                    <div class="fac-field">
                        <label>Name *</label>
                        <input type="text" name="name" id="facilityName" required placeholder="e.g., Orion Boardroom">
                    </div>
                    <div class="fac-field">
                        <label>Room #</label>
                        <input type="text" name="room_number" id="facilityRoomNumber" placeholder="1208">
                    </div>
                    <div class="fac-field">
                        <label>Building *</label>
                        <select name="building_id" id="facilityBuilding" required>
                            <option value="">Choose building</option>
                            @foreach ($buildings as $building)
                                <option value="{{ $building['id'] }}">{{ $building['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fac-field">
                        <label>Floor *</label>
                        <select name="floor" id="facilityFloor" required>
                            <option value="">Choose floor</option>
                            <option value="ground">Ground</option>
                            <option value="2nd">2nd</option>
                            <option value="3rd">3rd</option>
                        </select>
                    </div>
                    <div class="fac-field">
                        <label>Capacity *</label>
                        <input type="number" name="capacity" id="facilityCapacity" min="1" placeholder="Max people" required>
                    </div>
                    <div class="fac-field">
                        <label>Type *</label>
                        <select name="type" id="facilityType" required>
                            <option value="">Select type</option>
                            <option value="meeting">Meeting Room</option>
                            <option value="training">Training Room</option>
                            <option value="multipurpose">Multipurpose</option>
                        </select>
                    </div>
                    <div class="fac-field">
                        <label>Status</label>
                        <select name="status" id="facilityStatus">
                            <option value="active">Active</option>
                            <option value="maintenance">Under Maintenance</option>
                        </select>
                    </div>
                </div>
                <div class="fac-field">
                    <label>Description</label>
                    <textarea name="description" id="facilityDescription" rows="2" placeholder="Entrance details, nearby amenities..."></textarea>
                </div>
            </div>

            <div class="fac-section">
                <header><h4>Photos & media</h4></header>
                <div class="fac-upload-grid">
                    <div class="fac-upload-slot" id="photoUploadSlot">
                        <input type="file" name="photo" id="facilityPhoto" accept="image/*" style="display: none;">
                        <input type="hidden" name="photo_url" id="facilityPhotoUrl">
                        <button type="button" class="fac-upload-trigger" onclick="document.getElementById('facilityPhoto').click()">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <polyline points="21 15 16 10 5 21"/>
                            </svg>
                            <span>Click to upload photo</span>
                        </button>
                        <img id="photoPreview" src="" alt="Preview" style="display: none; width: 100%; height: 200px; object-fit: cover; border-radius: 8px;">
                    </div>
                </div>
            </div>

            <div class="fac-section">
                <header><h4>Equipment</h4></header>
                <div class="fac-field">
                    <label>Select equipment</label>
                    <div class="fac-chip-group" id="equipmentCheckboxes">
                        @foreach ($equipmentOptions as $option)
                            <label class="fac-chip fac-chip-input">
                                <input type="checkbox" name="equipment[]" value="{{ $option }}"> {{ $option }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="fac-field">
                    <label>Custom equipment</label>
                    <input type="text" name="custom_equipment" id="facilityCustomEquipment" placeholder="Add custom item">
                </div>
            </div>

            <div class="fac-section">
                <header>
                    <h4>Operating hours</h4>
                </header>
                <div class="fac-grid fac-grid--tight">
                    <div class="fac-field">
                        <label>Opens</label>
                        <input type="time" name="open_time" id="facilityOpenTime" value="08:00">
                    </div>
                    <div class="fac-field">
                        <label>Closes</label>
                        <input type="time" name="close_time" id="facilityCloseTime" value="20:00">
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
        const facilityForm = document.getElementById('facilityForm');
        const searchInput = document.getElementById('facilitySearch');
        const facilitiesTable = document.getElementById('facilitiesTable');

        // Modal handlers
        openers.forEach(btn => {
            btn.addEventListener('click', () => {
                const targetId = btn.getAttribute('data-modal-open');
                const overlay = document.getElementById(targetId);
                if (!overlay) return;

                if (targetId === 'facilityDetailModal') {
                    const row = btn.closest('tr');
                    const payload = row?.dataset?.facility ? JSON.parse(row.dataset.facility) : null;
                    if (payload) fillFacilityDetail(payload);
                }

                overlay.classList.add('active');
            });
        });

        // Edit facility handlers
        document.querySelectorAll('[data-edit-facility]').forEach(btn => {
            btn.addEventListener('click', async () => {
                const facilityId = btn.getAttribute('data-edit-facility');
                
                try {
                    const response = await fetch(`/admin/facilities/${facilityId}`);
                    const data = await response.json();
                    
                    fillFacilityForm(data);
                    document.getElementById('facilityModal').classList.add('active');
                } catch (error) {
                    console.error('Error loading facility:', error);
                    alert('Failed to load facility data');
                }
            });
        });

        closers.forEach(btn => {
            btn.addEventListener('click', () => {
                const overlay = btn.closest('.fac-modal-overlay');
                overlay?.classList.remove('active');
                if (overlay?.id === 'facilityModal') {
                    resetFacilityForm();
                }
            });
        });

        document.querySelectorAll('.fac-modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) {
                    overlay.classList.remove('active');
                    if (overlay.id === 'facilityModal') {
                        resetFacilityForm();
                    }
                }
            });
        });

        // Photo upload handler
        const photoInput = document.getElementById('facilityPhoto');
        const photoPreview = document.getElementById('photoPreview');
        const photoUploadSlot = document.getElementById('photoUploadSlot');
        const uploadTrigger = photoUploadSlot.querySelector('.fac-upload-trigger');

        photoInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    photoPreview.src = e.target.result;
                    photoPreview.style.display = 'block';
                    uploadTrigger.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });

        // Clear photo preview when clicking on it
        photoPreview.addEventListener('click', () => {
            if (confirm('Remove photo?')) {
                photoPreview.src = '';
                photoPreview.style.display = 'none';
                uploadTrigger.style.display = 'flex';
                photoInput.value = '';
                document.getElementById('facilityPhotoUrl').value = '';
            }
        });

        // Fill form for editing
        function fillFacilityForm(data) {
            document.getElementById('modalBreadcrumb').textContent = 'Edit entry';
            document.getElementById('modalTitle').textContent = 'Edit facility';
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('facilityId').value = data.id;
            facilityForm.action = `/admin/facilities/${data.id}`;

            document.getElementById('facilityName').value = data.name || '';
            document.getElementById('facilityRoomNumber').value = data.room_number || '';
            document.getElementById('facilityBuilding').value = data.building_id || '';
            document.getElementById('facilityFloor').value = data.floor || '';
            document.getElementById('facilityCapacity').value = data.capacity || '';
            document.getElementById('facilityType').value = data.type || '';
            document.getElementById('facilityStatus').value = data.status_key || 'active';
            document.getElementById('facilityDescription').value = data.notes || '';
            document.getElementById('facilityPhotoUrl').value = data.photo || '';
            document.getElementById('facilityOpenTime').value = data.open_time || '08:00';
            document.getElementById('facilityCloseTime').value = data.close_time || '20:00';

            // Show existing photo preview
            if (data.photo) {
                photoPreview.src = data.photo;
                photoPreview.style.display = 'block';
                uploadTrigger.style.display = 'none';
            }

            // Check equipment boxes
            document.querySelectorAll('#equipmentCheckboxes input[type="checkbox"]').forEach(checkbox => {
                checkbox.checked = data.equipment && data.equipment.includes(checkbox.value);
            });
        }

        // Reset form
        function resetFacilityForm() {
            document.getElementById('modalBreadcrumb').textContent = 'New entry';
            document.getElementById('modalTitle').textContent = 'Add facility';
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('facilityId').value = '';
            facilityForm.action = '{{ route("admin.facilities.store") }}';
            facilityForm.reset();
            
            // Reset photo preview
            photoPreview.src = '';
            photoPreview.style.display = 'none';
            uploadTrigger.style.display = 'flex';
            photoInput.value = '';
        }

        // Fill detail modal
        function fillFacilityDetail(data) {
            const fallbackPhoto = 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?q=80&w=1200&auto=format&fit=crop';
            document.getElementById('detailName').textContent = data.name || 'Facility';
            document.getElementById('detailMeta').textContent = `${data.building ?? 'Building'} • ${data.floor ?? 'Floor'} • ${data.capacity ?? '—'} seats`;
            document.getElementById('detailBuilding').textContent = data.building ?? 'Building';
            document.getElementById('detailFloor').textContent = data.floor ?? '—';
            document.getElementById('detailType').textContent = data.type ? data.type.charAt(0).toUpperCase() + data.type.slice(1) : 'Room';
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

        // Search functionality
        if (searchInput && facilitiesTable) {
            searchInput.addEventListener('input', (e) => {
                const searchTerm = e.target.value.toLowerCase();
                filterTable();
            });
        }

        // Filter chips
        document.querySelectorAll('[data-filter-building], [data-filter-type], [data-filter-status]').forEach(btn => {
            btn.addEventListener('click', () => {
                // Remove active from siblings
                btn.parentElement.querySelectorAll('.fac-chip').forEach(chip => chip.classList.remove('active'));
                btn.classList.add('active');
                filterTable();
            });
        });

        function filterTable() {
            const searchTerm = searchInput?.value.toLowerCase() || '';
            const activeBuilding = document.querySelector('[data-filter-building].active')?.dataset.filterBuilding || 'all';
            const activeType = document.querySelector('[data-filter-type].active')?.dataset.filterType || 'all';
            const activeStatus = document.querySelector('[data-filter-status].active')?.dataset.filterStatus || 'all';

            const rows = facilitiesTable?.querySelectorAll('tbody tr') || [];
            
            rows.forEach(row => {
                if (!row.dataset.facility) {
                    return; // Skip empty state row
                }

                const name = row.dataset.name || '';
                const building = row.dataset.building || '';
                const type = row.dataset.type || '';
                const status = row.dataset.status || '';

                const matchesSearch = !searchTerm || name.includes(searchTerm) || building.toLowerCase().includes(searchTerm) || type.includes(searchTerm);
                const matchesBuilding = activeBuilding === 'all' || building === activeBuilding;
                const matchesType = activeType === 'all' || type === activeType;
                const matchesStatus = activeStatus === 'all' || status === activeStatus;

                if (matchesSearch && matchesBuilding && matchesType && matchesStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    });
</script>
@endpush
@endsection