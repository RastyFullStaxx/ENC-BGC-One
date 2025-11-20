<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class AuditLogger
{
    public function log(array $data): void
    {
        if (! Schema::hasTable('audit_logs')) {
            return; // Avoid errors before migrations run
        }

        $actor = $data['actor'] ?? $this->resolveActor();

        AuditLog::create([
            'actor_name' => $actor['name'],
            'actor_email' => $actor['email'],
            'action' => $data['action'] ?? 'Action',
            'module' => $data['module'] ?? 'General',
            'target' => $data['target'] ?? null,
            'action_type' => $data['action_type'] ?? null,
            'risk' => $data['risk'] ?? 'low',
            'status' => $data['status'] ?? 'success',
            'source' => $data['source'] ?? 'Admin UI',
            'environment' => $data['environment'] ?? 'Production',
            'ip' => request()->ip(),
            'location' => $data['location'] ?? null,
            'device' => $data['device'] ?? request()->userAgent(),
            'session_id' => $data['session_id'] ?? session()->getId(),
            'correlation_id' => $data['correlation_id'] ?? null,
            'notes' => $data['notes'] ?? null,
            'before' => $data['before'] ?? null,
            'after' => $data['after'] ?? null,
            'changes' => $data['changes'] ?? null,
        ]);
    }

    protected function resolveActor(): array
    {
        $user = Auth::user();

        if ($user) {
            return [
                'name' => $user->name ?? 'Unknown',
                'email' => $user->email ?? 'unknown@example.com',
            ];
        }

        return [
            'name' => 'System',
            'email' => 'system@enc.gov',
        ];
    }
}
