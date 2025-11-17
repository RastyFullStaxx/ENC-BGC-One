<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\NotificationLog;
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
                'viewUrl' => route('user.booking.show', $booking),
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

        // Get notification count
        $notificationsCount = NotificationLog::whereHas('booking', function ($query) use ($user) {
            $query->where('requester_id', $user->id);
        })->count();

        return view('user.booking.index', [
            'bookingsData' => $bookingsData,
            'bookingStats' => $bookingStats,
            'totalBookings' => $bookingsCollection->count(),
            'notificationsCount' => $notificationsCount,
        ]);
    }

    public function show(Booking $booking)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        abort_unless($booking->requester_id === $user->id, 403);

        $booking->load(['facility.building', 'details', 'equipment.equipment']);

        $status = $this->normalizeStatus($booking->status ?? 'pending');
        $statusTone = $this->statusTone($status);

        $bookingSummary = [
            'id' => $booking->id,
            'reference' => $booking->reference_code ?? 'Pending Reference',
            'status' => $status,
            'status_label' => ucfirst($status),
            'status_tone' => $statusTone,
            'date_label' => $booking->date
                ? Carbon::parse($booking->date, 'Asia/Manila')->format('l, F j, Y')
                : 'Date to be confirmed',
            'time_range' => $this->formatTimeRange($booking),
            'duration' => $this->formatDuration($booking),
            'created_at' => $booking->created_at
                ? Carbon::parse($booking->created_at)->timezone('Asia/Manila')->format('M d, Y · g:i A')
                : null,
            'facility' => [
                'name' => $booking->facility->name ?? 'Facility',
                'type' => $booking->facility->type ?? 'Room',
                'location' => $this->formatLocation($booking),
                'capacity' => $booking->facility->capacity ?? null,
            ],
            'purpose' => $booking->details->purpose ?? 'No agenda provided',
            'attendees' => $booking->details->attendees_count ?? null,
            'notes' => $booking->details->additional_notes ?? null,
            'sfi_support' => [
                'enabled' => (bool) ($booking->details->sfi_support ?? false),
                'count' => $booking->details->sfi_count ?? null,
            ],
            'equipment' => $booking->equipment->map(function ($item) {
                return [
                    'name' => $item->equipment->name ?? 'Equipment',
                    'quantity' => $item->quantity ?? 1,
                ];
            })->filter(fn ($item) => !empty($item['name']))->values()->toArray(),
        ];

        $bookingsTotal = Booking::where('requester_id', $user->id)->count();

        // Get notification count
        $notificationsCount = NotificationLog::whereHas('booking', function ($query) use ($user) {
            $query->where('requester_id', $user->id);
        })->count();

        return view('user.booking.show', [
            'bookingSummary' => $bookingSummary,
            'bookingsCount' => $bookingsTotal,
            'notificationsCount' => $notificationsCount,
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

    private function formatDuration(Booking $booking): ?string
    {
        if (!$booking->start_at || !$booking->end_at) {
            return null;
        }

        $start = Carbon::parse($booking->start_at, 'Asia/Manila');
        $end = Carbon::parse($booking->end_at, 'Asia/Manila');
        $minutes = $start->diffInMinutes($end);

        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        $parts = [];
        if ($hours > 0) {
            $parts[] = $hours . ' hr' . ($hours > 1 ? 's' : '');
        }
        if ($remainingMinutes > 0) {
            $parts[] = $remainingMinutes . ' min';
        }

        return implode(' ', $parts);
    }

    private function normalizeStatus(?string $status): string
    {
        $normalized = strtolower($status ?? 'pending');

        if ($normalized === 'approved') {
            return 'confirmed';
        }

        return $normalized;
    }

    private function statusTone(string $status): string
    {
        return match ($status) {
            'confirmed', 'completed' => 'is-success',
            'cancelled' => 'is-danger',
            'pending' => 'is-warning',
            default => 'is-neutral',
        };
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
}
