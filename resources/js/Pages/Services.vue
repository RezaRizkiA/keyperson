<script setup>
import { Head } from "@inertiajs/vue3";
import MainLayout from "../Layouts/MainLayout.vue";
import HeroSection from "../Components/HeroSection.vue";
import FeatureCard from "../Components/FeatureCard.vue";
import CTASection from "../Components/CTASection.vue";
import {
    Brain,
    Users,
    TrendingUp,
    Shield,
    Zap,
    Globe,
    Award,
    Clock,
} from "lucide-vue-next";

// Services data
const services = [
    {
        icon: "brain",
        title: "1-on-1 Mentorship",
        description:
            "Personalized guidance from industry veterans who have achieved what you aspire to accomplish.",
        color: "blue-500",
        features: [
            "Career Navigation",
            "Industry Insights",
            "Network Building",
        ],
    },
    {
        icon: "users",
        title: "Group Coaching",
        description:
            "Collaborative sessions with peers facing similar challenges, led by expert facilitators.",
        color: "cyan-500",
        features: ["Peer Learning", "Shared Experiences", "Team Building"],
    },
    {
        icon: "trending",
        title: "Executive Coaching",
        description:
            "High-level strategic guidance for C-suite and senior leaders navigating complex decisions.",
        color: "violet-500",
        features: [
            "Leadership Development",
            "Strategic Thinking",
            "Decision Making",
        ],
    },
    {
        icon: "shield",
        title: "Mental Health Counseling",
        description:
            "Licensed therapists and counselors providing confidential support for personal well-being.",
        color: "blue-500",
        features: [
            "Stress Management",
            "Work-Life Balance",
            "Emotional Wellness",
        ],
    },
    {
        icon: "zap",
        title: "Skill Development",
        description:
            "Targeted training sessions to master specific skills or technologies relevant to your field.",
        color: "cyan-500",
        features: ["Technical Skills", "Soft Skills", "Certifications"],
    },
    {
        icon: "globe",
        title: "Career Transition",
        description:
            "Navigate industry shifts, role changes, or entrepreneurial ventures with expert guidance.",
        color: "violet-500",
        features: ["Resume Review", "Interview Prep", "Salary Negotiation"],
    },
];

// Benefits
const benefits = [
    {
        icon: "award",
        title: "Verified Expertise",
        description:
            "Every professional undergoes rigorous vetting including background checks, credential verification, and sample sessions.",
    },
    {
        icon: "clock",
        title: "Flexible Scheduling",
        description:
            "Book sessions that fit your timezone and calendar. No more email ping-pong or scheduling conflicts.",
    },
    {
        icon: "shield",
        title: "Confidential & Secure",
        description:
            "All sessions are private and encrypted. We never share your information without explicit consent.",
    },
    {
        icon: "zap",
        title: "Instant Access",
        description:
            "Browse profiles, check availability, and book within minutes. Meeting links sent automatically to your calendar.",
    },
];

// Testimonials
const testimonials = [
    {
        quote: "Connecting with a mentor here transformed my career trajectory. It's not just a platform; it's a catalyst for success.",
        author: "Sarah Jenks",
        role: "Senior Product Designer",
        avatar: "👩‍💼",
    },
    {
        quote: "The caliber of experts is unmatched. I went from confusion to clarity in just three sessions.",
        author: "Michael Chen",
        role: "Tech Entrepreneur",
        avatar: "👨‍💻",
    },
];
</script>

<template>
    <Head title="Services - Professional Guidance for Every Need" />

    <MainLayout>
        <div>
            <!-- Hero Section -->
            <HeroSection
                badge="OUR SERVICES"
                title="Professional Services for Every Need"
                subtitle="From career coaching to mental health support, our platform connects you with verified experts who provide personalized guidance tailored to your unique journey."
                align="center"
            />

            <!-- Services Grid Section -->
            <section class="relative py-24 px-6 bg-slate-900">
                <div class="max-w-7xl mx-auto">
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
                    >
                        <FeatureCard
                            v-for="(service, index) in services"
                            :key="index"
                            :icon="service.icon"
                            :title="service.title"
                            :description="service.description"
                            :color="service.color"
                            :features="service.features"
                        >
                            <template #icon>
                                <Brain
                                    v-if="service.icon === 'brain'"
                                    class="w-6 h-6"
                                />
                                <Users
                                    v-else-if="service.icon === 'users'"
                                    class="w-6 h-6"
                                />
                                <TrendingUp
                                    v-else-if="service.icon === 'trending'"
                                    class="w-6 h-6"
                                />
                                <Shield
                                    v-else-if="service.icon === 'shield'"
                                    class="w-6 h-6"
                                />
                                <Zap
                                    v-else-if="service.icon === 'zap'"
                                    class="w-6 h-6"
                                />
                                <Globe v-else class="w-6 h-6" />
                            </template>
                        </FeatureCard>
                    </div>
                </div>
            </section>

            <!-- Why Choose Us Section -->
            <section class="relative py-24 px-6 bg-slate-950">
                <div
                    class="absolute inset-0 bg-glow-gradient opacity-30 -z-10"
                ></div>

                <div class="max-w-7xl mx-auto">
                    <div class="text-center max-w-2xl mx-auto mb-16">
                        <p
                            class="text-sm font-bold text-blue-400 uppercase tracking-wider mb-4"
                        >
                            WHY CHOOSE US
                        </p>
                        <h2
                            class="text-3xl md:text-5xl font-bold text-white mb-6"
                        >
                            More Than Just Scheduling
                        </h2>
                        <p class="text-slate-400 leading-relaxed">
                            We built keyPerson because finding the right expert
                            shouldn't be harder than the challenge you're
                            facing.
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
                                <Award
                                    v-if="benefit.icon === 'award'"
                                    class="w-8 h-8 text-blue-400"
                                />
                                <Clock
                                    v-else-if="benefit.icon === 'clock'"
                                    class="w-8 h-8 text-blue-400"
                                />
                                <Shield
                                    v-else-if="benefit.icon === 'shield'"
                                    class="w-8 h-8 text-blue-400"
                                />
                                <Zap v-else class="w-8 h-8 text-blue-400" />
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
                </div>
            </section>

            <!-- Testimonials Section -->
            <section class="relative py-24 px-6 bg-slate-900">
                <div class="max-w-7xl mx-auto">
                    <div class="text-center max-w-2xl mx-auto mb-16">
                        <p
                            class="text-sm font-bold text-cyan-400 uppercase tracking-wider mb-4"
                        >
                            TESTIMONIALS
                        </p>
                        <h2
                            class="text-3xl md:text-5xl font-bold text-white mb-6"
                        >
                            What Our Users Say
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div
                            v-for="(testimonial, index) in testimonials"
                            :key="index"
                            class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-3xl p-8 hover:border-blue-500/50 hover:shadow-[0_0_40px_rgba(59,130,246,0.2)] transition-all"
                        >
                            <!-- Stars -->
                            <div class="flex gap-1 mb-6">
                                <span
                                    v-for="i in 5"
                                    :key="i"
                                    class="text-yellow-400"
                                    >⭐</span
                                >
                            </div>

                            <!-- Quote -->
                            <p
                                class="text-slate-300 text-lg leading-relaxed mb-6 italic"
                            >
                                "{{ testimonial.quote }}"
                            </p>

                            <!-- Author -->
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 rounded-full bg-blue-500/20 border border-blue-500/30 flex items-center justify-center text-2xl"
                                >
                                    {{ testimonial.avatar }}
                                </div>
                                <div>
                                    <div class="font-bold text-white">
                                        {{ testimonial.author }}
                                    </div>
                                    <div class="text-sm text-slate-400">
                                        {{ testimonial.role }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <CTASection
                title="Ready to Get Started?"
                description="Browse our network of experts and book your first session today. No commitments, cancel anytime."
                primary-button-text="Find an Expert"
                :primary-button-link="route('client_onboarding.create')"
                secondary-button-text="Become an Expert"
                :secondary-button-link="route('expert_onboarding.create')"
            />
        </div>
    </MainLayout>
</template>
