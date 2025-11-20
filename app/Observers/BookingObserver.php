<?php

namespace App\Observers;

use App\Models\Booking;
use App\Services\AuditLogger;

class BookingObserver
{
    public function updated(Booking $booking): void
    {
        if (property_exists($booking, 'skip_audit_observer') && $booking->skip_audit_observer) {
            return;
        }

        $watched = ['facility_id', 'date', 'start_at', 'end_at', 'status', 'requester_id'];
        $dirty = array_intersect_key($booking->getDirty(), array_flip($watched));

        if (empty($dirty)) {
            return;
        }

        $before = [];
        $after = [];
        $changes = [];

        foreach ($dirty as $field => $newValue) {
            $oldValue = $booking->getOriginal($field);
            $before[$field] = $oldValue;
            $after[$field] = $newValue;
            $changes[] = "{$field}: {$oldValue} → {$newValue}";
        }

        app(AuditLogger::class)->log([
            'action' => 'Updated booking',
            'module' => 'Bookings',
            'target' => $booking->reference_code ?? $booking->id,
            'action_type' => 'update',
            'risk' => 'medium',
            'status' => 'success',
            'before' => $before,
            'after' => $after,
            'changes' => $changes,
        ]);
    }
}
