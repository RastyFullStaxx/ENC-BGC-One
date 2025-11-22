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
use Illuminate\View\View;

class AdminFacilityController extends Controller
{
    public function index(): View
    {
        $facilities = Facility::with(['building', 'equipment', 'operatingHours', 'photos'])
            ->orderBy('name')
            ->get()
            ->map(function (Facility $facility) {
                // Map DB enum to display format
                $statusDisplay = str_replace('_', ' ', $facility->status ?? 'Available');
                $statusKey = str_contains(strtolower($facility->status ?? ''), 'Maintenance') ? 'maintenance' : 'active';

                // Get photo URL and use asset() for local images
                $photoUrl = optional($facility->photos->first())->url;
                if ($photoUrl && !filter_var($photoUrl, FILTER_VALIDATE_URL)) {
                    $photoUrl = asset($photoUrl);
                } elseif (!$photoUrl) {
                    $photoUrl = 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?q=80&w=1200&auto=format&fit=crop';
                }

                return [
                    'id' => $facility->id,
                    'facility_code' => $facility->facility_code,
                    'name' => $facility->name,
                    'room_number' => $facility->room_number,
                    'building' => optional($facility->building)->name ?? 'Building A',
                    'building_id' => $facility->building_id,
                    'floor' => $facility->floor,
                    'type' => $facility->type,
                    'capacity' => $facility->capacity,
                    'status' => $statusDisplay,
                    'status_key' => $statusKey,
                    'photo' => $photoUrl,
                    'equipment' => $facility->equipment->pluck('name')->toArray(),
                    'hours' => $this->formatHours($facility->operatingHours),
                    'notes' => $facility->description ?? null,
                ];
            })
            ->toArray();

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
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'room_number' => 'nullable|string|max:50',
            'building_id' => 'required|exists:buildings,id',
            'floor' => 'required|in:ground,2nd,3rd',
            'capacity' => 'required|integer|min:1',
            'type' => 'required|in:meeting,training,multipurpose',
            'status' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo_url' => 'nullable|string',
            'equipment' => 'array',
            'equipment.*' => 'string|max:255',
            'custom_equipment' => 'nullable|string|max:255',
            'open_time' => 'nullable|date_format:H:i',
            'close_time' => 'nullable|date_format:H:i',
        ]);

        // Auto-generate facility_code
        $latestFacility = Facility::orderBy('facility_code', 'desc')->first();
        $nextNumber = $latestFacility ? ((int)substr($latestFacility->facility_code, 1) + 1) : 1;
        $facilityCode = 'F' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // Map status to DB enum values
        $statusMap = [
            'active' => 'Available',
            'maintenance' => 'Under_Maintenance',
        ];
        $dbStatus = $statusMap[strtolower($data['status'] ?? 'active')] ?? 'Available';

        $facility = Facility::create([
            'facility_code' => $facilityCode,
            'name' => $data['name'],
            'room_number' => $data['room_number'] ?? null,
            'building_id' => $data['building_id'],
            'floor' => $data['floor'],
            'capacity' => $data['capacity'],
            'type' => $data['type'],
            'status' => $dbStatus,
            'description' => $data['description'] ?? null,
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = time() . '_' . $photo->getClientOriginalName();
            $photo->move(public_path('images/facilities'), $filename);
            $facility->photos()->create(['url' => '/images/facilities/' . $filename]);
        } elseif (!empty($data['photo_url'])) {
            $facility->photos()->create(['url' => $data['photo_url']]);
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

        $statusDisplay = str_replace('_', ' ', $facility->status ?? 'Available');
        $statusKey = str_contains(strtolower($facility->status ?? ''), 'Maintenance') ? 'maintenance' : 'active';

        // Get photo URL and use asset() for local images
        $photoUrl = optional($facility->photos->first())->url;
        if ($photoUrl && !filter_var($photoUrl, FILTER_VALIDATE_URL)) {
            $photoUrl = asset($photoUrl);
        }

        return response()->json([
            'id' => $facility->id,
            'facility_code' => $facility->facility_code,
            'name' => $facility->name,
            'room_number' => $facility->room_number,
            'building' => optional($facility->building)->name,
            'building_id' => $facility->building_id,
            'floor' => $facility->floor,
            'type' => $facility->type,
            'capacity' => $facility->capacity,
            'status' => $statusDisplay,
            'status_key' => $statusKey,
            'photo' => $photoUrl,
            'equipment' => $facility->equipment->pluck('name'),
            'hours' => $this->formatHours($facility->operatingHours),
            'open_time' => $facility->operatingHours?->open_time ? substr($facility->operatingHours->open_time, 0, 5) : null,
            'close_time' => $facility->operatingHours?->close_time ? substr($facility->operatingHours->close_time, 0, 5) : null,
            'notes' => $facility->description,
        ]);
    }

    public function update(Request $request, Facility $facility): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'room_number' => 'nullable|string|max:50',
            'building_id' => 'required|exists:buildings,id',
            'floor' => 'required|in:ground,2nd,3rd',
            'capacity' => 'required|integer|min:1',
            'type' => 'required|in:meeting,training,multipurpose',
            'status' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo_url' => 'nullable|string',
            'equipment' => 'array',
            'equipment.*' => 'string|max:255',
            'custom_equipment' => 'nullable|string|max:255',
            'open_time' => 'nullable|date_format:H:i',
            'close_time' => 'nullable|date_format:H:i',
        ]);

        // Map status to DB enum values
        $statusMap = [
            'active' => 'Available',
            'maintenance' => 'Under_Maintenance',
        ];
        $dbStatus = $statusMap[strtolower($data['status'] ?? 'active')] ?? 'Available';

        $facility->update([
            'name' => $data['name'],
            'room_number' => $data['room_number'] ?? null,
            'building_id' => $data['building_id'],
            'floor' => $data['floor'],
            'capacity' => $data['capacity'],
            'type' => $data['type'],
            'status' => $dbStatus,
            'description' => $data['description'] ?? null,
        ]);

        // Update photo if provided
        if ($request->hasFile('photo')) {
            // Delete old photos
            $facility->photos()->delete();
            
            $photo = $request->file('photo');
            $filename = time() . '_' . $photo->getClientOriginalName();
            $photo->move(public_path('images/facilities'), $filename);
            $facility->photos()->create(['url' => '/images/facilities/' . $filename]);
        } elseif (!empty($data['photo_url'])) {
            $facility->photos()->delete();
            $facility->photos()->create(['url' => $data['photo_url']]);
        }

        // Update equipment
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

        // Update operating hours
        if (!empty($data['open_time']) || !empty($data['close_time'])) {
            OperatingHours::updateOrCreate(
                ['facility_id' => $facility->id],
                [
                    'open_time' => $data['open_time'] ?? '08:00',
                    'close_time' => $data['close_time'] ?? '20:00',
                ]
            );
        }

        return redirect()->route('admin.facilities')->with('status', 'Facility updated');
    }

    public function destroy(Facility $facility): RedirectResponse
    {
        $facility->delete();
        return redirect()->route('admin.facilities')->with('status', 'Facility deleted');
    }

    private function formatHours(?OperatingHours $hours): ?string
    {
        if (!$hours?->open_time || !$hours?->close_time) {
            return null;
        }

        return sprintf('%s – %s', substr($hours->open_time, 0, 5), substr($hours->close_time, 0, 5));
    }
}
