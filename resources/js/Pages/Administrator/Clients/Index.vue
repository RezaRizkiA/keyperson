<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import StatCard from "@/Components/Dashboard/StatCard.vue";
import ConfirmDialog from "@/Components/ConfirmDialog.vue";
import {
    Users,
    CheckCircle,
    Clock,
    XCircle,
    Download,
    UserPlus,
    Search,
    Eye,
    Edit,
    Trash2,
    Building2,
} from "lucide-vue-next";
import { ref, computed } from "vue";

defineOptions({ layout: DashboardLayout });

const props = defineProps({
    clients: Object,
    stats: Object,
    filters: Object,
});

// Filter states
const searchQuery = ref(props.filters?.search || "");
const selectedStatus = ref(props.filters?.status || "");

// Confirm Dialog state
const showConfirmDialog = ref(false);
const clientToDelete = ref(null);

// Apply filters
const applyFilters = () => {
    router.get(
        route("dashboard.clients.index"),
        {
            search: searchQuery.value,
            status: selectedStatus.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
};

// Delete client - show confirmation dialog
const deleteClient = (clientId, clientName, clientCompany) => {
    clientToDelete.value = {
        id: clientId,
        name: clientName,
        company: clientCompany,
    };
    showConfirmDialog.value = true;
};

// Confirm delete action
const confirmDelete = () => {
    if (!clientToDelete.value) return;

    router.delete(route("dashboard.clients.destroy", clientToDelete.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            // Backend flash message will be shown by global ToastNotification
            clientToDelete.value = null;
        },
        onError: () => {
            // Backend error will be shown by global ToastNotification
            clientToDelete.value = null;
        },
    });
};

// Cancel delete
const cancelDelete = () => {
    clientToDelete.value = null;
};

// Status badge colors
const getStatusColor = (status) => {
    const colors = {
        active: "bg-green-500/20 text-green-400 border-green-500/30",
        new: "bg-blue-500/20 text-blue-400 border-blue-500/30",
        inactive: "bg-slate-500/20 text-slate-400 border-slate-500/30",
        suspended: "bg-red-500/20 text-red-400 border-red-500/30",
    };
    return colors[status] || colors.inactive;
};

const getStatusLabel = (status) => {
    const labels = {
        active: "Active",
        new: "New",
        inactive: "Inactive",
        suspended: "Suspended",
    };
    return labels[status] || status;
};

// Client label color
const getLabelColor = (label) => {
    const colors = {
        VIP: "bg-purple-500/20 text-purple-400 border-purple-500/30",
        Premium: "bg-blue-500/20 text-blue-400 border-blue-500/30",
        Regular: "bg-green-500/20 text-green-400 border-green-500/30",
        Active: "bg-slate-500/20 text-slate-400 border-slate-500/30",
        New: "bg-yellow-500/20 text-yellow-400 border-yellow-500/30",
    };
    return colors[label] || colors.New;
};
</script>

<template>
    <Head title="Manage Clients" />

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-2">
            <div>
                <h2 class="text-2xl font-bold text-slate-100">
                    Manage Clients
                </h2>
                <p class="text-slate-400 mt-1">
                    Oversee client companies, track activity, and manage account
                    statuses.
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
                    <span class="hidden sm:inline">Add Client</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <StatCard
            label="Total Clients"
            :value="stats.total"
            :icon="Building2"
            iconColor="blue"
        />

        <StatCard
            label="Active Clients"
            :value="stats.active"
            :icon="CheckCircle"
            iconColor="green"
        />

        <StatCard
            label="New This Month"
            :value="stats.new_this_month"
            :icon="Clock"
            iconColor="orange"
        />

        <StatCard
            label="Inactive"
            :value="stats.inactive"
            :icon="XCircle"
            iconColor="red"
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
                    placeholder="Search by company name, industry, or email"
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-900/50 border border-slate-700 rounded-lg text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500"
                />
            </div>

            <!-- Status Filter -->
            <select
                v-model="selectedStatus"
                @change="applyFilters"
                class="px-4 py-2.5 bg-slate-900/50 border border-slate-700 rounded-lg text-sm text-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 cursor-pointer"
            >
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="new">New</option>
                <option value="inactive">Inactive</option>
                <option value="suspended">Suspended</option>
            </select>
        </div>
    </div>

    <!-- Clients Table -->
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
                            Client Profile
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider"
                        >
                            Company Info
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider"
                        >
                            Status
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
                    <template v-if="clients.data && clients.data.length > 0">
                        <tr
                            v-for="client in clients.data"
                            :key="client.id"
                            class="hover:bg-slate-900/20 transition-colors"
                        >
                            <!-- Client Profile -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="relative">
                                        <img
                                            :src="
                                                client.avatar ||
                                                'https://ui-avatars.com/api/?name=' +
                                                    client.name +
                                                    '&background=3b82f6&color=fff'
                                            "
                                            :alt="client.name"
                                            class="w-10 h-10 rounded-full object-cover border-2 border-slate-700"
                                        />
                                        <div
                                            v-if="client.is_verified"
                                            class="absolute bottom-0 right-0 w-3 h-3 bg-blue-500 border-2 border-slate-800 rounded-full"
                                        ></div>
                                    </div>
                                    <div>
                                        <div
                                            class="text-sm font-semibold text-slate-200"
                                        >
                                            {{ client.name }}
                                        </div>
                                        <div class="text-xs text-slate-500">
                                            ID: {{ client.client_id }}
                                        </div>
                                        <div class="text-xs text-slate-500">
                                            {{ client.email }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Company Info -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-slate-200">
                                    {{ client.company_name }}
                                </div>
                                <div
                                    v-if="client.industry"
                                    class="text-xs text-slate-500 mt-0.5"
                                >
                                    <span
                                        class="px-2 py-0.5 bg-slate-700/50 rounded text-xs"
                                        >{{ client.industry }}</span
                                    >
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border"
                                    :class="getStatusColor(client.status)"
                                >
                                    {{ getStatusLabel(client.status) }}
                                </span>
                            </td>

                            <!-- Activity -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-200 mb-1">
                                    {{ client.performance.appointments }} Appts
                                </div>
                                <div class="text-xs text-slate-400">
                                    Total Spent: Rp
                                    {{ client.performance.total_spent }}
                                </div>
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold border mt-1"
                                    :class="
                                        getLabelColor(client.performance.label)
                                    "
                                >
                                    {{ client.performance.label }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <Link
                                        :href="
                                            route(
                                                'dashboard.clients.show',
                                                client.id
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
                                                'dashboard.clients.edit',
                                                client.id
                                            )
                                        "
                                        class="p-2 text-slate-400 hover:text-blue-400 hover:bg-slate-700 rounded-lg transition-colors"
                                        title="Edit"
                                    >
                                        <Edit class="w-4 h-4" />
                                    </Link>
                                    <button
                                        @click="
                                            deleteClient(
                                                client.id,
                                                client.name,
                                                client.company_name
                                            )
                                        "
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
                            No clients found
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div
            v-if="clients.data && clients.data.length > 0"
            class="px-6 py-4 border-t border-slate-700/50 flex items-center justify-between"
        >
            <div class="text-sm text-slate-400">
                Showing {{ clients.from }} to {{ clients.to }} of
                {{ clients.total }} clients
            </div>

            <div class="flex items-center gap-2">
                <Link
                    v-if="clients.prev_page_url"
                    :href="clients.prev_page_url"
                    class="px-3 py-1.5 bg-slate-700 hover:bg-slate-600 text-slate-200 text-sm rounded-lg transition-colors"
                    preserve-scroll
                >
                    Prev
                </Link>

                <template v-for="link in clients.links" :key="link.label">
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
                    v-if="clients.next_page_url"
                    :href="clients.next_page_url"
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
        title="Delete Client?"
        :message="`Are you sure you want to delete ${clientToDelete?.company}? This action cannot be undone and will permanently remove all client data.`"
        confirm-text="Yes, Delete"
        cancel-text="Cancel"
        :dangerous="true"
        @confirm="confirmDelete"
        @cancel="cancelDelete"
        @close="showConfirmDialog = false"
    />
</template>
