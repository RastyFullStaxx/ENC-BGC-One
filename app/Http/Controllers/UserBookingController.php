<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UserBookingController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $baseQuery = Booking::with(['facility.building', 'details'])
            ->where('requester_id', $user->id);

        $bookingsCollection = (clone $baseQuery)
            ->orderByDesc('date')
            ->orderByDesc('start_at')
            ->get();

        $bookingsData = $bookingsCollection->map(function (Booking $booking) {
            $facilityName = $booking->facility->name ?? 'Facility';
            $purpose = $booking->details->purpose ?? 'Booking request';
            $status = $this->normalizeStatus($booking->status ?? 'pending');

            return [
                'id' => $booking->id,
                'facility' => $facilityName,
                'facilityType' => $booking->facility->type ?? 'Facility',
                'date' => $booking->date
                    ? Carbon::parse($booking->date, 'Asia/Manila')->format('D, M j, Y')
                    : 'Date TBA',
                'time' => $this->formatTimeRange($booking),
                'purpose' => $purpose,
                'status' => $status,
            ];
        })->values();

        $pendingCount   = (clone $baseQuery)->where('status', 'pending')->count();
        $confirmedCount = (clone $baseQuery)->whereIn('status', ['approved', 'confirmed'])->count();
        $cancelledCount = (clone $baseQuery)->where('status', 'cancelled')->count();

        $bookingStats = [
            'pending'   => $pendingCount,
            'confirmed' => $confirmedCount,
            'cancelled' => $cancelledCount,
        ];

        return view('user.booking.index', [
            'bookingsData' => $bookingsData,
            'bookingStats' => $bookingStats,
        ]);
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

    private function normalizeStatus(?string $status): string
    {
        $normalized = strtolower($status ?? 'pending');

        if ($normalized === 'approved') {
            return 'confirmed';
        }

        return $normalized;
    }
}
