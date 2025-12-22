<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    expert: {
        type: Object,
        required: true,
    },
});

// Helper to get initials
const getInitials = (name) => {
    return name
        .split(" ")
        .map((word) => word[0])
        .join("")
        .toUpperCase()
        .substring(0, 2);
};
</script>

<template>
    <div
        class="bg-slate-900/50 backdrop-blur-sm border border-blue-500/20 rounded-2xl p-6 hover:border-blue-500/60 hover:shadow-[0_0_30px_rgba(59,130,246,0.2)] transition-all duration-300 group"
    >
        <!-- Expert Profile -->
        <div class="flex items-start gap-4 mb-4">
            <!-- Avatar -->
            <div class="shrink-0">
                <div
                    class="w-16 h-16 rounded-full bg-blue-500/10 border-2 border-blue-500/30 overflow-hidden"
                >
                    <img
                        v-if="expert.profile_photo_url"
                        :src="expert.profile_photo_url"
                        :alt="expert.name"
                        class="w-full h-full object-cover"
                    />
                    <div
                        v-else
                        class="w-full h-full flex items-center justify-center text-lg font-bold text-blue-400"
                    >
                        {{ getInitials(expert.name) }}
                    </div>
                </div>
            </div>

            <!-- Info -->
            <div class="flex-1 min-w-0">
                <h3
                    class="text-lg font-bold text-white mb-1 group-hover:text-blue-400 transition-colors truncate"
                >
                    {{ expert.name }}
                </h3>
                <p
                    v-if="expert.expert?.title"
                    class="text-slate-400 text-sm mb-2"
                >
                    {{ expert.expert.title }}
                </p>

                <!-- Rating & Sessions -->
                <div class="flex items-center gap-4 text-sm">
                    <div
                        v-if="expert.expert?.rating"
                        class="flex items-center gap-1 text-yellow-400"
                    >
                        <svg
                            class="w-4 h-4"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                            />
                        </svg>
                        <span class="font-medium">{{
                            expert.expert.rating
                        }}</span>
                    </div>
                    <div
                        v-if="expert.expert?.total_sessions"
                        class="text-slate-400"
                    >
                        {{ expert.expert.total_sessions }} sessions
                    </div>
                </div>
            </div>
        </div>

        <!-- Bio (if available) -->
        <p
            v-if="expert.expert?.bio"
            class="text-slate-300 text-sm mb-4 line-clamp-2"
        >
            {{ expert.expert.bio }}
        </p>

        <!-- Actions -->
        <div class="flex items-center gap-3 pt-4 border-t border-slate-800">
            <Link
                :href="route('experts.show', expert.id)"
                class="flex-1 text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors"
            >
                View Profile
            </Link>
            <button
                class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white border border-slate-700 hover:border-blue-500/50 rounded-lg font-medium transition-all"
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
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                    />
                </svg>
            </button>
        </div>

        <!-- Hourly Rate (if available) -->
        <div
            v-if="expert.expert?.hourly_rate"
            class="mt-4 pt-4 border-t border-slate-800"
        >
            <div class="flex items-center justify-between">
                <span class="text-slate-400 text-sm">Hourly Rate</span>
                <span class="text-xl font-bold text-blue-400"
                    >${{ expert.expert.hourly_rate }}</span
                >
            </div>
        </div>
    </div>
</template>
