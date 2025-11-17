<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $baseQuery = Booking::with(['facility.building', 'details'])
            ->where('requester_id', $user->id);

        $totalBookings    = (clone $baseQuery)->count();
        $pendingCount     = (clone $baseQuery)->where('status', 'pending')->count();
        $confirmedCount   = (clone $baseQuery)->whereIn('status', ['approved', 'confirmed'])->count();
        $cancelledCount   = (clone $baseQuery)->where('status', 'cancelled')->count();

        $recentBookings = (clone $baseQuery)
            ->latest('date')
            ->latest('start_at')
            ->take(5)
            ->get();

        $upcomingBookings = (clone $baseQuery)
            ->whereDate('date', '>=', Carbon::today('Asia/Manila'))
            ->whereIn('status', ['pending', 'approved'])
            ->orderBy('date')
            ->orderBy('start_at')
            ->take(10)
            ->get();

        $dashboardBookings = [
            'title' => 'My Bookings',
            'eyebrow' => 'Bookings snapshot',
            'subtitle' => 'Monitor requests and keep your schedule aligned with ENC services.',
            'totalBookings' => $totalBookings,
            'tabs' => [
                ['key' => 'pending',   'label' => 'Pending',   'count' => $pendingCount],
                ['key' => 'confirmed', 'label' => 'Confirmed', 'count' => $confirmedCount],
                ['key' => 'cancelled', 'label' => 'Cancelled', 'count' => $cancelledCount],
            ],
            'bookings' => $recentBookings->map(fn ($booking) => $this->formatSnapshotBooking($booking))->all(),
        ];

        $upcomingBookingsCards = $upcomingBookings->map(function($booking) {
            return [
                'date'     => Carbon::parse($booking->date, 'Asia/Manila')->format('D, M j, Y'),
                'time'     => $this->formatTimeRange($booking),
                'facility' => $booking->facility->name ?? 'Facility',
                'purpose'  => $booking->details->purpose ?? 'Scheduled booking',
                'location' => $this->formatLocation($booking),
            ];
        })->toArray();

        $bookingStats = [
            'pending'   => $pendingCount,
            'confirmed' => $confirmedCount,
            'cancelled' => $cancelledCount,
            'total'     => $totalBookings,
        ];

        return view('user.dashboard', [
            'dashboardBookings'   => $dashboardBookings,
            'bookingStats'        => $bookingStats,
            'upcomingBookingsCards' => $upcomingBookingsCards,
        ]);
    }

    private function formatSnapshotBooking(Booking $booking): array
    {
        $facilityName = $booking->facility->name ?? 'Facility';
        $purpose = $booking->details->purpose ?? 'Booking request';
        $status = $this->normalizeStatus($booking->status ?? 'pending');

        return [
            'status' => $status,
            'status_label' => ucfirst($status),
            'date' => $booking->date
                ? Carbon::parse($booking->date, 'Asia/Manila')->format('D, M j')
                : 'TBD',
            'title' => $facilityName . ' · ' . $purpose,
            'time' => $this->formatTimeRange($booking),
            'location' => $this->formatLocation($booking),
        ];
    }

    private function formatTimeRange(Booking $booking): string
    {
        $start = $booking->start_at
            ? Carbon::parse($booking->start_at, 'Asia/Manila')->format('g:i A')
            : 'TBA';
        $end = $booking->end_at
            ? Carbon::parse($booking->end_at, 'Asia/Manila')->format('g:i A')
            : 'TBA';

        return $start . ' – ' . $end;
    }

    private function formatLocation(Booking $booking): string
    {
        $parts = [];
        if ($booking->facility?->building?->name) {
            $parts[] = $booking->facility->building->name;
        }
        if ($booking->facility?->floor) {
            $parts[] = ucfirst($booking->facility->floor) . ' Floor';
        }

        return implode(' · ', $parts);
    }

    private function normalizeStatus(?string $status): string
    {
        $normalized = strtolower($status ?? 'pending');

        if ($normalized === 'approved') {
            return 'confirmed';
        }

        return $normalized;
    }
}
