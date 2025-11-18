<?php

namespace App\Livewire;

use App\Models\NotificationLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationBell extends Component
{
    public string $pollInterval = '30s';

    protected $listeners = [
        'refreshNotifications' => '$refresh',
    ];

    public function render()
    {
        $user = Auth::user();

        if (! $user) {
            return view('livewire.notification-bell', [
                'count' => 0,
                'notifications' => collect(),
            ]);
        }

        $notifications = NotificationLog::with([
                'booking.facility',
                'booking.details',
            ])
            ->whereHas('booking', function ($q) use ($user) {
                $q->where('requester_id', $user->id);
            })
            ->latest('created_at')
            ->limit(6)
            ->get()
            ->map(function ($log) {
                $booking = $log->booking;
                $facility = $booking?->facility?->name ?? 'Booking';
                $purpose = $booking?->details?->purpose ?? 'Booking update';
                $time = $booking?->date ? Carbon::parse($booking->date, config('app.timezone'))->format('M j, g:i A') : null;
                $status = ucfirst($booking?->status ?? 'pending');

                return [
                    'id' => $log->id,
                    'channel' => $log->channel,
                    'created_at' => optional($log->created_at)->diffForHumans(),
                    'facility' => $facility,
                    'purpose' => $purpose,
                    'status' => $status,
                    'time' => $time,
                ];
            });

        $count = NotificationLog::whereHas('booking', function ($q) use ($user) {
            $q->where('requester_id', $user->id);
        })->count();

        return view('livewire.notification-bell', [
            'count' => $count,
            'notifications' => $notifications,
        ]);
    }
}
