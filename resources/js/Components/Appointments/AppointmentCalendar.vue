<template>
    <div
        class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 overflow-hidden"
    >
        <!-- Calendar Header -->
        <div
            class="p-6 border-b border-slate-700/50 flex items-center justify-between"
        >
            <!-- Left: Month/Year with Navigation -->
            <div class="flex items-center gap-4">
                <h2 class="text-xl font-bold text-slate-200">
                    {{ currentDateDisplay }}
                </h2>
                <div class="flex items-center gap-2">
                    <button
                        @click="previousPeriod"
                        class="p-2 hover:bg-slate-700 rounded-lg transition-colors text-slate-300"
                        title="Previous"
                    >
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
                                d="M15 19l-7-7 7-7"
                            />
                        </svg>
                    </button>
                    <button
                        @click="nextPeriod"
                        class="p-2 hover:bg-slate-700 rounded-lg transition-colors text-slate-300"
                        title="Next"
                    >
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
                                d="M9 5l7 7-7 7"
                            />
                        </svg>
                    </button>
                    <button
                        @click="goToToday"
                        class="px-3 py-1.5 bg-slate-700 hover:bg-slate-600 text-slate-200 text-sm rounded-lg transition-colors font-medium"
                    >
                        Today
                    </button>
                </div>
            </div>

            <!-- Right: View Mode Tabs -->
            <div
                class="flex items-center gap-1 bg-slate-900/50 border border-slate-700 rounded-lg p-1"
            >
                <button
                    @click="setViewMode('month')"
                    :class="[
                        'px-4 py-2 rounded-md text-sm font-medium transition-colors',
                        localViewMode === 'month'
                            ? 'bg-slate-700 text-slate-200'
                            : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800',
                    ]"
                >
                    Month
                </button>
                <button
                    @click="setViewMode('week')"
                    :class="[
                        'px-4 py-2 rounded-md text-sm font-medium transition-colors',
                        localViewMode === 'week'
                            ? 'bg-slate-700 text-slate-200'
                            : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800',
                    ]"
                >
                    Week
                </button>
                <button
                    @click="setViewMode('day')"
                    :class="[
                        'px-4 py-2 rounded-md text-sm font-medium transition-colors',
                        localViewMode === 'day'
                            ? 'bg-slate-700 text-slate-200'
                            : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800',
                    ]"
                >
                    Day
                </button>
            </div>
        </div>

        <!-- Calendar Component -->
        <div class="p-6 relative">
            <!-- Loading Overlay -->
            <div
                v-if="isLoading"
                class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm z-10 flex items-center justify-center rounded-lg"
            >
                <div
                    class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"
                ></div>
            </div>

            <!-- Month View -->
            <Calendar
                v-if="localViewMode === 'month' && isReady"
                :attributes="calendarAttributes"
                :from-page="{
                    month: localCurrentDate.getMonth() + 1,
                    year: localCurrentDate.getFullYear(),
                }"
                expanded
                class="custom-calendar"
                transparent
                borderless
            >
                <template #day-content="{ day, attributes }">
                    <div class="h-full flex flex-col">
                        <span class="day-label text-sm font-medium mb-1">{{
                            day.day
                        }}</span>
                        <div class="flex-1 space-y-1">
                            <!-- Show only first appointment -->
                            <div
                                v-if="attributes && attributes.length > 0"
                                @click="
                                    handleAppointmentClick(
                                        attributes[0].customData
                                    )
                                "
                                class="text-xs p-1.5 rounded cursor-pointer hover:opacity-80 transition-opacity"
                                :class="
                                    getStatusClasses(
                                        attributes[0].customData.status
                                    )
                                "
                                :title="`${attributes[0].customData.client} - ${attributes[0].customData.expert}`"
                            >
                                <div class="font-semibold truncate">
                                    {{ attributes[0].customData.time }}
                                    {{ attributes[0].customData.client }}
                                </div>
                            </div>

                            <!-- +N more button -->
                            <button
                                v-if="attributes && attributes.length > 1"
                                @click.stop="openDayModal(day.date, attributes)"
                                class="text-xs text-cyan-400 hover:text-cyan-300 font-medium cursor-pointer transition-colors"
                            >
                                +{{ attributes.length - 1 }} more
                            </button>
                        </div>
                    </div>
                </template>
            </Calendar>

            <!-- Day Appointments Modal -->
            <Teleport to="body">
                <div
                    v-if="showDayModal && selectedDayData"
                    class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
                    @click="closeDayModal"
                >
                    <div
                        class="bg-slate-900/95 backdrop-blur-xl rounded-2xl border border-slate-700/50 max-w-2xl w-full shadow-2xl"
                        @click.stop
                    >
                        <!-- Modal Header -->
                        <div class="p-6 border-b border-slate-700/50">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="flex items-center gap-3">
                                        <h3
                                            class="text-xl font-bold text-slate-100"
                                        >
                                            Appointments for
                                            {{
                                                formatDayModalDate(
                                                    selectedDayData.date
                                                )
                                            }}
                                        </h3>
                                        <span
                                            class="px-2.5 py-1 bg-blue-500/20 text-blue-400 text-xs font-semibold rounded-full border border-blue-500/30"
                                        >
                                            {{
                                                selectedDayData.appointments
                                                    .length
                                            }}
                                            Scheduled
                                        </span>
                                    </div>
                                    <p class="text-sm text-slate-400 mt-1">
                                        Manage today's schedule for all experts.
                                    </p>
                                </div>
                                <button
                                    @click="closeDayModal"
                                    class="text-slate-400 hover:text-slate-200 transition-colors p-1"
                                >
                                    <X class="w-6 h-6" />
                                </button>
                            </div>
                        </div>

                        <!-- Modal Body - Appointments List -->
                        <div
                            class="p-6 max-h-[500px] overflow-y-auto space-y-4"
                        >
                            <div
                                v-for="(
                                    apt, index
                                ) in selectedDayData.appointments"
                                :key="apt.id || index"
                                class="bg-slate-800/50 rounded-xl border border-slate-700/50 p-4"
                            >
                                <div class="flex items-start gap-4">
                                    <!-- Time Column -->
                                    <div class="w-20 shrink-0">
                                        <div
                                            class="text-lg font-bold text-slate-100"
                                        >
                                            {{
                                                formatDateTime(apt.date_time)
                                                    .time
                                            }}
                                        </div>
                                        <div class="text-xs text-slate-500">
                                            {{
                                                apt.hours ? apt.hours * 60 : 60
                                            }}
                                            min
                                        </div>
                                    </div>

                                    <!-- Details Column -->
                                    <div class="flex-1 min-w-0">
                                        <!-- Status Badge -->
                                        <div
                                            class="flex items-center gap-2 mb-2"
                                        >
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold"
                                                :class="
                                                    getStatusClasses(apt.status)
                                                "
                                            >
                                                <span
                                                    class="w-1.5 h-1.5 rounded-full mr-1.5"
                                                    :class="
                                                        getStatusDotClass(
                                                            apt.status
                                                        )
                                                    "
                                                ></span>
                                                {{ formatStatus(apt.status) }}
                                            </span>
                                            <span class="text-slate-500 text-xs"
                                                >•
                                                {{
                                                    apt.service_type ||
                                                    "Consultation"
                                                }}</span
                                            >
                                        </div>

                                        <!-- Expert -->
                                        <div
                                            class="flex items-center gap-2 mb-1"
                                        >
                                            <div
                                                class="w-6 h-6 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold shrink-0"
                                            >
                                                {{
                                                    apt.expert_name
                                                        ? apt.expert_name
                                                              .charAt(0)
                                                              .toUpperCase()
                                                        : "E"
                                                }}
                                            </div>
                                            <span class="text-slate-400 text-sm"
                                                >Expert:</span
                                            >
                                            <span
                                                class="text-slate-200 text-sm font-medium truncate"
                                                >{{
                                                    apt.expert_name || "Unknown"
                                                }}</span
                                            >
                                        </div>

                                        <!-- Client -->
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="w-6 h-6 rounded-full bg-gradient-to-br from-cyan-500 to-teal-600 flex items-center justify-center text-white text-xs font-bold shrink-0"
                                            >
                                                {{
                                                    apt.client_name
                                                        ? apt.client_name
                                                              .charAt(0)
                                                              .toUpperCase()
                                                        : "C"
                                                }}
                                            </div>
                                            <span class="text-slate-400 text-sm"
                                                >Client:</span
                                            >
                                            <span
                                                class="text-slate-200 text-sm font-medium truncate"
                                                >{{
                                                    apt.client_name || "Unknown"
                                                }}</span
                                            >
                                        </div>
                                    </div>

                                    <!-- Action Button -->
                                    <div class="shrink-0">
                                        <button
                                            @click="viewAppointmentDetails(apt)"
                                            class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 text-sm font-medium rounded-lg transition-colors flex items-center gap-2"
                                        >
                                            {{
                                                apt.status === "progress"
                                                    ? "Join"
                                                    : "View"
                                            }}
                                            <ArrowRight class="w-4 h-4" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </Teleport>

            <!-- Month View - Loading/Empty State -->
            <div
                v-if="localViewMode === 'month' && !isReady"
                class="flex items-center justify-center py-20"
            >
                <div class="text-center">
                    <div class="animate-pulse">
                        <CalendarIcon
                            class="w-16 h-16 text-slate-600 mx-auto mb-4"
                        />
                        <p class="text-slate-400">Preparing calendar...</p>
                    </div>
                </div>
            </div>

            <!-- Week View -->
            <div
                v-if="localViewMode === 'week'"
                class="week-view overflow-x-auto"
            >
                <div class="min-w-[800px]">
                    <!-- Header Row: Days of the week -->
                    <div class="grid grid-cols-8 gap-px bg-slate-700/30 mb-px">
                        <div class="bg-slate-800/50 p-3"></div>
                        <div
                            v-for="day in weekDays"
                            :key="day.toISOString()"
                            class="bg-slate-800/50 p-3 text-center"
                            :class="
                                day.toDateString() === new Date().toDateString()
                                    ? 'bg-blue-900/20'
                                    : ''
                            "
                        >
                            <div class="text-sm font-semibold text-slate-200">
                                {{
                                    day.toLocaleDateString("en-US", {
                                        weekday: "short",
                                    })
                                }}
                            </div>
                            <div class="text-xs text-slate-400">
                                {{
                                    day.toLocaleDateString("en-US", {
                                        month: "short",
                                        day: "numeric",
                                    })
                                }}
                            </div>
                        </div>
                    </div>

                    <!-- Time Rows (8 AM - 6 PM business hours) -->
                    <div class="grid grid-cols-8 gap-px bg-slate-700/30">
                        <template
                            v-for="hour in [
                                8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18,
                            ]"
                            :key="hour"
                        >
                            <!-- Hour Label -->
                            <div
                                class="bg-slate-800/30 p-2 text-xs text-slate-400 text-right border-t border-slate-700/50"
                            >
                                {{ hour.toString().padStart(2, "0") }}:00
                            </div>

                            <!-- Day Cells -->
                            <div
                                v-for="day in weekDays"
                                :key="`${day.toISOString()}-${hour}`"
                                class="bg-slate-900/20 p-1 min-h-[60px] relative border-t border-slate-700/50"
                                :class="
                                    day.toDateString() ===
                                    new Date().toDateString()
                                        ? 'bg-blue-900/5'
                                        : ''
                                "
                            >
                                <!-- Render appointments for this hour and day -->
                                <div
                                    v-for="apt in getAppointmentsForDayHour(
                                        day,
                                        hour
                                    )"
                                    :key="apt.id"
                                    @click="handleAppointmentClick(apt)"
                                    class="text-xs p-2 rounded mb-1 cursor-pointer hover:shadow-lg transition-all border-l-2"
                                    :class="getStatusClasses(apt.status)"
                                >
                                    <div class="font-semibold truncate">
                                        {{ apt.client_name }}
                                    </div>
                                    <div class="truncate text-xs opacity-75">
                                        {{ apt.expert_name }}
                                    </div>
                                    <div class="text-xs opacity-60 mt-0.5">
                                        {{ apt.hours }}h
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Day View -->
            <div v-if="localViewMode === 'day'" class="day-view">
                <!-- Day Header -->
                <div
                    class="bg-slate-800/50 p-4 rounded-lg border border-slate-700/50 mb-4"
                >
                    <h3 class="text-lg font-bold text-slate-200">
                        {{
                            localCurrentDate.toLocaleDateString("en-US", {
                                weekday: "long",
                                month: "long",
                                day: "numeric",
                                year: "numeric",
                            })
                        }}
                    </h3>
                    <p class="text-sm text-slate-400 mt-1">
                        Detailed schedule for the selected day
                    </p>
                </div>

                <!-- Time Grid -->
                <div
                    class="overflow-y-auto max-h-[600px] border border-slate-700/50 rounded-lg"
                >
                    <div class="grid grid-cols-12 gap-px bg-slate-700/30">
                        <template
                            v-for="slot in timeSlots.filter(
                                (s) => s.hour >= 6 && s.hour <= 22
                            )"
                            :key="slot.label"
                        >
                            <!-- Time Label (2 columns) -->
                            <div
                                class="col-span-2 bg-slate-800/50 p-3 text-sm text-slate-400 text-right border-b border-slate-700/30"
                            >
                                {{ slot.label }}
                            </div>

                            <!-- Appointment Area (10 columns) -->
                            <div
                                class="col-span-10 bg-slate-900/20 p-2 min-h-[50px] border-b border-slate-700/30 relative"
                            >
                                <div
                                    v-for="apt in getAppointmentsForTimeSlot(
                                        localCurrentDate,
                                        slot.hour,
                                        slot.minute
                                    )"
                                    :key="apt.id"
                                    @click="handleAppointmentClick(apt)"
                                    class="p-3 rounded-lg mb-2 cursor-pointer hover:shadow-lg transition-all border-l-4"
                                    :class="getStatusClasses(apt.status)"
                                >
                                    <div
                                        class="flex items-start justify-between gap-3"
                                    >
                                        <div class="flex-1">
                                            <div
                                                class="font-bold text-slate-200 mb-1"
                                            >
                                                {{ apt.client_name }}
                                            </div>
                                            <div
                                                class="text-sm text-slate-400 mb-1"
                                            >
                                                Expert: {{ apt.expert_name }}
                                            </div>
                                            <div class="text-xs text-slate-500">
                                                {{
                                                    formatDateTime(
                                                        apt.date_time
                                                    ).time
                                                }}
                                                • {{ apt.hours }}h • Rp
                                                {{
                                                    apt.price?.toLocaleString()
                                                }}
                                            </div>
                                        </div>
                                        <div
                                            class="flex flex-col items-end gap-1"
                                        >
                                            <span
                                                class="text-xs px-2 py-1 rounded-full"
                                                :class="
                                                    getStatusClasses(apt.status)
                                                "
                                            >
                                                {{ apt.status }}
                                            </span>
                                            <span
                                                class="text-xs px-2 py-1 rounded-full"
                                                :class="
                                                    getPaymentStatusClasses(
                                                        apt.payment_status
                                                    )
                                                "
                                            >
                                                {{ apt.payment_status }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import { Calendar } from "v-calendar";
import { CalendarIcon, X, ArrowRight } from "lucide-vue-next";
import { formatDateTime } from "@/Utils/dateUtils";
import { getCalendarStatusColor } from "@/Utils/appointmentUtils";

const props = defineProps({
    appointments: {
        type: [Object, Array],
        required: true,
    },
    currentDate: {
        type: Date,
        default: () => new Date(),
    },
    viewMode: {
        type: String,
        default: "month",
    },
    isLoading: {
        type: Boolean,
        default: false,
    },
    isReady: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits([
    "update:currentDate",
    "update:viewMode",
    "appointment-click",
]);

// Local state for two-way binding
const localCurrentDate = ref(new Date(props.currentDate));
const localViewMode = ref(props.viewMode);

// Day Modal state
const showDayModal = ref(false);
const selectedDayData = ref(null);

// Open day modal with appointments for that day
const openDayModal = (date, attributes) => {
    selectedDayData.value = {
        date: date,
        appointments: attributes.map((attr) => attr.customData),
    };
    showDayModal.value = true;
};

// Close day modal
const closeDayModal = () => {
    showDayModal.value = false;
    selectedDayData.value = null;
};

// Format date for modal header
const formatDayModalDate = (date) => {
    if (!date) return "";
    const d = new Date(date);
    return d.toLocaleDateString("en-US", {
        month: "long",
        day: "numeric",
        year: "numeric",
    });
};

// Format status for display
const formatStatus = (status) => {
    if (!status) return "Pending";
    return status.charAt(0).toUpperCase() + status.slice(1).replace("_", " ");
};

// Get status dot class for badge
const getStatusDotClass = (status) => {
    const dots = {
        confirmed: "bg-green-400",
        completed: "bg-emerald-400",
        progress: "bg-cyan-400",
        scheduled: "bg-blue-400",
        pending: "bg-yellow-400",
        cancelled: "bg-red-400",
        declined: "bg-red-400",
    };
    return dots[status] || "bg-gray-400";
};

// View appointment details (emit to parent to show modal)
const viewAppointmentDetails = (appointment) => {
    closeDayModal();
    emit("appointment-click", appointment);
};

// Watch for prop changes from parent
watch(
    () => props.currentDate,
    (newDate) => {
        localCurrentDate.value = new Date(newDate);
    }
);

watch(
    () => props.viewMode,
    (newMode) => {
        localViewMode.value = newMode;
    }
);

//computed current date display
const currentDateDisplay = computed(() => {
    if (localViewMode.value === "month") {
        return localCurrentDate.value.toLocaleDateString("en-US", {
            month: "long",
            year: "numeric",
        });
    } else if (localViewMode.value === "week") {
        return `Week of ${localCurrentDate.value.toLocaleDateString("en-US", {
            month: "short",
            day: "numeric",
            year: "numeric",
        })}`;
    } else {
        return localCurrentDate.value.toLocaleDateString("en-US", {
            weekday: "long",
            month: "long",
            day: "numeric",
            year: "numeric",
        });
    }
});

// Calendar attributes for v-calendar
const calendarAttributes = computed(() => {
    const data = Array.isArray(props.appointments)
        ? props.appointments
        : props.appointments?.data || [];

    if (!data.length) return [];

    return data.map((appointment) => {
        const statusColor = getCalendarStatusColor(appointment.status);
        const dateTime = new Date(appointment.date_time);

        return {
            key: appointment.id,
            dot: {
                color: statusColor,
                class: "opacity-75",
            },
            dates: dateTime,
            customData: {
                ...appointment,
                client: appointment.client_name,
                company: appointment.company_name,
                expert: appointment.expert_name,
                time: formatDateTime(appointment.date_time).time,
            },
            popover: {
                label: `${appointment.client_name} - ${appointment.expert_name}`,
            },
        };
    });
});

// Helper to get status classes for calendar items
const getStatusClasses = (status) => {
    const classes = {
        confirmed: "bg-green-500/20 text-green-300 border border-green-500/30",
        completed:
            "bg-emerald-500/20 text-emerald-300 border border-emerald-500/30",
        progress: "bg-cyan-500/20 text-cyan-300 border border-cyan-500/30",
        scheduled: "bg-blue-500/20 text-blue-300 border border-blue-500/30",
        pending: "bg-yellow-500/20 text-yellow-300 border border-yellow-500/30",
        cancelled: "bg-red-500/20 text-red-300 border border-red-500/30",
        declined: "bg-red-500/20 text-red-300 border border-red-500/30",
    };
    return (
        classes[status] ||
        "bg-gray-500/20 text-gray-300 border border-gray-500/30"
    );
};

// Helper to get payment status classes
const getPaymentStatusClasses = (status) => {
    const classes = {
        paid: "bg-green-500/20 text-green-300 border border-green-500/30",
        pending: "bg-yellow-500/20 text-yellow-300 border border-yellow-500/30",
        refunded:
            "bg-purple-500/20 text-purple-300 border border-purple-500/30",
        failed: "bg-red-500/20 text-red-300 border border-red-500/30",
    };
    return (
        classes[status] ||
        "bg-gray-500/20 text-gray-300 border border-gray-500/30"
    );
};

// Week view computed properties
const weekStart = computed(() => {
    const date = new Date(localCurrentDate.value);
    const day = date.getDay();
    const diff = date.getDate() - day;
    return new Date(date.setDate(diff));
});

const weekDays = computed(() => {
    const days = [];
    for (let i = 0; i < 7; i++) {
        const day = new Date(weekStart.value);
        day.setDate(day.getDate() + i);
        days.push(day);
    }
    return days;
});

// Day view time slots (30-minute intervals)
const timeSlots = computed(() => {
    const slots = [];
    for (let hour = 0; hour < 24; hour++) {
        for (let minute = 0; minute < 60; minute += 30) {
            slots.push({
                hour,
                minute,
                label: `${hour.toString().padStart(2, "0")}:${minute
                    .toString()
                    .padStart(2, "0")}`,
            });
        }
    }
    return slots;
});

// Get appointments for specific day and hour (Week View)
const getAppointmentsForDayHour = (day, hour) => {
    const data = Array.isArray(props.appointments)
        ? props.appointments
        : props.appointments?.data || [];

    return data.filter((apt) => {
        const aptDate = new Date(apt.date_time);
        return (
            aptDate.toDateString() === day.toDateString() &&
            aptDate.getHours() === hour
        );
    });
};

// Get appointments for specific time slot (Day View)
const getAppointmentsForTimeSlot = (day, hour, minute) => {
    const data = Array.isArray(props.appointments)
        ? props.appointments
        : props.appointments?.data || [];

    return data.filter((apt) => {
        const aptDate = new Date(apt.date_time);
        return (
            aptDate.toDateString() === day.toDateString() &&
            aptDate.getHours() === hour &&
            aptDate.getMinutes() === minute
        );
    });
};

// Navigation functions
const previousPeriod = () => {
    const newDate = new Date(localCurrentDate.value);
    if (localViewMode.value === "month") {
        newDate.setMonth(newDate.getMonth() - 1);
    } else if (localViewMode.value === "week") {
        newDate.setDate(newDate.getDate() - 7);
    } else {
        newDate.setDate(newDate.getDate() - 1);
    }
    localCurrentDate.value = newDate;
    emit("update:currentDate", newDate);
};

const nextPeriod = () => {
    const newDate = new Date(localCurrentDate.value);
    if (localViewMode.value === "month") {
        newDate.setMonth(newDate.getMonth() + 1);
    } else if (localViewMode.value === "week") {
        newDate.setDate(newDate.getDate() + 7);
    } else {
        newDate.setDate(newDate.getDate() + 1);
    }
    localCurrentDate.value = newDate;
    emit("update:currentDate", newDate);
};

const goToToday = () => {
    const today = new Date();
    localCurrentDate.value = today;
    emit("update:currentDate", today);
};

// View mode switching
const setViewMode = (mode) => {
    localViewMode.value = mode;
    emit("update:viewMode", mode);
};

// Handle appointment click
const handleAppointmentClick = (appointment) => {
    emit("appointment-click", appointment);
};
</script>
