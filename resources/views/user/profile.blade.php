@extends('layouts.app')

@section('title', 'My Profile — ONE Services')

@push('styles')
  @vite(['resources/css/wizard/base.css'])
  @vite(['resources/css/user/account.css'])
  <style>
    .account-hero {
      margin-top: -2rem;
    }
  </style>
@endpush

@php
  $user = auth()->user();
  $fullName = $user->name ?? 'Alexis Dela Cruz';
  $role     = $user->role ?? 'Operations Lead';
  $email    = $user->email ?? 'alexis.delacruz@enc.ph';
  $phone    = '+63 917 555 0123';
  $location = 'ENC Tower A · 3rd Floor';
  $teams    = ['ENC Facilities', 'Shared Services', 'BGC Ops'];
  $favorites = [
    ['label' => 'Preferred Layout', 'value' => 'Boardroom · 12 pax'],
    ['label' => 'Lead Time',        'value' => '48 hrs'],
    ['label' => 'Support SLA',      'value' => 'Under 30 mins'],
  ];
  $projects = [
    ['name' => 'Monthly All-Hands', 'date' => 'Every 2nd Thursday', 'status' => 'Recurring'],
    ['name' => 'Client Immersion Days', 'date' => 'Next: Apr 18', 'status' => 'Preparation'],
    ['name' => 'Ops Leadership Sync', 'date' => 'Mar 28', 'status' => 'Confirmed'],
  ];
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
          <p class="mb-0 text-white-50">Review your profile, team affiliations, and booking footprint.</p>
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
                {{ substr($fullName, 0, 1) }}
              </div>
              <h4 class="mb-1">{{ $fullName }}</h4>
              <p class="text-muted mb-3">{{ $role }}</p>
              <div class="d-flex flex-wrap gap-2 mb-4">
                @foreach ($teams as $team)
                  <span class="account-chip">{{ $team }}</span>
                @endforeach
              </div>
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
                  <li>
                    <strong>Location:</strong> {{ $location }}
                  </li>
                </ul>
              </div>

              <div class="account-section pt-3">
                <p class="text-uppercase small fw-semibold text-muted mb-2">Quick stats</p>
                <div class="vstack gap-2">
                  <div class="account-stat">
                    <div class="small text-muted">Room bookings YTD</div>
                    <div class="h5 mb-0">36</div>
                  </div>
                  <div class="account-stat">
                    <div class="small text-muted">Avg. approval speed</div>
                    <div class="h5 mb-0">2h 15m</div>
                  </div>
                  <div class="account-stat">
                    <div class="small text-muted">Preferred facilities</div>
                    <div class="mb-0">A-301, A-302, Lab C-401</div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-8">
            <div class="account-section">
              <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
                <h5 class="mb-0">Professional details</h5>
                <button class="btn btn-outline-primary btn-sm">Update</button>
              </div>
              <div class="row g-3">
                @foreach ($favorites as $favorite)
                  <div class="col-sm-6">
                    <div class="account-stat h-100">
                      <div class="text-muted small">{{ $favorite['label'] }}</div>
                      <div class="fw-semibold">{{ $favorite['value'] }}</div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>

            <div class="account-section">
              <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
                <h5 class="mb-0">Recent initiatives</h5>
                <a class="btn btn-link btn-sm" href="{{ route('user.booking.index') }}">See all bookings</a>
              </div>
              <ul class="account-list">
                @foreach ($projects as $project)
                  <li class="d-flex justify-content-between flex-wrap gap-2">
                    <div>
                      <div class="fw-semibold">{{ $project['name'] }}</div>
                      <div class="small text-muted">{{ $project['date'] }}</div>
                    </div>
                    <span class="badge text-bg-light text-primary">{{ $project['status'] }}</span>
                  </li>
                @endforeach
              </ul>
            </div>

            <div class="account-section">
              <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
                <h5 class="mb-0">Delegated approvers</h5>
                <button class="btn btn-outline-secondary btn-sm">Manage delegates</button>
              </div>
              <div class="row g-3">
                <div class="col-md-6">
                  <div class="account-stat h-100">
                    <div class="fw-semibold">Jamie Soriano</div>
                    <div class="small text-muted">Approves bookings up to 15 pax</div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="account-stat h-100">
                    <div class="fw-semibold">Carlos Banzon</div>
                    <div class="small text-muted">Approves weekend events</div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- Footer is already included in layouts.app -->
@endsection
