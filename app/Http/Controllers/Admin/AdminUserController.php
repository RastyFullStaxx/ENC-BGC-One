<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function __construct(private AuditLogger $auditLogger)
    {
    }
    public function index()
    {
        $users = User::with('department')->orderBy('name')->paginate(10);
        $allUsers = User::with('department')->orderBy('name')->get();
        $departments = Department::orderBy('name')->paginate(10);
        $totalDepartments = Department::count();
        $departmentCounts = User::selectRaw('department_id, COUNT(*) as total')
            ->groupBy('department_id')
            ->pluck('total', 'department_id');

        $roleMeta = $this->roleMeta();
        $roleSummaries = collect($roleMeta)->map(function (array $meta, string $key) use ($allUsers) {
            return [
                'key' => $key,
                'title' => $meta['title'],
                'description' => $meta['description'],
                'count' => $allUsers->where('role', $key)->count(),
            ];
        })->values();

        $activeRecent = $allUsers->whereNotNull('last_login_at')
            ->where('last_login_at', '>=', now()->subDays(7))
            ->count();

        $overview = [
            'total' => $allUsers->count(),
            'activeRecent' => $activeRecent,
            'activeRate' => $allUsers->count()
                ? round(($activeRecent / max(1, $allUsers->count())) * 100)
                : 0,
            'pending' => User::where('status', 'pending')->count(),
            'inactive' => User::where('status', 'inactive')->count(),
            'departments' => $totalDepartments,
        ];

        $departmentSummaries = $departments->map(function (Department $department) use ($departmentCounts) {
            return [
                'name' => $department->name,
                'head' => '—',
                'count' => (int) ($departmentCounts[$department->id] ?? 0),
            ];
        });

        return view('admin.users', [
            'users' => $users,
            'departments' => $departments,
            'departmentSummaries' => $departmentSummaries,
            'roleSummaries' => $roleSummaries,
            'permissionGroups' => $this->permissionGroups(),
            'roleMap' => $roleMeta,
            'overview' => $overview,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'role' => ['required', Rule::in(array_keys($this->roleMeta()))],
            'status' => ['required', Rule::in(['active', 'inactive', 'pending'])],
        ]);

        $temporaryPassword = Str::random(12);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'department_id' => $data['department_id'] ?? null,
            'role' => $data['role'],
            'status' => $data['status'],
            'password' => Hash::make($temporaryPassword),
        ]);

        $this->logUserAction('Created user', $user, [
            'before' => null,
            'after' => $user->only(['name', 'email', 'role', 'status', 'department_id']),
            'changes' => ['Account created', 'Role set to ' . $user->role],
            'risk' => 'medium',
        ]);

        return response()->json([
            'status' => 'ok',
            'message' => 'User created successfully.',
            'temporary_password' => $temporaryPassword,
            'user' => $this->transformUser($user),
        ], 201);
    }

    public function update(Request $request, User $user)
    {
        $before = $user->only(['name', 'email', 'role', 'status', 'department_id']);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'role' => ['required', Rule::in(array_keys($this->roleMeta()))],
            'status' => ['required', Rule::in(['active', 'inactive', 'pending'])],
        ]);

        $user->fill($data)->save();

        $this->logUserAction('Updated user profile', $user, [
            'before' => $before,
            'after' => $user->only(['name', 'email', 'role', 'status', 'department_id']),
            'changes' => $this->diffChanges($before, $user->only(['name', 'email', 'role', 'status', 'department_id'])),
            'risk' => 'medium',
        ]);

        return response()->json([
            'status' => 'ok',
            'message' => 'User updated successfully.',
            'user' => $this->transformUser($user),
        ]);
    }

    public function deactivate(Request $request, User $user)
    {
        return $this->updateStatus($user, 'inactive', 'User deactivated and blocked from signing in.');
    }

    public function activate(Request $request, User $user)
    {
        return $this->updateStatus($user, 'active', 'User reactivated.');
    }

    public function bulkStatus(Request $request)
    {
        $data = $request->validate([
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['exists:users,id'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        User::whereIn('id', $data['user_ids'])->update(['status' => $data['status']]);

        $updated = User::whereIn('id', $data['user_ids'])->with('department')->get();

        $this->logUserAction('Bulk user status update', null, [
            'target' => implode(',', $data['user_ids']),
            'before' => ['status' => 'mixed'],
            'after' => ['status' => $data['status']],
            'changes' => ['Bulk status set to ' . $data['status']],
            'risk' => 'medium',
        ]);

        return response()->json([
            'status' => 'ok',
            'message' => 'Statuses updated.',
            'users' => $updated->map(fn (User $user) => $this->transformUser($user)),
        ]);
    }

    public function resetPassword(Request $request, User $user)
    {
        $temporaryPassword = Str::random(12);

        $beforeStatus = $user->status;
        $user->forceFill([
            'password' => Hash::make($temporaryPassword),
            'status' => 'active',
        ])->save();

        $this->logUserAction('Reset user password', $user, [
            'before' => ['status' => $beforeStatus],
            'after' => ['status' => 'active'],
            'changes' => ['Password rotated', 'Status set to active'],
            'risk' => 'high',
        ]);

        return response()->json([
            'status' => 'ok',
            'message' => 'Temporary password generated and account set to Active.',
            'temporary_password' => $temporaryPassword,
            'user' => $this->transformUser($user),
        ]);
    }

    protected function updateStatus(User $user, string $status, string $message)
    {
        $before = $user->only(['status']);
        $user->forceFill(['status' => $status])->save();

        $this->logUserAction("User {$status}", $user, [
            'before' => $before,
            'after' => ['status' => $status],
            'changes' => ["Status {$before['status']} → {$status}"],
            'risk' => 'medium',
        ]);

        return response()->json([
            'status' => 'ok',
            'message' => $message,
            'user' => $this->transformUser($user),
        ]);
    }

    protected function transformUser(User $user): array
    {
        $user->loadMissing('department');

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'role_label' => $this->roleMeta()[$user->role]['title'] ?? ucfirst($user->role),
            'status' => $user->status,
            'status_label' => ucfirst($user->status),
            'department' => optional($user->department)->name,
            'department_id' => $user->department_id,
            'last_login_label' => $user->last_login_at
                ? $user->last_login_at->format('M j · g:i A')
                : 'Never',
            'last_login_at' => $user->last_login_at?->toIso8601String(),
        ];
    }

    private function logUserAction(string $action, ?User $user, array $context = []): void
    {
        $this->auditLogger->log([
            'action' => $action,
            'module' => 'Users',
            'target' => $context['target'] ?? $user?->email ?? 'Bulk',
            'action_type' => $context['action_type'] ?? null,
            'risk' => $context['risk'] ?? 'low',
            'status' => 'success',
            'notes' => $context['notes'] ?? null,
            'before' => $context['before'] ?? null,
            'after' => $context['after'] ?? null,
            'changes' => $context['changes'] ?? null,
        ]);
    }

    private function diffChanges(array $before, array $after): array
    {
        $changes = [];
        foreach ($after as $key => $value) {
            $prev = $before[$key] ?? null;
            if ($prev !== $value) {
                $changes[] = "{$key}: {$prev} → {$value}";
            }
        }

        return $changes;
    }

    protected function roleMeta(): array
    {
        return [
            'admin' => [
                'title' => 'Admin',
                'description' => 'Full control across modules, approvals, and audit.',
            ],
            'staff' => [
                'title' => 'Staff',
                'description' => 'Standard booking and catalog access.',
            ],
        ];
    }

    protected function permissionGroups(): array
    {
        return [
            'Approvals & Audit' => ['View queue', 'Approve / Reject', 'Audit trail'],
            'Bookings' => ['View', 'Create', 'Edit', 'Cancel'],
            'Facilities' => ['View inventory', 'Manage availability'],
            'Reports' => ['View dashboards', 'Export logs'],
            'Admin Modules' => ['Roles & Permissions', 'Departments'],
        ];
    }
}
