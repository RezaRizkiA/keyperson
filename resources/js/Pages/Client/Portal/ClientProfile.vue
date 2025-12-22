<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { ref, computed } from "vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import {
    ChevronLeft,
    Share2,
    MoreVertical,
    MapPin,
    Globe,
    CheckCircle,
} from "lucide-vue-next";

const props = defineProps({
    client: {
        type: Object,
        required: true,
    },
});

const activeTab = "overview";

// Load more functionality
const skillsToShow = ref(3);
const displayedSkills = computed(() => {
    return props.client.skills?.slice(0, skillsToShow.value) || [];
});
const hasMoreSkills = computed(() => {
    return (
        props.client.skills && skillsToShow.value < props.client.skills.length
    );
});
const loadMore = () => {
    skillsToShow.value += 3;
};
</script>

<template>
    <Head :title="`${client.company_name} - Client Portal`" />

    <MainLayout>
        <div class="min-h-screen bg-slate-950 text-white pt-20">
            <!-- Back Navigation -->
            <div class="max-w-7xl mx-auto px-6 py-4 mt-5">
                <Link
                    :href="route('dashboard')"
                    class="inline-flex items-center gap-2 text-slate-400 hover:text-white transition-colors"
                >
                    <ChevronLeft class="w-5 h-5" />
                    <span>Back to Dashboard</span>
                </Link>
            </div>

            <!-- Cover Image Section -->
            <div class="max-w-7xl mx-auto px-6">
                <div class="relative rounded-3xl overflow-hidden">
                    <!-- Cover Image with Glow Effect -->
                    <div
                        class="h-64 md:h-80 relative bg-linear-to-br from-blue-900 via-slate-900 to-cyan-900 overflow-hidden"
                    >
                        <div v-if="client.cover_url" class="absolute inset-0">
                            <img
                                :src="client.cover_url"
                                :alt="client.company_name"
                                class="w-full h-full object-cover opacity-30"
                            />
                        </div>

                        <!-- Animated Glow Lines -->
                        <div class="absolute inset-0 opacity-40">
                            <div
                                class="absolute top-0 left-0 w-full h-full bg-[linear-gradient(45deg,transparent_25%,rgba(59,130,246,0.1)_50%,transparent_75%)] animate-pulse-glow"
                            ></div>
                        </div>

                        <!-- Glow Overlays -->
                        <div
                            class="absolute top-10 right-10 w-96 h-96 bg-cyan-500/20 rounded-full blur-3xl"
                        ></div>
                        <div
                            class="absolute bottom-10 left-10 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl"
                        ></div>

                        <!-- Action Buttons -->
                        <div
                            class="absolute top-4 right-4 flex items-center gap-2"
                        >
                            <button
                                class="w-10 h-10 bg-slate-900/50 backdrop-blur-sm border border-slate-700 hover:border-blue-500/50 rounded-lg flex items-center justify-center transition-all"
                            >
                                <Share2 class="w-5 h-5 text-slate-300" />
                            </button>
                            <button
                                class="w-10 h-10 bg-slate-900/50 backdrop-blur-sm border border-slate-700 hover:border-blue-500/50 rounded-lg flex items-center justify-center transition-all"
                            >
                                <MoreVertical class="w-5 h-5 text-slate-300" />
                            </button>
                        </div>
                    </div>

                    <!-- Company Info Card -->
                    <div
                        class="bg-slate-900/95 backdrop-blur-md border-t border-slate-800 p-6"
                    >
                        <div
                            class="flex flex-col md:flex-row items-start md:items-center gap-6"
                        >
                            <!-- Logo -->
                            <div class="shrink-0 -mt-20 md:-mt-24">
                                <div
                                    class="w-32 h-32 md:w-40 md:h-40 rounded-2xl bg-slate-800 border-4 border-slate-900 overflow-hidden shadow-xl"
                                >
                                    <img
                                        v-if="client.logo_url"
                                        :src="client.logo_url"
                                        :alt="client.company_name"
                                        class="w-full h-full object-cover"
                                    />
                                    <div
                                        v-else
                                        class="w-full h-full flex items-center justify-center text-5xl font-bold text-blue-400 bg-slate-800"
                                    >
                                        {{ client.company_name?.charAt(0) }}
                                    </div>
                                </div>
                            </div>

                            <!-- Company Details -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start gap-2 mb-2">
                                    <h1
                                        class="text-3xl md:text-4xl font-bold text-white"
                                    >
                                        {{ client.company_name }}
                                    </h1>
                                    <CheckCircle
                                        v-if="client.is_verified"
                                        class="w-6 h-6 text-blue-500 shrink-0"
                                    />
                                </div>

                                <div
                                    class="flex flex-wrap items-center gap-4 text-sm text-slate-400 mb-4"
                                >
                                    <div
                                        v-if="client.industry"
                                        class="flex items-center gap-1.5"
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
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                            />
                                        </svg>
                                        <span>{{ client.industry }}</span>
                                    </div>
                                    <div
                                        v-if="client.address"
                                        class="flex items-center gap-1.5"
                                    >
                                        <MapPin class="w-4 h-4" />
                                        <span>{{ client.address }}</span>
                                    </div>
                                    <div
                                        v-if="client.website"
                                        class="flex items-center gap-1.5"
                                    >
                                        <Globe class="w-4 h-4" />
                                        <a
                                            :href="client.website"
                                            target="_blank"
                                            class="text-blue-400 hover:text-cyan-400 transition-colors"
                                        >
                                            {{
                                                client.website.replace(
                                                    /^https?:\/\//,
                                                    ""
                                                )
                                            }}
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center gap-3 shrink-0">
                                <button
                                    class="px-6 py-2.5 bg-slate-800 hover:bg-slate-700 text-white border border-slate-700 hover:border-blue-500/50 rounded-lg font-medium transition-all flex items-center gap-2"
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
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                        />
                                    </svg>
                                    Contact
                                </button>
                                <button
                                    class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-all flex items-center gap-2"
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
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                        />
                                    </svg>
                                    Edit Profile
                                </button>
                            </div>
                        </div>

                        <!-- Tabs -->
                        <div
                            class="flex items-center gap-8 mt-6 border-t border-slate-800 pt-4"
                        >
                            <button
                                :class="[
                                    'pb-2 font-medium transition-all relative',
                                    activeTab === 'overview'
                                        ? 'text-blue-400'
                                        : 'text-slate-400 hover:text-white',
                                ]"
                            >
                                Overview
                                <div
                                    v-if="activeTab === 'overview'"
                                    class="absolute bottom-0 left-0 right-0 h-0.5 bg-blue-400"
                                ></div>
                            </button>
                            <button
                                class="pb-2 font-medium text-slate-400 hover:text-white transition-all"
                            >
                                Key People
                            </button>
                            <button
                                class="pb-2 font-medium text-slate-400 hover:text-white transition-all"
                            >
                                Documents
                            </button>
                            <button
                                class="pb-2 font-medium text-slate-400 hover:text-white transition-all"
                            >
                                Activity Log
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="max-w-7xl mx-auto px-6 py-12">
                <!-- About Section -->
                <section v-if="client.about" class="mb-12">
                    <h2
                        class="text-sm font-bold text-blue-400 uppercase tracking-wider mb-4"
                    >
                        ABOUT
                    </h2>
                    <div
                        class="bg-slate-900/50 backdrop-blur-sm border border-blue-500/20 rounded-2xl p-8"
                    >
                        <p
                            class="text-slate-300 leading-relaxed whitespace-pre-wrap"
                        >
                            {{ client.about }}
                        </p>
                    </div>
                </section>

                <!-- Skills Section -->
                <section v-if="client.skills && client.skills.length > 0">
                    <div class="mb-8">
                        <h2
                            class="text-sm font-bold text-blue-400 uppercase tracking-wider mb-2"
                        >
                            DISCOVER EXPERTISE
                        </h2>
                        <h3
                            class="text-3xl md:text-4xl font-bold text-white mb-4"
                        >
                            Browse Skills
                        </h3>
                        <p class="text-slate-400 max-w-2xl">
                            Find the right experts for your project stack.
                            Explore our curated list of technical and creative
                            domains.
                        </p>
                    </div>

                    <!-- Skills Grid -->
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8"
                    >
                        <!-- Skill Cards -->
                        <Link
                            v-for="skill in displayedSkills"
                            :key="skill.id"
                            :href="
                                route('client.experts', [
                                    client.slug,
                                    skill.name,
                                ])
                            "
                            class="block bg-slate-900/50 backdrop-blur-sm border border-blue-500/20 rounded-2xl p-6 hover:border-blue-500/60 hover:shadow-[0_0_30px_rgba(59,130,246,0.3)] transition-all duration-300 group"
                        >
                            <!-- Category Path -->
                            <div
                                class="flex items-center gap-2 text-xs text-slate-500 uppercase tracking-wider mb-4"
                            >
                                <svg
                                    class="w-3 h-3"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"
                                    />
                                </svg>
                                <span v-if="skill.sub_category">
                                    {{ skill.sub_category.category?.name }} /
                                    {{ skill.sub_category.name }}
                                </span>
                            </div>

                            <!-- Skill Name -->
                            <h4
                                class="text-xl font-bold text-white mb-12 group-hover:text-blue-400 transition-colors"
                            >
                                {{ skill.name }}
                            </h4>

                            <!-- Footer -->
                            <div class="flex items-center justify-between">
                                <div
                                    class="flex items-center gap-2 text-slate-400 text-sm"
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
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                        />
                                    </svg>
                                    <span>Experts available</span>
                                </div>
                                <div
                                    class="w-8 h-8 bg-slate-800 group-hover:bg-blue-600 rounded-full flex items-center justify-center transition-all"
                                >
                                    <svg
                                        class="w-4 h-4 text-slate-400 group-hover:text-white group-hover:translate-x-0.5 transition-all"
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
                                </div>
                            </div>
                        </Link>
                    </div>

                    <!-- Load More Button -->
                    <div v-if="hasMoreSkills" class="text-center">
                        <button
                            @click="loadMore"
                            class="inline-flex items-center gap-3 px-8 py-4 bg-slate-900/50 hover:bg-slate-800 border border-blue-500/20 hover:border-blue-500/60 text-white rounded-xl font-medium transition-all hover:shadow-[0_0_20px_rgba(59,130,246,0.2)]"
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
                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"
                                />
                            </svg>
                            <span>Load More Skills</span>
                            <div
                                class="px-2 py-0.5 bg-blue-500/20 rounded-full text-xs text-blue-400"
                            >
                                +{{ client.skills.length - skillsToShow }} more
                            </div>
                        </button>
                    </div>
                </section>

                <!-- Empty State -->
                <div v-else class="text-center py-20">
                    <div
                        class="w-20 h-20 bg-slate-900 border border-slate-800 rounded-full flex items-center justify-center mx-auto mb-4"
                    >
                        <svg
                            class="w-10 h-10 text-slate-700"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"
                            />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-400 mb-2">
                        No Skills Available
                    </h3>
                    <p class="text-slate-500">
                        This client hasn't added any skills yet.
                    </p>
                </div>

                <!-- CTA Section -->
                <section class="mt-20">
                    <div
                        class="relative rounded-3xl overflow-hidden bg-linear-to-br from-blue-900/40 via-slate-900/60 to-cyan-900/40 border border-blue-500/20 p-12 md:p-16"
                    >
                        <!-- Glow Effects -->
                        <div
                            class="absolute top-0 right-0 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl"
                        ></div>
                        <div
                            class="absolute bottom-0 left-0 w-96 h-96 bg-cyan-500/10 rounded-full blur-3xl"
                        ></div>

                        <div
                            class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-12 py-4"
                        >
                            <div class="flex-1">
                                <h3
                                    class="text-4xl md:text-5xl font-bold text-white mb-3"
                                >
                                    Can't find what you need?
                                </h3>
                                <p class="text-lg text-slate-300">
                                    Our team can help you source niche experts.
                                </p>
                            </div>
                            <button
                                class="px-12 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-base transition-all shadow-xl hover:shadow-2xl hover:shadow-blue-500/50 shrink-0"
                            >
                                Contact Support
                            </button>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </MainLayout>
</template>
