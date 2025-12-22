<script setup>
import { Head } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import StatCard from "@/Components/Dashboard/StatCard.vue";
import {
    Users,
    Calendar,
    DollarSign,
    Building2,
    Plus,
    UserPlus,
} from "lucide-vue-next";
import { computed, onMounted, ref } from "vue";
import ApexCharts from "apexcharts";

// Menggunakan Persistent Layout
defineOptions({ layout: DashboardLayout });

const props = defineProps({
    stats: Object,
});

// Helper untuk format currency
const formatCurrency = (val) =>
    new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(val);

// Helper untuk format date/time
const formatDateTime = (dateStr) => {
    const date = new Date(dateStr);
    return {
        month: date.toLocaleDateString("en-US", { month: "short" }),
        day: date.getDate(),
        time: date.toLocaleTimeString("en-US", {
            hour: "2-digit",
            minute: "2-digit",
            hour12: true,
        }),
    };
};

// Status badge colors
const getStatusColor = (status) => {
    const colors = {
        need_confirmation:
            "bg-yellow-500/20 text-yellow-400 border-yellow-500/30",
        confirmed: "bg-green-500/20 text-green-400 border-green-500/30",
        progress: "bg-blue-500/20 text-blue-400 border-blue-500/30",
        completed: "bg-violet-500/20 text-violet-400 border-violet-500/30",
        cancelled: "bg-red-500/20 text-red-400 border-red-500/30",
    };
    return (
        colors[status] || "bg-slate-500/20 text-slate-400 border-slate-500/30"
    );
};

const getStatusLabel = (status) => {
    const labels = {
        need_confirmation: "Pending",
        confirmed: "Confirmed",
        progress: "In Progress",
        completed: "Completed",
        cancelled: "Cancelled",
    };
    return labels[status] || status;
};

// Appointment Trends Chart
const chartRef = ref(null);

onMounted(() => {
    if (chartRef.value && props.stats.appointment_trends) {
        const dates = props.stats.appointment_trends.map((item) => {
            const date = new Date(item.date);
            return date.toLocaleDateString("en-US", {
                month: "short",
                day: "numeric",
            });
        });
        const counts = props.stats.appointment_trends.map((item) => item.count);

        const options = {
            series: [
                {
                    name: "Total Bookings",
                    data: counts,
                },
            ],
            chart: {
                type: "area",
                height: 320,
                background: "transparent",
                toolbar: {
                    show: false,
                },
                zoom: {
                    enabled: false,
                },
            },
            theme: {
                mode: "dark",
            },
            dataLabels: {
                enabled: false,
            },
            stroke: {
                curve: "smooth",
                width: 3,
                colors: ["#3b82f6"],
            },
            fill: {
                type: "gradient",
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.1,
                    stops: [0, 90, 100],
                },
                colors: ["#3b82f6"],
            },
            xaxis: {
                categories: dates,
                labels: {
                    style: {
                        colors: "#94a3b8",
                        fontSize: "11px",
                    },
                },
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false,
                },
            },
            yaxis: {
                labels: {
                    style: {
                        colors: "#94a3b8",
                        fontSize: "11px",
                    },
                },
            },
            grid: {
                borderColor: "#334155",
                strokeDashArray: 4,
                xaxis: {
                    lines: {
                        show: false,
                    },
                },
            },
            tooltip: {
                theme: "dark",
                y: {
                    formatter: function (val) {
                        return val + " bookings";
                    },
                },
            },
        };

        const chart = new ApexCharts(chartRef.value, options);
        chart.render();
    }
});

// Computed stats
const totalBookings = computed(() => {
    if (!props.stats.appointment_trends) return 0;
    return props.stats.appointment_trends.reduce(
        (sum, item) => sum + item.count,
        0
    );
});
</script>

<template>
    <Head title="Administrator Dashboard" />

    <!-- Header with Action Buttons -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-100">
                Dashboard Overview
            </h2>
            <p class="text-slate-400 mt-1">
                Welcome back, Administrator. Here's what's happening today.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <button
                class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 font-medium rounded-lg border border-slate-700 transition-colors flex items-center gap-2"
            >
                <Plus class="w-4 h-4" />
                <span class="hidden sm:inline">New Booking</span>
            </button>
            <button
                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-colors flex items-center gap-2"
            >
                <UserPlus class="w-4 h-4" />
                <span class="hidden sm:inline">Add Expert</span>
            </button>
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <StatCard
            label="Total Experts"
            :value="stats.total_users"
            :icon="Users"
            iconColor="blue"
            trend="+12%"
        />

        <StatCard
            label="Active Appointments"
            :value="stats.total_appointments"
            :icon="Calendar"
            iconColor="violet"
            trend="+5%"
        />

        <StatCard
            label="New Institutions"
            :value="56"
            :icon="Building2"
            iconColor="green"
            trend="+2%"
        />

        <StatCard
            label="Monthly Revenue"
            :value="formatCurrency(stats.total_revenue)"
            :icon="DollarSign"
            iconColor="orange"
            trend="+8%"
        />
    </div>

    <!-- Charts and Quick Schedule Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Appointment Trends Chart -->
        <div
            class="lg:col-span-2 bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 p-6"
        >
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-100">
                        Appointment Trends
                    </h3>
                    <p class="text-sm text-slate-400 mt-1">
                        Booking volume over last 30 days
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-slate-400">Last 30 Days</span>
                </div>
            </div>
            <div class="mb-4 flex items-baseline gap-2">
                <span class="text-3xl font-bold text-slate-100">{{
                    totalBookings
                }}</span>
                <span class="text-sm text-slate-400">Total Bookings</span>
            </div>
            <div ref="chartRef"></div>
        </div>

        <!-- Quick Schedule Sidebar -->
        <div
            class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 p-6"
        >
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-slate-100">Quick Schedule</h3>
                <a
                    href="#"
                    class="text-sm text-blue-400 hover:text-blue-300 font-medium"
                    >View Calendar</a
                >
            </div>

            <div class="space-y-4">
                <template
                    v-if="
                        stats.quick_schedule && stats.quick_schedule.length > 0
                    "
                >
                    <div
                        v-for="appointment in stats.quick_schedule"
                        :key="appointment.id"
                        class="flex items-start gap-3 pb-4 border-b border-slate-700/50 last:border-0 last:pb-0"
                    >
                        <div
                            class="flex flex-col items-center bg-slate-900/50 rounded-lg p-2 min-w-[48px]"
                        >
                            <span
                                class="text-xs font-bold text-slate-400 uppercase"
                                >{{
                                    formatDateTime(appointment.date_time).month
                                }}</span
                            >
                            <span class="text-xl font-bold text-slate-100">{{
                                formatDateTime(appointment.date_time).day
                            }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4
                                class="text-sm font-semibold text-slate-200 truncate"
                            >
                                {{ appointment.title }}
                            </h4>
                            <p class="text-xs text-slate-400 mt-0.5">
                                {{ appointment.type }}
                            </p>
                            <p class="text-xs text-slate-500 mt-1">
                                {{ formatDateTime(appointment.date_time).time }}
                            </p>
                        </div>
                        <div
                            class="w-2 h-2 rounded-full bg-blue-400 mt-2"
                        ></div>
                    </div>
                </template>
                <div v-else class="text-center py-8 text-slate-500 text-sm">
                    No upcoming appointments
                </div>

                <button
                    class="w-full mt-4 px-4 py-2 bg-slate-900/50 hover:bg-slate-900 text-slate-300 hover:text-slate-100 font-medium text-sm rounded-lg border border-slate-700 transition-colors flex items-center justify-center gap-2"
                >
                    <Calendar class="w-4 h-4" />
                    Connect Google Calendar
                </button>
            </div>
        </div>
    </div>

    <!-- Recent Booking Requests Table -->
    <div
        class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 overflow-hidden"
    >
        <div
            class="p-6 border-b border-slate-700/50 flex items-center justify-between"
        >
            <div>
                <h3 class="text-lg font-bold text-slate-100">
                    Recent Booking Requests
                </h3>
                <p class="text-sm text-slate-400 mt-1">
                    Latest appointment requests from clients
                </p>
            </div>
            <div class="flex items-center gap-2">
                <button
                    class="px-3 py-1.5 bg-slate-900/50 text-slate-300 text-sm font-medium rounded-lg border border-slate-700 hover:bg-slate-900 transition-colors flex items-center gap-2"
                >
                    All Status
                    <svg
                        class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 9l-7 7-7-7"
                        />
                    </svg>
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-900/30">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider"
                        >
                            Expert
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider"
                        >
                            Client Institution
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider"
                        >
                            Date & Time
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider"
                        >
                            Status
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider"
                        >
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/30">
                    <template
                        v-if="
                            stats.recent_bookings &&
                            stats.recent_bookings.length > 0
                        "
                    >
                        <tr
                            v-for="booking in stats.recent_bookings"
                            :key="booking.id"
                            class="hover:bg-slate-900/20 transition-colors"
                        >
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <img
                                        :src="
                                            booking.expert.avatar ||
                                            'https://ui-avatars.com/api/?name=' +
                                                booking.expert.name +
                                                '&background=3b82f6&color=fff'
                                        "
                                        class="w-8 h-8 rounded-full object-cover"
                                        :alt="booking.expert.name"
                                    />
                                    <span
                                        class="text-sm font-medium text-slate-200"
                                        >{{ booking.expert.name }}</span
                                    >
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-slate-300">{{
                                    booking.client.institution
                                }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-300">
                                    {{
                                        new Date(
                                            booking.date_time
                                        ).toLocaleDateString("en-US", {
                                            month: "short",
                                            day: "numeric",
                                            year: "numeric",
                                        })
                                    }}
                                </div>
                                <div class="text-xs text-slate-500">
                                    {{
                                        new Date(
                                            booking.date_time
                                        ).toLocaleTimeString("en-US", {
                                            hour: "2-digit",
                                            minute: "2-digit",
                                        })
                                    }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border"
                                    :class="getStatusColor(booking.status)"
                                >
                                    {{ getStatusLabel(booking.status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <button
                                    class="text-blue-400 hover:text-blue-300 font-medium"
                                >
                                    Review
                                </button>
                            </td>
                        </tr>
                    </template>
                    <tr v-else>
                        <td
                            colspan="5"
                            class="px-6 py-12 text-center text-slate-500"
                        >
                            No recent bookings
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
