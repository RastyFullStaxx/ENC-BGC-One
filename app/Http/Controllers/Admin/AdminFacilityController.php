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
                return [
                    'id' => $facility->id,
                    'name' => $facility->name,
                    'room_number' => $facility->room_number,
                    'building' => optional($facility->building)->name ?? 'Building A',
                    'floor' => $facility->floor,
                    'type' => $facility->type,
                    'capacity' => $facility->capacity,
                    'status' => ucfirst($facility->status ?? 'Active'),
                    'status_key' => str_contains(strtolower($facility->status ?? ''), 'maint') ? 'maintenance' : 'active',
                    'photo' => optional($facility->photos->first())->url ?? 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?q=80&w=1200&auto=format&fit=crop',
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
            'floor' => 'required|string|max:50',
            'capacity' => 'required|integer|min:1',
            'type' => 'required|string|max:100',
            'status' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'photo_url' => 'nullable|url',
            'equipment' => 'array',
            'equipment.*' => 'string|max:255',
            'custom_equipment' => 'nullable|string|max:255',
            'open_time' => 'nullable|date_format:H:i',
            'close_time' => 'nullable|date_format:H:i',
        ]);

        $facility = Facility::create([
            'name' => $data['name'],
            'room_number' => $data['room_number'] ?? null,
            'building_id' => $data['building_id'],
            'floor' => $data['floor'],
            'capacity' => $data['capacity'],
            'type' => $data['type'],
            'status' => $data['status'] ?? 'active',
        ]);

        if (!empty($data['photo_url'])) {
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

    private function formatHours(?OperatingHours $hours): ?string
    {
        if (!$hours?->open_time || !$hours?->close_time) {
            return null;
        }

        return sprintf('%s – %s', substr($hours->open_time, 0, 5), substr($hours->close_time, 0, 5));
    }
}
