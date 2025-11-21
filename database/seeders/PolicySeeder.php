<?php

namespace Database\Seeders;

use App\Models\Policy;
use Illuminate\Database\Seeder;

class PolicySeeder extends Seeder
{
    /**
     * Seed initial booking policies and rules.
     */
    public function run(): void
    {
        $policies = [
            [
                'name' => 'Booking Lead Time · 24h',
                'domain_key' => 'bookings',
                'status' => 'active',
                'active' => true,
                'owner' => 'Legal Ops',
                'reminder' => 'Review quarterly',
                'desc' => 'Bookings must be created at least 24 hours before the scheduled start time.',
                'impact' => 'Blocks last-minute requests; shown before submission.',
                'tags' => ['Lead Time', 'Cutoff'],
                'rules' => [
                    ['title' => 'Lead Time', 'summary' => 'Bookings must be created ≥ 24 hours before the event start.', 'position' => 1],
                    ['title' => 'Late Exception', 'summary' => 'Requests inside 24h require approver sign-off before confirmation.', 'position' => 2],
                ],
            ],
            [
                'name' => 'Recurring Booking Guard',
                'domain_key' => 'bookings',
                'status' => 'active',
                'active' => true,
                'owner' => 'Scheduling',
                'reminder' => 'Auto-review Sep 15',
                'desc' => 'Keep recurring series manageable and leave room for other teams.',
                'impact' => 'Warns requesters and caps recurring spans.',
                'tags' => ['Recurrence', 'Duration'],
                'rules' => [
                    ['title' => 'Series Length', 'summary' => 'Recurring bookings are limited to 6 weeks at a time.', 'position' => 1],
                    ['title' => 'Gap Rule', 'summary' => 'Buffer at least 15 minutes between recurring sessions for room reset.', 'position' => 2],
                ],
            ],
            [
                'name' => 'Cancellation & No-Show',
                'domain_key' => 'bookings',
                'status' => 'active',
                'active' => true,
                'owner' => 'Workplace Ops',
                'reminder' => 'Review quarterly',
                'desc' => 'Reduce no-shows and free space early for others.',
                'impact' => 'Applies reminders and cooldowns for late changes.',
                'tags' => ['Cancellation', 'Attendance'],
                'rules' => [
                    ['title' => 'Cancel Window', 'summary' => 'Cancel or update at least 12 hours before start to avoid cooldowns.', 'position' => 1],
                    ['title' => 'No-Show', 'summary' => 'No-shows may trigger a 7-day cooldown for peak-time rooms.', 'position' => 2],
                ],
            ],
            [
                'name' => 'Room Care & Usage',
                'domain_key' => 'bookings',
                'status' => 'active',
                'active' => true,
                'owner' => 'Facilities',
                'reminder' => 'Ops review monthly',
                'desc' => 'Keep rooms tidy and ready for the next team.',
                'impact' => 'Adds reminders and post-use checks.',
                'tags' => ['Etiquette', 'Cleanup'],
                'rules' => [
                    ['title' => 'Cleanliness', 'summary' => 'Leave rooms tidy; dispose of trash and return furniture to default layout.', 'position' => 1],
                    ['title' => 'Equipment Care', 'summary' => 'Report any equipment issues immediately after the session.', 'position' => 2],
                ],
            ],
        ];

        foreach ($policies as $policyData) {
            $rules = $policyData['rules'] ?? [];
            unset($policyData['rules']);

            $policy = Policy::create($policyData);
            if ($rules) {
                $policy->rules()->createMany($rules);
            }
        }
    }
}
