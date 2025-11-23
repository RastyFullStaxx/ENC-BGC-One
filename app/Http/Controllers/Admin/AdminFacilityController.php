<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Equipment;
use App\Models\Facility;
use App\Models\OperatingHours;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminFacilityController extends Controller
{
    public function index(Request $request): View
    {
        $filters = [
            'search' => $request->string('search')->toString(),
            'building' => $request->string('building')->toString(),
            'type' => $request->string('type')->toString(),
            'status' => $request->string('status')->toString(),
        ];

        $facilities = Facility::with(['building', 'equipment', 'operatingHours', 'photos'])
            ->when($filters['search'], function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('type', 'like', "%{$search}%")
                        ->orWhere('floor', 'like', "%{$search}%");
                });
            })
            ->when($filters['building'], fn ($q, $building) => $q->whereHas('building', fn ($b) => $b->where('name', $building)))
            ->when($filters['type'], fn ($q, $type) => $q->where('type', $type))
            ->when($filters['status'], function ($query, $status) {
                if ($status === 'active') {
                    $query->where('status', 'not like', '%maint%');
                } elseif ($status === 'maintenance') {
                    $query->where('status', 'like', '%maint%');
                }
            })
            ->orderBy('name')
            ->paginate(20);

        $facilities->getCollection()->transform(function (Facility $facility) {
            return [
                'id' => $facility->id,
                'name' => $facility->name,
                'room_number' => $facility->room_number,
                'building_id' => $facility->building_id,
                'building' => optional($facility->building)->name ?? 'Building A',
                'floor' => $facility->floor,
                'type' => $facility->type,
                'capacity' => $facility->capacity,
                'status' => $this->statusLabel($facility->status),
                'status_key' => $this->statusKey($facility->status),
                'photo' => $this->normalizePhotoUrl(optional($facility->photos->first())->url),
                'equipment' => $facility->equipment->pluck('name')->toArray(),
                'photos' => $facility->photos->pluck('url')->map(fn ($url) => $this->normalizePhotoUrl($url))->toArray(),
                'hours' => $this->formatHours($facility->operatingHours),
                'notes' => $facility->description ?? null,
            ];
        });

        if ($facilities->total() === 0) {
            $fallback = collect($this->fallbackFacilities());
            $facilities = new LengthAwarePaginator(
                $fallback,
                $fallback->count(),
                20,
                1,
                ['path' => request()->url(), 'query' => request()->query()]
            );
        }

        $equipmentOptions = Equipment::orderBy('name')->pluck('name')->toArray();
        $buildings = Building::select('id', 'name')->orderBy('name')->get()->toArray();

        return view('admin.facilities', [
            'facilities' => $facilities,
            'facilitiesCount' => count($facilities),
            'maintenanceQueue' => Facility::where('status', 'like', '%maintenance%')->count(),
            'bookableCapacity' => Facility::sum('capacity'),
            'verifiedPhotos' => Facility::whereHas('photos')->count(),
            'equipmentOptions' => $equipmentOptions,
            'buildings' => $buildings,
            'filters' => $filters,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'room_number' => 'nullable|string|max:50',
            'building_id' => 'required|exists:buildings,id',
            'floor' => 'required|string|max:50',
            'capacity' => 'required|integer|min:1',
            'type' => 'required|string|max:100',
            'status' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'photo_url' => 'nullable|url',
            'photo_urls' => 'array',
            'photo_urls.*' => 'nullable|url',
            'equipment' => 'array',
            'equipment.*' => 'string|max:255',
            'custom_equipment' => 'nullable|string|max:255',
            'open_time' => 'nullable|date_format:H:i',
            'close_time' => 'nullable|date_format:H:i',
            'photos' => 'sometimes',
            'photos.*' => 'nullable|image|max:4096',
        ]);

        $status = $this->normalizeStatus($data['status'] ?? null, 'Available');

        $facility = Facility::create([
            'name' => $data['name'],
            'room_number' => $data['room_number'] ?? null,
            'building_id' => $data['building_id'],
            'floor' => $data['floor'],
            'capacity' => $data['capacity'],
            'type' => $data['type'],
            'status' => $status,
        ]);

        $photosToStore = $this->gatherPhotoUrls($request);
        if ($photosToStore->isNotEmpty()) {
            $photosToStore->each(fn ($url) => $facility->photos()->create(['url' => $url]));
        }

        $equipment = $data['equipment'] ?? [];
        if (!empty($data['custom_equipment'])) {
            $equipment[] = $data['custom_equipment'];
        }

        if (!empty($equipment)) {
            $equipmentIds = collect($equipment)
                ->map(fn ($name) => trim($name))
                ->filter()
                ->unique()
                ->map(function ($name) {
                    return Equipment::firstOrCreate(['name' => $name])->id;
                })
                ->values()
                ->all();

            $facility->equipment()->sync($equipmentIds);
        }

        if (!empty($data['open_time']) || !empty($data['close_time'])) {
            OperatingHours::updateOrCreate(
                ['facility_id' => $facility->id],
                [
                    'open_time' => $data['open_time'] ?? '08:00',
                    'close_time' => $data['close_time'] ?? '20:00',
                    'timezone' => 'Asia/Manila',
                ]
            );
        }

        return redirect()->route('admin.facilities')->with('status', 'Facility created');
    }

    public function show(Facility $facility): JsonResponse
    {
        $facility->load(['building', 'equipment', 'operatingHours', 'photos']);

        return response()->json([
            'id' => $facility->id,
            'name' => $facility->name,
            'room_number' => $facility->room_number,
            'building' => optional($facility->building)->name,
            'floor' => $facility->floor,
            'type' => $facility->type,
            'capacity' => $facility->capacity,
            'status' => $facility->status,
            'status_key' => str_contains(strtolower($facility->status ?? ''), 'maint') ? 'maintenance' : 'active',
            'photo' => optional($facility->photos->first())->url,
            'equipment' => $facility->equipment->pluck('name'),
            'hours' => $this->formatHours($facility->operatingHours),
            'notes' => $facility->description,
        ]);
    }

    public function update(Request $request, Facility $facility): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'room_number' => 'nullable|string|max:50',
            'building_id' => 'required|exists:buildings,id',
            'floor' => 'required|string|max:50',
            'capacity' => 'required|integer|min:1',
            'type' => 'required|string|max:100',
            'status' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'photo_url' => 'nullable|url',
            'photo_urls' => 'array',
            'photo_urls.*' => 'nullable|url',
            'equipment' => 'array',
            'equipment.*' => 'string|max:255',
            'custom_equipment' => 'nullable|string|max:255',
            'open_time' => 'nullable|date_format:H:i',
            'close_time' => 'nullable|date_format:H:i',
            'photos' => 'sometimes',
            'photos.*' => 'nullable|image|max:4096',
        ]);

        $facility->update([
            'name' => $data['name'],
            'room_number' => $data['room_number'] ?? null,
            'building_id' => $data['building_id'],
            'floor' => $data['floor'],
            'capacity' => $data['capacity'],
            'type' => $data['type'],
            'status' => $this->normalizeStatus($data['status'] ?? null, $facility->status),
            'description' => $data['description'] ?? $facility->description,
        ]);

        $photosToStore = $this->gatherPhotoUrls($request);
        if ($photosToStore->isNotEmpty()) {
            $facility->photos()->delete();
            $photosToStore->each(fn ($url) => $facility->photos()->create(['url' => $url]));
        }

        $equipment = $data['equipment'] ?? [];
        if (!empty($data['custom_equipment'])) {
            $equipment[] = $data['custom_equipment'];
        }

        if (!empty($equipment)) {
            $equipmentIds = collect($equipment)
                ->map(fn ($name) => trim($name))
                ->filter()
                ->unique()
                ->map(function ($name) {
                    return Equipment::firstOrCreate(['name' => $name])->id;
                })
                ->values()
                ->all();

            $facility->equipment()->sync($equipmentIds);
        }

        if (!empty($data['open_time']) || !empty($data['close_time'])) {
            OperatingHours::updateOrCreate(
                ['facility_id' => $facility->id],
                [
                    'open_time' => $data['open_time'] ?? '08:00',
                    'close_time' => $data['close_time'] ?? '20:00',
                    'timezone' => 'Asia/Manila',
                ]
            );
        }

        return redirect()->route('admin.facilities')->with('status', 'Facility updated');
    }

    public function toggleStatus(Facility $facility): RedirectResponse
    {
        $currentKey = $this->statusKey($facility->status);
        $facility->status = $currentKey === 'maintenance' ? 'Available' : 'Under_Maintenance';
        $facility->save();

        return redirect()->back()->with('status', $facility->name . ' status updated.');
    }

    private function formatHours(?OperatingHours $hours): ?string
    {
        if (!$hours?->open_time || !$hours?->close_time) {
            return null;
        }

        return sprintf('%s – %s', substr($hours->open_time, 0, 5), substr($hours->close_time, 0, 5));
    }

    private function gatherPhotoUrls(Request $request)
    {
        $urls = collect($request->input('photo_urls', []))->filter();
        if ($request->filled('photo_url')) {
            $urls->prepend($request->input('photo_url'));
        }

        if ($request->hasFile('photos')) {
            collect($request->file('photos'))
                ->filter()
                ->each(function ($file) use (&$urls) {
                    $path = $file->store('public/facilities');
                    $urls->push(Storage::url($path));
                });
        }

        return $urls->filter()->values();
    }

    private function statusLabel(?string $status): string
    {
        return match ($this->statusKey($status)) {
            'maintenance' => 'Under Maintenance',
            'occupied' => 'Occupied',
            'limited' => 'Limited Availability',
            default => 'Available',
        };
    }

    private function statusKey(?string $status): string
    {
        $value = strtolower($status ?? '');
        return match (true) {
            str_contains($value, 'maint') => 'maintenance',
            str_contains($value, 'occup') => 'occupied',
            str_contains($value, 'limited') => 'limited',
            default => 'active',
        };
    }

    private function normalizeStatus(?string $incoming, ?string $fallback = 'Available'): string
    {
        $value = strtolower($incoming ?? '');
        return match ($value) {
            'maintenance', 'under_maintenance', 'under maintenance' => 'Under_Maintenance',
            'occupied' => 'Occupied',
            'limited', 'limited_availability', 'limited availability' => 'Limited_Availability',
            'active', 'available' => 'Available',
            default => $fallback ?? 'Available',
        };
    }

    private function normalizePhotoUrl(?string $url): string
    {
        if (!$url) {
            return asset('images/meeting_room_a.jpg');
        }

        // Already absolute
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        // If stored via storage:link
        if (str_starts_with($url, '/storage') || str_starts_with($url, 'storage')) {
            return asset(ltrim($url, '/'));
        }

        // If user pasted a public path like public/images/...
        if (str_starts_with($url, 'public/')) {
            return asset(str_replace('public/', 'storage/', $url));
        }

        return asset(ltrim($url, '/'));
    }

    /**
     * Provide a small fixture set when no facilities exist yet.
     */
    private function fallbackFacilities(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Meeting Room A',
                'room_number' => '205',
                'building_id' => Building::first()?->id ?? 1,
                'building' => 'Building A',
                'floor' => '2F',
                'type' => 'Meeting Room',
                'capacity' => 12,
                'status' => 'Available',
                'status_key' => 'active',
                'photo' => asset('images/meeting_room_a.jpg'),
                'equipment' => ['TV', 'Whiteboard', 'Speakerphone'],
                'hours' => '08:00 – 20:00',
                'notes' => 'Cozy huddle space with VC ready.',
            ],
            [
                'id' => 2,
                'name' => 'Meeting Room B',
                'room_number' => '206',
                'building_id' => Building::first()?->id ?? 1,
                'building' => 'Building A',
                'floor' => '2F',
                'type' => 'Meeting Room',
                'capacity' => 10,
                'status' => 'Available',
                'status_key' => 'active',
                'photo' => asset('images/meeting_room_b.jpg'),
                'equipment' => ['TV', 'Whiteboard'],
                'hours' => '08:00 – 20:00',
                'notes' => 'Great for interviews and quick collabs.',
            ],
        ];
    }
}
