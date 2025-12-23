<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import StatCard from "@/Components/Dashboard/StatCard.vue";
import ConfirmDialog from "@/Components/ConfirmDialog.vue";
import {
    Users,
    UserCog,
    Briefcase,
    Building2,
    Download,
    UserPlus,
    Search,
    Eye,
    Edit,
    Trash2,
} from "lucide-vue-next";
import { ref } from "vue";

defineOptions({ layout: DashboardLayout });

const props = defineProps({
    users: Object,
    stats: Object,
    filters: Object,
});

// Filter states
const searchQuery = ref(props.filters?.search || "");
const selectedRole = ref(props.filters?.role || "");

// Confirm Dialog state
const showConfirmDialog = ref(false);
const userToDelete = ref(null);

// Apply filters
const applyFilters = () => {
    router.get(
        route("dashboard.users.index"),
        {
            search: searchQuery.value,
            role: selectedRole.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
};

// Delete user - show confirmation dialog
const deleteUser = (userId, userName) => {
    userToDelete.value = { id: userId, name: userName };
    showConfirmDialog.value = true;
};

// Confirm delete action
const confirmDelete = () => {
    if (!userToDelete.value) return;

    router.delete(route("dashboard.users.destroy", userToDelete.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            userToDelete.value = null;
        },
        onError: () => {
            userToDelete.value = null;
        },
    });
};

// Cancel delete
const cancelDelete = () => {
    userToDelete.value = null;
};

// Role badge colors
const getRoleColor = (role) => {
    const colors = {
        administrator: "bg-purple-500/20 text-purple-400 border-purple-500/30",
        expert: "bg-blue-500/20 text-blue-400 border-blue-500/30",
        client: "bg-green-500/20 text-green-400 border-green-500/30",
        user: "bg-slate-500/20 text-slate-400 border-slate-500/30",
    };
    return colors[role] || colors.user;
};
</script>

<template>
    <Head title="Manage Users" />

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-2">
            <div>
                <h2 class="text-2xl font-bold text-slate-100">Manage Users</h2>
                <p class="text-slate-400 mt-1">
                    Oversee user accounts, manage roles, and track activity.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <button
                    class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 font-medium rounded-lg border border-slate-700 transition-colors flex items-center gap-2"
                >
                    <Download class="w-4 h-4" />
                    <span class="hidden sm:inline">Export</span>
                </button>
                <button
                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-colors flex items-center gap-2"
                >
                    <UserPlus class="w-4 h-4" />
                    <span class="hidden sm:inline">Add User</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <StatCard
            label="Total Users"
            :value="stats.total"
            :icon="Users"
            iconColor="blue"
        />

        <StatCard
            label="Administrators"
            :value="stats.administrators"
            :icon="UserCog"
            iconColor="purple"
        />

        <StatCard
            label="Experts"
            :value="stats.experts"
            :icon="Briefcase"
            iconColor="blue"
        />

        <StatCard
            label="Clients"
            :value="stats.clients"
            :icon="Building2"
            iconColor="green"
        />
    </div>

    <!-- Search and Filters -->
    <div
        class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 p-6 mb-6"
    >
        <div class="flex flex-col md:flex-row gap-4">
            <!-- Search -->
            <div class="relative flex-1">
                <Search
                    class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                />
                <input
                    v-model="searchQuery"
                    @keyup.enter="applyFilters"
                    type="text"
                    placeholder="Search by name, email, or phone"
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-900/50 border border-slate-700 rounded-lg text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500"
                />
            </div>

            <!-- Role Filter -->
            <select
                v-model="selectedRole"
                @change="applyFilters"
                class="px-4 py-2.5 bg-slate-900/50 border border-slate-700 rounded-lg text-sm text-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 cursor-pointer"
            >
                <option value="">All Roles</option>
                <option value="administrator">Administrator</option>
                <option value="expert">Expert</option>
                <option value="client">Client</option>
                <option value="user">User</option>
            </select>
        </div>
    </div>

    <!-- Users Table -->
    <div
        class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 overflow-hidden"
    >
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-900/30">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider"
                        >
                            User Profile
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider"
                        >
                            Roles
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider"
                        >
                            Contact
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider"
                        >
                            Activity
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider"
                        >
                            Manage
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/30">
                    <template v-if="users.data && users.data.length > 0">
                        <tr
                            v-for="user in users.data"
                            :key="user.id"
                            class="hover:bg-slate-900/20 transition-colors"
                        >
                            <!-- User Profile -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <img
                                        :src="user.picture"
                                        :alt="user.name"
                                        class="w-10 h-10 rounded-full object-cover border-2 border-slate-700"
                                    />
                                    <div>
                                        <div
                                            class="text-sm font-semibold text-slate-200"
                                        >
                                            {{ user.name }}
                                        </div>
                                        <div class="text-xs text-slate-500">
                                            {{ user.email }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Roles -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-wrap gap-1">
                                    <span
                                        v-for="role in user.roles"
                                        :key="role"
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold border"
                                        :class="getRoleColor(role)"
                                    >
                                        {{
                                            role.charAt(0).toUpperCase() +
                                            role.slice(1)
                                        }}
                                    </span>
                                </div>
                            </td>

                            <!-- Contact -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-200">
                                    {{ user.phone }}
                                </div>
                                <div
                                    class="text-xs text-slate-500 truncate max-w-[200px]"
                                >
                                    {{ user.address }}
                                </div>
                            </td>

                            <!-- Activity -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-200">
                                    {{ user.appointments_count }} Appointments
                                </div>
                                <div class="text-xs text-slate-400">
                                    Joined: {{ user.created_at }}
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <Link
                                        :href="
                                            route(
                                                'dashboard.users.show',
                                                user.id
                                            )
                                        "
                                        class="p-2 text-slate-400 hover:text-blue-400 hover:bg-slate-700 rounded-lg transition-colors"
                                        title="View"
                                    >
                                        <Eye class="w-4 h-4" />
                                    </Link>
                                    <Link
                                        :href="
                                            route(
                                                'dashboard.users.edit',
                                                user.id
                                            )
                                        "
                                        class="p-2 text-slate-400 hover:text-blue-400 hover:bg-slate-700 rounded-lg transition-colors"
                                        title="Edit"
                                    >
                                        <Edit class="w-4 h-4" />
                                    </Link>
                                    <button
                                        @click="deleteUser(user.id, user.name)"
                                        class="p-2 text-slate-400 hover:text-red-400 hover:bg-slate-700 rounded-lg transition-colors"
                                        title="Delete"
                                    >
                                        <Trash2 class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr v-else>
                        <td
                            colspan="5"
                            class="px-6 py-12 text-center text-slate-500"
                        >
                            No users found
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div
            v-if="users.data && users.data.length > 0"
            class="px-6 py-4 border-t border-slate-700/50 flex items-center justify-between"
        >
            <div class="text-sm text-slate-400">
                Showing {{ users.from }} to {{ users.to }} of
                {{ users.total }} users
            </div>

            <div class="flex items-center gap-2">
                <Link
                    v-if="users.prev_page_url"
                    :href="users.prev_page_url"
                    class="px-3 py-1.5 bg-slate-700 hover:bg-slate-600 text-slate-200 text-sm rounded-lg transition-colors"
                    preserve-scroll
                >
                    Prev
                </Link>

                <template v-for="link in users.links" :key="link.label">
                    <Link
                        v-if="
                            link.url &&
                            !link.label.includes('Previous') &&
                            !link.label.includes('Next')
                        "
                        :href="link.url"
                        class="px-3 py-1.5 text-sm rounded-lg transition-colors"
                        :class="
                            link.active
                                ? 'bg-blue-500 text-white font-semibold'
                                : 'bg-slate-700 hover:bg-slate-600 text-slate-200'
                        "
                        preserve-scroll
                    >
                        <span v-html="link.label"></span>
                    </Link>
                </template>

                <Link
                    v-if="users.next_page_url"
                    :href="users.next_page_url"
                    class="px-3 py-1.5 bg-slate-700 hover:bg-slate-600 text-slate-200 text-sm rounded-lg transition-colors"
                    preserve-scroll
                >
                    Next
                </Link>
            </div>
        </div>
    </div>

    <!-- Confirm Delete Dialog -->
    <ConfirmDialog
        :show="showConfirmDialog"
        title="Delete User?"
        :message="`Are you sure you want to delete ${userToDelete?.name}? This action cannot be undone and will permanently remove all user data.`"
        confirm-text="Yes, Delete"
        cancel-text="Cancel"
        :dangerous="true"
        @confirm="confirmDelete"
        @cancel="cancelDelete"
        @close="showConfirmDialog = false"
    />
</template>
