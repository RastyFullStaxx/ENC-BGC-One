<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $startDate = Carbon::parse($request->input('start_date', Carbon::now()->subDays(30)->toDateString()))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date', Carbon::now()->toDateString()))->endOfDay();

        $bookings = Booking::with(['facility.building', 'requester.department'])
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->get();

        $kpis = $this->buildKpis($bookings, $startDate, $endDate);

        $utilizationStats = $this->buildUtilizationStats($bookings);
        $peakHoursStats = $this->buildPeakHours($bookings);
        $departmentShare = $this->buildDepartmentShare($bookings);
        $statusBreakdown = $this->buildStatusBreakdown($bookings);
        $noShowReasons = $this->buildNoShowReasons($bookings);
        $recurrenceStats = $this->buildRecurrenceStats($bookings, $startDate, $endDate);
        $demandRanking = $this->buildDemandRanking($bookings);

        return view('admin.analytics', compact(
            'kpis',
            'demandRanking',
            'utilizationStats',
            'peakHoursStats',
            'departmentShare',
            'statusBreakdown',
            'noShowReasons',
            'recurrenceStats'
        ));
    }

    private function buildKpis($bookings, Carbon $start, Carbon $end): array
    {
        $approved = $bookings->where('status', 'approved')->count();
        $cancelled = $bookings->where('status', 'cancelled')->count();
        $total = $bookings->count();
        $approvedPct = $total ? round(($approved / $total) * 100) : 0;
        $cancelledPct = $total ? round(($cancelled / $total) * 100) : 0;

        $topRoom = $bookings
            ->filter(fn ($b) => $b->facility)
            ->groupBy('facility_id')
            ->map(fn ($set) => ['count' => $set->count(), 'name' => optional($set->first()->facility)->name ?? 'N/A'])
            ->sortByDesc('count')
            ->first() ?? ['count' => 0, 'name' => 'N/A'];

        $peakDay = $bookings
            ->groupBy(fn ($b) => Carbon::parse($b->date)->isoFormat('dddd'))
            ->map->count()
            ->sortDesc()
            ->keys()
            ->first() ?? 'N/A';

        return [
            ['label' => 'Total Bookings', 'value' => (string) $total, 'note' => $start->format('M j') . ' – ' . $end->format('M j')],
            ['label' => 'Approved vs Cancelled', 'value' => "{$approvedPct}% / {$cancelledPct}%", 'note' => 'Green = approved'],
            ['label' => 'Most Booked Room', 'value' => $topRoom['name'], 'note' => "{$topRoom['count']} bookings"],
            ['label' => 'Peak Day', 'value' => $peakDay, 'note' => 'Most check-ins'],
        ];
    }

    private function buildUtilizationStats($bookings): array
    {
        $hoursByFacility = $bookings
            ->filter(fn ($b) => $b->facility)
            ->groupBy('facility_id')
            ->map(function ($set) {
                $hours = $set->sum(function ($booking) {
                    if (!$booking->start_at || !$booking->end_at) {
                        return 0;
                    }
                    return Carbon::parse($booking->start_at)->floatDiffInHours(Carbon::parse($booking->end_at));
                });
                return [
                    'name' => optional($set->first()->facility)->name ?? 'Facility',
                    'hours' => round($hours, 1),
                ];
            })
            ->sortByDesc('hours')
            ->take(6);

        $maxHours = max($hoursByFacility->max('hours') ?? 0, 1);

        return [
            'labels' => $hoursByFacility->pluck('name')->values(),
            'values' => $hoursByFacility->pluck('hours')->map(fn ($h) => round(($h / $maxHours) * 100))->values(),
        ];
    }

    private function buildPeakHours($bookings): array
    {
        $baseHours = collect(range(7, 20))->mapWithKeys(fn ($h) => [$h => 0]);

        $hourCounts = $bookings->reduce(function ($carry, $booking) {
            if (!$booking->start_at) {
                return $carry;
            }
            $hour = Carbon::parse($booking->start_at)->hour;
            if ($carry->has($hour)) {
                $carry[$hour] = $carry[$hour] + 1;
            }
            return $carry;
        }, $baseHours);

        return [
            'labels' => $hourCounts->keys()->map(fn ($h) => (string) $h)->values(),
            'values' => $hourCounts->values(),
        ];
    }

    private function buildDepartmentShare($bookings): array
    {
        $departmentCounts = $bookings
            ->filter(fn ($b) => optional($b->requester)->department)
            ->groupBy(fn ($b) => optional($b->requester->department)->name ?? 'Unassigned')
            ->map->count()
            ->sortDesc();

        return [
            'labels' => $departmentCounts->keys()->values(),
            'values' => $departmentCounts->values(),
        ];
    }

    private function buildStatusBreakdown($bookings): array
    {
        $statusCounts = $bookings
            ->groupBy('status')
            ->map->count()
            ->sortDesc();

        return [
            'labels' => $statusCounts->keys()->values(),
            'values' => $statusCounts->values(),
        ];
    }

    private function buildNoShowReasons($bookings): array
    {
        // Approximate "drivers" based on available status data.
        $counts = collect([
            'Cancelled' => $bookings->where('status', 'cancelled')->count(),
            'Rejected' => $bookings->where('status', 'rejected')->count(),
            'Pending (unattended)' => $bookings->where('status', 'pending')->count(),
            'Approved (no check-in)' => $bookings->where('status', 'approved')->count(),
        ])->filter(fn ($count) => $count > 0);

        if ($counts->isEmpty()) {
            $counts = collect(['No data yet' => 1]);
        }

        return [
            'labels' => $counts->keys()->values(),
            'values' => $counts->values(),
        ];
    }

    private function buildRecurrenceStats($bookings, Carbon $start, Carbon $end): array
    {
        $weeks = collect();
        $cursor = (clone $start)->startOfWeek();
        while ($cursor->lessThanOrEqualTo($end)) {
            $weeks->push($cursor->copy());
            $cursor->addWeek();
        }

        $weekCounts = $weeks->map(function (Carbon $weekStart) use ($bookings) {
            $weekEnd = (clone $weekStart)->endOfWeek();
            $count = $bookings->filter(function ($booking) use ($weekStart, $weekEnd) {
                return Carbon::parse($booking->date)->betweenIncluded($weekStart, $weekEnd);
            })->count();
            return [
                'label' => $weekStart->format('M j'),
                'value' => $count,
            ];
        })->take(-4); // last 4 weeks

        return [
            'labels' => $weekCounts->pluck('label'),
            'values' => $weekCounts->pluck('value'),
        ];
    }

    private function buildDemandRanking($bookings): array
    {
        return $bookings
            ->filter(fn ($b) => $b->facility)
            ->groupBy('facility_id')
            ->map(function ($set) {
                $hours = $set->sum(function ($booking) {
                    if (!$booking->start_at || !$booking->end_at) {
                        return 0;
                    }
                    return Carbon::parse($booking->start_at)->floatDiffInHours(Carbon::parse($booking->end_at));
                });

                return [
                    'name' => optional($set->first()->facility)->name ?? 'Facility',
                    'bookings' => $set->count(),
                    'hours' => round($hours, 1),
                    'conflicts' => $set->where('status', 'rejected')->count(),
                    'maintenance' => optional($set->first()->facility)->updated_at
                        ? Carbon::parse($set->first()->facility->updated_at)->format('M d')
                        : '—',
                    'score' => '4.5',
                ];
            })
            ->sortByDesc('bookings')
            ->values()
            ->take(8)
            ->all();
    }
}
