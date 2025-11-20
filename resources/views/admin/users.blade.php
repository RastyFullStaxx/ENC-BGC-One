@extends('layouts.app')

@section('title', 'Admin • Users & Roles')

@push('styles')
    @vite(['resources/css/admin/users.css'])
@endpush

@section('content')
@php
    $roleChoices = $roleMap ?? [
        'admin' => ['title' => 'Admin', 'description' => 'Full control across modules.'],
        'staff' => ['title' => 'Staff', 'description' => 'Booking access and catalog visibility.'],
    ];
    $permissionGroups = $permissionGroups ?? [];
    $overview = $overview ?? ['total' => 0, 'activeRecent' => 0, 'pending' => 0, 'departments' => 0];
@endphp

<section
    class="admin-users-page"
    data-update-url-template="{{ route('admin.users.update', '__USER__') }}"
    data-deactivate-url-template="{{ route('admin.users.deactivate', '__USER__') }}"
    data-activate-url-template="{{ route('admin.users.activate', '__USER__') }}"
    data-reset-url-template="{{ route('admin.users.reset', '__USER__') }}"
    data-bulk-status-url="{{ route('admin.users.bulk-status') }}"
    data-store-url="{{ route('admin.users.store') }}"
>
    <div class="admin-users-shell">
        <a href="{{ route('admin.hub') }}" class="admin-back-button admin-back-button--light">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to admin hub
        </a>
        <p class="admin-breadcrumb">Admin Hub · Users & Roles</p>
        <div class="admin-header">
            <div>
                <h1>Users & Roles</h1>
                <p>Manage accounts, roles, departments, and approvals from one workspace.</p>
            </div>
            <button class="admin-btn admin-btn-primary" data-modal-open="addUserModal">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                    <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
                Add User
            </button>
        </div>

        <div class="admin-note">
            <div>
                <p class="admin-note-eyebrow">How this tool behaves</p>
                <h3 class="mb-1">Live directory, instant writes</h3>
                <p class="mb-0">Updates save to the database right away and reflect across dashboards.</p>
            </div>
            <ul class="admin-note-list">
                <li><strong>Deactivate</strong> blocks sign-ins and hides the account from approval routing until reactivated.</li>
                <li><strong>Edit role</strong> switches access between Admin and Staff; changes apply immediately.</li>
                <li><strong>Reset</strong> issues a new temporary password and sets the status to Active.</li>
            </ul>
        </div>

        <div class="admin-stats">
            <div class="admin-stat-card">
                <strong>{{ number_format($overview['total'] ?? 0) }}</strong>
                <span>Total members</span>
            </div>
            <div class="admin-stat-card">
                <strong>{{ $overview['activeRate'] ?? 0 }}%</strong>
                <span>{{ $overview['activeRecent'] ?? 0 }} active in last 7 days</span>
            </div>
            <div class="admin-stat-card">
                <strong>{{ str_pad(($overview['pending'] ?? 0) + ($overview['inactive'] ?? 0), 2, '0', STR_PAD_LEFT) }}</strong>
                <span>Need attention · pending / inactive</span>
            </div>
            <div class="admin-stat-card">
                <strong>{{ str_pad($overview['departments'] ?? 0, 2, '0', STR_PAD_LEFT) }}</strong>
                <span>Departments</span>
            </div>
        </div>

        <div class="admin-surface">
            <div class="admin-toolbar">
                <div class="admin-search">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                        <path d="M11 19a8 8 0 100-16 8 8 0 000 16zm8-1l4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                    </svg>
                    <input type="search" id="adminUserSearch" placeholder="Search by name, email or department">
                </div>
                <div class="admin-filter-group">
                    <button class="admin-filter-chip active" data-filter-role="all">All roles</button>
                    <button class="admin-filter-chip" data-filter-role="admin">Admin</button>
                    <button class="admin-filter-chip" data-filter-role="staff">Staff</button>
                </div>
                <div class="admin-filter-group admin-filter-divider">
                    <button class="admin-filter-chip active" data-filter-status="all">All status</button>
                    <button class="admin-filter-chip" data-filter-status="active">Active</button>
                    <button class="admin-filter-chip" data-filter-status="pending">Pending</button>
                    <button class="admin-filter-chip" data-filter-status="inactive">Inactive</button>
                </div>
                <div class="admin-filter-group">
                    <button class="admin-btn admin-btn-outline" id="bulkActionBtn">
                        Bulk actions
                    </button>
                </div>
            </div>

            <div class="admin-table-wrapper">
                <table class="admin-table" id="adminUserTable">
                    <thead>
                        <tr>
                            <th scope="col">
                                <input type="checkbox" id="selectAllUsers" aria-label="Select all users">
                            </th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Department</th>
                            <th scope="col">Roles</th>
                            <th scope="col">Status</th>
                            <th scope="col">Last Login</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="adminUserTableBody">
                        @forelse ($users as $user)
                            @php
                                $roleLabel = $roleChoices[$user->role]['title'] ?? ucfirst($user->role);
                                $statusLabel = ucfirst($user->status);
                                $statusAction = $user->status === 'inactive' ? 'activate' : 'deactivate';
                            @endphp
                            <tr
                                data-user-id="{{ $user->id }}"
                                data-role="{{ $user->role }}"
                                data-status="{{ $user->status }}"
                                data-name="{{ $user->name }}"
                                data-email="{{ $user->email }}"
                                data-department="{{ optional($user->department)->name ?? '—' }}"
                                data-department-id="{{ $user->department_id }}"
                                data-status-label="{{ $statusLabel }}"
                                data-last-login="{{ $user->last_login_at?->toIso8601String() }}"
                                data-last-login-label="{{ $user->last_login_at?->format('M j · g:i A') ?? 'Never' }}"
                                data-role-label="{{ $roleLabel }}"
                            >
                                <td><input type="checkbox" class="row-select"></td>
                                <td>
                                    <div class="user-pill">
                                        <span>{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ optional($user->department)->name ?? '—' }}</td>
                                <td>
                                    <span class="role-chip">{{ $roleLabel }}</span>
                                </td>
                                <td>
                                    <span class="status-chip status-{{ $user->status }}">{{ $statusLabel }}</span>
                                </td>
                                <td>{{ $user->last_login_at?->format('M j · g:i A') ?? 'Never' }}</td>
                                <td>
                                    <div class="admin-table-actions">
                                        <button
                                            class="admin-quick-btn admin-quick-btn-warning"
                                            data-modal-open="editUserModal"
                                            data-user-id="{{ $user->id }}"
                                        >
                                            Edit
                                        </button>
                                        <button
                                            class="admin-quick-btn admin-quick-btn-warning"
                                            data-user-id="{{ $user->id }}"
                                            data-action="{{ $statusAction }}"
                                            data-status-toggle="true"
                                            data-confirm="{{ $statusAction === 'deactivate' ? 'Deactivate' : 'Activate' }} {{ $user->name }}?"
                                            data-success="{{ $user->name }} has been {{ $statusAction === 'deactivate' ? 'deactivated' : 'activated' }}."
                                        >
                                            {{ $statusAction === 'deactivate' ? 'Deactivate' : 'Activate' }}
                                        </button>
                                        <button
                                            class="admin-quick-btn admin-quick-btn-muted"
                                            data-user-id="{{ $user->id }}"
                                            data-action="reset"
                                            data-confirm="Reset password for {{ $user->name }}?"
                                            data-success="Password reset generated for {{ $user->email }}."
                                        >
                                            Reset
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">No users yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="admin-bulk-bar">
                <div><strong id="selectedCount">0</strong> users selected</div>
                <div class="d-flex gap-2">
                    <button class="admin-btn admin-btn-outline" data-bulk-action="deactivate">Deactivate</button>
                    <button class="admin-btn admin-btn-outline" data-bulk-action="activate">Activate</button>
                </div>
            </div>
        </div>

        <div class="admin-surface">
            <div class="admin-tabs">
                <button class="admin-tab active" data-tab="roles">Roles</button>
                <button class="admin-tab" data-tab="permissions">Permissions</button>
                <button class="admin-tab" data-tab="departments">Departments</button>
            </div>

            <div id="tabRoles" class="tab-panel">
                <div class="roles-grid" id="roleGrid">
                    @foreach ($roleSummaries as $summary)
                        <article
                            class="role-card"
                            data-role-id="{{ $summary['key'] }}"
                            data-role-name="{{ $summary['title'] }}"
                            data-role-description="{{ e($summary['description']) }}"
                        >
                            <header>
                                <h3>{{ $summary['title'] }}</h3>
                                <span class="role-badge {{ strtolower($summary['title']) }}">{{ $summary['count'] }} members</span>
                            </header>
                            <p class="mb-2">{{ $summary['description'] }}</p>
                            <ul class="permissions-list">
                                <li>Manage approvals</li>
                                <li>View reports</li>
                                <li>Audit logs</li>
                            </ul>
                            <div class="mt-3 d-flex justify-content-end">
                                <button class="admin-btn admin-btn-outline" data-modal-open="editRoleModal">Edit role</button>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            <div id="tabPermissions" class="tab-panel" hidden>
                <div class="row">
                    @foreach ($permissionGroups as $group => $items)
                        <div class="col-md-6 mb-3">
                            <div class="role-card">
                                <header>
                                    <h3>{{ $group }}</h3>
                                </header>
                                <ul class="permissions-list">
                                    @foreach ($items as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div id="tabDepartments" class="tab-panel" hidden>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="mb-0">Departments</h3>
                    <button class="admin-btn admin-btn-outline" data-modal-open="departmentModal">Add department</button>
                </div>
                <div class="departments-list">
                    <table>
                        <thead>
                            <tr>
                                <th>Department</th>
                                <th>Head</th>
                                <th>Members</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($departmentSummaries as $dept)
                                <tr>
                                    <td>{{ $dept['name'] }}</td>
                                    <td>{{ $dept['head'] ?? '—' }}</td>
                                    <td>{{ $dept['count'] }}</td>
                                    <td>
                                        <div class="admin-table-actions" style="opacity:1;">
                                            <button
                                                class="admin-quick-btn admin-quick-btn-warning"
                                                data-modal-open="departmentModal"
                                                data-department-name="{{ $dept['name'] }}"
                                                data-department-head="{{ $dept['head'] ?? '' }}"
                                            >
                                                Edit
                                            </button>
                                            <button
                                                class="admin-quick-btn admin-quick-btn-muted"
                                                data-confirm="Delete {{ $dept['name'] }} department?"
                                                data-success="{{ $dept['name'] }} department removed."
                                            >
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Add User Modal --}}
<div class="modal-overlay" id="addUserModal">
    <div class="modal">
        <header>
            <h3>Add User</h3>
            <button class="admin-quick-btn" data-modal-close>&times;</button>
        </header>
        <form id="addUserForm">
            <div class="row">
                <div class="col-md-6">
                    <label for="addUserName">Name</label>
                    <input type="text" id="addUserName" required placeholder="Full name">
                </div>
                <div class="col-md-6">
                    <label for="addUserEmail">Email</label>
                    <input type="email" id="addUserEmail" placeholder="name@enc.gov" required>
                </div>
                <div class="col-md-6">
                    <label for="addUserDepartment">Department</label>
                    <select id="addUserDepartment">
                        <option value="">Select department</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Status</label>
                    <div class="chip-group" id="addUserStatus">
                        <span class="chip-option active" data-status="active">Active</span>
                        <span class="chip-option" data-status="pending">Pending</span>
                        <span class="chip-option" data-status="inactive">Inactive</span>
                    </div>
                </div>
                <div class="col-12">
                    <label>Role</label>
                    <div class="chip-group" id="addUserRoles">
                        <span class="chip-option active" data-role="staff">Staff – bookings & catalog</span>
                        <span class="chip-option" data-role="admin">Admin – full access</span>
                    </div>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="admin-btn admin-btn-outline" data-modal-close>Cancel</button>
                <button type="submit" class="admin-btn admin-btn-primary" disabled id="saveAddUserBtn">Save user</button>
            </div>
        </form>
    </div>
</div>

{{-- Bulk Drawer --}}
<div class="modal-overlay" id="bulkDrawer">
    <div class="modal">
        <header>
            <h3>Bulk actions</h3>
            <button class="admin-quick-btn" data-modal-close>&times;</button>
        </header>
        <p class="text-muted small mb-3">Select rows in the table, then choose a bulk action below.</p>
        <div class="d-flex gap-2 flex-wrap">
            <button class="admin-btn admin-btn-primary" data-bulk-trigger="activate">Activate selected</button>
            <button class="admin-btn admin-btn-outline" data-bulk-trigger="deactivate">Deactivate selected</button>
        </div>
    </div>
</div>

{{-- Edit User Modal --}}
<div class="modal-overlay" id="editUserModal">
    <div class="modal">
        <header>
            <div>
                <h3>Edit User</h3>
                <p class="small mb-0 edit-user-meta" id="editUserMeta">—</p>
            </div>
            <button class="admin-quick-btn" data-modal-close>&times;</button>
        </header>
        <form id="editUserForm">
            <div>
                <label for="editUserName">Name</label>
                <input type="text" id="editUserName" required>
            </div>
            <div>
                <label for="editUserDepartment">Department</label>
                <select id="editUserDepartment">
                    @foreach ($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Roles</label>
                <div class="chip-group" id="editUserRoles">
                    <span class="chip-option" data-role="admin">Admin</span>
                    <span class="chip-option" data-role="staff">Staff</span>
                </div>
            </div>
            <div>
                <label>Status</label>
                <div class="chip-group">
                    <span class="chip-option active" data-status="active">Active</span>
                    <span class="chip-option" data-status="pending">Pending</span>
                    <span class="chip-option" data-status="inactive">Inactive</span>
                </div>
            </div>
            <div>
                <label>Security</label>
                <div class="d-flex gap-2">
                    <button type="button" class="admin-btn admin-btn-outline" data-inline-reset="true">Reset password</button>
                    <button type="button" class="admin-btn admin-btn-outline text-danger" data-suspend="true">Suspend access</button>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="admin-btn admin-btn-outline" data-modal-close>Cancel</button>
                <button type="submit" class="admin-btn admin-btn-primary">Save changes</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Role Modal --}}
<div class="modal-overlay" id="editRoleModal">
    <div class="modal">
        <header>
            <h3>Edit Role</h3>
            <button class="admin-quick-btn" data-modal-close>&times;</button>
        </header>
        <form id="editRoleForm">
            <div>
                <label for="roleName">Role name</label>
                <input type="text" id="roleName" value="Admin">
            </div>
            <div>
                <label for="roleDescription">Description</label>
                <textarea id="roleDescription" rows="2" placeholder="What this role can do"></textarea>
            </div>
            <div>
                <label>Permissions</label>
                <div class="permissions-grid">
                    @foreach ($permissionGroups as $group => $items)
                        <div class="perm-column">
                            <p class="fw-semibold small mb-1">{{ $group }}</p>
                            @foreach ($items as $item)
                                <label class="perm-item d-flex align-items-center gap-2 small mb-1">
                                    <input type="checkbox" checked>
                                    <span>{{ $item }}</span>
                                </label>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="admin-btn admin-btn-outline text-danger" id="deleteRoleBtn">Delete role</button>
                <div class="d-flex gap-2 ms-auto">
                    <button type="button" class="admin-btn admin-btn-outline" data-modal-close>Cancel</button>
                    <button type="submit" class="admin-btn admin-btn-primary">Update role</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Department Modal --}}
<div class="modal-overlay" id="departmentModal">
    <div class="modal">
        <header>
            <h3>Department Details</h3>
            <button class="admin-quick-btn" data-modal-close>&times;</button>
        </header>
        <form id="departmentForm">
            <div>
                <label for="departmentName">Name</label>
                <input type="text" id="departmentName" required>
            </div>
            <div>
                <label for="departmentHead">Department head</label>
                <input type="text" id="departmentHead" placeholder="Search user">
            </div>
            <div class="modal-actions">
                <button type="button" class="admin-btn admin-btn-outline" data-modal-close>Cancel</button>
                <button type="submit" class="admin-btn admin-btn-primary">Save department</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const page = document.querySelector('.admin-users-page');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const urlTemplates = {
            store: page?.dataset.storeUrl,
            update: page?.dataset.updateUrlTemplate,
            deactivate: page?.dataset.deactivateUrlTemplate,
            activate: page?.dataset.activateUrlTemplate,
            reset: page?.dataset.resetUrlTemplate,
            bulkStatus: page?.dataset.bulkStatusUrl,
        };

        const rowsContainer = document.querySelector('#adminUserTableBody');
        const roleGrid = document.querySelector('#roleGrid');
        const filterRoleButtons = document.querySelectorAll('[data-filter-role]');
        const filterStatusButtons = document.querySelectorAll('[data-filter-status]');
        const searchInput = document.querySelector('#adminUserSearch');
        const selectAll = document.querySelector('#selectAllUsers');
        const selectedCount = document.querySelector('#selectedCount');
        const bulkActionBtn = document.querySelector('#bulkActionBtn');
        const bulkActionButtons = document.querySelectorAll('[data-bulk-action]');
        const modals = document.querySelectorAll('.modal-overlay');

        const addUserForm = document.querySelector('#addUserForm');
        const addRoleChips = document.querySelectorAll('#addUserRoles .chip-option');
        const addStatusChips = document.querySelectorAll('#addUserStatus .chip-option');
        const saveAddUserBtn = document.querySelector('#saveAddUserBtn');

        const editUserForm = document.querySelector('#editUserForm');
        const editUserName = document.querySelector('#editUserName');
        const editUserDepartment = document.querySelector('#editUserDepartment');
        const editUserMeta = document.querySelector('#editUserMeta');
        const editRoleChips = document.querySelectorAll('#editUserRoles .chip-option');
        const statusChips = document.querySelectorAll('#editUserForm [data-status]');
        const inlineResetBtn = document.querySelector('[data-inline-reset]');
        const suspendBtn = document.querySelector('[data-suspend]');

        const editRoleForm = document.querySelector('#editRoleForm');
        const roleNameInput = document.querySelector('#roleName');
        const roleDescriptionInput = document.querySelector('#roleDescription');
        const deleteRoleBtn = document.querySelector('#deleteRoleBtn');

        const departmentForm = document.querySelector('#departmentForm');
        const departmentNameInput = document.querySelector('#departmentName');
        const departmentHeadInput = document.querySelector('#departmentHead');

        const tabs = document.querySelectorAll('.admin-tab');
        const panels = {
            roles: document.querySelector('#tabRoles'),
            permissions: document.querySelector('#tabPermissions'),
            departments: document.querySelector('#tabDepartments'),
        };

        let activeRole = 'all';
        let activeStatus = 'all';
        let currentEditRole = null;
        let currentEditStatus = 'active';
        let currentEditUserId = null;
        let addSelectedRole = 'staff';
        let addSelectedStatus = 'active';
        let activeRoleCard = null;

        const swalBase = {
            background: 'rgba(0, 11, 28, 0.96)',
            color: '#FDFDFD',
            confirmButtonColor: '#155DFC',
            cancelButtonColor: '#9F0712',
            buttonsStyling: true,
        };

        const notify = ({ title = 'Success', text = '', icon = 'success', confirmText = 'Great' }) =>
            Swal.fire({
                ...swalBase,
                title,
                text,
                icon,
                confirmButtonText: confirmText,
                showCancelButton: false,
            });

        const confirmAction = ({ title, text, confirmText = 'Yes, continue' }) =>
            Swal.fire({
                ...swalBase,
                title,
                text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: confirmText,
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#00C950',
            });

        const buildUrl = (template, id) => template?.replace('__USER__', id);
        const capitalize = value => (value ? value.charAt(0).toUpperCase() + value.slice(1) : '');

        const apiRequest = async (url, method = 'POST', payload = null) => {
            const response = await fetch(url, {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    Accept: 'application/json',
                },
                body: payload ? JSON.stringify(payload) : null,
            });

            const data = await response.json().catch(() => ({}));

            if (!response.ok) {
                const firstError = data?.errors ? Object.values(data.errors)?.[0]?.[0] : null;
                throw new Error(firstError || data.message || 'Something went wrong');
            }

            return data;
        };

        const getRows = () => Array.from(rowsContainer.querySelectorAll('tr[data-user-id]'));
        const getRowCheckboxes = () => Array.from(rowsContainer.querySelectorAll('.row-select'));
        const getRowById = id => rowsContainer.querySelector(`[data-user-id="${id}"]`);

        const statusActionValue = status => (status === 'inactive' ? 'activate' : 'deactivate');
        const statusActionLabel = status => (status === 'inactive' ? 'Activate' : 'Deactivate');
        const formatStatus = status => capitalize(status || 'inactive');

        const renderUserRow = user => {
            const statusAction = statusActionValue(user.status);
            const statusLabel = user.status_label || formatStatus(user.status);
            const roleText = user.role_label || capitalize(user.role);
            const departmentLabel = user.department || '—';
            const lastLoginLabel = user.last_login_label || 'Never';

            return `
                <tr
                    data-user-id="${user.id}"
                    data-role="${user.role}"
                    data-status="${user.status}"
                    data-name="${user.name}"
                    data-email="${user.email}"
                    data-department="${departmentLabel}"
                    data-department-id="${user.department_id || ''}"
                    data-status-label="${statusLabel}"
                    data-last-login="${user.last_login_at || ''}"
                    data-last-login-label="${lastLoginLabel}"
                    data-role-label="${roleText}"
                >
                    <td><input type="checkbox" class="row-select"></td>
                    <td>
                        <div class="user-pill">
                            <span>${user.name}</span>
                        </div>
                    </td>
                    <td>${user.email}</td>
                    <td>${departmentLabel}</td>
                    <td><span class="role-chip">${roleText}</span></td>
                    <td><span class="status-chip status-${user.status}">${statusLabel}</span></td>
                    <td>${lastLoginLabel}</td>
                    <td>
                        <div class="admin-table-actions">
                            <button class="admin-quick-btn admin-quick-btn-warning" data-modal-open="editUserModal" data-user-id="${user.id}">Edit</button>
                            <button class="admin-quick-btn admin-quick-btn-warning" data-user-id="${user.id}" data-action="${statusAction}" data-status-toggle="true">
                                ${statusActionLabel(user.status)}
                            </button>
                            <button class="admin-quick-btn admin-quick-btn-muted" data-user-id="${user.id}" data-action="reset">Reset</button>
                        </div>
                    </td>
                </tr>
            `;
        };

        const updateRowDom = user => {
            const row = getRowById(user.id);
            if (!row) return;

            const statusLabel = user.status_label || formatStatus(user.status);
            const roleLabel = user.role_label || capitalize(user.role);
            const lastLoginLabel = user.last_login_label || 'Never';
            const departmentLabel = user.department || '—';

            row.dataset.role = user.role;
            row.dataset.status = user.status;
            row.dataset.statusLabel = statusLabel;
            row.dataset.name = user.name;
            row.dataset.email = user.email;
            row.dataset.department = departmentLabel;
            row.dataset.departmentId = user.department_id || '';
            row.dataset.lastLogin = user.last_login_at || '';
            row.dataset.lastLoginLabel = lastLoginLabel;
            row.dataset.roleLabel = roleLabel;

            const cells = row.querySelectorAll('td');
            const nameSpan = cells[1]?.querySelector('.user-pill span');
            if (nameSpan) nameSpan.textContent = user.name;
            if (cells[2]) cells[2].textContent = user.email;
            if (cells[3]) cells[3].textContent = departmentLabel;
            if (cells[6]) cells[6].textContent = lastLoginLabel;

            const roleChip = row.querySelector('.role-chip');
            if (roleChip) roleChip.textContent = roleLabel;

            const statusChip = row.querySelector('.status-chip');
            if (statusChip) {
                statusChip.textContent = statusLabel;
                statusChip.className = `status-chip status-${user.status}`;
            }

            const toggleBtn = row.querySelector('[data-status-toggle]');
            if (toggleBtn) {
                const nextAction = statusActionValue(user.status);
                toggleBtn.dataset.action = nextAction;
                toggleBtn.textContent = statusActionLabel(user.status);
            }
        };

        const appendUserRow = user => {
            rowsContainer.insertAdjacentHTML('afterbegin', renderUserRow(user));
            applyFilters();
        };

        const applyFilters = () => {
            const keyword = searchInput.value.trim().toLowerCase();
            getRows().forEach(row => {
                const matchRole = activeRole === 'all' || row.dataset.role === activeRole;
                const matchStatus = activeStatus === 'all' || row.dataset.status === activeStatus;
                const matchKeyword = !keyword || row.innerText.toLowerCase().includes(keyword);
                row.style.display = matchRole && matchStatus && matchKeyword ? '' : 'none';
            });
        };

        const updateSelectedCount = () => {
            const count = getRowCheckboxes().filter(cb => cb.checked).length;
            selectedCount.textContent = count;
            return count;
        };

        filterRoleButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                filterRoleButtons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                activeRole = btn.dataset.filterRole;
                applyFilters();
            });
        });

        filterStatusButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                filterStatusButtons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                activeStatus = btn.dataset.filterStatus;
                applyFilters();
            });
        });

        searchInput.addEventListener('input', applyFilters);

        selectAll.addEventListener('change', () => {
            getRowCheckboxes().forEach(cb => (cb.checked = selectAll.checked));
            updateSelectedCount();
        });

        rowsContainer.addEventListener('change', e => {
            if (!e.target.classList.contains('row-select')) return;
            updateSelectedCount();
            if (!e.target.checked) selectAll.checked = false;
        });

        bulkActionBtn?.addEventListener('click', () => {
            notify({
                title: 'Bulk actions',
                text: 'Select one or more members using the checkboxes, then choose Activate or Deactivate.',
                icon: 'info',
                confirmText: 'Got it',
            });
        });

        const runBulkStatus = async status => {
            const ids = getRowCheckboxes()
                .filter(cb => cb.checked)
                .map(cb => cb.closest('tr')?.dataset.userId)
                .filter(Boolean);

            if (!ids.length) {
                notify({ title: 'No users selected', text: 'Pick at least one member first.', icon: 'info' });
                return;
            }

            const confirmation = await confirmAction({
                title: `${status === 'active' ? 'Activate' : 'Deactivate'} ${ids.length} user(s)?`,
                text: `This will mark the selected accounts as ${status}.`,
                confirmText: `Yes, ${status === 'active' ? 'activate' : 'deactivate'}`,
            });

            if (!confirmation.isConfirmed) return;

            try {
                const response = await apiRequest(urlTemplates.bulkStatus, 'POST', {
                    user_ids: ids,
                    status,
                });
                (response.users || []).forEach(updateRowDom);
                notify({ title: 'Action completed', text: `Updated ${ids.length} user(s).` });
                updateSelectedCount();
            } catch (error) {
                notify({ title: 'Unable to update', text: error.message, icon: 'error' });
            }
        };

        bulkActionButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const action = btn.dataset.bulkAction;
                runBulkStatus(action === 'activate' ? 'active' : 'inactive');
            });
        });

        document.addEventListener('click', e => {
            const bulkBtn = e.target.closest('[data-bulk-trigger]');
            if (bulkBtn) {
                runBulkStatus(bulkBtn.dataset.bulkTrigger === 'activate' ? 'active' : 'inactive');
            }
        });

        const openModal = id => {
            const target = document.getElementById(id);
            if (target) {
                target.classList.add('active');
            } else {
                notify({ title: 'Not available', text: 'Modal content not found for this action.', icon: 'info' });
            }
        };
        const closeModal = modal => modal?.classList.remove('active');

        modals.forEach(overlay => {
            overlay.addEventListener('click', e => {
                if (e.target === overlay) closeModal(overlay);
            });
        });

        document.querySelectorAll('[data-modal-close]').forEach(btn => {
            btn.addEventListener('click', e => closeModal(e.target.closest('.modal-overlay')));
        });

        const syncRoleChips = () => {
            editRoleChips.forEach(chip => chip.classList.toggle('active', chip.dataset.role === currentEditRole));
        };

        const syncStatusChips = () => {
            statusChips.forEach(chip => chip.classList.toggle('active', chip.dataset.status === currentEditStatus));
        };

        const syncAddRoleChips = () => {
            addRoleChips.forEach(chip => chip.classList.toggle('active', chip.dataset.role === addSelectedRole));
        };

        const syncAddStatusChips = () => {
            addStatusChips.forEach(chip => chip.classList.toggle('active', chip.dataset.status === addSelectedStatus));
        };

        addRoleChips.forEach(chip => {
            chip.addEventListener('click', () => {
                addSelectedRole = chip.dataset.role;
                syncAddRoleChips();
                validateAddUser();
            });
        });

        addStatusChips.forEach(chip => {
            chip.addEventListener('click', () => {
                addSelectedStatus = chip.dataset.status;
                syncAddStatusChips();
                validateAddUser();
            });
        });

        editRoleChips.forEach(chip => {
            chip.addEventListener('click', () => {
                currentEditRole = chip.dataset.role;
                syncRoleChips();
            });
        });

        statusChips.forEach(chip => {
            chip.addEventListener('click', () => {
                currentEditStatus = chip.dataset.status;
                syncStatusChips();
            });
        });

        const validateAddUser = () => {
            if (!saveAddUserBtn) return;
            const formValid = addUserForm?.checkValidity();
            const ready = formValid && addSelectedRole && addSelectedStatus;
            saveAddUserBtn.disabled = !ready;
        };

        addUserForm?.addEventListener('input', validateAddUser);

        const roleModalDefaults = () => {
            activeRoleCard = null;
            roleNameInput.value = 'Admin';
            roleDescriptionInput.value = '';
            deleteRoleBtn?.classList.add('d-none');
        };

        document.addEventListener('click', e => {
            const trigger = e.target.closest('[data-modal-open]');
            if (!trigger) return;

            const target = trigger.dataset.modalOpen;
            const modalEl = document.getElementById(target);

            if (target === 'editUserModal') {
                const row = trigger.closest('tr');
                if (row) {
                    currentEditUserId = row.dataset.userId;
                    editUserName.value = row.dataset.name;
                    editUserDepartment.value = row.dataset.departmentId || '';
                    editUserMeta.textContent = `${row.dataset.email} • ${row.dataset.department}`;
                    currentEditStatus = row.dataset.status;
                    currentEditRole = row.dataset.role || 'staff';
                    syncRoleChips();
                    syncStatusChips();
                    if (inlineResetBtn) inlineResetBtn.dataset.userId = currentEditUserId;
                    if (suspendBtn) suspendBtn.dataset.userId = currentEditUserId;
                }
            }

            if (target === 'addUserModal') {
                addUserForm?.reset();
                addSelectedRole = 'staff';
                addSelectedStatus = 'active';
                syncAddRoleChips();
                syncAddStatusChips();
                validateAddUser();
            }

            if (target === 'departmentModal') {
                const deptName = trigger.dataset.departmentName || '';
                const deptHead = trigger.dataset.departmentHead || '';
                departmentNameInput.value = deptName;
                departmentHeadInput.value = deptHead;
            }

            if (target === 'editRoleModal') {
                const card = trigger.closest('[data-role-id]');
                if (card) {
                    activeRoleCard = card;
                    roleNameInput.value = card.dataset.roleName || card.querySelector('h3')?.textContent?.trim() || 'Admin';
                    roleDescriptionInput.value = card.dataset.roleDescription || card.querySelector('p')?.textContent?.trim() || '';
                    deleteRoleBtn?.classList.remove('d-none');
                } else {
                    roleModalDefaults();
                }
            }

            if (!modalEl) {
                notify({ title: 'Not available', text: 'This action is not wired yet.', icon: 'info' });
                return;
            }

            openModal(target);
        });

        editUserForm?.addEventListener('submit', async e => {
            e.preventDefault();
            if (!currentEditUserId || !currentEditRole) {
                notify({ title: 'Missing details', text: 'Pick a role before saving.', icon: 'info' });
                return;
            }

            try {
                const response = await apiRequest(buildUrl(urlTemplates.update, currentEditUserId), 'PUT', {
                    name: editUserName.value,
                    department_id: editUserDepartment.value || null,
                    role: currentEditRole,
                    status: currentEditStatus,
                });

                updateRowDom(response.user);
                closeModal(document.querySelector('#editUserModal'));
                notify({ title: 'User updated', text: `${response.user.name} saved successfully.` });
            } catch (error) {
                notify({ title: 'Unable to save', text: error.message, icon: 'error' });
            }
        });

        addUserForm?.addEventListener('submit', async e => {
            e.preventDefault();
            try {
                const response = await apiRequest(urlTemplates.store, 'POST', {
                    name: document.querySelector('#addUserName').value,
                    email: document.querySelector('#addUserEmail').value,
                    department_id: document.querySelector('#addUserDepartment').value || null,
                    role: addSelectedRole,
                    status: addSelectedStatus,
                });

                appendUserRow(response.user);
                closeModal(document.querySelector('#addUserModal'));
                notify({
                    title: 'User added',
                    text: `Temp password: ${response.temporary_password}`,
                    confirmText: 'Copy',
                }).then(() => navigator.clipboard.writeText(response.temporary_password));
            } catch (error) {
                notify({ title: 'Unable to add user', text: error.message, icon: 'error' });
            }
        });

        if (inlineResetBtn) {
            inlineResetBtn.addEventListener('click', async () => {
                if (!currentEditUserId) return;
                try {
                    const response = await apiRequest(buildUrl(urlTemplates.reset, currentEditUserId), 'POST');
                    updateRowDom(response.user);
                    notify({
                        title: 'Password reset',
                        text: `New temporary password: ${response.temporary_password}`,
                    });
                } catch (error) {
                    notify({ title: 'Reset failed', text: error.message, icon: 'error' });
                }
            });
        }

        if (suspendBtn) {
            suspendBtn.addEventListener('click', async () => {
                if (!currentEditUserId) return;
                const confirmation = await confirmAction({
                    title: 'Suspend access?',
                    text: 'This will deactivate the account until you reactivate it.',
                });
                if (!confirmation.isConfirmed) return;

                try {
                    const response = await apiRequest(buildUrl(urlTemplates.deactivate, currentEditUserId), 'POST');
                    updateRowDom(response.user);
                    notify({ title: 'Suspended', text: response.message || 'User deactivated.' });
                } catch (error) {
                    notify({ title: 'Action failed', text: error.message, icon: 'error' });
                }
            });
        }

        document.addEventListener('click', async e => {
            const btn = e.target.closest('[data-action]');
            if (!btn) return;

            const action = btn.dataset.action;
            const userId = btn.dataset.userId;
            if (!userId) return;
            const row = getRowById(userId);
            const userName = row?.dataset.name || 'this user';

            if (action === 'reset') {
                const confirmation = await confirmAction({
                    title: 'Reset password?',
                    text: btn.dataset.confirm || `Generate a temporary password for ${userName}?`,
                });
                if (!confirmation.isConfirmed) return;
                try {
                    const response = await apiRequest(buildUrl(urlTemplates.reset, userId), 'POST');
                    updateRowDom(response.user);
                    notify({
                        title: 'Password reset',
                        text: `New temporary password: ${response.temporary_password}`,
                        confirmText: 'Copy',
                    }).then(() => navigator.clipboard.writeText(response.temporary_password));
                } catch (error) {
                    notify({ title: 'Reset failed', text: error.message, icon: 'error' });
                }
                return;
            }

            if (action === 'deactivate' || action === 'activate') {
                const endpoint = action === 'deactivate' ? urlTemplates.deactivate : urlTemplates.activate;
                const confirmation = await confirmAction({
                    title: `${capitalize(action)} user?`,
                    text: btn.dataset.confirm || `Apply this change to ${userName}?`,
                });
                if (!confirmation.isConfirmed) return;

                try {
                    const response = await apiRequest(buildUrl(endpoint, userId), 'POST');
                    updateRowDom(response.user);
                    notify({ title: 'Done', text: response.message || `${userName} updated.` });
                } catch (error) {
                    notify({ title: 'Action failed', text: error.message, icon: 'error' });
                }
            }
        });

        editRoleForm?.addEventListener('submit', e => {
            e.preventDefault();
            const name = roleNameInput.value.trim() || 'Role';
            const desc = roleDescriptionInput.value.trim() || 'Updated permissions and scope.';

            if (activeRoleCard) {
                activeRoleCard.dataset.roleName = name;
                activeRoleCard.dataset.roleDescription = desc;
                const titleEl = activeRoleCard.querySelector('h3');
                if (titleEl) titleEl.textContent = name;
                const descEl = activeRoleCard.querySelector('p');
                if (descEl) descEl.textContent = desc;
            } else if (roleGrid) {
                const newCard = document.createElement('article');
                newCard.className = 'role-card';
                newCard.dataset.roleId = name.toLowerCase().replace(/\s+/g, '-');
                newCard.dataset.roleName = name;
                newCard.dataset.roleDescription = desc;
                newCard.innerHTML = `
                    <header>
                        <h3>${name}</h3>
                        <span class="role-badge">${'0'} members</span>
                    </header>
                    <p class="mb-2">${desc}</p>
                    <ul class="permissions-list">
                        <li>View</li>
                        <li>Approve</li>
                        <li>Edit</li>
                    </ul>
                    <div class="mt-3 d-flex justify-content-end">
                        <button class="admin-btn admin-btn-outline" data-modal-open="editRoleModal">Edit role</button>
                    </div>
                `;
                roleGrid.prepend(newCard);
            }

            closeModal(document.querySelector('#editRoleModal'));
            notify({ title: 'Role saved', text: `${name} updated.` });
        });

        deleteRoleBtn?.addEventListener('click', async e => {
            e.preventDefault();
            if (!activeRoleCard) {
                closeModal(document.querySelector('#editRoleModal'));
                return;
            }

            const confirmation = await confirmAction({
                title: 'Delete role?',
                text: 'This removes the role card from the view.',
            });
            if (!confirmation.isConfirmed) return;

            activeRoleCard.remove();
            closeModal(document.querySelector('#editRoleModal'));
            notify({ title: 'Role deleted', text: 'Role removed from the view.' });
        });

        departmentForm?.addEventListener('submit', e => {
            e.preventDefault();
            closeModal(document.querySelector('#departmentModal'));
            notify({ title: 'Department saved', text: `${departmentNameInput.value} captured.` });
        });

        document.querySelectorAll('[data-confirm]:not([data-action])').forEach(btn => {
            btn.addEventListener('click', async () => {
                const confirmation = await confirmAction({
                    title: 'Please confirm',
                    text: btn.dataset.confirm || 'Proceed with this action?',
                });

                if (confirmation.isConfirmed) {
                    notify({ title: 'Done', text: btn.dataset.success || 'Action completed.' });
                }
            });
        });

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                Object.values(panels).forEach(panel => (panel.hidden = true));
                panels[tab.dataset.tab].hidden = false;
            });
        });

        syncAddRoleChips();
        syncAddStatusChips();
        applyFilters();
    });
</script>
@endpush
@endsection
