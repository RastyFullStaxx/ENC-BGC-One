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
    public int $userId;

    public function __construct(NotificationLog $notification)
    {
        $this->notificationId = $notification->id;
        $this->userId = $notification->booking->requester_id ?? 0;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('users.' . $this->userId);
    }

    public function broadcastAs()
    {
        return 'notification.created';
    }
}
