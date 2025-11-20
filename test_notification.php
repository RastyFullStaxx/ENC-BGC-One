<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\NotificationLog;
use App\Models\Booking;

$booking = Booking::first();

if (!$booking) {
    echo "No bookings found. Please create a booking first.\n";
    exit;
}

echo "Testing NotificationLog creation...\n";
echo "Booking ID: " . $booking->id . "\n";

try {
    $log = NotificationLog::create([
        'booking_id' => $booking->id,
        'recipient_id' => $booking->requester_id,
        'recipient_role' => 'user',
        'channel' => 'EMAIL',
        'event' => 'booking_created',
    ]);
    
    echo "✓ Notification log created successfully!\n";
    echo "  ID: " . $log->id . "\n";
    echo "  Booking ID: " . $log->booking_id . "\n";
    echo "  Channel: " . $log->channel . "\n";
    echo "  Created at: " . $log->created_at . "\n";
    echo "  Updated at: " . $log->updated_at . "\n";
} catch (\Exception $e) {
    echo "✗ Error creating notification log:\n";
    echo "  " . $e->getMessage() . "\n";
}
