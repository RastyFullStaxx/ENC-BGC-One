<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\UserBookingController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminHubController;
use App\Http\Controllers\Admin\AdminApprovalController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminAnalyticsController;
use App\Http\Controllers\Admin\AdminCalendarController;
use App\Http\Controllers\Admin\AdminAuditController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\FacilityCatalogController;
use App\Http\Controllers\AccountSettingsController;
use App\Http\Controllers\Admin\AdminPolicyController;
use App\Http\Controllers\Admin\AdminFacilityController;


Route::get('/', LandingPageController::class)->name('landing');

// Facility catalog (public for preview)
Route::get('/facilities/catalog', FacilityCatalogController::class)->name('facilities.catalog');

// Help/FAQ - Accessible to all users (guest and authenticated)
Route::get('/faq', function () {
    return view('faqs.faq');
})->name('faq');


// --- Authentication pages ---
// Login pages (guest middleware redirects authenticated users to dashboard)
Route::middleware(['guest'])->group(function () {
    Route::view('/login', 'auth.login.index')->name('login');     
    Route::get('/login/form', [LoginController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
    
    // Signup pages
    Route::view('/signup', 'auth.signup.index')->name('signup.index');
    Route::get('/signup/staff', [SignupController::class, 'showStaffForm'])->name('signup.staff');
    Route::post('/signup/staff', [SignupController::class, 'registerStaff'])->name('signup.staff.submit');
});

// Loading page (accessible to all)
Route::view('/login/loading', 'auth.login.loading')->name('login.loading');

// Logout (requires authentication) - allow GET fallback to avoid stale CSRF
Route::match(['GET', 'POST'], '/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');


// User Pages (Protected - requires authentication and staff role)
Route::middleware(['auth', 'role:staff'])->group(function () {
    // Dashboard
    Route::get('/user/dashboard', \App\Http\Controllers\UserDashboardController::class)->name('user.dashboard');
    
    // List of Bookings
    Route::get('/user/booking', [\App\Http\Controllers\UserBookingController::class, 'index'])->name('user.booking.index');

    // Booking Wizard (must come before {booking} route to avoid route conflict)
    Route::get('/user/booking/wizard', [\App\Http\Controllers\BookingController::class, 'index'])->name('user.booking.wizard');
    Route::get('/user/booking/{booking}/edit', [\App\Http\Controllers\UserBookingController::class, 'edit'])->name('user.booking.edit');
    Route::put('/user/booking/{booking}', [\App\Http\Controllers\UserBookingController::class, 'update'])->name('user.booking.update');
    Route::get('/user/booking/{booking}/request-change', [\App\Http\Controllers\UserBookingController::class, 'requestChangeForm'])->name('user.booking.request-change.form');
    Route::post('/user/booking/{booking}/request-change', [\App\Http\Controllers\UserBookingController::class, 'storeChangeRequest'])->name('user.booking.request-change.store');
    Route::post('/user/booking/{booking}/cancel', [\App\Http\Controllers\UserBookingController::class, 'cancelBooking'])->name('user.booking.cancel');
    Route::post('/user/booking/change-requests/{changeRequest}/acknowledge', [\App\Http\Controllers\UserBookingController::class, 'acknowledgeChangeRequest'])->name('user.booking.change-request.acknowledge');
    Route::get('/user/booking/{booking}', [\App\Http\Controllers\UserBookingController::class, 'show'])->name('user.booking.show');

    // Booking API endpoints
    Route::prefix('api/bookings')->group(function () {
        Route::get('/facilities', [\App\Http\Controllers\BookingController::class, 'getFacilities'])
            ->name('api.bookings.facilities')
            ->withoutMiddleware(['auth', 'role:staff', 'role:admin']);
        Route::post('/check-availability', [\App\Http\Controllers\BookingController::class, 'checkAvailability'])->name('api.bookings.check-availability');
        Route::post('/store', [\App\Http\Controllers\BookingController::class, 'store'])->name('api.bookings.store');
        Route::get('/user-bookings', [\App\Http\Controllers\BookingController::class, 'getUserBookings'])->name('api.bookings.user-bookings');
        Route::get('/{id}', [\App\Http\Controllers\BookingController::class, 'show'])->name('api.bookings.show');
        Route::post('/{id}/cancel', [\App\Http\Controllers\BookingController::class, 'cancel'])->name('api.bookings.cancel');
    });

    // Return Room Capacity for Booking Wizard
    Route::get('/user/booking/wizard/capacities', [\App\Http\Controllers\BookingController::class, 'returnRoomCapacity'])->name('api.bookings.capacities');
});

// Shared Authenticated Pages (accessible to both staff and admin)
Route::middleware(['auth'])->group(function () {
    Route::view('/user/profile', 'profile.profile')->name('user.profile');
    Route::view('/user/settings', 'settings.settings')->name('user.settings');
    Route::put('/user/settings', [AccountSettingsController::class, 'update'])->name('user.settings.update');
});

// Notifications API (returns empty array when not authenticated to avoid redirects)
Route::prefix('api/bookings')->group(function () {
    Route::get('/notifications', [\App\Http\Controllers\BookingController::class, 'getUserNotifications'])
        ->withoutMiddleware(['auth', 'role:staff', 'role:admin'])
        ->name('api.bookings.notifications');
});

// Admin Pages (Protected - requires authentication and admin role)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/hub', [AdminHubController::class, 'index'])->name('admin.hub');
    Route::get('/admin/approvals', [AdminApprovalController::class, 'index'])->name('admin.approvals.queue');
    Route::get('/admin/approvals/{booking}', [AdminApprovalController::class, 'show'])->name('admin.approvals.show');
    Route::post('/admin/approvals/{booking}/decision', [AdminApprovalController::class, 'decide'])->name('admin.approvals.decision');

    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users');
    Route::prefix('/admin/users')->name('admin.users.')->group(function () {
        Route::post('/', [AdminUserController::class, 'store'])->name('store');
        Route::put('/{user}', [AdminUserController::class, 'update'])->name('update');
        Route::post('/{user}/deactivate', [AdminUserController::class, 'deactivate'])->name('deactivate');
        Route::post('/{user}/activate', [AdminUserController::class, 'activate'])->name('activate');
        Route::post('/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('reset');
        Route::post('/bulk/status', [AdminUserController::class, 'bulkStatus'])->name('bulk-status');
    });

    Route::get('/admin/analytics', [AdminAnalyticsController::class, 'index'])->name('admin.analytics');
    Route::get('/admin/analytics/export/csv', [AdminAnalyticsController::class, 'exportCsv'])->name('admin.analytics.export.csv');
    Route::get('/admin/analytics/export/charts', [AdminAnalyticsController::class, 'exportCharts'])->name('admin.analytics.export.charts');
    Route::get('/admin/analytics/export/pdf', [AdminAnalyticsController::class, 'exportPdf'])->name('admin.analytics.export.pdf');

    Route::get('/admin/calendar', [AdminCalendarController::class, 'index'])->name('admin.calendar');
    Route::post('/admin/calendar/block', [AdminCalendarController::class, 'block'])->name('admin.calendar.block');
    Route::get('/admin/calendar/export/ics', [AdminCalendarController::class, 'exportIcs'])->name('admin.calendar.export.ics');

    Route::get('/admin/audit', [AdminAuditController::class, 'index'])->name('admin.audit');
    Route::get('/admin/audit/export/csv', [AdminAuditController::class, 'exportCsv'])->name('admin.audit.export.csv');
    Route::get('/admin/audit/export/json', [AdminAuditController::class, 'exportJson'])->name('admin.audit.export.json');
    Route::get('/admin/audit/{auditLog}/export', [AdminAuditController::class, 'exportEntry'])->name('admin.audit.export.entry');
    Route::post('/admin/audit/{auditLog}/flag', [AdminAuditController::class, 'flag'])->name('admin.audit.flag');

    Route::get('/admin/facilities', [AdminFacilityController::class, 'index'])->name('admin.facilities');
    Route::post('/admin/facilities', [AdminFacilityController::class, 'store'])->name('admin.facilities.store');
    Route::patch('/admin/facilities/{facility}', [AdminFacilityController::class, 'update'])->name('admin.facilities.update');
    Route::match(['patch', 'post', 'get'], '/admin/facilities/{facility}/status', [AdminFacilityController::class, 'toggleStatus'])->name('admin.facilities.status');
    Route::get('/admin/facilities/{facility}', [AdminFacilityController::class, 'show'])->name('admin.facilities.show');

    // Policies
    Route::get('/admin/policies', [AdminPolicyController::class, 'index'])->name('admin.policies');
    Route::post('/admin/policies', [AdminPolicyController::class, 'store'])->name('admin.policies.store');
    Route::put('/admin/policies/{policy}', [AdminPolicyController::class, 'update'])->name('admin.policies.update');
    Route::delete('/admin/policies/{policy}', [AdminPolicyController::class, 'destroy'])->name('admin.policies.destroy');
    Route::post('/admin/policies/{policy}/status', [AdminPolicyController::class, 'setStatus'])->name('admin.policies.status');
    Route::post('/admin/policies/{policy}/rules', [AdminPolicyController::class, 'storeRule'])->name('admin.policies.rules.store');
    Route::put('/admin/policies/rules/{rule}', [AdminPolicyController::class, 'updateRule'])->name('admin.policies.rules.update');
    Route::delete('/admin/policies/rules/{rule}', [AdminPolicyController::class, 'destroyRule'])->name('admin.policies.rules.destroy');
});

// --- Public Booking Preview (for testing without auth) ---
Route::get('/book', function () {
    return view('booking.wizard');
})->name('booking.wizard');
