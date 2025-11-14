<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approvals Queue - Shared Services Portal</title>
    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #F5F6F8;
            color: #1F2937;
            line-height: 1.5;
        }

        button {
            cursor: pointer;
            border: none;
            background: none;
            font-family: inherit;
        }

        /* Header */
        .header {
            background: white;
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #E5E7EB;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo {
            width: 40px;
            height: 40px;
            flex-shrink: 0;
        }

        .header-title h1 {
            font-size: 16px;
            font-weight: 600;
            color: #1F2937;
        }

        .header-title p {
            font-size: 12px;
            color: #6B7280;
        }

        .header-nav {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .nav-btn.approvals {
            background: #7C3AED;
            color: white;
        }

        .nav-btn.approvals:hover {
            background: #6D28D9;
        }

        .nav-btn.secondary {
            background: white;
            color: #1F2937;
            border: 1px solid #E5E7EB;
        }

        .nav-btn.secondary:hover {
            background: #F9FAFB;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .notification-icon {
            position: relative;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6B7280;
            cursor: pointer;
        }

        .notification-badge {
            position: absolute;
            top: 2px;
            right: 2px;
            background: #EF4444;
            color: white;
            font-size: 11px;
            font-weight: 600;
            min-width: 20px;
            height: 20px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 6px;
        }

        .admin-btn {
            background: #EF4444;
            color: white;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            border-radius: 8px;
            transition: background-color 0.2s;
            cursor: pointer;
        }

        .user-profile:hover {
            background-color: #F9FAFB;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #1E3A8A;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-size: 14px;
            font-weight: 500;
            color: #1F2937;
        }

        .user-email {
            font-size: 12px;
            color: #6B7280;
        }

        /* Main Container */
        .main-container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 24px;
        }

        /* Page Header */
        .page-header {
            margin-bottom: 24px;
        }

        .page-header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .page-header h2 {
            font-size: 24px;
            font-weight: 600;
            color: #1F2937;
        }

        .page-header p {
            font-size: 14px;
            color: #6B7280;
        }

        .header-actions {
            display: flex;
            gap: 12px;
        }

        .action-btn {
            padding: 8px 16px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            color: #1F2937;
            background: white;
            transition: background-color 0.2s;
        }

        .action-btn:hover {
            background-color: #F9FAFB;
        }

        /* Tabs */
        .tabs-container {
            background: white;
            border-radius: 12px 12px 0 0;
            border-bottom: 1px solid #E5E7EB;
            padding: 0 24px;
        }

        .tabs {
            display: flex;
            gap: 32px;
        }

        .tab {
            padding: 16px 0;
            font-size: 14px;
            font-weight: 500;
            color: #6B7280;
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
            cursor: pointer;
        }

        .tab.active {
            color: #1F2937;
            border-bottom-color: #7C3AED;
        }

        .tab:hover {
            color: #1F2937;
        }

        /* Content Card */
        .content-card {
            background: white;
            border-radius: 0 0 12px 12px;
            padding: 24px;
        }

        /* Filters */
        .filters {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .search-box {
            flex: 1;
            min-width: 300px;
            position: relative;
        }

        .search-box input {
            width: 100%;
            padding: 10px 12px 10px 40px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
        }

        .search-box input::placeholder {
            color: #9CA3AF;
        }

        .search-box input:focus {
            outline: none;
            border-color: #7C3AED;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
        }

        .filter-select {
            padding: 10px 36px 10px 12px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            font-size: 14px;
            color: #1F2937;
            background: white;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg width='16' height='16' viewBox='0 0 16 16' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M4 6l4 4 4-4' stroke='%236B7280' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
        }

        .filter-select:focus {
            outline: none;
            border-color: #7C3AED;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
        }

        /* Table Section */
        .table-section {
            margin-top: 24px;
        }

        .table-header {
            font-size: 16px;
            font-weight: 600;
            color: #1F2937;
            margin-bottom: 16px;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            text-align: left;
            padding: 12px;
            font-size: 13px;
            font-weight: 600;
            color: #6B7280;
            border-bottom: 1px solid #E5E7EB;
            background: #F9FAFB;
        }

        tbody td {
            padding: 16px 12px;
            font-size: 14px;
            color: #1F2937;
            border-bottom: 1px solid #F3F4F6;
        }

        tbody tr {
            transition: background-color 0.2s;
        }

        tbody tr:hover {
            background-color: #F9FAFB;
        }

        /* Table Cell Content */
        .date-cell {
            min-width: 100px;
        }

        .date-text {
            font-size: 13px;
            color: #1F2937;
            margin-bottom: 2px;
        }

        .time-text {
            font-size: 12px;
            color: #6B7280;
        }

        .when-cell {
            min-width: 140px;
        }

        .when-icon {
            color: #9CA3AF;
            margin-right: 4px;
        }

        .facility-cell {
            min-width: 160px;
        }

        .facility-name {
            font-size: 14px;
            color: #1F2937;
            margin-bottom: 2px;
        }

        .building-info {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            color: #6B7280;
        }

        .requester-cell {
            min-width: 140px;
        }

        .requester-name {
            font-size: 14px;
            color: #1F2937;
            margin-bottom: 2px;
        }

        .department {
            font-size: 12px;
            color: #6B7280;
        }

        .purpose-cell {
            min-width: 200px;
        }

        .priority-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .priority-badge.high {
            background: #FEE2E2;
            color: #DC2626;
        }

        .priority-badge.medium {
            background: #FEF3C7;
            color: #D97706;
        }

        .priority-badge.low {
            background: #D1FAE5;
            color: #059669;
        }

        /* Actions */
        .actions-cell {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .action-icon-btn {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: background-color 0.2s;
        }

        .action-icon-btn.approve {
            background: #D1FAE5;
            color: #059669;
        }

        .action-icon-btn.approve:hover {
            background: #A7F3D0;
        }

        .action-icon-btn.reject {
            background: #FEE2E2;
            color: #DC2626;
        }

        .action-icon-btn.reject:hover {
            background: #FECACA;
        }

        .action-icon-btn.info {
            color: #6B7280;
        }

        .action-icon-btn.info:hover {
            background: #F3F4F6;
        }

        .action-icon-btn.more {
            color: #6B7280;
        }

        .action-icon-btn.more:hover {
            background: #F3F4F6;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .header-nav {
                display: none;
            }

            .table-container {
                overflow-x: auto;
            }
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 12px;
            }

            .header-right {
                width: 100%;
                justify-content: space-between;
            }

            .filters {
                flex-direction: column;
            }

            .search-box {
                min-width: 100%;
            }

            .filter-select {
                width: 100%;
            }
        }

        /* Dropdown Menu */
        .dropdown {
            position: relative;
        }

        .dropdown-menu {
            position: absolute;
            right: 0;
            top: 100%;
            margin-top: 4px;
            background: white;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            min-width: 160px;
            z-index: 10;
            display: none;
        }

        .dropdown-menu.active {
            display: block;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 12px;
            font-size: 14px;
            color: #1F2937;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .dropdown-item:first-child {
            border-radius: 8px 8px 0 0;
        }

        .dropdown-item:last-child {
            border-radius: 0 0 8px 8px;
        }

        .dropdown-item:hover {
            background-color: #F3F4F6;
        }

        .dropdown-divider {
            height: 1px;
            background: #E5E7EB;
            margin: 4px 0;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-left">
            <div class="logo">
                <img src="{{ asset('images/enclogo.png') }}">
            </div>
            <div class="header-title">
                <h1>Shared Services Portal</h1>
                <p>One-Stop Booking Platform</p>
            </div>
        </div>
        
        <div class="header-nav">
            <button class="nav-btn approvals">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path d="M3 8l3 3 7-7" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2 2h12v12H2z" stroke="white" stroke-width="1.5" fill="none"/>
                </svg>
                Approvals
            </button>
            <button class="nav-btn secondary">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <circle cx="8" cy="8" r="6" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M8 5v3l2 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                Admin Hub
            </button>
            <button class="nav-btn secondary">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <rect x="2" y="3" width="12" height="10" rx="1" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M5 1v4M11 1v4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                My Bookings
            </button>
        </div>

        <div class="header-right">
            <div class="notification-icon">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M15 6.5a5 5 0 10-10 0c0 5-2 6.5-2 6.5h14s-2-1.5-2-6.5zM11.73 16.5a2 2 0 01-3.46 0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="notification-badge">2</span>
            </div>
            <button class="admin-btn">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <circle cx="8" cy="8" r="6" stroke="white" stroke-width="1.5"/>
                </svg>
                Admin
            </button>
            <div class="user-profile">
                <div class="user-avatar">CR</div>
                <div class="user-info">
                    <span class="user-name">Charles Ramos</span>
                    <span class="user-email">charles@enc.gov</span>
                </div>
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="main-container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-header-top">
                <div>
                    <h2>Approvals Queue</h2>
                    <p>Review and manage booking requests</p>
                </div>
                <div class="header-actions">
                    <button class="action-btn" id="refreshBtn">Refresh</button>
                    <button class="action-btn" id="debugBtn">Debug</button>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs-container">
            <div class="tabs">
                <div class="tab active" data-tab="pending">Pending</div>
                <div class="tab" data-tab="approved">Approved</div>
                <div class="tab" data-tab="rejected">Rejected</div>
            </div>
        </div>

        <!-- Content Card -->
        <div class="content-card">
            <!-- Filters -->
            <div class="filters">
                <div class="search-box">
                    <svg class="search-icon" width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <circle cx="7" cy="7" r="5" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M11 11l3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <input type="text" id="searchInput" placeholder="Search by requester, facility, or purpose...">
                </div>
                <select class="filter-select" id="facilityFilter">
                    <option value="">Meeting Rooms</option>
                    <option value="conference">Conference Room</option>
                    <option value="training">Training Hall</option>
                    <option value="meeting">Meeting Room</option>
                </select>
                <select class="filter-select" id="priorityFilter">
                    <option value="">All Priority</option>
                    <option value="high">High</option>
                    <option value="medium">Medium</option>
                    <option value="low">Low</option>
                </select>
                <select class="filter-select" id="departmentFilter">
                    <option value="">All Departments</option>
                    <option value="it">IT Department</option>
                    <option value="hr">HR Department</option>
                    <option value="finance">Finance Department</option>
                </select>
            </div>

            <!-- Table -->
            <div class="table-section">
                <div class="table-header">Pending Requests</div>
                <div class="table-container">
                    <table>
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
                        <tbody id="requestsTable">
                            <tr>
                                <td class="date-cell">
                                    <div class="date-text">Nov 10,</div>
                                    <div class="time-text">02:30 PM</div>
                                </td>
                                <td class="when-cell">
                                    <div class="date-text">2025-11-18</div>
                                    <div class="time-text">
                                        <svg class="when-icon" width="12" height="12" viewBox="0 0 12 12" fill="none" style="display: inline; vertical-align: middle;">
                                            <rect x="1" y="2" width="10" height="8" rx="1" stroke="currentColor" stroke-width="1"/>
                                        </svg>
                                        14:00-16:00
                                    </div>
                                </td>
                                <td class="facility-cell">
                                    <div class="facility-name">Conference Room</div>
                                    <div class="building-info">
                                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                                            <path d="M2 10h8M3 10V3l3-2 3 2v7" stroke="currentColor" stroke-width="1"/>
                                        </svg>
                                        Building A
                                    </div>
                                </td>
                                <td class="requester-cell">
                                    <div class="requester-name">Charles Ramos</div>
                                    <div class="department">IT Department</div>
                                </td>
                                <td class="purpose-cell">Quarterly Team Review Meeting</td>
                                <td>
                                    <span class="priority-badge high">high</span>
                                </td>
                                <td>
                                    <div class="actions-cell">
                                        <button class="action-icon-btn approve" title="Approve">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                <path d="M3 8l3 3 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                        <button class="action-icon-btn reject" title="Reject">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                <path d="M4 4l8 8M12 4l-8 8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                            </svg>
                                        </button>
                                        <button class="action-icon-btn info" title="More Info">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                <circle cx="8" cy="8" r="6" stroke="currentColor" stroke-width="1.5"/>
                                                <path d="M8 8V5M8 10.5v.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                            </svg>
                                        </button>
                                        <div class="dropdown">
                                            <button class="action-icon-btn more" title="More Actions">
                                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                    <path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </button>
                                            <div class="dropdown-menu">
                                                <div class="dropdown-item">View Details</div>
                                                <div class="dropdown-item">Edit Request</div>
                                                <div class="dropdown-divider"></div>
                                                <div class="dropdown-item">Contact Requester</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="date-cell">
                                    <div class="date-text">Nov 9,</div>
                                    <div class="time-text">09:15 AM</div>
                                </td>
                                <td class="when-cell">
                                    <div class="date-text">2025-11-20</div>
                                    <div class="time-text">
                                        <svg class="when-icon" width="12" height="12" viewBox="0 0 12 12" fill="none" style="display: inline; vertical-align: middle;">
                                            <rect x="1" y="2" width="10" height="8" rx="1" stroke="currentColor" stroke-width="1"/>
                                        </svg>
                                        09:00-12:00
                                    </div>
                                </td>
                                <td class="facility-cell">
                                    <div class="facility-name">Training Hall B</div>
                                    <div class="building-info">
                                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                                            <path d="M2 10h8M3 10V3l3-2 3 2v7" stroke="currentColor" stroke-width="1"/>
                                        </svg>
                                        Building B
                                    </div>
                                </td>
                                <td class="requester-cell">
                                    <div class="requester-name">Maria Santos</div>
                                    <div class="department">HR Department</div>
                                </td>
                                <td class="purpose-cell">New Employee Orientation</td>
                                <td>
                                    <span class="priority-badge medium">medium</span>
                                </td>
                                <td>
                                    <div class="actions-cell">
                                        <button class="action-icon-btn approve" title="Approve">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                <path d="M3 8l3 3 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                        <button class="action-icon-btn reject" title="Reject">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                <path d="M4 4l8 8M12 4l-8 8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                            </svg>
                                        </button>
                                        <button class="action-icon-btn info" title="More Info">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                <circle cx="8" cy="8" r="6" stroke="currentColor" stroke-width="1.5"/>
                                                <path d="M8 8V5M8 10.5v.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                            </svg>
                                        </button>
                                        <div class="dropdown">
                                            <button class="action-icon-btn more" title="More Actions">
                                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                    <path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </button>
                                            <div class="dropdown-menu">
                                                <div class="dropdown-item">View Details</div>
                                                <div class="dropdown-item">Edit Request</div>
                                                <div class="dropdown-divider"></div>
                                                <div class="dropdown-item">Contact Requester</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="date-cell">
                                    <div class="date-text">Nov 11,</div>
                                    <div class="time-text">04:00 PM</div>
                                </td>
                                <td class="when-cell">
                                    <div class="date-text">2025-11-15</div>
                                    <div class="time-text">
                                        <svg class="when-icon" width="12" height="12" viewBox="0 0 12 12" fill="none" style="display: inline; vertical-align: middle;">
                                            <rect x="1" y="2" width="10" height="8" rx="1" stroke="currentColor" stroke-width="1"/>
                                        </svg>
                                        15:00-17:00
                                    </div>
                                </td>
                                <td class="facility-cell">
                                    <div class="facility-name">Meeting Room C</div>
                                    <div class="building-info">
                                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                                            <path d="M2 10h8M3 10V3l3-2 3 2v7" stroke="currentColor" stroke-width="1"/>
                                        </svg>
                                        Building A
                                    </div>
                                </td>
                                <td class="requester-cell">
                                    <div class="requester-name">John Dela Cruz</div>
                                    <div class="department">Finance Department</div>
                                </td>
                                <td class="purpose-cell">Budget Planning Session</td>
                                <td>
                                    <span class="priority-badge low">low</span>
                                </td>
                                <td>
                                    <div class="actions-cell">
                                        <button class="action-icon-btn approve" title="Approve">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                <path d="M3 8l3 3 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                        <button class="action-icon-btn reject" title="Reject">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                <path d="M4 4l8 8M12 4l-8 8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                            </svg>
                                        </button>
                                        <button class="action-icon-btn info" title="More Info">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                <circle cx="8" cy="8" r="6" stroke="currentColor" stroke-width="1.5"/>
                                                <path d="M8 8V5M8 10.5v.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                            </svg>
                                        </button>
                                        <div class="dropdown">
                                            <button class="action-icon-btn more" title="More Actions">
                                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                    <path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </button>
                                            <div class="dropdown-menu">
                                                <div class="dropdown-item">View Details</div>
                                                <div class="dropdown-item">Edit Request</div>
                                                <div class="dropdown-divider"></div>
                                                <div class="dropdown-item">Contact Requester</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tab Switching
        const tabs = document.querySelectorAll('.tab');
        const tableHeader = document.querySelector('.table-header');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                
                const tabType = tab.getAttribute('data-tab');
                const headerText = tabType.charAt(0).toUpperCase() + tabType.slice(1) + ' Requests';
                tableHeader.textContent = headerText;

                // Here you would typically load different data based on the tab
                console.log('Switched to:', tabType);
            });
        });

        // Dropdown Menu Toggle
        const dropdowns = document.querySelectorAll('.dropdown');
        
        dropdowns.forEach(dropdown => {
            const button = dropdown.querySelector('.action-icon-btn.more');
            const menu = dropdown.querySelector('.dropdown-menu');
            
            button.addEventListener('click', (e) => {
                e.stopPropagation();
                
                // Close all other dropdowns
                document.querySelectorAll('.dropdown-menu').forEach(m => {
                    if (m !== menu) m.classList.remove('active');
                });
                
                menu.classList.toggle('active');
            });
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', () => {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.remove('active');
            });
        });

        // Approve Button
        document.querySelectorAll('.action-icon-btn.approve').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const row = btn.closest('tr');
                const requester = row.querySelector('.requester-name').textContent;
                const facility = row.querySelector('.facility-name').textContent;
                
                if (confirm(`Approve booking request for ${facility} by ${requester}?`)) {
                    row.style.backgroundColor = '#D1FAE5';
                    setTimeout(() => {
                        row.remove();
                    }, 1000);
                }
            });
        });

        // Reject Button
        document.querySelectorAll('.action-icon-btn.reject').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const row = btn.closest('tr');
                const requester = row.querySelector('.requester-name').textContent;
                const facility = row.querySelector('.facility-name').textContent;
                
                if (confirm(`Reject booking request for ${facility} by ${requester}?`)) {
                    row.style.backgroundColor = '#FEE2E2';
                    setTimeout(() => {
                        row.remove();
                    }, 1000);
                }
            });
        });

        // Info Button
        document.querySelectorAll('.action-icon-btn.info').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const row = btn.closest('tr');
                const requester = row.querySelector('.requester-name').textContent;
                const facility = row.querySelector('.facility-name').textContent;
                const purpose = row.querySelector('.purpose-cell').textContent;
                
                alert(`Booking Details:\n\nRequester: ${requester}\nFacility: ${facility}\nPurpose: ${purpose}`);
            });
        });

        // Search Functionality
        const searchInput = document.getElementById('searchInput');
        const tableRows = document.querySelectorAll('#requestsTable tr');

        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Filter Functionality
        const facilityFilter = document.getElementById('facilityFilter');
        const priorityFilter = document.getElementById('priorityFilter');
        const departmentFilter = document.getElementById('departmentFilter');

        function applyFilters() {
            const facility = facilityFilter.value.toLowerCase();
            const priority = priorityFilter.value.toLowerCase();
            const department = departmentFilter.value.toLowerCase();
            
            tableRows.forEach(row => {
                const rowFacility = row.querySelector('.facility-name')?.textContent.toLowerCase() || '';
                const rowPriority = row.querySelector('.priority-badge')?.textContent.toLowerCase() || '';
                const rowDepartment = row.querySelector('.department')?.textContent.toLowerCase() || '';
                
                const facilityMatch = !facility || rowFacility.includes(facility);
                const priorityMatch = !priority || rowPriority.includes(priority);
                const departmentMatch = !department || rowDepartment.includes(department);
                
                if (facilityMatch && priorityMatch && departmentMatch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        facilityFilter.addEventListener('change', applyFilters);
        priorityFilter.addEventListener('change', applyFilters);
        departmentFilter.addEventListener('change', applyFilters);

        // Refresh Button
        document.getElementById('refreshBtn').addEventListener('click', () => {
            window.location.reload();
        });

        // Debug Button
        document.getElementById('debugBtn').addEventListener('click', () => {
            console.log('Debug mode activated');
            console.log('Total requests:', tableRows.length);
            console.log('Applied filters:', {
                facility: facilityFilter.value,
                priority: priorityFilter.value,
                department: departmentFilter.value
            });
        });

        // Dropdown menu item actions
        document.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', (e) => {
                const action = item.textContent;
                const dropdown = item.closest('.dropdown');
                const row = dropdown.closest('tr');
                const requester = row.querySelector('.requester-name').textContent;
                
                console.log(`Action: ${action} for ${requester}`);
                
                // Close dropdown
                dropdown.querySelector('.dropdown-menu').classList.remove('active');
            });
        });
    </script>
</body>
</html>
