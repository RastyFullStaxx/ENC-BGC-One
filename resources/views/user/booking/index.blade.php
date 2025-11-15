@extends('layouts.app')

@section('title', 'My Bookings')

@push('styles')
    @vite(['resources/css/wizard/base.css'])
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
@endpush

@section('content')
    <!-- Include Dashboard Navbar -->
    @include('partials.dashboard-navbar', [
        'currentStep' => 0,
        'steps' => [],
        'bookingsCount' => 2,
        'notificationsCount' => 2,
        'userName' => auth()->user()->name ?? 'User',
        'userEmail' => auth()->user()->email ?? 'user@ministry.gov',
        'userRole' => auth()->user()->role ?? 'staff',
        'brand' => 'ONE Services'
    ])

    <!-- Toast Notification -->
    <div id="toast" class="toast">
        <span id="toastMessage"></span>
    </div>

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

</html>