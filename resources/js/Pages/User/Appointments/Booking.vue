<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from "vue";
import { Head, useForm, Link, usePage } from "@inertiajs/vue3";
import { DatePicker } from "v-calendar";
import "v-calendar/style.css";
import {
    User,
    Users,
    ArrowRight,
    ArrowLeft,
    Calendar as CalendarIcon,
    Clock,
    Plus,
    Minus,
    Trash2,
    CheckCircle2,
    AlertCircle,
    CreditCard,
    MessageSquare,
    Info,
} from "lucide-vue-next";

const page = usePage();
const assets = computed(() => page.props.assets);

const props = defineProps({
    expert: Object,
    bookedSlots: Object,
    backUrl: String,
    skillId: {
        type: Number,
        default: null,
    },
});

// --- STATE MANAGEMENT ---
// Step: 0 = Skill Selection (if needed), 1 = Type Selection, 2 = Schedule
const needsSkillSelection = computed(
    () => !props.skillId && props.expert.skills?.length > 1
);
const step = ref(needsSkillSelection.value ? 0 : 1);

const dateModel = ref(null);
const isDark = ref(true); // Always dark for this page

// Min date = H+1 (tomorrow)
const minDate = computed(() => {
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    tomorrow.setHours(0, 0, 0, 0);
    return tomorrow;
});

// Selected skill for display
const selectedSkill = ref(
    props.skillId
        ? props.expert.skills?.find((s) => s.id === props.skillId) || null
        : props.expert.skills?.length === 1
        ? props.expert.skills[0]
        : null
);

// Form Utama (Inertia) - uses date_time (combined) for backend validation
const form = useForm({
    expert_id: props.expert.id,
    skill_id:
        props.skillId ||
        (props.expert.skills?.length === 1 ? props.expert.skills[0].id : null),
    type: "individual",
    date_time: "", // Combined datetime string: "YYYY-MM-DD HH:mm"
    hours: 1,
    topic: "",
    guests: [],
});

// UI state for separate date/time selection
const selectedDate = ref("");
const selectedTime = ref("");

// --- PRICE CALCULATION ---
const pricePerHour = computed(() => props.expert.price || 0);

const totalParticipants = computed(() => {
    return (
        1 +
        (form.type === "group"
            ? form.guests.filter((g) => g.trim() !== "").length
            : 0)
    );
});

const totalPrice = computed(() => {
    return pricePerHour.value * form.hours * totalParticipants.value;
});

const formatCurrency = (amount) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount || 0);
};

// --- LOGIC STEP 0: SKILL SELECTION ---
const selectSkill = (skill) => {
    form.skill_id = skill.id;
    selectedSkill.value = skill;
    step.value = 1; // Go to type selection
};

// --- LOGIC STEP 1: TYPE SELECTION ---
const selectType = (type) => {
    form.type = type;
    if (type === "individual") {
        form.guests = [];
    } else {
        if (form.guests.length === 0) addGuest();
    }
    step.value = 2;
};

// --- LOGIC STEP 2: GUEST MANAGEMENT ---
const addGuest = () => {
    if (form.guests.length < 5) {
        form.guests.push("");
    }
};

const removeGuest = (index) => {
    form.guests.splice(index, 1);
};

// --- LOGIC CALENDAR & TIME SLOTS ---

// Helper to combine date + time into date_time string
const updateDateTime = () => {
    if (selectedDate.value && selectedTime.value) {
        form.date_time = `${selectedDate.value} ${selectedTime.value}:00`;
    }
};

watch(dateModel, (val) => {
    if (val) {
        const year = val.getFullYear();
        const month = String(val.getMonth() + 1).padStart(2, "0");
        const day = String(val.getDate()).padStart(2, "0");
        selectedDate.value = `${year}-${month}-${day}`;
        selectedTime.value = ""; // Reset time when date changes
        form.date_time = ""; // Clear combined datetime
    }
});

// Available Time Slots with busy check
const availableTimeSlots = computed(() => {
    const slots = [];
    if (!selectedDate.value) return slots;

    const busyTimes = props.bookedSlots[selectedDate.value] || [];

    for (let i = 9; i < 17; i++) {
        const hour = String(i).padStart(2, "0");
        const time = `${hour}:00`;
        const isAvailable = !busyTimes.includes(time);
        slots.push({ time, isAvailable });
    }
    return slots;
});

const selectTime = (slot) => {
    if (slot.isAvailable) {
        selectedTime.value = slot.time;
        updateDateTime();
    }
};

const updateHours = (delta) => {
    const newVal = form.hours + delta;
    if (newVal >= 1 && newVal <= 8) {
        form.hours = newVal;
    }
};

// --- SUBMIT ---
const submit = () => {
    form.post(route("booking.store"), {
        preserveScroll: true,
        onError: (errors) => {
            console.error("Form errors:", errors);
        },
    });
};

const currentYear = new Date().getFullYear();
</script>

<template>
    <Head :title="`Book ${expert.user?.name}`" />

    <div
        class="min-h-screen bg-page-gradient text-foreground font-sans flex flex-col"
    >
        <!-- Navbar -->
        <nav class="w-full px-6 py-4 flex items-center justify-between">
            <Link :href="route('home')" class="flex items-center gap-2 group">
                <img
                    :src="assets?.logoSmallUrl"
                    class="h-9 w-auto transition-transform group-hover:scale-105"
                    alt="Logo"
                />
                <span
                    class="font-display font-bold text-xl tracking-tight text-white"
                >
                    Key<span class="text-blue-400">Person</span>
                </span>
            </Link>

            <Link
                :href="route('dashboard.index')"
                class="px-5 py-2.5 rounded-lg bg-slate-800/50 border border-slate-700 text-sm font-medium text-white hover:bg-slate-700/50 hover:border-blue-500/50 transition-all"
            >
                Dashboard
            </Link>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 px-6 py-8">
            <div class="max-w-6xl mx-auto">
                <!-- Back Link (Step 0 and 1 only) -->
                <div v-if="step === 0 || step === 1" class="mb-8">
                    <Link
                        :href="backUrl || route('experts.show', expert.slug)"
                        class="inline-flex items-center text-sm font-bold text-slate-400 hover:text-violet-400 transition-colors"
                    >
                        <ArrowLeft class="w-4 h-4 mr-2" /> Back to Expert
                    </Link>
                </div>

                <!-- Step 0: Skill Selection (when skill_id not provided) -->
                <div v-if="step === 0" class="text-center">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">
                        <span class="italic">Select a</span> skill
                    </h1>
                    <p class="text-slate-400 text-lg mb-12 max-w-xl mx-auto">
                        Choose the expertise area you want to consult with
                        <span class="text-violet-400 font-bold">{{
                            expert.user?.name
                        }}</span>
                    </p>

                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-4xl mx-auto"
                    >
                        <button
                            v-for="skill in expert.skills"
                            :key="skill.id"
                            @click="selectSkill(skill)"
                            class="group relative rounded-2xl overflow-hidden bg-slate-900/30 border border-slate-700/50 hover:border-cyan-500/50 transition-all duration-300 text-left p-6"
                        >
                            <div
                                class="w-12 h-12 rounded-xl bg-cyan-600/20 border border-cyan-500/30 flex items-center justify-center mb-4"
                            >
                                <span class="text-2xl">🎯</span>
                            </div>
                            <h3
                                class="text-xl font-bold text-white mb-2 group-hover:text-cyan-400 transition-colors"
                            >
                                {{ skill.name }}
                            </h3>
                            <span
                                class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 group-hover:text-cyan-400 transition-colors"
                            >
                                Select <ArrowRight class="w-4 h-4" />
                            </span>
                        </button>
                    </div>
                </div>

                <!-- Step 1: Type Selection -->
                <div v-if="step === 1" class="text-center">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">
                        <span class="italic">Select session</span> type
                    </h1>
                    <p class="text-slate-400 text-lg mb-4 max-w-xl mx-auto">
                        Book with
                        <span class="text-violet-400 font-bold">{{
                            expert.user?.name
                        }}</span>
                        · {{ formatCurrency(pricePerHour) }}/hour
                    </p>
                    <!-- Show selected skill badge -->
                    <div v-if="selectedSkill" class="mb-12">
                        <span
                            class="inline-flex items-center gap-2 px-4 py-2 bg-cyan-500/10 border border-cyan-500/30 rounded-full text-sm text-cyan-400"
                        >
                            🎯 {{ selectedSkill.name }}
                            <button
                                v-if="needsSkillSelection"
                                @click="step = 0"
                                class="ml-1 hover:text-white"
                            >
                                ✕
                            </button>
                        </span>
                    </div>

                    <div
                        class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-3xl mx-auto"
                    >
                        <!-- Personal Session Card -->
                        <button
                            @click="selectType('individual')"
                            class="group relative rounded-2xl overflow-hidden bg-slate-900/30 border border-slate-700/50 hover:border-violet-500/50 transition-all duration-300 text-left p-8"
                        >
                            <div
                                class="w-14 h-14 rounded-xl bg-violet-600/20 border border-violet-500/30 flex items-center justify-center mb-6"
                            >
                                <User class="w-7 h-7 text-violet-400" />
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-3">
                                Personal Session
                            </h3>
                            <p
                                class="text-slate-400 text-sm leading-relaxed mb-6"
                            >
                                One-on-one consultation for personal growth,
                                career advice, or skill development.
                            </p>
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-bold text-violet-400"
                                    >{{
                                        formatCurrency(pricePerHour)
                                    }}/jam</span
                                >
                                <span
                                    class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 group-hover:text-violet-400 transition-colors"
                                >
                                    Select
                                    <ArrowRight class="w-4 h-4" />
                                </span>
                            </div>
                        </button>

                        <!-- Team Session Card -->
                        <button
                            @click="selectType('group')"
                            class="group relative rounded-2xl overflow-hidden bg-slate-900/30 border border-slate-700/50 hover:border-blue-500/50 transition-all duration-300 text-left p-8"
                        >
                            <div
                                class="w-14 h-14 rounded-xl bg-blue-600/20 border border-blue-500/30 flex items-center justify-center mb-6"
                            >
                                <Users class="w-7 h-7 text-blue-400" />
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-3">
                                Team Session
                            </h3>
                            <p
                                class="text-slate-400 text-sm leading-relaxed mb-6"
                            >
                                Invite up to 5 colleagues for team training,
                                workshops, or group consultation.
                            </p>
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-bold text-blue-400"
                                    >{{
                                        formatCurrency(pricePerHour)
                                    }}/jam/orang</span
                                >
                                <span
                                    class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 group-hover:text-blue-400 transition-colors"
                                >
                                    Select
                                    <ArrowRight class="w-4 h-4" />
                                </span>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Schedule Selection -->
                <div
                    v-if="step === 2"
                    class="grid lg:grid-cols-12 gap-8 items-start"
                >
                    <div class="lg:col-span-7">
                        <div
                            class="bg-slate-900/50 backdrop-blur-sm rounded-[2rem] p-6 md:p-8 border border-slate-700/50"
                        >
                            <!-- Header with back button -->
                            <div class="flex items-center gap-4 mb-8">
                                <button
                                    @click="step = 1"
                                    class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:text-white transition-colors"
                                >
                                    <ArrowLeft class="w-5 h-5" />
                                </button>
                                <h2
                                    class="font-display text-2xl font-bold text-white flex items-center gap-3"
                                >
                                    <div
                                        class="w-10 h-10 rounded-full bg-violet-500/20 flex items-center justify-center text-violet-400"
                                    >
                                        <CalendarIcon class="w-5 h-5" />
                                    </div>
                                    Select Schedule
                                </h2>
                            </div>

                            <form
                                @submit.prevent="submit"
                                id="appointmentForm"
                                class="space-y-8"
                            >
                                <div class="grid md:grid-cols-2 gap-8">
                                    <!-- Date Picker -->
                                    <div class="space-y-2">
                                        <label
                                            class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1"
                                            >1. Pick a Date</label
                                        >
                                        <div
                                            class="border border-slate-700 rounded-2xl overflow-hidden p-4 bg-slate-800/50 flex justify-center"
                                        >
                                            <DatePicker
                                                v-model="dateModel"
                                                mode="date"
                                                :min-date="minDate"
                                                color="purple"
                                                borderless
                                                transparent
                                                :is-dark="isDark"
                                                class="font-sans w-full"
                                            />
                                        </div>
                                        <p
                                            v-if="form.errors.date_time"
                                            class="text-red-400 text-xs mt-1 ml-1"
                                        >
                                            {{ form.errors.date_time }}
                                        </p>
                                    </div>

                                    <!-- Time Slots -->
                                    <div class="space-y-2">
                                        <label
                                            class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1"
                                            >2. Pick a Time</label
                                        >

                                        <div
                                            v-if="selectedDate"
                                            class="grid grid-cols-2 gap-3 max-h-[320px] overflow-y-auto pr-2 custom-scrollbar"
                                        >
                                            <button
                                                v-for="slot in availableTimeSlots"
                                                :key="slot.time"
                                                type="button"
                                                @click="selectTime(slot)"
                                                :disabled="!slot.isAvailable"
                                                :class="[
                                                    'py-3 px-4 rounded-xl text-sm font-bold border transition-all duration-200 flex items-center justify-between group',
                                                    !slot.isAvailable
                                                        ? 'bg-slate-800 border-transparent text-slate-600 cursor-not-allowed line-through opacity-60'
                                                        : selectedTime ===
                                                          slot.time
                                                        ? 'bg-violet-600 border-violet-600 text-white shadow-lg shadow-violet-500/30'
                                                        : 'bg-slate-800 border-slate-700 text-slate-300 hover:border-violet-500/50 hover:bg-slate-700',
                                                ]"
                                            >
                                                <span>{{ slot.time }}</span>
                                                <span
                                                    v-if="!slot.isAvailable"
                                                    class="text-[10px] uppercase font-normal"
                                                    >Booked</span
                                                >
                                                <CheckCircle2
                                                    v-else-if="
                                                        selectedTime ===
                                                        slot.time
                                                    "
                                                    class="w-4 h-4"
                                                />
                                            </button>
                                        </div>

                                        <div
                                            v-else
                                            class="h-[320px] flex items-center justify-center border border-dashed border-slate-700 rounded-2xl"
                                        >
                                            <p class="text-slate-500 text-sm">
                                                Select a date first
                                            </p>
                                        </div>

                                        <p
                                            v-if="
                                                form.errors.date_time &&
                                                !selectedDate
                                            "
                                            class="text-red-400 text-xs mt-1 ml-1"
                                        >
                                            Please select a time
                                        </p>
                                    </div>
                                </div>

                                <!-- Topic -->
                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1"
                                        >3. Topic Discussion</label
                                    >
                                    <div class="relative">
                                        <MessageSquare
                                            class="absolute top-4 left-4 w-5 h-5 text-slate-500"
                                        />
                                        <textarea
                                            v-model="form.topic"
                                            rows="3"
                                            required
                                            placeholder="Briefly describe what you want to discuss..."
                                            class="block w-full pl-12 py-3.5 bg-slate-800 border border-slate-700 rounded-2xl text-white placeholder-slate-500 focus:bg-slate-700 focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all text-sm resize-none"
                                        ></textarea>
                                    </div>
                                    <p
                                        v-if="form.errors.topic"
                                        class="text-red-400 text-xs mt-1 ml-1"
                                    >
                                        {{ form.errors.topic }}
                                    </p>
                                </div>

                                <!-- Guest Management (Group Only) -->
                                <div
                                    v-if="form.type === 'group'"
                                    class="space-y-4 pt-4 border-t border-slate-700"
                                >
                                    <div
                                        class="flex justify-between items-center"
                                    >
                                        <label
                                            class="text-xs font-bold text-slate-400 uppercase tracking-wider"
                                            >4. Invite Colleagues</label
                                        >
                                        <span class="text-xs text-slate-500"
                                            >{{ form.guests.length }}/5
                                            Guests</span
                                        >
                                    </div>

                                    <div
                                        v-for="(guest, index) in form.guests"
                                        :key="index"
                                        class="flex gap-2"
                                    >
                                        <input
                                            type="email"
                                            v-model="form.guests[index]"
                                            placeholder="colleague@company.com"
                                            class="flex-1 rounded-xl border-slate-700 bg-slate-800 text-white focus:ring-blue-500 focus:border-blue-500 text-sm"
                                            required
                                        />
                                        <button
                                            type="button"
                                            @click="removeGuest(index)"
                                            class="p-2.5 text-red-400 hover:bg-red-500/10 rounded-xl transition-colors"
                                        >
                                            <Trash2 class="w-5 h-5" />
                                        </button>
                                    </div>

                                    <button
                                        v-if="form.guests.length < 5"
                                        type="button"
                                        @click="addGuest"
                                        class="text-sm font-bold text-blue-400 flex items-center gap-2 hover:bg-blue-500/10 px-3 py-2 rounded-lg transition-colors"
                                    >
                                        <Plus class="w-4 h-4" /> Add Another
                                        Guest
                                    </button>

                                    <div
                                        class="flex gap-2 p-3 bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs rounded-xl"
                                    >
                                        <AlertCircle class="w-4 h-4 shrink-0" />
                                        <p>
                                            Guests will receive email invitation
                                            and Google Calendar event.
                                        </p>
                                    </div>
                                </div>

                                <!-- Info Note -->
                                <div
                                    class="p-4 bg-slate-800/50 border border-slate-700 rounded-2xl flex gap-3 text-slate-400 text-xs leading-relaxed items-start"
                                >
                                    <AlertCircle
                                        class="w-5 h-5 shrink-0 mt-0.5"
                                    />
                                    <span>
                                        <strong class="text-slate-300"
                                            >Note:</strong
                                        >
                                        Times are displayed in WIB (UTC+7).
                                        Appointments can only be made H+1
                                        (tomorrow or later).
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Booking Summary Sidebar -->
                    <div class="lg:col-span-5 sticky top-28">
                        <div
                            class="bg-slate-900/50 backdrop-blur-sm rounded-[2rem] p-8 border border-slate-700/50"
                        >
                            <h3
                                class="font-display text-lg font-bold text-white mb-6"
                            >
                                Booking Summary
                            </h3>

                            <!-- Expert Info -->
                            <div
                                class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-700"
                            >
                                <div
                                    class="w-14 h-14 rounded-2xl bg-slate-800 border border-slate-700 flex items-center justify-center overflow-hidden"
                                >
                                    <img
                                        v-if="expert.user?.picture"
                                        :src="expert.user.picture"
                                        class="w-full h-full object-cover"
                                    />
                                    <span
                                        v-else
                                        class="text-2xl font-bold text-violet-400"
                                    >
                                        {{ expert.user?.name?.charAt(0) }}
                                    </span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-white">
                                        {{ expert.user?.name }}
                                    </h4>
                                    <p
                                        class="text-xs font-bold text-violet-400 bg-violet-500/10 px-2 py-0.5 rounded inline-block mt-1"
                                    >
                                        {{ expert.title }}
                                    </p>
                                </div>
                            </div>

                            <!-- Session Type Badge -->
                            <div
                                class="flex items-center gap-3 p-3 bg-slate-800/50 rounded-xl border border-slate-700 mb-4"
                            >
                                <div
                                    class="w-8 h-8 rounded-full flex items-center justify-center"
                                    :class="
                                        form.type === 'individual'
                                            ? 'bg-violet-500/20 text-violet-400'
                                            : 'bg-blue-500/20 text-blue-400'
                                    "
                                >
                                    <User
                                        v-if="form.type === 'individual'"
                                        class="w-4 h-4"
                                    />
                                    <Users v-else class="w-4 h-4" />
                                </div>
                                <div>
                                    <p
                                        class="text-xs text-slate-500 uppercase font-bold"
                                    >
                                        Session
                                    </p>
                                    <p
                                        class="text-sm font-bold text-white capitalize"
                                    >
                                        {{ form.type }}
                                    </p>
                                </div>
                            </div>

                            <!-- Date & Time Summary -->
                            <div class="space-y-3 mb-6">
                                <div
                                    class="flex items-center justify-between p-3 bg-slate-800/50 rounded-xl border border-slate-700"
                                >
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center text-slate-400"
                                        >
                                            <CalendarIcon class="w-4 h-4" />
                                        </div>
                                        <div class="text-sm">
                                            <p
                                                class="text-xs text-slate-500 font-bold uppercase"
                                            >
                                                Date
                                            </p>
                                            <p class="font-bold text-white">
                                                {{
                                                    selectedDate
                                                        ? new Date(
                                                              selectedDate
                                                          ).toLocaleDateString(
                                                              "id-ID",
                                                              {
                                                                  weekday:
                                                                      "long",
                                                                  day: "numeric",
                                                                  month: "long",
                                                              }
                                                          )
                                                        : "-"
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="flex items-center justify-between p-3 bg-slate-800/50 rounded-xl border border-slate-700"
                                >
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center text-slate-400"
                                        >
                                            <Clock class="w-4 h-4" />
                                        </div>
                                        <div class="text-sm">
                                            <p
                                                class="text-xs text-slate-500 font-bold uppercase"
                                            >
                                                Time
                                            </p>
                                            <p class="font-bold text-white">
                                                {{ selectedTime || "-" }} WIB
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Duration Control -->
                            <div class="flex items-center justify-between mb-6">
                                <span class="text-sm font-medium text-slate-400"
                                    >Duration</span
                                >
                                <div
                                    class="flex items-center bg-slate-800 rounded-xl border border-slate-700 p-1"
                                >
                                    <button
                                        @click="updateHours(-1)"
                                        :disabled="form.hours <= 1"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg bg-slate-700 disabled:opacity-50 hover:text-violet-400 text-slate-300 transition-colors"
                                    >
                                        <Minus class="w-4 h-4" />
                                    </button>
                                    <span
                                        class="w-10 text-center font-bold text-white text-sm"
                                        >{{ form.hours }}h</span
                                    >
                                    <button
                                        @click="updateHours(1)"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg bg-slate-700 hover:text-violet-400 text-slate-300 transition-colors"
                                    >
                                        <Plus class="w-4 h-4" />
                                    </button>
                                </div>
                            </div>

                            <!-- Participants (Group) -->
                            <div
                                v-if="form.type === 'group'"
                                class="flex items-center justify-between mb-6"
                            >
                                <span class="text-sm font-medium text-slate-400"
                                    >Participants</span
                                >
                                <span class="font-bold text-white"
                                    >{{ totalParticipants }} orang</span
                                >
                            </div>

                            <!-- Total Price -->
                            <div
                                class="flex justify-between items-center mb-8 pt-6 border-t border-dashed border-slate-700"
                            >
                                <span class="text-base font-bold text-white"
                                    >Total Payment</span
                                >
                                <span
                                    class="text-2xl font-display font-bold text-violet-400"
                                    >{{ formatCurrency(totalPrice) }}</span
                                >
                            </div>

                            <!-- Submit Button -->
                            <button
                                type="submit"
                                form="appointmentForm"
                                :disabled="form.processing || !form.date_time"
                                class="w-full py-4 bg-violet-600 text-white rounded-xl font-bold text-base hover:bg-violet-500 transition-all shadow-lg shadow-violet-500/20 flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed"
                            >
                                <span
                                    v-if="form.processing"
                                    class="animate-spin rounded-full h-4 w-4 border-2 border-white border-t-transparent"
                                ></span>
                                <CreditCard v-else class="w-5 h-5" />
                                {{
                                    form.processing
                                        ? "Processing..."
                                        : "Proceed to Payment"
                                }}
                            </button>

                            <!-- Cancel Booking Link -->
                            <Link
                                :href="
                                    backUrl ||
                                    route('experts.show', expert.slug)
                                "
                                class="w-full mt-3 py-3 text-center text-sm font-medium text-slate-400 hover:text-red-400 transition-colors block"
                            >
                                Cancel Booking
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="w-full px-6 py-6 border-t border-slate-800/50">
            <div
                class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4"
            >
                <p class="text-sm text-slate-500">
                    © {{ currentYear }} KeyPerson Inc. All rights reserved.
                </p>
                <div class="flex items-center gap-6 text-sm text-slate-500">
                    <Link
                        :href="route('support')"
                        class="hover:text-white transition-colors"
                        >Help Center</Link
                    >
                    <Link
                        :href="route('privacy')"
                        class="hover:text-white transition-colors"
                        >Privacy Policy</Link
                    >
                    <Link
                        :href="route('terms')"
                        class="hover:text-white transition-colors"
                        >Terms</Link
                    >
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(148, 163, 184, 0.3);
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(148, 163, 184, 0.5);
}
</style>
