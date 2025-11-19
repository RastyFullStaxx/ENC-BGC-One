<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingApproval;
use App\Models\Facility;
use App\Models\NotificationLog;
use App\Models\User;
use Illuminate\Support\Carbon;

class AdminHubController extends Controller
{
    public function index()
    {
        $facilitiesOnline = Facility::where('status', 'Available')->count();
        $totalFacilities = Facility::count() ?: 1;
        $bookingsToday = Booking::whereDate('date', today())->count();

        $utilization = min(100, round(($bookingsToday / $totalFacilities) * 100));
        $avgSla = $this->averageSlaMinutes();
        $incidents = Facility::where('status', '!=', 'Available')
            ->limit(4)
            ->get()
            ->map(fn ($facility) => [
                'name' => $facility->name,
                'status' => $facility->status,
                'note' => $facility->room_number,
            ])
            ->values();

        $staffing = User::where('role', 'admin')
            ->with('department')
            ->get()
            ->map(fn ($user) => [
                'name' => $user->name,
                'department' => optional($user->department)->name ?? 'Operations',
            ])
            ->values();

        $policyUpdates = NotificationLog::with('booking')
            ->where('recipient_role', 'admin')
            ->latest('created_at')
            ->limit(4)
            ->get()
            ->map(function (NotificationLog $log) {
                $booking = $log->booking;
                return [
                    'title' => strtoupper($log->channel) . ' notification',
                    'meta' => $booking
                        ? optional($booking->facility)->name . ' · ' . Carbon::parse($booking->date)->format('M j')
                        : $log->created_at?->format('M j · g:i A'),
                ];
            })
            ->values();

        $slaTrend = Booking::with('approval')
            ->whereHas('approval')
            ->latest('created_at')
            ->limit(5)
            ->get()
            ->map(function (Booking $booking) {
                $approval = $booking->approval;
                if (! $approval || ! $approval->updated_at) {
                    return 40;
                }

                return min(100, max(10, $approval->created_at->diffInMinutes($approval->updated_at)));
            })
            ->values();

        $adminUser = auth()->user();
        $notificationsCount = NotificationLog::forRecipient($adminUser)->count();

        return view('admin.admin-hub', [
            'user' => $adminUser,
            'utilization' => $utilization,
            'avgSla' => $avgSla,
            'incidentsCount' => $incidents->count(),
            'incidentItems' => $incidents,
            'staffing' => $staffing,
            'policyUpdates' => $policyUpdates,
            'slaTrend' => $slaTrend,
            'approversOnDuty' => $staffing->count(),
            'facilitiesOnline' => $facilitiesOnline,
            'totalFacilities' => $totalFacilities,
            'notificationsCount' => $notificationsCount,
        ]);
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
