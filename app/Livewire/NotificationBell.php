<?php

namespace App\Livewire;

use App\Models\NotificationLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationBell extends Component
{
    public string $pollInterval = '30s';
    public bool $muteBadge = false;
    public int $lastCount = 0;

    protected $listeners = [
        'refreshNotifications' => '$refresh',
    ];

    public function render()
    {
        $user = Auth::user();

        if (!$user) {
            $this->lastCount = 0;
            return view('livewire.notification-bell', [
                'count' => 0,
                'notifications' => collect(),
                'muteBadge' => false,
            ]);
        }

        // Fetch latest 6 notifications for this user
        $notifications = NotificationLog::with([
                'booking.facility',
                'booking.details',
            ])
            ->whereHas('booking', fn($q) => $q->where('requester_id', $user->id))
            ->latest('created_at')
            ->limit(6)
            ->get()
            ->map(function ($log) {
                $meta = $this->eventMeta($log->event, $log->booking?->status);

                return [
                    'id' => $log->id,
                    'channel' => $log->channel,
                    'event' => $log->event,
                    'event_label' => $meta['label'],
                    'event_class' => $meta['class'],
                    'created_at' => optional($log->created_at)->diffForHumans(),
                    'facility' => $log->booking?->facility?->name ?? 'Booking',
                    'purpose' => $log->booking?->details?->purpose ?? 'Booking update',
                    'time' => $log->booking?->date ? Carbon::parse($log->booking->date, config('app.timezone'))->format('M j, g:i A') : null,
                    'status' => ucfirst($log->booking?->status ?? 'pending'),
                    'seen_at' => $log->seen_at,
                ];
            });

        // Count only unread notifications
        $count = NotificationLog::whereHas('booking', fn($q) => $q->where('requester_id', $user->id))
            ->whereNull('seen_at')
            ->count();

        // Unmute badge when new notifications arrive
        if ($count > $this->lastCount) {
            $this->muteBadge = false;
        }
        $this->lastCount = $count;

        return view('livewire.notification-bell', [
            'count' => $count,
            'notifications' => $notifications,
            'muteBadge' => $this->muteBadge,
        ]);
    }

    /**
     * Mark all notifications as seen
     */
    public function markSeen(): void
    {
        $user = Auth::user();
        if (!$user) return;

        // Update all unseen notifications to set seen_at
        NotificationLog::whereHas('booking', fn($q) => $q->where('requester_id', $user->id))
            ->whereNull('seen_at')
            ->update(['seen_at' => now()]);

        $this->muteBadge = true;
        $this->lastCount = 0;
    }
    public function markSeenAndKeepOpen()
    {
        $this->markSeen();
        $this->dispatch('$refresh'); // or just let poll handle it
        // Tell Bootstrap NOT to close
        $this->dispatch('dropdown-stay-open');
    }

    private function eventMeta(?string $event, ?string $status): array
    {
        $map = [
            'booking_created' => ['label' => 'Booking submitted', 'class' => 'badge bg-primary text-white small'],
            'booking_cancelled' => ['label' => 'Booking cancelled', 'class' => 'badge bg-secondary text-white small'],
            'booking_approved' => ['label' => 'Booking approved', 'class' => 'badge bg-success text-white small'],
            'booking_rejected' => ['label' => 'Booking rejected', 'class' => 'badge bg-danger text-white small'],
            'change_requested_admin' => ['label' => 'Action required', 'class' => 'badge bg-warning text-dark small'],
            'change_requested_user' => ['label' => 'Change submitted', 'class' => 'badge bg-info text-dark small'],
        ];

        if (isset($map[$event])) {
            return $map[$event];
        }

        return [
            'label' => ucfirst($status ?? 'Update'),
            'class' => 'badge bg-primary-subtle text-primary border-primary-subtle small',
        ];
    }
}
