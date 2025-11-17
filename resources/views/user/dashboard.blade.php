@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
    @vite(['resources/css/wizard/base.css'])
@endpush

@push('styles')
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
    gap: 12px;
}

.btn-nav,
.badge-user,
.notifications-btn,
.user-menu {
    border: none;
    background: white;
    padding: 8px 12px;
    border-radius: 999px;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

.btn-nav {
    border: 0.8px solid #e5e7eb;
}

.btn-nav:hover,
.notifications-btn:hover,
.user-menu:hover {
    background: #f8fafc;
}

.badge-user {
    background: linear-gradient(135deg, #1e3a8a, #2563eb);
    color: white;
    font-size: 12px;
    font-weight: 600;
}

.user-avatar svg {
    color: white;
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
    width: 100%;
    max-width: 1231px;
    margin: 0 auto;
}

.dashboard-top-actions {
    display: flex;
    justify-content: flex-start;
    margin-bottom: 12px;
}

.btn-back-home {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0;
    border-radius: 0;
    border: none;
    color: rgba(0, 24, 64, 0.75);
    text-decoration: none;
    background: transparent;
    font-weight: 600;
}

.btn-back-home:hover {
    color: #001840;
}

.btn-back-home svg {
    width: 16px;
    height: 16px;
}

/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, rgba(0, 36, 99, 0.95), #001840, rgba(0, 20, 59, 0.95));
    background-size: 250% 250%;
    animation: adminGradientFlow 18s ease infinite;
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
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    text-align: left;
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
    text-align: left;
    width: 100%;
}

.hero-subtitle {
    font-size: 14px;
    line-height: 20px;
    color: #dbeafe;
    margin-bottom: 16px;
    text-align: left;
    width: 100%;
}

.hero-actions {
    display: flex;
    gap: 8px;
    justify-content: flex-start;
    width: 100%;
}

.btn-browse,
.btn-new-booking {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 14px;
    border-radius: 6px;
    font-size: 14px;
    line-height: 20px;
    text-decoration: none;
}

.btn-browse {
    background: white;
    border: none;
    color: #1c398e;
    cursor: pointer;
    height: 32px;
}

.btn-browse:hover {
    background: #f1f5f9;
}

.btn-new-booking {
    background: rgba(25, 60, 184, 0.5);
    border: 0.8px solid #155dfc;
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

.upcoming-booking {
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.upcoming-date {
    font-size: 12px;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #99a1af;
    margin: 0;
}

.upcoming-title {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
}

.upcoming-purpose {
    margin: 0;
    color: #4b5563;
    font-size: 14px;
}

.upcoming-meta {
    margin: 0;
    font-size: 14px;
    color: #6b7280;
    display: flex;
    gap: 8px;
    align-items: center;
}

/* My Bookings Widget */
.dashboard-bookings-panel[data-panel-state="hidden"] {
    display: none;
}

.dashboard-bookings-panel.is-visible {
    display: block;
}

.enc-my-bookings-card {
    padding: 0;
    border: 1px solid #e5e7eb;
    border-radius: 20px;
    background: #fdfdfd;
    box-shadow: 0px 35px 80px -60px rgba(0, 24, 64, 0.8);
    overflow: hidden;
}

.enc-my-bookings-card__header {
    background: linear-gradient(135deg, rgba(0, 36, 99, 0.95), #001840, rgba(0, 20, 59, 0.95));
    background-size: 250% 250%;
    animation: adminGradientFlow 18s ease infinite;
    color: #fdfdfd;
    padding: 24px;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
}

.enc-my-bookings-card__heading h3 {
    margin: 4px 0;
    font-size: 20px;
    line-height: 28px;
    color: #fdfdfd;
}

.enc-my-bookings-card__eyebrow {
    margin: 0;
    font-size: 11px;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #fff085;
}

.enc-my-bookings-card__subtitle {
    margin: 0;
    font-size: 13px;
    color: rgba(253, 253, 253, 0.85);
}

.enc-my-bookings-card__cta {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    border-radius: 999px;
    border: 1px solid rgba(253, 253, 253, 0.45);
    color: #fdfdfd;
    text-decoration: none;
    font-size: 12px;
    font-weight: 600;
    background: rgba(253, 253, 253, 0.12);
}

.enc-my-bookings-card__cta:hover {
    background: rgba(253, 253, 253, 0.25);
}

.enc-my-bookings-card__body {
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.enc-my-bookings-card__identity {
    display: flex;
    width: 100%;
    flex-direction: column;
    align-items: stretch;
    background: #f8faff;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    padding: 18px 20px;
    gap: 16px;
}

.enc-my-bookings-card__meta-label {
    font-size: 11px;
    color: #6a7282;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    margin: 0 0 4px;
}

.enc-my-bookings-card__identity-name {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #001840;
}

.enc-my-bookings-card__identity-email {
    margin: 0;
    font-size: 13px;
    color: #4a5565;
}

.enc-my-bookings-card__identity > div:first-child {
    text-align: left;
}

.enc-my-bookings-card__total {
    background: #001840;
    color: #fdfdfd;
    border-radius: 12px;
    padding: 12px 22px;
    text-align: center;
    min-width: 220px;
    align-self: center;
}

.enc-my-bookings-card__total span {
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.08em;
}

.enc-my-bookings-card__total strong {
    display: block;
    font-size: 32px;
    line-height: 36px;
    color: #fff085;
}

.enc-my-bookings-card__tabs {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #e5e7eb;
    border-radius: 999px;
    padding: 4px;
    flex-wrap: wrap;
}

.enc-my-bookings-card__tab {
    flex: 1 1 auto;
    border: none;
    background: transparent;
    border-radius: 999px;
    padding: 10px 14px;
    font-size: 13px;
    font-weight: 600;
    color: #1e2939;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
    transition: background 0.2s ease, color 0.2s ease;
}

.enc-my-bookings-card__tab.is-active {
    background: #001840;
    color: #fdfdfd;
}

.enc-my-bookings-card__tab-pill {
    min-width: 32px;
    text-align: center;
    border-radius: 999px;
    padding: 4px 10px;
    background: rgba(0, 24, 64, 0.12);
    font-size: 12px;
}

.enc-my-bookings-card__tab.is-active .enc-my-bookings-card__tab-pill {
    background: rgba(253, 253, 253, 0.2);
    color: #fff085;
}

.enc-bookings-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
    max-height: 360px;
    overflow-y: auto;
    padding-right: 4px;
}

.enc-bookings-list__item {
    border: 1px solid #d9e3ff;
    border-radius: 14px;
    padding: 16px;
    background: #f4f6ff;
    border-left: 4px solid #155dfc;
    box-shadow: 0px 20px 40px -30px rgba(0, 24, 64, 0.45);
}

.enc-bookings-list__item[data-status="pending"] {
    background: #eaf0ff;
    border-left-color: #155dfc;
}

.enc-bookings-list__item[data-status="confirmed"],
.enc-bookings-list__item[data-status="approved"] {
    background: #e2ecff;
    border-left-color: #001840;
}

.enc-bookings-list__item[data-status="completed"] {
    background: #dfe8ff;
    border-left-color: #4c6fff;
}

.enc-bookings-list__item[data-status="cancelled"] {
    background: #f5f7ff;
    border-left-color: #9f0712;
}

.enc-bookings-list__item-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
}

.enc-booking-status {
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 999px;
}

.enc-booking-status.is-pending {
    background: #dfe8ff;
    color: #001840;
}

.enc-booking-status.is-confirmed {
    background: #d6e2ff;
    color: #001840;
}

.enc-booking-status.is-completed {
    background: #cbd9ff;
    color: #001840;
}

.enc-booking-status.is-cancelled {
    background: #ffc9c9;
    color: #9f0712;
}

.enc-booking-date {
    font-size: 13px;
    font-weight: 600;
    color: #1e2939;
}

.enc-bookings-list__item-body {
    display: flex;
    gap: 12px;
    align-items: flex-start;
}

.enc-bookings-list__icon {
    width: 40px;
    height: 40px;
    background: #e8eeff;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.enc-booking-title {
    margin: 0 0 4px;
    font-size: 15px;
    font-weight: 600;
    color: #001840;
}

.enc-booking-meta {
    margin: 0;
    font-size: 13px;
    color: #4a5565;
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.enc-bookings-empty-message {
    text-align: center;
    border: 1px dashed #e5e7eb;
    border-radius: 14px;
    padding: 20px;
    font-size: 14px;
    color: #6a7282;
    background: #f8faff;
}

@keyframes adminGradientFlow {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
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

@media (max-width: 640px) {
    .enc-my-bookings-card__identity {
        flex-direction: column;
        align-items: flex-start;
    }

    .enc-my-bookings-card__total {
        width: 100%;
    }

    .enc-my-bookings-card__tabs {
        border-radius: 16px;
    }

    .enc-my-bookings-card__tab {
        flex: 1 1 100%;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab + filter behaviour for My Bookings widget
    const bookingWidgets = document.querySelectorAll('[data-component="enc-my-bookings"]');
    bookingWidgets.forEach(widget => {
        const tabs = widget.querySelectorAll('.enc-my-bookings-card__tab');
        const items = widget.querySelectorAll('[data-role="booking-item"]');
        const emptyState = widget.querySelector('[data-role="empty-state"]');

        const filterList = (status) => {
            let visibleItems = 0;
            items.forEach(item => {
                const matches = status === 'all' || item.dataset.status === status;
                item.style.display = matches ? '' : 'none';
                if (matches) visibleItems++;
            });

            if (emptyState) {
                emptyState.hidden = visibleItems !== 0;
            }
        };

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(btn => {
                    btn.classList.remove('is-active');
                    btn.setAttribute('aria-selected', 'false');
                });

                tab.classList.add('is-active');
                tab.setAttribute('aria-selected', 'true');
                filterList(tab.dataset.status);
            });
        });

        const initialTab = widget.querySelector('.enc-my-bookings-card__tab.is-active') || tabs[0];
        if (initialTab) {
            filterList(initialTab.dataset.status);
        }
    });

    // Navbar toggle controls for bookings snapshot
    const navToggle = document.querySelector('#myBookingsToggle');
    if (navToggle) {
        const panelSelector = navToggle.getAttribute('data-target');
        if (panelSelector) {
            const panel = document.querySelector(panelSelector);
            if (panel) {
                const setPanelState = (visible) => {
                    panel.dataset.panelState = visible ? 'visible' : 'hidden';
                    panel.classList.toggle('is-visible', visible);
                    navToggle.setAttribute('aria-expanded', String(visible));
                    navToggle.setAttribute('aria-pressed', String(visible));
                };

                const initialVisible = panel.dataset.panelState !== 'hidden';
                setPanelState(initialVisible);

                navToggle.addEventListener('click', () => {
                    const currentlyVisible = panel.dataset.panelState !== 'hidden';
                    setPanelState(!currentlyVisible);
                });

                document.addEventListener('keyup', (event) => {
                    if (event.key === 'Escape' && panel.dataset.panelState !== 'hidden') {
                        setPanelState(false);
                    }
                });
            }
        }
    }

    // Individual booking items click
    const bookingItems = document.querySelectorAll('[data-role="booking-item"]');
    bookingItems.forEach(item => {
        item.addEventListener('click', function() {
            alert('Booking details would be shown here');
        });
        item.style.cursor = 'pointer';
    });
});
</script>
@endpush

@php
    $bookingStats = $bookingStats ?? ['pending' => 0, 'confirmed' => 0, 'cancelled' => 0, 'total' => 0];
    $upcomingBookingsCards = $upcomingBookingsCards ?? [];
@endphp

@section('app-navbar')
    @include('partials.dashboard-navbar', [
        'currentStep' => 0,
        'steps' => [],
        'bookingsCount' => $bookingStats['total'] ?? 0,
        'notificationsCount' => 2,
        'userName' => auth()->user()->name ?? 'Charles Ramos',
        'userEmail' => auth()->user()->email ?? 'user.charles@enc.gov',
        'userRole' => auth()->user()->role ?? 'staff',
        'brand' => 'ONE Services',
        'bookingsPanelTarget' => '#dashboardBookingsPanel',
        'showStepper' => false,
    ])
@endsection

@section('content')
<section class="dashboard-main">
    <div class="main-content">
        <div class="dashboard-top-actions">
            <a href="{{ route('landing') }}" class="btn-back-home">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9 12H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Back to Home
            </a>
        </div>
        <div class="hero-section">
            <div class="hero-content">
                <div class="hero-left">
                    <div class="greeting">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 2.5L13.9443 8.48693L20.2929 8.48693L15.1743 12.5261L17.1187 18.5131L12 14.4739L6.88128 18.5131L8.82567 12.5261L3.70706 8.48693L10.0557 8.48693L12 2.5Z" stroke="#FFF085" stroke-width="1.5" stroke-linejoin="round"/>
                        </svg>
                        Good evening
                    </div>
                    <h2 class="hero-heading">Let's plan your next booking</h2>
                    <p  class="text-white-50">Plan smarter with up-to-the-minute availability across ENC facilities.</p>
                    <div class="hero-actions">
                        <a href="{{ route('facilities.catalog') }}" class="btn-browse">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M2.66675 4.66634H13.3334" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M6.6665 2V4.66667" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M9.33325 2V4.66667" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M4.6665 7.33301H11.3332" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M4.6665 10H8.6665" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M5.33325 13.333H10.6666C12.1393 13.333 13.3333 12.139 13.3333 10.6663V4.66634H2.66659V10.6663C2.66659 12.139 3.86059 13.333 5.33325 13.333Z" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Browse Rooms
                        </a>
                        <a href="{{ route('user.booking.wizard') }}#wizardMethodSection" class="btn-new-booking">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M8 3.33301V12.6663" stroke="white" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M3.33325 8H12.6666" stroke="white" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            New Booking
                        </a>
                    </div>
                </div>
                <div class="hero-stats">
                    <div class="stat-item">
                        <p class="stat-value stat-pending">{{ $bookingStats['pending'] ?? 0 }}</p>
                        <p class="stat-label">Pending approvals</p>
                    </div>
                    <div class="stat-item">
                        <p class="stat-value stat-confirmed">{{ $bookingStats['confirmed'] ?? 0 }}</p>
                        <p class="stat-label">Confirmed today</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-grid">
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
                    <div class="badge-count">{{ count($upcomingBookingsCards) }}</div>
                </div>
                @if(count($upcomingBookingsCards) > 0)
                    @foreach($upcomingBookingsCards as $upcomingCard)
                        <div class="upcoming-booking" style="border-bottom: 1px solid #e5e7eb; padding-bottom: 16px; margin-bottom: 16px;">
                            <p class="upcoming-date">{{ $upcomingCard['date'] }}</p>
                            <h4 class="upcoming-title">{{ $upcomingCard['facility'] }}</h4>
                            <p class="upcoming-purpose">{{ $upcomingCard['purpose'] }}</p>
                            <p class="upcoming-meta">
                                <span>{{ $upcomingCard['time'] }}</span>
                                @if(!empty($upcomingCard['location']))
                                    <span aria-hidden="true">•</span>
                                    <span>{{ $upcomingCard['location'] }}</span>
                                @endif
                            </p>
                        </div>
                    @endforeach
                @else
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
                @endif
            </div>

            <section id="dashboardBookingsPanel" class="dashboard-bookings-panel is-visible" data-panel-state="visible" aria-live="polite">
                @if(!empty($dashboardBookings))
                    @include('partials.my-bookings-card', $dashboardBookings)
                @endif
            </section>
        </div>
    </div>
</section>
@endsection
