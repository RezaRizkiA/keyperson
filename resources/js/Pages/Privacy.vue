<script setup>
import { Head } from "@inertiajs/vue3";
import MainLayout from "../Layouts/MainLayout.vue";
import HeroSection from "../Components/HeroSection.vue";
import TableOfContents from "../Components/TableOfContents.vue";

const sections = [
    { id: "introduction", title: "1. Introduction" },
    { id: "information-collect", title: "2. Information We Collect" },
    { id: "how-use", title: "3. How We Use Your Information" },
    { id: "sharing", title: "4. Information Sharing" },
    { id: "data-security", title: "5. Data Security" },
    { id: "cookies", title: "6. Cookies & Tracking" },
    { id: "your-rights", title: "7. Your Rights" },
    { id: "retention", title: "8. Data Retention" },
    { id: "children", title: "9. Children's Privacy" },
    { id: "international", title: "10. International Transfers" },
    { id: "changes", title: "11. Changes to Privacy Policy" },
    { id: "contact", title: "12. Contact Us" },
];

const lastUpdated = "January 15, 2025";
</script>

<template>
    <Head title="Privacy Policy" />

    <MainLayout>
        <!-- Hero Section -->
        <HeroSection
            title="Privacy Policy"
            subtitle="Your privacy is important to us. This policy explains how we collect, use, and protect your personal information."
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
                        <section id="introduction" class="mb-12 scroll-mt-32">
                            <h2
                                class="text-2xl font-bold text-slate-900 dark:text-white mb-4"
                            >
                                1. Introduction
                            </h2>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed mb-4"
                            >
                                keyPerson, Inc. ("we," "us," or "our") is
                                committed to protecting your privacy. This
                                Privacy Policy explains how we collect, use,
                                disclose, and safeguard your information when
                                you use our platform.
                            </p>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed"
                            >
                                By using keyPerson, you consent to the practices
                                described in this Privacy Policy. If you do not
                                agree with this policy, please do not use our
                                Service.
                            </p>
                        </section>

                        <!-- Section 2 -->
                        <section
                            id="information-collect"
                            class="mb-12 scroll-mt-32"
                        >
                            <h2
                                class="text-2xl font-bold text-slate-900 dark:text-white mb-4"
                            >
                                2. Information We Collect
                            </h2>

                            <h3
                                class="text-xl font-bold text-slate-900 dark:text-white mb-3 mt-6"
                            >
                                2.1 Information You Provide
                            </h3>
                            <ul
                                class="list-disc list-inside text-slate-700 dark:text-slate-300 space-y-2 mb-4"
                            >
                                <li>
                                    <strong>Account Information:</strong> Name,
                                    email address, password, profile photo
                                </li>
                                <li>
                                    <strong>Professional Information:</strong>
                                    Credentials, expertise, experience, bio (for
                                    Experts)
                                </li>
                                <li>
                                    <strong>Payment Information:</strong> Credit
                                    card details, billing address (processed by
                                    Stripe)
                                </li>
                                <li>
                                    <strong>Calendar Information:</strong>
                                    Google Calendar access for scheduling
                                </li>
                                <li>
                                    <strong>Communications:</strong> Messages,
                                    reviews, feedback, support inquiries
                                </li>
                            </ul>

                            <h3
                                class="text-xl font-bold text-slate-900 dark:text-white mb-3 mt-6"
                            >
                                2.2 Information Collected Automatically
                            </h3>
                            <ul
                                class="list-disc list-inside text-slate-700 dark:text-slate-300 space-y-2 mb-4"
                            >
                                <li>
                                    <strong>Usage Data:</strong> Pages visited,
                                    features used, time spent on platform
                                </li>
                                <li>
                                    <strong>Device Information:</strong> IP
                                    address, browser type, operating system
                                </li>
                                <li>
                                    <strong>Cookies:</strong> Session cookies,
                                    preference cookies, analytics cookies
                                </li>
                                <li>
                                    <strong>Session Data:</strong> Start time,
                                    duration, attendance (with your consent,
                                    session recordings)
                                </li>
                            </ul>
                        </section>

                        <!-- Section 3 -->
                        <section id="how-use" class="mb-12 scroll-mt-32">
                            <h2
                                class="text-2xl font-bold text-slate-900 dark:text-white mb-4"
                            >
                                3. How We Use Your Information
                            </h2>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed mb-4"
                            >
                                We use your information to:
                            </p>
                            <ul
                                class="list-disc list-inside text-slate-700 dark:text-slate-300 space-y-2"
                            >
                                <li>
                                    Provide, operate, and maintain the Service
                                </li>
                                <li>
                                    Process transactions and send related
                                    notifications
                                </li>
                                <li>Match Clients with appropriate Experts</li>
                                <li>Facilitate communication between users</li>
                                <li>Improve and personalize user experience</li>
                                <li>
                                    Send administrative information and updates
                                </li>
                                <li>Respond to customer service requests</li>
                                <li>
                                    Analyze usage trends and optimize
                                    performance
                                </li>
                                <li>
                                    Detect, prevent, and address fraud or
                                    security issues
                                </li>
                                <li>Comply with legal obligations</li>
                            </ul>
                        </section>

                        <!-- Section 4 -->
                        <section id="sharing" class="mb-12 scroll-mt-32">
                            <h2
                                class="text-2xl font-bold text-slate-900 dark:text-white mb-4"
                            >
                                4. Information Sharing
                            </h2>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed mb-4"
                            >
                                We do not sell your personal information. We may
                                share information in the following
                                circumstances:
                            </p>

                            <h3
                                class="text-xl font-bold text-slate-900 dark:text-white mb-3 mt-6"
                            >
                                4.1 With Other Users
                            </h3>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed mb-4"
                            >
                                Your profile information is visible to other
                                users to facilitate connections. Clients can see
                                Expert profiles; Experts can see Client booking
                                details.
                            </p>

                            <h3
                                class="text-xl font-bold text-slate-900 dark:text-white mb-3 mt-6"
                            >
                                4.2 Service Providers
                            </h3>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed mb-4"
                            >
                                We share information with third-party service
                                providers who help us operate the platform:
                            </p>
                            <ul
                                class="list-disc list-inside text-slate-700 dark:text-slate-300 space-y-2 mb-4"
                            >
                                <li>
                                    <strong>Payment Processing:</strong> Stripe
                                    (for secure payment processing)
                                </li>
                                <li>
                                    <strong>Cloud Hosting:</strong> AWS (for
                                    data storage and infrastructure)
                                </li>
                                <li>
                                    <strong>Email Services:</strong> SendGrid
                                    (for transactional emails)
                                </li>
                                <li>
                                    <strong>Video Conferencing:</strong> Zoom,
                                    Google Meet (for sessions)
                                </li>
                                <li>
                                    <strong>Analytics:</strong> Google Analytics
                                    (for usage insights)
                                </li>
                            </ul>

                            <h3
                                class="text-xl font-bold text-slate-900 dark:text-white mb-3 mt-6"
                            >
                                4.3 Legal Requirements
                            </h3>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed"
                            >
                                We may disclose information if required by law,
                                court order, or to protect our rights, property,
                                or safety.
                            </p>
                        </section>

                        <!-- Section 5 -->
                        <section id="data-security" class="mb-12 scroll-mt-32">
                            <h2
                                class="text-2xl font-bold text-slate-900 dark:text-white mb-4"
                            >
                                5. Data Security
                            </h2>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed mb-4"
                            >
                                We implement industry-standard security measures
                                to protect your information:
                            </p>
                            <ul
                                class="list-disc list-inside text-slate-700 dark:text-slate-300 space-y-2 mb-4"
                            >
                                <li>
                                    TLS/SSL encryption for data transmission
                                </li>
                                <li>Encrypted storage for sensitive data</li>
                                <li>
                                    Regular security audits and penetration
                                    testing
                                </li>
                                <li>
                                    Access controls and authentication
                                    requirements
                                </li>
                                <li>
                                    Employee training on data privacy best
                                    practices
                                </li>
                            </ul>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed"
                            >
                                However, no method of transmission over the
                                internet is 100% secure. We cannot guarantee
                                absolute security.
                            </p>
                        </section>

                        <!-- Section 6 -->
                        <section id="cookies" class="mb-12 scroll-mt-32">
                            <h2
                                class="text-2xl font-bold text-slate-900 dark:text-white mb-4"
                            >
                                6. Cookies & Tracking Technologies
                            </h2>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed mb-4"
                            >
                                We use cookies and similar technologies to:
                            </p>
                            <ul
                                class="list-disc list-inside text-slate-700 dark:text-slate-300 space-y-2 mb-4"
                            >
                                <li>
                                    <strong>Essential Cookies:</strong> Required
                                    for platform functionality (e.g., session
                                    management)
                                </li>
                                <li>
                                    <strong>Analytics Cookies:</strong> Help us
                                    understand how users interact with the
                                    platform
                                </li>
                                <li>
                                    <strong>Preference Cookies:</strong>
                                    Remember your settings and preferences
                                </li>
                            </ul>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed"
                            >
                                You can control cookie settings through your
                                browser. Note that disabling cookies may affect
                                platform functionality.
                            </p>
                        </section>

                        <!-- Section 7 -->
                        <section id="your-rights" class="mb-12 scroll-mt-32">
                            <h2
                                class="text-2xl font-bold text-slate-900 dark:text-white mb-4"
                            >
                                7. Your Rights
                            </h2>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed mb-4"
                            >
                                Depending on your location, you may have the
                                following rights:
                            </p>
                            <ul
                                class="list-disc list-inside text-slate-700 dark:text-slate-300 space-y-2 mb-4"
                            >
                                <li>
                                    <strong>Access:</strong> Request a copy of
                                    your personal data
                                </li>
                                <li>
                                    <strong>Correction:</strong> Update
                                    inaccurate or incomplete information
                                </li>
                                <li>
                                    <strong>Deletion:</strong> Request deletion
                                    of your data (subject to legal obligations)
                                </li>
                                <li>
                                    <strong>Portability:</strong> Receive your
                                    data in a machine-readable format
                                </li>
                                <li>
                                    <strong>Objection:</strong> Object to
                                    certain processing activities
                                </li>
                                <li>
                                    <strong>Restriction:</strong> Request
                                    limitation of processing
                                </li>
                                <li>
                                    <strong>Withdraw Consent:</strong> Withdraw
                                    consent for optional data processing
                                </li>
                            </ul>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed"
                            >
                                To exercise these rights, contact us at
                                privacy@keyperson.id.
                            </p>
                        </section>

                        <!-- Section 8 -->
                        <section id="retention" class="mb-12 scroll-mt-32">
                            <h2
                                class="text-2xl font-bold text-slate-900 dark:text-white mb-4"
                            >
                                8. Data Retention
                            </h2>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed mb-4"
                            >
                                We retain your information for as long as
                                necessary to provide the Service and fulfill
                                legal obligations:
                            </p>
                            <ul
                                class="list-disc list-inside text-slate-700 dark:text-slate-300 space-y-2"
                            >
                                <li>
                                    <strong>Active Accounts:</strong> Retained
                                    while account is active
                                </li>
                                <li>
                                    <strong>Closed Accounts:</strong> Deleted
                                    within 90 days (except data required for
                                    legal compliance)
                                </li>
                                <li>
                                    <strong>Transaction Records:</strong>
                                    Retained for 7 years for tax and legal
                                    purposes
                                </li>
                                <li>
                                    <strong>Session Recordings:</strong>
                                    Retained according to user preferences
                                    (default: 90 days)
                                </li>
                            </ul>
                        </section>

                        <!-- Section 9 -->
                        <section id="children" class="mb-12 scroll-mt-32">
                            <h2
                                class="text-2xl font-bold text-slate-900 dark:text-white mb-4"
                            >
                                9. Children's Privacy
                            </h2>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed"
                            >
                                The Service is not intended for individuals
                                under 18 years of age. We do not knowingly
                                collect personal information from children. If
                                you believe we have collected information from a
                                child, please contact us immediately.
                            </p>
                        </section>

                        <!-- Section 10 -->
                        <section id="international" class="mb-12 scroll-mt-32">
                            <h2
                                class="text-2xl font-bold text-slate-900 dark:text-white mb-4"
                            >
                                10. International Data Transfers
                            </h2>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed mb-4"
                            >
                                Your information may be transferred to and
                                processed in countries other than your country
                                of residence. We ensure appropriate safeguards
                                are in place, including:
                            </p>
                            <ul
                                class="list-disc list-inside text-slate-700 dark:text-slate-300 space-y-2"
                            >
                                <li>
                                    Standard Contractual Clauses (SCCs) approved
                                    by the European Commission
                                </li>
                                <li>
                                    Adequacy decisions for data transfers to
                                    certain countries
                                </li>
                                <li>
                                    Compliance with GDPR and other data
                                    protection regulations
                                </li>
                            </ul>
                        </section>

                        <!-- Section 11 -->
                        <section id="changes" class="mb-12 scroll-mt-32">
                            <h2
                                class="text-2xl font-bold text-slate-900 dark:text-white mb-4"
                            >
                                11. Changes to This Privacy Policy
                            </h2>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed"
                            >
                                We may update this Privacy Policy from time to
                                time. We will notify you of material changes by
                                email or through a prominent notice on the
                                platform. Your continued use of the Service
                                after changes constitutes acceptance of the
                                updated policy.
                            </p>
                        </section>

                        <!-- Section 12 -->
                        <section id="contact" class="mb-12 scroll-mt-32">
                            <h2
                                class="text-2xl font-bold text-slate-900 dark:text-white mb-4"
                            >
                                12. Contact Us
                            </h2>
                            <p
                                class="text-slate-700 dark:text-slate-300 leading-relaxed mb-4"
                            >
                                If you have questions or concerns about this
                                Privacy Policy or our data practices, please
                                contact us:
                            </p>
                            <div
                                class="bg-white/80 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-6"
                            >
                                <p
                                    class="text-slate-700 dark:text-slate-300 mb-2"
                                >
                                    <strong>Email:</strong> privacy@keyperson.id
                                </p>
                                <p
                                    class="text-slate-700 dark:text-slate-300 mb-2"
                                >
                                    <strong>Data Protection Officer:</strong>
                                    dpo@keyperson.id
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
