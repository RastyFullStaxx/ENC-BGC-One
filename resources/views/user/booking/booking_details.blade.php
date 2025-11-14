<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shared Services Portal - One-Stop Booking Platform</title>
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

        .header-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .my-bookings-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border: 1px solid #E5E7EB;
            border-radius: 6px;
            font-size: 14px;
            color: #1F2937;
            transition: background-color 0.2s;
        }

        .my-bookings-btn:hover {
            background-color: #F9FAFB;
        }

        .notification-icon {
            position: relative;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6B7280;
        }

        .notification-badge {
            position: absolute;
            top: 4px;
            right: 4px;
            background: #EF4444;
            color: white;
            font-size: 11px;
            font-weight: 600;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-type-btn {
            background: #0066CC;
            color: white;
            padding: 6px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            border-radius: 8px;
            transition: background-color 0.2s;
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
            display: flex;
            gap: 24px;
            padding: 24px;
            max-width: 1600px;
            margin: 0 auto;
        }

        /* Left Panel */
        .left-panel {
            flex: 1;
            max-width: 100%;
        }

        /* Progress Steps */
        .progress-steps {
            background: white;
            padding: 24px;
            border-radius: 12px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            height: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            flex: 1;
            width: 128.42px;
            height: 105px;
            min-width: 84.14px;
            
        }

        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #E5E7EB;
            color: #6B7280;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 600;
            flex-shrink: 0;
        }

        .step.completed .step-circle {
            background: #10B981;
            color: white;
        }

        .step.active .step-circle {
            background: #1E3A8A;
            color: white;
        }

        .step-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .step-title {
            font-size: 14px;
            font-weight: 600;
            color: #1F2937;
        }

        .step-subtitle {
            font-size: 12px;
            color: #6B7280;
        }

        .step-line {
            height: 2px;
            flex: 1;
            background: #E5E7EB;
            margin: 0 -8px;
            align-self: flex-start;
            margin-top: 20px;
        }

        .step-line.completed {
            background: #10B981;
        }

        .step-line.active {
            background: linear-gradient(to right, #10B981 0%, #1E3A8A 100%);
        }

        /* Booking Form */
        .booking-form {
            background: white;
            padding: 32px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .form-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }

        .form-header h2 {
            font-size: 20px;
            font-weight: 600;
            color: #1F2937;
        }

        .back-link {
            display: flex;
            align-items: center;
            gap: 4px;
            color: #0066CC;
            font-size: 14px;
            font-weight: 500;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #1F2937;
            margin-bottom: 8px;
        }

        .required {
            color: #EF4444;
        }

        /* Number Input */
        .number-input {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 16px;
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            margin-bottom: 8px;
        }

        .number-btn {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            color: #6B7280;
            transition: background-color 0.2s;
        }

        .number-btn:hover {
            background-color: #E5E7EB;
        }

        .number-display {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 16px;
            font-weight: 600;
            color: #1F2937;
        }

        .helper-text {
            font-size: 12px;
            color: #6B7280;
        }

        /* Form Label Row */
        .form-label-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .priority-select {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #6B7280;
            padding: 4px 12px;
            border: 1px solid #E5E7EB;
            border-radius: 6px;
            cursor: pointer;
            position: relative;
        }

        .priority-select:hover {
            background-color: #F9FAFB;
        }

        .priority-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #0066CC;
        }

        .priority-indicator.low {
            background: #10B981;
        }

        .priority-indicator.normal {
            background: #0066CC;
        }

        .priority-indicator.high {
            background: #F59E0B;
        }

        .priority-indicator.urgent {
            background: #EF4444;
        }

        .priority-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 4px;
            background: white;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            min-width: 150px;
            z-index: 10;
            display: none;
        }

        .priority-dropdown.active {
            display: block;
        }

        .priority-option {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            cursor: pointer;
            font-size: 14px;
            color: #1F2937;
            transition: background-color 0.2s;
        }

        .priority-option:first-child {
            border-radius: 8px 8px 0 0;
        }

        .priority-option:last-child {
            border-radius: 0 0 8px 8px;
        }

        .priority-option:hover {
            background-color: #F3F4F6;
        }

        .priority-option.selected {
            background-color: #EFF6FF;
        }

        /* Textarea */
        .textarea-wrapper {
            position: relative;
        }

        .textarea-wrapper svg {
            position: absolute;
            left: 12px;
            top: 12px;
            color: #9CA3AF;
        }

        .textarea-wrapper textarea,
        textarea.additional-notes {
            width: 100%;
            min-height: 100px;
            padding: 12px 12px 12px 40px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            resize: vertical;
        }

        textarea.additional-notes {
            padding: 12px;
            min-height: 80px;
        }

        textarea::placeholder {
            color: #9CA3AF;
        }

        textarea:focus {
            outline: none;
            border-color: #0066CC;
            box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
        }

        /* SFI Support Card */
        .sfi-support-card {
            background: #F0F7FF;
            border: 1px solid #BFDBFE;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 32px;
        }

        .sfi-support-header {
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        .sfi-support-header svg {
            flex-shrink: 0;
            margin-top: 2px;
        }

        .sfi-support-content {
            flex: 1;
        }

        .sfi-support-content h3 {
            font-size: 15px;
            font-weight: 600;
            color: #1F2937;
            margin-bottom: 4px;
        }

        .sfi-support-content p {
            font-size: 13px;
            color: #4B5563;
            line-height: 1.5;
        }

        /* Toggle Switch */
        .toggle-switch {
            position: relative;
            width: 48px;
            height: 28px;
            flex-shrink: 0;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #D1D5DB;
            transition: 0.3s;
            border-radius: 28px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
        }

        input:checked + .toggle-slider {
            background-color: #0066CC;
        }

        input:checked + .toggle-slider:before {
            transform: translateX(20px);
        }

        /* SFI Details */
        .sfi-details {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #BFDBFE;
            display: none;
        }

        .sfi-details.active {
            display: block;
        }

        /* Equipment Grid */
        .equipment-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .equipment-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 16px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            font-size: 14px;
            color: #6B7280;
            background: white;
            transition: all 0.2s;
        }

        .equipment-btn:hover {
            border-color: #0066CC;
            color: #0066CC;
        }

        .equipment-btn.selected {
            border-color: #0066CC;
            background: #EFF6FF;
            color: #0066CC;
        }

        /* Hint Text */
        .hint-text {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            margin-top: 8px;
            font-size: 12px;
            color: #92400E;
            padding: 8px;
            background: #FEF3C7;
            border-radius: 6px;
        }

        .hint-text svg {
            flex-shrink: 0;
            margin-top: 2px;
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            padding-top: 24px;
            border-top: 1px solid #E5E7EB;
        }

        .btn-secondary {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            color: #1F2937;
            background: white;
            transition: background-color 0.2s;
        }

        .btn-secondary:hover {
            background-color: #F9FAFB;
        }

        .btn-primary {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            color: white;
            background: #1E3A8A;
            transition: background-color 0.2s;
        }

        .btn-primary:hover {
            background-color: #1E40AF;
        }

        /* Right Panel */
        .right-panel {
            width: 360px;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .right-panel.hidden {
            display: none;
        }

        .bookings-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .bookings-header h2 {
            font-size: 18px;
            font-weight: 600;
            color: #1F2937;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 24px;
            height: 24px;
            padding: 0 8px;
            background: #E5E7EB;
            color: #1F2937;
            font-size: 12px;
            font-weight: 600;
            border-radius: 12px;
        }

        .bookings-actions {
            display: flex;
            gap: 8px;
        }

        .icon-btn {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            color: #6B7280;
            transition: background-color 0.2s;
        }

        .icon-btn:hover {
            background-color: #F3F4F6;
        }

        /* User Login Info */
        .user-login-info {
            background: white;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 13px;
            color: #6B7280;
        }

        .user-email-text {
            font-weight: 500;
            color: #1F2937;
            margin: 4px 0;
        }

        /* Booking Tabs */
        .booking-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 16px;
        }

        .tab {
            flex: 1;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 500;
            color: #6B7280;
            background: white;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .tab.active {
            color: #1F2937;
            background: #F3F4F6;
        }

        .tab:hover {
            background: #F9FAFB;
        }

        /* Booking List */
        .booking-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        /* Booking Card */
        .booking-card {
            background: #FEF3C7;
            padding: 16px;
            border-radius: 8px;
            position: relative;
        }

        .booking-status {
            position: absolute;
            top: 12px;
            left: 12px;
            background: #F59E0B;
            color: white;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
        }

        .booking-date {
            text-align: right;
            font-size: 13px;
            color: #78350F;
            margin-bottom: 24px;
        }

        .booking-card h3 {
            font-size: 15px;
            font-weight: 600;
            color: #1F2937;
            margin-bottom: 4px;
        }

        .booking-time {
            font-size: 13px;
            color: #6B7280;
        }

        .sfi-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 8px;
            background: white;
            border: 1px solid #BFDBFE;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            color: #0066CC;
            margin-top: 8px;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .main-container {
                flex-direction: column;
            }

            .right-panel {
                width: 100%;
            }

            .right-panel.hidden {
                display: none;
            }
        }

        /* ------- MOBILE (Phones) -------- */
        @media (max-width: 600px) {
            /* Header */
            .header {
                flex-direction: column;
                gap: 12px;
                padding: 12px 16px;
                text-align: center;
            }

            .header-left {
                flex-direction: column;
                gap: 8px;
            }

            .header-right {
                width: 100%;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 8px;
            }

            .my-bookings-btn {
                order: 1;
                flex: 1;
                justify-content: center;
                min-width: 120px;
            }

            .notification-icon {
                order: 2;
            }

            .user-type-btn {
                order: 3;
                flex: 1;
                min-width: 80px;
            }

            .user-profile {
                order: 4;
                flex: 2;
                justify-content: center;
                min-width: 140px;
            }

            /* Main container */
            .main-container {
                flex-direction: column;
                padding: 12px;
                gap: 16px;
            }

            /* Progress steps */
            .progress-steps {
                flex-direction: column;
                gap: 16px;
                padding: 16px;
            }

            .step {
                flex-direction: row;
                justify-content: flex-start;
                width: 100%;
                height: auto;
                gap: 12px;
            }

            .step-circle {
                width: 36px;
                height: 36px;
                font-size: 13px;
            }

            .step-content {
                align-items: flex-start;
                text-align: left;
            }

            .step-title {
                font-size: 14px;
            }

            .step-subtitle {
                white-space: nowrap;
                font-size: 12px;
                opacity: 0.9;
            }

            /* Remove the horizontal connector lines */
            .step-line {
                display: none;
            }

            /* Booking form */
            .booking-form {
                padding: 20px;
            }

            .form-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
                margin-bottom: 24px;
            }

            .form-header h2 {
                font-size: 18px;
            }

            /* Form elements */
            .form-label-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .priority-select {
                align-self: flex-end;
            }

            .number-input {
                padding: 10px 12px;
            }

            .number-btn {
                width: 28px;
                height: 28px;
            }

            /* SFI support card */
            .sfi-support-header {
                flex-direction: column;
                gap: 16px;
            }

            .toggle-switch {
                align-self: flex-start;
            }

            /* Equipment grid */
            .equipment-grid {
                grid-template-columns: 1fr;
            }

            /* Form actions */
            .form-actions {
                flex-direction: column;
                gap: 12px;
            }

            .btn-primary,
            .btn-secondary {
                width: 100%;
                justify-content: center;
            }

            /* Right panel */
            .right-panel {
                width: 100%;
                margin-top: 0;
            }

            .bookings-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .bookings-actions {
                align-self: flex-end;
            }

            /* Booking tabs */
            .booking-tabs {
                flex-direction: column;
            }

            /* User profile in header - hide email on mobile */
            .user-email {
                display: none;
            }
        }

        /* Small phones */
        @media (max-width: 400px) {
            .header-right {
                flex-direction: column;
                gap: 8px;
            }

            .my-bookings-btn,
            .user-type-btn,
            .user-profile {
                width: 100%;
            }

            .priority-dropdown {
                left: 0;
                right: auto;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-left">
            <div class="logo">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                    <rect width="32" height="32" rx="4" fill="#0066CC"/>
                    <path d="M8 12h16M8 16h16M8 20h16" stroke="white" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>
            <div class="header-title">
                <h1>Shared Services Portal</h1>
                <p>One-Stop Booking Platform</p>
            </div>
        </div>
        <div class="header-right">
            <button class="my-bookings-btn">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <rect x="2" y="3" width="12" height="10" rx="1" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M5 1v4M11 1v4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                My Bookings
            </button>
            <div class="notification-icon">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M15 6.5a5 5 0 10-10 0c0 5-2 6.5-2 6.5h14s-2-1.5-2-6.5zM11.73 16.5a2 2 0 01-3.46 0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="notification-badge">2</span>
            </div>
            <button class="user-type-btn">User</button>
            <div class="user-profile">
                <div class="user-avatar">CR</div>
                <div class="user-info">
                    <span class="user-name">Charles Ramos</span>
                    <span class="user-email">user.charles@enc.gov</span>
                </div>
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="main-container">
        <!-- Left Panel -->
        <div class="left-panel">
            <!-- Progress Steps -->
            <div class="progress-steps">
                <div class="step completed">
                    <div class="step-circle">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M3 8l3 3 7-7" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="step-content">
                        <span class="step-title">Select Room</span>
                        <span class="step-subtitle">Browse available rooms</span>
                    </div>
                </div>
                <div class="step-line completed"></div>
                
                <div class="step completed">
                    <div class="step-circle">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M3 8l3 3 7-7" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="step-content">
                        <span class="step-title">Date & Time</span>
                        <span class="step-subtitle">Choose when to book</span>
                    </div>
                </div>
                <div class="step-line active"></div>
                
                <div class="step active">
                    <div class="step-circle">3</div>
                    <div class="step-content" style="">
                        <span class="step-title">Details</span>
                        <span class="step-subtitle" style="white-space: normal;">Add booking information</span>
                    </div>
                </div>
                <div class="step-line"></div>
                
                <div class="step">
                    <div class="step-circle">4</div>
                    <div class="step-content">
                        <span class="step-title">Confirm</span>
                        <span class="step-subtitle">Review and submit</span>
                    </div>
                </div>
            </div>

            <!-- Booking Form -->
            <div class="booking-form">
                <div class="form-header">
                    <h2>Add Booking Details</h2>
                    <button class="back-link">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M10 12L6 8l4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Back
                    </button>
                </div>

                <!-- Number of Attendees -->
                <div class="form-group">
                    <label>Number of Attendees <span class="required">*</span></label>
                    <div class="number-input">
                        <button class="number-btn" id="decrementBtn">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M3 8h10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                        </button>
                        <div class="number-display">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M13 5.5c0 2.5-2 4-5 6.5-3-2.5-5-4-5-6.5a3 3 0 016 0 3 3 0 016 0z" stroke="currentColor" stroke-width="1.5"/>
                            </svg>
                            <span id="attendeeCount">4</span>
                        </div>
                        <button class="number-btn" id="incrementBtn">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M8 3v10M3 8h10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                        </button>
                    </div>
                    <span class="helper-text">Maximum capacity: 8 people</span>
                </div>

                <!-- Purpose / Meeting Agenda -->
                <div class="form-group">
                    <div class="form-label-row">
                        <label>Purpose / Meeting Agenda</label>
                        <div class="priority-select" id="prioritySelect">
                            <span>Priority</span>
                            <span class="priority-indicator normal"></span>
                            <span id="priorityText">Normal</span>
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div class="priority-dropdown" id="priorityDropdown">
                                <div class="priority-option" data-priority="low">
                                    <span class="priority-indicator low"></span>
                                    <span>Low</span>
                                </div>
                                <div class="priority-option selected" data-priority="normal">
                                    <span class="priority-indicator normal"></span>
                                    <span>Normal</span>
                                </div>
                                <div class="priority-option" data-priority="high">
                                    <span class="priority-indicator high"></span>
                                    <span>High</span>
                                </div>
                                <div class="priority-option" data-priority="urgent">
                                    <span class="priority-indicator urgent"></span>
                                    <span>Urgent</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="textarea-wrapper">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M3 3h10v10H3z" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M5 6h6M5 9h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        <textarea placeholder="Enter the purpose of your meeting (optional)"></textarea>
                    </div>
                </div>

                <!-- Request SFI Support -->
                <div class="sfi-support-card">
                    <div class="sfi-support-header">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="#0066CC">
                            <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 12a1 1 0 110-2 1 1 0 010 2zm1-4V6H9v4h2z"/>
                        </svg>
                        <div class="sfi-support-content">
                            <h3>Request SFI Support</h3>
                            <p>Need help with setup? Our support team can assist with equipment, refreshments (juice/water), and room preparation.</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" id="sfiToggle">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>

                    <!-- SFI Details (Hidden by default) -->
                    <div class="sfi-details" id="sfiDetails">
                        <!-- SFI Manpower Required -->
                        <div class="form-group">
                            <label>SFI Manpower Required <span class="required">*</span></label>
                            <div class="number-input">
                                <button class="number-btn" id="staffDecrementBtn">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <path d="M3 8h10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                    </svg>
                                </button>
                                <div class="number-display">
                                    <span id="staffCount">1</span>
                                    <span class="number-label">Staff</span>
                                </div>
                                <button class="number-btn" id="staffIncrementBtn">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <path d="M8 3v10M3 8h10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                    </svg>
                                </button>
                            </div>
                            <span class="helper-text">Maximum 10 staff members</span>
                        </div>

                        <!-- Equipment Needed -->
                        <div class="form-group">
                            <label>Equipment Needed</label>
                            <div class="equipment-grid">
                                <button class="equipment-btn">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <rect x="2" y="3" width="12" height="8" rx="1" stroke="currentColor" stroke-width="1.5"/>
                                        <path d="M7 11v2M9 11v2M5 13h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                    </svg>
                                    Projector
                                </button>
                                <button class="equipment-btn">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <rect x="3" y="2" width="10" height="12" rx="1" stroke="currentColor" stroke-width="1.5"/>
                                        <circle cx="8" cy="12" r="0.5" fill="currentColor"/>
                                    </svg>
                                    TV Monitor
                                </button>
                                <button class="equipment-btn">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <rect x="2" y="5" width="12" height="8" rx="1" stroke="currentColor" stroke-width="1.5"/>
                                        <path d="M2 8h12" stroke="currentColor" stroke-width="1.5"/>
                                    </svg>
                                    Whiteboard
                                </button>
                                <button class="equipment-btn">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <circle cx="8" cy="5" r="2" stroke="currentColor" stroke-width="1.5"/>
                                        <path d="M8 7v6M5 10l3-1.5M11 10l-3-1.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                    </svg>
                                    Microphone
                                </button>
                            </div>
                        </div>

                        <!-- Additional Requirements -->
                        <div class="form-group">
                            <label>Additional Requirements / Notes</label>
                            <textarea class="additional-notes" placeholder="E.g., Need 20 bottles of water, coffee/tea station, specific table arrangement, etc."></textarea>
                            <div class="hint-text">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="#FFA500">
                                    <path d="M7 0l1.5 4.5L13 7l-4.5 1.5L7 13l-1.5-4.5L1 7l4.5-1.5L7 0z"/>
                                </svg>
                                <span>Typical services: Refreshments (water/juice/coffee), table arrangement, A/V support</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="form-actions">
                    <button class="btn-secondary">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M10 12L6 8l4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Back to Date & Time
                    </button>
                    <button class="btn-primary">
                        Next: Review & Confirm
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M6 4l4 4-4 4" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Right Panel (Bookings) -->
        <div class="right-panel" id="rightPanel">
            <div class="bookings-header">
                <h2>Your Bookings <span class="badge">5</span></h2>
                <div class="bookings-actions">
                    <button class="icon-btn">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M14 8a6 6 0 11-12 0 6 6 0 0112 0z" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M8 8V5M8 10.5v.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </button>
                    <button class="icon-btn">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="user-login-info">
                <p>Logged in as</p>
                <p class="user-email-text">user.charles@enc.gov</p>
                <p>Total Bookings: <strong>5</strong></p>
            </div>

            <!-- Tabs -->
            <div class="booking-tabs">
                <button class="tab active">Pending (5)</button>
                <button class="tab">Confirmed (0)</button>
            </div>

            <!-- Booking Cards -->
            <div class="booking-list">
                <div class="booking-card">
                    <div class="booking-status">Pending</div>
                    <div class="booking-date">11/29/2025</div>
                    <h3>Meeting Room B</h3>
                    <p class="booking-time">9:00 AM - 10:00 AM</p>
                </div>

                <div class="booking-card">
                    <div class="booking-status">Pending</div>
                    <div class="booking-date">11/29/2025</div>
                    <h3>Meeting Room B</h3>
                    <p class="booking-time">10:30 AM - 11:00 AM</p>
                </div>

                <div class="booking-card">
                    <div class="booking-status">Pending</div>
                    <div class="booking-date">11/29/2025</div>
                    <h3>Meeting Room B</h3>
                    <p class="booking-time">11:00 AM - 12:00 PM</p>
                </div>

                <div class="booking-card">
                    <div class="booking-status">Pending</div>
                    <div class="booking-date">11/30/2025</div>
                    <div class="sfi-badge">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                            <circle cx="6" cy="6" r="5" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M6 3v3l2 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        SFI
                    </div>
                    <h3>Meeting Room B</h3>
                    <p class="booking-time">9:00 AM - 10:00 AM</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Attendee Counter
        let attendeeCount = 4;
        const maxAttendees = 8;
        const minAttendees = 1;

        const attendeeCountEl = document.getElementById('attendeeCount');
        const incrementBtn = document.getElementById('incrementBtn');
        const decrementBtn = document.getElementById('decrementBtn');

        incrementBtn.addEventListener('click', () => {
            if (attendeeCount < maxAttendees) {
                attendeeCount++;
                attendeeCountEl.textContent = attendeeCount;
            }
        });

        decrementBtn.addEventListener('click', () => {
            if (attendeeCount > minAttendees) {
                attendeeCount--;
                attendeeCountEl.textContent = attendeeCount;
            }
        });

        // Staff Counter
        let staffCount = 1;
        const maxStaff = 10;
        const minStaff = 1;

        const staffCountEl = document.getElementById('staffCount');
        const staffIncrementBtn = document.getElementById('staffIncrementBtn');
        const staffDecrementBtn = document.getElementById('staffDecrementBtn');

        staffIncrementBtn.addEventListener('click', () => {
            if (staffCount < maxStaff) {
                staffCount++;
                staffCountEl.textContent = staffCount;
            }
        });

        staffDecrementBtn.addEventListener('click', () => {
            if (staffCount > minStaff) {
                staffCount--;
                staffCountEl.textContent = staffCount;
            }
        });

        // SFI Support Toggle
        const sfiToggle = document.getElementById('sfiToggle');
        const sfiDetails = document.getElementById('sfiDetails');
        const rightPanel = document.getElementById('rightPanel');

        sfiToggle.addEventListener('change', (e) => {
            if (e.target.checked) {
                sfiDetails.classList.add('active');
            } else {
                sfiDetails.classList.remove('active');
            }
        });

        // Equipment Selection
        const equipmentButtons = document.querySelectorAll('.equipment-btn');

        equipmentButtons.forEach(button => {
            button.addEventListener('click', () => {
                button.classList.toggle('selected');
            });
        });

        // Tab Switching
        const tabs = document.querySelectorAll('.tab');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                
                // Here you would typically filter/show different bookings
                // For this demo, we'll just update the active state
            });
        });

        // Add animation when showing/hiding SFI details
        sfiDetails.style.transition = 'all 0.3s ease';

        // Responsive handling
        function handleResponsive() {
            if (window.innerWidth <= 1200) {
                // On mobile/tablet, always show right panel (don't hide it based on SFI toggle)
                if (sfiToggle.checked) {
                    rightPanel.classList.remove('hidden');
                }
            }
        }

        window.addEventListener('resize', handleResponsive);
        handleResponsive(); // Call on load

        // Priority Selection
        const prioritySelect = document.getElementById('prioritySelect');
        const priorityDropdown = document.getElementById('priorityDropdown');
        const priorityOptions = document.querySelectorAll('.priority-option');
        const priorityText = document.getElementById('priorityText');

        prioritySelect.addEventListener('click', () => {
            priorityDropdown.classList.toggle('active');
        });

        priorityOptions.forEach(option => {
            option.addEventListener('click', (e) => {
                e.stopPropagation();
                const priority = option.getAttribute('data-priority');
                const text = option.querySelector('span:last-child').textContent;

                // Update selected state
                priorityOptions.forEach(opt => opt.classList.remove('selected'));
                option.classList.add('selected');

                // Update display
                priorityText.textContent = text;
                const selectIndicator = prioritySelect.querySelector('.priority-indicator');
                selectIndicator.className = 'priority-indicator ' + priority;

                // Close dropdown
                priorityDropdown.classList.remove('active');
            });
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!prioritySelect.contains(e.target)) {
                priorityDropdown.classList.remove('active');
            }
        });
    </script>
</body>
</html>