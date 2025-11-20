<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminAuditController extends Controller
{
    public function index(Request $request)
    {
        $logs = collect();

        try {
            if (Schema::hasTable('audit_logs')) {
                $logs = AuditLog::query()
                    ->latest('created_at')
                    ->limit(120)
                    ->get()
                    ->map(fn (AuditLog $log) => $this->transform($log));
            }
        } catch (QueryException $e) {
            // fall back to demo data until migrations are applied
        }

        if ($logs->isEmpty()) {
            $logs = collect($this->demoEntries());
        }

        $metrics = $this->buildMetrics($logs);

        return view('admin.audit', [
            'entries' => $logs,
            'metrics' => $metrics,
        ]);
    }

    public function exportEntry(AuditLog $auditLog): StreamedResponse
    {
        $payload = $this->transform($auditLog);
        return response()->streamDownload(function () use ($payload) {
            echo json_encode($payload, JSON_PRETTY_PRINT);
        }, 'audit-entry-' . $auditLog->id . '.json');
    }

    public function flag(Request $request, AuditLog $auditLog): Response
    {
        $auditLog->flagged = true;
        $auditLog->save();

        return redirect()->back()->with('statusMessage', 'Entry flagged for review.');
    }

    public function exportJson(): StreamedResponse
    {
        $payload = AuditLog::latest('created_at')
            ->limit(500)
            ->get()
            ->map(fn (AuditLog $log) => $this->transform($log));

        return response()->streamDownload(function () use ($payload) {
            echo $payload->toJson(JSON_PRETTY_PRINT);
        }, 'audit-log.json');
    }

    public function exportCsv(): StreamedResponse
    {
        $rows = AuditLog::latest('created_at')
            ->limit(500)
            ->get()
            ->map(fn (AuditLog $log) => $this->transform($log));

        $headers = [
            'ID', 'Timestamp', 'Actor', 'Email', 'Action', 'Module', 'Target', 'Action Type',
            'Risk', 'Status', 'Source', 'Environment', 'IP', 'Location', 'Device',
            'Session', 'Correlation', 'Notes',
        ];

        return response()->streamDownload(function () use ($rows, $headers) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);

            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row['id'],
                    $row['timestamp'],
                    $row['actor'],
                    $row['email'],
                    $row['action'],
                    $row['module'],
                    $row['target'],
                    $row['action_type'],
                    $row['risk'],
                    $row['status'],
                    $row['source'],
                    $row['environment'],
                    $row['ip'],
                    $row['location'],
                    $row['device'],
                    $row['session'],
                    $row['correlation'],
                    $row['notes'],
                ]);
            }

            fclose($handle);
        }, 'audit-log.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    protected function transform(AuditLog $log): array
    {
        $created = $log->created_at ?? now();

        return [
            'id' => $log->id,
            'day' => $this->dayLabel($created),
            'timestamp' => $created->format('M j, Y · g:i A'),
            'actor' => $log->actor_name ?? 'Unknown actor',
            'email' => $log->actor_email ?? '—',
            'action' => $log->action,
            'module' => $log->module ?? 'General',
            'target' => $log->target ?? '—',
            'before' => $log->before ?? [],
            'after' => $log->after ?? [],
            'action_type' => $log->action_type ?? 'update',
            'risk' => $log->risk ?? 'low',
            'status' => $log->status ?? 'success',
            'source' => $log->source ?? 'System',
            'actor_type' => $this->actorTypeFromSource($log->source ?? ''),
            'environment' => $log->environment ?? 'Production',
            'ip' => $log->ip ?? '—',
            'location' => $log->location ?? '—',
            'device' => $log->device ?? '—',
            'session' => $log->session_id ?? '—',
            'correlation' => $log->correlation_id ?? '—',
            'notes' => $log->notes ?? '',
            'changes' => is_array($log->changes) ? $log->changes : [],
        ];
    }

    protected function actorTypeFromSource(string $source): string
    {
        $source = strtolower($source);
        if (str_contains($source, 'api')) {
            return 'api';
        }
        if (str_contains($source, 'automation') || str_contains($source, 'system')) {
            return 'system';
        }
        if (str_contains($source, 'auth') || str_contains($source, 'user')) {
            return 'user';
        }
        return 'admin';
    }

    protected function dayLabel(Carbon $created): string
    {
        if ($created->isToday()) {
            return 'Today';
        }

        if ($created->isYesterday()) {
            return 'Yesterday';
        }

        if ($created->greaterThanOrEqualTo(now()->startOfWeek())) {
            return 'This week';
        }

        return $created->format('M j');
    }

    protected function buildMetrics($logs): array
    {
        $todayCount = $logs->where('day', 'Today')->count();
        $highRisk = $logs->where('risk', 'high')->count();
        $failed = $logs->where('status', 'failed')->count();
        $topModule = $logs
            ->groupBy('module')
            ->sortByDesc(fn ($items) => $items->count())
            ->keys()
            ->first() ?? 'General';

        return [
            'today' => $todayCount,
            'highRisk' => $highRisk,
            'failed' => $failed,
            'topModule' => $topModule,
        ];
    }

    protected function demoEntries(): array
    {
        return [
            [
                'id' => 'EVT-10921',
                'day' => 'Today',
                'timestamp' => 'Aug 15, 2024 · 2:41 PM',
                'actor' => 'Ava Santos',
                'email' => 'ava.santos@enc.gov',
                'action' => 'Updated facility capacity',
                'module' => 'Facilities',
                'target' => 'Orion Boardroom',
                'before' => ['capacity' => 12],
                'after' => ['capacity' => 16, 'setup' => 'Conference'],
                'action_type' => 'update',
                'risk' => 'medium',
                'status' => 'success',
                'source' => 'Admin UI',
                'actor_type' => 'admin',
                'environment' => 'Production',
                'ip' => '10.15.34.16',
                'location' => 'BGC, PH',
                'device' => 'Chrome · macOS',
                'session' => 'sess-8af3',
                'correlation' => 'corr-7321',
                'notes' => 'Requested by facilities lead before board meeting.',
                'changes' => ['Capacity increased 12 → 16', 'Availability updated for 3 dates'],
            ],
            [
                'id' => 'EVT-10918',
                'day' => 'Today',
                'timestamp' => 'Aug 15, 2024 · 1:10 PM',
                'actor' => 'Brian Lopez',
                'email' => 'brian.lopez@enc.gov',
                'action' => 'Approved booking BKG-8742',
                'module' => 'Bookings',
                'target' => 'BKG-8742',
                'before' => ['status' => 'Pending'],
                'after' => ['status' => 'Approved'],
                'action_type' => 'approval',
                'risk' => 'low',
                'status' => 'success',
                'source' => 'Admin UI',
                'actor_type' => 'admin',
                'environment' => 'Production',
                'ip' => '10.18.5.24',
                'location' => 'Makati, PH',
                'device' => 'Edge · Windows',
                'session' => 'sess-5b21',
                'correlation' => 'corr-7077',
                'notes' => 'Approval auto-notified requestor.',
                'changes' => ['Status changed Pending → Approved'],
            ],
            [
                'id' => 'EVT-10906',
                'day' => 'Today',
                'timestamp' => 'Aug 15, 2024 · 10:05 AM',
                'actor' => 'Dana Uy',
                'email' => 'dana.uy@enc.gov',
                'action' => 'Adjusted approval routing rule',
                'module' => 'Approvals',
                'target' => 'Routing Rule #33',
                'before' => ['condition' => 'over 10 seats → Facilities'],
                'after' => ['condition' => 'over 8 seats → Facilities', 'fallback' => 'Security'],
                'action_type' => 'update',
                'risk' => 'high',
                'status' => 'success',
                'source' => 'Admin UI',
                'actor_type' => 'admin',
                'environment' => 'Production',
                'ip' => '10.20.88.12',
                'location' => 'Pasig, PH',
                'device' => 'Chrome · Windows',
                'session' => 'sess-93af',
                'correlation' => 'corr-7001',
                'notes' => 'High-risk because it changes escalation policy.',
                'changes' => ['Seat threshold adjusted 10 → 8', 'Fallback approver routed to Security'],
            ],
            [
                'id' => 'EVT-10890',
                'day' => 'Yesterday',
                'timestamp' => 'Aug 14, 2024 · 6:02 PM',
                'actor' => 'System Automation',
                'email' => 'system@enc.gov',
                'action' => 'Logged policy violation alert',
                'module' => 'Policies',
                'target' => 'BKG-8670',
                'before' => ['status' => 'Pending'],
                'after' => ['status' => 'Flagged'],
                'action_type' => 'alert',
                'risk' => 'high',
                'status' => 'failed',
                'source' => 'Automation',
                'actor_type' => 'system',
                'environment' => 'Production',
                'ip' => '172.16.0.5',
                'location' => 'Quezon City, PH',
                'device' => 'Service Worker',
                'session' => 'n/a',
                'correlation' => 'corr-6903',
                'notes' => 'Booking exceeded policy footprint. Escalated to duty officer.',
                'changes' => ['Violation detected on restricted zone', 'Auto-hold placed on booking'],
            ],
            [
                'id' => 'EVT-10884',
                'day' => 'Yesterday',
                'timestamp' => 'Aug 14, 2024 · 5:10 PM',
                'actor' => 'Diego Ramos',
                'email' => 'diego.ramos@enc.gov',
                'action' => 'Deactivated user guest.torres@enc.gov',
                'module' => 'Users',
                'target' => 'guest.torres@enc.gov',
                'before' => ['active' => true],
                'after' => ['active' => false, 'role' => 'Guest'],
                'action_type' => 'deactivate',
                'risk' => 'medium',
                'status' => 'success',
                'source' => 'Admin UI',
                'actor_type' => 'admin',
                'environment' => 'Production',
                'ip' => '10.6.24.88',
                'location' => 'Remote · PH',
                'device' => 'Firefox · Linux',
                'session' => 'sess-18af',
                'correlation' => 'corr-6710',
                'notes' => 'Account stale; archived per access review.',
                'changes' => ['Account disabled', 'Role locked to Guest'],
            ],
            [
                'id' => 'EVT-10840',
                'day' => 'This week',
                'timestamp' => 'Aug 13, 2024 · 10:45 AM',
                'actor' => 'Liam Chua (API)',
                'email' => 'service@enc.gov',
                'action' => 'Bulk updated facility tags',
                'module' => 'Facilities',
                'target' => '14 facilities',
                'before' => ['tags' => ['Shared', 'AV']],
                'after' => ['tags' => ['Shared', 'AV', 'Wheelchair Access']],
                'action_type' => 'bulk-update',
                'risk' => 'medium',
                'status' => 'success',
                'source' => 'API',
                'actor_type' => 'api',
                'environment' => 'Staging',
                'ip' => '35.90.120.1',
                'location' => 'us-west-2',
                'device' => 'API Client',
                'session' => 'svc-aws',
                'correlation' => 'corr-6404',
                'notes' => 'Sync job scheduled weekly.',
                'changes' => ['Bulk tag sync completed', 'Wheelchair Access added'],
            ],
        ];
    }
}
