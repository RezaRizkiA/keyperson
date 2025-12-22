<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref } from "vue";
import MainLayout from "../Layouts/MainLayout.vue";
import HeroSection from "../Components/HeroSection.vue";
import FAQAccordion from "../Components/FAQAccordion.vue";
import { Check, X } from "lucide-vue-next";

// Billing period toggle
const billingPeriod = ref("yearly");

// Pricing tiers
const pricingTiers = [
    {
        name: "Explorer",
        description: "Perfect for individuals seeking occasional guidance",
        monthlyPrice: 49,
        yearlyPrice: 490,
        savings: "2 months free",
        features: [
            "5 sessions per month",
            "Access to vetted mentors",
            "Basic calendar sync",
            "Email support",
            "Session recordings (30 days)",
        ],
        excluded: [
            "Dedicated account manager",
            "Priority booking",
            "Advanced analytics",
        ],
        cta: "Get Started",
        popular: false,
        color: "slate",
    },
    {
        name: "Achiever",
        description: "For ambitious professionals elevating their career",
        monthlyPrice: 149,
        yearlyPrice: 1490,
        savings: "2 months free",
        features: [
            "Unlimited sessions",
            "Premium expert access",
            "Full calendar integration",
            "Priority support",
            "Session recordings (unlimited)",
            "Downloadable transcripts",
            "1-on-1 onboarding call",
        ],
        excluded: ["Dedicated account manager", "Team analytics dashboard"],
        cta: "Start Free Trial",
        popular: true,
        color: "blue",
    },
    {
        name: "Enterprise",
        description: "Complete solution for teams and organizations",
        monthlyPrice: 499,
        yearlyPrice: 4990,
        savings: "2 months free",
        features: [
            "Everything in Achiever",
            "Unlimited team members",
            "Dedicated account manager",
            "Custom onboarding",
            "Advanced analytics dashboard",
            "24/7 Priority Network Access",
            "Bulk billing",
            "SLA guarantee",
        ],
        excluded: [],
        cta: "Contact Sales",
        popular: false,
        color: "cyan",
    },
];

// FAQ data
const faqItems = [
    {
        question: "How does the matching process work?",
        answer: "We use a proprietary algorithm combined with human curation to match you with experts based on your goals, industry, and availability. You can also browse our expert directory and book directly with anyone who catches your eye.",
    },
    {
        question: "Can I switch between monthly and yearly billing?",
        answer: "Absolutely! You can upgrade or downgrade your plan at any time. If switching from monthly to yearly mid-cycle, we'll pro-rate your remaining monthly balance and apply it to your yearly subscription.",
    },
    {
        question: "Is there a commitment period?",
        answer: "No lock-in contracts. Our monthly plans are month-to-month, and you can cancel anytime. Yearly plans offer the best value but can also be canceled with a pro-rated refund for unused months.",
    },
    {
        question: "What if I'm not satisfied with my session?",
        answer: "We stand behind the quality of our network. If you're not satisfied within the first 10 minutes of a session, reach out to support@keyperson.id and we'll issue a full credit to rebook with a different expert.",
    },
    {
        question: "Do you offer custom enterprise solutions?",
        answer: "Yes! Enterprise plans can be fully customized to your organization's needs—whether that's dedicated expert pools, custom integrations with your HR systems, or white-label options. Contact our sales team to discuss.",
    },
];

// Trusted by companies (placeholder)
const trustedCompanies = [
    "ACME",
    "GlobalCorp",
    "Nebula",
    "Vertex",
    "Starlings",
];
</script>

<template>
    <Head title="Pricing - Invest in Your Unlimited Potential" />

    <MainLayout>
        <div>
            <!-- Hero Section -->
            <HeroSection
                badge="TRANSPARENT PRICING"
                title="Invest in Your Unlimited Potential"
                subtitle="Choose a plan that fits your ambition. Whether you're seeking occasional guidance or building up expertise to elevate your entire team, we have the right plan for your journey."
                align="center"
            >
                <!-- Billing Toggle -->
                <div
                    class="inline-flex items-center gap-3 bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-full p-1.5 mb-8"
                >
                    <button
                        @click="billingPeriod = 'monthly'"
                        class="px-6 py-2.5 rounded-full font-bold text-sm transition-all"
                        :class="
                            billingPeriod === 'monthly'
                                ? 'bg-blue-600 text-white shadow-lg'
                                : 'text-slate-400 hover:text-white'
                        "
                    >
                        Monthly
                    </button>
                    <button
                        @click="billingPeriod = 'yearly'"
                        class="px-6 py-2.5 rounded-full font-bold text-sm transition-all flex items-center gap-2"
                        :class="
                            billingPeriod === 'yearly'
                                ? 'bg-blue-600 text-white shadow-lg'
                                : 'text-slate-400 hover:text-white'
                        "
                    >
                        Yearly
                        <span
                            class="text-xs bg-green-500/20 text-green-400 px-2 py-0.5 rounded-full border border-green-500/30"
                        >
                            Save 20%
                        </span>
                    </button>
                </div>
            </HeroSection>

            <!-- Pricing Cards Section -->
            <section class="relative py-24 px-6 bg-slate-900">
                <div class="max-w-7xl mx-auto">
                    <div
                        class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start"
                    >
                        <div
                            v-for="(tier, index) in pricingTiers"
                            :key="index"
                            class="relative rounded-3xl p-8 transition-all duration-300"
                            :class="[
                                tier.popular
                                    ? 'bg-slate-800 border-2 border-blue-500 shadow-[0_0_60px_rgba(59,130,246,0.3)] scale-105 md:scale-110'
                                    : 'bg-slate-800/50 border border-slate-700 hover:border-blue-500/50 hover:shadow-[0_0_40px_rgba(59,130,246,0.2)]',
                            ]"
                        >
                            <!-- Popular Badge -->
                            <div
                                v-if="tier.popular"
                                class="absolute -top-4 left-1/2 -translate-x-1/2"
                            >
                                <span
                                    class="bg-blue-600 text-white px-4 py-1.5 rounded-full text-xs font-bold shadow-lg"
                                >
                                    MOST POPULAR
                                </span>
                            </div>

                            <!-- Tier Name -->
                            <h3 class="text-2xl font-bold text-white mb-2">
                                {{ tier.name }}
                            </h3>
                            <p class="text-sm text-slate-400 mb-6">
                                {{ tier.description }}
                            </p>

                            <!-- Price -->
                            <div class="mb-6">
                                <div class="flex items-baseline gap-2 mb-1">
                                    <span class="text-5xl font-bold text-white">
                                        ${{
                                            billingPeriod === "monthly"
                                                ? tier.monthlyPrice
                                                : tier.yearlyPrice
                                        }}
                                    </span>
                                    <span class="text-slate-400"
                                        >/{{
                                            billingPeriod === "monthly"
                                                ? "month"
                                                : "year"
                                        }}</span
                                    >
                                </div>
                                <div
                                    v-if="billingPeriod === 'yearly'"
                                    class="text-sm text-green-400 font-medium"
                                >
                                    {{ tier.savings }}
                                </div>
                            </div>

                            <!-- CTA Button -->
                            <Link
                                :href="route('client_onboarding.create')"
                                class="block w-full text-center py-3.5 rounded-xl font-bold transition-all mb-8"
                                :class="
                                    tier.popular
                                        ? 'bg-blue-600 text-white hover:bg-blue-700 shadow-lg shadow-blue-500/20'
                                        : 'bg-slate-700 text-white hover:bg-slate-600'
                                "
                            >
                                {{ tier.cta }}
                            </Link>

                            <!-- Features List -->
                            <div class="space-y-3">
                                <p
                                    class="text-xs font-bold text-slate-400 uppercase tracking-wider"
                                >
                                    INCLUDES:
                                </p>

                                <!-- Included Features -->
                                <div
                                    v-for="(feature, fIndex) in tier.features"
                                    :key="'inc-' + fIndex"
                                    class="flex items-start gap-3"
                                >
                                    <Check
                                        class="w-5 h-5 text-blue-400 shrink-0 mt-0.5"
                                    />
                                    <span class="text-sm text-slate-300">{{
                                        feature
                                    }}</span>
                                </div>

                                <!-- Excluded Features (grayed out) -->
                                <div
                                    v-for="(feature, fIndex) in tier.excluded"
                                    :key="'exc-' + fIndex"
                                    class="flex items-start gap-3 opacity-40"
                                >
                                    <X
                                        class="w-5 h-5 text-slate-500 shrink-0 mt-0.5"
                                    />
                                    <span
                                        class="text-sm text-slate-500 line-through"
                                        >{{ feature }}</span
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Trusted By Section -->
            <section class="relative py-16 px-6 bg-slate-950">
                <div class="max-w-7xl mx-auto text-center">
                    <p
                        class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-8"
                    >
                        TRUSTED BY LEADERS FROM
                    </p>
                    <div
                        class="flex flex-wrap items-center justify-center gap-12"
                    >
                        <div
                            v-for="(company, index) in trustedCompanies"
                            :key="index"
                            class="text-2xl font-bold text-slate-700 hover:text-slate-500 transition-colors"
                        >
                            {{ company }}
                        </div>
                    </div>
                </div>
            </section>

            <!-- FAQ Section -->
            <section class="relative py-24 px-6 bg-slate-900">
                <div class="max-w-4xl mx-auto">
                    <div class="text-center mb-12">
                        <p
                            class="text-sm font-bold text-blue-400 uppercase tracking-wider mb-4"
                        >
                            SUPPORT
                        </p>
                        <h2
                            class="text-3xl md:text-4xl font-bold text-white mb-4"
                        >
                            Frequently Asked Questions
                        </h2>
                        <p class="text-slate-400">
                            Have a question not answered here? Reach out to our
                            support team.
                        </p>
                    </div>

                    <FAQAccordion :items="faqItems" />
                </div>
            </section>
        </div>
    </MainLayout>
</template>
