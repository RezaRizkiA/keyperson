<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import ClientHeader from "@/Components/Portal/ClientHeader.vue";

const props = defineProps({
    client: {
        type: Object,
        required: true,
    },
    skill: {
        type: Object,
        required: true,
    },
    experts: {
        type: Array,
        required: true,
    },
    expertCount: {
        type: Number,
        required: true,
    },
});
</script>

<template>
    <Head :title="`${skill.name} Experts - ${client.company_name}`" />

    <div class="min-h-screen bg-slate-950 text-white">
        <!-- Client Header with Back Button -->
        <ClientHeader
            :client="client"
            :show-back-button="true"
            :back-route="route('client.home', client.slug)"
        />

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-6 py-16">
            <!-- Header Section - Centered -->
            <div class="text-center mb-16">
                <h1 class="text-5xl md:text-6xl font-bold text-white mb-4">
                    Find Your Expert
                </h1>
                <p class="text-xl text-slate-400 max-w-3xl mx-auto">
                    Connect with world-class mentors and consultants to
                    accelerate your growth.
                </p>
            </div>

            <!-- Experts Grid -->
            <div
                v-if="experts.length > 0"
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
            >
                <!-- Expert Card -->
                <div
                    v-for="expert in experts"
                    :key="expert.id"
                    class="bg-slate-900/50 backdrop-blur-sm border border-slate-800 hover:border-blue-500/50 rounded-3xl p-8 transition-all duration-300 hover:shadow-[0_0_40px_rgba(59,130,246,0.2)] flex flex-col"
                >
                    <!-- Avatar with Verified Badge -->
                    <div class="flex justify-center mb-6">
                        <div class="relative">
                            <div
                                class="w-28 h-28 rounded-full overflow-hidden border-4 border-slate-800 bg-slate-800"
                            >
                                <img
                                    v-if="expert.profile_photo_url"
                                    :src="expert.profile_photo_url"
                                    :alt="expert.name"
                                    class="w-full h-full object-cover"
                                />
                                <div
                                    v-else
                                    class="w-full h-full flex items-center justify-center text-3xl font-bold text-blue-400"
                                >
                                    {{ expert.name?.charAt(0) }}
                                </div>
                            </div>
                            <!-- Verified Badge -->
                            <div
                                class="absolute bottom-0 right-0 w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center border-4 border-slate-900"
                            >
                                <svg
                                    class="w-4 h-4 text-white"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Expert Info -->
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold text-white mb-2">
                            {{ expert.name }}
                        </h3>
                        <p
                            v-if="expert.expert?.title"
                            class="text-blue-400 font-medium mb-4"
                        >
                            {{ expert.expert.title }}
                        </p>

                        <!-- Rating & Sessions -->
                        <div
                            class="flex items-center justify-center gap-3 mb-4"
                        >
                            <!-- Rating Badge -->
                            <div
                                v-if="expert.expert?.rating"
                                class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-800/50 border border-slate-700 rounded-lg"
                            >
                                <svg
                                    class="w-4 h-4 text-yellow-400"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                    />
                                </svg>
                                <span class="text-white font-bold text-sm">{{
                                    expert.expert.rating
                                }}</span>
                                <span
                                    v-if="expert.expert.total_reviews"
                                    class="text-slate-400 text-xs"
                                    >({{ expert.expert.total_reviews }})</span
                                >
                            </div>

                            <!-- Sessions Badge -->
                            <div
                                v-if="
                                    expert.expert &&
                                    expert.expert.total_sessions !== undefined
                                "
                                class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-800/50 border border-slate-700 rounded-lg"
                            >
                                <svg
                                    class="w-4 h-4 text-blue-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                                <span class="text-white font-medium text-sm"
                                    >{{ expert.expert.total_sessions }}+</span
                                >
                                <span class="text-slate-400 text-xs"
                                    >Sessions</span
                                >
                            </div>
                        </div>
                        <!-- Bio -->
                        <p
                            v-if="expert.expert?.about"
                            class="text-slate-300 text-sm leading-relaxed line-clamp-3"
                        >
                            {{ expert.expert.about }}
                        </p>
                    </div>

                    <!-- Hourly Rate & Actions -->
                    <div class="mt-auto pt-6 border-t border-slate-800">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-slate-400 text-sm"
                                >Hourly Rate</span
                            >
                            <span
                                v-if="expert.expert?.price"
                                class="text-2xl font-bold text-white"
                                >Rp{{
                                    expert.expert.price.toLocaleString("id-ID")
                                }}<span class="text-sm text-slate-400"
                                    >/hr</span
                                ></span
                            >
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3">
                            <button
                                class="shrink-0 w-12 h-12 bg-slate-800 hover:bg-slate-700 border border-slate-700 hover:border-blue-500/50 rounded-xl flex items-center justify-center transition-all"
                            >
                                <svg
                                    class="w-5 h-5 text-slate-300"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                                    />
                                </svg>
                            </button>
                            <Link
                                :href="route('experts.show', expert.id)"
                                class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-all text-center flex items-center justify-center gap-2"
                            >
                                <span>View Profile</span>
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
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-20">
                <div
                    class="w-24 h-24 bg-slate-900 border border-slate-800 rounded-full flex items-center justify-center mx-auto mb-6"
                >
                    <svg
                        class="w-12 h-12 text-slate-700"
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
                </div>
                <h3 class="text-2xl font-bold text-slate-400 mb-3">
                    No Experts Found
                </h3>
                <p class="text-slate-500 text-lg">
                    No experts are currently available for this skill.
                </p>
            </div>
        </div>
    </div>
</template>
