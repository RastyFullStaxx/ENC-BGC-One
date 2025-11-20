<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingApproval;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminApprovalController extends Controller
{
    public function __construct(private AuditLogger $auditLogger)
    {
    }

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
        $booking->skip_audit_observer = true;
        $beforeStatus = $booking->status;

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

        $this->auditLogger->log([
            'action' => 'Admin ' . $data['action'] . 'd booking',
            'module' => 'Approvals',
            'target' => $booking->reference ?? $booking->id,
            'action_type' => $data['action'],
            'risk' => $data['action'] === 'reject' ? 'medium' : 'low',
            'status' => 'success',
            'notes' => $data['notes'] ?? null,
            'before' => ['status' => $beforeStatus],
            'after' => ['status' => $newStatus],
            'changes' => ["Status {$beforeStatus} → {$newStatus}"],
        ]);

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
