<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import StatCard from "@/Components/Dashboard/StatCard.vue";
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
} from "lucide-vue-next";
import { ref, computed } from "vue";

defineOptions({ layout: DashboardLayout });

const props = defineProps({
    experts: Object,
    stats: Object,
    filters: Object,
});

// Filter states
const searchQuery = ref(props.filters?.search || "");
const selectedSpecialization = ref(props.filters?.specialization || "");
const selectedStatus = ref(props.filters?.status || "");

// Apply filters
const applyFilters = () => {
    router.get(
        route("dashboard.experts.index"),
        {
            search: searchQuery.value,
            specialization: selectedSpecialization.value,
            status: selectedStatus.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
};

// Delete expert with confirmation
const deleteExpert = (expertId, expertName) => {
    if (
        confirm(
            `Are you sure you want to delete ${expertName}? This action cannot be undone.`
        )
    ) {
        router.delete(route("dashboard.experts.destroy", expertId), {
            preserveScroll: true,
            onSuccess: () => {
                // Success message will be shown by backend
            },
        });
    }
};

// Status badge colors
const getStatusColor = (status) => {
    const colors = {
        active: "bg-green-500/20 text-green-400 border-green-500/30",
        pending: "bg-yellow-500/20 text-yellow-400 border-yellow-500/30",
        inactive: "bg-slate-500/20 text-slate-400 border-slate-500/30",
        suspended: "bg-red-500/20 text-red-400 border-red-500/30",
    };
    return colors[status] || colors.inactive;
};

const getStatusLabel = (status) => {
    const labels = {
        active: "Active",
        pending: "Pending",
        inactive: "Inactive",
        suspended: "Suspended",
    };
    return labels[status] || status;
};

// Performance bar color
const getPerformanceColor = (rating) => {
    if (rating >= 4.5) return "bg-green-500";
    if (rating >= 4.0) return "bg-blue-500";
    if (rating >= 3.5) return "bg-yellow-500";
    return "bg-slate-500";
};

const getPerformanceWidth = (rating) => {
    return `${(rating / 5) * 100}%`;
};
</script>

<template>
    <Head title="Manage Experts" />

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-2">
            <div>
                <h2 class="text-2xl font-bold text-slate-100">
                    Manage Experts
                </h2>
                <p class="text-slate-400 mt-1">
                    Oversee expert profiles, track performance, and manage
                    account statuses.
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
                    <span class="hidden sm:inline">Add Expert</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <StatCard
            label="Total Experts"
            :value="stats.total_experts"
            :icon="Users"
            iconColor="blue"
        />

        <StatCard
            label="Active Now"
            :value="stats.active_now"
            :icon="CheckCircle"
            iconColor="green"
        />

        <StatCard
            label="Pending Approval"
            :value="stats.pending_approval"
            :icon="Clock"
            iconColor="orange"
        />

        <StatCard
            label="Suspended"
            :value="stats.suspended"
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
                    placeholder="Search by name, specialization, or email"
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-900/50 border border-slate-700 rounded-lg text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500"
                />
            </div>

            <!-- Specialization Filter -->
            <select
                v-model="selectedSpecialization"
                @change="applyFilters"
                class="px-4 py-2.5 bg-slate-900/50 border border-slate-700 rounded-lg text-sm text-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 cursor-pointer"
            >
                <option value="">All Specializations</option>
                <option value="cardiology">Cardiology</option>
                <option value="blockchain">Blockchain</option>
                <option value="design">Graphic Design</option>
                <option value="engineering">Engineering</option>
                <option value="law">Corporate Law</option>
            </select>

            <!-- Status Filter -->
            <select
                v-model="selectedStatus"
                @change="applyFilters"
                class="px-4 py-2.5 bg-slate-900/50 border border-slate-700 rounded-lg text-sm text-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 cursor-pointer"
            >
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="pending">Pending</option>
                <option value="inactive">Inactive</option>
                <option value="suspended">Suspended</option>
            </select>
        </div>
    </div>

    <!-- Experts Table -->
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
                            Expert Profile
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider"
                        >
                            Specialization
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider"
                        >
                            Status
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider"
                        >
                            Performance
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider"
                        >
                            Manage
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/30">
                    <template v-if="experts.data && experts.data.length > 0">
                        <tr
                            v-for="expert in experts.data"
                            :key="expert.id"
                            class="hover:bg-slate-900/20 transition-colors"
                        >
                            <!-- Expert Profile -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="relative">
                                        <img
                                            :src="
                                                expert.avatar ||
                                                'https://ui-avatars.com/api/?name=' +
                                                    expert.name +
                                                    '&background=3b82f6&color=fff'
                                            "
                                            :alt="expert.name"
                                            class="w-10 h-10 rounded-full object-cover border-2 border-slate-700"
                                        />
                                        <div
                                            v-if="expert.is_online"
                                            class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-slate-800 rounded-full"
                                        ></div>
                                    </div>
                                    <div>
                                        <div
                                            class="text-sm font-semibold text-slate-200"
                                        >
                                            {{ expert.name }}
                                        </div>
                                        <div class="text-xs text-slate-500">
                                            ID: {{ expert.expert_id }}
                                        </div>
                                        <div class="text-xs text-slate-500">
                                            {{ expert.email }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Specialization -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-slate-200">
                                    {{ expert.specialization }}
                                </div>
                                <div
                                    v-if="expert.category"
                                    class="text-xs text-slate-500 mt-0.5"
                                >
                                    <span
                                        class="px-2 py-0.5 bg-slate-700/50 rounded text-xs"
                                        >{{ expert.category }}</span
                                    >
                                </div>
                                <div class="text-xs text-slate-500 mt-1">
                                    {{ expert.experience }}
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border"
                                    :class="getStatusColor(expert.status)"
                                >
                                    {{ getStatusLabel(expert.status) }}
                                </span>
                            </td>

                            <!-- Performance -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-200 mb-1">
                                    {{ expert.performance.appointments }} Appts
                                    <span class="text-slate-500 ml-2">{{
                                        expert.performance.label
                                    }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div
                                        class="flex-1 h-1.5 bg-slate-700 rounded-full overflow-hidden"
                                    >
                                        <div
                                            class="h-full transition-all duration-300"
                                            :class="
                                                getPerformanceColor(
                                                    expert.performance.rating
                                                )
                                            "
                                            :style="{
                                                width: getPerformanceWidth(
                                                    expert.performance.rating
                                                ),
                                            }"
                                        ></div>
                                    </div>
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <Link
                                        :href="
                                            route(
                                                'dashboard.experts.show',
                                                expert.id
                                            )
                                        "
                                        class="p-2 text-slate-400 hover:text-blue-400 hover:bg-slate-700 rounded-lg transition-colors"
                                        title="View"
                                    >
                                        <Eye class="w-4 h-4" />
                                    </Link>
                                    <Link
                                        :href="route('dashboard.experts.edit', expert.id)"
                                        class="p-2 text-slate-400 hover:text-blue-400 hover:bg-slate-700 rounded-lg transition-colors"
                                        title="Edit"
                                    >
                                        <Edit class="w-4 h-4" />
                                    </Link>
                                    <button
                                        @click="
                                            deleteExpert(expert.id, expert.name)
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
                            No experts found
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div
            v-if="experts.data && experts.data.length > 0"
            class="px-6 py-4 border-t border-slate-700/50 flex items-center justify-between"
        >
            <div class="text-sm text-slate-400">
                Showing {{ experts.from }} to {{ experts.to }} of
                {{ experts.total }} experts
            </div>

            <div class="flex items-center gap-2">
                <Link
                    v-if="experts.prev_page_url"
                    :href="experts.prev_page_url"
                    class="px-3 py-1.5 bg-slate-700 hover:bg-slate-600 text-slate-200 text-sm rounded-lg transition-colors"
                    preserve-scroll
                >
                    Prev
                </Link>

                <template v-for="link in experts.links" :key="link.label">
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
                    v-if="experts.next_page_url"
                    :href="experts.next_page_url"
                    class="px-3 py-1.5 bg-slate-700 hover:bg-slate-600 text-slate-200 text-sm rounded-lg transition-colors"
                    preserve-scroll
                >
                    Next
                </Link>
            </div>
        </div>
    </div>
</template>
