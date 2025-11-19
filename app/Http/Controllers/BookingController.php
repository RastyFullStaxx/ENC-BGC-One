<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\BookingEquipment;
use App\Models\Facility;
use App\Models\Equipment;
use App\Models\NotificationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Display the booking wizard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get notification count
        $notificationsCount = NotificationLog::whereHas('booking', function ($query) use ($user) {
            $query->where('requester_id', $user->id);
        })->count();

        return view('booking.wizard', [
            'notificationsCount' => $notificationsCount,
        ]);
    }

    /**
     * Get all facilities with optional filters.
     */
    public function getFacilities(Request $request)
    {
        $query = Facility::with(['building', 'equipment', 'photos', 'operatingHours']);

        // Filter by floor
        if ($request->has('floor') && $request->floor != '') {
            $query->where('floor', $request->floor);
        }

        // Filter by capacity/size
        if ($request->has('size') && $request->size != '') {
            switch ($request->size) {
                case 'small':
                    $query->where('capacity', '<=', 10);
                    break;
                case 'medium':
                    $query->whereBetween('capacity', [11, 15]);
                    break;
                case 'large':
                    $query->where('capacity', '>=', 16);
                    break;
            }
        }

        // Search by name, location, or feature
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('room_number', 'like', "%{$search}%")
                  ->orWhereHas('building', function ($buildingQuery) use ($search) {
                      $buildingQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('equipment', function ($equipmentQuery) use ($search) {
                      $equipmentQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $facilities = $query->get()->map(function ($facility) use ($request) {
            $availability = $this->getFacilityAvailability($facility, $request->date ?? Carbon::now('Asia/Manila')->format('Y-m-d'));
            
            // Convert photo URLs from storage paths to web-accessible URLs
            $photos = $facility->photos->map(function ($photo) {
                // Remove 'public/' prefix and prepend '/' for web root
                return '/' . str_replace('public/', '', $photo->url);
            })->toArray();
            
            return [
                'id' => $facility->id,
                'name' => $facility->name,
                'room_number' => $facility->room_number,
                'capacity' => $facility->capacity,
                'status' => $facility->status,
                'floor' => $facility->floor,
                'type' => $facility->type,
                'building' => $facility->building ? $facility->building->name : '',
                'location' => ($facility->building ? $facility->building->name : '') . ' · ' . ucfirst($facility->floor) . ' Floor',
                'equipment' => $facility->equipment->pluck('name')->toArray(),
                'photos' => $photos,
                'availability' => $availability,
            ];
        });

        return response()->json($facilities);
    }

    /**
     * Get availability status for a facility on a specific date.
     */
    private function getFacilityAvailability($facility, $date)
    {
        // Use Manila timezone
        $now = Carbon::now('Asia/Manila');
        $checkDate = Carbon::parse($date, 'Asia/Manila')->startOfDay();

        // Get bookings for this facility on this date
        // Use DB::raw to compare only date part
        $bookings = Booking::where('facility_id', $facility->id)
            ->where(DB::raw('DATE(date)'), '=', $checkDate->toDateString())
            ->whereIn('status', ['pending', 'approved'])
            ->orderBy('start_at')
            ->get();

        // If no bookings, available all day
        if ($bookings->isEmpty()) {
            return [
                'status' => 'Available',
                'variant' => 'success',
                'text' => 'Available all day',
            ];
        }

        // Check if currently occupied (for today only)
        if ($checkDate->isToday()) {
            $currentTime = $now->format('H:i:s');
            
            // Find current booking (if any)
            $currentBooking = $bookings->first(function ($booking) use ($currentTime) {
                return $booking->start_at <= $currentTime && $booking->end_at >= $currentTime;
            });

            if ($currentBooking) {
                // Room is currently occupied
                $endTime = Carbon::parse($currentBooking->end_at, 'Asia/Manila');
                $purpose = $currentBooking->details->purpose ?? 'Meeting in progress';
                $requesterName = $currentBooking->requester->firstName ?? $currentBooking->requester->name ?? 'User';
                $department = $currentBooking->requester->department->name ?? 'N/A';
                
                return [
                    'status' => 'Occupied',
                    'variant' => 'danger',
                    'text' => $purpose,
                    'subtext' => 'by ' . $requesterName . ' (' . $department . ')',
                    'next' => 'Next available at ' . $endTime->format('g:i A'),
                ];
            }

            // Find next booking after current time
            $nextBooking = $bookings->first(function ($booking) use ($currentTime) {
                return $booking->start_at > $currentTime;
            });

            if ($nextBooking) {
                // Room is free now but has upcoming booking(s)
                $nextStartTime = Carbon::parse($nextBooking->start_at, 'Asia/Manila');
                $freeUntilTime = $nextStartTime->copy()->subMinutes(30); // 30 minutes before next booking
                
                // If next booking is within 30 minutes, show it starts soon
                if ($nextStartTime->diffInMinutes($now) <= 30) {
                    $freeUntilTime = $nextStartTime;
                }

                return [
                    'status' => 'Limited Availability',
                    'variant' => 'warning',
                    'text' => 'Free until ' . $freeUntilTime->format('g:i A'),
                    'subtext' => 'Next booking at ' . $nextStartTime->format('g:i A'),
                ];
            }

            // Room was booked earlier today but is now free for the rest of the day
            return [
                'status' => 'Available',
                'variant' => 'success',
                'text' => 'Available for the rest of the day',
            ];
        }

        // For future dates, show limited availability if there are bookings
        return [
            'status' => 'Limited Availability',
            'variant' => 'warning',
            'text' => $bookings->count() . ' booking(s) scheduled',
            'subtext' => 'Check schedule for available time slots',
        ];
    }

    /**
     * Check availability for a specific facility, date, and time.
     */
    public function checkAvailability(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'facility_id' => 'required|exists:facilities,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'available' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $facility = Facility::findOrFail($request->facility_id);
        
        // Check if the time slot is within operating hours
        if ($facility->operatingHours) {
            if ($request->start_time < $facility->operatingHours->open_time || 
                $request->end_time > $facility->operatingHours->close_time) {
                return response()->json([
                    'available' => false,
                    'message' => 'Selected time is outside operating hours (' . 
                                Carbon::parse($facility->operatingHours->open_time)->format('g:i A') . ' - ' . 
                                Carbon::parse($facility->operatingHours->close_time)->format('g:i A') . ')',
                ]);
            }
        }

        // Check for conflicting bookings
        $hasConflict = Booking::where('facility_id', $request->facility_id)
            ->where('date', $request->date)
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_at', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_at', [$request->start_time, $request->end_time])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_at', '<=', $request->start_time)
                          ->where('end_at', '>=', $request->end_time);
                    });
            })
            ->exists();

        if ($hasConflict) {
            return response()->json([
                'available' => false,
                'message' => 'This time slot is already booked. Please choose a different time.',
            ]);
        }

        return response()->json([
            'available' => true,
            'message' => 'This time slot is available!',
        ]);
    }

    /**
     * Store a new booking.
     */
    public function store(Request $request)
    {
        // Build validation rules dynamically based on sfi_support
        $rules = [
            'facility_id' => 'required|exists:facilities,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'purpose' => 'required|string|max:1000',
            'attendees_count' => 'required|integer|min:1',
            'sfi_support' => 'boolean',
            'equipment' => 'array',
            'equipment.*' => 'exists:equipment,id',
            'equipment_quantities' => 'array',
            'additional_notes' => 'nullable|string|max:500',
        ];

        // Only validate sfi_count if sfi_support is true
        if ($request->sfi_support === true || $request->sfi_support === 'true' || $request->sfi_support === 1) {
            $rules['sfi_count'] = 'required|integer|min:1|max:10';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Check availability again
        $availabilityCheck = $this->checkAvailability($request);
        if ($availabilityCheck->getData()->available === false) {
            return response()->json([
                'success' => false,
                'message' => $availabilityCheck->getData()->message,
            ], 409);
        }

        try {
            DB::beginTransaction();

            // Generate reference code before creating booking
            $referenceCode = 'ENC-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));

            // Create the booking
            $booking = Booking::create([
                'facility_id' => $request->facility_id,
                'requester_id' => Auth::id(),
                'date' => $request->date,
                'start_at' => $request->start_time,
                'end_at' => $request->end_time,
                'status' => 'pending',
                'reference_code' => $referenceCode,
            ]);

            // Create booking details
            BookingDetail::create([
                'booking_id' => $booking->id,
                'purpose' => $request->purpose,
                'attendees_count' => $request->attendees_count,
                'sfi_support' => $request->sfi_support ?? false,
                'sfi_count' => $request->sfi_support ? ($request->sfi_count ?? 0) : 0,
                'additional_notes' => $request->additional_notes,
            ]);

            // Create booking equipment if requested
            if ($request->has('equipment') && is_array($request->equipment)) {
                foreach ($request->equipment as $index => $equipmentId) {
                    BookingEquipment::create([
                        'booking_id' => $booking->id,
                        'facility_id' => $request->facility_id,
                        'equipment_id' => $equipmentId,
                        'quantity' => $request->equipment_quantities[$index] ?? 1,
                    ]);
                }
            }

            NotificationLog::logEvent($booking, 'booking_created');

            DB::commit();

            // Load relationships for response
            $booking->load(['facility.building', 'details', 'equipment.equipment', 'requester']);

            return response()->json([
                'success' => true,
                'message' => 'Booking request submitted successfully!',
                'data' => [
                    'booking_id' => $booking->id,
                    'reference_code' => $booking->reference_code,
                    'status' => $booking->status,
                    'facility' => $booking->facility->name,
                    'date' => Carbon::parse($booking->date)->format('F j, Y'),
                    'time' => Carbon::parse($booking->start_at)->format('g:i A') . ' - ' . Carbon::parse($booking->end_at)->format('g:i A'),
                    'requester' => $booking->requester->name,
                ],
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create booking: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get user's bookings.
     */
    public function getUserBookings(Request $request)
    {
        $status = $request->get('status'); // pending, approved, rejected, cancelled
        
        $query = Booking::with(['facility.building', 'details'])
            ->where('requester_id', Auth::id())
            ->orderBy('date', 'desc')
            ->orderBy('start_at', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        $bookings = $query->get()->map(function ($booking) {
            return [
                'id' => $booking->id,
                'status' => ucfirst($booking->status),
                'room' => $booking->facility->name,
                'date' => Carbon::parse($booking->date)->format('m/d/Y'),
                'time' => Carbon::parse($booking->start_at)->format('g:i A') . ' - ' . Carbon::parse($booking->end_at)->format('g:i A'),
                'purpose' => $booking->details->purpose ?? '',
                'attendees' => $booking->details->attendees_count ?? 0,
            ];
        });

        return response()->json($bookings);
    }

    /**
     * Get booking details by ID.
     */
    public function show($id)
    {
        $booking = Booking::with([
            'facility.building', 
            'facility.equipment',
            'details', 
            'equipment.equipment',
            'requester.department'
        ])->findOrFail($id);

        // Check if user is authorized to view this booking
        if ($booking->requester_id !== Auth::id() && Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $booking->id,
                'reference_code' => $booking->reference_code,
                'status' => $booking->status,
                'facility' => [
                    'id' => $booking->facility->id,
                    'name' => $booking->facility->name,
                    'room_number' => $booking->facility->room_number,
                    'capacity' => $booking->facility->capacity,
                    'building' => $booking->facility->building->name ?? '',
                    'floor' => $booking->facility->floor,
                ],
                'date' => Carbon::parse($booking->date)->format('F j, Y'),
                'start_time' => Carbon::parse($booking->start_at)->format('g:i A'),
                'end_time' => Carbon::parse($booking->end_at)->format('g:i A'),
                'duration' => $this->calculateDuration($booking->start_at, $booking->end_at),
                'details' => [
                    'purpose' => $booking->details->purpose ?? '',
                    'attendees_count' => $booking->details->attendees_count ?? 0,
                    'sfi_support' => $booking->details->sfi_support ?? false,
                    'sfi_count' => $booking->details->sfi_count ?? 0,
                    'additional_notes' => $booking->details->additional_notes ?? '',
                ],
                'equipment' => $booking->equipment->map(function ($be) {
                    return [
                        'name' => $be->equipment->name,
                        'quantity' => $be->quantity,
                    ];
                }),
                'requester' => [
                    'name' => $booking->requester->name,
                    'email' => $booking->requester->email,
                    'department' => $booking->requester->department->name ?? 'N/A',
                ],
                'created_at' => $booking->created_at->format('F j, Y g:i A'),
            ],
        ]);
    }

    /**
     * Calculate duration between two times.
     */
    private function calculateDuration($startTime, $endTime)
    {
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);
        $diffInMinutes = $start->diffInMinutes($end);
        
        $hours = floor($diffInMinutes / 60);
        $minutes = $diffInMinutes % 60;
        
        if ($hours > 0 && $minutes > 0) {
            return $hours . ' hour(s) ' . $minutes . ' minute(s)';
        } elseif ($hours > 0) {
            return $hours . ' hour(s)';
        } else {
            return $minutes . ' minute(s)';
        }
    }

    /**
     * Cancel a booking.
     */
    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);

        // Check if user is authorized to cancel this booking
        if ($booking->requester_id !== Auth::id() && Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access',
            ], 403);
        }

        // Check if booking can be cancelled
        if (in_array($booking->status, ['cancelled', 'rejected'])) {
            return response()->json([
                'success' => false,
                'message' => 'This booking has already been ' . $booking->status,
            ], 400);
        }

        $booking->status = 'cancelled';
        $booking->save();

        NotificationLog::logEvent($booking, 'booking_cancelled');

        return response()->json([
            'success' => true,
            'message' => 'Booking cancelled successfully',
        ]);
    }

    /**
     * Get user's notification logs.
     */
    public function getUserNotifications(Request $request)
    {
        $limit = $request->get('limit', 10);
        $userId = Auth::id();

        if (! $userId) {
            return response()->json([]);
        }
        
        $notifications = NotificationLog::with(['booking.facility'])
            ->whereHas('booking', function ($query) use ($userId) {
                $query->where('requester_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($notification) {
                $booking = $notification->booking;
                $message = $this->getNotificationMessage($booking, $notification);
                
                return [
                    'id' => $notification->id,
                    'booking_id' => $booking->id,
                    'facility_name' => $booking->facility->name ?? 'Facility',
                    'message' => $message,
                    'date' => Carbon::parse($booking->date)->format('M j, Y'),
                    'time' => Carbon::parse($booking->start_at)->format('g:i A'),
                    'status' => $booking->status,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'is_read' => false, // Can be implemented later
                ];
            });

        return response()->json($notifications);
    }

    /**
     * Generate notification message based on booking status.
     */
    private function getNotificationMessage($booking, ?NotificationLog $notification = null)
    {
        $facilityName = $booking->facility->name ?? 'Facility';
        $event = $notification?->event;

        return match ($event) {
            'booking_created' => "We received your booking request for {$facilityName}.",
            'booking_cancelled' => "You cancelled your booking for {$facilityName}.",
            'change_requested_admin' => "Shared Services requested changes to your {$facilityName} booking.",
            default => match ($booking->status) {
                'pending' => "Your booking request for {$facilityName} is awaiting approval",
                'approved', 'confirmed' => "Your booking for {$facilityName} has been confirmed",
                'cancelled' => "Your booking for {$facilityName} was cancelled",
                'rejected' => "Your booking request for {$facilityName} was declined",
                default => "Update on your booking for {$facilityName}",
            },
        };
    }
}
