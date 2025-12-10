<script setup>
import { ref, computed } from "vue";
import { Link, router, useForm } from "@inertiajs/vue3";
import {
    Briefcase,
    Clock,
    CheckCircle2,
    XCircle,
    CalendarClock,
    Medal,
    Search,
    Filter,
    AlertTriangle,
    ChevronRight,
    Calendar,
    MoreHorizontal
} from "lucide-vue-next";

const props = defineProps({
    appointments: Array,
    isExpert: Boolean,
});

// --- State Management ---
const searchQuery = ref("");
const activeFilter = ref("all");
const showActionModal = ref(false);
const showRescheduleModal = ref(false);

// State untuk Modal Konfirmasi
const actionState = ref({
    id: null,
    type: "", // 'progress', 'declined', 'completed'
    title: "",
    message: "",
});

// State untuk Modal Reschedule
const editingAppointment = ref(null);
const editForm = useForm({
    date: "",
    time: "",
});

// --- Helpers ---
const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString("en-US", {
        weekday: 'short', year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'
    });
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat("id-ID").format(amount || 0);
};

// --- Computed: Search & Filter Logic (Safe Mode) ---
const filteredAppointments = computed(() => {
    let result = props.appointments || [];

    // 1. Filter by Status Tab
    if (activeFilter.value !== 'all') {
        if (activeFilter.value === 'pending') {
            result = result.filter(a => ['need_confirmation', 'paid'].includes(a.status));
        } else if (activeFilter.value === 'upcoming') {
            result = result.filter(a => ['confirmed', 'progress'].includes(a.status));
        } else {
            result = result.filter(a => a.status === activeFilter.value);
        }
    }

    // 2. Filter by Search Query (dengan Safe Navigation ?.)
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        result = result.filter(a => {
            // Jika Expert, cari nama User. Jika Client, cari nama Expert.
            const name = props.isExpert ? a.user?.name : a.expert?.user?.name;
            return name ? name.toLowerCase().includes(query) : false;
        });
    }

    return result;
});

// --- Modal Handlers ---
const openActionModal = (id, type) => {
    actionState.value.id = id;
    actionState.value.type = type;

    if (type === 'progress') {
        actionState.value.title = "Confirm Appointment";
        actionState.value.message = "Are you sure you want to accept this session? The user will be notified.";
    } else if (type === 'declined') {
        actionState.value.title = "Decline Appointment";
        actionState.value.message = "This action cannot be undone. Funds will be refunded to the user.";
    } else if (type === 'completed') {
        actionState.value.title = "Mark as Completed";
        actionState.value.message = "Has the session finished successfully? Funds will be released to your wallet.";
    }
    showActionModal.value = true;
};

const closeActionModal = () => {
    showActionModal.value = false;
    setTimeout(() => {
        actionState.value.id = null;
    }, 200);
};

const submitAction = () => {
    if (!actionState.value.id) return;

    router.post(
        route("appointment.update_status", actionState.value.id),
        { status: actionState.value.type },
        {
            preserveScroll: true,
            onSuccess: () => closeActionModal(),
        }
    );
};

// Reschedule Logic
const openRescheduleModal = (appointment) => {
    editingAppointment.value = appointment;
    const dateObj = new Date(appointment.date_time);
    // Format tanggal agar sesuai input type="date" (YYYY-MM-DD)
    editForm.date = dateObj.toISOString().split("T")[0];
    // Format waktu agar sesuai input type="time" (HH:mm)
    editForm.time = dateObj.toTimeString().slice(0, 5);
    showRescheduleModal.value = true;
};

const closeRescheduleModal = () => {
    showRescheduleModal.value = false;
    editForm.reset();
    editingAppointment.value = null;
};

const submitReschedule = () => {
    if (!editingAppointment.value) return;
    editForm.put(route("appointment.edit_schedule", editingAppointment.value.id), {
        preserveScroll: true,
        onSuccess: () => closeRescheduleModal(),
    });
};

// --- UI Constants ---
const filters = [
    { id: 'all', label: 'All' },
    { id: 'pending', label: 'Pending Request' },
    { id: 'upcoming', label: 'Upcoming' },
    { id: 'completed', label: 'History' },
];
</script>

<template>
    <div class="min-h-[500px]">

        <div class="sticky top-0 z-10 bg-white/80 backdrop-blur-md pt-1 pb-4 mb-6 border-b border-slate-100">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">

                <div class="flex items-center gap-1 overflow-x-auto no-scrollbar py-1">
                    <button v-for="filter in filters" :key="filter.id" @click="activeFilter = filter.id"
                        class="px-4 py-2 rounded-full text-sm font-semibold transition-all whitespace-nowrap" :class="activeFilter === filter.id
                            ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/20'
                            : 'bg-white text-slate-500 hover:bg-slate-50 border border-slate-200'">
                        {{ filter.label }}
                    </button>
                </div>

                <div class="relative w-full md:w-64 shrink-0">
                    <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                    <input v-model="searchQuery" type="text" placeholder="Search name..."
                        class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all">
                </div>
            </div>
        </div>

        <div v-if="filteredAppointments.length === 0"
            class="flex flex-col items-center justify-center py-20 text-center bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200">
            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mb-4">
                <Filter class="w-8 h-8 text-slate-300" />
            </div>
            <h3 class="text-slate-900 font-bold text-lg">No appointments found</h3>
            <p class="text-slate-500 text-sm max-w-xs mx-auto mt-1">
                Try adjusting your search or filter settings.
            </p>
            <button v-if="activeFilter !== 'all' || searchQuery" @click="{ activeFilter = 'all'; searchQuery = '' }"
                class="mt-4 text-violet-600 font-bold text-sm hover:underline">
                Clear all filters
            </button>
        </div>

        <TransitionGroup name="list" tag="div" class="space-y-4">
            <div v-for="appointment in filteredAppointments" :key="appointment.id"
                class="group bg-white rounded-2xl p-5 border border-slate-200 hover:border-violet-200 hover:shadow-lg hover:shadow-slate-200/40 transition-all duration-300 relative overflow-hidden">

                <div class="absolute left-0 top-0 bottom-0 w-1.5" :class="{
                    'bg-yellow-400': ['need_confirmation', 'paid'].includes(appointment.status),
                    'bg-green-500': ['confirmed', 'progress'].includes(appointment.status),
                    'bg-blue-500': appointment.status === 'completed',
                    'bg-red-500': ['cancelled', 'declined'].includes(appointment.status)
                }">
                </div>

                <div class="pl-4 flex flex-col lg:flex-row lg:items-center justify-between gap-6">

                    <div class="flex items-start gap-4">
                        <div class="relative shrink-0">
                            <div
                                class="w-12 h-12 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-lg font-bold text-slate-600 overflow-hidden">
                                <img v-if="isExpert ? appointment.user?.picture_url : appointment.expert?.user?.picture_url"
                                    :src="isExpert ? appointment.user?.picture_url : appointment.expert?.user?.picture_url"
                                    class="w-full h-full object-cover" />
                                <span v-else>
                                    {{ isExpert ? (appointment.user?.name?.charAt(0) || 'U') :
                                        (appointment.expert?.user?.name?.charAt(0) || 'E') }}
                                </span>
                            </div>
                            <div
                                class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full border-2 border-white flex items-center justify-center bg-white">
                                <Clock v-if="['need_confirmation', 'paid'].includes(appointment.status)"
                                    class="w-3 h-3 text-yellow-500 fill-yellow-100" />
                                <CheckCircle2 v-else-if="['confirmed', 'progress'].includes(appointment.status)"
                                    class="w-3 h-3 text-green-500 fill-green-100" />
                                <Medal v-else-if="appointment.status === 'completed'"
                                    class="w-3 h-3 text-blue-500 fill-blue-100" />
                                <XCircle v-else class="w-3 h-3 text-red-500 fill-red-100" />
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="font-bold text-slate-900 group-hover:text-violet-700 transition-colors">
                                    {{ isExpert ? appointment.user?.name : appointment.expert?.user?.name }}
                                </h4>
                                <span
                                    class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-500">
                                    {{ appointment.status.replace('_', ' ') }}
                                </span>
                            </div>

                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1 text-sm text-slate-500">
                                <div class="flex items-center gap-1.5">
                                    <Calendar class="w-3.5 h-3.5" />
                                    {{ formatDate(appointment.date_time) }}
                                </div>
                                <div class="w-1 h-1 bg-slate-300 rounded-full hidden sm:block"></div>
                                <div>
                                    <span class="font-medium text-slate-700">Rp {{ formatCurrency(appointment.price)
                                    }}</span>
                                    <span class="text-xs ml-1">({{ appointment.hours }} Hours)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 mt-2 lg:mt-0">

                        <Link v-if="!isExpert" :href="route('payment.show', appointment.id)"
                            class="px-4 py-2 bg-slate-50 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-100 transition-colors border border-slate-200">
                            Details
                        </Link>

                        <div v-if="isExpert" class="flex items-center gap-2">

                            <template
                                v-if="!['paid', 'berhasil'].includes(appointment.payment_status) && appointment.status !== 'cancelled'">
                                <div class="flex items-center gap-2 px-4 py-2 rounded-xl border bg-slate-50 border-slate-200 cursor-not-allowed opacity-80"
                                    :title="`Payment Status: ${appointment.payment_status}`">
                                    <Clock v-if="appointment.payment_status === 'pending'"
                                        class="w-4 h-4 text-orange-500" />
                                    <XCircle v-else class="w-4 h-4 text-red-500" />

                                    <div class="flex flex-col items-end">
                                        <span class="text-xs font-bold text-slate-600 uppercase">
                                            {{ appointment.payment_status || 'Unpaid' }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 font-medium leading-none">
                                            Waiting for Client
                                        </span>
                                    </div>
                                </div>
                            </template>

                            <template v-else>

                                <template v-if="['need_confirmation', 'paid'].includes(appointment.status)">
                                    <button @click="openRescheduleModal(appointment)" title="Reschedule"
                                        class="p-2 text-slate-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors">
                                        <CalendarClock class="w-5 h-5" />
                                    </button>

                                    <button @click="openActionModal(appointment.id, 'declined')"
                                        class="px-4 py-2 rounded-xl text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 border border-transparent hover:border-red-200 transition-all">
                                        Decline
                                    </button>

                                    <button @click="openActionModal(appointment.id, 'progress')"
                                        class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-slate-900 hover:bg-violet-600 shadow-lg shadow-slate-900/20 hover:shadow-violet-600/30 transition-all flex items-center gap-2">
                                        Accept
                                        <ChevronRight class="w-3 h-3" />
                                    </button>
                                </template>

                                <template v-if="appointment.status === 'progress'">
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="text-xs font-medium text-green-600 bg-green-50 px-3 py-1 rounded-full animate-pulse border border-green-100">
                                            Session Active
                                        </span>
                                        <button @click="openActionModal(appointment.id, 'completed')"
                                            class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-green-600 hover:bg-green-700 shadow-lg shadow-green-600/20 transition-all flex items-center gap-2">
                                            <Medal class="w-4 h-4" /> Complete Session
                                        </button>
                                    </div>
                                </template>

                                <span v-if="['completed', 'declined', 'cancelled'].includes(appointment.status)"
                                    class="text-xs font-bold text-slate-400 px-4 py-2 bg-slate-50 rounded-xl border border-slate-100">
                                    {{ appointment.status === 'completed' ? 'Archived' : 'Closed' }}
                                </span>

                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </TransitionGroup>

        <Transition name="modal">
            <div v-if="showActionModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div @click="closeActionModal"
                    class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity">
                </div>
                <div
                    class="relative w-full max-w-sm bg-white rounded-3xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
                    <div class="p-6 text-center">
                        <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" :class="{
                            'bg-green-100 text-green-600': actionState.type === 'progress',
                            'bg-red-100 text-red-600': actionState.type === 'declined',
                            'bg-blue-100 text-blue-600': actionState.type === 'completed'
                        }">
                            <CheckCircle2 v-if="actionState.type === 'progress'" class="w-8 h-8" />
                            <XCircle v-else-if="actionState.type === 'declined'" class="w-8 h-8" />
                            <Medal v-else class="w-8 h-8" />
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">{{ actionState.title }}</h3>
                        <p class="text-slate-500 text-sm leading-relaxed mb-6">{{ actionState.message }}</p>
                        <div class="flex gap-3">
                            <button @click="closeActionModal"
                                class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200 transition-colors">Cancel</button>
                            <button @click="submitAction"
                                class="flex-1 px-4 py-3 text-white font-bold rounded-xl shadow-lg transition-transform active:scale-95"
                                :class="{
                                    'bg-green-600 hover:bg-green-700 shadow-green-200': actionState.type === 'progress',
                                    'bg-red-600 hover:bg-red-700 shadow-red-200': actionState.type === 'declined',
                                    'bg-blue-600 hover:bg-blue-700 shadow-blue-200': actionState.type === 'completed'
                                }">
                                Confirm
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>

        <Transition name="modal">
            <div v-if="showRescheduleModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div @click="closeRescheduleModal" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
                <div
                    class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
                    <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <div class="flex items-center gap-2">
                            <CalendarClock class="w-5 h-5 text-violet-600" />
                            <h3 class="font-bold text-slate-900">Reschedule Session</h3>
                        </div>
                        <button @click="closeRescheduleModal"
                            class="text-slate-400 hover:text-slate-600 transition-colors">
                            <XCircle class="w-6 h-6" />
                        </button>
                    </div>
                    <form @submit.prevent="submitReschedule" class="p-6">
                        <div class="p-4 bg-yellow-50 rounded-xl mb-6 flex gap-3 border border-yellow-100">
                            <AlertTriangle class="w-5 h-5 text-yellow-600 shrink-0" />
                            <p class="text-xs text-yellow-800 leading-relaxed">
                                Changing the schedule will notify <strong>{{ editingAppointment?.user?.name }}</strong>.
                            </p>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">New Date</label>
                                <input v-model="editForm.date" type="date" required
                                    class="w-full rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500" />
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">New Time</label>
                                <input v-model="editForm.time" type="time" required
                                    class="w-full rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500" />
                            </div>
                        </div>
                        <div class="mt-8 flex justify-end gap-3">
                            <button type="button" @click="closeRescheduleModal"
                                class="px-5 py-2.5 rounded-xl font-bold text-slate-500 hover:bg-slate-100 transition-colors">Cancel</button>
                            <button type="submit" :disabled="editForm.processing"
                                class="px-6 py-2.5 bg-violet-600 text-white font-bold rounded-xl hover:bg-violet-700 shadow-lg shadow-violet-200 transition-all disabled:opacity-70">
                                {{ editForm.processing ? 'Updating...' : 'Save Changes' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </Transition>

    </div>
</template>

<style scoped>
.list-enter-active,
.list-leave-active {
    transition: all 0.3s ease;
}

.list-enter-from,
.list-leave-to {
    opacity: 0;
    transform: translateY(20px);
}

.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.2s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}

.no-scrollbar::-webkit-scrollbar {
    display: none;
}

.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>