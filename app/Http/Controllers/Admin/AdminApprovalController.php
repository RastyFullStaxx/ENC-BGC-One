<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingApproval;
use App\Models\BookingChangeRequest;
use App\Models\NotificationLog;
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

        $heroStats = [
            'approvalsToday' => Booking::where('status', 'approved')->count(),
            'avgSla' => $this->averageSlaMinutes(),
            'openIncidents' => Booking::where('status', 'rejected')->count(),
        ];

        return view('admin.approvals.queue', [
            'user' => auth()->user(),
            'bookings' => $bookings,
            'queueStats' => $queueStats,
            'latestPending' => $latestPending,
            'statusFilter' => $statusFilter,
            'availableStatuses' => $statuses,
            'heroStats' => $heroStats,
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

    public function decide(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'action' => 'required|in:approve,reject,changes',
            'notes' => 'nullable|string|max:1000',
            'redirect' => 'nullable|url',
        ]);

        $statusMap = [
            'approve' => 'approved',
            'reject' => 'rejected',
            'changes' => 'pending',
        ];

        $newStatus = $statusMap[$data['action']];
        $booking->status = $newStatus;
        $booking->save();

        $booking->approval()->updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'approved_by' => auth()->id(),
                'status' => $newStatus,
                'remarks' => $data['notes'] ?? ucfirst($data['action']) . ' via admin workspace',
            ]
        );

        $message = match ($data['action']) {
            'approve' => 'Booking approved successfully.',
            'reject' => 'Booking rejected.',
            default => 'Changes requested from requester.',
        };

        if ($data['action'] === 'changes') {
            $booking->changeRequests()
                ->where('requested_by_role', 'admin')
                ->whereIn('status', ['open', 'acknowledged'])
                ->update([
                    'status' => 'resolved',
                    'resolved_at' => now(),
                    'resolved_by' => auth()->id(),
                    'resolution_notes' => 'Superseded by a new admin request',
                ]);

            BookingChangeRequest::create([
                'booking_id' => $booking->id,
                'requested_by' => auth()->id(),
                'requested_by_role' => 'admin',
                'type' => 'adjustment',
                'notes' => $data['notes'] ?? null,
            ]);

            NotificationLog::logEvent($booking, 'change_requested_admin');
        } elseif ($data['action'] === 'approve') {
            NotificationLog::logEvent($booking, 'booking_approved');
        } elseif ($data['action'] === 'reject') {
            NotificationLog::logEvent($booking, 'booking_rejected');
        }

        return redirect($data['redirect'] ?? url()->previous())
            ->with('statusMessage', $message);
    }

    protected function averageSlaMinutes(): int
    {
        $durations = BookingApproval::whereIn('status', ['approved', 'rejected'])
            ->get()
            ->map(function (BookingApproval $approval) {
                if (! $approval->created_at || ! $approval->updated_at) {
                    return null;
                }

                return $approval->created_at->diffInMinutes($approval->updated_at);
            })
            ->filter();

        return $durations->count() ? (int) round($durations->avg()) : 0;
    }
}
