<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminApprovalController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->get('status');

        $statuses = ['pending', 'approved', 'rejected', 'cancelled', 'noshow'];
        $bookingsQuery = Booking::with(['facility', 'requester.department', 'details', 'approval'])
            ->latest('created_at');

        if ($statusFilter && in_array($statusFilter, $statuses)) {
            $bookingsQuery->where('status', $statusFilter);
        }

        $bookings = $bookingsQuery->get();

        $queueStats = [
            'pending' => Booking::where('status', 'pending')->count(),
            'breaches' => Booking::where('status', 'pending')
                ->where('created_at', '<', now()->subMinutes(60))
                ->count(),
        ];

        $avgResponse = BookingApproval::whereIn('status', ['approved', 'rejected'])
            ->get()
            ->map(function (BookingApproval $approval) {
                if (! $approval->created_at || ! $approval->updated_at) {
                    return null;
                }

                return $approval->created_at->diffInMinutes($approval->updated_at);
            })
            ->filter();

        $queueStats['avgResponse'] = $avgResponse->count() ? (int) round($avgResponse->avg()) : 0;

        $latestPending = Booking::where('status', 'pending')->latest('created_at')->first();

        return view('admin.approvals.queue', [
            'user' => auth()->user(),
            'bookings' => $bookings,
            'queueStats' => $queueStats,
            'latestPending' => $latestPending,
            'statusFilter' => $statusFilter,
            'availableStatuses' => $statuses,
        ]);
    }

    public function show(Booking $booking)
    {
        $booking->load(['facility', 'requester.department', 'details', 'approval.approver']);

        return view('admin.approvals.show', [
            'user' => auth()->user(),
            'booking' => $booking,
            'detail' => $booking->details,
            'approvalRecord' => $booking->approval,
        ]);
    }
}
