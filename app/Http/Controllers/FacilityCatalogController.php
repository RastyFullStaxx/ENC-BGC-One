<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Facility;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Support\Collection;

class FacilityCatalogController extends Controller
{
    public function __invoke(): View
    {
        $timezone = 'Asia/Manila';
        $today = Carbon::now($timezone)->startOfDay();
        $dates = [
            $today,
            (clone $today)->addDay(),
            (clone $today)->addDays(2),
        ];

        $facilities = Facility::with(['building', 'equipment', 'photos', 'operatingHours'])
            ->orderBy('name')
            ->get();

        $facilityRooms = $facilities->map(function (Facility $facility) use ($dates, $timezone) {
            $photo = $facility->photos->first();
            $imagePath = $photo?->url ? '/' . ltrim(str_replace('public/', '', $photo->url), '/') : null;

            $availability = [];
            foreach ($dates as $date) {
                $availability[$date->toDateString()] = $this->buildAvailabilitySnapshot($facility, $date, $timezone);
            }

            $todayKey = $dates[0]->toDateString();
            $todayAvailability = $availability[$todayKey] ?? null;
            $status = $this->buildStatus($facility->status, $todayAvailability);

            $todayBookings = Booking::with('details')
                ->where('facility_id', $facility->id)
                ->whereDate('date', $todayKey)
                ->where('status', 'approved')
                ->orderBy('start_at')
                ->get();

            $bookingsToday = Booking::where('facility_id', $facility->id)
                ->whereDate('date', $todayKey)
                ->whereIn('status', ['pending', 'approved'])
                ->count();

            $layout = match ($facility->type) {
                'training' => 'Training / Workshop layout',
                'multipurpose' => 'Flexible seating',
                default => 'Boardroom / Meeting setup',
            };

            $prepTime = match ($facility->type) {
                'training' => '15 mins prep',
                'multipurpose' => '20 mins prep',
                default => '10 mins prep',
            };

            $sizeLabel = $this->sizeLabel($facility->capacity);
            $location = trim(
                implode(' · ', array_filter([
                    $facility->building?->name,
                    $facility->floor ? ucfirst($facility->floor) . ' Floor' : null,
                ]))
            );

            $amenities = $facility->equipment->pluck('name')->toArray();
            $amenities = empty($amenities) ? ['Wi-Fi', 'Display-ready', 'AV support on request'] : $amenities;

            return [
                'id' => $facility->facility_code ?? 'facility-' . $facility->id,
                'name' => $facility->name,
                'short_label' => $facility->room_number ? 'Room ' . $facility->room_number : $facility->name,
                'image' => $imagePath ?? 'https://images.unsplash.com/photo-1503424886308-418b31263d55?auto=format&fit=crop&w=1600&q=80',
                'status' => $status,
                'capacity' => $facility->capacity,
                'capacity_label' => $facility->capacity . ' seats',
                'layout' => $layout,
                'location' => $location ?: 'ENC BGC',
                'size' => $sizeLabel,
                'prep_time' => $prepTime,
                'description' => $this->descriptionFor($facility),
                'amenities' => $amenities,
                'highlights' => $this->highlightsFor($facility, $amenities),
                'timeline' => $this->timelineFor($facility, $todayBookings, $dates[0], $timezone),
                'tags' => $this->tagsFor($facility),
                'support_contact' => 'mailto:facilities@enc.com?subject=' . rawurlencode('Facility Support - ' . $facility->name),
                'bookings_today' => $bookingsToday,
                'availability' => collect($availability)->map(fn ($item) => [
                    'state' => $item['state'] ?? 'unknown',
                    'copy' => $item['copy'] ?? '',
                ])->toArray(),
            ];
        })->values()->all();

        $statusRank = ['success' => 0, 'warning' => 1, 'info' => 2, 'danger' => 3];
        usort($facilityRooms, function ($a, $b) use ($statusRank) {
            $statusDiff = ($statusRank[$a['status']['variant'] ?? 'info'] ?? 99) <=> ($statusRank[$b['status']['variant'] ?? 'info'] ?? 99);
            if ($statusDiff !== 0) {
                return $statusDiff;
            }

            $bookingDiff = ($a['bookings_today'] ?? 99) <=> ($b['bookings_today'] ?? 99);
            if ($bookingDiff !== 0) {
                return $bookingDiff;
            }

            return strcmp($a['name'] ?? '', $b['name'] ?? '');
        });

        $availableRooms = collect($facilityRooms)->filter(function ($room) {
            return ($room['status']['variant'] ?? null) === 'success';
        })->count();

        return view('facilities.catalog', [
            'facilityRooms' => $facilityRooms,
            'activeFacility' => $facilityRooms[0] ?? null,
            'availableRooms' => $availableRooms,
            'totalSeats' => collect($facilityRooms)->sum('capacity'),
        ]);
    }

    private function buildAvailabilitySnapshot(Facility $facility, Carbon $date, string $timezone): array
    {
        $bookings = Booking::where('facility_id', $facility->id)
            ->whereDate('date', $date->toDateString())
            ->whereIn('status', ['pending', 'approved'])
            ->orderBy('start_at')
            ->get();

        if ($bookings->isEmpty()) {
            return [
                'state' => 'available',
                'label' => 'Available now',
                'variant' => 'success',
                'copy' => 'Available all day',
            ];
        }

        if ($date->isToday()) {
            $now = Carbon::now($timezone)->format('H:i:s');

            $current = $bookings->first(function ($booking) use ($now) {
                return $booking->start_at <= $now && $booking->end_at >= $now;
            });

            if ($current) {
                $end = Carbon::parse($current->end_at, $timezone);

                return [
                    'state' => 'occupied',
                    'label' => 'Occupied',
                    'variant' => 'danger',
                    'copy' => 'Busy until ' . $end->format('g:i A'),
                ];
            }

            $next = $bookings->first(function ($booking) use ($now) {
                return $booking->start_at > $now;
            });

            if ($next) {
                $start = Carbon::parse($next->start_at, $timezone);
                $freeUntil = $start->copy()->subMinutes(30);

                return [
                    'state' => 'limited',
                    'label' => 'Limited availability',
                    'variant' => 'warning',
                    'copy' => 'Free until ' . $freeUntil->format('g:i A'),
                ];
            }

            return [
                'state' => 'available',
                'label' => 'Available now',
                'variant' => 'success',
                'copy' => 'Available for the rest of the day',
            ];
        }

        return [
            'state' => 'limited',
            'label' => 'Limited availability',
            'variant' => 'warning',
            'copy' => $bookings->count() . ' booking(s) scheduled',
        ];
    }

    private function buildStatus(string $status, ?array $availability): array
    {
        $variant = $availability['variant'] ?? $this->variantFromStatus($status);

        return [
            'label' => $availability['label'] ?? $this->labelFromStatus($status),
            'variant' => $variant,
            'copy' => $availability['copy'] ?? $this->copyFromStatus($status),
        ];
    }

    private function variantFromStatus(string $status): string
    {
        return match ($status) {
            'Available' => 'success',
            'Limited_Availability' => 'warning',
            'Occupied' => 'danger',
            'Under_Maintenance' => 'info',
            default => 'info',
        };
    }

    private function labelFromStatus(string $status): string
    {
        return match ($status) {
            'Available' => 'Available now',
            'Limited_Availability' => 'Limited availability',
            'Occupied' => 'Occupied',
            'Under_Maintenance' => 'Under maintenance',
            default => Str::headline($status ?: 'Status'),
        };
    }

    private function copyFromStatus(string $status): string
    {
        return match ($status) {
            'Available' => 'Open for booking',
            'Limited_Availability' => 'Check slots before booking',
            'Occupied' => 'Currently in use',
            'Under_Maintenance' => 'Temporarily unavailable',
            default => 'Status update pending',
        };
    }

    private function sizeLabel(?int $capacity): string
    {
        if ($capacity === null) {
            return 'Flexible size';
        }

        if ($capacity <= 8) {
            return 'Small';
        }

        if ($capacity <= 16) {
            return 'Medium';
        }

        return 'Large';
    }

    private function descriptionFor(Facility $facility): string
    {
        $typeLabel = match ($facility->type) {
            'training' => 'training',
            'multipurpose' => 'multi-purpose',
            default => 'meeting',
        };

        return sprintf(
            '%s in %s with capacity for %s. Ideal for %s sessions with quick AV support.',
            $facility->name,
            $facility->building?->name ?? 'ENC BGC',
            $facility->capacity . ' guests',
            $typeLabel
        );
    }

    private function highlightsFor(Facility $facility, array $amenities): array
    {
        $highlights = [];

        if (!empty($amenities)) {
            $highlights[] = $amenities[0] . ' ready';
        }

        if ($facility->operatingHours) {
            $highlights[] = 'Open ' .
                Carbon::parse($facility->operatingHours->open_time)->format('g:i A') .
                ' – ' .
                Carbon::parse($facility->operatingHours->close_time)->format('g:i A');
        }

        $highlights[] = 'Concierge support available';

        return $highlights;
    }

    private function timelineFor(Facility $facility, Collection $bookings, Carbon $date, string $timezone): array
    {
        $openTime = $facility->operatingHours?->open_time ?? '08:00:00';
        $closeTime = $facility->operatingHours?->close_time ?? '22:00:00';

        $open = $date->copy()->setTimeFromTimeString($openTime);
        $close = $date->copy()->setTimeFromTimeString($closeTime);

        if ($bookings->isEmpty()) {
            return [
                [
                    'label' => 'Available all day',
                    'copy' => $open->format('g:i A') . ' – ' . $close->format('g:i A'),
                ],
            ];
        }

        $timeline = [];
        $cursor = $open;

        foreach ($bookings as $booking) {
            $start = $date->copy()->setTimeFromTimeString($booking->start_at);
            $end = $date->copy()->setTimeFromTimeString($booking->end_at);

            if ($start->gt($cursor)) {
                $timeline[] = [
                    'label' => 'Available',
                    'copy' => $cursor->format('g:i A') . ' – ' . $start->format('g:i A'),
                ];
            }

            $purpose = $booking->details->purpose ?? 'Approved booking';
            $timeline[] = [
                'label' => $purpose,
                'copy' => $start->format('g:i A') . ' – ' . $end->format('g:i A'),
            ];

            if ($end->gt($cursor)) {
                $cursor = $end;
            }
        }

        if ($cursor->lt($close)) {
            $timeline[] = [
                'label' => 'Available',
                'copy' => $cursor->format('g:i A') . ' – ' . $close->format('g:i A'),
            ];
        }

        return $timeline;
    }

    private function tagsFor(Facility $facility): array
    {
        $tags = [$facility->type ?? 'facility'];

        if ($facility->capacity) {
            $tags[] = $this->sizeLabel($facility->capacity);
        }

        if ($facility->floor) {
            $tags[] = ucfirst($facility->floor) . ' floor';
        }

        return $tags;
    }
}
