@extends('layouts.app')

@section('title', 'My Profile — ONE Services')

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
  $phone = $user->phone ?? 'Not provided';
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
          <span class="badge rounded-pill mb-3">PROFILE CENTER</span>
          <h1 class="display-6 mb-2">Hello, {{ $fullName }}</h1>
          <p class="mb-0 text-white-50">Review your account details and where you have access.</p>
        </div>
        <div class="account-nav-pills">
          <a href="{{ route('user.profile') }}" class="active">Profile</a>
          <a href="{{ route('user.settings') }}">Settings</a>
        </div>
      </div>
    </div>
  </section>

  <section class="account-shell">
    <div class="container">
      <div class="account-card p-4 p-lg-5">
        <div class="row g-4">
          <div class="col-lg-4 account-sidebar pb-4 pb-lg-0">
            <div class="d-flex flex-column align-items-center text-center text-lg-start">
              <div class="account-avatar d-inline-flex align-items-center justify-content-center mb-3">
                {{ $firstInitial }}
              </div>
              <h4 class="mb-1">{{ $fullName }}</h4>
              <p class="text-muted mb-3">{{ $roleLabel }} @if($department !== 'Not assigned') · {{ $department }} @endif</p>
            </div>

            <div class="vstack gap-3">
              <div>
                <p class="text-uppercase small fw-semibold text-muted mb-2">Contact</p>
                <ul class="list-unstyled small mb-0">
                  <li class="mb-1">
                    <strong>Email:</strong> {{ $email }}
                  </li>
                  <li class="mb-1">
                    <strong>Mobile:</strong> {{ $phone }}
                  </li>
                  <li class="mb-1">
                    <strong>Department:</strong> {{ $department }}
                  </li>
                </ul>
              </div>

              <div class="account-section pt-3">
                <p class="text-uppercase small fw-semibold text-muted mb-2">Quick links</p>
                <div class="vstack gap-2">
                  @if ($isAdmin)
                    <a class="btn btn-outline-primary w-100" href="{{ route('admin.dashboard') }}">Admin dashboard</a>
                    <a class="btn btn-outline-secondary w-100" href="{{ route('admin.users') }}">Manage users</a>
                    <a class="btn btn-outline-secondary w-100" href="{{ route('admin.approvals.queue') }}">Review approvals</a>
                  @else
                    <a class="btn btn-outline-primary w-100" href="{{ route('user.dashboard') }}">User dashboard</a>
                    <a class="btn btn-outline-secondary w-100" href="{{ route('user.booking.index') }}">View bookings</a>
                    <a class="btn btn-outline-secondary w-100" href="{{ route('user.booking.wizard') }}">Start a booking</a>
                  @endif
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-8">
            <div class="account-section">
              <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
                <h5 class="mb-0">Account overview</h5>
                <a class="btn btn-outline-primary btn-sm" href="{{ route('user.settings') }}">Edit details</a>
              </div>
              <ul class="account-list">
                <li class="d-flex justify-content-between flex-wrap gap-2">
                  <div>
                    <div class="fw-semibold">Role & access</div>
                    <div class="small text-muted">What you can do in ONE Services</div>
                  </div>
                  <span class="badge text-bg-light text-primary">{{ $roleLabel }}</span>
                </li>
                <li class="d-flex justify-content-between flex-wrap gap-2">
                  <div>
                    <div class="fw-semibold">Department</div>
                    <div class="small text-muted">Org unit for routing approvals</div>
                  </div>
                  <span class="text-muted">{{ $department }}</span>
                </li>
                <li class="d-flex justify-content-between flex-wrap gap-2">
                  <div>
                    <div class="fw-semibold">Status</div>
                    <div class="small text-muted">Active / suspended accounts</div>
                  </div>
                  <span class="badge text-bg-light">{{ $status }}</span>
                </li>
                <li class="d-flex justify-content-between flex-wrap gap-2">
                  <div>
                    <div class="fw-semibold">Member since</div>
                    <div class="small text-muted">Date you joined the workspace</div>
                  </div>
                  <span class="text-muted">{{ $memberSince }}</span>
                </li>
                <li class="d-flex justify-content-between flex-wrap gap-2">
                  <div>
                    <div class="fw-semibold">Last login</div>
                    <div class="small text-muted">Most recent access time</div>
                  </div>
                  <span class="text-muted">{{ $lastLogin }}</span>
                </li>
              </ul>
            </div>

            @if ($isAdmin)
              <div class="account-section">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
                  <h5 class="mb-0">Admin tools</h5>
                  <a class="btn btn-link btn-sm" href="{{ route('admin.hub') }}">Open admin hub</a>
                </div>
                <div class="row g-3">
                  <div class="col-md-6">
                    <div class="account-stat h-100">
                      <div class="fw-semibold mb-1">User management</div>
                      <div class="small text-muted">Invite, activate, or suspend accounts.</div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="account-stat h-100">
                      <div class="fw-semibold mb-1">Approvals & audit</div>
                      <div class="small text-muted">Review bookings and audit trails.</div>
                    </div>
                  </div>
                </div>
              </div>
            @else
              <div class="account-section">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
                  <h5 class="mb-0">Bookings</h5>
                  <a class="btn btn-link btn-sm" href="{{ route('user.booking.index') }}">See my bookings</a>
                </div>
                <div class="row g-3">
                  <div class="col-md-6">
                    <div class="account-stat h-100">
                      <div class="fw-semibold mb-1">Upcoming reservations</div>
                      <div class="small text-muted">Track your confirmed rooms.</div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="account-stat h-100">
                      <div class="fw-semibold mb-1">Start a booking</div>
                      <div class="small text-muted">Use the wizard to request space.</div>
                    </div>
                  </div>
                </div>
              </div>
            @endif

          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- Footer is already included in layouts.app -->
@endsection
