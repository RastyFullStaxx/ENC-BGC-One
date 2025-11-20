<?php

namespace App\Observers;

use App\Models\Facility;
use App\Services\AuditLogger;

class FacilityObserver
{
    public function updated(Facility $facility): void
    {
        $watched = ['status', 'capacity', 'type', 'floor', 'building_id'];
        $dirty = array_intersect_key($facility->getDirty(), array_flip($watched));

        if (empty($dirty)) {
            return;
        }

        $before = [];
        $after = [];
        $changes = [];

        foreach ($dirty as $field => $newValue) {
            $oldValue = $facility->getOriginal($field);
            $before[$field] = $oldValue;
            $after[$field] = $newValue;
            $changes[] = "{$field}: {$oldValue} → {$newValue}";
        }

        app(AuditLogger::class)->log([
            'action' => 'Updated facility',
            'module' => 'Facilities',
            'target' => $facility->name ?? $facility->id,
            'action_type' => 'update',
            'risk' => 'medium',
            'status' => 'success',
            'before' => $before,
            'after' => $after,
            'changes' => $changes,
        ]);
    }
}
