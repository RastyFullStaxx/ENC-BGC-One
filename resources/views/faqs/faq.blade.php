@extends('layouts.app')

@section('app-navbar')
@endsection

@push('styles')
  @vite(['resources/css/wizard/base.css'])
  @vite(['resources/css/profile/account.css'])
  @vite(['resources/css/faqs/faq.css'])
@endpush


@section('content')
@php $faqBackUrl = url()->previous() ?? route('landing'); @endphp
<section class="faq-stage">
    <div class="faq-hub">
        <div class="faq-back">
            <a href="{{ $faqBackUrl }}" class="faq-back-link">
                <svg viewBox="0 0 16 16" fill="none" aria-hidden="true">
                    <path d="M10 12L6 8L10 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M6 8H14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Back
            </a>
        </div>
        <p class="faq-eyebrow">Help Center</p>
        <h1>Frequently Asked Questions</h1>
        <p class="faq-hero-copy">
            Helpful tips for navigating ENC BGC ONE Services—from onboarding to booking rooms, transport, and shared infrastructure without friction.
        </p>

        <div class="faq-stats">
            <div class="faq-stat-chip">Average response time is 1 business day</div>
            <div class="faq-stat-chip">Knowledge base refreshed · {{ now()->format('M d, Y') }}</div>
            <div class="faq-stat-chip">Support hours · 6:00 AM – 8:00 PM</div>
        </div>

        <div class="faq-panel">
            <h2>Top questions</h2>
            <p>Tap an item to reveal the complete walkthrough.</p>

            <div class="faq-accordion" id="faqAccordion">
                <article class="faq-accordion-item">
                    <button class="faq-accordion-trigger" aria-expanded="false">
                        How do I create an account?
                        <svg width="18" height="18" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="1.6">
                            <path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <div class="faq-accordion-content">
                        <p>
                            Visit the ONE Services portal and choose <strong>Create Account</strong>. Use your ENC-issued email, set a password that follows our 12-character policy,
                            then confirm via the verification link we send. Once verified you can log in immediately and personalize your profile.
                        </p>
                    </div>
                </article>

                <article class="faq-accordion-item">
                    <button class="faq-accordion-trigger" aria-expanded="false">
                        I forgot my password. What should I do?
                        <svg width="18" height="18" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="1.6">
                            <path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <div class="faq-accordion-content">
                        <p>
                            Click <strong>Forgot Password</strong> on the login screen, enter your registered email, and follow the reset link.
                            Links remain active for 30 minutes. Set a new password and sign back in—your bookings and preferences stay intact.
                        </p>
                    </div>
                </article>

                <article class="faq-accordion-item">
                    <button class="faq-accordion-trigger" aria-expanded="false">
                        How do I book a meeting room?
                        <svg width="18" height="18" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="1.6">
                            <path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <div class="faq-accordion-content">
                        <p>
                            Go to <strong>Facilities &gt; Meeting Rooms</strong>, pick the room that fits your capacity and equipment needs,
                            choose an open slot, and provide agenda notes plus support requirements. Submit to receive instant confirmation plus a calendar invite.
                        </p>
                    </div>
                </article>

                <article class="faq-accordion-item">
                    <button class="faq-accordion-trigger" aria-expanded="false">
                        How do I book special facilities or infrastructure?
                        <svg width="18" height="18" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="1.6">
                            <path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <div class="faq-accordion-content">
                        <p>
                            In the catalog select <strong>Special Facilities</strong> to browse production studios, labs, and equipment bays.
                            Each listing displays operational notes, lead times, and compliance requirements. Submit the request—our concierge confirms availability within one business day.
                        </p>
                    </div>
                </article>

                <article class="faq-accordion-item">
                    <button class="faq-accordion-trigger" aria-expanded="false">
                        How do I schedule shuttle services?
                        <svg width="18" height="18" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="1.6">
                            <path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <div class="faq-accordion-content">
                        <p>
                            Under <strong>Mobility</strong>, pick your pickup point, destination, passenger count, and preferred window.
                            Real-time seat availability appears instantly. Confirm the trip to receive a QR boarding pass via email and inside the mobile wallet.
                        </p>
                    </div>
                </article>

                <article class="faq-accordion-item">
                    <button class="faq-accordion-trigger" aria-expanded="false">
                        What do booking statuses mean?
                        <svg width="18" height="18" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="1.6">
                            <path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <div class="faq-accordion-content">
                        <p>
                            <strong>Draft</strong> is saved but not submitted. <strong>Pending</strong> awaits concierge review.
                            <strong>Confirmed</strong> is approved and reminders are scheduled.
                            <strong>In Progress</strong> means the activity is underway (e.g., shuttle en route).
                            <strong>Completed</strong> archives the record for reporting and audits.
                        </p>
                    </div>
                </article>

                <article class="faq-accordion-item">
                    <button class="faq-accordion-trigger" aria-expanded="false">
                        How can I view my booking history?
                        <svg width="18" height="18" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="1.6">
                            <path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <div class="faq-accordion-content">
                        <p>
                            Navigate to <strong>My Activities</strong> to filter by service, team, or custom date range.
                            Export the list to CSV for reconciliation or download a PDF digest for leadership updates.
                        </p>
                    </div>
                </article>

                <article class="faq-accordion-item">
                    <button class="faq-accordion-trigger" aria-expanded="false">
                        Can I cancel or modify a booking?
                        <svg width="18" height="18" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="1.6">
                            <path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <div class="faq-accordion-content">
                        <p>
                            Open the booking, select <strong>Adjust or Cancel</strong>, and follow the prompts.
                            Changes made at least two hours before start time process instantly; for urgent edits use the in-app chat to alert our concierge.
                        </p>
                    </div>
                </article>

                <article class="faq-accordion-item">
                    <button class="faq-accordion-trigger" aria-expanded="false">
                        What is the date format used in the system?
                        <svg width="18" height="18" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="1.6">
                            <path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <div class="faq-accordion-content">
                        <p>
                            We follow the ISO-standard <strong>YYYY-MM-DD</strong> format across listings, exports, and calendar feeds
                            to avoid ambiguity when teams collaborate across countries.
                        </p>
                    </div>
                </article>

                <article class="faq-accordion-item">
                    <button class="faq-accordion-trigger" aria-expanded="false">
                        Do I need to be online to access the portal?
                        <svg width="18" height="18" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="1.6">
                            <path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <div class="faq-accordion-content">
                        <p>
                            The web portal requires an active internet connection. The mobile companion app caches boarding passes and past confirmations,
                            allowing offline access—everything syncs once the device reconnects.
                        </p>
                    </div>
                </article>

                <article class="faq-accordion-item">
                    <button class="faq-accordion-trigger" aria-expanded="false">
                        Is my personal information secure?
                        <svg width="18" height="18" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="1.6">
                            <path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <div class="faq-accordion-content">
                        <p>
                            Yes. We use AES-256 encryption for data at rest, TLS 1.3 for data in transit,
                            and role-based access controls linked to your ENC identity to restrict sensitive bookings to authorized teams.
                        </p>
                    </div>
                </article>
            </div>
        </div>

        <div class="faq-contact">
            <h3>Still need help?</h3>
            <p>Our concierge desk can assist with escalations, bespoke setups, or anything that needs a human touch.</p>
            <div class="faq-contact-grid">
                <div class="contact-chip">
                    <strong>Live chat</strong>
                    Open the in-app messenger from 6:00 AM – 8:00 PM (GMT+8) for instant responses.
                </div>
                <div class="contact-chip">
                    <strong>Email</strong>
                    concierge@encbgc.one · We reply within one business day.
                </div>
                <div class="contact-chip">
                    <strong>Hotline</strong>
                    (02) 8899 0001 · Press 3 for Facilities or 4 for Mobility support.
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const items = document.querySelectorAll('.faq-accordion-item');

        items.forEach(item => {
            const trigger = item.querySelector('.faq-accordion-trigger');

            trigger.addEventListener('click', () => {
                const isOpen = item.classList.contains('active');

                items.forEach(other => {
                    other.classList.remove('active');
                    const btn = other.querySelector('.faq-accordion-trigger');
                    if (btn) {
                        btn.setAttribute('aria-expanded', 'false');
                    }
                });

                if (!isOpen) {
                    item.classList.add('active');
                    trigger.setAttribute('aria-expanded', 'true');
                }
            });
        });
    });
</script>

@endsection
