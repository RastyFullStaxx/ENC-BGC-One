<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\NotificationLog;
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

        $baseQuery = Booking::with(['facility.building', 'details', 'requester'])
            ->where('requester_id', $user->id);

        $monthParam = request('month');
        try {
            $calendarMonth = $monthParam
                ? Carbon::parse($monthParam, 'Asia/Manila')->startOfMonth()
                : Carbon::now('Asia/Manila')->startOfMonth();
        } catch (\Exception $e) {
            $calendarMonth = Carbon::now('Asia/Manila')->startOfMonth();
        }

        $calendarStart = $calendarMonth->copy()->startOfMonth();
        $calendarEnd = $calendarMonth->copy()->endOfMonth();
        $prevMonth = $calendarMonth->copy()->subMonth()->format('Y-m-01');
        $nextMonth = $calendarMonth->copy()->addMonth()->format('Y-m-01');

        $totalBookings    = (clone $baseQuery)->count();
        $pendingCount     = (clone $baseQuery)->where('status', 'pending')->count();
        $confirmedCount   = (clone $baseQuery)->whereIn('status', ['approved', 'confirmed'])->count();
        $cancelledCount   = (clone $baseQuery)->where('status', 'cancelled')->count();

        // Get notification count
        $notificationsCount = NotificationLog::whereHas('booking', function ($query) use ($user) {
            $query->where('requester_id', $user->id);
        })->count();

        $recentBookings = (clone $baseQuery)
            ->latest('date')
            ->latest('start_at')
            ->take(5)
            ->get();

        $approvedCalendarBookings = (clone $baseQuery)
            ->whereBetween('date', [$calendarStart->toDateString(), $calendarEnd->toDateString()])
            ->whereIn('status', ['approved', 'confirmed'])
            ->orderBy('date')
            ->orderBy('start_at')
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

        $calendarEvents = $approvedCalendarBookings->map(function (Booking $booking) {
            return [
                'id' => $booking->id,
                'day' => Carbon::parse($booking->date, 'Asia/Manila')->day,
                'facility' => $booking->facility->name ?? 'Facility',
                'title' => $booking->details->purpose ?? 'Approved booking',
                'status' => $booking->status,
                'date_display' => Carbon::parse($booking->date, 'Asia/Manila')->format('D, M j, Y'),
                'time' => $this->formatTimeRange($booking),
                'requester' => $booking->requester->name ?? 'You',
            ];
        })->groupBy('day');

        $favoriteRooms = (clone $baseQuery)
            ->whereHas('facility')
            ->selectRaw('facility_id, COUNT(*) as total')
            ->groupBy('facility_id')
            ->orderByDesc('total')
            ->with('facility.building')
            ->take(3)
            ->get()
            ->map(function ($booking) {
                return [
                    'name' => $booking->facility->name ?? 'Facility',
                    'capacity' => ($booking->facility->capacity ?? null) ? ($booking->facility->capacity . ' seats') : 'Capacity TBA',
                    'location' => $this->formatFacilityLocation($booking->facility?->building?->name ?? null, $booking->facility?->floor ?? null),
                    'status' => 'Available',
                    'tone' => 'info',
                    'image' => $booking->facility->photo_url ?? 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?q=80&w=1200&auto=format&fit=crop',
                ];
            });

        $bookingStats = [
            'pending'   => $pendingCount,
            'confirmed' => $confirmedCount,
            'cancelled' => $cancelledCount,
            'total'     => $totalBookings,
        ];

        $announcements = [
            ['title' => 'Facility catalog refreshed', 'date' => Carbon::now('Asia/Manila')->format('M j, Y'), 'summary' => 'New media rooms added for Q1 planning.'],
            ['title' => 'Booking SLA update', 'date' => Carbon::now('Asia/Manila')->subDays(2)->format('M j, Y'), 'summary' => 'Approvals now target under 45 minutes during business hours.'],
        ];

        return view('user.dashboard', [
            'dashboardBookings'   => $dashboardBookings,
            'bookingStats'        => $bookingStats,
            'calendarEvents'      => $calendarEvents,
            'calendarMonth'       => $calendarMonth,
            'prevMonth'           => $prevMonth,
            'nextMonth'           => $nextMonth,
            'favoriteRooms'       => $favoriteRooms,
            'announcements'       => $announcements,
            'notificationsCount' => $notificationsCount,
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

    private function formatFacilityLocation(?string $building, ?string $floor): string
    {
        $parts = [];
        if ($building) {
            $parts[] = $building;
        }
        if ($floor) {
            $parts[] = ucfirst($floor) . ' Floor';
        }
        return $parts ? implode(' · ', $parts) : 'Location TBA';
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
