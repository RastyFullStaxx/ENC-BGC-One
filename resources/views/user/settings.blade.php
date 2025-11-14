@extends('layouts.app')

@section('title', 'Account Settings — ONE Services')

@push('styles')
  @vite(['resources/css/user/account.css'])
@endpush

@php
  $notificationPrefs = [
    ['label' => 'Booking confirmations', 'desc' => 'Send recap once a booking is approved', 'id' => 'notifConfirmation', 'state' => true],
    ['label' => 'Same-day reminders', 'desc' => 'Reminder 2 hours before your meeting', 'id' => 'notifReminder', 'state' => true],
    ['label' => 'Facility alerts', 'desc' => 'Updates when your favorite rooms open up', 'id' => 'notifFacility', 'state' => false],
  ];
@endphp

@section('content')
<div class="account-page">
  <section class="account-hero">
    <div class="container">
      <div class="d-flex flex-column flex-lg-row align-items-lg-end justify-content-lg-between gap-3">
        <div>
          <span class="badge rounded-pill mb-3">SETTINGS</span>
          <h1 class="display-6 mb-2">Control how your workspace behaves.</h1>
          <p class="mb-0 text-white-50">Update account details, security, and notifications from one place.</p>
        </div>
        <div class="account-nav-pills">
          <a href="{{ route('user.profile') }}">Profile</a>
          <a href="{{ route('user.settings') }}" class="active">Settings</a>
        </div>
      </div>
    </div>
  </section>

  <section class="account-shell">
    <div class="container">
      <div class="account-card p-4 p-lg-5">
        <div class="row g-4">
          <div class="col-lg-4 account-sidebar pb-4 pb-lg-0">
            <div class="vstack gap-3">
              <div>
                <p class="text-uppercase small fw-semibold text-muted mb-2">Quick links</p>
                <div class="vstack gap-2">
                  <a class="btn btn-outline-primary w-100" href="{{ route('user.profile') }}">View profile</a>
                  <a class="btn btn-outline-secondary w-100" href="{{ route('user.booking.index') }}">View bookings</a>
                </div>
              </div>
              <div class="account-section pt-3">
                <p class="text-uppercase small fw-semibold text-muted mb-2">Session security</p>
                <div class="small text-muted mb-2">Last password update: Jan 12, 2025</div>
                <button class="btn btn-outline-danger btn-sm">Sign out of all devices</button>
              </div>
              <div class="account-section pt-3">
                <p class="text-uppercase small fw-semibold text-muted mb-2">Support</p>
                <p class="small mb-2">Need help? Our concierge team replies in under 30 minutes.</p>
                <a href="mailto:facilities@enc.com" class="btn btn-primary btn-sm">Contact support</a>
              </div>
            </div>
          </div>

          <div class="col-lg-8">
            <div class="account-section">
              <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Account Information</h5>
                <button class="btn btn-outline-primary btn-sm">Save changes</button>
              </div>
              <form class="account-form row g-3">
                <div class="col-md-6">
                  <label class="form-label" for="settingsName">Full name</label>
                  <input type="text" id="settingsName" class="form-control" value="Alexis Dela Cruz">
                </div>
                <div class="col-md-6">
                  <label class="form-label" for="settingsRole">Role / Title</label>
                  <input type="text" id="settingsRole" class="form-control" value="Operations Lead">
                </div>
                <div class="col-md-6">
                  <label class="form-label" for="settingsEmail">Email</label>
                  <input type="email" id="settingsEmail" class="form-control" value="alexis.delacruz@enc.ph">
                </div>
                <div class="col-md-6">
                  <label class="form-label" for="settingsPhone">Mobile</label>
                  <input type="tel" id="settingsPhone" class="form-control" value="+63 917 555 0123">
                </div>
                <div class="col-12">
                  <label class="form-label" for="settingsLocation">Default location</label>
                  <select id="settingsLocation" class="form-select">
                    <option selected>ENC Tower A · 3rd Floor</option>
                    <option>ENC Tower B · 5th Floor</option>
                    <option>ENC Tower C · Innovation Lab</option>
                  </select>
                </div>
              </form>
            </div>

            <div class="account-section">
              <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Security</h5>
                <button class="btn btn-outline-secondary btn-sm">Update password</button>
              </div>
              <form class="account-form row g-3">
                <div class="col-md-6">
                  <label class="form-label">New password</label>
                  <input type="password" class="form-control" placeholder="••••••••">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Confirm password</label>
                  <input type="password" class="form-control" placeholder="••••••••">
                </div>
                <div class="col-12">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="settingsMfa" checked>
                    <label class="form-check-label" for="settingsMfa">
                      Require MFA for sensitive bookings
                    </label>
                  </div>
                </div>
              </form>
            </div>

            <div class="account-section">
              <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Notifications</h5>
                <button class="btn btn-outline-secondary btn-sm">Reset defaults</button>
              </div>
              <div class="vstack gap-3">
                @foreach ($notificationPrefs as $pref)
                  <div class="account-toggle">
                    <div>
                      <div class="fw-semibold">{{ $pref['label'] }}</div>
                      <small>{{ $pref['desc'] }}</small>
                    </div>
                    <div class="form-check form-switch m-0">
                      <input class="form-check-input" type="checkbox" role="switch" id="{{ $pref['id'] }}" {{ $pref['state'] ? 'checked' : '' }}>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection
