/**
 * Booking Wizard API Integration Guide
 * 
 * This file documents the API endpoints and data structures for the booking wizard.
 * Include these functions in your wizard.js file to connect with the BookingController.
 */

// ============================================================================
// 1. FETCH FACILITIES (Step 1 - Browse Rooms)
// ============================================================================

/**
 * Load facilities from the API with optional filters
 */
async function loadFacilities(filters = {}) {
    try {
        const params = new URLSearchParams(filters);
        const response = await fetch(`${window.bookingAPI.getFacilities}?${params}`);
        const facilities = await response.json();
        
        // Clear loading state
        const grid = document.getElementById('wizardRoomsGrid');
        grid.innerHTML = '';
        
        // Render facility cards
        facilities.forEach(facility => {
            grid.innerHTML += createFacilityCard(facility);
        });
    } catch (error) {
        console.error('Error loading facilities:', error);
        showError('Failed to load facilities. Please try again.');
    }
}

/**
 * Create HTML for a facility card
 */
function createFacilityCard(facility) {
    const statusVariant = facility.availability.variant || 'success';
    const statusBadge = facility.availability.status || 'Available';
    
    return `
        <div class="col-12 col-md-6 col-xl-4">
            <article class="wizard-room-card card h-100 border-0">
                <div class="wizard-room-media position-relative">
                    <img src="${facility.photos[0] || '/images/rooms/default-room.jpg'}" 
                         alt="${facility.name}" 
                         class="wizard-room-image">
                    <span class="wizard-room-status badge rounded-pill text-bg-${statusVariant} position-absolute top-0 end-0 m-3">
                        ${statusBadge}
                    </span>
                </div>
                <div class="card-body wizard-room-body d-flex flex-column">
                    <h3 class="h5 mb-1">${facility.name}</h3>
                    <div class="wizard-room-meta d-flex flex-wrap gap-2 mb-3">
                        <span class="wizard-room-meta-chip">${facility.location}</span>
                        <span class="wizard-room-meta-chip wizard-room-meta-outline">
                            Up to ${facility.capacity} people
                        </span>
                    </div>
                    <div class="wizard-room-amenities mb-3">
                        ${facility.equipment.map(eq => `
                            <span class="wizard-room-amenity" title="${eq}">
                                <span class="wizard-room-amenity-label">${eq}</span>
                            </span>
                        `).join('')}
                    </div>
                    <div class="wizard-room-actions mt-auto pt-2">
                        <button type="button" 
                                class="btn btn-room-available w-100 wizard-room-select"
                                data-room-id="${facility.id}"
                                data-room-name="${facility.name}"
                                data-room-capacity="${facility.capacity}">
                            Book This Room
                        </button>
                    </div>
                </div>
            </article>
        </div>
    `;
}

// ============================================================================
// 2. CHECK AVAILABILITY (Step 2 - Date & Time Selection)
// ============================================================================

/**
 * Check if selected time slot is available
 */
async function checkAvailability(facilityId, date, startTime, endTime) {
    try {
        const response = await fetch(window.bookingAPI.checkAvailability, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.bookingAPI.csrfToken,
            },
            body: JSON.stringify({
                facility_id: facilityId,
                date: date,
                start_time: startTime,
                end_time: endTime,
            }),
        });
        
        const data = await response.json();
        
        if (!data.available) {
            showWarning(data.message);
            return false;
        }
        
        return true;
    } catch (error) {
        console.error('Error checking availability:', error);
        showError('Failed to check availability. Please try again.');
        return false;
    }
}

// ============================================================================
// 3. SUBMIT BOOKING (Step 4 - Review & Confirm)
// ============================================================================

/**
 * Submit the booking request
 */
async function submitBooking() {
    // Gather form data
    const bookingData = {
        facility_id: document.querySelector('[data-selected-room-id]').value,
        date: document.getElementById('bookingDate').value,
        start_time: document.getElementById('bookingStartTime').value,
        end_time: document.getElementById('bookingEndTime').value,
        purpose: document.getElementById('wizardAgendaInput').value,
        attendees_count: parseInt(document.getElementById('wizardAttendeesInput').value),
        sfi_support: document.getElementById('wizardSupportToggle').checked,
        sfi_count: document.getElementById('wizardSupportToggle').checked 
            ? parseInt(document.getElementById('wizardSupportCountInput').value) 
            : 0,
        additional_notes: document.getElementById('wizardSupportNotes').value,
        equipment: [],
        equipment_quantities: [],
    };
    
    // Collect selected equipment
    document.querySelectorAll('input[name="supportEquipment[]"]:checked').forEach(checkbox => {
        bookingData.equipment.push(checkbox.value);
        bookingData.equipment_quantities.push(1); // Default quantity
    });
    
    try {
        const response = await fetch(window.bookingAPI.store, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.bookingAPI.csrfToken,
            },
            body: JSON.stringify(bookingData),
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Show success panel
            showSuccessPanel(result.data);
            return true;
        } else {
            showError(result.message);
            return false;
        }
    } catch (error) {
        console.error('Error submitting booking:', error);
        showError('Failed to submit booking. Please try again.');
        return false;
    }
}

/**
 * Display the success panel with booking details
 */
function showSuccessPanel(bookingData) {
    // Hide all wizard stages
    document.getElementById('wizardManualStage').classList.add('d-none');
    document.getElementById('wizardLandingShell').classList.add('d-none');
    
    // Show success panel
    const successPanel = document.getElementById('wizardSuccessPanel');
    successPanel.classList.remove('d-none');
    successPanel.removeAttribute('hidden');
    
    // Populate success panel with data
    document.getElementById('wizardSuccessCode').textContent = bookingData.reference_code;
    document.getElementById('wizardSuccessRoom').textContent = bookingData.facility;
    document.getElementById('wizardSuccessDate').textContent = bookingData.date;
    document.getElementById('wizardSuccessTime').textContent = bookingData.time;
    document.getElementById('wizardSuccessAgenda').textContent = 
        document.getElementById('wizardAgendaInput').value;
}

// ============================================================================
// 4. LOAD USER BOOKINGS (Sidebar)
// ============================================================================

/**
 * Load user's bookings for the sidebar
 */
async function loadUserBookings(status = null) {
    try {
        const url = status 
            ? `${window.bookingAPI.getUserBookings}?status=${status}`
            : window.bookingAPI.getUserBookings;
            
        const response = await fetch(url);
        const bookings = await response.json();
        
        // Update sidebar with bookings
        updateBookingsSidebar(bookings);
    } catch (error) {
        console.error('Error loading user bookings:', error);
    }
}

/**
 * Update the bookings sidebar with new data
 */
function updateBookingsSidebar(bookings) {
    const list = document.querySelector('.wizard-bookings-list');
    if (!list) return;
    
    if (bookings.length === 0) {
        list.innerHTML = `
            <div class="text-center py-4 text-muted">
                <p class="mb-0">No bookings yet</p>
                <small>Your booking requests will appear here</small>
            </div>
        `;
        return;
    }
    
    list.innerHTML = bookings.map(booking => {
        const statusClass = getStatusClass(booking.status);
        const bgClass = getBgClass(booking.status);
        
        return `
            <div class="wizard-booking-item border rounded-3 p-2 mb-2 ${bgClass}">
                <div class="d-flex justify-content-between">
                    <span class="badge ${statusClass} me-2">${booking.status}</span>
                    <span class="text-muted">${booking.date}</span>
                </div>
                <div class="fw-semibold mt-1">${booking.room}</div>
                <div class="text-muted">${booking.time}</div>
            </div>
        `;
    }).join('');
}

// ============================================================================
// HELPER FUNCTIONS
// ============================================================================

function getStatusClass(status) {
    const map = {
        'Pending': 'bg-warning text-dark',
        'Approved': 'bg-success text-white',
        'Rejected': 'bg-danger text-white',
        'Cancelled': 'bg-secondary text-white',
    };
    return map[status] || 'bg-light text-dark';
}

function getBgClass(status) {
    const map = {
        'Pending': 'bg-warning-subtle',
        'Approved': 'bg-success-subtle',
        'Rejected': 'bg-danger-subtle',
        'Cancelled': 'bg-secondary-subtle',
    };
    return map[status] || 'bg-light';
}

function showError(message) {
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: message,
    });
}

function showWarning(message) {
    Swal.fire({
        icon: 'warning',
        title: 'Notice',
        text: message,
    });
}

function showSuccess(message) {
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: message,
    });
}

// ============================================================================
// EVENT LISTENERS
// ============================================================================

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Load facilities when entering Step 1
    const methodCards = document.querySelectorAll('[data-method="manual"]');
    methodCards.forEach(card => {
        card.addEventListener('click', function() {
            loadFacilities();
        });
    });
    
    // Filter facilities when filters change
    document.getElementById('roomSearch')?.addEventListener('input', debounce(function(e) {
        loadFacilities({ search: e.target.value });
    }, 500));
    
    document.getElementById('roomFloor')?.addEventListener('change', function(e) {
        const floor = e.target.value.toLowerCase().replace(' floor', '');
        loadFacilities({ floor: floor });
    });
    
    document.getElementById('roomSize')?.addEventListener('change', function(e) {
        loadFacilities({ size: e.target.value });
    });
    
    // Check availability when date/time changes
    const dateInput = document.getElementById('bookingDate');
    const startTime = document.getElementById('bookingStartTime');
    const endTime = document.getElementById('bookingEndTime');
    
    [dateInput, startTime, endTime].forEach(input => {
        input?.addEventListener('change', async function() {
            if (dateInput.value && startTime.value && endTime.value) {
                const facilityId = document.querySelector('[data-selected-room-id]').value;
                await checkAvailability(facilityId, dateInput.value, startTime.value, endTime.value);
            }
        });
    });
    
    // Submit booking
    document.getElementById('wizardSubmitRequest')?.addEventListener('click', async function() {
        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';
        
        const success = await submitBooking();
        
        if (!success) {
            this.disabled = false;
            this.innerHTML = 'Submit booking request';
        }
    });
});

// Debounce helper
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}
