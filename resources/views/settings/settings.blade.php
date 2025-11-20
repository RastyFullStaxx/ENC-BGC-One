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
              <div class="d-flex align-items-center gap-3">
                <div class="account-avatar d-inline-flex align-items-center justify-content-center">
                  {{ $firstInitial }}
                </div>
                <div>
                  <div class="fw-semibold">{{ $fullName }}</div>
                  <div class="small text-muted">{{ $roleLabel }}</div>
                </div>
              </div>

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
            @if (session('status'))
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            @endif
            @if ($errors->any())
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="fw-semibold">Please fix the highlighted fields.</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            @endif

            <form class="account-form" method="POST" action="{{ route('user.settings.update') }}">
              @csrf
              @method('PUT')

              <div class="account-section" data-edit-section="account">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                  <h5 class="mb-0">Account Information</h5>
                  <div class="d-flex gap-2 align-items-center">
                    <button class="btn btn-outline-primary btn-sm" type="button" data-edit-trigger="account">Edit</button>
                    <div class="d-flex gap-2 align-items-center d-none" data-edit-actions="account">
                      <button class="btn btn-light btn-sm" type="reset" data-edit-cancel="account">Cancel</button>
                      <button class="btn btn-primary btn-sm" type="submit" data-edit-save="account">Save</button>
                    </div>
                  </div>
                </div>
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label" for="settingsName">Full name</label>
                    <input
                      type="text"
                      id="settingsName"
                      name="name"
                      class="form-control @error('name') is-invalid @enderror"
                      value="{{ old('name', $fullName) }}"
                      autocomplete="name"
                      required
                      disabled
                      data-editable="account"
                    >
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="settingsEmail">Email</label>
                    <input
                      type="email"
                      id="settingsEmail"
                      name="email"
                      class="form-control @error('email') is-invalid @enderror"
                      value="{{ old('email', $email) }}"
                      autocomplete="email"
                      required
                      disabled
                      data-editable="account"
                    >
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="settingsPhone">Mobile</label>
                    <input
                      type="tel"
                      id="settingsPhone"
                      name="phone"
                      class="form-control @error('phone') is-invalid @enderror"
                      value="{{ old('phone', $phone) }}"
                      placeholder="Add a mobile number"
                      autocomplete="tel"
                      disabled
                      data-editable="account"
                    >
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="settingsRole">Role</label>
                    <input type="text" id="settingsRole" class="form-control" value="{{ $roleLabel }}" disabled>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="settingsDepartment">Department</label>
                    <input type="text" id="settingsDepartment" class="form-control" value="{{ $department }}" disabled>
                  </div>
                </div>
              </div>

              <div class="account-section" data-edit-section="security">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                  <h5 class="mb-0">Security</h5>
                  <div class="d-flex gap-2 align-items-center">
                    <button class="btn btn-outline-secondary btn-sm" type="button" data-edit-trigger="security">Edit</button>
                    <div class="d-flex gap-2 align-items-center d-none" data-edit-actions="security">
                      <button class="btn btn-light btn-sm" type="reset" data-edit-cancel="security">Cancel</button>
                      <button class="btn btn-secondary btn-sm" type="submit" data-edit-save="security">Update</button>
                    </div>
                  </div>
                </div>
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label" for="settingsCurrentPassword">Current password</label>
                    <input
                      type="password"
                      id="settingsCurrentPassword"
                      name="current_password"
                      class="form-control @error('current_password') is-invalid @enderror"
                      autocomplete="current-password"
                      disabled
                      data-editable="security"
                    >
                    @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="settingsNewPassword">New password</label>
                    <input
                      type="password"
                      id="settingsNewPassword"
                      name="new_password"
                      class="form-control @error('new_password') is-invalid @enderror"
                      autocomplete="new-password"
                      disabled
                      data-editable="security"
                    >
                    @error('new_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="settingsNewPasswordConfirmation">Confirm new password</label>
                    <input
                      type="password"
                      id="settingsNewPasswordConfirmation"
                      name="new_password_confirmation"
                      class="form-control"
                      autocomplete="new-password"
                      disabled
                      data-editable="security"
                    >
                  </div>
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
                </div>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    (() => {
      const successMessage = @json(session('status'));
      const errors = @json($errors->all());

      if (successMessage) {
        Swal.fire({
          icon: 'success',
          title: 'Saved',
          text: successMessage,
          confirmButtonColor: '#155dfc'
        });
      } else if (errors && errors.length) {
        Swal.fire({
          icon: 'error',
          title: 'Check your inputs',
          text: errors[0],
          confirmButtonColor: '#dc2626'
        });
      }

      const enableSection = (key, enable) => {
        const section = document.querySelector(`[data-edit-section=\"${key}\"]`);
        if (!section) return;
        const editables = section.querySelectorAll(`[data-editable=\"${key}\"]`);
        editables.forEach((el) => {
          el.disabled = !enable;
          if (!enable && el.type === 'password') el.value = '';
        });

        const actions = section.querySelector(`[data-edit-actions=\"${key}\"]`);
        const trigger = section.querySelector(`[data-edit-trigger=\"${key}\"]`);
        if (actions && trigger) {
          actions.classList.toggle('d-none', !enable);
          trigger.classList.toggle('d-none', enable);
        }
      };

      ['account', 'security'].forEach((key) => {
        const trigger = document.querySelector(`[data-edit-trigger=\"${key}\"]`);
        const cancel = document.querySelector(`[data-edit-cancel=\"${key}\"]`);
        const form = document.querySelector('form.account-form');

        trigger?.addEventListener('click', () => enableSection(key, true));
        cancel?.addEventListener('click', () => {
          form?.reset();
          enableSection(key, false);
        });

        enableSection(key, false);
      });
    })();
  </script>
@endpush
