<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Review - Shared Services Portal</title>
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
            padding: 24px;
        }

        button {
            cursor: pointer;
            border: none;
            background: none;
            font-family: inherit;
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Header */
        .page-header {
            margin-bottom: 24px;
        }

        .header-top {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 12px;
        }

        .back-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 12px;
            border: 1px solid #E5E7EB;
            border-radius: 6px;
            font-size: 14px;
            color: #1F2937;
            background: white;
            transition: background-color 0.2s;
        }

        .back-btn:hover {
            background-color: #F9FAFB;
        }

        .header-title-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-title {
            font-size: 24px;
            font-weight: 600;
            color: #1F2937;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            background: #FEF3C7;
            color: #D97706;
        }

        .header-meta {
            display: flex;
            align-items: center;
            gap: 16px;
            font-size: 13px;
            color: #6B7280;
        }

        .ref-number {
            font-weight: 500;
            color: #1F2937;
        }

        .view-details-link {
            display: flex;
            align-items: center;
            gap: 4px;
            color: #0066CC;
            text-decoration: none;
            font-weight: 500;
        }

        .view-details-link:hover {
            text-decoration: underline;
        }

        /* Main Layout */
        .main-layout {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 24px;
        }

        /* Left Panel */
        .left-panel {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Card */
        .card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
        }

        .card-icon {
            color: #3B82F6;
        }

        .card-title {
            font-size: 16px;
            font-weight: 600;
            color: #1F2937;
        }

        /* Alert Box */
        .alert-box {
            background: #FFFBEB;
            border: 1px solid #FDE68A;
            border-left: 4px solid #F59E0B;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 20px;
        }

        .alert-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }

        .alert-icon {
            color: #F59E0B;
        }

        .alert-title {
            font-size: 14px;
            font-weight: 600;
            color: #92400E;
        }

        .alert-message {
            font-size: 13px;
            color: #92400E;
            line-height: 1.6;
        }

        /* Meeting Room Info */
        .meeting-room-info {
            display: grid;
            grid-template-columns: 120px 1fr;
            gap: 16px;
            margin-bottom: 16px;
        }

        .room-image {
            width: 120px;
            height: 80px;
            border-radius: 8px;
            object-fit: cover;
            background: #E5E7EB;
        }

        .room-details {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .room-name {
            font-size: 15px;
            font-weight: 600;
            color: #1F2937;
        }

        .room-building {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 13px;
            color: #6B7280;
        }

        .room-amenities {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .amenity-tag {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            color: #6B7280;
        }

        .capacity-info {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #1F2937;
        }

        /* Date Time Info */
        .date-time-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            padding-top: 16px;
            border-top: 1px solid #F3F4F6;
        }

        .info-group label {
            display: block;
            font-size: 12px;
            color: #6B7280;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 14px;
            color: #1F2937;
            font-weight: 500;
        }

        .time-value {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Purpose Section */
        .purpose-section {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .purpose-field {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .purpose-field label {
            font-size: 12px;
            color: #6B7280;
        }

        .purpose-field .value {
            font-size: 14px;
            color: #1F2937;
            font-weight: 500;
        }

        /* Compliance Check */
        .compliance-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .compliance-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background: #F9FAFB;
            border-radius: 6px;
        }

        .compliance-label {
            font-size: 13px;
            color: #1F2937;
        }

        .compliance-status {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .compliance-status.compliant {
            background: #D1FAE5;
            color: #059669;
        }

        .compliance-status.non-compliant {
            background: #FEE2E2;
            color: #DC2626;
        }

        /* Conflicts Section */
        .no-conflicts {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px;
            text-align: center;
        }

        .check-icon-large {
            width: 60px;
            height: 60px;
            margin-bottom: 12px;
        }

        .check-icon-large circle {
            fill: #D1FAE5;
        }

        .check-icon-large path {
            stroke: #059669;
        }

        .no-conflicts-text {
            font-size: 14px;
            color: #6B7280;
        }

        /* Additional Notes */
        .notes-content {
            font-size: 13px;
            color: #1F2937;
            line-height: 1.6;
        }

        /* Right Panel */
        .right-panel {
            position: sticky;
            top: 24px;
            height: fit-content;
        }

        .decision-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .decision-header {
            font-size: 18px;
            font-weight: 600;
            color: #1F2937;
            margin-bottom: 8px;
        }

        .decision-subtitle {
            font-size: 13px;
            color: #6B7280;
            margin-bottom: 20px;
        }

        /* Requester Info */
        .requester-info {
            background: #F9FAFB;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 24px;
        }

        .requester-label {
            font-size: 12px;
            color: #6B7280;
            margin-bottom: 8px;
        }

        .requester-profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .requester-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #DBEAFE;
            color: #1E40AF;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 600;
        }

        .requester-details {
            display: flex;
            flex-direction: column;
        }

        .requester-name {
            font-size: 14px;
            font-weight: 600;
            color: #1F2937;
        }

        .requester-email {
            font-size: 12px;
            color: #6B7280;
        }

        .requester-dept {
            font-size: 12px;
            color: #6B7280;
        }

        /* Decision Options */
        .decision-section {
            margin-bottom: 20px;
        }

        .section-label {
            font-size: 14px;
            font-weight: 600;
            color: #1F2937;
            margin-bottom: 12px;
        }

        .required-mark {
            color: #EF4444;
        }

        .decision-options {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .decision-option {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .decision-option:hover {
            border-color: #3B82F6;
            background: #F9FAFB;
        }

        .decision-option.selected {
            border-color: #3B82F6;
            background: #EFF6FF;
        }

        .decision-option input[type="radio"] {
            margin-top: 2px;
            cursor: pointer;
        }

        .option-content {
            flex: 1;
        }

        .option-title {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
            font-weight: 500;
            color: #1F2937;
            margin-bottom: 4px;
        }

        .option-description {
            font-size: 12px;
            color: #6B7280;
        }

        /* Remarks */
        .remarks-textarea {
            width: 100%;
            min-height: 80px;
            padding: 12px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            resize: vertical;
        }

        .remarks-textarea::placeholder {
            color: #9CA3AF;
        }

        .remarks-textarea:focus {
            outline: none;
            border-color: #3B82F6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Submit Button */
        .submit-btn {
            width: 100%;
            padding: 12px;
            background: #1E3A8A;
            color: white;
            font-size: 14px;
            font-weight: 500;
            border-radius: 8px;
            margin-bottom: 16px;
            transition: background-color 0.2s;
        }

        .submit-btn:hover {
            background: #1E40AF;
        }

        .submit-btn:disabled {
            background: #9CA3AF;
            cursor: not-allowed;
        }

        /* Info Notice */
        .info-notice {
            display: flex;
            gap: 10px;
            padding: 12px;
            background: #EFF6FF;
            border-radius: 8px;
            font-size: 12px;
            color: #1E40AF;
        }

        .info-icon {
            flex-shrink: 0;
            margin-top: 2px;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .main-layout {
                grid-template-columns: 1fr;
            }

            .right-panel {
                position: static;
            }
        }

        @media (max-width: 640px) {
            body {
                padding: 12px;
            }

            .page-title {
                font-size: 20px;
            }

            .meeting-room-info {
                grid-template-columns: 1fr;
            }

            .date-time-info {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-top">
                <button class="back-btn" onclick="window.history.back()">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M10 12L6 8l4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Back
                </button>
                <div class="header-title-section">
                    <h1 class="page-title">Booking Review</h1>
                    <span class="status-badge">Pending Approval</span>
                </div>
            </div>
            <div class="header-meta">
                <span>Ref: <span class="ref-number">BK-2025-0142</span></span>
                <span>•</span>
                <span>Submitted: 2025-11-11 09:30 AM</span>
                <span>•</span>
                <a href="#" class="view-details-link">
                    View Full Details
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <path d="M10 7H4M7 10V4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <rect x="1" y="1" width="12" height="12" rx="2" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Main Layout -->
        <div class="main-layout">
            <!-- Left Panel -->
            <div class="left-panel">
                <!-- Alert Box -->
                <div class="alert-box">
                    <div class="alert-header">
                        <svg class="alert-icon" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <circle cx="8" cy="8" r="7" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M8 4v4M8 10v1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        <span class="alert-title">Why approval is needed:</span>
                    </div>
                    <p class="alert-message">Booking made less than 24 hours in advance requires manager approval per Policy #5.2</p>
                </div>

                <!-- When & Where -->
                <div class="card">
                    <div class="card-header">
                        <svg class="card-icon" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <rect x="3" y="4" width="14" height="12" rx="2" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M6 2v4M14 2v4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M3 8h14" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                        <h3 class="card-title">When & Where</h3>
                    </div>

                    <div class="meeting-room-info">
                        <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?w=200&h=150&fit=crop" alt="Meeting Room" class="room-image">
                        <div class="room-details">
                            <div class="room-name">Meeting Room A</div>
                            <div class="room-building">
                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                                    <path d="M2 10h8M3 10V3l3-2 3 2v7" stroke="currentColor" stroke-width="1"/>
                                </svg>
                                Building A
                            </div>
                            <div class="room-amenities">
                                <span class="amenity-tag">projector</span>
                                <span class="amenity-tag">whiteboard</span>
                                <span class="amenity-tag">tv</span>
                            </div>
                            <div class="capacity-info">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                                    <path d="M13 5.5c0 2.5-2 4-5 6.5-3-2.5-5-4-5-6.5a3 3 0 016 0 3 3 0 016 0z" stroke="currentColor" stroke-width="1"/>
                                </svg>
                                Capacity: 12 people
                            </div>
                        </div>
                    </div>

                    <div class="date-time-info">
                        <div class="info-group">
                            <label>Date</label>
                            <div class="info-value">Monday, November 18, 2025</div>
                        </div>
                        <div class="info-group">
                            <label>Time</label>
                            <div class="info-value time-value">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                                    <circle cx="7" cy="7" r="6" stroke="currentColor" stroke-width="1.5"/>
                                    <path d="M7 3v4l2 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                                14:00 - 16:00 <span style="color: #6B7280; font-weight: 400;">(2 hours)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Purpose & Manpower -->
                <div class="card">
                    <div class="card-header">
                        <svg class="card-icon" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <rect x="3" y="3" width="14" height="14" rx="2" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M6 7h8M6 11h5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        <h3 class="card-title">Purpose & Manpower</h3>
                    </div>

                    <div class="purpose-section">
                        <div class="purpose-field">
                            <label>Meeting Purpose</label>
                            <div class="value">Quarterly Team Review Meeting</div>
                        </div>
                        <div class="purpose-field">
                            <label>Expected Attendees</label>
                            <div class="value">10 people</div>
                        </div>
                    </div>
                </div>

                <!-- Policy Compliance Check -->
                <div class="card">
                    <div class="card-header">
                        <svg class="card-icon" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <rect x="3" y="3" width="14" height="14" rx="2" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M7 10l2 2 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <h3 class="card-title">Policy Compliance Check</h3>
                    </div>

                    <div class="compliance-list">
                        <div class="compliance-item">
                            <span class="compliance-label">Maximum booking duration: 4 hours</span>
                            <span class="compliance-status compliant">
                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                                    <path d="M2 6l2.5 2.5 5.5-5.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Compliant
                            </span>
                        </div>
                        <div class="compliance-item">
                            <span class="compliance-label">Advance notice: 24 hours</span>
                            <span class="compliance-status non-compliant">
                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                                    <path d="M3 3l6 6M9 3l-6 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                                Non-compliant
                            </span>
                        </div>
                        <div class="compliance-item">
                            <span class="compliance-label">Room capacity limits</span>
                            <span class="compliance-status compliant">
                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                                    <path d="M2 6l2.5 2.5 5.5-5.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Compliant
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Scheduling Conflicts -->
                <div class="card">
                    <div class="card-header">
                        <svg class="card-icon" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <circle cx="10" cy="10" r="7" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M10 6v4l2 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        <h3 class="card-title">Scheduling Conflicts</h3>
                    </div>

                    <div class="no-conflicts">
                        <svg class="check-icon-large" viewBox="0 0 60 60" fill="none">
                            <circle cx="30" cy="30" r="28" fill="#D1FAE5"/>
                            <path d="M18 30l8 8 16-16" stroke="#059669" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div class="no-conflicts-text">No conflicts detected</div>
                    </div>
                </div>

                <!-- Additional Notes -->
                <div class="card">
                    <div class="card-header">
                        <svg class="card-icon" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <rect x="3" y="3" width="14" height="14" rx="2" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M6 7h8M6 11h5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        <h3 class="card-title">Additional Notes</h3>
                    </div>

                    <div class="notes-content">
                        Need setup 15 minutes before meeting. Refreshments requested.
                    </div>
                </div>
            </div>

            <!-- Right Panel -->
            <div class="right-panel">
                <div class="decision-card">
                    <h2 class="decision-header">Make Decision</h2>
                    <p class="decision-subtitle">Review and approve or reject this booking</p>

                    <!-- Requester Info -->
                    <div class="requester-info">
                        <div class="requester-label">Requested by</div>
                        <div class="requester-profile">
                            <div class="requester-avatar">JB</div>
                            <div class="requester-details">
                                <div class="requester-name">Jason Buenacosejo</div>
                                <div class="requester-email">jason@enc.gov</div>
                                <div class="requester-dept">IT Department</div>
                            </div>
                        </div>
                    </div>

                    <!-- Your Decision -->
                    <div class="decision-section">
                        <label class="section-label">Your Decision <span class="required-mark">*</span></label>
                        <div class="decision-options">
                            <label class="decision-option" id="option-approve">
                                <input type="radio" name="decision" value="approve">
                                <div class="option-content">
                                    <div class="option-title">
                                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                                            <path d="M3 7l2.5 2.5 5.5-5.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        Approve
                                    </div>
                                    <div class="option-description">Grant access to the requested room</div>
                                </div>
                            </label>

                            <label class="decision-option" id="option-reject">
                                <input type="radio" name="decision" value="reject">
                                <div class="option-content">
                                    <div class="option-title">
                                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                                            <path d="M4 4l6 6M10 4l-6 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                        </svg>
                                        Reject
                                    </div>
                                    <div class="option-description">Deny this booking request</div>
                                </div>
                            </label>

                            <label class="decision-option" id="option-moreinfo">
                                <input type="radio" name="decision" value="moreinfo">
                                <div class="option-content">
                                    <div class="option-title">
                                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                                            <circle cx="7" cy="7" r="6" stroke="currentColor" stroke-width="1.5"/>
                                            <path d="M7 7V4M7 9.5v.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                        </svg>
                                        Ask for More Info
                                    </div>
                                    <div class="option-description">Request additional details from requester</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Remarks -->
                    <div class="decision-section">
                        <label class="section-label">Remarks</label>
                        <textarea 
                            class="remarks-textarea" 
                            id="remarks" 
                            placeholder="Record a decision above..."
                        ></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button class="submit-btn" id="submitBtn" disabled>Submit Decision</button>

                    <!-- Info Notice -->
                    <div class="info-notice">
                        <svg class="info-icon" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <circle cx="8" cy="8" r="7" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M8 4v4M8 10v1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        <div>The requester will be notified immediately via email with your decision and remarks.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Decision Options Logic
        const decisionOptions = document.querySelectorAll('.decision-option');
        const radioButtons = document.querySelectorAll('input[name="decision"]');
        const submitBtn = document.getElementById('submitBtn');
        const remarksTextarea = document.getElementById('remarks');

        // Handle option selection
        decisionOptions.forEach((option, index) => {
            option.addEventListener('click', () => {
                // Remove selected class from all options
                decisionOptions.forEach(opt => opt.classList.remove('selected'));
                
                // Add selected class to clicked option
                option.classList.add('selected');
                
                // Check the radio button
                const radio = option.querySelector('input[type="radio"]');
                radio.checked = true;
                
                // Enable submit button
                submitBtn.disabled = false;
            });
        });

        // Handle radio button change
        radioButtons.forEach(radio => {
            radio.addEventListener('change', () => {
                // Enable submit button
                submitBtn.disabled = false;
            });
        });

        // Submit Decision
        submitBtn.addEventListener('click', () => {
            const selectedDecision = document.querySelector('input[name="decision"]:checked');
            
            if (!selectedDecision) {
                alert('Please select a decision');
                return;
            }

            const decision = selectedDecision.value;
            const remarks = remarksTextarea.value;

            let decisionText = '';
            if (decision === 'approve') {
                decisionText = 'Approved';
            } else if (decision === 'reject') {
                decisionText = 'Rejected';
            } else if (decision === 'moreinfo') {
                decisionText = 'More Information Requested';
            }

            // Show confirmation
            const confirmMessage = `Are you sure you want to ${decisionText.toLowerCase()} this booking?\n\nRemarks: ${remarks || 'None'}`;
            
            if (confirm(confirmMessage)) {
                // Simulate submission
                submitBtn.textContent = 'Submitting...';
                submitBtn.disabled = true;

                setTimeout(() => {
                    alert(`Booking ${decisionText}!\n\nThe requester has been notified via email.`);
                    
                    // Reset form or redirect
                    // window.location.href = '/approvals.html';
                    submitBtn.textContent = 'Submit Decision';
                }, 1000);
            }
        });

        // View Full Details Link
        document.querySelector('.view-details-link').addEventListener('click', (e) => {
            e.preventDefault();
            alert('Opening full booking details...');
        });

        // Back Button (already has onclick in HTML)
        
        // Auto-focus on first decision option
        document.addEventListener('DOMContentLoaded', () => {
            // You can auto-select first option if needed
            // decisionOptions[0].click();
        });
    </script>
</body>
</html>
