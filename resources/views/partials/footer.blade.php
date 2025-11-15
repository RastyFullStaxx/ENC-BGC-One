{{-- resources/views/partials/footer.blade.php --}}
<footer class="site-footer-alt footer-slide" role="contentinfo">
  <div class="container">
    <div class="footer-top">
      <div class="footer-column footer-brand">
        <span class="footer-brand-mark" aria-hidden="true">ENC</span>
        <div>
          <strong>ENC Booking</strong>
          <p>Shared services for meeting rooms, facilities, and shuttle support.</p>
        </div>
      </div>
      <div class="footer-column">
          <strong>Explore</strong>
          <ul>
            <li><a href="{{ route('booking.wizard') }}">Booking wizard</a></li>
            <li><a href="{{ route('facilities.catalog') }}">Facility catalog</a></li>
            <li><a href="{{ route('faq') }}">Help &amp; FAQ</a></li>
          </ul>
      </div>
      <div class="footer-column">
          <strong>Accounts</strong>
          <ul>
            <li><a href="{{ route('login') }}">Log in</a></li>
            <li><a href="{{ route('signup.index') }}">Create account</a></li>
            <li><a href="{{ url('/rooms') }}">Check availability</a></li>
          </ul>
      </div>
      <div class="footer-column">
          <strong>Support</strong>
          <ul>
            <li><a href="mailto:support@enc-booking.com">support@enc-booking.com</a></li>
            <li><span>+1 (555) 123-4567</span></li>
          </ul>
      </div>
      <div class="footer-column">
          <strong>Teams &amp; policy</strong>
          <ul>
            <li><a href="{{ url('/support/facilities') }}">Facilities team</a></li>
            <li><a href="{{ url('/support/transport') }}">Transport team</a></li>
            <li><a href="{{ url('/privacy') }}">Privacy &amp; terms</a></li>
          </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© {{ date('Y') }} ENC Booking Portal. All rights reserved.</span>
      <span>Employee Resource Management System</span>
    </div>
  </div>
</footer>
