<?php

namespace App\Events;

use App\Models\NotificationLog;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $notificationId;
    public ?int $recipientId;
    public string $recipientRole;

    public function __construct(NotificationLog $notification)
    {
        $this->notificationId = $notification->id;
        $this->recipientId = $notification->recipient_id ?? $notification->booking->requester_id ?? null;
        $this->recipientRole = $notification->recipient_role ?? 'user';
    }

    public function broadcastOn()
    {
        if ($this->recipientRole === 'admin') {
            return new PrivateChannel('admins');
        }

        return new PrivateChannel('users.' . ($this->recipientId ?? 0));
    }

    public function broadcastAs()
    {
        return 'notification.created';
    }
}
