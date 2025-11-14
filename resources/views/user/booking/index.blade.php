<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - Shared Services Portal</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f8fafc;
            color: #1f2937;
        }

        /* Header Styles */
        .header {
            background-color: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 16px 24px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1280px;
            margin: 0 auto;
        }

        .logo-section {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .logo {
            width: 70px;
            height: 70px;
            flex-shrink: 0;
        }

        .logo-text h1 {
            font-size: 20px;
            color: #1e40af;
            font-weight: 500;
        }

        .logo-text p {
            font-size: 12px;
            color: #6b7280;
        }

        .header-right {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .mobile-menu-btn {
            display: none;
            width: 40px;
            height: 40px;
            border: none;
            background: transparent;
            cursor: pointer;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 4px;
        }

        .mobile-menu-btn span {
            width: 24px;
            height: 2px;
            background-color: #1f2937;
            transition: all 0.3s;
        }

        .mobile-menu-btn.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .mobile-menu-btn.active span:nth-child(2) {
            opacity: 0;
        }

        .mobile-menu-btn.active span:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -6px);
        }

        .mobile-menu {
            display: none;
            position: fixed;
            top: 85px;
            left: 0;
            right: 0;
            background-color: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 16px;
            flex-direction: column;
            gap: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .mobile-menu.active {
            display: flex;
        }

        .btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            background-color: white;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.2s;
            white-space: nowrap;
        }

        .btn:hover {
            background-color: #f8fafc;
        }

        .user-badge {
            background-color: #2563eb;
            color: white;
            padding: 10px 25px 10px 25px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .user-profile:hover {
            background-color: #f8fafc;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            background-color: #1e3a8a;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            flex-shrink: 0;
        }

        .user-info p:first-child {
            font-size: 14px;
            color: #1e40af;
            font-weight: 500;
        }

        .user-info p:last-child {
            font-size: 12px;
            color: #6b7280;
        }

        .notification {
            position: relative;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background-color: #ef4444;
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 500;
        }

        /* Main Content */
        .main-content {
            max-width: 1232px;
            margin: 0 auto;
            padding: 24px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            gap: 16px;
        }

        .page-header h2 {
            font-size: 24px;
            color: #101828;
            margin-bottom: 4px;
        }

        .page-header p {
            font-size: 14px;
            color: #6a7282;
        }

        /* Tabs */
        .tabs {
            background-color: #f1f5f9;
            border-radius: 12px;
            padding: 3px;
            display: flex;
            gap: 3px;
            margin-bottom: 24px;
        }

        .tab {
            flex: 1;
            padding: 8px 12px;
            border: none;
            background: transparent;
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .tab.active {
            background-color: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .tab-badge {
            background-color: #f1f5f9;
            color: #1e40af;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
        }

        /* Search Card */
        .search-card {
            background-color: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .search-filters {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .search-input {
            flex: 1;
            min-width: 200px;
            position: relative;
        }

        .search-input input {
            width: 100%;
            height: 36px;
            background-color: #f8fafc;
            border: 1px solid transparent;
            border-radius: 6px;
            padding: 0 12px 0 36px;
            font-size: 14px;
            outline: none;
            transition: all 0.2s;
        }

        .search-input input:focus {
            background-color: white;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 10px;
            width: 16px;
            height: 16px;
            color: #9ca3af;
        }

        .filter-select {
            flex: 1;
            min-width: 150px;
        }

        select {
            width: 100%;
            height: 36px;
            background-color: #f8fafc;
            border: 1px solid transparent;
            border-radius: 6px;
            padding: 0 32px 0 12px;
            font-size: 14px;
            cursor: pointer;
            outline: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3E%3Cpath fill='%23666' d='M4 6l4 4 4-4z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 8px center;
            transition: all 0.2s;
        }

        select:focus {
            background-color: white;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Table */
        .table-card {
            background-color: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            border-bottom: 1px solid #e2e8f0;
        }

        th {
            padding: 12px 8px;
            text-align: left;
            font-size: 14px;
            font-weight: 500;
            color: #000;
        }

        th:last-child {
            text-align: right;
        }

        tbody tr {
            border-bottom: 1px solid #e2e8f0;
            transition: background-color 0.2s;
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        tbody tr:hover {
            background-color: #f8fafc;
        }

        td {
            padding: 12px 8px;
            font-size: 14px;
        }

        .facility-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .facility-icon {
            width: 40px;
            height: 40px;
            background-color: #dbeafe;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .facility-info h4 {
            font-size: 14px;
            color: #101828;
            margin-bottom: 2px;
        }

        .facility-info p {
            font-size: 12px;
            color: #6a7282;
        }

        .date-cell h4 {
            font-size: 14px;
            color: #101828;
            margin-bottom: 2px;
        }

        .date-cell p {
            font-size: 12px;
            color: #6a7282;
        }

        .purpose-cell {
            color: #4a5565;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background-color: #fef3c7;
            border: 1px solid #fde68a;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
            color: #92400e;
        }

        .status-icon {
            width: 12px;
            height: 12px;
        }

        .actions-cell {
            text-align: right;
            display: flex;
            justify-content: flex-end;
            gap: 4px;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            border: none;
            background: transparent;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.2s;
        }

        .action-btn:hover {
            background-color: #f1f5f9;
        }

        .action-btn.cancel:hover {
            background-color: #fee2e2;
        }

        .icon {
            width: 16px;
            height: 16px;
        }

        .empty-state {
            padding: 40px;
            text-align: center;
            color: #6b7280;
        }

        /* Mobile Card View */
        .booking-card {
            display: none;
            background-color: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 12px;
            transition: box-shadow 0.2s;
        }

        .booking-card:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .booking-card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .booking-card-body {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 12px;
        }

        .booking-card-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .booking-card-label {
            font-size: 12px;
            color: #6b7280;
        }

        .booking-card-value {
            font-size: 14px;
            color: #1f2937;
            font-weight: 500;
        }

        .booking-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 12px;
            border-top: 1px solid #e5e7eb;
        }

        .mobile-cards {
            display: none;
        }

        /* Toast Notification */
        .toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background-color: #1f2937;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: none;
            align-items: center;
            gap: 12px;
            z-index: 1000;
            animation: slideIn 0.3s ease-out;
        }

        .toast.show {
            display: flex;
        }

        .toast.success {
            background-color: #059669;
        }

        .toast.error {
            background-color: #dc2626;
        }

        @keyframes slideIn {
            from {
                transform: translateY(100px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Loading Spinner */
        .spinner {
            border: 3px solid #f3f4f6;
            border-top: 3px solid #2563eb;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive Styles */
        @media (max-width: 1024px) {
            .header-content {
                padding: 0;
            }

            .main-content {
                padding: 16px;
            }

            .search-filters {
                flex-direction: column;
            }

            .search-input,
            .filter-select {
                width: 100%;
                min-width: 100%;
            }
        }

        @media (max-width: 768px) {
            .logo-text h1 {
                font-size: 16px;
            }

            .logo-text p {
                display: none;
            }

            .logo {
                width: 36px;
                height: 36px;
            }

            .mobile-menu-btn {
                display: flex;
            }

            .header-right {
                display: none;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .page-header h2 {
                font-size: 20px;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            /* Hide table on mobile, show cards */
            table {
                display: none;
            }

            .mobile-cards {
                display: block;
            }

            .booking-card {
                display: block;
            }

            .search-card {
                padding: 16px;
            }

            .tabs {
                flex-direction: row;
            }

            .tab {
                font-size: 12px;
                padding: 6px 8px;
            }

            .tab-badge {
                font-size: 10px;
            }
        }

        @media (max-width: 480px) {
            .header {
                padding: 12px 16px;
            }

            .main-content {
                padding: 12px;
            }

            .search-card {
                padding: 12px;
            }

            .page-header h2 {
                font-size: 18px;
            }

            .page-header p {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <!-- Toast Notification -->
    <div id="toast" class="toast">
        <span id="toastMessage"></span>
    </div>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="logo-section">
                <a href="{{ route('user.dashboard', ['start' => 'method']) }}"><img src="{{ asset('images/enclogo.png') }}" alt="ENC Logo" class="logo"></a>
                <div class="logo-text">
                    <h1>Shared Services Portal</h1>
                    <p>One-Stop Booking Platform</p>
                </div>
            </div>

            <!-- Desktop Navigation -->
            <div class="header-right">
                <button class="btn">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    My Bookings
                </button>
                
                <span class="user-badge"> User</span>

                <div class="user-profile" onclick="toggleUserMenu()">
                    <div class="user-avatar">CR</div>
                    <div class="user-info">
                        <p>Charles Ramos</p>
                        <p>user.charles@enc.gov</p>
                    </div>
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>

                <div class="notification" onclick="showNotifications()">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="notification-badge">2</span>
                </div>
            </div>

            <!-- Mobile Menu Button -->
            <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="mobile-menu">
            <div class="user-profile">
                <div class="user-avatar">CR</div>
                <div class="user-info">
                    <p>Charles Ramos</p>
                    <p>user.charles@enc.gov</p>
                </div>
            </div>
            <button class="btn">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                My Bookings
            </button>
            <button class="btn" onclick="showNotifications()">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                Notifications (2)
            </button>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2>My Bookings</h2>
                <p>View and manage all your bookings</p>
            </div>
            <button class="btn" onclick="exportCalendar()">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export Calendar
            </button>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <button class="tab active" onclick="switchTab(this, 'upcoming')">
                Upcoming
                <span class="tab-badge" id="upcomingCount">2</span>
            </button>
            <button class="tab" onclick="switchTab(this, 'past')">Past</button>
            <button class="tab" onclick="switchTab(this, 'cancelled')">Cancelled</button>
        </div>

        <!-- Search and Filters -->
        <div class="search-card">
            <div class="search-filters">
                <div class="search-input">
                    <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" id="searchInput" placeholder="Search by purpose, room, or facility..." oninput="filterBookings()">
                </div>
                <div class="filter-select">
                    <select id="facilityFilter" onchange="filterBookings()">
                        <option value="">All Facilities</option>
                        <option value="Meeting Room">Meeting Room</option>
                        <option value="Conference Room">Conference Room</option>
                    </select>
                </div>
                <div class="filter-select">
                    <select id="statusFilter" onchange="filterBookings()">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Bookings Table (Desktop) -->
        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Facility</th>
                        <th>Date & Time</th>
                        <th>Purpose</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="bookingsTable">
                    <!-- Table rows will be inserted here by JavaScript -->
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div id="mobileCards" class="mobile-cards">
            <!-- Cards will be inserted here by JavaScript -->
        </div>
    </main>

    <script>
        // Sample bookings data
        let bookings = [
            {
                id: 1,
                facility: 'Meeting Room',
                facilityType: 'Meeting Room',
                date: 'Sat, Nov 29, 2025',
                time: '09:00 - 10:00',
                purpose: 'Victory Group',
                status: 'pending'
            },
            {
                id: 2,
                facility: 'Meeting Room',
                facilityType: 'Meeting Room',
                date: 'Sat, Nov 29, 2025',
                time: '10:30 - 11:00',
                purpose: 'Meeting',
                status: 'pending'
            }
        ];

        let currentTab = 'upcoming';
        let filteredBookings = [...bookings];

        // Initialize the page
        function init() {
            renderBookings();
            updateBookingCount();
        }

        // Toggle mobile menu
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            const btn = document.querySelector('.mobile-menu-btn');
            menu.classList.toggle('active');
            btn.classList.toggle('active');
        }

        // Tab switching
        function switchTab(element, tab) {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            element.classList.add('active');
            currentTab = tab;
            filterBookings();
            showToast(`Switched to ${tab} bookings`, 'success');
        }

        // Filter bookings
        function filterBookings() {
            const searchQuery = document.getElementById('searchInput').value.toLowerCase();
            const facilityFilter = document.getElementById('facilityFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;

            filteredBookings = bookings.filter(booking => {
                const matchesSearch = !searchQuery || 
                    booking.purpose.toLowerCase().includes(searchQuery) ||
                    booking.facility.toLowerCase().includes(searchQuery);
                
                const matchesFacility = !facilityFilter || booking.facility === facilityFilter;
                const matchesStatus = !statusFilter || booking.status === statusFilter;
                
                return matchesSearch && matchesFacility && matchesStatus;
            });

            renderBookings();
        }

        // Render bookings in both table and card view
        function renderBookings() {
            const tableBody = document.getElementById('bookingsTable');
            const mobileCards = document.getElementById('mobileCards');

            // Clear existing content
            tableBody.innerHTML = '';
            mobileCards.innerHTML = '';

            if (filteredBookings.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="5" class="empty-state">No bookings found</td></tr>';
                mobileCards.innerHTML = '<div class="empty-state">No bookings found</div>';
                return;
            }

            filteredBookings.forEach(booking => {
                // Render table row
                const row = document.createElement('tr');
                row.setAttribute('data-id', booking.id);
                row.innerHTML = `
                    <td>
                        <div class="facility-cell">
                            <div class="facility-icon">
                                <svg width="20" height="20" fill="none" stroke="#2563eb" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="facility-info">
                                <h4>${booking.facility}</h4>
                                <p>${booking.facilityType}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="date-cell">
                            <h4>${booking.date}</h4>
                            <p>${booking.time}</p>
                        </div>
                    </td>
                    <td class="purpose-cell">${booking.purpose}</td>
                    <td>
                        <span class="status-badge">
                            <svg class="status-icon" fill="none" stroke="#92400e" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke-width="2"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2"/>
                            </svg>
                            ${booking.status}
                        </span>
                    </td>
                    <td>
                        <div class="actions-cell">
                            <button class="action-btn" onclick="viewBooking(${booking.id})" title="View">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                            <button class="action-btn cancel" onclick="cancelBooking(${booking.id})" title="Cancel">
                                <svg class="icon" fill="none" stroke="#dc2626" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                `;
                tableBody.appendChild(row);

                // Render mobile card
                const card = document.createElement('div');
                card.className = 'booking-card';
                card.setAttribute('data-id', booking.id);
                card.innerHTML = `
                    <div class="booking-card-header">
                        <div class="facility-icon">
                            <svg width="20" height="20" fill="none" stroke="#2563eb" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="facility-info">
                            <h4>${booking.facility}</h4>
                            <p>${booking.facilityType}</p>
                        </div>
                    </div>
                    <div class="booking-card-body">
                        <div class="booking-card-row">
                            <span class="booking-card-label">Date</span>
                            <span class="booking-card-value">${booking.date}</span>
                        </div>
                        <div class="booking-card-row">
                            <span class="booking-card-label">Time</span>
                            <span class="booking-card-value">${booking.time}</span>
                        </div>
                        <div class="booking-card-row">
                            <span class="booking-card-label">Purpose</span>
                            <span class="booking-card-value">${booking.purpose}</span>
                        </div>
                    </div>
                    <div class="booking-card-footer">
                        <span class="status-badge">
                            <svg class="status-icon" fill="none" stroke="#92400e" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke-width="2"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2"/>
                            </svg>
                            ${booking.status}
                        </span>
                        <div class="actions-cell">
                            <button class="action-btn" onclick="viewBooking(${booking.id})" title="View">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                            <button class="action-btn cancel" onclick="cancelBooking(${booking.id})" title="Cancel">
                                <svg class="icon" fill="none" stroke="#dc2626" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                `;
                mobileCards.appendChild(card);
            });
        }

        // View booking
        function viewBooking(id) {
            const booking = bookings.find(b => b.id === id);
            if (booking) {
                showToast(`Viewing booking: ${booking.purpose}`, 'success');
                // Here you would typically open a modal or navigate to details page
                // For Laravel: window.location.href = `/bookings/${id}`;
            }
        }

        // Cancel booking
        function cancelBooking(id) {
            if (confirm('Are you sure you want to cancel this booking?')) {
                const bookingIndex = bookings.findIndex(b => b.id === id);
                if (bookingIndex !== -1) {
                    bookings[bookingIndex].status = 'cancelled';
                    filterBookings();
                    updateBookingCount();
                    showToast('Booking cancelled successfully', 'success');
                    
                    // For Laravel backend:
                    // fetch(`/api/bookings/${id}/cancel`, {
                    //     method: 'POST',
                    //     headers: {
                    //         'Content-Type': 'application/json',
                    //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    //     }
                    // })
                    // .then(response => response.json())
                    // .then(data => {
                    //     showToast('Booking cancelled successfully', 'success');
                    //     filterBookings();
                    // })
                    // .catch(error => {
                    //     showToast('Failed to cancel booking', 'error');
                    // });
                }
            }
        }

        // Export calendar
        function exportCalendar() {
            showToast('Exporting calendar...', 'success');
            // For Laravel: window.location.href = '/api/bookings/export';
        }

        // Show toast notification
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            
            toastMessage.textContent = message;
            toast.className = `toast show ${type}`;
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        // Update booking count
        function updateBookingCount() {
            const upcomingCount = bookings.filter(b => b.status === 'pending').length;
            document.getElementById('upcomingCount').textContent = upcomingCount;
        }

        // Toggle user menu
        function toggleUserMenu() {
            showToast('User menu clicked', 'success');
        }

        // Show notifications
        function showNotifications() {
            showToast('You have 2 new notifications', 'success');
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', init);
    </script>
</body>
</html>
