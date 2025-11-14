{{-- resources/views/partials/footer.blade.php --}}
<footer class="py-4 site-footer footer-slide bg-white" role="contentinfo">
  <div class="container">
    <div class="row gy-4">

      {{-- Brand + blurb --}}
      <div class="col-12 col-md-6 col-lg-4">
        <div class="d-flex align-items-start gap-2 mb-2">
          <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary text-white"
                style="width:32px;height:32px;">
            {{-- calendar icon --}}
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <rect x="3" y="4" width="18" height="17" rx="3" stroke="currentColor" stroke-width="1.6"/>
              <path d="M8 2v4M16 2v4M3 9h18" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
            </svg>
          </span>
          <div class="fw-semibold">ENC Booking</div>
        </div>
        <p class="text-secondary mb-0" style="max-width: 320px;">
          Your one-stop shop for all ENC resource bookings and facility management.
        </p>
      </div>

      {{-- Quick Links --}}
      <div class="col-6 col-md-3 col-lg-2">
        <div class="fw-semibold mb-2">Quick Links</div>
        <ul class="list-unstyled mb-0 small">
          <li class="mb-1">
            <a class="link-secondary link-underline-opacity-0 link-underline-opacity-75-hover" href="{{ route('login') }}">Login</a>
          </li>
          <li class="mb-1">
            <a class="link-secondary link-underline-opacity-0 link-underline-opacity-75-hover" href="{{ route('signup.index') }}">Sign Up</a>
          </li>
          <li class="mb-1">
            <a class="link-secondary link-underline-opacity-0 link-underline-opacity-75-hover" href="{{ url('/help') }}">Help &amp; FAQ</a>
          </li>
          <li>
            <a class="link-secondary link-underline-opacity-0 link-underline-opacity-75-hover" href="{{ url('/rooms') }}">
              Check Availability
            </a>
          </li>
        </ul>
      </div>

      {{-- Contact Us --}}
      <div class="col-6 col-md-3 col-lg-3">
        <div class="fw-semibold mb-2">Contact Us</div>
        <ul class="list-unstyled mb-0 small text-secondary">
          <li class="mb-1 d-flex align-items-start gap-2">
            {{-- mail --}}
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" class="text-secondary flex-shrink-0" aria-hidden="true">
              <path d="M4 6h16v12H4z" stroke="currentColor" stroke-width="1.6" fill="none" />
              <path d="M4 7l8 6 8-6" stroke="currentColor" stroke-width="1.6" fill="none" />
            </svg>
            <a class="link-secondary link-underline-opacity-0 link-underline-opacity-75-hover" href="mailto:support@enc-booking.com">
              support@enc-booking.com
            </a>
          </li>
          <li class="mb-1 d-flex align-items-start gap-2">
            {{-- phone --}}
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" class="text-secondary flex-shrink-0" aria-hidden="true">
              <path d="M6 3h3l2 5-2 1a12 12 0 006 6l1-2 5 2v3a2 2 0 01-2 2 16 16 0 01-14-14 2 2 0 012-2z"
                    stroke="currentColor" stroke-width="1.6" fill="none" stroke-linejoin="round"/>
            </svg>
            <span>+1 (555) 123-4567</span>
          </li>
          <li class="d-flex align-items-start gap-2">
            {{-- location --}}
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" class="text-secondary flex-shrink-0" aria-hidden="true">
              <path d="M12 22s7-6.5 7-12a7 7 0 10-14 0c0 5.5 7 12 7 12z" stroke="currentColor" stroke-width="1.6" fill="none"/>
              <circle cx="12" cy="10" r="2.5" stroke="currentColor" stroke-width="1.6" fill="none"/>
            </svg>
            <span>IT Support Desk, Building 1</span>
          </li>
        </ul>
      </div>

      {{-- Support --}}
      <div class="col-12 col-lg-3">
        <div class="fw-semibold mb-2">Support</div>
        <ul class="list-unstyled mb-0 small">
          <li class="mb-1"><a class="link-secondary link-underline-opacity-0 link-underline-opacity-75-hover" href="{{ url('/support/it') }}">IT Support</a></li>
          <li class="mb-1"><a class="link-secondary link-underline-opacity-0 link-underline-opacity-75-hover" href="{{ url('/support/facilities') }}">Facilities Team</a></li>
          <li class="mb-1"><a class="link-secondary link-underline-opacity-0 link-underline-opacity-75-hover" href="{{ url('/support/transport') }}">Transport Team</a></li>
          <li class="d-flex align-items-center gap-2">
            {{-- shield/privacy icon --}}
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" class="text-secondary flex-shrink-0" aria-hidden="true">
              <path d="M12 3l8 3v6c0 5-3.5 7.5-8 9-4.5-1.5-8-4-8-9V6l8-3z" stroke="currentColor" stroke-width="1.6" fill="none"/>
            </svg>
            <a class="link-secondary link-underline-opacity-0 link-underline-opacity-75-hover" href="{{ url('/privacy') }}">Privacy Policy</a>
          </li>
        </ul>
      </div>

    </div>

    <br>

    <p class="text-center text-secondary small mb-0">
      © {{ date('Y') }} ENC Booking Portal. All rights reserved. | Employee Resource Management System
    </p>
  </div>
</footer>
