<script setup>
import { Head } from "@inertiajs/vue3";
import MainLayout from "../Layouts/MainLayout.vue";
import HeroSection from "../Components/HeroSection.vue";
import TableOfContents from "../Components/TableOfContents.vue";

const sections = [
    { id: "acceptance", title: "1. Acceptance of Terms" },
    { id: "services", title: "2. Description of Services" },
    { id: "accounts", title: "3. User Accounts" },
    { id: "expert-obligations", title: "4. Expert Obligations" },
    { id: "client-obligations", title: "5. Client Obligations" },
    { id: "payments", title: "6. Payments & Refunds" },
    { id: "cancellation", title: "7. Cancellation Policy" },
    { id: "intellectual-property", title: "8. Intellectual Property" },
    { id: "prohibited-conduct", title: "9. Prohibited Conduct" },
    { id: "termination", title: "10. Termination" },
    { id: "disclaimers", title: "11. Disclaimers" },
    { id: "limitation-liability", title: "12. Limitation of Liability" },
    { id: "governing-law", title: "13. Governing Law" },
    { id: "changes", title: "14. Changes to Terms" },
    { id: "contact", title: "15. Contact Information" },
];

const lastUpdated = "January 15, 2025";
</script>

<template>
    <Head title="Terms of Service" />

    <MainLayout>
        <!-- Hero Section -->
        <HeroSection
            title="Terms of Service"
            subtitle="Please read these terms carefully before using keyPerson."
            align="center"
        />

        <!-- Content Section -->
        <section class="relative py-24 px-6 bg-slate-50 dark:bg-slate-900">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
                    <!-- Sidebar TOC -->
                    <aside class="hidden lg:block lg:col-span-1">
                        <TableOfContents :sections="sections" />
                    </aside>

                    <!-- Main Content -->
                    <article
                        class="lg:col-span-3 prose prose-slate dark:prose-invert max-w-none"
                    >
                        <!-- Last Updated -->
                        <div
                            class="text-sm text-slate-600 dark:text-slate-400 mb-8 pb-8 border-b border-slate-200 dark:border-slate-800"
                        >
                            <strong>Last Updated:</strong> {{ lastUpdated }}
                        </div>

                        <!-- Section 1 -->
                        <section id="acceptance" class="mb-12 scroll-mt-32">
                            <h2
                                class="text-2xl font-bold text-slate-900 dark:text-white mb-4"
                            >
                                1. Acceptance of Terms
                            </h2>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed mb-4"
                            >
                                By accessing or using the keyPerson platform
                                ("Service"), you agree to be bound by these
                                Terms of Service ("Terms"). If you do not agree
                                to these Terms, you may not use the Service.
                            </p>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed"
                            >
                                These Terms apply to all users of the Service,
                                including without limitation users who are
                                Clients, Experts, or browsers.
                            </p>
                        </section>

                        <!-- Section 2 -->
                        <section id="services" class="mb-12 scroll-mt-32">
                            <h2
                                class="text-2xl font-bold text-slate-900 dark:text-white mb-4"
                            >
                                2. Description of Services
                            </h2>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed mb-4"
                            >
                                keyPerson is a marketplace platform that
                                connects Clients seeking professional guidance
                                with Experts offering mentorship, coaching,
                                counseling, and consulting services. The
                                platform facilitates:
                            </p>
                            <ul
                                class="list-disc list-inside text-slate-700 dark:text-slate-300 space-y-2 mb-4"
                            >
                                <li>Profile creation and discovery</li>
                                <li>Calendar synchronization and scheduling</li>
                                <li>Payment processing and fund escrow</li>
                                <li>Video call integration for sessions</li>
                                <li>
                                    Session documentation and recording (with
                                    consent)
                                </li>
                            </ul>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed"
                            >
                                keyPerson is a facilitator and not a party to
                                the actual service relationship between Clients
                                and Experts.
                            </p>
                        </section>

                        <!-- Section 3 -->
                        <section id="accounts" class="mb-12 scroll-mt-32">
                            <h2
                                class="text-2xl font-bold text-slate-900 dark:text-white mb-4"
                            >
                                3. User Accounts
                            </h2>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed mb-4"
                            >
                                <strong>Registration:</strong> You must create
                                an account to use certain features of the
                                Service. You agree to provide accurate, current,
                                and complete information during registration and
                                to update such information as necessary.
                            </p>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed mb-4"
                            >
                                <strong>Account Security:</strong> You are
                                responsible for maintaining the confidentiality
                                of your account credentials and for all
                                activities that occur under your account. Notify
                                us immediately of any unauthorized access.
                            </p>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed"
                            >
                                <strong>Age Requirement:</strong> You must be at
                                least 18 years old to use the Service.
                            </p>
                        </section>

                        <!-- Section 4 -->
                        <section
                            id="expert-obligations"
                            class="mb-12 scroll-mt-32"
                        >
                            <h2
                                class="text-2xl font-bold text-slate-900 dark:text-white mb-4"
                            >
                                4. Expert Obligations
                            </h2>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed mb-4"
                            >
                                Experts agree to:
                            </p>
                            <ul
                                class="list-disc list-inside text-slate-700 dark:text-slate-300 space-y-2"
                            >
                                <li>
                                    Provide services in a professional and
                                    ethical manner
                                </li>
                                <li>
                                    Maintain accurate credentials and experience
                                    information
                                </li>
                                <li>
                                    Honor confirmed bookings or provide at least
                                    24 hours notice for cancellations
                                </li>
                                <li>
                                    Protect Client confidentiality in accordance
                                    with applicable laws
                                </li>
                                <li>
                                    Not solicit Clients to transact outside the
                                    platform
                                </li>
                                <li>
                                    Maintain appropriate licensing where
                                    required by law
                                </li>
                            </ul>
                        </section>

                        <!-- Section 5 -->
                        <section
                            id="client-obligations"
                            class="mb-12 scroll-mt-32"
                        >
                            <h2
                                class="text-2xl font-bold text-slate-900 dark:text-white mb-4"
                            >
                                5. Client Obligations
                            </h2>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed mb-4"
                            >
                                Clients agree to:
                            </p>
                            <ul
                                class="list-disc list-inside text-slate-700 dark:text-slate-300 space-y-2"
                            >
                                <li>
                                    Attend scheduled sessions or cancel with
                                    appropriate notice
                                </li>
                                <li>
                                    Provide payment information and authorize
                                    charges
                                </li>
                                <li>
                                    Treat Experts with respect and
                                    professionalism
                                </li>
                                <li>
                                    Not record sessions without explicit consent
                                </li>
                                <li>
                                    Not solicit Experts to transact outside the
                                    platform
                                </li>
                            </ul>
                        </section>

                        <!-- Section 6 -->
                        <section id="payments" class="mb-12 scroll-mt-32">
                            <h2
                                class="text-2xl font-bold text-slate-900 dark:text-white mb-4"
                            >
                                6. Payments & Refunds
                            </h2>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed mb-4"
                            >
                                <strong>Pricing:</strong> Experts set their own
                                rates. All prices are displayed in USD and are
                                subject to platform fees and applicable taxes.
                            </p>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed mb-4"
                            >
                                <strong>Escrow:</strong> Client payments are
                                held in escrow and released to Experts upon
                                successful completion of the session.
                            </p>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed mb-4"
                            >
                                <strong>Platform Fees:</strong> keyPerson
                                charges a service fee on each transaction (15%
                                for Experts, included in Client pricing).
                            </p>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed"
                            >
                                <strong>Refunds:</strong> Refunds are processed
                                according to our Cancellation Policy.
                            </p>
                        </section>

                        <!-- Section 7 -->
                        <section id="cancellation" class="mb-12 scroll-mt-32">
                            <h2
                                class="text-2xl font-bold text-slate-900 dark:text-white mb-4"
                            >
                                7. Cancellation Policy
                            </h2>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed mb-4"
                            >
                                <strong>By Clients:</strong>
                            </p>
                            <ul
                                class="list-disc list-inside text-slate-700 dark:text-slate-300 space-y-2 mb-4"
                            >
                                <li>24+ hours before session: Full refund</li>
                                <li>
                                    Less than 24 hours: 50% refund (subject to
                                    Expert policy)
                                </li>
                                <li>No-show: No refund</li>
                            </ul>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed mb-4"
                            >
                                <strong>By Experts:</strong> Experts may cancel
                                at any time with full refund to Client. Repeated
                                cancellations may result in account suspension.
                            </p>
                        </section>

                        <!-- Section 8 -->
                        <section
                            id="intellectual-property"
                            class="mb-12 scroll-mt-32"
                        >
                            <h2
                                class="text-2xl font-bold text-slate-900 dark:text-white mb-4"
                            >
                                8. Intellectual Property
                            </h2>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed mb-4"
                            >
                                The Service and its original content, features,
                                and functionality are owned by keyPerson and are
                                protected by international copyright, trademark,
                                and other intellectual property laws.
                            </p>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed"
                            >
                                Content created by Experts during sessions
                                remains their property unless otherwise agreed
                                with Clients.
                            </p>
                        </section>

                        <!-- Section 9 -->
                        <section
                            id="prohibited-conduct"
                            class="mb-12 scroll-mt-32"
                        >
                            <h2
                                class="text-2xl font-bold text-slate-900 dark:text-white mb-4"
                            >
                                9. Prohibited Conduct
                            </h2>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed mb-4"
                            >
                                You may not:
                            </p>
                            <ul
                                class="list-disc list-inside text-slate-700 dark:text-slate-300 space-y-2"
                            >
                                <li>Violate any laws or regulations</li>
                                <li>
                                    Impersonate others or provide false
                                    information
                                </li>
                                <li>Harass, abuse, or harm other users</li>
                                <li>
                                    Circumvent platform fees by transacting
                                    offline
                                </li>
                                <li>Use the Service for illegal activities</li>
                                <li>
                                    Scrape or collect data without permission
                                </li>
                                <li>
                                    Interfere with the operation of the Service
                                </li>
                            </ul>
                        </section>

                        <!-- Section 10 -->
                        <section id="termination" class="mb-12 scroll-mt-32">
                            <h2
                                class="text-2xl font-bold text-slate-900 dark:text-white mb-4"
                            >
                                10. Termination
                            </h2>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed mb-4"
                            >
                                We reserve the right to suspend or terminate
                                your account at any time for violation of these
                                Terms or for any other reason at our sole
                                discretion.
                            </p>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed"
                            >
                                You may terminate your account at any time
                                through your account settings. Termination does
                                not relieve you of obligations for completed
                                transactions.
                            </p>
                        </section>

                        <!-- Section 11 -->
                        <section id="disclaimers" class="mb-12 scroll-mt-32">
                            <h2
                                class="text-2xl font-bold text-slate-900 dark:text-white mb-4"
                            >
                                11. Disclaimers
                            </h2>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed mb-4"
                            >
                                THE SERVICE IS PROVIDED "AS IS" WITHOUT
                                WARRANTIES OF ANY KIND. WE DO NOT WARRANT THAT
                                THE SERVICE WILL BE UNINTERRUPTED OR ERROR-FREE.
                            </p>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed"
                            >
                                keyPerson is not responsible for the quality,
                                accuracy, or outcomes of services provided by
                                Experts. We do not endorse or guarantee any
                                Expert.
                            </p>
                        </section>

                        <!-- Section 12 -->
                        <section
                            id="limitation-liability"
                            class="mb-12 scroll-mt-32"
                        >
                            <h2
                                class="text-2xl font-bold text-slate-900 dark:text-white mb-4"
                            >
                                12. Limitation of Liability
                            </h2>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed"
                            >
                                TO THE FULLEST EXTENT PERMITTED BY LAW,
                                KEYPERSON SHALL NOT BE LIABLE FOR ANY INDIRECT,
                                INCIDENTAL, SPECIAL, OR CONSEQUENTIAL DAMAGES
                                ARISING OUT OF OR IN CONNECTION WITH THE
                                SERVICE. OUR TOTAL LIABILITY SHALL NOT EXCEED
                                THE AMOUNT PAID BY YOU IN THE PAST 12 MONTHS.
                            </p>
                        </section>

                        <!-- Section 13 -->
                        <section id="governing-law" class="mb-12 scroll-mt-32">
                            <h2
                                class="text-2xl font-bold text-slate-900 dark:text-white mb-4"
                            >
                                13. Governing Law
                            </h2>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed"
                            >
                                These Terms shall be governed by and construed
                                in accordance with the laws of the State of
                                California, USA, without regard to its conflict
                                of law provisions.
                            </p>
                        </section>

                        <!-- Section 14 -->
                        <section id="changes" class="mb-12 scroll-mt-32">
                            <h2
                                class="text-2xl font-bold text-slate-900 dark:text-white mb-4"
                            >
                                14. Changes to Terms
                            </h2>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed"
                            >
                                We reserve the right to modify these Terms at
                                any time. We will notify users of material
                                changes via email or through the Service.
                                Continued use of the Service after changes
                                constitutes acceptance of the new Terms.
                            </p>
                        </section>

                        <!-- Section 15 -->
                        <section id="contact" class="mb-12 scroll-mt-32">
                            <h2
                                class="text-2xl font-bold text-slate-900 dark:text-white mb-4"
                            >
                                15. Contact Information
                            </h2>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed mb-4"
                            >
                                If you have questions about these Terms, please
                                contact us:
                            </p>
                            <div
                                class="bg-white/80 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-6"
                            >
                                <p
                                    class="text-slate-700 dark:text-slate-300 mb-2"
                                >
                                    <strong>Email:</strong> legal@keyperson.id
                                </p>
                                <p
                                    class="text-slate-700 dark:text-slate-300 mb-2"
                                >
                                    <strong>Address:</strong> 100 Innovation
                                    Blvd, Tech District, San Francisco, CA 94102
                                </p>
                                <p class="text-slate-700 dark:text-slate-300">
                                    <strong>Phone:</strong> +1 (650) 555-0606
                                </p>
                            </div>
                        </section>
                    </article>
                </div>
            </div>
        </section>
    </MainLayout>
</template>

<style scoped>
/* Custom prose styles for light/dark theme */
:deep(.prose) {
    --tw-prose-body: #334155;
    --tw-prose-headings: #0f172a;
    --tw-prose-links: #2563eb;
    --tw-prose-bold: #0f172a;
    --tw-prose-bullets: #2563eb;
}

:deep(.dark .prose) {
    --tw-prose-body: #cbd5e1;
    --tw-prose-headings: #ffffff;
    --tw-prose-links: #60a5fa;
    --tw-prose-bold: #ffffff;
    --tw-prose-bullets: #60a5fa;
}
</style>
