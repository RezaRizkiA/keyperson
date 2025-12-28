<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { ref, computed } from "vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import {
    Star,
    ChevronLeft,
    Mail,
    Phone,
    MapPin,
    MessageCircle,
    Share2,
    MoreVertical,
    CheckCircle,
} from "lucide-vue-next";

// Components
import ExpertOverview from "@/Components/Portal/ExpertOverview.vue";
import ExpertAbout from "@/Components/Portal/ExpertAbout.vue";
import ExpertExperience from "@/Components/Portal/ExpertExperience.vue";
import ExpertLicenses from "@/Components/Portal/ExpertLicenses.vue";
import ExpertSocials from "@/Components/Portal/ExpertSocials.vue";
import ExpertReviews from "@/Components/Portal/ExpertReviews.vue";
import ExpertSkills from "@/Components/Portal/ExpertSkills.vue";

const props = defineProps({
    expert: {
        type: Object,
        required: true,
    },
    reviewsCount: {
        type: Number,
        default: 0,
    },
    backUrl: {
        type: String,
        default: null,
    },
    skillId: {
        type: Number,
        default: null,
    },
});

// Booking URL with skill_id query param
const bookingUrl = computed(() => {
    const baseUrl = route("booking.create", props.expert.slug);
    if (props.skillId) {
        return `${baseUrl}?skill_id=${props.skillId}`;
    }
    return baseUrl;
});

// Active tab state
const activeTab = ref("overview");

// Parse JSON fields safely
const experiences = computed(() => {
    try {
        const exp = props.expert.experiences;
        if (typeof exp === "string") return JSON.parse(exp);
        if (Array.isArray(exp)) return exp;
        return [];
    } catch {
        return [];
    }
});

const licenses = computed(() => {
    try {
        const lic = props.expert.licenses;
        if (typeof lic === "string") return JSON.parse(lic);
        if (Array.isArray(lic)) return lic;
        return [];
    } catch {
        return [];
    }
});

const socials = computed(() => {
    try {
        const soc = props.expert.socials;
        let parsed = [];

        if (typeof soc === "string") {
            parsed = JSON.parse(soc);
        } else if (Array.isArray(soc)) {
            parsed = soc;
        }

        // Convert array format [{key, value}] → {key: value}
        if (Array.isArray(parsed) && parsed.length > 0 && parsed[0]?.key) {
            return parsed.reduce((obj, item) => {
                obj[item.key] = item.value;
                return obj;
            }, {});
        }

        return parsed;
    } catch {
        return {};
    }
});

const expertTypes = computed(() => {
    try {
        const types = props.expert.type;
        if (typeof types === "string") return JSON.parse(types);
        if (Array.isArray(types)) return types;
        return [];
    } catch {
        return [];
    }
});

// Tab navigation
const switchTab = (tab) => {
    activeTab.value = tab;
    // Smooth scroll to content
    const element = document.getElementById("tab-content");
    if (element) {
        element.scrollIntoView({ behavior: "smooth", block: "start" });
    }
};

const navigateToAbout = () => {
    switchTab("about");
};

const navigateToReviews = () => {
    switchTab("reviews");
};

const navigateToSkills = () => {
    switchTab("skills");
};

// Back navigation - use backUrl prop from backend
const goBack = () => {
    if (props.backUrl) {
        router.visit(props.backUrl);
    } else {
        router.visit(route("home"));
    }
};
</script>

<template>
    <Head :title="`${expert.user?.name} - Expert Profile`" />

    <MainLayout>
        <div class="min-h-screen bg-slate-950 text-white pt-20">
            <!-- Back Navigation -->
            <div class="max-w-7xl mx-auto px-6 py-4 mt-5">
                <button
                    @click="goBack"
                    class="inline-flex items-center gap-2 text-slate-400 hover:text-white transition-colors"
                >
                    <ChevronLeft class="w-5 h-5" />
                    <span>Back</span>
                </button>
            </div>

            <!-- Cover/Hero Section -->
            <div class="max-w-7xl mx-auto px-6">
                <div class="relative rounded-3xl overflow-hidden">
                    <!-- Cover with Glow -->
                    <div
                        class="h-64 md:h-80 relative bg-linear-to-br from-blue-900 via-slate-900 to-cyan-900 overflow-hidden"
                    >
                        <div v-if="expert.background" class="absolute inset-0">
                            <img
                                :src="expert.background"
                                alt="Background"
                                class="w-full h-full object-cover opacity-30"
                            />
                        </div>

                        <!-- Animated Glow -->
                        <div class="absolute inset-0 opacity-40">
                            <div
                                class="absolute top-0 left-0 w-full h-full bg-[linear-gradient(45deg,transparent_25%,rgba(59,130,246,0.1)_50%,transparent_75%)] animate-pulse-glow"
                            ></div>
                        </div>

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

                    <!-- Expert Info Card -->
                    <div
                        class="bg-slate-900/95 backdrop-blur-md border-t border-slate-800 p-6"
                    >
                        <div
                            class="flex flex-col md:flex-row items-start md:items-center gap-6"
                        >
                            <!-- Avatar -->
                            <div class="shrink-0 -mt-20 md:-mt-24">
                                <div
                                    class="w-32 h-32 md:w-40 md:h-40 rounded-2xl bg-slate-800 border-4 border-slate-900 overflow-hidden shadow-xl"
                                >
                                    <img
                                        v-if="expert.user?.picture"
                                        :src="expert.user.picture"
                                        :alt="expert.user?.name"
                                        class="w-full h-full object-cover"
                                    />
                                    <div
                                        v-else
                                        class="w-full h-full flex items-center justify-center text-5xl font-bold text-blue-400 bg-slate-800"
                                    >
                                        {{ expert.user?.name?.charAt(0) }}
                                    </div>
                                </div>
                            </div>

                            <!-- Expert Details -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start gap-2 mb-2">
                                    <h1
                                        class="text-3xl md:text-4xl font-bold text-white"
                                    >
                                        {{ expert.user?.name }}
                                    </h1>
                                    <CheckCircle
                                        class="w-6 h-6 text-blue-500 shrink-0"
                                    />
                                </div>

                                <p
                                    v-if="expert.title"
                                    class="text-xl text-blue-400 font-medium mb-3"
                                >
                                    {{ expert.title }}
                                </p>

                                <!-- Expert Types -->
                                <div
                                    v-if="expertTypes.length > 0"
                                    class="flex flex-wrap gap-2 mb-4"
                                >
                                    <span
                                        v-for="(type, index) in expertTypes"
                                        :key="index"
                                        class="px-3 py-1 bg-blue-500/10 border border-blue-500/30 text-blue-400 rounded-full text-xs font-medium"
                                    >
                                        {{ type }}
                                    </span>
                                </div>

                                <!-- Meta Info -->
                                <div
                                    class="flex flex-wrap items-center gap-4 text-sm text-slate-400"
                                >
                                    <!-- Rating -->
                                    <div
                                        v-if="expert.rating"
                                        class="flex items-center gap-2"
                                    >
                                        <Star
                                            class="w-5 h-5 text-yellow-400 fill-yellow-400"
                                        />
                                        <span class="font-bold text-white">{{
                                            expert.rating
                                        }}</span>
                                        <span
                                            >({{
                                                expert.total_reviews
                                            }}
                                            reviews)</span
                                        >
                                    </div>

                                    <!-- Sessions -->
                                    <div
                                        v-if="
                                            expert.total_sessions !== undefined
                                        "
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
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                            />
                                        </svg>
                                        <span
                                            >{{ expert.total_sessions }}+
                                            sessions</span
                                        >
                                    </div>

                                    <!-- Email -->
                                    <div
                                        v-if="expert.user?.email"
                                        class="flex items-center gap-1.5"
                                    >
                                        <Mail class="w-4 h-4" />
                                        <span>{{ expert.user.email }}</span>
                                    </div>

                                    <!-- Phone -->
                                    <div
                                        v-if="expert.user?.phone"
                                        class="flex items-center gap-1.5"
                                    >
                                        <Phone class="w-4 h-4" />
                                        <span>{{ expert.user.phone }}</span>
                                    </div>

                                    <!-- Location -->
                                    <div
                                        v-if="expert.user?.address"
                                        class="flex items-center gap-1.5"
                                    >
                                        <MapPin class="w-4 h-4" />
                                        <span>{{ expert.user.address }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- CTA Buttons -->
                            <div class="flex items-center gap-3 shrink-0">
                                <button
                                    class="px-6 py-2.5 bg-slate-800 hover:bg-slate-700 text-white border border-slate-700 hover:border-blue-500/50 rounded-lg font-medium transition-all flex items-center gap-2"
                                >
                                    <MessageCircle class="w-5 h-5" />
                                    Message
                                </button>
                                <Link
                                    :href="bookingUrl"
                                    class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-all"
                                >
                                    Book · Rp{{
                                        (expert.price || 0).toLocaleString(
                                            "id-ID"
                                        )
                                    }}/hr
                                </Link>
                            </div>
                        </div>

                        <!-- Tab Navigation -->
                        <div
                            class="flex items-center gap-8 mt-6 border-t border-slate-800 pt-4 overflow-x-auto"
                        >
                            <button
                                @click="switchTab('overview')"
                                :class="[
                                    'pb-2 font-medium transition-all relative shrink-0',
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
                                @click="switchTab('about')"
                                :class="[
                                    'pb-2 font-medium transition-all relative shrink-0',
                                    activeTab === 'about'
                                        ? 'text-blue-400'
                                        : 'text-slate-400 hover:text-white',
                                ]"
                            >
                                About
                                <div
                                    v-if="activeTab === 'about'"
                                    class="absolute bottom-0 left-0 right-0 h-0.5 bg-blue-400"
                                ></div>
                            </button>
                            <button
                                @click="switchTab('skills')"
                                :class="[
                                    'pb-2 font-medium transition-all relative shrink-0',
                                    activeTab === 'skills'
                                        ? 'text-blue-400'
                                        : 'text-slate-400 hover:text-white',
                                ]"
                            >
                                Skills
                                <div
                                    v-if="activeTab === 'skills'"
                                    class="absolute bottom-0 left-0 right-0 h-0.5 bg-blue-400"
                                ></div>
                            </button>
                            <button
                                @click="switchTab('reviews')"
                                :class="[
                                    'pb-2 font-medium transition-all relative shrink-0',
                                    activeTab === 'reviews'
                                        ? 'text-blue-400'
                                        : 'text-slate-400 hover:text-white',
                                ]"
                            >
                                Reviews
                                <div
                                    v-if="activeTab === 'reviews'"
                                    class="absolute bottom-0 left-0 right-0 h-0.5 bg-blue-400"
                                ></div>
                            </button>
                            <button
                                v-if="experiences.length > 0"
                                @click="switchTab('experience')"
                                :class="[
                                    'pb-2 font-medium transition-all relative shrink-0',
                                    activeTab === 'experience'
                                        ? 'text-blue-400'
                                        : 'text-slate-400 hover:text-white',
                                ]"
                            >
                                Experience
                                <div
                                    v-if="activeTab === 'experience'"
                                    class="absolute bottom-0 left-0 right-0 h-0.5 bg-blue-400"
                                ></div>
                            </button>
                            <button
                                v-if="licenses.length > 0"
                                @click="switchTab('licenses')"
                                :class="[
                                    'pb-2 font-medium transition-all relative shrink-0',
                                    activeTab === 'licenses'
                                        ? 'text-blue-400'
                                        : 'text-slate-400 hover:text-white',
                                ]"
                            >
                                Licenses
                                <div
                                    v-if="activeTab === 'licenses'"
                                    class="absolute bottom-0 left-0 right-0 h-0.5 bg-blue-400"
                                ></div>
                            </button>
                            <button
                                v-if="
                                    socials.linkedin ||
                                    socials.twitter ||
                                    socials.website
                                "
                                @click="switchTab('socials')"
                                :class="[
                                    'pb-2 font-medium transition-all relative shrink-0',
                                    activeTab === 'socials'
                                        ? 'text-blue-400'
                                        : 'text-slate-400 hover:text-white',
                                ]"
                            >
                                Socials
                                <div
                                    v-if="activeTab === 'socials'"
                                    class="absolute bottom-0 left-0 right-0 h-0.5 bg-blue-400"
                                ></div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Content -->
            <div id="tab-content" class="max-w-7xl mx-auto px-6 py-12">
                <!-- Overview Tab -->
                <ExpertOverview
                    v-if="activeTab === 'overview'"
                    :expert="expert"
                    :about="expert.about || ''"
                    :experiences="experiences"
                    :licenses="licenses"
                    @navigate-to-about="navigateToAbout"
                    @navigate-to-reviews="navigateToReviews"
                    @navigate-to-skills="navigateToSkills"
                />

                <!-- About Tab -->
                <ExpertAbout
                    v-if="activeTab === 'about'"
                    :about="expert.about || ''"
                />

                <!-- Skills Tab -->
                <ExpertSkills
                    v-if="activeTab === 'skills'"
                    :skills="expert.skills || []"
                />

                <!-- Reviews Tab -->
                <ExpertReviews
                    v-if="activeTab === 'reviews'"
                    :expert="expert"
                    :reviews="expert.reviews || []"
                    :reviewsCount="reviewsCount"
                />

                <!-- Experience Tab -->
                <ExpertExperience
                    v-if="activeTab === 'experience'"
                    :experiences="experiences"
                />

                <!-- Licenses Tab -->
                <ExpertLicenses
                    v-if="activeTab === 'licenses'"
                    :licenses="licenses"
                />

                <!-- Socials Tab -->
                <ExpertSocials
                    v-if="activeTab === 'socials'"
                    :socials="socials"
                />
            </div>

            <!-- CTA Section -->
            <div class="max-w-7xl mx-auto px-6 pb-20">
                <section>
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
                                    Ready to accelerate your growth?
                                </h3>
                                <p class="text-lg text-slate-300">
                                    Book a session with
                                    {{ expert.user?.name }} today.
                                </p>
                            </div>
                            <Link
                                :href="bookingUrl"
                                class="px-12 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-base transition-all shadow-xl hover:shadow-2xl hover:shadow-blue-500/50 shrink-0"
                            >
                                Book Now
                            </Link>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </MainLayout>
</template>
