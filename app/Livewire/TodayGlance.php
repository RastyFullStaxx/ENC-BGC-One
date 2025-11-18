<?php

namespace App\Livewire;

use App\Models\Facility;
use App\Models\OperatingHours;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

class TodayGlance extends Component
{
    public function render()
    {
        $now = Carbon::now(config('app.timezone', 'UTC'));
        $today = $now->toDateString();

        $facilities = $this->fetchFacilitiesForLanding($today);
        [$scheduleStart, $scheduleEnd] = $this->determineScheduleWindow($facilities);

        $scheduleBlocks = [];
        foreach ($facilities as $facility) {
            $bookings = $facility->bookings ?? collect();
            $availability = $this->describeAvailability($facility, $bookings, $now, $scheduleEnd);
            $segments = $this->buildSegments($bookings, $scheduleStart, $scheduleEnd, $availability['status'] === 'under-maintenance');

            $scheduleBlocks[] = [
                'room' => $this->formatRoomName($facility),
                'status' => $availability['status'],
                'status_label' => $availability['label'],
                'note' => $availability['note'],
                'segments' => $segments,
            ];
        }

        $range = max(1, $scheduleEnd - $scheduleStart);
        $nowDecimal = (int) $now->format('G') + ((int) $now->format('i') / 60);
        $nowInRange = $nowDecimal >= $scheduleStart && $nowDecimal <= $scheduleEnd;
        $nowOffset = max(0, min(100, (($nowDecimal - $scheduleStart) / $range) * 100));

        return view('livewire.today-glance', [
            'scheduleStart' => $scheduleStart,
            'scheduleEnd' => $scheduleEnd,
            'scheduleBlocks' => $scheduleBlocks,
            'lastSync' => $now,
            'nowInRange' => $nowInRange,
            'nowOffset' => $nowOffset,
            'range' => $range,
            'nowLabel' => $now->format('g:i A'),
            'stateLabels' => [
                'available'   => 'Available',
                'limited'     => 'Limited availability',
                'occupied'    => 'Occupied',
                'maintenance' => 'Under maintenance',
            ],
        ]);
    }

    private function fetchFacilitiesForLanding(string $date): Collection
    {
        return Facility::with([
            'building',
            'photos',
            'operatingHours',
            'bookings' => function ($query) use ($date) {
                $query->whereDate('date', $date)
                    ->whereIn('status', ['pending', 'approved'])
                    ->with('details')
                    ->orderBy('start_at');
            },
        ])
            ->orderBy('id')
            ->take(4)
            ->get();
    }

    private function determineScheduleWindow(Collection $facilities): array
    {
        $opens = $facilities->map(fn (Facility $facility) => optional($facility->operatingHours)->open_time)
            ->filter()
            ->map(fn ($time) => $this->timeToDecimal($time));

        $closes = $facilities->map(fn (Facility $facility) => optional($facility->operatingHours)->close_time)
            ->filter()
            ->map(fn ($time) => $this->timeToDecimal($time));

        $start = $opens->isNotEmpty() ? (int) floor($opens->min()) : 7;
        $end = $closes->isNotEmpty() ? (int) ceil($closes->max()) : 19;

        if ($end <= $start) {
            $end = $start + 1;
        }

        return [$start, $end];
    }

    private function describeAvailability(Facility $facility, Collection $bookings, Carbon $now, float $scheduleEnd): array
    {
        $facilityStatus = strtolower($facility->status ?? '');
        $isMaintenance = str_contains($facilityStatus, 'maintenance');

        if ($isMaintenance) {
            return [
                'status' => 'under-maintenance',
                'label' => 'Under maintenance',
                'note' => 'Temporarily offline for upkeep',
                'next' => null,
            ];
        }

        $currentDecimal = $this->timeToDecimal($now->format('H:i:s'));
        $currentBooking = $bookings->first(function ($booking) use ($currentDecimal) {
            $start = $this->timeToDecimal($booking->start_at);
            $end = $this->timeToDecimal($booking->end_at);

            return $start <= $currentDecimal && $end >= $currentDecimal;
        });

        if ($currentBooking) {
            $end = Carbon::parse($currentBooking->end_at, config('app.timezone', 'UTC'));
            $purpose = optional($currentBooking->details)->purpose;

            return [
                'status' => 'occupied',
                'label' => 'Occupied',
                'note' => trim(($purpose ? $purpose . ' · ' : '') . 'Until ' . $end->format('g:i A')),
                'next' => 'Available after ' . $end->format('g:i A'),
            ];
        }

        $nextBooking = $bookings->first(function ($booking) use ($currentDecimal) {
            return $this->timeToDecimal($booking->start_at) > $currentDecimal;
        });

        if ($nextBooking) {
            $start = Carbon::parse($nextBooking->start_at, config('app.timezone', 'UTC'));
            $diffMinutes = $now->diffInMinutes($start, false);
            $status = $diffMinutes <= 120 ? 'limited-availability' : 'available';
            $label = $this->statusLabel($status);
            $freeUntil = $start->copy()->subMinutes(15);
            $note = $diffMinutes > 0
                ? 'Free until ' . $freeUntil->format('g:i A')
                : 'Booking starts soon';

            return [
                'status' => $status,
                'label' => $label,
                'note' => $note,
                'next' => 'Next: ' . $start->format('g:i A'),
            ];
        }

        return [
            'status' => 'available',
            'label' => 'Available',
            'note' => 'Available for the rest of today',
            'next' => 'Closes at ' . $this->formatHourLabel($scheduleEnd),
        ];
    }

    private function buildSegments(Collection $bookings, float $scheduleStart, float $scheduleEnd, bool $isMaintenance): array
    {
        if ($isMaintenance) {
            return [[
                'start' => $scheduleStart,
                'end' => $scheduleEnd,
                'status' => 'maintenance',
            ]];
        }

        if ($bookings->isEmpty()) {
            return [[
                'start' => $scheduleStart,
                'end' => $scheduleEnd,
                'status' => 'available',
            ]];
        }

        $segments = [];
        $current = $scheduleStart;

        foreach ($bookings as $booking) {
            $start = max($scheduleStart, $this->timeToDecimal($booking->start_at));
            $end = min($scheduleEnd, $this->timeToDecimal($booking->end_at));

            if ($end <= $scheduleStart || $start >= $scheduleEnd) {
                continue;
            }

            if ($start > $current) {
                $segments[] = [
                    'start' => $current,
                    'end' => $start,
                    'status' => $this->gapStatus($start - $current),
                ];
            }

            $segments[] = [
                'start' => $start,
                'end' => $end,
                'status' => 'occupied',
            ];

            $current = $end;
        }

        if ($current < $scheduleEnd) {
            $segments[] = [
                'start' => $current,
                'end' => $scheduleEnd,
                'status' => $this->gapStatus($scheduleEnd - $current),
            ];
        }

        return $segments;
    }

    private function timeToDecimal(?string $time): float
    {
        if (! $time) {
            return 0;
        }

        $parts = explode(':', $time);
        $hour = (int) ($parts[0] ?? 0);
        $minute = (int) ($parts[1] ?? 0);

        return $hour + ($minute / 60);
    }

    private function formatHourLabel(float $hour): string
    {
        $whole = (int) floor($hour);
        $minutes = (int) round(($hour - $whole) * 60);

        return Carbon::createFromTime($whole, $minutes)->setTimezone(config('app.timezone', 'UTC'))->format('g:i A');
    }

    private function gapStatus(float $gapHours): string
    {
        return $gapHours <= 1 ? 'limited' : 'available';
    }

    private function statusLabel(string $status): string
    {
        return match ($status) {
            'limited-availability' => 'Limited availability',
            'occupied' => 'Occupied',
            'under-maintenance' => 'Under maintenance',
            default => 'Available',
        };
    }

    private function formatRoomName(Facility $facility): string
    {
        $name = $facility->name ?? 'Facility';

        if ($facility->room_number) {
            $name .= ' ' . $facility->room_number;
        }

        return $name;
    }
}
