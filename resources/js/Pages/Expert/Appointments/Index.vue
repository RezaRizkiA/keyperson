<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import StatCards from "@/Components/Appointments/StatCards.vue";
import AppointmentDetailModal from "@/Components/Appointments/AppointmentDetailModal.vue";
import AppointmentFilters from "@/Components/Appointments/AppointmentFilters.vue";
import AppointmentList from "@/Components/Appointments/AppointmentList.vue";
import AppointmentCalendar from "@/Components/Appointments/AppointmentCalendar.vue";
import { formatDateForAPI } from "@/Utils/dateUtils";
import { ref, watch } from "vue";

defineOptions({ layout: DashboardLayout });

const props = defineProps({
    appointments: [Object, Array],
    stats: Object,
    filters: Object,
});

// View mode and filter states - unified for v-model
const filters = ref({
    search: props.filters?.search || "",
    status: props.filters?.status || "",
    viewMode: "list", // 'list' or 'calendar'
});

// Calendar state (managed here, passed to AppointmentCalendar)
const calendarViewMode = ref("month");
const currentDate = ref(new Date());
const isLoadingCalendar = ref(false);
const isCalendarReady = ref(false);

// Modal states
const selectedAppointment = ref(null);
const showAppointmentModal = ref(false);

// Fetch calendar data with date range (calls API)
const fetchCalendarData = () => {
    if (filters.value.viewMode !== "calendar") return;

    isCalendarReady.value = false;
    isLoadingCalendar.value = true;

    // Calculate date range based on view mode
    let startDate, endDate;
    const date = new Date(currentDate.value);

    if (calendarViewMode.value === "month") {
        startDate = new Date(date.getFullYear(), date.getMonth(), 1);
        endDate = new Date(date.getFullYear(), date.getMonth() + 1, 0);
    } else if (calendarViewMode.value === "week") {
        const day = date.getDay();
        startDate = new Date(date);
        startDate.setDate(date.getDate() - day);
        endDate = new Date(startDate);
        endDate.setDate(startDate.getDate() + 6);
    } else {
        startDate = new Date(date);
        endDate = new Date(date);
    }

    router.get(
        route("dashboard.appointments.index"),
        {
            view_mode: "calendar",
            start_date: formatDateForAPI(startDate),
            end_date: formatDateForAPI(endDate),
            search: filters.value.search,
            status: filters.value.status,
        },
        {
            preserveState: true,
            preserveScroll: true,
            only: ["appointments"],
            onFinish: () => {
                isLoadingCalendar.value = false;
                isCalendarReady.value = true;
            },
        }
    );
};

// Apply filters (for list view or trigger calendar reload)
const applyFilters = () => {
    if (filters.value.viewMode === "calendar") {
        fetchCalendarData();
    } else {
        router.get(
            route("dashboard.appointments.index"),
            {
                page: 1,
                search: filters.value.search,
                status: filters.value.status,
                // Clear calendar params
                view_mode: undefined,
                start_date: undefined,
                end_date: undefined,
            },
            {
                preserveState: false,
                preserveScroll: true,
            }
        );
    }
};

// Watch view mode changes
watch(
    () => filters.value.viewMode,
    (newMode) => {
        if (newMode === "calendar") {
            fetchCalendarData();
        } else {
            // Switching to list view - explicitly clear calendar params
            router.get(
                route("dashboard.appointments.index"),
                {
                    page: 1,
                    search: filters.value.search,
                    status: filters.value.status,
                    // Explicitly set to null/undefined to clear from URL
                    view_mode: undefined,
                    start_date: undefined,
                    end_date: undefined,
                },
                {
                    preserveState: false,
                    preserveScroll: false,
                }
            );
        }
    }
);

// Handle calendar date change (from AppointmentCalendar component)
const handleCalendarDateChange = (newDate) => {
    currentDate.value = newDate;
    fetchCalendarData();
};

// Handle calendar view mode change (from AppointmentCalendar component)
const handleCalendarViewModeChange = (newMode) => {
    calendarViewMode.value = newMode;
    fetchCalendarData();
};

// Show appointment detail modal
const showAppointmentDetail = (appointment) => {
    selectedAppointment.value = appointment;
    showAppointmentModal.value = true;
};
</script>

<template>
    <Head title="My Schedule" />

    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-2">
            <div>
                <h2
                    class="text-2xl font-bold text-slate-900 dark:text-slate-100"
                >
                    My Schedule
                </h2>
                <p class="text-slate-500 dark:text-slate-400 mt-1">
                    Manage your upcoming consultation sessions.
                </p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <StatCards :stats="stats" />

    <!-- Filters -->
    <AppointmentFilters v-model="filters" @apply-filters="applyFilters" />

    <!-- List View -->
    <AppointmentList
        v-if="filters.viewMode === 'list'"
        :appointments="appointments"
    />

    <!-- Calendar View -->
    <AppointmentCalendar
        v-else
        :appointments="appointments"
        :current-date="currentDate"
        :view-mode="calendarViewMode"
        :is-loading="isLoadingCalendar"
        :is-ready="isCalendarReady"
        @update:current-date="handleCalendarDateChange"
        @update:view-mode="handleCalendarViewModeChange"
        @appointment-click="showAppointmentDetail"
    />

    <!-- Appointment Detail Modal -->
    <AppointmentDetailModal
        :show="showAppointmentModal"
        :appointment="selectedAppointment"
        @close="showAppointmentModal = false"
    />
</template>

<style>
/* Custom Calendar Styles for Dark Theme */
:root {
    --vc-gray-50: #1e293b;
    --vc-gray-100: #334155;
    --vc-gray-200: #475569;
    --vc-gray-300: #64748b;
    --vc-gray-400: #94a3b8;
    --vc-gray-500: #cbd5e1;
    --vc-gray-600: #e2e8f0;
    --vc-gray-700: #f1f5f9;
    --vc-gray-800: #f8fafc;
    --vc-gray-900: #ffffff;
}

.custom-calendar {
    --vc-bg: transparent;
    --vc-border: transparent;
}

.custom-calendar .vc-title {
    color: #e2e8f0;
    font-weight: 600;
}

.custom-calendar .vc-weekday {
    color: #94a3b8;
    font-weight: 600;
}

.custom-calendar .vc-day {
    color: #cbd5e1;
}

.custom-calendar .vc-day-content {
    width: 100%;
    height: 100%;
    min-height: 80px;
}

.custom-calendar .day-label {
    color: #e2e8f0;
}

.custom-calendar .vc-day.is-today {
    background-color: #1e40af20;
}

.custom-calendar .vc-day.is-today .day-label {
    color: #60a5fa;
    font-weight: 700;
}

.custom-calendar .vc-arrows-container {
    display: none;
}

.custom-calendar .vc-arrow:hover {
    background-color: #334155;
}

.custom-calendar .vc-day:hover {
    background-color: #1e293b;
}

.custom-calendar .vc-header {
    display: none;
}

.custom-calendar .vc-weeks {
    margin-top: 0;
}
</style>
