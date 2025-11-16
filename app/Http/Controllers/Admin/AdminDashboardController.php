<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingApproval;
use App\Models\Facility;
use App\Models\User;
use Illuminate\Support\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $pendingApprovals = Booking::where('status', 'pending')->count();
        $approvalsToday = Booking::where('status', 'approved')
            ->whereDate('updated_at', today())
            ->count();
        $resolvedToday = $approvalsToday;

        $waiting = $pendingApprovals;
        $slaBreaches = Booking::where('status', 'pending')
            ->where('created_at', '<', now()->subMinutes(60))
            ->count();

        $rooms = Booking::with(['facility', 'requester.department', 'details'])
            ->where('status', 'approved')
            ->whereDate('date', '>=', today())
            ->orderBy('date')
            ->orderBy('start_at')
            ->limit(4)
            ->get()
            ->map(function (Booking $booking) {
                $start = Carbon::parse($booking->start_at)->format('g:i A');
                $end = Carbon::parse($booking->end_at)->format('g:i A');

                return [
                    'room' => $booking->facility->name ?? 'Facility',
                    'purpose' => optional($booking->details)->purpose ?? 'Booking request',
                    'time' => "{$start} – {$end}",
                    'owner' => optional(optional($booking->requester)->department)->name
                        ?? optional($booking->requester)->name
                        ?? 'Team',
                ];
            })
            ->values();

        $approvalsQueue = Booking::with(['facility', 'requester', 'details'])
            ->where('status', 'pending')
            ->latest('updated_at')
            ->limit(3)
            ->get()
            ->map(function (Booking $booking) {
                return [
                    'id' => $booking->id,
                    'facility' => $booking->facility->name ?? 'Facility',
                    'title' => optional($booking->details)->purpose ?? 'Booking',
                    'team' => optional(optional($booking->requester)->department)->name
                        ?? optional($booking->requester)->name
                        ?? 'Team',
                    'date' => Carbon::parse($booking->date)->format('M j') . ' · ' . Carbon::parse($booking->start_at)->format('g:i A'),
                    'status' => $booking->status,
                    'priority' => optional($booking->details)->sfi_support ? 'High' : 'Standard',
                ];
            })
            ->values();

        $chartRange = 6;
        $chartStart = today()->subDays($chartRange)->startOfDay();
        $chartCounts = Booking::selectRaw('DATE(updated_at) as day, COUNT(*) as total')
            ->where('status', 'approved')
            ->where('updated_at', '>=', $chartStart)
            ->groupBy('day')
            ->pluck('total', 'day');

        $heroChart = collect(range($chartRange, 0))->map(function ($offset) use ($chartCounts) {
            $date = today()->subDays($offset);
            return [
                'label' => $date->format('M j'),
                'value' => (int) ($chartCounts[$date->toDateString()] ?? 0),
            ];
        });

        $heroStats = [
            'approvalsToday' => $approvalsToday,
            'avgSla' => $this->averageSlaMinutes(),
            'openIncidents' => Booking::where('status', 'rejected')->count(),
            'waiting' => $waiting,
            'breaches' => $slaBreaches,
            'chart' => [
                'series' => $heroChart,
                'max' => max(1, $heroChart->max('value')),
                'total' => $heroChart->sum('value'),
            ],
        ];

        $statusBoard = [
            'facilitiesOnline' => Facility::where('status', 'Available')->count(),
            'totalFacilities' => Facility::count(),
            'approversOnDuty' => User::where('role', 'admin')->count(),
            'resolvedToday' => $resolvedToday,
        ];

        return view('admin.dashboard', [
            'user' => auth()->user(),
            'rooms' => $rooms,
            'approvalsQueue' => $approvalsQueue,
            'heroStats' => $heroStats,
            'statusBoard' => $statusBoard,
            'pendingApprovals' => $pendingApprovals,
            'todaysRequests' => $approvalsToday,
            'heroChart' => $heroStats['chart'],
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
