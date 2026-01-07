<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import { ShieldX, ArrowLeft, Home, Building2 } from "lucide-vue-next";

const props = defineProps({
    title: { type: String, default: "Akses Ditolak" },
    message: {
        type: String,
        default: "Anda tidak memiliki akses ke halaman ini.",
    },
    description: { type: String, default: null },
    clientName: { type: String, default: null },
});

const page = usePage();
const user = page.props.auth?.user;
</script>

<template>
    <Head :title="title" />

    <div
        class="min-h-screen flex items-center justify-center bg-linear-to-br from-slate-900 via-red-900/20 to-slate-900 p-4"
    >
        <div class="w-full max-w-lg text-center">
            <!-- Decorative Background Elements -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div
                    class="absolute top-1/4 left-1/4 w-96 h-96 bg-red-500/5 rounded-full blur-3xl"
                ></div>
                <div
                    class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-orange-500/5 rounded-full blur-3xl"
                ></div>
            </div>

            <!-- Error Card -->
            <div
                class="relative p-8 md:p-10 rounded-3xl bg-white/5 backdrop-blur-xl border border-white/10 shadow-2xl"
            >
                <!-- Icon with Pulse Animation -->
                <div class="relative mb-8">
                    <div
                        class="w-20 h-20 mx-auto rounded-2xl bg-linear-to-br from-red-500/20 to-orange-500/20 flex items-center justify-center"
                    >
                        <ShieldX class="w-10 h-10 text-red-400" />
                    </div>
                    <!-- Subtle pulse ring -->
                    <div
                        class="absolute inset-0 w-20 h-20 mx-auto rounded-2xl bg-red-500/10 animate-ping opacity-75"
                        style="animation-duration: 2s"
                    ></div>
                </div>

                <!-- Title -->
                <h1 class="text-3xl font-bold text-white mb-3">
                    {{ title }}
                </h1>

                <!-- Company Name Badge (if provided) -->
                <div
                    v-if="clientName"
                    class="inline-flex items-center gap-2 px-4 py-2 mb-4 rounded-full bg-slate-800/50 border border-slate-700"
                >
                    <Building2 class="w-4 h-4 text-slate-400" />
                    <span class="text-sm font-medium text-slate-300">{{
                        clientName
                    }}</span>
                </div>

                <!-- Message -->
                <p class="text-lg text-slate-300 mb-3">
                    {{ message }}
                </p>

                <!-- Description -->
                <p
                    v-if="description"
                    class="text-slate-400 mb-8 leading-relaxed"
                >
                    {{ description }}
                </p>

                <!-- User Info (if logged in) -->
                <div
                    v-if="user"
                    class="mb-8 p-4 rounded-xl bg-slate-800/30 border border-slate-700/50"
                >
                    <p class="text-sm text-slate-400 mb-1">
                        Anda login sebagai:
                    </p>
                    <p class="font-medium text-white">{{ user.name }}</p>
                    <p class="text-sm text-slate-400">{{ user.email }}</p>
                </div>

                <!-- Action Buttons -->
                <div
                    class="flex flex-col sm:flex-row items-center justify-center gap-3"
                >
                    <Link
                        v-if="user?.client_id"
                        :href="
                            route('client.home', {
                                client: user.company?.slug || '',
                            })
                        "
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-linear-to-r from-blue-600 to-cyan-600 hover:from-blue-500 hover:to-cyan-500 text-white font-medium rounded-xl transition-all duration-200 shadow-lg shadow-blue-500/25"
                    >
                        <Building2 class="w-5 h-5" />
                        Portal Perusahaan Saya
                    </Link>

                    <Link
                        :href="route('dashboard.index')"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-white/10 hover:bg-white/20 text-white font-medium rounded-xl transition-all duration-200 border border-white/10"
                    >
                        <ArrowLeft class="w-5 h-5" />
                        Kembali ke Dashboard
                    </Link>

                    <Link
                        :href="route('home')"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-white/5 hover:bg-white/10 text-slate-300 hover:text-white font-medium rounded-xl transition-all duration-200"
                    >
                        <Home class="w-5 h-5" />
                        Ke Beranda
                    </Link>
                </div>
            </div>

            <!-- Footer Text -->
            <p class="mt-6 text-slate-500 text-sm">
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
