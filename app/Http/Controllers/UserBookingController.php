<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingChangeRequest;
use App\Models\NotificationLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserBookingController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $baseQuery = Booking::with([
                'facility.building',
                'details',
                'changeRequests' => function ($query) {
                    $query->latest('created_at');
                },
            ])
            ->where('requester_id', $user->id);

        $bookingsCollection = (clone $baseQuery)
            ->orderByDesc('date')
            ->orderByDesc('start_at')
            ->get();

        $bookingsData = $bookingsCollection
            ->map(fn (Booking $booking) => $this->formatBookingListItem($booking, $user))
            ->values();

        $pendingCount   = (clone $baseQuery)->where('status', 'pending')->count();
        $confirmedCount = (clone $baseQuery)->whereIn('status', ['approved', 'confirmed'])->count();
        $cancelledCount = (clone $baseQuery)->where('status', 'cancelled')->count();

        $bookingStats = [
            'pending'   => $pendingCount,
            'confirmed' => $confirmedCount,
            'cancelled' => $cancelledCount,
        ];

        $notificationsCount = $this->notificationCountFor($user);

        return view('user.booking.index', [
            'bookingsData' => $bookingsData,
            'bookingStats' => $bookingStats,
            'totalBookings' => $bookingsCollection->count(),
            'notificationsCount' => $notificationsCount,
        ]);
    }

    public function show(Booking $booking)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        abort_unless($booking->requester_id === $user->id, 403);

        $booking->load(['facility.building', 'details', 'equipment.equipment', 'changeRequests']);

        $status = $this->normalizeStatus($booking->status ?? 'pending');
        $statusTone = $this->statusTone($status);

        $bookingSummary = [
            'id' => $booking->id,
            'reference' => $booking->reference_code ?? 'Pending Reference',
            'status' => $status,
            'status_label' => ucfirst($status),
            'status_tone' => $statusTone,
            'date_label' => $booking->date
                ? Carbon::parse($booking->date, 'Asia/Manila')->format('l, F j, Y')
                : 'Date to be confirmed',
            'time_range' => $this->formatTimeRange($booking),
            'duration' => $this->formatDuration($booking),
            'created_at' => $booking->created_at
                ? Carbon::parse($booking->created_at)->timezone('Asia/Manila')->format('M d, Y · g:i A')
                : null,
            'facility' => [
                'name' => $booking->facility->name ?? 'Facility',
                'type' => $booking->facility->type ?? 'Room',
                'location' => $this->formatLocation($booking),
                'capacity' => $booking->facility->capacity ?? null,
            ],
            'purpose' => $booking->details->purpose ?? 'No agenda provided',
            'attendees' => $booking->details->attendees_count ?? null,
            'notes' => $booking->details->additional_notes ?? null,
            'sfi_support' => [
                'enabled' => (bool) ($booking->details->sfi_support ?? false),
                'count' => $booking->details->sfi_count ?? null,
            ],
            'equipment' => $booking->equipment->map(function ($item) {
                return [
                    'name' => $item->equipment->name ?? 'Equipment',
                    'quantity' => $item->quantity ?? 1,
                ];
            })->filter(fn ($item) => !empty($item['name']))->values()->toArray(),
        ];

        $bookingsTotal = Booking::where('requester_id', $user->id)->count();

        $adminChangeRequest = $booking->changeRequests
            ->first(fn ($request) => $request->requested_by_role === 'admin'
                && in_array($request->status, ['open', 'acknowledged']));

        $userChangeRequest = $booking->changeRequests
            ->first(fn ($request) => $request->requested_by === $user->id
                && in_array($request->status, ['open', 'acknowledged']));

        $actionLinks = [
            'can_edit' => $this->canEditBooking($booking),
            'edit_url' => $this->canEditBooking($booking) ? route('user.booking.edit', $booking) : null,
            'can_request_change' => $this->canRequestChange($booking, $user),
            'request_change_url' => $this->canRequestChange($booking, $user)
                ? route('user.booking.request-change.form', $booking)
                : null,
        ];

        $notificationsCount = $this->notificationCountFor($user);

        return view('user.booking.show', [
            'bookingSummary' => $bookingSummary,
            'bookingsCount' => $bookingsTotal,
            'notificationsCount' => $notificationsCount,
            'changeRequest' => $adminChangeRequest ? [
                'id' => $adminChangeRequest->id,
                'status' => $adminChangeRequest->status,
                'notes' => $adminChangeRequest->notes,
                'opened_at' => optional($adminChangeRequest->created_at)
                    ? Carbon::parse($adminChangeRequest->created_at)->timezone('Asia/Manila')->format('M j, Y · g:i A')
                    : null,
            ] : null,
            'userChangeRequest' => $userChangeRequest ? [
                'id' => $userChangeRequest->id,
                'status' => $userChangeRequest->status,
                'notes' => $userChangeRequest->notes,
                'opened_at' => optional($userChangeRequest->created_at)
                    ? Carbon::parse($userChangeRequest->created_at)->timezone('Asia/Manila')->format('M j, Y · g:i A')
                    : null,
            ] : null,
            'actions' => $actionLinks,
        ]);
    }

    public function edit(Booking $booking)
    {
        $user = Auth::user();
        if (!$user || $booking->requester_id !== $user->id) {
            abort(403);
        }

        if (! $this->canEditBooking($booking)) {
            return redirect()->route('user.booking.show', $booking)
                ->with('statusMessage', 'This booking can no longer be edited.');
        }

        $booking->load(['facility.building', 'details']);

        $bookingForm = [
            'id' => $booking->id,
            'reference' => $booking->reference_code ?? 'N/A',
            'facility' => $booking->facility->name ?? 'Facility',
            'date_value' => $booking->date
                ? Carbon::parse($booking->date, 'Asia/Manila')->format('Y-m-d')
                : null,
            'date_label' => $booking->date
                ? Carbon::parse($booking->date, 'Asia/Manila')->format('l, F j, Y')
                : 'Date to be confirmed',
            'start_time_value' => $booking->start_at
                ? Carbon::parse($booking->start_at, 'Asia/Manila')->format('H:i')
                : null,
            'end_time_value' => $booking->end_at
                ? Carbon::parse($booking->end_at, 'Asia/Manila')->format('H:i')
                : null,
            'time_label' => $this->formatTimeRange($booking),
            'purpose' => $booking->details->purpose ?? '',
            'attendees' => $booking->details->attendees_count ?? null,
            'notes' => $booking->details->additional_notes ?? null,
        ];

        return view('user.booking.edit', [
            'booking' => $bookingForm,
            'bookingsCount' => Booking::where('requester_id', $user->id)->count(),
            'notificationsCount' => $this->notificationCountFor($user),
        ]);
    }

    public function update(Request $request, Booking $booking)
    {
        $user = Auth::user();
        if (!$user || $booking->requester_id !== $user->id) {
            abort(403);
        }

        $hasAdminChangeRequest = $this->hasOpenAdminChangeRequest($booking);

        if (! $this->canEditBooking($booking) && ! $hasAdminChangeRequest) {
            return redirect()->route('user.booking.show', $booking)
                ->with('statusMessage', 'This booking can no longer be edited.');
        }

        $data = $request->validate([
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'purpose' => 'required|string|max:1000',
            'attendees' => 'nullable|integer|min:1|max:500',
            'additional_notes' => 'nullable|string|max:500',
        ]);

        $startDateTime = Carbon::parse($data['date'] . ' ' . $data['start_time'], 'Asia/Manila');
        if ($startDateTime->lte(now('Asia/Manila')->addHours(24))) {
            return back()->withErrors([
                'date' => 'Bookings can only be edited at least 24 hours before the start time.',
            ])->withInput();
        }

        $startTime = Carbon::createFromFormat('H:i', $data['start_time'], 'Asia/Manila')->format('H:i:s');
        $endTime = Carbon::createFromFormat('H:i', $data['end_time'], 'Asia/Manila')->format('H:i:s');

        $hasConflict = Booking::where('facility_id', $booking->facility_id)
            ->where('date', $data['date'])
            ->where('id', '!=', $booking->id)
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_at', [$startTime, $endTime])
                    ->orWhereBetween('end_at', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_at', '<=', $startTime)
                          ->where('end_at', '>=', $endTime);
                    });
            })
            ->exists();

        if ($hasConflict) {
            return back()->withErrors([
                'date' => 'Another request already exists for this facility within the selected time.',
            ])->withInput();
        }

        $booking->update([
            'date' => $data['date'],
            'start_at' => $startTime,
            'end_at' => $endTime,
        ]);

        $booking->details()->updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'purpose' => $data['purpose'],
                'attendees_count' => $data['attendees'] ?? optional($booking->details)->attendees_count ?? 0,
                'additional_notes' => $data['additional_notes'] ?? null,
                'sfi_support' => optional($booking->details)->sfi_support ?? false,
                'sfi_count' => optional($booking->details)->sfi_count ?? 0,
            ]
        );

        $booking->changeRequests()
            ->where('requested_by_role', 'admin')
            ->whereIn('status', ['open', 'acknowledged'])
            ->update([
                'status' => 'resolved',
                'resolved_by' => $user->id,
                'resolved_at' => now(),
                'resolution_notes' => 'Requester provided updated booking details.',
            ]);

        NotificationLog::logEvent($booking, 'booking_updated_user', 'EMAIL', null, 'admin');

        return redirect()->route('user.booking.show', $booking)
            ->with('statusMessage', 'Booking updated successfully.');
    }

    public function requestChangeForm(Booking $booking)
    {
        $user = Auth::user();
        if (!$user || $booking->requester_id !== $user->id) {
            abort(403);
        }

        if (! $this->canRequestChange($booking, $user)) {
            return redirect()->route('user.booking.show', $booking)
                ->with('statusMessage', 'This booking can no longer accept change requests.');
        }

        $booking->load(['facility.building', 'changeRequests']);

        $existingRequest = $booking->changeRequests
            ->first(fn ($request) => $request->requested_by === $user->id
                && in_array($request->status, ['open', 'acknowledged']));

        $adminRequest = $booking->changeRequests
            ->first(fn ($request) => $request->requested_by_role === 'admin'
                && in_array($request->status, ['open', 'acknowledged']));

        $canEdit = $this->canEditBooking($booking) || (bool) $adminRequest;

        $bookingForm = [
            'id' => $booking->id,
            'reference' => $booking->reference_code ?? 'N/A',
            'facility' => $booking->facility->name ?? 'Facility',
            'date_label' => $booking->date
                ? Carbon::parse($booking->date, 'Asia/Manila')->format('l, F j, Y')
                : 'Date to be confirmed',
            'time_label' => $this->formatTimeRange($booking),
            'date_value' => $booking->date
                ? Carbon::parse($booking->date, 'Asia/Manila')->format('Y-m-d')
                : null,
            'start_time_value' => $booking->start_at
                ? Carbon::parse($booking->start_at, 'Asia/Manila')->format('H:i')
                : null,
            'end_time_value' => $booking->end_at
                ? Carbon::parse($booking->end_at, 'Asia/Manila')->format('H:i')
                : null,
            'purpose' => $booking->details->purpose ?? '',
            'attendees' => $booking->details->attendees_count ?? null,
            'notes' => $booking->details->additional_notes ?? null,
        ];

        return view('user.booking.request-change', [
            'booking' => $bookingForm,
            'canEditBooking' => $canEdit,
            'adminRequest' => $adminRequest ? [
                'status' => $adminRequest->status,
                'notes' => $adminRequest->notes,
                'opened_at' => optional($adminRequest->created_at)
                    ? Carbon::parse($adminRequest->created_at)->timezone('Asia/Manila')->format('M j, Y · g:i A')
                    : null,
            ] : null,
            'existingRequest' => $existingRequest ? [
                'status' => $existingRequest->status,
                'notes' => $existingRequest->notes,
                'opened_at' => optional($existingRequest->created_at)
                    ? Carbon::parse($existingRequest->created_at)->timezone('Asia/Manila')->format('M j, Y · g:i A')
                    : null,
            ] : null,
            'bookingsCount' => Booking::where('requester_id', $user->id)->count(),
            'notificationsCount' => $this->notificationCountFor($user),
        ]);
    }

    public function storeChangeRequest(Request $request, Booking $booking)
    {
        $user = Auth::user();
        if (!$user || $booking->requester_id !== $user->id) {
            abort(403);
        }

        if (! $this->canRequestChange($booking, $user)) {
            return redirect()->route('user.booking.show', $booking)
                ->with('statusMessage', 'This booking can no longer accept change requests.');
        }

        $data = $request->validate([
            'notes' => 'required|string|min:10|max:1000',
            'type' => 'nullable|string|max:40',
        ]);

        $existingRequest = $booking->changeRequests()
            ->where('requested_by', $user->id)
            ->whereIn('status', ['open', 'acknowledged'])
            ->first();

        if ($existingRequest) {
            return redirect()->route('user.booking.request-change.form', $booking)
                ->withErrors(['notes' => 'You already have a change request awaiting review.']);
        }

        BookingChangeRequest::create([
            'booking_id' => $booking->id,
            'requested_by' => $user->id,
            'requested_by_role' => 'user',
            'type' => $data['type'] ?? 'adjustment',
            'notes' => $data['notes'],
        ]);

        NotificationLog::logEvent($booking, 'change_requested_user', 'EMAIL', null, 'admin');

        $booking->status = 'pending';
        $booking->save();

        if ($booking->approval) {
            $booking->approval->update([
                'status' => 'pending',
                'remarks' => 'Requester submitted change request',
            ]);
        }

        return redirect()->route('user.booking.show', $booking)
            ->with('statusMessage', 'Change request submitted. We\'ll notify you once it\'s reviewed.');
    }

    public function acknowledgeChangeRequest(BookingChangeRequest $changeRequest)
    {
        $user = Auth::user();
        if (!$user || $changeRequest->booking->requester_id !== $user->id) {
            abort(403);
        }

        if (in_array($changeRequest->status, ['resolved', 'cancelled'])) {
            return back()->with('statusMessage', 'This change request was already closed.');
        }

        $changeRequest->update([
            'status' => 'acknowledged',
            'acknowledged_at' => now(),
            'acknowledged_by' => $user->id,
        ]);

        return redirect()->back()->with('statusMessage', 'Marked as reviewed.');
    }

    public function cancelBooking(Booking $booking)
    {
        $user = Auth::user();
        if (!$user || $booking->requester_id !== $user->id) {
            abort(403);
        }

        if (in_array($booking->status, ['cancelled', 'rejected'])) {
            return redirect()->route('user.booking.show', $booking)
                ->with('statusMessage', 'This booking was already ' . $booking->status . '.');
        }

        $booking->status = 'cancelled';
        $booking->save();

        NotificationLog::logEvent($booking, 'booking_cancelled');
        NotificationLog::logEvent($booking, 'booking_cancelled_user', 'EMAIL', null, 'admin');

        return redirect()->route('user.booking.show', $booking)
            ->with('statusMessage', 'Booking cancelled successfully.');
    }

    private function formatBookingListItem(Booking $booking, $user): array
    {
        $status = $this->normalizeStatus($booking->status ?? 'pending');
        $dateLabel = $booking->date
            ? Carbon::parse($booking->date, 'Asia/Manila')->format('D, M j, Y')
            : 'Date TBA';

        $adminChangeRequest = $booking->changeRequests
            ->first(fn ($request) => $request->requested_by_role === 'admin'
                && in_array($request->status, ['open', 'acknowledged']));

        $userChangeRequest = $user
            ? $booking->changeRequests
                ->first(fn ($request) => $request->requested_by === $user->id
                    && in_array($request->status, ['open', 'acknowledged']))
            : null;

        return [
            'id' => $booking->id,
            'facility' => $booking->facility->name ?? 'Facility',
            'facilityType' => $booking->facility->type ?? 'Facility',
            'date' => $dateLabel,
            'time' => $this->formatTimeRange($booking),
            'purpose' => $booking->details->purpose ?? 'Booking request',
            'status' => $status,
            'viewUrl' => route('user.booking.show', $booking),
            'editUrl' => $this->canEditBooking($booking) ? route('user.booking.edit', $booking) : null,
            'requestChangeUrl' => $this->canRequestChange($booking, $user)
                ? route('user.booking.request-change.form', $booking)
                : null,
            'attention' => $adminChangeRequest ? [
                'id' => $adminChangeRequest->id,
                'label' => 'Changes requested',
                'reviewUrl' => route('user.booking.show', $booking) . '#change-request',
            ] : null,
            'userChangeRequest' => $userChangeRequest ? [
                'id' => $userChangeRequest->id,
                'status' => $userChangeRequest->status,
            ] : null,
        ];
    }

    private function canEditBooking(Booking $booking): bool
    {
        if (strtolower($booking->status ?? '') !== 'pending') {
            return false;
        }

        $hasUserChangeRequest = $booking->changeRequests
            ->first(fn ($request) => $request->requested_by_role === 'user'
                && in_array($request->status, ['open', 'acknowledged']));

        if ($hasUserChangeRequest) {
            return false;
        }

        return $this->bookingStartsAfterThreshold($booking);
    }

    private function canRequestChange(Booking $booking, ?\App\Models\User $user = null): bool
    {
        $status = strtolower($booking->status ?? '');
        if (! in_array($status, ['approved', 'confirmed'])) {
            return false;
        }

        if (! $this->bookingStartsAfterThreshold($booking)) {
            return false;
        }

        $user ??= Auth::user();
        if (! $user) {
            return false;
        }

        $hasOpenRequest = $booking->changeRequests
            ->first(fn ($request) => $request->requested_by === $user->id
                && in_array($request->status, ['open', 'acknowledged']));

        return ! (bool) $hasOpenRequest;
    }

    private function hasOpenAdminChangeRequest(Booking $booking): bool
    {
        return $booking->changeRequests()
            ->where('requested_by_role', 'admin')
            ->whereIn('status', ['open', 'acknowledged'])
            ->exists();
    }

    private function bookingStartsAfterThreshold(Booking $booking, int $hours = 24): bool
    {
        if (!$booking->date || !$booking->start_at) {
            return false;
        }

        $dateString = $booking->date instanceof Carbon
            ? $booking->date->format('Y-m-d')
            : $booking->date;
        $start = Carbon::parse($dateString . ' ' . $booking->start_at, 'Asia/Manila');
        return $start->greaterThan(now('Asia/Manila')->addHours($hours));
    }

    private function notificationCountFor($user): int
    {
        if (! $user) {
            return 0;
        }

        return NotificationLog::forRecipient($user)->count();
    }

    private function formatTimeRange(Booking $booking): string
    {
        $start = $booking->start_at
            ? Carbon::parse($booking->start_at, 'Asia/Manila')->format('g:i A')
            : 'TBA';
        $end = $booking->end_at
            ? Carbon::parse($booking->end_at, 'Asia/Manila')->format('g:i A')
            : 'TBA';

        return $start . ' – ' . $end;
    }

    private function formatDuration(Booking $booking): ?string
    {
        if (!$booking->start_at || !$booking->end_at) {
            return null;
        }

        $start = Carbon::parse($booking->start_at, 'Asia/Manila');
        $end = Carbon::parse($booking->end_at, 'Asia/Manila');
        $minutes = $start->diffInMinutes($end);

        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        $parts = [];
        if ($hours > 0) {
            $parts[] = $hours . ' hr' . ($hours > 1 ? 's' : '');
        }
        if ($remainingMinutes > 0) {
            $parts[] = $remainingMinutes . ' min';
        }

        return implode(' ', $parts);
    }

    private function normalizeStatus(?string $status): string
    {
        $normalized = strtolower($status ?? 'pending');

        if ($normalized === 'approved') {
            return 'confirmed';
        }

        return $normalized;
    }

    private function statusTone(string $status): string
    {
        return match ($status) {
            'confirmed', 'completed' => 'is-success',
            'cancelled' => 'is-danger',
            'pending' => 'is-warning',
            default => 'is-neutral',
        };
    }

    private function formatLocation(Booking $booking): string
    {
        $parts = [];
        if ($booking->facility?->building?->name) {
            $parts[] = $booking->facility->building->name;
        }
        if ($booking->facility?->floor) {
            $parts[] = ucfirst($booking->facility->floor) . ' Floor';
        }
        return implode(' · ', $parts);
    }
}
