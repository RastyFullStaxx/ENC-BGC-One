<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\Building;
use App\Models\Facility;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminCalendarController extends Controller
{
    public function __construct(private AuditLogger $auditLogger)
    {
    }

    public function index(Request $request)
    {
        $tz = 'Asia/Manila';
        $today = Carbon::now($tz);
        $startParam = $request->get('start');
        $weekStart = $startParam
            ? Carbon::parse($startParam, $tz)->startOfWeek(Carbon::MONDAY)
            : $today->copy()->startOfWeek(Carbon::MONDAY);
        $weekEnd = $weekStart->copy()->endOfWeek(Carbon::SUNDAY);

        $calendarDays = collect(range(0, 6))->map(fn ($i) => $weekStart->copy()->addDays($i));

        $bookings = Booking::with(['facility.building', 'details', 'requester.department'])
            ->whereBetween('date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->when($request->filled('status'), function ($query) use ($request) {
                $status = $this->normalizeStatus($request->get('status'));
                $query->where('status', $status);
            })
            ->when($request->filled('building_id'), function ($query) use ($request) {
                $query->whereHas('facility', function ($q) use ($request) {
                    $q->where('building_id', $request->get('building_id'));
                });
            })
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->whereHas('facility', function ($q) use ($request) {
                    $q->where('type', $request->get('type'));
                });
            })
            ->orderBy('date')
            ->orderBy('start_at')
            ->get();

        $eventsByDay = $bookings->map(function (Booking $booking) use ($tz) {
            $date = Carbon::parse($booking->date, $tz);
            $status = $this->normalizeStatus($booking->status);
            $timeRange = $this->formatTimeRange($booking);
            $purpose = optional($booking->details)->purpose ?? 'Booking';
            $attendees = optional($booking->details)->attendees_count ?? '—';
            $requester = optional($booking->requester)->name ?? 'Staff';

            return [
                'id' => $booking->id,
                'facility' => optional($booking->facility)->name ?? 'Facility',
                'time' => $date->format('Y-m-d') . ' ' . $timeRange,
                'slot' => $timeRange,
                'status' => $status,
                'status_label' => ucfirst($status),
                'day' => $date->format('D'),
                'tooltip' => implode("\n", [
                    $purpose,
                    'Requester: ' . $requester,
                    'Attendees: ' . $attendees,
                ]),
                'priority' => $purpose,
                'requester' => $requester,
                'attendees' => $attendees,
                'date_display' => $date->format('M j'),
            ];
        })->groupBy('day');

        $listBookings = $bookings->map(function (Booking $booking) use ($tz) {
            $date = $booking->date ? Carbon::parse($booking->date, $tz) : null;
            $status = $this->normalizeStatus($booking->status);
            $start = $booking->start_at ? Carbon::parse($booking->start_at, $tz) : null;
            $end = $booking->end_at ? Carbon::parse($booking->end_at, $tz) : null;
            $durationMinutes = ($start && $end) ? $start->diffInMinutes($end) : null;
            $sortKey = $date
                ? $date->copy()->setTimeFromTimeString($start?->format('H:i') ?? '00:00')->timestamp
                : PHP_INT_MAX;

            return [
                'id' => $booking->id,
                'title' => optional($booking->details)->purpose ?? 'Booking',
                'facility' => optional($booking->facility)->name ?? 'Facility',
                'status' => $status,
                'status_label' => ucfirst($status),
                'date' => $date ? $date->format('M j') : 'TBD',
                'time' => ($start && $end) ? $start->format('H:i') . ' – ' . $end->format('H:i') : 'TBA',
                'duration' => $this->formatDuration($durationMinutes),
                'requester' => optional($booking->requester)->name ?? 'Staff',
                'owner' => optional(optional($booking->requester)->department)->name ?? 'Admin',
                'type' => optional($booking->facility)->type ?? 'General',
                'sort_key' => $sortKey,
            ];
        })->sortBy('sort_key')->values();

        $bookedMinutes = $bookings->reduce(function ($carry, Booking $booking) use ($tz) {
            if (!$booking->start_at || !$booking->end_at) {
                return $carry;
            }

            $start = Carbon::parse($booking->start_at, $tz);
            $end = Carbon::parse($booking->end_at, $tz);

            return $carry + $start->diffInMinutes($end);
        }, 0);

        $businessMinutes = 7 * 8 * 60; // 8h x 7 days baseline
        $utilization = $businessMinutes > 0 ? min(100, round(($bookedMinutes / $businessMinutes) * 100)) : 0;

        $stats = [
            'today' => $bookings->filter(fn ($b) => $b->date && $b->date->isSameDay($today))->count(),
            'pending' => $bookings->where('status', 'pending')->count(),
            'approved' => $bookings->whereIn('status', ['approved', 'confirmed'])->count(),
            'cancelled' => $bookings->where('status', 'cancelled')->count(),
            'utilization' => $utilization,
            'week_range' => $weekStart->format('M j') . ' – ' . $weekEnd->format('M j'),
            'timezone' => $tz,
            'week_start' => $weekStart->toDateString(),
            'prev_start' => $weekStart->copy()->subWeek()->toDateString(),
            'next_start' => $weekStart->copy()->addWeek()->toDateString(),
        ];

        $buildingOptions = Building::orderBy('name')->get(['id', 'name']);
        $facilityTypes = Facility::query()->distinct()->pluck('type')->filter()->values();
        $facilityOptions = Facility::orderBy('name')->get(['id', 'name']);

        $appliedFilters = [];
        if ($request->filled('building_id')) {
            $building = $buildingOptions->firstWhere('id', $request->get('building_id'));
            $appliedFilters[] = $building?->name ?? 'Building';
        }
        if ($request->filled('type')) {
            $appliedFilters[] = $request->get('type');
        }
        if ($request->filled('status')) {
            $appliedFilters[] = ucfirst($this->normalizeStatus($request->get('status')));
        }
        if (empty($appliedFilters)) {
            $appliedFilters[] = 'All facilities';
        }

        return view('admin.calendar', [
            'calendarDays' => $calendarDays,
            'eventsByDay' => $eventsByDay,
            'listBookings' => $listBookings,
            'stats' => $stats,
            'buildings' => $buildingOptions,
            'facilityTypes' => $facilityTypes,
            'facilities' => $facilityOptions,
            'filters' => [
                'status' => $request->get('status'),
                'building_id' => $request->get('building_id'),
                'type' => $request->get('type'),
            ],
            'appliedFilters' => $appliedFilters,
        ]);
    }

    public function block(Request $request)
    {
        $tz = 'Asia/Manila';
        $validator = validator($request->all(), [
            'facility_id' => 'required|exists:facilities,id',
            'date' => 'required|date',
            'start_at' => 'required|date_format:H:i',
            'end_at' => 'required|date_format:H:i|after:start_at',
            'note' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $hasConflict = Booking::where('facility_id', $request->facility_id)
            ->where('date', $request->date)
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_at', [$request->start_at, $request->end_at])
                    ->orWhereBetween('end_at', [$request->start_at, $request->end_at])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_at', '<=', $request->start_at)
                          ->where('end_at', '>=', $request->end_at);
                    });
            })
            ->exists();

        if ($hasConflict) {
            return response()->json(['message' => 'Time is not available for that facility.'], 409);
        }

        DB::beginTransaction();
        try {
            $booking = Booking::create([
                'facility_id' => $request->facility_id,
                'requester_id' => Auth::id(),
                'date' => $request->date,
                'start_at' => $request->start_at,
                'end_at' => $request->end_at,
                'status' => 'blocked',
                'reference_code' => Booking::generateReferenceCode(),
            ]);

            BookingDetail::create([
                'booking_id' => $booking->id,
                'purpose' => 'Blocked Time',
                'additional_notes' => $request->note,
            ]);

            DB::commit();

            $this->auditLogger->log([
                'action' => 'Blocked facility time',
                'module' => 'Calendar',
                'target' => optional($booking->facility)->name ?? $booking->facility_id,
                'action_type' => 'block',
                'risk' => 'medium',
                'status' => 'success',
                'source' => 'Admin UI',
                'before' => null,
                'after' => [
                    'date' => $booking->date,
                    'start_at' => $booking->start_at,
                    'end_at' => $booking->end_at,
                    'status' => 'blocked',
                ],
                'changes' => ['Calendar block added'],
                'notes' => $request->note,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => 'Could not save block: ' . $e->getMessage()], 500);
        }

        return response()->json([
            'message' => 'Blocked successfully',
            'booking' => $booking,
        ]);
    }

    public function exportIcs(Request $request)
    {
        $tz = 'Asia/Manila';
        $today = Carbon::now($tz);
        $start = $today->copy()->startOfWeek(Carbon::MONDAY);
        $end = $start->copy()->endOfWeek(Carbon::SUNDAY);

        $bookings = Booking::with(['facility.building', 'details'])
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->orderBy('date')
            ->orderBy('start_at')
            ->get();

        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//ENC//Admin Calendar//EN',
            'CALSCALE:GREGORIAN',
        ];

        foreach ($bookings as $booking) {
            $eventDate = Carbon::parse($booking->date, $tz);
            $startAt = $booking->start_at ? Carbon::parse($booking->start_at, $tz) : null;
            $endAt = $booking->end_at ? Carbon::parse($booking->end_at, $tz) : null;

            if ($startAt) {
                $startAt = $eventDate->copy()->setTimeFromTimeString($startAt->format('H:i:s'));
            }

            if ($endAt) {
                $endAt = $eventDate->copy()->setTimeFromTimeString($endAt->format('H:i:s'));
            }

            $summary = optional($booking->details)->purpose ?? 'Booking';
            $location = optional($booking->facility)->name ?? 'Facility';
            $status = strtoupper($booking->status ?? 'CONFIRMED');

            $lines = array_merge($lines, [
                'BEGIN:VEVENT',
                'UID:booking-' . $booking->id . '@enc',
                'DTSTAMP:' . $today->format('Ymd\THis\Z'),
                $startAt ? 'DTSTART:' . $startAt->format('Ymd\THis') : null,
                $endAt ? 'DTEND:' . $endAt->format('Ymd\THis') : null,
                'SUMMARY:' . $summary,
                'LOCATION:' . $location,
                'STATUS:' . $status,
                'END:VEVENT',
            ]);
        }

        $lines[] = 'END:VCALENDAR';
        $content = implode("\r\n", array_filter($lines));

        return response($content, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="enc-admin-calendar.ics"',
        ]);
    }

    private function normalizeStatus(?string $status): string
    {
        return match (strtolower($status ?? 'pending')) {
            'approved', 'confirmed' => 'approved',
            'cancelled', 'canceled' => 'cancelled',
            'rejected' => 'rejected',
            default => 'pending',
        };
    }

    private function formatDuration(?int $minutes): string
    {
        if (!$minutes) {
            return '—';
        }

        if ($minutes < 60) {
            return $minutes . 'm';
        }

        $hours = $minutes / 60;
        return $hours >= 1 && fmod($hours, 1) === 0.0
            ? (int) $hours . 'h'
            : number_format($hours, 1) . 'h';
    }

    private function formatTimeRange(Booking $booking): string
    {
        $start = $booking->start_at
            ? Carbon::parse($booking->start_at)->format('g:i A')
            : 'TBA';
        $end = $booking->end_at
            ? Carbon::parse($booking->end_at)->format('g:i A')
            : 'TBA';

        return $start . ' – ' . $end;
    }
}
