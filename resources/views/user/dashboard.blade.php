<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONE ENC Community - Shared Services Portal</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Header -->
    <header class="dashboard-header">
        <div class="container">
            <div class="header-content">
                <button class="brand-button">
                    <img src="https://raw.githubusercontent.com/figma/img/master/6c4079e2ed27821998f79c93a3b09111b61f0dd3.png" alt="ONE Logo" class="brand-logo">
                    <div class="brand-text">
                        <h1 class="brand-title">Shared Services Portal</h1>
                        <p class="brand-subtitle">One-Stop Booking Platform</p>
                    </div>
                </button>
                
                <div class="header-actions">
                    <button class="btn-nav">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M8 4.66667V14" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M2 12C1.82319 12 1.65362 11.9298 1.5286 11.8047C1.40357 11.6797 1.33333 11.5101 1.33333 11.3333V2.66667C1.33333 2.48986 1.40357 2.32029 1.5286 2.19526C1.65362 2.07024 1.82319 2 2 2H5.33333C6.04058 2 6.71885 2.28095 7.21895 2.78105C7.71905 3.28115 8 3.95942 8 4.66667C8 3.95942 8.28095 3.28115 8.78105 2.78105C9.28115 2.28095 9.95942 2 10.6667 2H14C14.1768 2 14.3464 2.07024 14.4714 2.19526C14.5964 2.32029 14.6667 2.48986 14.6667 2.66667V11.3333C14.6667 11.5101 14.5964 11.6797 14.4714 11.8047C14.3464 11.9298 14.1768 12 14 12H10C9.46957 12 8.96086 12.2107 8.58579 12.5858C8.21071 12.9609 8 13.4696 8 14C8 13.4696 7.78929 12.9609 7.41421 12.5858C7.03914 12.2107 6.53043 12 6 12H2Z" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>My Bookings</span>
                    </button>
                    
                    <div class="badge-user">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                            <path d="M10 6.5C10 9 8.25 10.25 6.17 10.975C6.06108 11.0119 5.94277 11.0101 5.835 10.97C3.75 10.25 2 9 2 6.5V3C2 2.86739 2.05268 2.74021 2.14645 2.64645C2.24021 2.55268 2.36739 2.5 2.5 2.5C3.5 2.5 4.75 1.9 5.62 1.14C5.72593 1.0495 5.86068 0.999775 6 0.999775C6.13932 0.999775 6.27407 1.0495 6.38 1.14C7.255 1.905 8.5 2.5 9.5 2.5C9.63261 2.5 9.75979 2.55268 9.85355 2.64645C9.94732 2.74021 10 2.86739 10 3V6.5Z" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>User</span>
                    </div>
                    
                    <button class="notifications-btn">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M2.71833 12.7717C2.60947 12.891 2.53763 13.0394 2.51155 13.1988C2.48547 13.3582 2.50627 13.5217 2.57142 13.6695C2.63658 13.8173 2.74328 13.943 2.87855 14.0312C3.01381 14.1195 3.17182 14.1665 3.33333 14.1667H16.6667C16.8282 14.1667 16.9862 14.1199 17.1216 14.0318C17.2569 13.9437 17.3637 13.8181 17.4291 13.6704C17.4944 13.5227 17.5154 13.3592 17.4895 13.1998C17.4637 13.0404 17.392 12.892 17.2833 12.7725C16.175 11.63 15 10.4158 15 6.66667C15 5.34058 14.4732 4.06881 13.5355 3.13113C12.5979 2.19345 11.3261 1.66667 10 1.66667C8.67392 1.66667 7.40215 2.19345 6.46447 3.13113C5.52679 4.06881 5 5.34058 5 6.66667C5 10.4158 3.82417 11.63 2.71833 12.7717Z" stroke="#4A5565" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M8.55667 17.5C8.70296 17.7533 8.91335 17.9637 9.16671 18.11C9.42006 18.2563 9.70746 18.3333 10 18.3333C10.2926 18.3333 10.5799 18.2563 10.8333 18.11C11.0867 17.9637 11.2971 17.7533 11.4433 17.5" stroke="#4A5565" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="notification-badge">2</span>
                    </button>
                    
                    <button class="user-menu">
                        <div class="user-avatar">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M15.8333 17.5V15.8333C15.8333 14.9493 15.4821 14.1014 14.857 13.4763C14.2319 12.8512 13.3841 12.5 12.5 12.5H7.5C6.61594 12.5 5.7681 12.8512 5.14298 13.4763C4.51786 14.1014 4.16667 14.9493 4.16667 15.8333V17.5" stroke="white" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M10 9.16667C11.8409 9.16667 13.3333 7.67428 13.3333 5.83333C13.3333 3.99238 11.8409 2.5 10 2.5C8.15905 2.5 6.66667 3.99238 6.66667 5.83333C6.66667 7.67428 8.15905 9.16667 10 9.16667Z" stroke="white" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="user-info">
                            <p class="user-name">Charles Ramos</p>
                            <p class="user-email">user.charles@enc.gov</p>
                        </div>
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M4 6L8 10L12 6" stroke="#6A7282" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        <div class="main-content">
            <!-- Hero Section -->
            <div class="hero-section">
                <div class="hero-content">
                    <div class="hero-left">
                        <div class="greeting">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M9.18083 2.345C9.21654 2.15384 9.31798 1.98118 9.46758 1.85693C9.61718 1.73268 9.80553 1.66467 10 1.66467C10.1945 1.66467 10.3828 1.73268 10.5324 1.85693C10.682 1.98118 10.7835 2.15384 10.8192 2.345L11.695 6.97667C11.7572 7.30596 11.9172 7.60885 12.1542 7.84581C12.3912 8.08277 12.694 8.24279 13.0233 8.305L17.655 9.18083C17.8462 9.21654 18.0188 9.31798 18.1431 9.46758C18.2673 9.61718 18.3353 9.80553 18.3353 10C18.3353 10.1945 18.2673 10.3828 18.1431 10.5324C18.0188 10.682 17.8462 10.7835 17.655 10.8192L13.0233 11.695C12.694 11.7572 12.3912 11.9172 12.1542 12.1542C11.9172 12.3912 11.7572 12.694 11.695 13.0233L10.8192 17.655C10.7835 17.8462 10.682 18.0188 10.5324 18.1431C10.3828 18.2673 10.1945 18.3353 10 18.3353C9.80553 18.3353 9.61718 18.2673 9.46758 18.1431C9.31798 18.0188 9.21654 17.8462 9.18083 17.655L8.305 13.0233C8.24279 12.694 8.08277 12.3912 7.84581 12.1542C7.60885 11.9172 7.30596 11.7572 6.97667 11.695L2.345 10.8192C2.15384 10.7835 1.98118 10.682 1.85693 10.5324C1.73268 10.3828 1.66467 10.1945 1.66467 10C1.66467 9.80553 1.73268 9.61718 1.85693 9.46758C1.98118 9.31798 2.15384 9.21654 2.345 9.18083L6.97667 8.305C7.30596 8.24279 7.60885 8.08277 7.84581 7.84581C8.08277 7.60885 8.24279 7.30596 8.305 6.97667L9.18083 2.345Z" stroke="#8EC5FF" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M16.6667 1.66667V5" stroke="#8EC5FF" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M18.3333 3.33333H15" stroke="#8EC5FF" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M3.33333 18.3333C4.25381 18.3333 5 17.5871 5 16.6667C5 15.7462 4.25381 15 3.33333 15C2.41286 15 1.66667 15.7462 1.66667 16.6667C1.66667 17.5871 2.41286 18.3333 3.33333 18.3333Z" stroke="#8EC5FF" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>Good evening</span>
                        </div>
                        <h2 class="hero-heading">Charles Ramos</h2>
                        <p class="hero-subtitle">No upcoming bookings. Ready to schedule your next meeting?</p>
                        <div class="hero-actions">
                            <button class="btn-browse">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                    <path d="M14 14L11.1067 11.1067" stroke="#1C398E" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M7.33333 12.6667C10.2789 12.6667 12.6667 10.2789 12.6667 7.33333C12.6667 4.38781 10.2789 2 7.33333 2C4.38781 2 2 4.38781 2 7.33333C2 10.2789 4.38781 12.6667 7.33333 12.6667Z" stroke="#1C398E" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Browse Rooms
                            </button>
                            <button class="btn-new-booking">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                    <path d="M3.33333 8H12.6667" stroke="white" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M8 3.33333V12.6667" stroke="white" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                New Booking
                            </button>
                        </div>
                    </div>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <div class="stat-value">3</div>
                            <div class="stat-label">Total</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value stat-pending">3</div>
                            <div class="stat-label">Pending</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value stat-confirmed">0</div>
                            <div class="stat-label">Confirmed</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="content-grid">
                <!-- Upcoming Card -->
                <div class="card upcoming-card">
                    <div class="card-header">
                        <div class="card-title-group">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M6.66667 1.66667V5" stroke="#99A1AF" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M13.3333 1.66667V5" stroke="#99A1AF" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M15.8333 3.33333H4.16667C3.24619 3.33333 2.5 4.07953 2.5 5V16.6667C2.5 17.5871 3.24619 18.3333 4.16667 18.3333H15.8333C16.7538 18.3333 17.5 17.5871 17.5 16.6667V5C17.5 4.07953 16.7538 3.33333 15.8333 3.33333Z" stroke="#99A1AF" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M2.5 8.33333H17.5" stroke="#99A1AF" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <h3>Upcoming</h3>
                        </div>
                        <div class="badge-count">0</div>
                    </div>
                    <div class="card-empty">
                        <div class="empty-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M8 2V6" stroke="#D1D5DC" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M16 2V6" stroke="#D1D5DC" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M19 4H5C3.89543 4 3 4.89543 3 6V20C3 21.1046 3.89543 22 5 22H19C20.1046 22 21 21.1046 21 20V6C21 4.89543 20.1046 4 19 4Z" stroke="#D1D5DC" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M3 10H21" stroke="#D1D5DC" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <p>No upcoming bookings</p>
                    </div>
                </div>

                <!-- My Bookings Card -->
                <div class="card bookings-card">
                    <div class="card-header">
                        <div class="card-title-group">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M2.5 2.5V15.8333C2.5 16.2754 2.67559 16.6993 2.98816 17.0118C3.30072 17.3244 3.72464 17.5 4.16667 17.5H17.5" stroke="#99A1AF" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M15 14.1667V7.5" stroke="#99A1AF" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M10.8333 14.1667V4.16667" stroke="#99A1AF" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M6.66667 14.1667V11.6667" stroke="#99A1AF" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <h3>My Bookings</h3>
                        </div>
                        <button class="btn-expand">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M2 8C2 6.4087 2.63214 4.88258 3.75736 3.75736C4.88258 2.63214 6.4087 2 8 2C9.67737 2.00631 11.2874 2.66082 12.4933 3.82667L14 5.33333" stroke="black" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M14 2V5.33333H10.6667" stroke="black" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M14 8C14 9.5913 13.3679 11.1174 12.2426 12.2426C11.1174 13.3679 9.5913 14 8 14C6.32263 13.9937 4.71265 13.3392 3.50667 12.1733L2 10.6667" stroke="black" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M5.33333 10.6667H2V14" stroke="black" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="user-info-box">
                        <p class="info-label">Logged in as</p>
                        <p class="info-value">user.charles@enc.gov</p>
                        <p class="info-bookings">Total Bookings: <span>3</span></p>
                    </div>

                    <div class="tabs">
                        <button class="tab-btn active">Pending (3)</button>
                        <button class="tab-btn">Confirmed (0)</button>
                    </div>

                    <div class="bookings-list">
                        <div class="booking-item">
                            <div class="booking-header">
                                <span class="badge-pending">Pending</span>
                                <span class="booking-date">Sat, Nov 29</span>
                            </div>
                            <div class="booking-content">
                                <div class="booking-icon">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <path d="M5.33333 1.33333V4" stroke="#155DFC" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M10.6667 1.33333V4" stroke="#155DFC" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M12.6667 2.66667H3.33333C2.59695 2.66667 2 3.26362 2 4V13.3333C2 14.0697 2.59695 14.6667 3.33333 14.6667H12.6667C13.403 14.6667 14 14.0697 14 13.3333V4C14 3.26362 13.403 2.66667 12.6667 2.66667Z" stroke="#155DFC" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M2 6.66667H14" stroke="#155DFC" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="booking-details">
                                    <p class="booking-title">Meeting Room</p>
                                    <p class="booking-time">11:00 AM - 12:00 PM</p>
                                </div>
                            </div>
                        </div>

                        <div class="booking-item">
                            <div class="booking-header">
                                <span class="badge-pending">Pending</span>
                                <span class="booking-date">Sat, Nov 29</span>
                            </div>
                            <div class="booking-content">
                                <div class="booking-icon">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <path d="M5.33333 1.33333V4" stroke="#155DFC" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M10.6667 1.33333V4" stroke="#155DFC" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M12.6667 2.66667H3.33333C2.59695 2.66667 2 3.26362 2 4V13.3333C2 14.0697 2.59695 14.6667 3.33333 14.6667H12.6667C13.403 14.6667 14 14.0697 14 13.3333V4C14 3.26362 13.403 2.66667 12.6667 2.66667Z" stroke="#155DFC" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M2 6.66667H14" stroke="#155DFC" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="booking-details">
                                    <p class="booking-title">Meeting Room</p>
                                    <p class="booking-time">10:30 AM - 11:00 AM</p>
                                </div>
                            </div>
                        </div>

                        <div class="booking-item">
                            <div class="booking-header">
                                <span class="badge-pending">Pending</span>
                                <span class="booking-date">Sat, Nov 29</span>
                            </div>
                            <div class="booking-content">
                                <div class="booking-icon">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <path d="M5.33333 1.33333V4" stroke="#155DFC" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M10.6667 1.33333V4" stroke="#155DFC" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M12.6667 2.66667H3.33333C2.59695 2.66667 2 3.26362 2 4V13.3333C2 14.0697 2.59695 14.6667 3.33333 14.6667H12.6667C13.403 14.6667 14 14.0697 14 13.3333V4C14 3.26362 13.403 2.66667 12.6667 2.66667Z" stroke="#155DFC" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M2 6.66667H14" stroke="#155DFC" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="booking-details">
                                    <p class="booking-title">Meeting Room</p>
                                    <p class="booking-time">9:00 AM - 10:00 AM</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button class="btn-view-all">
                        View All Bookings
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M3.33333 8H12.6667" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M8 3.33333L12.6667 8L8 12.6667" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </main>

    <script> 
// Tab switching functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all tabs
            tabButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked tab
            this.classList.add('active');
            
            // In a real application, you would filter the bookings list here
            // based on the selected tab (Pending vs Confirmed)
        });
    });

    // Button click handlers (for demo purposes)
    const browseRoomsBtn = document.querySelector('.btn-browse');
    const newBookingBtn = document.querySelector('.btn-new-booking');
    const viewAllBtn = document.querySelector('.btn-view-all');
    const expandBtn = document.querySelector('.btn-expand');

    if (browseRoomsBtn) {
        browseRoomsBtn.addEventListener('click', function() {
            alert('Browse Rooms functionality would be implemented here');
        });
    }

    if (newBookingBtn) {
        newBookingBtn.addEventListener('click', function() {
            alert('New Booking form would open here');
        });
    }

    if (viewAllBtn) {
        viewAllBtn.addEventListener('click', function() {
            alert('View All Bookings page would open here');
        });
    }

    if (expandBtn) {
        expandBtn.addEventListener('click', function() {
            alert('Refresh bookings functionality would be implemented here');
        });
    }

    // User menu dropdown (basic toggle)
    const userMenu = document.querySelector('.user-menu');
    
    if (userMenu) {
        userMenu.addEventListener('click', function() {
            alert('User menu dropdown would appear here');
        });
    }

    // Notifications button
    const notificationsBtn = document.querySelector('.notifications-btn');
    
    if (notificationsBtn) {
        notificationsBtn.addEventListener('click', function() {
            alert('Notifications panel would open here');
        });
    }

    // My Bookings navigation button
    const navBtn = document.querySelector('.btn-nav');
    
    if (navBtn) {
        navBtn.addEventListener('click', function() {
            alert('Navigate to My Bookings page');
        });
    }

    // Individual booking items click
    const bookingItems = document.querySelectorAll('.booking-item');
    
    bookingItems.forEach(item => {
        item.addEventListener('click', function() {
            alert('Booking details would be shown here');
        });
        
        // Add hover effect
        item.style.cursor = 'pointer';
    });
});

// Smooth scroll behavior
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
});


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
    background-color: #f8fafc;
    color: #000;
}

/* Header Styles */
.dashboard-header {
    background: white;
    border-bottom: 0.8px solid #e5e7eb;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 100;
}

.container {
    max-width: 1294px;
    margin: 0 auto;
    padding: 16px 24px;
}

.header-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 52px;
}

.brand-button {
    display: flex;
    gap: 12px;
    align-items: center;
    background: none;
    border: none;
    cursor: pointer;
}

.brand-logo {
    width: 48px;
    height: 48px;
    object-fit: contain;
}

.brand-text {
    text-align: left;
}

.brand-title {
    font-size: 20px;
    line-height: 28px;
    color: #1c398e;
    margin-bottom: 0;
}

.brand-subtitle {
    font-size: 12px;
    line-height: 16px;
    color: #6a7282;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-nav {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    background: white;
    border: 0.8px solid #e2e8f0;
    border-radius: 6px;
    font-size: 14px;
    line-height: 20px;
    cursor: pointer;
    height: 36px;
}

.btn-nav:hover {
    background: #f8fafc;
}

.badge-user {
    display: flex;
    align-items: center;
    gap: 4px;
    background: #2b7fff;
    color: white;
    padding: 2.8px 8.8px;
    border-radius: 6px;
    font-size: 12px;
    line-height: 16px;
    height: 21.587px;
}

.badge-user svg {
    width: 12px;
    height: 12px;
}

.notifications-btn {
    position: relative;
    width: 36px;
    height: 36px;
    border: none;
    background: none;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.notifications-btn:hover {
    background: #f1f5f9;
}

.notification-badge {
    position: absolute;
    top: -4px;
    left: 20px;
    background: #fb2c36;
    color: white;
    font-size: 12px;
    line-height: 16px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.user-menu {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 0 12px;
    background: none;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    height: 52px;
}

.user-menu:hover {
    background: #f8fafc;
}

.user-avatar {
    width: 36px;
    height: 36px;
    background: #1e3a8a;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.user-info {
    text-align: left;
}

.user-name {
    font-size: 14px;
    line-height: 20px;
    color: #1c398e;
}

.user-email {
    font-size: 12px;
    line-height: 16px;
    color: #6a7282;
}

/* Main Content */
.dashboard-main {
    margin-top: 84.8px;
    padding: 24px 24px 24px 24px;
}

.main-content {
    max-width: 1231px;
    margin: 0 auto;
}

/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0px 20px 25px -5px rgba(0,0,0,0.1), 0px 8px 10px -6px rgba(0,0,0,0.1);
    margin-bottom: 24px;
}

.hero-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.hero-left {
    flex: 1;
}

.greeting {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #bedbff;
    font-size: 14px;
    line-height: 20px;
    margin-bottom: 8px;
}

.hero-heading {
    font-size: 24px;
    line-height: 32px;
    color: white;
    margin-bottom: 8px;
}

.hero-subtitle {
    font-size: 14px;
    line-height: 20px;
    color: #dbeafe;
    margin-bottom: 16px;
}

.hero-actions {
    display: flex;
    gap: 8px;
}

.btn-browse {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 14px;
    background: white;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    line-height: 20px;
    color: #1c398e;
    cursor: pointer;
    height: 32px;
}

.btn-browse:hover {
    background: #f1f5f9;
}

.btn-new-booking {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 14px;
    background: rgba(25, 60, 184, 0.5);
    border: 0.8px solid #155dfc;
    border-radius: 6px;
    font-size: 14px;
    line-height: 20px;
    color: white;
    cursor: pointer;
    height: 32px;
}

.btn-new-booking:hover {
    background: rgba(25, 60, 184, 0.7);
}

.hero-stats {
    display: flex;
    gap: 16px;
}

.stat-item {
    text-align: center;
}

.stat-value {
    font-size: 24px;
    line-height: 32px;
    color: white;
    margin-bottom: 0;
}

.stat-value.stat-pending {
    color: #ffd230;
}

.stat-value.stat-confirmed {
    color: #7bf1a8;
}

.stat-label {
    font-size: 12px;
    line-height: 16px;
    color: #bedbff;
}

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 16px;
}

/* Card Styles */
.card {
    background: white;
    border: 0.8px solid #e5e7eb;
    border-radius: 12px;
    padding: 24px;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 42px;
}

.card-title-group {
    display: flex;
    align-items: center;
    gap: 8px;
}

.card-title-group h3 {
    font-size: 16px;
    line-height: 24px;
    color: #000;
}

.badge-count {
    background: #f1f5f9;
    color: #1e3a8a;
    font-size: 12px;
    line-height: 16px;
    padding: 2.8px 8.8px;
    border-radius: 6px;
}

.card-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 64px 0;
}

.empty-icon {
    width: 48px;
    height: 48px;
    background: #f3f4f6;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 8px;
}

.card-empty p {
    font-size: 14px;
    line-height: 20px;
    color: #6a7282;
}

/* Bookings Card */
.bookings-card {
    height: fit-content;
}

.btn-expand {
    width: 32px;
    height: 32px;
    background: white;
    border: 0.8px solid #e5e7eb;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.btn-expand:hover {
    background: #f8fafc;
}

.user-info-box {
    background: #f9fafb;
    border: 0.8px solid #f3f4f6;
    border-radius: 8px;
    padding: 12.8px;
    margin-bottom: 24px;
}

.info-label {
    font-size: 12px;
    line-height: 16px;
    color: #6a7282;
    margin-bottom: 4px;
}

.info-value {
    font-size: 14px;
    line-height: 20px;
    color: #1c398e;
    margin-bottom: 8px;
}

.info-bookings {
    font-size: 12px;
    line-height: 16px;
    color: #6a7282;
}

.info-bookings span {
    color: #1c398e;
}

/* Tabs */
.tabs {
    background: #f1f5f9;
    border-radius: 12px;
    padding: 3.5px 3px;
    display: flex;
    margin-bottom: 24px;
}

.tab-btn {
    flex: 1;
    padding: 6px 8.8px;
    border: none;
    background: transparent;
    border-radius: 12px;
    font-size: 12px;
    line-height: 16px;
    color: #000;
    cursor: pointer;
}

.tab-btn.active {
    background: white;
}

.tab-btn:hover:not(.active) {
    background: rgba(255, 255, 255, 0.5);
}

/* Bookings List */
.bookings-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 16px;
    max-height: 370px;
    overflow-y: auto;
}

.booking-item {
    background: #fffbeb;
    border: 0.8px solid #fee685;
    border-radius: 8px;
    padding: 12.8px;
}

.booking-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.badge-pending {
    background: #fe9a00;
    color: white;
    font-size: 12px;
    line-height: 16px;
    padding: 2.8px 8.8px;
    border-radius: 6px;
}

.booking-date {
    font-size: 12px;
    line-height: 16px;
    color: #4a5565;
}

.booking-content {
    display: flex;
    gap: 8px;
    align-items: flex-start;
}

.booking-icon {
    width: 32px;
    height: 32px;
    background: #dbeafe;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.booking-details {
    flex: 1;
}

.booking-title {
    font-size: 14px;
    line-height: 20px;
    color: #1c398e;
    margin-bottom: 4px;
}

.booking-time {
    font-size: 12px;
    line-height: 16px;
    color: #4a5565;
}

.btn-view-all {
    width: 100%;
    padding: 8px;
    background: white;
    border: 0.8px solid #e2e8f0;
    border-radius: 6px;
    font-size: 12px;
    line-height: 16px;
    color: #000;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.btn-view-all:hover {
    background: #f8fafc;
}

/* Responsive */
@media (max-width: 1024px) {
    .content-grid {
        grid-template-columns: 1fr;
    }

    .hero-content {
        flex-direction: column;
        gap: 24px;
    }

    .hero-stats {
        width: 100%;
        justify-content: space-around;
    }
}

@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        height: auto;
        gap: 12px;
    }

    .header-actions {
        width: 100%;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    .user-menu {
        order: -1;
        width: 100%;
        justify-content: space-between;
    }

    .hero-actions {
        flex-direction: column;
        width: 100%;
    }

    .btn-browse,
    .btn-new-booking {
        width: 100%;
        justify-content: center;
    }

    .dashboard-main {
        padding: 16px;
    }
}
</style>

</html>