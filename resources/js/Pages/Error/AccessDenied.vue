<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import { ShieldX, ArrowLeft, Home, Building2 } from "lucide-vue-next";
import { computed } from "vue";

const props = defineProps({
    title: { type: String, default: "Akses Ditolak" },
    message: {
        type: String,
        default: "You do not have permission to access this page.",
    },
    description: { type: String, default: null },
    clientName: { type: String, default: null },
});

const page = usePage();
const user = page.props.auth?.user;

// Cek Role User untuk menentukan dashboard route
const userRoles = computed(() => page.props.auth?.user?.roles || []);
const isAdmin = computed(() => userRoles.value.includes("administrator"));
const isClient = computed(() => userRoles.value.includes("client"));
const isExpert = computed(() => userRoles.value.includes("expert"));

// Computed route untuk Dashboard berdasarkan role
const dashboardRoute = computed(() => {
    if (isAdmin.value) return "dashboard.administrator.index";
    if (isClient.value) return "dashboard.client.index";
    if (isExpert.value) return "dashboard.expert.index";
    // Fallback untuk user biasa
    return "dashboard.user.index";
});

// Get user initials for avatar
const userInitials = computed(() => {
    if (!user?.name) return "?";
    const names = user.name.split(" ");
    if (names.length >= 2) {
        return (names[0][0] + names[1][0]).toUpperCase();
    }
    return names[0].substring(0, 2).toUpperCase();
});
</script>

<template>
    <Head :title="title" />

    <div
        class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden bg-linear-to-br from-slate-800 via-slate-700 to-slate-600"
    >
        <!-- Decorative Background Blobs -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <!-- Purple/Pink blob on right -->
            <div
                class="absolute -top-32 -right-32 w-[700px] h-[700px] rounded-full bg-linear-to-br from-pink-500/30 via-purple-500/20 to-transparent blur-3xl"
            ></div>
            <!-- Blue blob on bottom left -->
            <div
                class="absolute -bottom-48 -left-48 w-[600px] h-[600px] rounded-full bg-linear-to-tr from-blue-500/20 via-indigo-500/15 to-transparent blur-3xl"
            ></div>
        </div>

        <!-- Main Content -->
        <div class="w-full max-w-xl text-center relative z-10">
            <!-- Glassmorphism Card -->
            <div
                class="relative px-10 py-10 rounded-3xl backdrop-blur-xl border border-white/20 shadow-2xl bg-slate-600/40"
            >
                <!-- Shield Icon -->
                <div class="flex justify-center mb-6">
                    <div
                        class="w-16 h-16 rounded-2xl flex items-center justify-center bg-linear-to-br from-rose-500 to-red-500"
                    >
                        <ShieldX class="w-8 h-8 text-white" />
                    </div>
                </div>

                <!-- Title -->
                <h1 class="text-2xl font-bold text-white mb-1">
                    {{ title }}
                </h1>

                <!-- Subtitle -->
                <p class="text-slate-300 text-sm mb-4">Access Denied</p>

                <!-- Message -->
                <p class="text-slate-400 text-sm mb-8">
                    {{ message }}
                </p>

                <!-- User Info Box -->
                <div
                    v-if="user"
                    class="mb-8 px-6 py-4 rounded-2xl backdrop-blur-sm border border-white/10 bg-slate-700/50"
                >
                    <p
                        class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3"
                    >
                        Anda Login Sebagai!
                    </p>
                    <div class="flex items-center justify-center gap-3">
                        <!-- Avatar with initials -->
                        <div
                            class="w-10 h-10 rounded-full bg-slate-500 flex items-center justify-center text-white font-semibold text-sm"
                        >
                            {{ userInitials }}
                        </div>
                        <div class="text-left">
                            <p class="font-medium text-white text-sm">
                                {{ user.name }}
                            </p>
                            <p class="text-xs text-slate-400">
                                {{ user.email }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="grid grid-cols-3 gap-3">
                    <!-- Portal Perusahaan Saya -->
                    <Link
                        v-if="user?.client_id"
                        :href="
                            route('client.home', {
                                client: user.company?.slug || '',
                            })
                        "
                        class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl text-white font-medium transition-all duration-200 hover:opacity-90 bg-linear-to-br from-blue-500 to-blue-600"
                    >
                        <Building2 class="w-5 h-5" />
                        <span class="text-xs text-center leading-tight"
                            >Portal<br />Perusahaan Saya</span
                        >
                    </Link>

                    <!-- Kembali ke Dashboard -->
                    <Link
                        :href="route(dashboardRoute)"
                        class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl backdrop-blur-sm border border-white/10 text-slate-300 hover:text-white hover:bg-white/10 transition-all duration-200 bg-slate-600/30"
                    >
                        <ArrowLeft class="w-5 h-5" />
                        <span class="text-xs text-center leading-tight"
                            >Kembali ke<br />Dashboard</span
                        >
                    </Link>

                    <!-- Ke Beranda -->
                    <Link
                        :href="route('home')"
                        class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl backdrop-blur-sm border border-white/10 text-slate-300 hover:text-white hover:bg-white/10 transition-all duration-200 bg-slate-600/30"
                    >
                        <Home class="w-5 h-5" />
                        <span class="text-xs text-center leading-tight"
                            >Ke<br />Beranda</span
                        >
                    </Link>
                </div>
            </div>

            <!-- Footer Text -->
            <p class="mt-8 text-slate-400 text-sm">
                Jika Anda memerlukan bantuan, silakan hubungi
                <Link
                    :href="route('support')"
                    class="text-blue-400 hover:text-blue-300 underline"
                >
                    tim support
                </Link>
                kami.
            </p>
        </div>
    </div>
</template>
