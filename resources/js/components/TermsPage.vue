<script setup>
const props = defineProps({
    privacyUrl: String
});
</script>

<template>
    <div class="bg-slate-50 min-h-screen font-sans text-slate-600 pt-24 pb-20">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-12">
                <h1 class="font-display text-4xl md:text-5xl font-bold text-slate-900 mb-4">Terms of Service</h1>
                <span
                    class="inline-block px-3 py-1 bg-white border border-slate-200 rounded-full text-sm font-medium text-slate-500 shadow-sm">
                    Effective Date: July 22, 2025
                </span>
            </div>

            <div class="bg-white p-8 md:p-12 rounded-2xl shadow-sm border border-slate-200">

                <p class="text-lg leading-relaxed mb-8">
                    These Terms of Service ("Terms") govern your use of <strong
                        class="text-slate-900">Keyperson.id</strong>, owned and operated by <strong
                        class="text-slate-900">PT Kastara Prama Nusantara</strong>. By accessing or using the platform,
                    you agree to these Terms.
                </p>

                <hr class="my-8 border-slate-100">

                <div class="mb-10">
                    <h2 class="font-display text-2xl font-bold text-slate-900 mb-4">Description of Service</h2>
                    <p class="leading-relaxed">
                        Keyperson.id is an online platform that allows users to find professionals (e.g., counselors,
                        mentors, teachers) and schedule appointments. Appointments are automatically integrated with
                        Google Calendar.
                    </p>
                </div>

                <div class="mb-10">
                    <h2 class="font-display text-2xl font-bold text-slate-900 mb-4">Google Authentication</h2>
                    <p class="leading-relaxed mb-4">
                        To use the platform, you must log in using your Google account. You grant us permission to:
                    </p>
                    <ul class="space-y-3 pl-2">
                        <li class="flex items-start gap-3">
                            <span class="mt-2 w-1.5 h-1.5 bg-slate-900 rounded-full flex-shrink-0"></span>
                            <span class="leading-relaxed">View your Google profile (name, email, photo)</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-2 w-1.5 h-1.5 bg-slate-900 rounded-full flex-shrink-0"></span>
                            <span class="leading-relaxed">Manage your calendar events (create, update, delete)</span>
                        </li>
                    </ul>
                    <p
                        class="leading-relaxed mt-4 bg-violet-50 text-violet-700 p-4 rounded-xl border border-violet-100 text-sm">
                        These permissions are only used to support appointment scheduling and calendar synchronization.
                    </p>
                </div>

                <div class="mb-10">
                    <h2 class="font-display text-2xl font-bold text-slate-900 mb-4">Paid Appointments</h2>
                    <ul class="space-y-3 pl-2">
                        <li class="flex items-start gap-3">
                            <span class="mt-2 w-1.5 h-1.5 bg-slate-900 rounded-full flex-shrink-0"></span>
                            <span class="leading-relaxed">Scheduling appointments with professionals <strong
                                    class="text-slate-900">requires payment</strong>.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-2 w-1.5 h-1.5 bg-slate-900 rounded-full flex-shrink-0"></span>
                            <span class="leading-relaxed">Fees are clearly displayed before you complete a
                                booking.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-2 w-1.5 h-1.5 bg-slate-900 rounded-full flex-shrink-0"></span>
                            <span class="leading-relaxed">Appointments are only confirmed after successful
                                payment.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-2 w-1.5 h-1.5 bg-slate-900 rounded-full flex-shrink-0"></span>
                            <span class="leading-relaxed">All payments are non-refundable unless otherwise stated in
                                individual service terms or based on the professional’s cancellation policy.</span>
                        </li>
                    </ul>
                </div>

                <div class="mb-10">
                    <h2 class="font-display text-2xl font-bold text-slate-900 mb-4">User Responsibilities</h2>
                    <p class="leading-relaxed mb-4">You agree to:</p>
                    <ul class="space-y-3 pl-2">
                        <li class="flex items-start gap-3">
                            <span class="mt-2 w-1.5 h-1.5 bg-slate-900 rounded-full flex-shrink-0"></span>
                            <span class="leading-relaxed">Provide accurate information when registering or
                                booking.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-2 w-1.5 h-1.5 bg-slate-900 rounded-full flex-shrink-0"></span>
                            <span class="leading-relaxed">Not misuse the platform for spam, fraud, or
                                impersonation.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-2 w-1.5 h-1.5 bg-slate-900 rounded-full flex-shrink-0"></span>
                            <span class="leading-relaxed">Honor the appointment you book, or cancel it
                                responsibly.</span>
                        </li>
                    </ul>
                </div>

                <div class="mb-10">
                    <h2 class="font-display text-2xl font-bold text-slate-900 mb-4">Limitation of Liability</h2>
                    <p class="leading-relaxed mb-4">
                        Keyperson.id is a platform facilitator. We do not control or guarantee the quality or conduct of
                        professionals listed on the platform. We are not responsible for:
                    </p>
                    <ul class="space-y-3 pl-2">
                        <li class="flex items-start gap-3">
                            <span class="mt-2 w-1.5 h-1.5 bg-slate-900 rounded-full flex-shrink-0"></span>
                            <span class="leading-relaxed">Missed or canceled appointments.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-2 w-1.5 h-1.5 bg-slate-900 rounded-full flex-shrink-0"></span>
                            <span class="leading-relaxed">Disputes between users and professionals.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-2 w-1.5 h-1.5 bg-slate-900 rounded-full flex-shrink-0"></span>
                            <span class="leading-relaxed">Calendar integration failures due to third-party system
                                issues.</span>
                        </li>
                    </ul>
                </div>

                <div class="space-y-8 mb-10">
                    <div>
                        <h2 class="font-display text-xl font-bold text-slate-900 mb-2">Termination</h2>
                        <p class="leading-relaxed">We reserve the right to suspend or delete accounts that violate our
                            Terms or abuse our services.</p>
                    </div>
                    <div>
                        <h2 class="font-display text-xl font-bold text-slate-900 mb-2">Intellectual Property</h2>
                        <p class="leading-relaxed">All content, logos, branding, and software are owned by PT Kastara
                            Prama Nusantara. Unauthorized use is prohibited.</p>
                    </div>
                    <div>
                        <h2 class="font-display text-xl font-bold text-slate-900 mb-2">Modifications</h2>
                        <p class="leading-relaxed">We may update these Terms at any time. Continued use of the platform
                            after changes means you accept the revised Terms.</p>
                    </div>
                </div>

                <div class="mb-12 bg-slate-50 p-6 rounded-2xl border border-slate-100">
                    <h2 class="font-display text-xl font-bold text-slate-900 mb-4">Contact Us</h2>
                    <p class="mb-2">If you have questions or feedback:</p>
                    <p class="font-bold text-slate-900 mb-4">PT Kastara Prama Nusantara</p>
                    <div class="flex items-center gap-2 text-violet-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <a href="mailto:kastaraparama.idn@gmail.com"
                            class="font-semibold hover:underline">kastaraparama.idn@gmail.com</a>
                    </div>
                </div>

                <div class="text-center pt-8 border-t border-slate-100">
                    <p class="text-slate-500 mb-3">If you have read and understood our Terms and Conditions</p>
                    <a :href="privacyUrl"
                        class="inline-flex items-center text-violet-600 font-bold hover:text-violet-700 hover:underline">
                        please continue to the Privacy Policy
                        <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>

            </div>
        </div>
    </div>
</template>