<script setup>
import { Head } from "@inertiajs/vue3";
import MainLayout from "../Layouts/MainLayout.vue";
import HeroSection from "../Components/HeroSection.vue";
import FAQAccordion from "../Components/FAQAccordion.vue";
import CTASection from "../Components/CTASection.vue";
import {
    BookOpen,
    MessageCircle,
    Video,
    CreditCard,
    Calendar,
    Users,
} from "lucide-vue-next";

// Help categories
const helpCategories = [
    {
        icon: "book",
        title: "Getting Started",
        description:
            "Learn the basics of using keyPerson, from creating an account to booking your first session.",
        link: "#getting-started",
    },
    {
        icon: "message",
        title: "For Clients",
        description:
            "Find experts, manage bookings, and get the most out of your sessions.",
        link: "#for-clients",
    },
    {
        icon: "video",
        title: "For Experts",
        description:
            "Set up your profile, manage availability, and grow your practice.",
        link: "#for-experts",
    },
    {
        icon: "card",
        title: "Billing & Payments",
        description: "Understand pricing, refunds, and payment methods.",
        link: "#billing",
    },
    {
        icon: "calendar",
        title: "Scheduling & Calendar",
        description:
            "Sync with Google Calendar, manage time zones, and reschedule sessions.",
        link: "#scheduling",
    },
    {
        icon: "users",
        title: "Account & Settings",
        description:
            "Update profile, manage notifications, and security settings.",
        link: "#account",
    },
];

// Comprehensive FAQ data
const faqItems = [
    {
        question: "How do I book a session?",
        answer: "Browse our expert directory by expertise or search for specific skills. When you find someone you'd like to work with, click on their profile to view their calendar. Select an available time slot and complete the booking with your preferred payment method. You'll receive a confirmation email with the meeting link.",
    },
    {
        question: "Can I reschedule my appointment?",
        answer: 'Yes! You can reschedule up to 24 hours before your session through your dashboard. Go to "My Sessions," select the appointment, and click "Reschedule." If it\'s within 24 hours, you\'ll need to contact the expert directly or reach out to our support team for assistance.',
    },
    {
        question: "How does payment work?",
        answer: "Payments are processed securely via Stripe at the time of booking. The funds are held in escrow and only released to the expert after the session is successfully completed. This ensures both parties are protected. All major credit cards and digital wallets are accepted.",
    },
    {
        question: "What if I need to cancel a session?",
        answer: "You can cancel up to 24 hours before the session for a full refund. Cancellations within 24 hours are subject to the expert's cancellation policy (typically 50% refund). No-shows without prior notice are non-refundable. Experts can also cancel with full refunds at any time.",
    },
    {
        question: "How do I apply to become an expert?",
        answer: 'Click "Join as Expert" in the navigation menu. Complete your professional profile, including credentials, experience, and areas of expertise. Our vetting team reviews applications within 5-7 business days. We look for verified experience, strong communication skills, and genuine passion for mentoring or coaching.',
    },
    {
        question: "Is my session confidential?",
        answer: "Absolutely. All sessions are private and conducted via encrypted video calls. We never record sessions without explicit consent from both parties. Experts are bound by professional confidentiality agreements. Your personal information is protected by our strict privacy policy.",
    },
    {
        question: "What technology do I need for a session?",
        answer: "You need a device with a camera and microphone (laptop, tablet, or smartphone), a stable internet connection, and a modern web browser (Chrome, Firefox, Safari, or Edge). Sessions are conducted via Zoom or Google Meet—links are sent automatically to your email and calendar.",
    },
    {
        question: "Can I switch experts if I'm not satisfied?",
        answer: "Yes! We want you to find the right match. If you're not satisfied with your first session, contact support@keyperson.id within 24 hours and we'll issue a credit so you can try another expert. Our goal is to ensure you get value from every session.",
    },
    {
        question: "How do I sync with Google Calendar?",
        answer: "During account setup, you'll be prompted to connect your Google account. This allows automatic calendar syncing for all your bookings. You can also enable this later in Settings > Integrations. Once connected, all sessions appear in your Google Calendar with meeting links and reminders.",
    },
    {
        question: "Do you offer group sessions or corporate plans?",
        answer: "Yes! Many experts offer group coaching sessions at discounted rates. For corporate plans with team-wide access, dedicated account management, and custom billing, contact our sales team at enterprise@keyperson.id or view our Pricing page for details.",
    },
];
</script>

<template>
    <Head title="Support - How Can We Help?" />

    <MainLayout>
        <div>
            <!-- Hero Section -->
            <HeroSection
                title="How Can We Help?"
                subtitle="Find answers to common questions, browse our help categories, or contact our support team directly."
                align="center"
            />

            <!-- Help Categories Section -->
            <section class="relative py-24 px-6 bg-slate-900">
                <div class="max-w-7xl mx-auto">
                    <div class="text-center max-w-2xl mx-auto mb-16">
                        <h2
                            class="text-3xl md:text-4xl font-bold text-white mb-4"
                        >
                            Browse Help Topics
                        </h2>
                        <p class="text-slate-400">
                            Quick access to the most common questions and
                            guides.
                        </p>
                    </div>

                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                    >
                        <a
                            v-for="(category, index) in helpCategories"
                            :key="index"
                            :href="category.link"
                            class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-2xl p-6 hover:border-blue-500/50 hover:shadow-[0_0_40px_rgba(59,130,246,0.2)] transition-all group"
                        >
                            <!-- Icon -->
                            <div
                                class="w-12 h-12 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center mb-4 group-hover:bg-blue-500/20 transition-colors"
                            >
                                <BookOpen
                                    v-if="category.icon === 'book'"
                                    class="w-6 h-6 text-blue-400"
                                />
                                <MessageCircle
                                    v-else-if="category.icon === 'message'"
                                    class="w-6 h-6 text-blue-400"
                                />
                                <Video
                                    v-else-if="category.icon === 'video'"
                                    class="w-6 h-6 text-blue-400"
                                />
                                <CreditCard
                                    v-else-if="category.icon === 'card'"
                                    class="w-6 h-6 text-blue-400"
                                />
                                <Calendar
                                    v-else-if="category.icon === 'calendar'"
                                    class="w-6 h-6 text-blue-400"
                                />
                                <Users v-else class="w-6 h-6 text-blue-400" />
                            </div>

                            <!-- Title -->
                            <h3
                                class="text-lg font-bold text-white mb-2 group-hover:text-blue-400 transition-colors"
                            >
                                {{ category.title }}
                            </h3>

                            <!-- Description -->
                            <p class="text-sm text-slate-400 leading-relaxed">
                                {{ category.description }}
                            </p>
                        </a>
                    </div>
                </div>
            </section>

            <!-- FAQ Section -->
            <section class="relative py-24 px-6 bg-slate-950">
                <div
                    class="absolute inset-0 bg-glow-gradient opacity-20 -z-10"
                ></div>

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
                            Can't find what you're looking for? Contact our
                            support team.
                        </p>
                    </div>

                    <FAQAccordion :items="faqItems" />
                </div>
            </section>

            <!-- Contact Support CTA -->
            <CTASection
                title="Still Need Help?"
                description="Our support team is here for you. Reach out and we'll respond within 24 hours."
                primary-button-text="Contact Support"
                :primary-button-link="route('contact')"
            />
        </div>
    </MainLayout>
</template>
