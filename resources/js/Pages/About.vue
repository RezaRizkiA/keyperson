<script setup>
import { Head } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import MainLayout from "../Layouts/MainLayout.vue";
import HeroSection from "../Components/HeroSection.vue";
import FeatureCard from "../Components/FeatureCard.vue";
import CTASection from "../Components/CTASection.vue";
import {
    Users,
    BookOpen,
    Target,
    Search,
    Handshake,
    Sparkles,
    CheckCircle,
} from "lucide-vue-next";

// Data
const expertise = [
    {
        icon: "users",
        title: "Counseling",
        description:
            "Professional mental health and life guidance from certified counselors who understand your challenges.",
        color: "blue-500",
        features: [
            "Individual Sessions",
            "Relationship Dynamics",
            "Personal Well-being",
        ],
    },
    {
        icon: "book",
        title: "Mentoring",
        description:
            "One-on-one guidance from industry leaders who have walked the path you aspire to take.",
        color: "cyan-500",
        features: ["Career Growth", "Leadership Skills", "Industry Insights"],
    },
    {
        icon: "target",
        title: "Coaching",
        description:
            "Action-oriented coaching to unlock your full potential and achieve measurable results.",
        color: "violet-500",
        features: ["Performance Optimization", "Goal Setting", "Skill Mastery"],
    },
];

const processSteps = [
    {
        number: "01",
        title: "Discover",
        description:
            "Browse our curated network of experts. Filter by expertise, availability, and reviews to find your ideal match.",
    },
    {
        number: "02",
        title: "Connect",
        description:
            "Book instantly with our smart calendar sync. No email ping-pong—just click the slot that works for you.",
    },
    {
        number: "03",
        title: "Transform",
        description:
            "Attend your session via integrated video call. Payment is secure and sessions are automatically documented.",
    },
];

const benefits = [
    {
        icon: "verified",
        title: "Verified Experts",
        description:
            "Every expert undergoes strict vetting and interview process",
    },
    {
        icon: "calendar",
        title: "Zero Scheduling Hassle",
        description:
            "Google Calendar integration means no double-booking or confusion",
    },
    {
        icon: "shield",
        title: "Secure Payments",
        description:
            "Escrow system ensures your money is safe until session completes",
    },
    {
        icon: "video",
        title: "Seamless Meetings",
        description:
            "Zoom/GMeet links generated automatically for every booking",
    },
];
</script>

<template>
    <Head title="About Us - Connecting You With Expert Guidance" />

    <MainLayout>
        <div class="bg-slate-900 text-white">
            <!-- Hero Section -->
            <HeroSection
                badge="TRUSTED BY LEADING EXPERTS"
                title="Catalyzing Growth Through Expert Connection"
                subtitle="keyPerson is the premium platform bridging top-tier professionals and professionals. We handle admin—booking, payments, calendar sync—so both parties can focus purely on impactful conversations and growth."
                align="center"
            />

            <!-- Our Expertise Section -->
            <section class="relative py-24 px-6 bg-slate-900">
                <div class="max-w-7xl mx-auto">
                    <div class="text-center max-w-2xl mx-auto mb-16">
                        <p
                            class="text-sm font-bold text-blue-400 uppercase tracking-wider mb-4"
                        >
                            OUR EXPERTISE
                        </p>
                        <h2
                            class="text-3xl md:text-5xl font-bold text-white mb-6"
                        >
                            Tailored Guidance for Every Path
                        </h2>
                        <p class="text-slate-400 leading-relaxed">
                            Whether you're navigating career transitions,
                            seeking mental clarity, or optimizing performance,
                            our experts provide specialized support.
                        </p>
                    </div>

                    <!-- Expertise Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <FeatureCard
                            v-for="(item, index) in expertise"
                            :key="index"
                            :icon="item.icon"
                            :title="item.title"
                            :description="item.description"
                            :color="item.color"
                            :features="item.features"
                        >
                            <template #icon>
                                <Users
                                    v-if="item.icon === 'users'"
                                    class="w-6 h-6"
                                />
                                <BookOpen
                                    v-else-if="item.icon === 'book'"
                                    class="w-6 h-6"
                                />
                                <Target v-else class="w-6 h-6" />
                            </template>
                        </FeatureCard>
                    </div>
                </div>
            </section>

            <!-- How It Works Section -->
            <section class="relative py-24 px-6 bg-slate-950">
                <div
                    class="absolute inset-0 bg-glow-gradient opacity-30 -z-10"
                ></div>

                <div class="max-w-7xl mx-auto">
                    <div class="text-center max-w-2xl mx-auto mb-16">
                        <p
                            class="text-sm font-bold text-cyan-400 uppercase tracking-wider mb-4"
                        >
                            HOW IT WORKS
                        </p>
                        <h2
                            class="text-3xl md:text-5xl font-bold text-white mb-6"
                        >
                            Connecting with an Expert
                        </h2>
                    </div>

                    <!-- Process Steps -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                        <div
                            v-for="(step, index) in processSteps"
                            :key="index"
                            class="relative text-center"
                        >
                            <!-- Number -->
                            <div
                                class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-500/10 border-2 border-blue-500/30 mb-6"
                            >
                                <span
                                    class="text-3xl font-bold text-blue-400"
                                    >{{ step.number }}</span
                                >
                            </div>

                            <!-- Title -->
                            <h3 class="text-2xl font-bold text-white mb-4">
                                {{ step.title }}
                            </h3>

                            <!-- Description -->
                            <p class="text-slate-400 leading-relaxed">
                                {{ step.description }}
                            </p>

                            <!-- Connector Line (not for last item) -->
                            <div
                                v-if="index < processSteps.length - 1"
                                class="hidden md:block absolute top-10 left-[60%] w-[80%] h-0.5 bg-linear-to-r from-blue-500/50 to-transparent"
                            ></div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- The keyPerson Advantage Section -->
            <section class="relative py-24 px-6 bg-slate-900">
                <div class="max-w-7xl mx-auto">
                    <div class="text-center max-w-2xl mx-auto mb-16">
                        <p
                            class="text-sm font-bold text-blue-400 uppercase tracking-wider mb-4"
                        >
                            THE KEYPERSON ADVANTAGE
                        </p>
                        <h2
                            class="text-3xl md:text-5xl font-bold text-white mb-6"
                        >
                            Why Top Professionals Choose Us
                        </h2>
                        <p class="text-slate-400 leading-relaxed">
                            We didn't just build a platform—we built an
                            ecosystem that values trust, efficiency, and real
                            impact.
                        </p>
                    </div>

                    <!-- Benefits Grid -->
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8"
                    >
                        <div
                            v-for="(benefit, index) in benefits"
                            :key="index"
                            class="text-center"
                        >
                            <!-- Icon -->
                            <div
                                class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-blue-500/10 border border-blue-500/20 mb-6"
                            >
                                <CheckCircle
                                    v-if="benefit.icon === 'verified'"
                                    class="w-8 h-8 text-blue-400"
                                />
                                <Search
                                    v-else-if="benefit.icon === 'calendar'"
                                    class="w-8 h-8 text-blue-400"
                                />
                                <Handshake
                                    v-else-if="benefit.icon === 'shield'"
                                    class="w-8 h-8 text-blue-400"
                                />
                                <Sparkles
                                    v-else
                                    class="w-8 h-8 text-blue-400"
                                />
                            </div>

                            <!-- Title -->
                            <h3 class="text-lg font-bold text-white mb-3">
                                {{ benefit.title }}
                            </h3>

                            <!-- Description -->
                            <p class="text-sm text-slate-400 leading-relaxed">
                                {{ benefit.description }}
                            </p>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div
                        class="grid grid-cols-2 md:grid-cols-4 gap-8 mt-20 pt-16 border-t border-slate-800"
                    >
                        <div class="text-center">
                            <div
                                class="text-4xl md:text-5xl font-bold text-transparent bg-clip-text bg-linear-to-r from-blue-400 to-cyan-400 mb-2"
                            >
                                15,000+
                            </div>
                            <div class="text-slate-400 font-medium">
                                Verified Experts
                            </div>
                        </div>
                        <div class="text-center">
                            <div
                                class="text-4xl md:text-5xl font-bold text-transparent bg-clip-text bg-linear-to-r from-blue-400 to-cyan-400 mb-2"
                            >
                                50,000+
                            </div>
                            <div class="text-slate-400 font-medium">
                                Sessions Completed
                            </div>
                        </div>
                        <div class="text-center">
                            <div
                                class="text-4xl md:text-5xl font-bold text-transparent bg-clip-text bg-linear-to-r from-blue-400 to-cyan-400 mb-2"
                            >
                                4.9/5
                            </div>
                            <div class="text-slate-400 font-medium">
                                Average Rating
                            </div>
                        </div>
                        <div class="text-center">
                            <div
                                class="text-4xl md:text-5xl font-bold text-transparent bg-clip-text bg-linear-to-r from-blue-400 to-cyan-400 mb-2"
                            >
                                98%
                            </div>
                            <div class="text-slate-400 font-medium">
                                Satisfaction Rate
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <CTASection
                title="Ready to Accelerate Your Journey?"
                description="Join 15,000+ professionals who've found clarity, mentorship, and transformation through keyPerson."
                primary-button-text="Find Your Expert"
                :primary-button-link="route('client_onboarding.create')"
                secondary-button-text="Become an Expert"
                :secondary-button-link="route('expert_onboarding.create')"
            />
        </div>
    </MainLayout>
</template>
