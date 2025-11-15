<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Approval System</title>
    <link rel="stylesheet" href="styles/app.css">
</head>
<body>
    <!-- Navigation Header -->
    <header class="app-header">
        <div class="container">
            <div class="header-content">
                <div class="logo-section">
                    <div class="logo-circle">ENC</div>
                    <div>
                        <h1 class="header-title">Shared Services Portal</h1>
                        <p class="header-subtitle">One-Stop Booking Platform</p>
                    </div>
                </div>
                <nav class="nav-buttons">
                    <button class="nav-btn active">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M10 1.33333H6C5.63181 1.33333 5.33333 1.63181 5.33333 2V3.33333C5.33333 3.70152 5.63181 4 6 4H10C10.3682 4 10.6667 3.70152 10.6667 3.33333V2C10.6667 1.63181 10.3682 1.33333 10 1.33333Z" stroke="currentColor" stroke-width="1.33333"/>
                            <path d="M10.6667 2.66667H12C12.3536 2.66667 12.6928 2.80714 12.9428 3.05719C13.1929 3.30724 13.3333 3.64638 13.3333 4V13.3333C13.3333 13.687 13.1929 14.0261 12.9428 14.2761C12.6928 14.5262 12.3536 14.6667 12 14.6667H4C3.64638 14.6667 3.30724 14.5262 3.05719 14.2761C2.80714 14.0261 2.66667 13.687 2.66667 13.3333V4C2.66667 3.64638 2.80714 3.30724 3.05719 3.05719C3.30724 2.80714 3.64638 2.66667 4 2.66667H5.33333" stroke="currentColor" stroke-width="1.33333"/>
                            <path d="M6 8L7.33333 9.33333L10 6.66667" stroke="currentColor" stroke-width="1.33333"/>
                        </svg>
                        Approvals
                    </button>
                    <button class="nav-btn">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M13.3333 8.66667C13.3333 12 11 13.6667 8.22667 14.6333C8.08144 14.6825 7.92369 14.6802 7.78 14.6267C5 13.6667 2.66667 12 2.66667 8.66667V4C2.66667 3.82319 2.7369 3.65362 2.86193 3.5286C2.98695 3.40357 3.15652 3.33333 3.33333 3.33333C4.66667 3.33333 6.33333 2.53333 7.49333 1.52C7.63457 1.39933 7.81424 1.33303 8 1.33303C8.18576 1.33303 8.36543 1.39933 8.50667 1.52C9.67333 2.54 11.3333 3.33333 12.6667 3.33333C12.8435 3.33333 13.013 3.40357 13.1381 3.5286C13.2631 3.65362 13.3333 3.82319 13.3333 4V8.66667Z" stroke="currentColor" stroke-width="1.33333"/>
                            <path d="M6 8L7.33333 9.33333L10 6.66667" stroke="currentColor" stroke-width="1.33333"/>
                        </svg>
                        Admin Hub
                    </button>
                    <button class="nav-btn">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M8 4.66667V14" stroke="currentColor" stroke-width="1.33333"/>
                            <path d="M2 12C1.82319 12 1.65362 11.9298 1.5286 11.8047C1.40357 11.6797 1.33333 11.5101 1.33333 11.3333V2.66667C1.33333 2.48986 1.40357 2.32029 1.5286 2.19526C1.65362 2.07024 1.82319 2 2 2H5.33333C6.04058 2 6.71885 2.28095 7.21895 2.78105C7.71905 3.28115 8 3.95942 8 4.66667C8 3.95942 8.28095 3.28115 8.78105 2.78105C9.28115 2.28095 9.95942 2 10.6667 2H14C14.1768 2 14.3464 2.07024 14.4714 2.19526C14.5964 2.32029 14.6667 2.48986 14.6667 2.66667V11.3333C14.6667 11.5101 14.5964 11.6797 14.4714 11.8047C14.3464 11.9298 14.1768 12 14 12H10C9.46957 12 8.96086 12.2107 8.58579 12.5858C8.21071 12.9609 8 13.4696 8 14C8 13.4696 7.78929 12.9609 7.41421 12.5858C7.03914 12.2107 6.53043 12 6 12H2Z" stroke="currentColor" stroke-width="1.33333"/>
                        </svg>
                        My Bookings
                    </button>
                    <div class="badge-admin">Admin</div>
                    <button class="notification-btn">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M8.55667 17.5C8.70296 17.7533 8.91335 17.9637 9.16671 18.11C9.42006 18.2563 9.70746 18.3333 10 18.3333C10.2926 18.3333 10.5799 18.2563 10.8333 18.11C11.0867 17.9637 11.2971 17.7533 11.4433 17.5" stroke="currentColor" stroke-width="1.66667"/>
                            <path d="M2.71833 12.7717C2.60947 12.891 2.53763 13.0394 2.51155 13.1988C2.48547 13.3582 2.50627 13.5217 2.57142 13.6695C2.63658 13.8173 2.74328 13.943 2.87855 14.0312C3.01381 14.1195 3.17182 14.1665 3.33333 14.1667H16.6667C16.8282 14.1667 16.9862 14.1199 17.1216 14.0318C17.2569 13.9437 17.3637 13.8181 17.4291 13.6704C17.4944 13.5227 17.5154 13.3592 17.4895 13.1998C17.4637 13.0404 17.392 12.892 17.2833 12.7725C16.175 11.63 15 10.4158 15 6.66667C15 5.34058 14.4732 4.06881 13.5355 3.13113C12.5979 2.19345 11.3261 1.66667 10 1.66667C8.67392 1.66667 7.40215 2.19345 6.46447 3.13113C5.52679 4.06881 5 5.34058 5 6.66667C5 10.4158 3.82417 11.63 2.71833 12.7717Z" stroke="currentColor" stroke-width="1.66667"/>
                        </svg>
                        <span class="notification-badge">2</span>
                    </button>
                </nav>
                <div class="user-section">
                    <div class="user-avatar">CR</div>
                    <div>
                        <div class="user-name">Charles Ramos</div>
                        <div class="user-email">charles@enc.gov</div>
                    </div>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M4 6L8 10L12 6" stroke="currentColor" stroke-width="1.33333"/>
                    </svg>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Queue View -->
            <div id="queueView" class="view active">
                <div class="page-header">
                    <div>
                        <h2 class="page-title">Approvals Queue</h2>
                        <p class="page-subtitle">Review and manage booking requests</p>
                    </div>
                    <div class="header-actions">
                        <button class="btn-secondary">Refresh</button>
                        <button class="btn-secondary">Debug</button>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="tabs">
                    <button class="tab active" data-tab="pending">Pending</button>
                    <button class="tab" data-tab="approved">Approved</button>
                    <button class="tab" data-tab="rejected">Rejected</button>
                </div>

                <!-- Filters -->
                <div class="filters-card">
                    <div class="search-box">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M14 14L11.1067 11.1067" stroke="currentColor" stroke-width="1.33333"/>
                            <circle cx="7.33333" cy="7.33333" r="5.33333" stroke="currentColor" stroke-width="1.33333"/>
                        </svg>
                        <input type="text" placeholder="Search by requester, facility, or purpose..." id="searchInput">
                    </div>
                    <div class="filter-buttons">
                        <select class="filter-select">
                            <option>Meeting Rooms</option>
                            <option>Training Halls</option>
                            <option>Conference Rooms</option>
                        </select>
                        <select class="filter-select">
                            <option>All Priority</option>
                            <option>High</option>
                            <option>Medium</option>
                            <option>Low</option>
                        </select>
                        <select class="filter-select">
                            <option>All Departments</option>
                            <option>IT Department</option>
                            <option>HR Department</option>
                            <option>Finance</option>
                        </select>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-card">
                    <h3 class="card-title">Pending Requests</h3>
                    <div class="table-container">
                        <table class="booking-table">
                            <thead>
                                <tr>
                                    <th>Requested On</th>
                                    <th>When</th>
                                    <th>Facility</th>
                                    <th>Requester</th>
                                    <th>Purpose</th>
                                    <th>Priority</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="bookingsTableBody">
                                <!-- Will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Detail View -->
            <div id="detailView" class="view detail-view">
                <div class="detail-header">
                    <button class="btn-back" id="backBtn">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M8 12.6667L3.33333 8L8 3.33333" stroke="currentColor" stroke-width="1.33333"/>
                            <path d="M12.6667 8H3.33333" stroke="currentColor" stroke-width="1.33333"/>
                        </svg>
                        Back
                    </button>
                    <div class="detail-header-content">
                        <div>
                            <h2 class="detail-title">Booking Review</h2>
                            <div class="badge-status">Pending Approval</div>
                        </div>
                        <div class="detail-meta">
                            <span>Ref: <strong id="bookingRef"></strong></span>
                            <span>•</span>
                            <span id="submittedDate"></span>
                            <span>•</span>
                            <a href="#" class="link-primary">View Full Details</a>
                        </div>
                    </div>
                </div>

                <div class="detail-content">
                    <div class="detail-main">
                        <!-- Alert -->
                        <div class="alert alert-warning" id="approvalAlert">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <circle cx="8" cy="8" r="6.66667" stroke="currentColor" stroke-width="1.33333"/>
                                <path d="M8 5.33333V8" stroke="currentColor" stroke-width="1.33333"/>
                                <path d="M8 10.6667H8.00667" stroke="currentColor" stroke-width="1.33333"/>
                            </svg>
                            <div>
                                <strong>Why approval is needed:</strong>
                                <p>Booking made less than 24 hours in advance requires manager approval per Policy #5.2</p>
                            </div>
                        </div>

                        <!-- Cards will be populated by JavaScript -->
                        <div id="detailCards"></div>
                    </div>

                    <!-- Decision Panel -->
                    <aside class="decision-panel">
                        <div class="decision-header">
                            <h3>Make Decision</h3>
                            <p>Review and approve or reject this booking</p>
                        </div>

                        <div class="decision-content">
                            <!-- Requester Info -->
                            <div class="requester-info">
                                <label>Requested by</label>
                                <div class="requester-card">
                                    <div class="avatar-large">JB</div>
                                    <div>
                                        <div class="requester-name" id="requesterName"></div>
                                        <div class="requester-email" id="requesterEmail"></div>
                                        <div class="requester-dept" id="requesterDept"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Decision Options -->
                            <div class="decision-options">
                                <label>Your Decision <span class="required">*</span></label>
                                <div class="radio-cards">
                                    <label class="radio-card">
                                        <input type="radio" name="decision" value="approve">
                                        <div class="radio-content">
                                            <svg width="23" height="23" viewBox="0 0 23 23" fill="none">
                                                <path d="M19.1667 5.75L8.625 16.2917L3.83333 11.5" stroke="#00A63E" stroke-width="1.33333"/>
                                            </svg>
                                            <span>Approve</span>
                                        </div>
                                        <div class="radio-description">Grant access to the requested room</div>
                                    </label>
                                    <label class="radio-card">
                                        <input type="radio" name="decision" value="reject">
                                        <div class="radio-content">
                                            <svg width="23" height="23" viewBox="0 0 23 23" fill="none">
                                                <path d="M17.25 5.75L5.75 17.25" stroke="#E7000B" stroke-width="1.33333"/>
                                                <path d="M5.75 5.75L17.25 17.25" stroke="#E7000B" stroke-width="1.33333"/>
                                            </svg>
                                            <span>Reject</span>
                                        </div>
                                        <div class="radio-description">Deny this booking request</div>
                                    </label>
                                    <label class="radio-card">
                                        <input type="radio" name="decision" value="info">
                                        <div class="radio-content">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                <circle cx="8" cy="8" r="6.66667" stroke="#155DFC" stroke-width="0.764323"/>
                                                <path d="M6.06002 6.00001C6.21675 5.55446 6.52612 5.17875 6.93332 4.93944C7.34052 4.70012 7.81928 4.61264 8.2848 4.69249C8.75032 4.77234 9.17256 5.01436 9.47674 5.3757C9.78091 5.73703 9.94739 6.19436 9.94668 6.66668C9.94668 8.00001 7.94668 8.66668 7.94668 8.66668" stroke="#155DFC" stroke-width="0.764323"/>
                                                <path d="M8 11.3333H8.00615" stroke="#155DFC" stroke-width="0.764323"/>
                                            </svg>
                                            <span>Ask for More Info</span>
                                        </div>
                                        <div class="radio-description">Request additional details from requester</div>
                                    </label>
                                </div>
                            </div>

                            <!-- Remarks -->
                            <div class="form-group">
                                <label>Remarks</label>
                                <textarea id="remarksInput" placeholder="Select a decision above..." disabled></textarea>
                            </div>

                            <!-- Submit Button -->
                            <button class="btn-submit" id="submitBtn" disabled>Submit Decision</button>

                            <!-- Info Alert -->
                            <div class="alert alert-info">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                    <circle cx="8" cy="8" r="6.66667" stroke="currentColor" stroke-width="1.33333"/>
                                    <path d="M8 10.6667V8" stroke="currentColor" stroke-width="1.33333"/>
                                    <path d="M8 5.33333H8.00667" stroke="currentColor" stroke-width="1.33333"/>
                                </svg>
                                <p>The requester will be notified immediately via email with your decision and remarks.</p>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </main>

    <script>
// Application state
let currentView = 'queue';
let currentBooking = null;
let selectedDecision = null;

// DOM elements
const queueView = document.getElementById('queueView');
const detailView = document.getElementById('detailView');
const bookingsTableBody = document.getElementById('bookingsTableBody');
const backBtn = document.getElementById('backBtn');
const searchInput = document.getElementById('searchInput');
const tabs = document.querySelectorAll('.tab');
const submitBtn = document.getElementById('submitBtn');
const remarksInput = document.getElementById('remarksInput');
const decisionRadios = document.querySelectorAll('input[name="decision"]');

// Initialize app
function init() {
    renderBookingsTable();
    attachEventListeners();
}

// Render bookings table
function renderBookingsTable(filter = '') {
    const filteredBookings = bookingsData.filter(booking => {
        if (!filter) return true;
        const searchTerm = filter.toLowerCase();
        return (
            booking.requester.name.toLowerCase().includes(searchTerm) ||
            booking.facility.toLowerCase().includes(searchTerm) ||
            booking.purpose.toLowerCase().includes(searchTerm)
        );
    });

    bookingsTableBody.innerHTML = filteredBookings.map(booking => `
        <tr onclick="showBookingDetail('${booking.id}')">
            <td>${booking.requestedOn}</td>
            <td>
                <div style="display: flex; align-items: center; gap: 4px;">
                    <svg width="13" height="13" viewBox="0 0 14 14" fill="none" style="color: #99a1af;">
                        <path d="M4.43229 1.10807V3.32422" stroke="currentColor" stroke-width="1.10807"/>
                        <path d="M8.86458 1.10807V3.32422" stroke="currentColor" stroke-width="1.10807"/>
                        <rect x="1.66211" y="2.21615" width="9.97266" height="9.97266" rx="1.10807" stroke="currentColor" stroke-width="1.10807"/>
                        <path d="M1.66211 5.54036H11.6348" stroke="currentColor" stroke-width="1.10807"/>
                    </svg>
                    ${booking.when}
                </div>
            </td>
            <td>
                <div class="facility-info">
                    <div class="facility-name">${booking.facility}</div>
                    <div class="facility-building">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                            <path d="M6 5H6.005" stroke="currentColor"/>
                            <path d="M6 7H6.005" stroke="currentColor"/>
                            <path d="M6 3H6.005" stroke="currentColor"/>
                            <path d="M8 5H8.005" stroke="currentColor"/>
                            <path d="M8 7H8.005" stroke="currentColor"/>
                            <path d="M8 3H8.005" stroke="currentColor"/>
                            <path d="M4 5H4.005" stroke="currentColor"/>
                            <path d="M4 7H4.005" stroke="currentColor"/>
                            <path d="M4 3H4.005" stroke="currentColor"/>
                            <rect x="2" y="1" width="8" height="10" rx="1" stroke="currentColor"/>
                            <path d="M4.5 11V9.5C4.5 9.36739 4.55268 9.24021 4.64645 9.14645C4.74021 9.05268 4.86739 9 5 9H7C7.13261 9 7.25979 9.05268 7.35355 9.14645C7.44732 9.24021 7.5 9.36739 7.5 9.5V11" stroke="currentColor"/>
                        </svg>
                        ${booking.building}
                    </div>
                </div>
            </td>
            <td>
                <div class="requester-info-cell">
                    <div class="requester-name-cell">${booking.requester.name}</div>
                    <div class="requester-dept-cell">${booking.requester.department}</div>
                </div>
            </td>
            <td>${booking.purpose}</td>
            <td>
                <span class="badge badge-${booking.priority}">${booking.priority}</span>
            </td>
            <td>
                <div class="actions-cell">
                    <button class="btn-action btn-approve" onclick="event.stopPropagation(); handleQuickAction('${booking.id}', 'approve')">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M13.3333 4L6 11.3333L2.66667 8" stroke="currentColor" stroke-width="1.33333"/>
                        </svg>
                    </button>
                    <button class="btn-action btn-reject" onclick="event.stopPropagation(); handleQuickAction('${booking.id}', 'reject')">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M12 4L4 12" stroke="currentColor" stroke-width="1.33333"/>
                            <path d="M4 4L12 12" stroke="currentColor" stroke-width="1.33333"/>
                        </svg>
                    </button>
                    <button class="btn-action btn-info" onclick="event.stopPropagation(); showBookingDetail('${booking.id}')">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <circle cx="8" cy="8" r="6.66667" stroke="currentColor" stroke-width="1.33333"/>
                            <path d="M6.06002 6.00001C6.21675 5.55446 6.52612 5.17875 6.93332 4.93944" stroke="currentColor" stroke-width="1.33333"/>
                            <path d="M8 11.3333H8.00667" stroke="currentColor" stroke-width="1.33333"/>
                        </svg>
                    </button>
                    <button class="btn-action btn-more" onclick="event.stopPropagation()">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M4 6L8 10L12 6" stroke="currentColor" stroke-width="1.33333"/>
                        </svg>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

// Show booking detail
function showBookingDetail(bookingId) {
    currentBooking = bookingsData.find(b => b.id === bookingId);
    if (!currentBooking) return;

    // Update detail header
    document.getElementById('bookingRef').textContent = currentBooking.id;
    document.getElementById('submittedDate').textContent = `Submitted: ${currentBooking.submitted}`;
    
    // Show/hide approval alert
    const approvalAlert = document.getElementById('approvalAlert');
    if (currentBooking.needsApproval) {
        approvalAlert.style.display = 'flex';
        approvalAlert.querySelector('p').textContent = currentBooking.approvalReason;
    } else {
        approvalAlert.style.display = 'none';
    }

    // Update requester info
    document.getElementById('requesterName').textContent = currentBooking.requester.name;
    document.getElementById('requesterEmail').textContent = currentBooking.requester.email;
    document.getElementById('requesterDept').textContent = currentBooking.requester.department;
    document.querySelector('.avatar-large').textContent = currentBooking.requester.initials;

    // Render detail cards
    renderDetailCards();

    // Switch view
    queueView.classList.remove('active');
    detailView.classList.add('active');
    currentView = 'detail';
    
    // Scroll to top
    window.scrollTo(0, 0);
}

// Render detail cards
function renderDetailCards() {
    const detailCards = document.getElementById('detailCards');
    
    detailCards.innerHTML = `
        <!-- When & Where Card -->
        <div class="detail-card">
            <div class="detail-card-header">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M6.66667 1.66667V5" stroke="currentColor" stroke-width="1.66667"/>
                    <path d="M13.3333 1.66667V5" stroke="currentColor" stroke-width="1.66667"/>
                    <rect x="2.5" y="3.33333" width="15" height="15" rx="1.66667" stroke="currentColor" stroke-width="1.66667"/>
                    <path d="M2.5 8.33333H17.5" stroke="currentColor" stroke-width="1.66667"/>
                </svg>
                When & Where
            </div>
            <div class="room-preview">
                <img src="${currentBooking.image}" alt="${currentBooking.facility}" class="room-image">
                <div class="room-details">
                    <h3 class="room-name">${currentBooking.facility}</h3>
                    <div class="room-meta">
                        <div class="room-meta-item">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M6 5H6.005" stroke="currentColor"/>
                                <rect x="2" y="1" width="8" height="10" rx="1" stroke="currentColor"/>
                            </svg>
                            ${currentBooking.building}
                        </div>
                        <div class="room-meta-item">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M10.6667 14V12.6667C10.6667 11.9594 10.3857 11.2811 9.88562 10.781" stroke="currentColor"/>
                                <circle cx="6" cy="4.66667" r="2.66667" stroke="currentColor"/>
                            </svg>
                            Capacity: ${currentBooking.capacity}
                        </div>
                    </div>
                    <div class="room-amenities">
                        ${currentBooking.amenities.map(amenity => `
                            <span class="amenity-badge">${amenity}</span>
                        `).join('')}
                    </div>
                </div>
            </div>
            <div class="detail-section">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Date</div>
                        <div class="info-value">${currentBooking.date}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Time</div>
                        <div class="info-value">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" style="vertical-align: middle;">
                                <circle cx="8" cy="8" r="6.66667" stroke="currentColor" stroke-width="1.33333"/>
                                <path d="M8 4V8L10.6667 9.33333" stroke="currentColor" stroke-width="1.33333"/>
                            </svg>
                            ${currentBooking.when.split(' ')[1]}
                            <span style="color: #99a1af; margin-left: 8px;">(${currentBooking.duration})</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Purpose & Manpower Card -->
        <div class="detail-card">
            <div class="detail-card-header">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M5 18.3333C4.55797 18.3333 4.13405 18.1577 3.82149 17.8452" stroke="currentColor" stroke-width="1.66667"/>
                    <path d="M11.6667 1.66667V5.83333C11.6667 6.05435 11.7545 6.26631 11.9107 6.42259" stroke="currentColor" stroke-width="1.66667"/>
                    <path d="M8.33333 7.5H6.66667" stroke="currentColor" stroke-width="1.66667"/>
                    <path d="M13.3333 10.8333H6.66667" stroke="currentColor" stroke-width="1.66667"/>
                    <path d="M13.3333 14.1667H6.66667" stroke="currentColor" stroke-width="1.66667"/>
                </svg>
                Purpose & Manpower
            </div>
            <div style="display: flex; flex-direction: column; gap: 16px;">
                <div class="info-item">
                    <div class="info-label">Meeting Purpose</div>
                    <div class="info-value">${currentBooking.purpose}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Expected Attendees</div>
                    <div class="info-value">${currentBooking.attendees}</div>
                </div>
            </div>
        </div>

        <!-- Policy Compliance Card -->
        <div class="detail-card">
            <div class="detail-card-header">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M18.3333 14.1667C18.3333 14.6087 18.1577 15.0326 17.8452 15.3452" stroke="currentColor" stroke-width="1.66667"/>
                </svg>
                Policy Compliance Check
            </div>
            <div class="compliance-list">
                <div class="compliance-item">
                    <span>${currentBooking.compliance.duration.text}</span>
                    <span class="badge-compliant">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                            <path d="M10 3L4.5 8.5L2 6" stroke="currentColor"/>
                        </svg>
                        Compliant
                    </span>
                </div>
                <div class="compliance-item">
                    <span>${currentBooking.compliance.notice.text}</span>
                    <span class="${currentBooking.compliance.notice.status === 'compliant' ? 'badge-compliant' : 'badge-non-compliant'}">
                        ${currentBooking.compliance.notice.status === 'compliant' ? `
                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                                <path d="M10 3L4.5 8.5L2 6" stroke="currentColor"/>
                            </svg>
                            Compliant
                        ` : `
                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                                <path d="M9 3L3 9" stroke="currentColor"/>
                                <path d="M3 3L9 9" stroke="currentColor"/>
                            </svg>
                            Non-compliant
                        `}
                    </span>
                </div>
                <div class="compliance-item">
                    <span>${currentBooking.compliance.capacity.text}</span>
                    <span class="badge-compliant">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                            <path d="M10 3L4.5 8.5L2 6" stroke="currentColor"/>
                        </svg>
                        Compliant
                    </span>
                </div>
            </div>
        </div>

        <!-- Scheduling Conflicts Card -->
        <div class="detail-card">
            <div class="detail-card-header">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <circle cx="10" cy="10" r="8.33333" stroke="currentColor" stroke-width="1.66667"/>
                    <path d="M10 6.66667V10" stroke="currentColor" stroke-width="1.66667"/>
                    <path d="M10 13.3333H10.0083" stroke="currentColor" stroke-width="1.66667"/>
                </svg>
                Scheduling Conflicts
            </div>
            <div class="conflicts-empty">
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                    <path d="M43.602 20C44.5154 24.4826 43.8645 29.1428 41.7577 33.2036" stroke="currentColor" stroke-width="4"/>
                    <path d="M18 22L24 28L44 8" stroke="currentColor" stroke-width="4"/>
                </svg>
                <p>No conflicts detected</p>
            </div>
        </div>

        <!-- Additional Notes Card -->
        <div class="detail-card">
            <div class="detail-card-header">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M5 18.3333C4.55797 18.3333 4.13405 18.1577 3.82149 17.8452" stroke="currentColor" stroke-width="1.66667"/>
                    <path d="M11.6667 1.66667V5.83333C11.6667 6.05435 11.7545 6.26631 11.9107 6.42259" stroke="currentColor" stroke-width="1.66667"/>
                </svg>
                Additional Notes
            </div>
            <p style="color: #364153; font-size: 14px;">${currentBooking.notes}</p>
        </div>
    `;
}

// Go back to queue
function goBack() {
    detailView.classList.remove('active');
    queueView.classList.add('active');
    currentView = 'queue';
    currentBooking = null;
    selectedDecision = null;
    resetDecisionForm();
}

// Handle quick actions from table
function handleQuickAction(bookingId, action) {
    const booking = bookingsData.find(b => b.id === bookingId);
    if (!booking) return;

    const actionText = action === 'approve' ? 'approve' : 'reject';
    if (confirm(`Are you sure you want to ${actionText} booking ${bookingId}?`)) {
        alert(`Booking ${bookingId} has been ${action}d successfully!`);
        // In a real app, this would make an API call
    }
}

// Reset decision form
function resetDecisionForm() {
    decisionRadios.forEach(radio => radio.checked = false);
    remarksInput.value = '';
    remarksInput.disabled = true;
    submitBtn.disabled = true;
    selectedDecision = null;
}

// Handle decision change
function handleDecisionChange() {
    selectedDecision = document.querySelector('input[name="decision"]:checked')?.value;
    
    if (selectedDecision) {
        remarksInput.disabled = false;
        submitBtn.disabled = false;
        
        // Update placeholder based on decision
        if (selectedDecision === 'approve') {
            remarksInput.placeholder = 'Add any approval conditions or notes (optional)...';
        } else if (selectedDecision === 'reject') {
            remarksInput.placeholder = 'Please provide a reason for rejection...';
        } else {
            remarksInput.placeholder = 'What additional information do you need?';
        }
    } else {
        remarksInput.disabled = true;
        remarksInput.placeholder = 'Select a decision above...';
        submitBtn.disabled = true;
    }
}

// Submit decision
function submitDecision() {
    if (!selectedDecision || !currentBooking) return;

    const remarks = remarksInput.value.trim();
    const decisionText = selectedDecision === 'approve' ? 'approved' : 
                        selectedDecision === 'reject' ? 'rejected' : 
                        'sent for more information';

    alert(`Booking ${currentBooking.id} has been ${decisionText}!\n\nRemarks: ${remarks || 'None'}\n\nThe requester will be notified via email.`);
    
    // In a real app, this would make an API call
    goBack();
}

// Attach event listeners
function attachEventListeners() {
    // Back button
    backBtn.addEventListener('click', goBack);

    // Search
    searchInput.addEventListener('input', (e) => {
        renderBookingsTable(e.target.value);
    });

    // Tabs
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            // In a real app, this would filter the table
        });
    });

    // Decision radios
    decisionRadios.forEach(radio => {
        radio.addEventListener('change', handleDecisionChange);
    });

    // Submit button
    submitBtn.addEventListener('click', submitDecision);
}

// Make functions available globally for onclick handlers
window.showBookingDetail = showBookingDetail;
window.handleQuickAction = handleQuickAction;

// Initialize on page load
document.addEventListener('DOMContentLoaded', init);

</script>
    <script>
// Sample booking data
const bookingsData = [
    {
        id: 'BK-2025-0142',
        requestedOn: 'Nov 10, 02:30 PM',
        when: '2025-11-18 14:00-16:00',
        duration: '2 hours',
        facility: 'Conference Room A',
        building: 'Building A',
        capacity: '12 people',
        amenities: ['projector', 'whiteboard', 'tv'],
        requester: {
            name: 'Charles Ramos',
            email: 'charles@enc.gov',
            department: 'IT Department',
            initials: 'CR'
        },
        purpose: 'Quarterly Team Review Meeting',
        attendees: '10 people',
        priority: 'high',
        date: 'Monday, November 18, 2025',
        notes: 'Need setup 15 minutes before meeting. Refreshments requested.',
        submitted: '2025-11-11 09:30 AM',
        compliance: {
            duration: { status: 'compliant', text: 'Maximum booking duration: 4 hours' },
            notice: { status: 'non-compliant', text: 'Advance notice: 24 hours' },
            capacity: { status: 'compliant', text: 'Room capacity limits' }
        },
        needsApproval: true,
        approvalReason: 'Booking made less than 24 hours in advance requires manager approval per Policy #5.2',
        image: 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=400&h=300&fit=crop'
    },
    {
        id: 'BK-2025-0141',
        requestedOn: 'Nov 9, 09:15 AM',
        when: '2025-11-20 09:00-12:00',
        duration: '3 hours',
        facility: 'Training Hall B',
        building: 'Building B',
        capacity: '30 people',
        amenities: ['projector', 'sound system'],
        requester: {
            name: 'Maria Santos',
            email: 'maria@enc.gov',
            department: 'HR Department',
            initials: 'MS'
        },
        purpose: 'New Employee Orientation',
        attendees: '25 people',
        priority: 'medium',
        date: 'Wednesday, November 20, 2025',
        notes: 'Please arrange chairs in theater style.',
        submitted: '2025-11-09 09:15 AM',
        compliance: {
            duration: { status: 'compliant', text: 'Maximum booking duration: 4 hours' },
            notice: { status: 'compliant', text: 'Advance notice: 24 hours' },
            capacity: { status: 'compliant', text: 'Room capacity limits' }
        },
        needsApproval: false,
        image: 'https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?w=400&h=300&fit=crop'
    },
    {
        id: 'BK-2025-0140',
        requestedOn: 'Nov 11, 04:00 PM',
        when: '2025-11-15 15:00-17:00',
        duration: '2 hours',
        facility: 'Meeting Room C',
        building: 'Building A',
        capacity: '8 people',
        amenities: ['tv', 'whiteboard'],
        requester: {
            name: 'John Dela Cruz',
            email: 'john@enc.gov',
            department: 'Finance Department',
            initials: 'JD'
        },
        purpose: 'Budget Planning Session',
        attendees: '6 people',
        priority: 'low',
        date: 'Friday, November 15, 2025',
        notes: 'Confidential meeting - please ensure privacy.',
        submitted: '2025-11-11 04:00 PM',
        compliance: {
            duration: { status: 'compliant', text: 'Maximum booking duration: 4 hours' },
            notice: { status: 'compliant', text: 'Advance notice: 24 hours' },
            capacity: { status: 'compliant', text: 'Room capacity limits' }
        },
        needsApproval: false,
        image: 'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?w=400&h=300&fit=crop'
    }
];

// Make data available globally
window.bookingsData = bookingsData;

</script>
</body>
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background: #f8fafc;
    color: #0a0a0a;
    line-height: 1.5;
}

.container {
    max-width: 1294px;
    margin: 0 auto;
    padding: 0 24px;
}

/* Header */
.app-header {
    background: white;
    border-bottom: 1px solid #e2e8f0;
    position: sticky;
    top: 0;
    z-index: 100;
}

.header-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 0;
    gap: 24px;
}

.logo-section {
    display: flex;
    align-items: center;
    gap: 12px;
}

.logo-circle {
    width: 48px;
    height: 48px;
    background: #1e40af;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.header-title {
    font-size: 20px;
    color: #1e3a8a;
    font-weight: normal;
}

.header-subtitle {
    font-size: 12px;
    color: #6a7282;
}

.nav-buttons {
    display: flex;
    align-items: center;
    gap: 8px;
    flex: 1;
    justify-content: center;
}

.nav-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border: 1px solid #e2e8f0;
    background: white;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.2s;
}

.nav-btn:hover {
    background: #f8fafc;
}

.nav-btn.active {
    background: #9810fa;
    color: white;
    border-color: #9810fa;
}

.badge-admin {
    background: #fb2c36;
    color: white;
    padding: 4px 12px;
    border-radius: 6px;
    font-size: 12px;
}

.notification-btn {
    position: relative;
    background: none;
    border: none;
    padding: 8px;
    cursor: pointer;
    border-radius: 8px;
}

.notification-btn:hover {
    background: #f8fafc;
}

.notification-badge {
    position: absolute;
    top: 2px;
    right: 2px;
    background: #fb2c36;
    color: white;
    font-size: 10px;
    padding: 2px 6px;
    border-radius: 10px;
}

.user-section {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border-radius: 8px;
    cursor: pointer;
}

.user-section:hover {
    background: #f8fafc;
}

.user-avatar {
    width: 36px;
    height: 36px;
    background: #1e40af;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

.user-name {
    font-size: 14px;
    color: #1e3a8a;
}

.user-email {
    font-size: 12px;
    color: #6a7282;
}

/* Main Content */
.main-content {
    padding: 24px 0;
}

.view {
    display: none;
}

.view.active {
    display: block;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 24px;
}

.page-title {
    font-size: 24px;
    color: #101828;
    margin-bottom: 4px;
}

.page-subtitle {
    font-size: 14px;
    color: #6a7282;
}

.header-actions {
    display: flex;
    gap: 8px;
}

.btn-secondary {
    padding: 8px 16px;
    border: 1px solid #e2e8f0;
    background: white;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
}

.btn-secondary:hover {
    background: #f8fafc;
}

/* Tabs */
.tabs {
    background: #f1f5f9;
    padding: 4px;
    border-radius: 12px;
    display: flex;
    gap: 4px;
    margin-bottom: 24px;
}

.tab {
    flex: 1;
    padding: 8px 16px;
    border: none;
    background: transparent;
    border-radius: 12px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.2s;
}

.tab.active {
    background: white;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

/* Filters */
.filters-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
    display: flex;
    gap: 12px;
}

.search-box {
    flex: 1;
    position: relative;
}

.search-box svg {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #99a1af;
}

.search-box input {
    width: 100%;
    padding: 8px 8px 8px 36px;
    border: 1px solid transparent;
    background: #f8fafc;
    border-radius: 6px;
    font-size: 14px;
}

.search-box input:focus {
    outline: none;
    border-color: #155dfc;
}

.filter-buttons {
    display: flex;
    gap: 12px;
}

.filter-select {
    padding: 8px 12px;
    border: 1px solid transparent;
    background: #f8fafc;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
    min-width: 150px;
}

.filter-select:focus {
    outline: none;
    border-color: #155dfc;
}

/* Table */
.table-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 24px;
}

.card-title {
    font-size: 16px;
    margin-bottom: 24px;
}

.table-container {
    overflow-x: auto;
}

.booking-table {
    width: 100%;
    border-collapse: collapse;
}

.booking-table thead {
    background: #f8fafc;
}

.booking-table th {
    text-align: left;
    padding: 12px;
    font-size: 14px;
    color: #4a5565;
    font-weight: bold;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

.booking-table td {
    padding: 12px;
    font-size: 14px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

.booking-table tbody tr {
    transition: background 0.2s;
    cursor: pointer;
}

.booking-table tbody tr:hover {
    background: #f8fafc;
}

.badge {
    display: inline-block;
    padding: 3px 9px;
    border-radius: 8px;
    font-size: 12px;
    border: 1px solid;
}

.badge-high {
    background: #ffe2e2;
    color: #c10007;
    border-color: #ffa2a2;
}

.badge-medium {
    background: #fef9c2;
    color: #a65f00;
    border-color: #ffdf20;
}

.badge-low {
    background: #dcfce7;
    color: #008236;
    border-color: #7bf1a8;
}

.facility-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.facility-name {
    font-size: 14px;
    color: #0a0a0a;
}

.facility-building {
    font-size: 12px;
    color: #6a7282;
    display: flex;
    align-items: center;
    gap: 4px;
}

.requester-info-cell {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.requester-name-cell {
    font-size: 14px;
    color: #0a0a0a;
}

.requester-dept-cell {
    font-size: 12px;
    color: #6a7282;
}

.actions-cell {
    display: flex;
    gap: 8px;
}

.btn-action {
    width: 36px;
    height: 32px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-approve {
    background: #00a63e;
    color: white;
}

.btn-reject {
    background: #d4183d;
    color: white;
}

.btn-info {
    background: white;
    border: 1px solid rgba(0, 0, 0, 0.1);
}

.btn-more {
    background: transparent;
}

/* Detail View */
.detail-view {
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.detail-header {
    display: flex;
    gap: 16px;
    margin-bottom: 24px;
    padding: 24px;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
}

.btn-back {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    height: 32px;
    border: 1px solid rgba(0, 0, 0, 0.1);
    background: white;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
}

.detail-header-content {
    flex: 1;
}

.detail-title {
    font-size: 24px;
    color: #1c398e;
    display: inline-block;
    margin-right: 12px;
}

.badge-status {
    display: inline-block;
    padding: 3px 9px;
    background: #fef9c2;
    color: #a65f00;
    border: 1px solid #ffdf20;
    border-radius: 8px;
    font-size: 12px;
}

.detail-meta {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: 4px;
    font-size: 14px;
    color: #4a5565;
}

.link-primary {
    color: #155dfc;
    text-decoration: none;
}

.link-primary:hover {
    text-decoration: underline;
}

.detail-content {
    display: grid;
    grid-template-columns: 1fr 332px;
    gap: 24px;
}

.detail-main {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

/* Alert */
.alert {
    display: flex;
    gap: 12px;
    padding: 16px;
    border-radius: 10px;
    border: 1px solid;
}

.alert svg {
    flex-shrink: 0;
    margin-top: 2px;
}

.alert-warning {
    background: #fef3c7;
    border-color: #fee685;
    color: #7b3306;
}

.alert-warning strong {
    color: #7b3306;
}

.alert-warning p {
    color: #973c00;
    margin-top: 4px;
}

.alert-info {
    background: #eff6ff;
    border-color: #bedbff;
    color: #193cb8;
}

.alert-info p {
    font-size: 12px;
}

/* Detail Cards */
.detail-card {
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 14px;
    padding: 26px;
}

.detail-card-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 24px;
    font-size: 16px;
    color: #0a0a0a;
}

.detail-card-header svg {
    color: #155dfc;
}

.room-preview {
    display: flex;
    gap: 16px;
    margin-bottom: 16px;
}

.room-image {
    width: 128px;
    height: 96px;
    border-radius: 4px;
    background: #e2e8f0;
    object-fit: cover;
}

.room-details {
    flex: 1;
}

.room-name {
    font-size: 18px;
    margin-bottom: 8px;
}

.room-meta {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 8px;
}

.room-meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #4a5565;
}

.room-meta-item svg {
    color: #4a5565;
}

.room-amenities {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.amenity-badge {
    padding: 3px 9px;
    background: white;
    border: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    font-size: 12px;
}

.detail-section {
    padding-top: 16px;
    border-top: 1px solid rgba(0, 0, 0, 0.1);
}

.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.info-label {
    font-size: 12px;
    color: #6a7282;
}

.info-value {
    font-size: 14px;
    color: #0a0a0a;
}

.info-value svg {
    vertical-align: middle;
    margin-right: 4px;
}

.compliance-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.compliance-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px;
    background: #f8fafc;
    border-radius: 10px;
}

.badge-compliant {
    background: #dcfce7;
    color: #008236;
    padding: 3px 9px;
    border-radius: 8px;
    font-size: 12px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.badge-non-compliant {
    background: #ffe2e2;
    color: #c10007;
    padding: 3px 9px;
    border-radius: 8px;
    font-size: 12px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.conflicts-empty {
    text-align: center;
    padding: 48px 0;
}

.conflicts-empty svg {
    margin: 0 auto 16px;
    color: #00c950;
}

.conflicts-empty p {
    color: #4a5565;
}

/* Decision Panel */
.decision-panel {
    background: white;
    border: 2px solid #bedbff;
    border-radius: 14px;
    overflow: hidden;
    height: fit-content;
    position: sticky;
    top: 100px;
}

.decision-header {
    background: #eff6ff;
    padding: 24px;
}

.decision-header h3 {
    font-size: 16px;
    margin-bottom: 6px;
}

.decision-header p {
    font-size: 16px;
    color: #717182;
}

.decision-content {
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.requester-info label {
    display: block;
    font-size: 12px;
    color: #6a7282;
    margin-bottom: 8px;
}

.requester-card {
    display: flex;
    align-items: center;
    gap: 12px;
    padding-bottom: 24px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

.avatar-large {
    width: 40px;
    height: 40px;
    background: #dbeafe;
    color: #155dfc;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

.requester-name {
    font-size: 14px;
    color: #0a0a0a;
}

.requester-email {
    font-size: 12px;
    color: #6a7282;
}

.requester-dept {
    font-size: 12px;
    color: #6a7282;
}

.decision-options label {
    display: block;
    font-size: 14px;
    margin-bottom: 12px;
}

.required {
    color: #fb2c36;
}

.radio-cards {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.radio-card {
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    padding: 14px;
    cursor: pointer;
    position: relative;
    transition: all 0.2s;
}

.radio-card:hover {
    border-color: #155dfc;
}

.radio-card input {
    position: absolute;
    opacity: 0;
}

.radio-card input:checked + .radio-content {
    color: #155dfc;
}

.radio-card input:checked ~ .radio-description {
    color: #155dfc;
}

.radio-content {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    margin-bottom: 4px;
}

.radio-description {
    font-size: 12px;
    color: #6a7282;
    margin-left: 31px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-group label {
    font-size: 14px;
}

.form-group textarea {
    padding: 8px 12px;
    border: 1px solid transparent;
    background: #f3f3f5;
    border-radius: 8px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    resize: vertical;
    min-height: 64px;
}

.form-group textarea:focus {
    outline: none;
    border-color: #155dfc;
}

.form-group textarea:disabled {
    color: #717182;
}

.btn-submit {
    width: 100%;
    padding: 8px 16px;
    background: #99a1af;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    cursor: not-allowed;
    opacity: 0.5;
}

.btn-submit:not(:disabled) {
    background: #155dfc;
    cursor: pointer;
    opacity: 1;
}

.btn-submit:not(:disabled):hover {
    background: #0d47d4;
}

/* Responsive */
@media (max-width: 1024px) {
    .detail-content {
        grid-template-columns: 1fr;
    }

    .decision-panel {
        position: static;
    }

    .nav-buttons {
        flex-wrap: wrap;
    }

    .header-content {
        flex-wrap: wrap;
    }
}

@media (max-width: 768px) {
    .filters-card {
        flex-direction: column;
    }

    .filter-buttons {
        flex-direction: column;
    }

    .table-container {
        overflow-x: auto;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .room-preview {
        flex-direction: column;
    }

    .room-image {
        width: 100%;
    }
}
</style>
</html>
