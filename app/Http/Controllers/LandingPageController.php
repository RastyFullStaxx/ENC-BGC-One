<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\OperatingHours;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class LandingPageController extends Controller
{
    /**
     * Public landing page with live data pulled from facilities and bookings.
     */
    public function __invoke()
    {
        $now = Carbon::now('Asia/Manila');
        $today = $now->toDateString();

        $facilities = $this->fetchFacilitiesForLanding($today);
        [$scheduleStart, $scheduleEnd] = $this->determineScheduleWindow($facilities);

        $scheduleBlocks = [];
        $facilityTiles = [];
        $facilityHero = null;

        foreach ($facilities as $index => $facility) {
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

            if ($index === 0) {
                $facilityHero = $this->buildFacilityHero($facility, $availability);
                continue;
            }

            $facilityTiles[] = $this->buildFacilityTile($facility, $availability);
        }

        if (! $facilityHero) {
            $facilityHero = $this->fallbackHero();
        }

        return view('marketing.landing', [
            'lastSync' => $now,
            'scheduleStart' => $scheduleStart,
            'scheduleEnd' => $scheduleEnd,
            'scheduleBlocks' => $scheduleBlocks,
            'facilityHero' => $facilityHero,
            'facilityTiles' => collect($facilityTiles)->filter()->values()->all(),
            'featuredServices' => $this->featuredServices(),
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
            $end = Carbon::parse($currentBooking->end_at, 'Asia/Manila');
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
            $start = Carbon::parse($nextBooking->start_at, 'Asia/Manila');
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

    private function buildFacilityHero(?Facility $facility, array $availability): array
    {
        if (! $facility) {
            return $this->fallbackHero();
        }

        $metrics = [
            ['label' => 'Capacity', 'value' => $facility->capacity ? $facility->capacity . ' seats' : '—'],
            ['label' => 'Hours', 'value' => $this->formatOperatingHours($facility->operatingHours)],
        ];

        if (! empty($availability['next'])) {
            $metrics[] = ['label' => 'Next slot', 'value' => $availability['next']];
        }

        return [
            'title' => $this->formatRoomName($facility),
            'type' => $facility->type ? ucfirst($facility->type) : 'Facility',
            'status' => $availability['label'] ?? 'Available',
            'window' => $availability['note'] ?? '',
            'image' => $this->facilityPhotoUrl($facility),
            'summary' => $this->buildHeroSummary($facility),
            'chips' => array_values(array_filter([
                optional($facility->building)->name,
                $facility->floor ? ucfirst($facility->floor) . ' Floor' : null,
                $availability['label'] ?? null,
            ])),
            'metrics' => $metrics,
        ];
    }

    private function buildFacilityTile(Facility $facility, array $availability): array
    {
        return [
            'title' => $this->formatRoomName($facility),
            'type' => $facility->type ? ucfirst($facility->type) : 'Facility',
            'status' => $availability['note'] ?? ($availability['label'] ?? 'Available'),
            'tone' => $this->toneFromStatus($availability['status'] ?? 'available'),
            'image' => $this->facilityPhotoUrl($facility),
            'details' => array_values(array_filter([
                $facility->capacity ? $facility->capacity . ' seats' : null,
                optional($facility->building)->name,
                $facility->floor ? ucfirst($facility->floor) . ' Floor' : null,
            ])),
        ];
    }

    private function fallbackHero(): array
    {
        return [
            'title' => 'Innovation Lab C-401',
            'type' => 'Innovation Lab',
            'status' => 'Limited availability this morning',
            'window' => 'Wide open for workshops after 3:00 PM',
            'image' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?q=80&w=1600&auto=format&fit=crop',
            'summary' => 'Modular seating, writable walls, dual projectors, and embedded sensors to keep teams synced without tech anxiety.',
            'chips' => ['Hybrid-ready', 'Workshop layout', 'Natural light'],
            'metrics' => [
                ['label' => 'Capacity', 'value' => '24 seats'],
                ['label' => 'Amenities', 'value' => '2 displays · 4 mics'],
                ['label' => 'Next slot', 'value' => 'Today · 3:15 PM'],
            ],
        ];
    }

    private function featuredServices(): array
    {
        return [
            [
                'name' => 'Meeting room booking',
                'summary' => 'Live availability, instant approvals, and guided forms for every huddle or town hall.',
                'icon' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none"><rect x="4" y="6" width="16" height="12" rx="3" stroke="currentColor" stroke-width="1.8"/><path d="M4 10h16M10 14h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>',
            ],
            [
                'name' => 'SEI facilities',
                'summary' => 'Innovation labs, studios, and special venues with concierge prep and equipment support.',
                'icon' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none"><path d="M4 10l8-6 8 6v8a2 2 0 0 1-2 2h-1v-5h-4v5H6a2 2 0 0 1-2-2v-8z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>',
            ],
            [
                'name' => 'Shuttle service',
                'summary' => 'Reserve point-to-point or loop shuttles with passenger manifests and live tracking.',
                'icon' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none"><rect x="3" y="7" width="18" height="10" rx="3" stroke="currentColor" stroke-width="1.8"/><path d="M6 17v2M18 17v2M4 12h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>',
            ],
        ];
    }

    private function formatRoomName(Facility $facility): string
    {
        $name = $facility->name ?? 'Facility';

        if ($facility->room_number) {
            $name .= ' ' . $facility->room_number;
        }

        return $name;
    }

    private function buildHeroSummary(Facility $facility): string
    {
        $parts = array_filter([
            optional($facility->building)->name,
            $facility->floor ? ucfirst($facility->floor) . ' Floor' : null,
            $facility->capacity ? $facility->capacity . ' seats' : null,
        ]);

        return $parts ? implode(' · ', $parts) : 'Ready for your next booking.';
    }

    private function facilityPhotoUrl(Facility $facility): string
    {
        $photo = optional($facility->photos->first())->url;

        if ($photo) {
            return '/' . ltrim(str_replace('public/', '', $photo), '/');
        }

        return 'https://images.unsplash.com/photo-1551290464-5c9f2d8e8b43?q=80&w=1600&auto=format&fit=crop';
    }

    private function formatOperatingHours(?OperatingHours $hours): string
    {
        if (! $hours) {
            return '7:00 AM – 7:00 PM';
        }

        $open = Carbon::parse($hours->open_time, 'Asia/Manila')->format('g:i A');
        $close = Carbon::parse($hours->close_time, 'Asia/Manila')->format('g:i A');

        return "{$open} – {$close}";
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

        return Carbon::createFromTime($whole, $minutes)->format('g:i A');
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

    private function toneFromStatus(string $status): string
    {
        return match ($status) {
            'occupied' => 'occupied',
            'under-maintenance' => 'maintenance',
            default => 'available',
        };
    }
}
