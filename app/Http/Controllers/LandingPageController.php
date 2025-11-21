<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\OperatingHours;
use App\Models\Policy;
use App\Models\PolicyRule;
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

        $policyContent = $this->loadPolicyContent();

        return view('marketing.landing', [
            'lastSync' => $now,
            'scheduleStart' => $scheduleStart,
            'scheduleEnd' => $scheduleEnd,
            'scheduleBlocks' => $scheduleBlocks,
            'facilityHero' => $facilityHero,
            'facilityTiles' => collect($facilityTiles)->filter()->values()->all(),
            'featuredServices' => $this->featuredServices(),
            'policyHighlights' => $policyContent['highlights'],
            'policyRules' => $policyContent['rules'],
            'policySnapshot' => $policyContent,
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

    private function loadPolicyContent(): array
    {
        $fallbackPolicies = [
            [
                'id' => null,
                'name' => 'Room booking basics',
                'domain' => 'bookings',
                'status' => 'active',
                'owner' => 'Facilities',
                'reminder' => 'Lead times: 24h for rooms, 48h for special venues.',
                'impact' => 'Keeps rotations clean and concierge prepared.',
                'tags' => ['bookings', 'rooms'],
                'updated_label' => 'This week',
                'updated_detailed' => null,
                'expiring' => false,
                'needs_review' => false,
                'rules' => [
                    'Submit requests at least 24 hours before start for standard rooms.',
                    'Requests beyond 4 hours need manager approval.',
                    'Cancel or update 12 hours before start to avoid cooldowns.',
                ],
            ],
            [
                'id' => null,
                'name' => 'Suites, studios, & labs',
                'domain' => 'sfi',
                'status' => 'active',
                'owner' => 'SFI Concierge',
                'reminder' => 'Premium spaces require published lead times.',
                'impact' => 'Ensures specialists can prep equipment and staffing.',
                'tags' => ['sfi', 'premium'],
                'updated_label' => 'This week',
                'updated_detailed' => null,
                'expiring' => false,
                'needs_review' => false,
                'rules' => [
                    'Follow the posted lead times for labs and studios.',
                    'List equipment needs so specialists can prep before you arrive.',
                ],
            ],
        ];

        $fallbackHighlights = [
            ['label' => 'Lead times', 'value' => '24h rooms · 48h special'],
            ['label' => 'Cancellations', 'value' => '12h before start'],
            ['label' => 'Support hours', 'value' => '7AM – 7PM concierge'],
        ];

        $fallbackRules = [
            'Food & drinks only in approved spaces. Light refreshments welcome in Executive Boardroom.',
            'Standard room bookings max at 4 hours. Longer holds need manager approval.',
            'Special facilities (lab, shuttle, studio) require the published lead times.',
            'Cancel or update at least 12 hours before the slot to avoid cooldowns.',
            'Leave rooms tidy, return keycards, and log any equipment issues with Facilities.',
        ];

        try {
            $policies = Policy::query()
                ->with(['rules' => function ($query) {
                    $query->orderBy('position');
                }])
                ->where('status', 'active')
                ->orderBy('updated_at', 'desc')
                ->take(3)
                ->get();
        } catch (\Throwable $e) {
            return [
                'highlights' => $fallbackHighlights,
                'rules' => $fallbackRules,
                'stats' => [
                    ['label' => 'Live policies', 'value' => '3 defaults'],
                    ['label' => 'Owners', 'value' => 'Facilities & Admin'],
                    ['label' => 'Last updated', 'value' => 'This week'],
                ],
                'policies' => $fallbackPolicies,
            ];
        }

        if ($policies->isEmpty()) {
            return [
                'highlights' => $fallbackHighlights,
                'rules' => $fallbackRules,
                'stats' => [
                    ['label' => 'Live policies', 'value' => '0 live'],
                    ['label' => 'Owners', 'value' => 'Facilities & Admin'],
                    ['label' => 'Last updated', 'value' => '—'],
                ],
                'policies' => $fallbackPolicies,
            ];
        }

        $policyData = $policies->map(function (Policy $policy) {
            $rules = $policy->rules
                ->pluck('summary')
                ->filter()
                ->values()
                ->take(4)
                ->all();

            if (empty($rules) && $policy->desc) {
                $rules[] = $policy->desc;
            }

            return [
                'id' => $policy->id,
                'name' => $policy->name ?? ($policy->domain_key ?? 'Policy'),
                'domain' => $policy->domain_key ?? 'bookings',
                'status' => $policy->status ?? 'active',
                'owner' => $policy->owner ?: 'Admin',
                'reminder' => $policy->reminder ?: 'Live policy',
                'impact' => $policy->impact,
                'tags' => array_values($policy->tags ?? []),
                'updated_label' => optional($policy->updated_at)->format('M j') ?? 'Recently',
                'updated_detailed' => optional($policy->updated_at)->format('M j, Y g:i A'),
                'expiring' => (bool) $policy->expiring,
                'needs_review' => (bool) $policy->needs_review,
                'rules' => $rules,
            ];
        });

        $liveCount = $policyData->count();
        $needsReview = $policyData->filter(fn ($policy) => $policy['needs_review'])->count();
        $lastUpdated = optional($policies->first()?->updated_at)?->diffForHumans(null, false, false, 2) ?? 'Recently';

        $highlights = [
            ['label' => 'Live policies', 'value' => $liveCount . ' active'],
            ['label' => 'Needs review', 'value' => $needsReview ? $needsReview . ' flagged' : 'All clear'],
            ['label' => 'Last update', 'value' => $lastUpdated],
        ];

        $rules = $policyData
            ->flatMap(fn (array $policy) => $policy['rules'])
            ->filter()
            ->take(6)
            ->values()
            ->all();

        if (empty($rules)) {
            $rules = $fallbackRules;
        }

        return [
            'highlights' => $highlights ?: $fallbackHighlights,
            'rules' => $rules ?: $fallbackRules,
            'stats' => $highlights ?: $fallbackHighlights,
            'policies' => $policyData->toArray() ?: $fallbackPolicies,
        ];
    }
}
