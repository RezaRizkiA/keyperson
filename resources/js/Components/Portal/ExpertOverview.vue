<script setup>
import { computed } from "vue";
import { Star, Briefcase, Award } from "lucide-vue-next";

const props = defineProps({
    expert: {
        type: Object,
        required: true,
    },
    about: {
        type: String,
        default: "",
    },
    experiences: {
        type: Array,
        default: () => [],
    },
    licenses: {
        type: Array,
        default: () => [],
    },
});

// Emit events for navigation
const emit = defineEmits([
    "navigate-to-about",
    "navigate-to-reviews",
    "navigate-to-skills",
]);

// Summarize about text
const summarizedAbout = computed(() => {
    if (!props.about) return "";
    const text = props.about.replace(/<[^>]*>/g, ""); // Strip HTML
    return text.length > 250 ? text.substring(0, 250) + "..." : text;
});

const hasMore = computed(() => {
    const text = props.about.replace(/<[^>]*>/g, "");
    return text.length > 250;
});
</script>

<template>
    <div class="space-y-8">
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Rating Card - Clickable -->
            <button
                v-if="expert.expert?.rating"
                @click="emit('navigate-to-reviews')"
                class="bg-slate-900/50 backdrop-blur-sm border border-blue-500/20 hover:border-blue-500/60 rounded-2xl p-6 text-left transition-all hover:shadow-[0_0_30px_rgba(59,130,246,0.3)] group"
            >
                <div class="flex items-center gap-3 mb-2">
                    <Star class="w-6 h-6 text-yellow-400 fill-yellow-400" />
                    <h3
                        class="text-sm font-bold text-blue-400 uppercase group-hover:text-cyan-400 transition-colors"
                    >
                        Rating
                    </h3>
                </div>
                <p class="text-4xl font-bold text-white mb-1">
                    {{ expert.expert.rating }}
                </p>
                <p class="text-sm text-slate-400 mb-3">
                    {{ expert.expert.total_reviews }} reviews
                </p>
                <div
                    class="text-xs text-blue-400 group-hover:text-cyan-400 flex items-center gap-1"
                >
                    <span>View all reviews</span>
                    <svg
                        class="w-3 h-3 group-hover:translate-x-0.5 transition-transform"
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
            </button>

            <!-- Sessions Card - Not clickable -->
            <div
                v-if="expert.expert?.total_sessions !== undefined"
                class="bg-slate-900/50 backdrop-blur-sm border border-blue-500/20 rounded-2xl p-6"
            >
                <div class="flex items-center gap-3 mb-2">
                    <svg
                        class="w-6 h-6 text-blue-400"
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
                    <h3 class="text-sm font-bold text-blue-400 uppercase">
                        Sessions
                    </h3>
                </div>
                <p class="text-4xl font-bold text-white mb-1">
                    {{ expert.expert.total_sessions }}+
                </p>
                <p class="text-sm text-slate-400">Completed sessions</p>
            </div>

            <!-- Expertise Card - Clickable -->
            <button
                v-if="expert.expert?.skills"
                @click="emit('navigate-to-skills')"
                class="bg-slate-900/50 backdrop-blur-sm border border-blue-500/20 hover:border-blue-500/60 rounded-2xl p-6 text-left transition-all hover:shadow-[0_0_30px_rgba(59,130,246,0.3)] group"
            >
                <div class="flex items-center gap-3 mb-2">
                    <svg
                        class="w-6 h-6 text-blue-400"
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
                    <h3
                        class="text-sm font-bold text-blue-400 uppercase group-hover:text-cyan-400 transition-colors"
                    >
                        Expertise
                    </h3>
                </div>
                <p class="text-4xl font-bold text-white mb-1">
                    {{ expert.expert.skills.length }}
                </p>
                <p class="text-sm text-slate-400 mb-3">Areas of expertise</p>
                <div
                    class="text-xs text-blue-400 group-hover:text-cyan-400 flex items-center gap-1"
                >
                    <span>View all skills</span>
                    <svg
                        class="w-3 h-3 group-hover:translate-x-0.5 transition-transform"
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
            </button>
        </div>

        <!-- Summary About -->
        <div
            v-if="about"
            class="bg-slate-900/50 backdrop-blur-sm border border-blue-500/20 rounded-2xl p-8"
        >
            <h3 class="text-xl font-bold text-white mb-4">About</h3>
            <p class="text-slate-300 leading-relaxed mb-4">
                {{ summarizedAbout }}
            </p>
            <button
                v-if="hasMore"
                @click="emit('navigate-to-about')"
                class="text-blue-400 hover:text-cyan-400 font-medium text-sm flex items-center gap-2 transition-colors"
            >
                Read More
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
            </button>
        </div>

        <!-- Recent Experience -->
        <div
            v-if="experiences.length > 0"
            class="bg-slate-900/50 backdrop-blur-sm border border-blue-500/20 rounded-2xl p-8"
        >
            <div class="flex items-center justify-between mb-6">
                <h3
                    class="text-xl font-bold text-white flex items-center gap-2"
                >
                    <Briefcase class="w-5 h-5 text-blue-400" />
                    Recent Experience
                </h3>
            </div>
            <div class="space-y-4">
                <div
                    v-for="(exp, index) in experiences.slice(0, 2)"
                    :key="index"
                    class="border-l-2 border-blue-500 pl-4 pb-4 last:pb-0"
                >
                    <h4 class="font-bold text-white">{{ exp.position }}</h4>
                    <p class="text-blue-400 text-sm">
                        {{ exp.company }} · {{ exp.years }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Certifications Preview -->
        <div
            v-if="licenses.length > 0"
            class="bg-slate-900/50 backdrop-blur-sm border border-blue-500/20 rounded-2xl p-8"
        >
            <div class="flex items-center justify-between mb-6">
                <h3
                    class="text-xl font-bold text-white flex items-center gap-2"
                >
                    <Award class="w-5 h-5 text-blue-400" />
                    Certifications
                </h3>
            </div>
            <div class="flex flex-wrap gap-3">
                <div
                    v-for="(license, index) in licenses.slice(0, 3)"
                    :key="index"
                    class="px-4 py-2 bg-blue-500/10 border border-blue-500/30 text-blue-400 rounded-lg text-sm font-medium"
                >
                    {{ license.certification }}
                </div>
                <div
                    v-if="licenses.length > 3"
                    class="px-4 py-2 bg-slate-800 border border-slate-700 text-slate-400 rounded-lg text-sm"
                >
                    +{{ licenses.length - 3 }} more
                </div>
            </div>
        </div>
    </div>
</template>
