@extends('layouts.app')

@section('title', 'Account Settings — ONE Services')

@push('styles')
  @vite([
    'resources/css/wizard/base.css',
    'resources/css/profile/account.css',
  ])
@endpush
@php
  $user = auth()->user();
  $fullName = $user->name ?? 'User';
  $firstInitial = $fullName ? mb_substr($fullName, 0, 1) : '?';
  $role = $user->role ?? 'staff';
  $isAdmin = $role === 'admin';
  $roleLabel = ucfirst($role);
  $email = $user->email ?? 'Not provided';
  $phone = $user->phone ?? '';
  $department = $user?->department?->name ?? 'Not assigned';
  $status = ucfirst($user->status ?? 'Active');
  $lastLogin = $user?->last_login_at
    ? $user->last_login_at->timezone(config('app.timezone', 'UTC'))->format('M d, Y · h:i A')
    : 'No recent login';
  $memberSince = $user?->created_at
    ? $user->created_at->format('M d, Y')
    : 'N/A';
@endphp

@section('content')
<!-- Include Dashboard Navbar -->
@include('partials.dashboard-navbar', [
    'currentStep' => 0,
    'steps' => [],
    'bookingsCount' => 0,
    'notificationsCount' => 0,
    'userName' => auth()->user()->name ?? 'User',
    'userEmail' => auth()->user()->email ?? 'user@ministry.gov',
    'userRole' => auth()->user()->role ?? 'staff',
    'brand' => 'ONE Services'
])

<div class="account-page">
  <section class="account-hero">
    <div class="container">
      <div class="d-flex flex-column flex-lg-row align-items-lg-end justify-content-lg-between gap-3">
        <div>
          <span class="badge rounded-pill mb-3">SETTINGS</span>
          <h1 class="display-6 mb-2">Tune your account basics.</h1>
          <p class="mb-0 text-white-50">Keep your contact details and access info accurate.</p>
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
                  @if ($isAdmin)
                    <a class="btn btn-outline-secondary w-100" href="{{ route('admin.dashboard') }}">Admin dashboard</a>
                    <a class="btn btn-outline-secondary w-100" href="{{ route('admin.users') }}">Manage users</a>
                  @else
                    <a class="btn btn-outline-secondary w-100" href="{{ route('user.dashboard') }}">User dashboard</a>
                    <a class="btn btn-outline-secondary w-100" href="{{ route('user.booking.index') }}">View bookings</a>
                  @endif
                </div>
              </div>
              <div class="account-section pt-3">
                <p class="text-uppercase small fw-semibold text-muted mb-2">Session security</p>
                <div class="small text-muted mb-2">Last login: {{ $lastLogin }}</div>
                <a class="btn btn-outline-danger btn-sm" href="{{ route('logout') }}">Sign out</a>
              </div>
              <div class="account-section pt-3">
                <p class="text-uppercase small fw-semibold text-muted mb-2">Support</p>
                <p class="small mb-2">Need help? Our concierge team replies in under 30 minutes.</p>
                <a href="mailto:facilities@enc.com" class="btn btn-primary btn-sm">Contact support</a>
              </div>
            </div>
          </div>

          <div class="col-lg-8">
            <div class="alert alert-info small mb-3">
              Saving is disabled here until we wire the backend update endpoints. You can still review what will be editable.
            </div>

            <div class="account-section">
              <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Account Information</h5>
                <button class="btn btn-outline-primary btn-sm" type="button" disabled>Save changes</button>
              </div>
              <form class="account-form row g-3" onsubmit="return false;">
                <div class="col-md-6">
                  <label class="form-label" for="settingsName">Full name</label>
                  <input type="text" id="settingsName" class="form-control" value="{{ $fullName }}" disabled>
                </div>
                <div class="col-md-6">
                  <label class="form-label" for="settingsEmail">Email</label>
                  <input type="email" id="settingsEmail" class="form-control" value="{{ $email }}" disabled>
                </div>
                <div class="col-md-6">
                  <label class="form-label" for="settingsPhone">Mobile</label>
                  <input type="tel" id="settingsPhone" class="form-control" value="{{ $phone }}" placeholder="Add a mobile number" disabled>
                </div>
                <div class="col-md-6">
                  <label class="form-label" for="settingsRole">Role</label>
                  <input type="text" id="settingsRole" class="form-control" value="{{ $roleLabel }}" disabled>
                </div>
                <div class="col-md-6">
                  <label class="form-label" for="settingsDepartment">Department</label>
                  <input type="text" id="settingsDepartment" class="form-control" value="{{ $department }}" disabled>
                </div>
              </form>
            </div>

            <div class="account-section">
              <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Security</h5>
                <button class="btn btn-outline-secondary btn-sm" type="button" disabled>Update password</button>
              </div>
              <div class="account-form row g-3">
                <div class="col-md-6">
                  <label class="form-label">Account status</label>
                  <input type="text" class="form-control" value="{{ $status }}" disabled>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Last login</label>
                  <input type="text" class="form-control" value="{{ $lastLogin }}" disabled>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Member since</label>
                  <input type="text" class="form-control" value="{{ $memberSince }}" disabled>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Email for alerts</label>
                  <input type="text" class="form-control" value="{{ $email }}" disabled>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection
