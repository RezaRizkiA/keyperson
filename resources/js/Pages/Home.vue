<script setup>
import { Head, usePage, Link } from "@inertiajs/vue3";
import MainLayout from "../Layouts/MainLayout.vue";
import { route } from "ziggy-js";

import {
    Calendar,
    Video,
    CheckCircle,
    BarChart3,
    Globe,
    Bell,
    CreditCard,
    ArrowRight,
    ShieldCheck,
    BadgeCheck, // <-- Tambahan Import Icon
} from "lucide-vue-next";

// Props dari Controller
defineProps({
    hero: Object,
    features: Array,
    bentoFeatures: Array,
});

const { props } = usePage();
const routes = props.routes;

// Helper icon mapping (Wajib sinkron dengan Controller)
const iconMap = {
    calendar: Calendar,
    "badge-check": BadgeCheck, // <-- Icon Baru
    "shield-check": ShieldCheck, // <-- Icon Baru
    video: Video, // <-- Icon Baru
    "chart-bar": BarChart3,
    globe: Globe,
    bell: Bell,
    "credit-card": CreditCard,
};
</script>

<template>
    <Head title="Smart Scheduling for Professionals" />

    <MainLayout>
        <div class="bg-slate-900 text-white font-sans selection:bg-blue-500/20">
            <!-- Hero Section with Glow Effect -->
            <section
                class="relative pt-32 pb-20 px-6 text-center max-w-5xl mx-auto overflow-hidden"
            >
                <!-- Animated Glow Background -->
                <div class="absolute inset-0 bg-glow-gradient-hero -z-10"></div>
                <div
                    class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[800px] bg-blue-500/10 rounded-full blur-[150px] -z-10 animate-pulse-glow"
                ></div>

                <div class="badge-glow mb-6">New: Group Booking Available</div>
                <h1
                    class="text-5xl md:text-7xl font-bold tracking-tight mb-8 leading-[1.1] text-transparent bg-clip-text bg-linear-to-r from-blue-400 to-cyan-400 py-4"
                >
                    {{ hero.title }}
                </h1>
                <p
                    class="text-xl text-slate-300 mb-10 max-w-2xl mx-auto leading-relaxed"
                >
                    {{ hero.subtitle }}
                </p>
                <div
                    class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16"
                >
                    <Link
                        :href="route('client_onboarding.create')"
                        class="btn-primary-glow"
                    >
                        Untuk Perusahaan <ArrowRight class="w-5 h-5" />
                    </Link>
                    <Link
                        :href="route('expert_onboarding.create')"
                        class="btn-secondary-glow"
                    >
                        Gabung Expert
                    </Link>
                </div>

                <div
                    class="relative mx-auto max-w-4xl rounded-3xl overflow-hidden border border-slate-700/50 bg-slate-800/30 aspect-video group glow-blue-soft backdrop-blur-sm"
                >
                    <div
                        class="absolute inset-0 flex items-center justify-center"
                    >
                        <div
                            class="w-full h-full bg-linear-to-br from-slate-800/50 to-slate-900/50 p-8"
                        >
                            <div
                                class="w-full h-full border-2 border-dashed border-blue-500/30 rounded-xl flex items-center justify-center"
                            >
                                <img
                                    :src="hero.image"
                                    alt="Dashboard Overview Mockup Area"
                                    class="opacity-80"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Features Section -->
            <section class="relative py-24 px-6 overflow-hidden bg-slate-900">
                <div
                    v-for="(feature, index) in features"
                    :key="index"
                    class="max-w-6xl mx-auto flex flex-col md:flex-row items-center gap-16 mb-32 last:mb-0"
                    :class="{ 'md:flex-row-reverse': feature.align === 'left' }"
                >
                    <div class="flex-1 space-y-6 text-center md:text-left">
                        <h2
                            class="text-3xl md:text-5xl font-bold text-white leading-tight"
                        >
                            {{ feature.title }}
                        </h2>
                        <p class="text-lg text-slate-300 leading-relaxed">
                            {{ feature.description }}
                        </p>
                        <ul
                            v-if="index === 0"
                            class="space-y-3 pt-4 inline-block text-left"
                        >
                            <li
                                class="flex items-center gap-3 text-slate-200 font-medium"
                            >
                                <CheckCircle class="w-5 h-5 text-blue-400" />
                                Instant Booking
                            </li>
                            <li
                                class="flex items-center gap-3 text-slate-200 font-medium"
                            >
                                <CheckCircle class="w-5 h-5 text-blue-400" />
                                GCalendar Sync
                            </li>
                        </ul>
                    </div>

                    <div class="flex-1 w-full relative">
                        <div
                            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] bg-linear-to-tr from-blue-500/20 to-cyan-500/20 rounded-full blur-3xl -z-10"
                        ></div>

                        <div
                            v-if="feature.image === 'calendar_ui'"
                            class="bg-slate-800 rounded-2xl shadow-xl border border-slate-700 p-6 rotate-1 hover:rotate-0 transition-transform duration-500 glow-blue-soft"
                        >
                            <div class="flex justify-between items-center mb-6">
                                <div class="font-bold text-lg text-white">
                                    September 2025
                                </div>
                                <div class="flex gap-2">
                                    <div
                                        class="w-8 h-8 rounded-full bg-slate-700"
                                    ></div>
                                    <div
                                        class="w-8 h-8 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center font-bold text-xs border border-blue-500/30"
                                    >
                                        +3
                                    </div>
                                </div>
                            </div>
                            <div
                                class="grid grid-cols-7 gap-2 text-center text-sm text-slate-500 mb-2"
                            >
                                <span>M</span><span>T</span><span>W</span
                                ><span>T</span><span>F</span><span>S</span
                                ><span>S</span>
                            </div>
                            <div class="grid grid-cols-7 gap-2">
                                <div
                                    v-for="n in 31"
                                    :key="n"
                                    class="aspect-square rounded-lg flex items-center justify-center text-sm"
                                    :class="[
                                        n === 12
                                            ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/50'
                                            : n > 12 && n < 16
                                            ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30'
                                            : 'text-slate-400 hover:bg-slate-700',
                                    ]"
                                >
                                    {{ n }}
                                </div>
                            </div>
                            <div
                                class="absolute -bottom-6 -left-6 bg-slate-700 p-4 rounded-xl shadow-lg border border-blue-500/30 flex items-center gap-3 animate-bounce glow-blue-soft"
                                style="animation-duration: 3s"
                            >
                                <div
                                    class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-400 border border-blue-500/40"
                                >
                                    <Video class="w-5 h-5" />
                                </div>
                                <div>
                                    <div
                                        class="text-xs font-bold text-slate-400"
                                    >
                                        Next Meeting
                                    </div>
                                    <div class="font-bold text-white">
                                        10:00 AM
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="feature.image === 'dashboard_ui'"
                            class="bg-slate-800 rounded-2xl shadow-xl border border-slate-700 p-6 -rotate-1 hover:rotate-0 transition-transform duration-500 glow-blue-soft"
                        >
                            <div class="flex gap-4 mb-6">
                                <div
                                    class="flex-1 bg-blue-500/10 p-4 rounded-xl border border-blue-500/20"
                                >
                                    <div
                                        class="text-xs text-blue-400 font-bold uppercase"
                                    >
                                        Total Sesi
                                    </div>
                                    <div class="text-2xl font-bold text-white">
                                        1,240
                                    </div>
                                </div>
                                <div
                                    class="flex-1 bg-cyan-500/10 p-4 rounded-xl border border-cyan-500/20"
                                >
                                    <div
                                        class="text-xs text-cyan-400 font-bold uppercase"
                                    >
                                        Kepuasan
                                    </div>
                                    <div class="text-2xl font-bold text-white">
                                        4.9/5
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div
                                    class="h-2 bg-slate-700 rounded-full w-full overflow-hidden"
                                >
                                    <div
                                        class="h-full bg-blue-500 w-3/4 shadow-[0_0_10px_rgba(59,130,246,0.5)]"
                                    ></div>
                                </div>
                                <div
                                    class="flex justify-between text-xs text-slate-400"
                                >
                                    <span>Budget Utilization</span>
                                    <span>75%</span>
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="feature.image === 'meeting_ui'"
                            class="bg-slate-900 rounded-2xl shadow-2xl p-4 rotate-1 hover:rotate-0 transition-transform duration-500 text-white"
                        >
                            <div
                                class="aspect-video bg-slate-800 rounded-xl mb-4 relative overflow-hidden"
                            >
                                <div
                                    class="absolute inset-0 flex items-center justify-center"
                                >
                                    <div
                                        class="w-20 h-20 rounded-full bg-slate-700 flex items-center justify-center"
                                    >
                                        <span class="font-bold text-2xl"
                                            >JS</span
                                        >
                                    </div>
                                </div>
                                <div
                                    class="absolute bottom-4 left-4 bg-black/50 px-3 py-1 rounded-lg text-sm backdrop-blur-sm"
                                >
                                    Expert John
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div
                                    class="flex-1 h-24 bg-slate-800 rounded-xl relative overflow-hidden"
                                >
                                    <div
                                        class="absolute bottom-2 left-2 text-xs bg-black/50 px-2 py-0.5 rounded"
                                    >
                                        You
                                    </div>
                                </div>
                                <div
                                    class="flex-1 h-24 bg-slate-800 rounded-xl flex items-center justify-center"
                                >
                                    <span class="text-slate-500 text-xs"
                                        >+2 Others</span
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Features Grid Section -->
            <section class="relative py-24 bg-slate-950 px-6">
                <!-- Subtle glow background -->
                <div
                    class="absolute inset-0 bg-glow-gradient opacity-50 -z-10"
                ></div>

                <div class="max-w-6xl mx-auto">
                    <div class="text-center max-w-2xl mx-auto mb-16">
                        <h2
                            class="text-3xl md:text-4xl font-bold text-white mb-4"
                        >
                            All the Features You Need
                        </h2>
                        <p class="text-slate-400">
                            Complete platform for streamlining your consultation
                            operations.
                        </p>
                    </div>

                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"
                    >
                        <div
                            v-for="(bento, i) in bentoFeatures"
                            :key="i"
                            class="card-feature-dark group"
                        >
                            <div
                                class="w-12 h-12 rounded-2xl flex items-center justify-center mb-6 bg-blue-500/10 border border-blue-500/20 text-blue-400"
                            >
                                <component
                                    :is="iconMap[bento.icon]"
                                    class="w-6 h-6"
                                />
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">
                                {{ bento.title }}
                            </h3>
                            <p
                                class="text-slate-400 text-sm leading-relaxed mb-4"
                            >
                                {{ bento.desc }}
                            </p>

                            <div
                                class="flex items-center gap-2 text-sm font-bold text-blue-400 opacity-0 group-hover:opacity-100 transition-opacity"
                            >
                                Learn more <ArrowRight class="w-4 h-4" />
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section
                class="relative py-32 px-6 text-center bg-slate-900 overflow-hidden"
            >
                <!-- Glow effect background -->
                <div
                    class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-blue-500/20 rounded-full blur-[120px] -z-10"
                ></div>

                <div class="max-w-4xl mx-auto">
                    <h2
                        class="text-4xl md:text-6xl font-bold text-white mb-8 tracking-tight"
                    >
                        Unlock your
                        <span
                            class="text-transparent bg-clip-text bg-linear-to-r from-blue-400 to-cyan-400"
                            >business potential.</span
                        >
                    </h2>
                    <p class="text-xl text-slate-300 mb-12">
                        One platform, zero complexity. Join 15,000+
                        professionals already growing their practice with us.
                    </p>

                    <Link
                        :href="routes.login"
                        class="inline-flex items-center gap-3 px-10 py-5 bg-blue-600 text-white rounded-full font-bold text-xl hover:bg-blue-700 hover:scale-105 transition-all shadow-[0_0_40px_rgba(59,130,246,0.4)] hover:shadow-[0_0_60px_rgba(59,130,246,0.6)]"
                    >
                        Get Started - Free
                        <ArrowRight class="w-6 h-6" />
                    </Link>
                </div>
            </section>
        </div>
    </MainLayout>
</template>
