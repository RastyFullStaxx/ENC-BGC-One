@extends('layouts.app')

@section('title', 'Admin • Users & Roles')

@push('styles')
    @vite(['resources/css/admin/users.css'])
@endpush

@section('content')
@php
    $users = [
        [
            'name' => 'Ava Santos',
            'email' => 'ava.santos@enc.gov',
            'department' => 'Operations Excellence',
            'roles' => ['Admin', 'Approver'],
            'role_key' => 'admin',
            'status' => 'Active',
            'status_key' => 'active',
            'last_login' => 'Today · 09:32 AM',
        ],
        [
            'name' => 'Brian Lopez',
            'email' => 'brian.lopez@enc.gov',
            'department' => 'Facilities',
            'roles' => ['Approver'],
            'role_key' => 'approver',
            'status' => 'Active',
            'status_key' => 'active',
            'last_login' => 'Today · 07:50 AM',
        ],
        [
            'name' => 'Cheska Lim',
            'email' => 'cheska.lim@enc.gov',
            'department' => 'Internal Audit',
            'roles' => ['Auditor'],
            'role_key' => 'auditor',
            'status' => 'Pending',
            'status_key' => 'pending',
            'last_login' => 'Awaiting activation',
        ],
        [
            'name' => 'Diego Ramos',
            'email' => 'diego.ramos@enc.gov',
            'department' => 'Mobility',
            'roles' => ['User'],
            'role_key' => 'user',
            'status' => 'Active',
            'status_key' => 'active',
            'last_login' => 'Yesterday · 08:14 PM',
        ],
        [
            'name' => 'Erica Bautista',
            'email' => 'erica.bautista@enc.gov',
            'department' => 'Facilities',
            'roles' => ['User'],
            'role_key' => 'user',
            'status' => 'Inactive',
            'status_key' => 'inactive',
            'last_login' => 'Aug 11 · 03:40 PM',
        ],
    ];

    $roleSummaries = [
        ['title' => 'Admin', 'count' => 4, 'description' => 'Full system control, access governance.'],
        ['title' => 'Approver', 'count' => 12, 'description' => 'Can approve or reject bookings.'],
        ['title' => 'User', 'count' => 31, 'description' => 'Create bookings & view schedules.'],
        ['title' => 'Guest', 'count' => 7, 'description' => 'Limited catalog visibility.'],
    ];

    $permissionGroups = [
        'Bookings' => ['View', 'Create', 'Edit', 'Cancel'],
        'Facilities' => ['View inventory', 'Manage availability'],
        'Approvals' => ['View queue', 'Approve / Reject'],
        'Reports' => ['View dashboards', 'Export logs'],
        'Admin Modules' => ['Roles & Permissions', 'Audit trail'],
    ];

    $departments = [
        ['name' => 'Creative Office', 'head' => 'J. Mercado', 'count' => 14],
        ['name' => 'Admin Office', 'head' => 'John Doe', 'count' => 8],
        ['name' => 'Mobility', 'head' => 'Diego Ramos', 'count' => 11],
        ['name' => 'Facilities', 'head' => 'Marie G.', 'count' => 19],
    ];
@endphp

<section class="admin-users-page">
    <div class="admin-users-shell">
        <p class="admin-breadcrumb">Admin Hub · Users & Roles</p>
        <div class="admin-header">
            <div>
                <h1>Users & Roles</h1>
                <p>Manage accounts, roles, departments, and approvals from one workspace.</p>
            </div>
            <button class="admin-btn admin-btn-primary" data-modal-open="inviteModal">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                    <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
                Invite User
            </button>
        </div>

        <div class="admin-stats">
            <div class="admin-stat-card">
                <strong>52</strong>
                <span>Total members</span>
            </div>
            <div class="admin-stat-card">
                <strong>87%</strong>
                <span>Active in last 7 days</span>
            </div>
            <div class="admin-stat-card">
                <strong>05</strong>
                <span>Pending invitations</span>
            </div>
            <div class="admin-stat-card">
                <strong>12</strong>
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
                    <button class="admin-filter-chip" data-filter-role="approver">Approver</button>
                    <button class="admin-filter-chip" data-filter-role="user">User</button>
                    <button class="admin-filter-chip" data-filter-role="guest">Guest</button>
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
                        @foreach ($users as $user)
                            <tr
                                data-role="{{ $user['role_key'] }}"
                                data-status="{{ $user['status_key'] }}"
                                data-name="{{ $user['name'] }}"
                                data-email="{{ $user['email'] }}"
                                data-department="{{ $user['department'] }}"
                                data-status-label="{{ $user['status'] }}"
                                data-roles='@json($user['roles'])'
                                data-last-login="{{ $user['last_login'] }}"
                            >
                                <td><input type="checkbox" class="row-select"></td>
                                <td>
                                    <div class="user-pill">
                                        <span>{{ $user['name'] }}</span>
                                    </div>
                                </td>
                                <td>{{ $user['email'] }}</td>
                                <td>{{ $user['department'] }}</td>
                                <td>
                                    @foreach ($user['roles'] as $role)
                                        <span class="role-chip">{{ $role }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <span class="status-chip status-{{ $user['status_key'] }}">{{ $user['status'] }}</span>
                                </td>
                                <td>{{ $user['last_login'] }}</td>
                                <td>
                                        <div class="admin-table-actions">
                                            <button class="admin-quick-btn admin-quick-btn-warning" data-modal-open="editUserModal">Edit</button>
                                            <button
                                                class="admin-quick-btn admin-quick-btn-warning"
                                                data-confirm="Deactivate {{ $user['name'] }}?"
                                                data-success="{{ $user['name'] }} has been deactivated."
                                            >
                                                Deactivate
                                            </button>
                                            <button
                                                class="admin-quick-btn admin-quick-btn-muted"
                                                data-confirm="Reset password for {{ $user['name'] }}?"
                                                data-success="Password reset instructions sent to {{ $user['email'] }}."
                                            >
                                                Reset
                                            </button>
                                        </div>
                                </td>
                            </tr>
                        @endforeach
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
                <div class="roles-grid">
                    @foreach ($roleSummaries as $summary)
                        <article class="role-card">
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
                            @foreach ($departments as $dept)
                                <tr>
                                    <td>{{ $dept['name'] }}</td>
                                    <td>{{ $dept['head'] }}</td>
                                    <td>{{ $dept['count'] }}</td>
                                    <td>
                                        <div class="admin-table-actions" style="opacity:1;">
                                            <button
                                                class="admin-quick-btn admin-quick-btn-warning"
                                                data-modal-open="departmentModal"
                                                data-department-name="{{ $dept['name'] }}"
                                                data-department-head="{{ $dept['head'] }}"
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

{{-- Invite User Modal --}}
<div class="modal-overlay" id="inviteModal">
    <div class="modal">
        <header>
            <h3>Invite User</h3>
            <button class="admin-quick-btn" data-modal-close>&times;</button>
        </header>
        <form id="inviteForm">
            <div>
                <label for="inviteName">Name</label>
                <input type="text" id="inviteName" required>
            </div>
            <div>
                <label for="inviteEmail">Email</label>
                <input type="email" id="inviteEmail" placeholder="name@enc.gov" required>
            </div>
            <div>
                <label for="inviteDepartment">Department</label>
                <select id="inviteDepartment" required>
                    <option value="">Select department</option>
                    @foreach ($departments as $dept)
                        <option value="{{ $dept['name'] }}">{{ $dept['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Role</label>
                <div class="chip-group" id="inviteRoles">
                    <span class="chip-option" data-role="Admin">Admin – full access</span>
                    <span class="chip-option" data-role="Approver">Approver – approvals only</span>
                    <span class="chip-option" data-role="User">User – bookings</span>
                    <span class="chip-option" data-role="Guest">Guest – view catalog</span>
                </div>
            </div>
            <div>
                <label for="inviteTempPass">Temporary password</label>
                <div class="d-flex gap-2">
                    <input type="text" id="inviteTempPass" value="ENC-{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(6)) }}" readonly>
                    <button type="button" class="admin-btn admin-btn-outline" id="copyTempPass">Copy</button>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="admin-btn admin-btn-outline" data-modal-close>Cancel</button>
                <button type="submit" class="admin-btn admin-btn-primary" disabled id="sendInviteBtn">Send invitation</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit User Modal --}}
<div class="modal-overlay" id="editUserModal">
    <div class="modal">
        <header>
            <div>
                <h3>Edit User</h3>
                <p class="text-muted small mb-0" id="editUserMeta">—</p>
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
                        <option value="{{ $dept['name'] }}">{{ $dept['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Roles</label>
                <div class="chip-group" id="editUserRoles">
                    <span class="chip-option" data-role="Admin">Admin</span>
                    <span class="chip-option" data-role="Approver">Approver</span>
                    <span class="chip-option" data-role="User">User</span>
                    <span class="chip-option" data-role="Guest">Guest</span>
                </div>
            </div>
            <div>
                <label>Status</label>
                <div class="chip-group">
                    <span class="chip-option active" data-status="active">Active</span>
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
                <input type="text" id="roleName" value="Approver">
            </div>
            <div>
                <label>Permissions</label>
                <div class="row">
                    @foreach ($permissionGroups as $group => $items)
                        <div class="col-md-6">
                            <p class="fw-semibold small mb-1">{{ $group }}</p>
                            @foreach ($items as $item)
                                <label class="d-flex align-items-center gap-2 small mb-1">
                                    <input type="checkbox" checked>
                                    <span>{{ $item }}</span>
                                </label>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="admin-btn admin-btn-outline" data-modal-close>Cancel</button>
                <button type="submit" class="admin-btn admin-btn-primary">Update role</button>
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
        const filterRoleButtons = document.querySelectorAll('[data-filter-role]');
        const filterStatusButtons = document.querySelectorAll('[data-filter-status]');
        const rows = document.querySelectorAll('#adminUserTableBody tr');
        const searchInput = document.querySelector('#adminUserSearch');
        const selectAll = document.querySelector('#selectAllUsers');
        const rowCheckboxes = document.querySelectorAll('.row-select');
        const selectedCount = document.querySelector('#selectedCount');
        const bulkActionBtn = document.querySelector('#bulkActionBtn');
        const bulkActionButtons = document.querySelectorAll('[data-bulk-action]');
        const inviteForm = document.querySelector('#inviteForm');
        const sendInviteBtn = document.querySelector('#sendInviteBtn');
        const inviteRoleContainer = document.querySelector('#inviteRoles');
        const copyTempPassBtn = document.querySelector('#copyTempPass');
        const modals = document.querySelectorAll('.modal-overlay');
        const editUserForm = document.querySelector('#editUserForm');
        const editUserName = document.querySelector('#editUserName');
        const editUserDepartment = document.querySelector('#editUserDepartment');
        const editUserMeta = document.querySelector('#editUserMeta');
        const editRolesContainer = document.querySelector('#editUserRoles');
        const editRoleChips = editRolesContainer.querySelectorAll('.chip-option');
        const statusChips = document.querySelectorAll('#editUserForm [data-status]');
        const inlineResetBtn = document.querySelector('[data-inline-reset]');
        const suspendBtn = document.querySelector('[data-suspend]');
        const editRoleForm = document.querySelector('#editRoleForm');
        const departmentForm = document.querySelector('#departmentForm');
        const departmentNameInput = document.querySelector('#departmentName');
        const departmentHeadInput = document.querySelector('#departmentHead');

        let activeRole = 'all';
        let activeStatus = 'all';
        let inviteRolesSelected = new Set();
        let editRolesSelected = new Set();
        let currentEditStatus = 'active';

        const swalBase = {
            background: 'rgba(0, 11, 28, 0.96)',
            color: '#FDFDFD',
            confirmButtonColor: '#155DFC',
            cancelButtonColor: '#9F0712',
            buttonsStyling: true,
        };

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

        const notify = ({ title = 'Success', text = '', icon = 'success', confirmText = 'Great' }) =>
            Swal.fire({
                ...swalBase,
                title,
                text,
                icon,
                confirmButtonText: confirmText,
                showCancelButton: false,
            });

        const openModal = id => document.getElementById(id).classList.add('active');
        const closeModal = modal => modal.classList.remove('active');

        modals.forEach(overlay => {
            overlay.addEventListener('click', e => {
                if (e.target === overlay) closeModal(overlay);
            });
        });

        document.querySelectorAll('[data-modal-close]').forEach(btn => {
            btn.addEventListener('click', e => closeModal(e.target.closest('.modal-overlay')));
        });

        const applyFilters = () => {
            const keyword = searchInput.value.trim().toLowerCase();
            rows.forEach(row => {
                const matchRole = activeRole === 'all' || row.dataset.role === activeRole;
                const matchStatus = activeStatus === 'all' || row.dataset.status === activeStatus;
                const matchKeyword = !keyword || row.innerText.toLowerCase().includes(keyword);
                row.style.display = matchRole && matchStatus && matchKeyword ? '' : 'none';
            });
        };

        const updateSelectedCount = () => {
            const count = [...rowCheckboxes].filter(cb => cb.checked).length;
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
            rowCheckboxes.forEach(cb => (cb.checked = selectAll.checked));
            updateSelectedCount();
        });

        rowCheckboxes.forEach(cb =>
            cb.addEventListener('change', () => {
                updateSelectedCount();
                if (!cb.checked) selectAll.checked = false;
            })
        );

        bulkActionBtn.addEventListener('click', () => {
            notify({
                title: 'Bulk actions',
                text: 'Select one or more members using the checkboxes, then choose Activate or Deactivate.',
                icon: 'info',
                confirmText: 'Got it',
            });
        });

        bulkActionButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const count = updateSelectedCount();
                if (!count) {
                    notify({ title: 'No users selected', text: 'Pick at least one member first.', icon: 'info' });
                    return;
                }
                const action = btn.dataset.bulkAction;
                confirmAction({
                    title: `${action === 'activate' ? 'Activate' : 'Deactivate'} ${count} user(s)?`,
                    text: `This will ${action} the selected accounts.`,
                    confirmText: `Yes, ${action}`,
                }).then(result => {
                    if (result.isConfirmed) {
                        notify({
                            title: 'Action completed',
                            text: `Successfully ${action}d ${count} user(s).`,
                        });
                    }
                });
            });
        });

        const validateInvite = () => {
            const isValid = inviteForm.checkValidity() && inviteRolesSelected.size > 0;
            sendInviteBtn.disabled = !isValid;
        };

        inviteRoleContainer.querySelectorAll('.chip-option').forEach(chip => {
            chip.addEventListener('click', () => {
                chip.classList.toggle('active');
                inviteRolesSelected.has(chip.dataset.role)
                    ? inviteRolesSelected.delete(chip.dataset.role)
                    : inviteRolesSelected.add(chip.dataset.role);
                validateInvite();
            });
        });

        inviteForm.addEventListener('input', validateInvite);

        inviteForm.addEventListener('submit', e => {
            e.preventDefault();
            closeModal(document.querySelector('#inviteModal'));
            notify({
                title: 'Invitation sent',
                text: `An invitation was sent to ${document.querySelector('#inviteEmail').value}.`,
            });
            inviteForm.reset();
            inviteRolesSelected.clear();
            inviteRoleContainer.querySelectorAll('.chip-option').forEach(chip => chip.classList.remove('active'));
            validateInvite();
        });

        copyTempPassBtn.addEventListener('click', () => {
            const tempInput = document.querySelector('#inviteTempPass');
            navigator.clipboard.writeText(tempInput.value);
            notify({ title: 'Copied', text: 'Temporary password copied to clipboard.', icon: 'success' });
        });

        const syncEditRoleChips = () => {
            editRoleChips.forEach(chip => {
                chip.classList.toggle('active', editRolesSelected.has(chip.dataset.role));
            });
        };

        const syncStatusChips = () => {
            statusChips.forEach(chip => {
                chip.classList.toggle('active', chip.dataset.status === currentEditStatus);
            });
        };

        editRoleChips.forEach(chip => {
            chip.addEventListener('click', () => {
                chip.classList.toggle('active');
                editRolesSelected.has(chip.dataset.role)
                    ? editRolesSelected.delete(chip.dataset.role)
                    : editRolesSelected.add(chip.dataset.role);
            });
        });

        statusChips.forEach(chip => {
            chip.addEventListener('click', () => {
                statusChips.forEach(c => c.classList.remove('active'));
                chip.classList.add('active');
                currentEditStatus = chip.dataset.status;
            });
        });

        document.querySelectorAll('[data-modal-open]').forEach(btn => {
            btn.addEventListener('click', () => {
                const target = btn.dataset.modalOpen;
                if (target === 'editUserModal') {
                    const row = btn.closest('tr');
                    if (row) {
                        editUserName.value = row.dataset.name;
                        editUserDepartment.value = row.dataset.department;
                        editUserMeta.textContent = `${row.dataset.email} • ${row.dataset.department}`;
                        currentEditStatus = row.dataset.status;
                        editRolesSelected = new Set(JSON.parse(row.dataset.roles || '[]'));
                        syncEditRoleChips();
                        syncStatusChips();
                        if (inlineResetBtn) {
                            inlineResetBtn.dataset.confirm = `Reset password for ${row.dataset.name}?`;
                            inlineResetBtn.dataset.success = `Password reset emailed to ${row.dataset.email}.`;
                        }
                        if (suspendBtn) {
                            suspendBtn.dataset.confirm = `Suspend access for ${row.dataset.name}?`;
                            suspendBtn.dataset.success = `${row.dataset.name} access suspended.`;
                        }
                    }
                }

                if (target === 'departmentModal') {
                    const deptName = btn.dataset.departmentName || '';
                    const deptHead = btn.dataset.departmentHead || '';
                    departmentNameInput.value = deptName;
                    departmentHeadInput.value = deptHead;
                }

                openModal(target);
            });
        });

        editUserForm.addEventListener('submit', e => {
            e.preventDefault();
            closeModal(document.querySelector('#editUserModal'));
            notify({ title: 'User updated', text: `${editUserName.value} saved successfully.` });
        });

        editRoleForm.addEventListener('submit', e => {
            e.preventDefault();
            closeModal(document.querySelector('#editRoleModal'));
            notify({ title: 'Role updated', text: `${document.querySelector('#roleName').value} permissions saved.` });
        });

        departmentForm.addEventListener('submit', e => {
            e.preventDefault();
            closeModal(document.querySelector('#departmentModal'));
            notify({ title: 'Department saved', text: `${departmentNameInput.value} updated.` });
        });

        document.querySelectorAll('[data-confirm]').forEach(btn => {
            btn.addEventListener('click', () => {
                const confirmText = btn.dataset.confirm || 'Proceed with this action?';
                const successText = btn.dataset.success || 'Action completed.';
                confirmAction({ title: 'Please confirm', text: confirmText }).then(result => {
                    if (result.isConfirmed) {
                        notify({ title: 'Done', text: successText });
                    }
                });
            });
        });

        const tabs = document.querySelectorAll('.admin-tab');
        const panels = {
            roles: document.querySelector('#tabRoles'),
            permissions: document.querySelector('#tabPermissions'),
            departments: document.querySelector('#tabDepartments'),
        };

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                Object.values(panels).forEach(panel => (panel.hidden = true));
                panels[tab.dataset.tab].hidden = false;
            });
        });
    });
</script>
@endpush
@endsection
