<script setup>
import { computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import {
    Users,
    Briefcase,
    CheckCircle,
    Calendar,
    ArrowUpRight,
    Layout,
    ExternalLink,
    Clock,
    ShieldCheck,
    User,
    Building2,
    ShieldAlert,
} from "lucide-vue-next";

const props = defineProps({
    user: Object,
    appointmentsCount: Number,
    isExpert: Boolean,
});

const page = usePage();
const assets = computed(() => page.props.assets);

// --- LOGIC ROLE BADGE SYSTEM (UPDATED) ---
const roleDetails = computed(() => {
    // Pastikan roles adalah array, lalu normalisasi ke lowercase agar aman
    const rawRoles = props.user.roles || [];
    const roles = Array.isArray(rawRoles)
        ? rawRoles.map((r) => r.toLowerCase())
        : [];

    // 1. Cek ADMINISTRATOR (Prioritas Tertinggi)
    if (roles.includes("administrator")) {
        return {
            label: "Administrator",
            icon: ShieldAlert,
            class: "bg-rose-500/20 text-rose-100 border border-rose-500/30 shadow-[0_0_15px_rgba(244,63,94,0.3)]",
            iconColor: "text-rose-400",
        };
    }

    // 2. Cek EXPERT (Penyedia Jasa)
    if (roles.includes("expert")) {
        return {
            label: "Professional Expert",
            icon: Briefcase,
            class: "bg-violet-500/20 text-violet-100 border border-violet-500/30 shadow-[0_0_15px_rgba(139,92,246,0.3)]",
            iconColor: "text-violet-300",
        };
    }

    // 3. Cek CLIENT (Organisasi/Pencari Pro)
    if (roles.includes("client")) {
        return {
            label: "Verified Client",
            icon: Building2,
            class: "bg-sky-500/20 text-sky-100 border border-sky-500/30 shadow-[0_0_15px_rgba(14,165,233,0.3)]",
            iconColor: "text-sky-300",
        };
    }

    // 4. Default: USER BIASA (Hanya role ['user'])
    return {
        label: "General User",
        icon: User,
        class: "bg-emerald-500/20 text-emerald-100 border border-emerald-500/30",
        iconColor: "text-emerald-400",
    };
});

// Mockup Data Statistik
const stats = [
    {
        label: "Total Appointments",
        value: props.appointmentsCount,
        icon: Briefcase,
        color: "text-violet-600",
        bg: "bg-violet-50",
        trend: "+12% this month",
    },
    {
        label: "Active Sessions",
        value: 4,
        icon: Users,
        color: "text-blue-600",
        bg: "bg-blue-50",
        trend: "Ongoing",
    },
    {
        label: "Completed",
        value: 12,
        icon: CheckCircle,
        color: "text-emerald-600",
        bg: "bg-emerald-50",
        trend: "All time",
    },
];
</script>

<template>
    <div class="space-y-8">
        <!-- name card -->
        <div
            class="relative overflow-hidden rounded-[2.5rem] bg-slate-900 text-white shadow-xl shadow-slate-200/50 group"
        >
            <div
                class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-violet-600 rounded-full mix-blend-screen filter blur-[80px] opacity-30 animate-pulse"
            ></div>
            <div
                class="absolute bottom-0 left-0 -mb-20 -ml-20 w-80 h-80 bg-fuchsia-600 rounded-full mix-blend-screen filter blur-[80px] opacity-30"
            ></div>
            <div
                class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"
            ></div>

            <div
                class="relative z-10 p-8 md:p-12 flex flex-col md:flex-row items-center md:items-start gap-8"
            >
                <div class="shrink-0 relative">
                    <div
                        class="w-28 h-28 md:w-36 md:h-36 rounded-full p-1 bg-linear-to-br from-white/20 to-white/5 backdrop-blur-sm border border-white/10 shadow-2xl"
                    >
                        <img
                            :src="user.picture_url || assets.userPlaceholderUrl"
                            alt="Profile"
                            class="w-full h-full object-cover rounded-full transition-transform duration-700 group-hover:scale-105"
                        />
                    </div>
                </div>

                <div class="flex-1 text-center md:text-left space-y-4">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full backdrop-blur-md transition-all duration-300 hover:scale-105 cursor-default select-none"
                        :class="roleDetails.class"
                    >
                        <component
                            :is="roleDetails.icon"
                            class="w-4 h-4"
                            :class="roleDetails.iconColor"
                        />
                        <span
                            class="text-xs font-bold uppercase tracking-widest"
                            >{{ roleDetails.label }}</span
                        >
                    </div>

                    <div class="space-y-1">
                        <h1
                            class="text-3xl md:text-5xl font-display font-bold leading-tight tracking-tight"
                        >
                            Hi, {{ user.name.split(" ")[0] }}!
                        </h1>
                        <p class="text-slate-400 text-lg font-light">
                            {{ user.email }}
                        </p>
                    </div>

                    <div
                        class="flex flex-wrap justify-center md:justify-start gap-6 pt-2"
                    >
                        <div
                            v-if="!user.calendar_connected"
                            class="flex items-center gap-2 text-red-300 text-sm animate-pulse"
                        >
                            <Calendar class="w-4 h-4" />
                            <a
                                :href="route('google.calendar.connect')"
                                class="underline hover:text-white"
                                >Connect Calendar Action Required</a
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid md:grid-cols-3 gap-6">
            <div
                v-for="(stat, index) in stats"
                :key="index"
                class="group bg-white p-6 rounded-4xl border border-slate-100 shadow-sm hover:shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:-translate-y-1 transition-all duration-300 relative overflow-hidden"
            >
                <div
                    class="absolute inset-0 bg-linear-to-br from-white to-slate-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                ></div>

                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-4">
                        <div
                            :class="`w-6 h-6 rounded-2xl flex items-center justify-center transition-transform duration-300 group-hover:scale-110 ${stat.bg} ${stat.color}`"
                        >
                            <component :is="stat.icon" class="w-4 h-4" />
                        </div>
                        <span
                            class="text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded-lg bg-slate-100 text-slate-500"
                        >
                            {{ stat.trend }}
                        </span>
                    </div>
                    <div>
                        <h3
                            class="text-4xl font-display font-bold text-slate-900 mb-1 tracking-tight"
                        >
                            {{ stat.value }}
                        </h3>
                        <p
                            class="text-slate-500 font-medium text-sm uppercase tracking-wide"
                        >
                            {{ stat.label }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <Link
                v-if="
                    user.roles.some((r) => r.toLowerCase() === 'client') &&
                    user.client
                "
                :href="route('home_client', user.client.slug_page)"
                class="group relative overflow-hidden bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-blue-500/10 hover:border-blue-200 transition-all duration-500"
            >
                <div
                    class="absolute top-0 right-0 p-8 opacity-20 group-hover:opacity-100 group-hover:translate-x-2 group-hover:-translate-y-2 transition-all duration-500"
                >
                    <ArrowUpRight class="w-10 h-10 text-blue-500" />
                </div>

                <div class="relative z-10">
                    <div
                        class="w-16 h-16 rounded-3xl bg-blue-50 flex items-center justify-center text-blue-600 mb-6 group-hover:rotate-6 transition-transform duration-300"
                    >
                        <Layout class="w-8 h-8" />
                    </div>
                    <h3
                        class="text-2xl font-bold text-slate-900 mb-2 group-hover:text-blue-600 transition-colors"
                    >
                        Client Dashboard
                    </h3>
                    <p
                        class="text-slate-500 text-sm mb-8 leading-relaxed max-w-xs"
                    >
                        Manage your organization page, services, and view public
                        visitor analytics.
                    </p>
                    <span
                        class="inline-flex items-center gap-2 text-sm font-bold text-slate-900 group-hover:text-blue-600 transition-colors"
                    >
                        Access Dashboard
                        <ArrowUpRight class="w-4 h-4" />
                    </span>
                </div>
            </Link>

            <Link
                v-if="
                    user.roles.some((r) => r.toLowerCase() === 'expert') &&
                    user.expert
                "
                :href="route('expert_detail', user.expert.id)"
                class="group relative overflow-hidden bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-violet-500/10 hover:border-violet-200 transition-all duration-500"
            >
                <div
                    class="absolute top-0 right-0 p-8 opacity-20 group-hover:opacity-100 group-hover:translate-x-2 group-hover:-translate-y-2 transition-all duration-500"
                >
                    <ExternalLink class="w-10 h-10 text-violet-500" />
                </div>

                <div class="relative z-10">
                    <div
                        class="w-16 h-16 rounded-3xl bg-violet-50 flex items-center justify-center text-violet-600 mb-6 group-hover:rotate-6 transition-transform duration-300"
                    >
                        <Briefcase class="w-8 h-8" />
                    </div>
                    <h3
                        class="text-2xl font-bold text-slate-900 mb-2 group-hover:text-violet-600 transition-colors"
                    >
                        Expert Profile
                    </h3>
                    <p
                        class="text-slate-500 text-sm mb-8 leading-relaxed max-w-xs"
                    >
                        Preview how your professional profile looks to potential
                        clients.
                    </p>
                    <span
                        class="inline-flex items-center gap-2 text-sm font-bold text-slate-900 group-hover:text-violet-600 transition-colors"
                    >
                        View Profile
                        <ExternalLink class="w-4 h-4" />
                    </span>
                </div>
            </Link>

            <div
                v-if="
                    !user.roles.some((r) =>
                        ['client', 'expert', 'administrator'].includes(
                            r.toLowerCase()
                        )
                    )
                "
                class="col-span-full bg-linear-to-r from-emerald-50 to-teal-50 p-8 rounded-[2.5rem] border border-emerald-100 flex items-center gap-6"
            >
                <div
                    class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm shrink-0"
                >
                    <User class="w-8 h-8 text-emerald-600" />
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900">
                        Welcome to KeyPerson!
                    </h3>
                    <p class="text-slate-600 text-sm mt-1">
                        You are currently browsing as a general user. Book an
                        appointment or register as an expert to unlock more
                        features.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
