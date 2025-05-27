@extends('layouts.app')

@section('title', 'Roles & Permissions')
@section('page-title', 'Roles & Permissions Management')

@section('content')
<div class="max-w-7xl mx-auto" x-data="rolePermissionManager()">
    <!-- Header with Actions -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Roles & Permissions</h1>
            <p class="text-gray-600">Manage system roles and permissions</p>
        </div>
        <div class="flex space-x-3">
            <button @click="showAddPermissionModal = true" 
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Permission
            </button>
            <button @click="showAddRoleModal = true" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Role
            </button>
        </div>
    </div>

    <!-- Permissions Section -->
    <div class="mb-8">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Permissions</h2>
                <p class="text-sm text-gray-600">Available system permissions</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @forelse($permissions as $permission)
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $permission->name }}</h4>
                                <p class="text-xs text-gray-500">ID: {{ $permission->id }}</p>
                            </div>
                            <button @click="deletePermission({{ $permission->id }})" 
                                    class="text-red-600 hover:text-red-800 transition-colors"
                                    title="Delete Permission">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No permissions</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new permission.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Roles Section -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Roles</h2>
            <p class="text-sm text-gray-600">Manage roles and their permissions</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permissions</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($roles as $role)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $role->name }}</div>
                            <div class="text-sm text-gray-500">{{ $role->permissions->count() }} permissions</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @forelse($role->permissions as $permission)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $permission->name }}
                                </span>
                                @empty
                                <span class="text-sm text-gray-500">No permissions assigned</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button @click="editRole({{ $role->id }}, '{{ $role->name }}', {{ $role->permissions->pluck('id')->toJson() }})" 
                                    class="text-indigo-600 hover:text-indigo-900 mr-3">
                                Edit
                            </button>
                            <button @click="deleteRole({{ $role->id }})" 
                                    class="text-red-600 hover:text-red-900">
                                Delete
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No roles</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new role.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Permission Modal -->
    <div x-show="showAddPermissionModal" x-transition class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Permission</h3>
                <form @submit.prevent="addPermission()">
                    <div class="mb-4">
                        <label for="permission_name" class="block text-sm font-medium text-gray-700 mb-2">Permission Name</label>
                        <input type="text" 
                               id="permission_name" 
                               x-model="newPermission.name"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="e.g., create posts, view users"
                               required>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" 
                                @click="showAddPermissionModal = false"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Cancel
                        </button>
                        <button type="submit"
                                :disabled="isLoading"
                                class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 disabled:opacity-50">
                            <span x-show="!isLoading">Add Permission</span>
                            <span x-show="isLoading">Adding...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add/Edit Role Modal -->
    <div x-show="showAddRoleModal" x-transition class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white max-h-[80vh] overflow-y-auto">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4" x-text="editingRole ? 'Edit Role' : 'Add New Role'"></h3>
                <form @submit.prevent="editingRole ? updateRole() : addRole()">
                    <div class="mb-4">
                        <label for="role_name" class="block text-sm font-medium text-gray-700 mb-2">Role Name</label>
                        <input type="text" 
                               id="role_name" 
                               x-model="newRole.name"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="e.g., admin, editor, viewer"
                               required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Permissions</label>
                        <div class="max-h-40 overflow-y-auto border border-gray-300 rounded-md p-3">
                            @foreach($permissions as $permission)
                            <label class="flex items-center mb-2">
                                <input type="checkbox" 
                                       value="{{ $permission->id }}"
                                       x-model="newRole.permissions"
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">{{ $permission->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" 
                                @click="closeRoleModal()"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Cancel
                        </button>
                        <button type="submit"
                                :disabled="isLoading"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50">
                            <span x-show="!isLoading && !editingRole">Add Role</span>
                            <span x-show="!isLoading && editingRole">Update Role</span>
                            <span x-show="isLoading">Processing...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function rolePermissionManager() {
    return {
        showAddPermissionModal: false,
        showAddRoleModal: false,
        isLoading: false,
        editingRole: false,
        editingRoleId: null,
        newPermission: {
            name: ''
        },
        newRole: {
            name: '',
            permissions: []
        },

        async addPermission() {
            this.isLoading = true;
            try {
                const response = await fetch('{{ route("roles-permissions.store-permission") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.newPermission)
                });

                const data = await response.json();
                
                if (data.success) {
                    this.showNotification(data.message, 'success');
                    this.showAddPermissionModal = false;
                    this.newPermission.name = '';
                    location.reload(); // Refresh to show new permission
                } else {
                    this.showNotification(data.message, 'error');
                }
            } catch (error) {
                this.showNotification('An error occurred while adding the permission', 'error');
            }
            this.isLoading = false;
        },

        async addRole() {
            this.isLoading = true;
            try {
                const response = await fetch('{{ route("roles-permissions.store-role") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.newRole)
                });

                const data = await response.json();
                
                if (data.success) {
                    this.showNotification(data.message, 'success');
                    this.closeRoleModal();
                    location.reload(); // Refresh to show new role
                } else {
                    this.showNotification(data.message, 'error');
                }
            } catch (error) {
                this.showNotification('An error occurred while adding the role', 'error');
            }
            this.isLoading = false;
        },

        editRole(id, name, permissions) {
            this.editingRole = true;
            this.editingRoleId = id;
            this.newRole.name = name;
            this.newRole.permissions = permissions;
            this.showAddRoleModal = true;
        },

        async updateRole() {
            this.isLoading = true;
            try {
                const response = await fetch(`/roles-permissions/roles/${this.editingRoleId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.newRole)
                });

                const data = await response.json();
                
                if (data.success) {
                    this.showNotification(data.message, 'success');
                    this.closeRoleModal();
                    location.reload(); // Refresh to show updated role
                } else {
                    this.showNotification(data.message, 'error');
                }
            } catch (error) {
                this.showNotification('An error occurred while updating the role', 'error');
            }
            this.isLoading = false;
        },

        async deleteRole(id) {
            if (confirm('Are you sure you want to delete this role?')) {
                try {
                    const response = await fetch(`/roles-permissions/roles/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    const data = await response.json();
                    
                    if (data.success) {
                        this.showNotification(data.message, 'success');
                        location.reload();
                    } else {
                        this.showNotification(data.message, 'error');
                    }
                } catch (error) {
                    this.showNotification('An error occurred while deleting the role', 'error');
                }
            }
        },

        async deletePermission(id) {
            if (confirm('Are you sure you want to delete this permission?')) {
                try {
                    const response = await fetch(`/roles-permissions/permissions/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    const data = await response.json();
                    
                    if (data.success) {
                        this.showNotification(data.message, 'success');
                        location.reload();
                    } else {
                        this.showNotification(data.message, 'error');
                    }
                } catch (error) {
                    this.showNotification('An error occurred while deleting the permission', 'error');
                }
            }
        },

        closeRoleModal() {
            this.showAddRoleModal = false;
            this.editingRole = false;
            this.editingRoleId = null;
            this.newRole = {
                name: '',
                permissions: []
            };
        },

        showNotification(message, type) {
            // Simple notification - you can enhance this with a proper notification system
            alert(message);
        }
    }
}
</script>
@endsection