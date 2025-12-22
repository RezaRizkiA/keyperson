<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    client: {
        type: Object,
        required: true,
    },
    showBackButton: {
        type: Boolean,
        default: false,
    },
    backRoute: {
        type: String,
        default: null,
    },
});
</script>

<template>
    <!-- Client Header: Cover + Logo (LinkedIn-style) -->
    <div class="relative">
        <!-- Cover Image with Dark Glow Gradient -->
        <div
            class="h-48 md:h-64 w-full bg-linear-to-br from-blue-900 via-slate-900 to-cyan-900 relative overflow-hidden"
        >
            <div v-if="client.cover_url" class="absolute inset-0">
                <img
                    :src="client.cover_url"
                    :alt="client.company_name + ' cover'"
                    class="w-full h-full object-cover opacity-40"
                />
            </div>
            <!-- Glow Effect Overlay -->
            <div
                class="absolute inset-0 bg-blue-500/10 backdrop-blur-[2px]"
            ></div>
            <div
                class="absolute top-0 right-0 w-96 h-96 bg-cyan-500/20 rounded-full blur-3xl"
            ></div>
            <div
                class="absolute bottom-0 left-0 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl"
            ></div>
        </div>

        <!-- Logo & Info Card -->
        <div class="max-w-6xl mx-auto px-6 -mt-16 md:-mt-20 relative z-10">
            <div
                class="bg-slate-900/80 backdrop-blur-md border border-blue-500/30 rounded-3xl p-6 md:p-8 shadow-[0_0_40px_rgba(59,130,246,0.3)]"
            >
                <div
                    class="flex flex-col md:flex-row items-start md:items-center gap-6"
                >
                    <!-- Logo -->
                    <div class="shrink-0">
                        <div
                            class="w-24 h-24 md:w-32 md:h-32 rounded-2xl bg-slate-800 border-2 border-blue-500/40 overflow-hidden shadow-lg"
                        >
                            <img
                                v-if="client.logo_url"
                                :src="client.logo_url"
                                :alt="client.company_name + ' logo'"
                                class="w-full h-full object-cover"
                            />
                            <div
                                v-else
                                class="w-full h-full flex items-center justify-center text-4xl font-bold text-blue-400"
                            >
                                {{ client.company_name?.charAt(0) }}
                            </div>
                        </div>
                    </div>

                    <!-- Company Info -->
                    <div class="flex-1">
                        <h1
                            class="text-3xl md:text-4xl font-bold text-white mb-2"
                        >
                            {{ client.company_name }}
                        </h1>
                        <p v-if="client.industry" class="text-slate-400 mb-2">
                            {{ client.industry }}
                        </p>
                        <div
                            v-if="client.website"
                            class="flex items-center gap-2 text-blue-400 text-sm"
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
                                    d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"
                                />
                            </svg>
                            <a
                                :href="client.website"
                                target="_blank"
                                class="hover:text-cyan-400 transition-colors"
                            >
                                {{ client.website.replace(/^https?:\/\//, "") }}
                            </a>
                        </div>
                    </div>

                    <!-- Back Button (if needed) -->
                    <div v-if="showBackButton && backRoute" class="shrink-0">
                        <Link
                            :href="backRoute"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-lg border border-slate-700 hover:border-blue-500/50 transition-all"
                        >
                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"
                                />
                            </svg>
                            Back
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
