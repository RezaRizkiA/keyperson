<script setup>
const props = defineProps({
    termsUrl: String
});
</script>

<template>
    <div class="bg-slate-50 min-h-screen font-sans text-slate-600 pt-24 pb-20">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-12">
                <h1 class="font-display text-4xl md:text-5xl font-bold text-slate-900 mb-4">Privacy Policy</h1>
                <span
                    class="inline-block px-3 py-1 bg-white border border-slate-200 rounded-full text-sm font-medium text-slate-500 shadow-sm">
                    Effective Date: July 22, 2025
                </span>
            </div>

            <div class="bg-white p-8 md:p-12 rounded-2xl shadow-sm border border-slate-200">

                <p class="text-lg leading-relaxed mb-8">
                    Welcome to <strong class="text-slate-900">Keyperson.id</strong>, a platform operated by <strong
                        class="text-slate-900">PT Kastara Prama Nusantara</strong> ("we", "us", or "our"). Your privacy
                    is important to us. This Privacy Policy explains how we collect, use, disclose, and protect your
                    personal information when you use our service.
                </p>

                <hr class="my-8 border-slate-100">

                <div class="mb-10">
                    <h2 class="font-display text-2xl font-bold text-slate-900 mb-4">Information We Collect</h2>
                    <p class="leading-relaxed mb-4">When you use our service, we may collect the following data:</p>
                    <ul class="space-y-3 pl-2">
                        <li class="flex items-start gap-3">
                            <span class="mt-2 w-1.5 h-1.5 bg-slate-900 rounded-full flex-shrink-0"></span>
                            <span class="leading-relaxed">Your name, email, and profile picture from your Google account
                                (after login).</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-2 w-1.5 h-1.5 bg-slate-900 rounded-full flex-shrink-0"></span>
                            <span class="leading-relaxed">Appointment data created or received via our platform.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-2 w-1.5 h-1.5 bg-slate-900 rounded-full flex-shrink-0"></span>
                            <span class="leading-relaxed">Google Calendar access (used to schedule appointments on your
                                behalf).</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-2 w-1.5 h-1.5 bg-slate-900 rounded-full flex-shrink-0"></span>
                            <span class="leading-relaxed">Payment and transaction details related to booking
                                appointments.</span>
                        </li>
                    </ul>
                </div>

                <div class="mb-10">
                    <h2 class="font-display text-2xl font-bold text-slate-900 mb-4">Use of Your Information</h2>
                    <p class="leading-relaxed mb-4">We use your data to:</p>
                    <ul class="grid sm:grid-cols-2 gap-3">
                        <li class="flex items-center gap-2 p-3 bg-slate-50 rounded-lg border border-slate-100">
                            <svg class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-sm">Authenticate via Google</span>
                        </li>
                        <li class="flex items-center gap-2 p-3 bg-slate-50 rounded-lg border border-slate-100">
                            <svg class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-sm">Schedule appointments</span>
                        </li>
                        <li class="flex items-center gap-2 p-3 bg-slate-50 rounded-lg border border-slate-100">
                            <svg class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-sm">Sync Google Calendar</span>
                        </li>
                        <li class="flex items-center gap-2 p-3 bg-slate-50 rounded-lg border border-slate-100">
                            <svg class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-sm">Process payments securely</span>
                        </li>
                        <li
                            class="flex items-center gap-2 p-3 bg-slate-50 rounded-lg border border-slate-100 sm:col-span-2">
                            <svg class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-sm">Provide support & improve services</span>
                        </li>
                    </ul>
                </div>

                <div class="mb-10">
                    <h2 class="font-display text-2xl font-bold text-slate-900 mb-4">Payment Information</h2>
                    <p
                        class="leading-relaxed mb-4 bg-orange-50 text-orange-800 p-4 rounded-xl border border-orange-100 text-sm">
                        <span class="font-bold">Important:</span> We do not store your credit/debit card or bank account
                        numbers. Payments are handled securely by our authorized third-party payment processor.
                    </p>
                    <p class="leading-relaxed mb-2">We may store:</p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Payment history</li>
                        <li>Transaction amount</li>
                        <li>Timestamp of transaction</li>
                    </ul>
                </div>

                <div class="mb-10">
                    <h2 class="font-display text-2xl font-bold text-slate-900 mb-4">Sharing of Information</h2>
                    <p class="leading-relaxed mb-4">Your personal data is never sold or rented. It may only be shared:
                    </p>
                    <ul class="space-y-3 pl-2">
                        <li class="flex items-start gap-3">
                            <span class="mt-2 w-1.5 h-1.5 bg-slate-900 rounded-full flex-shrink-0"></span>
                            <span class="leading-relaxed">With your consent</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-2 w-1.5 h-1.5 bg-slate-900 rounded-full flex-shrink-0"></span>
                            <span class="leading-relaxed">To comply with legal requirements</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-2 w-1.5 h-1.5 bg-slate-900 rounded-full flex-shrink-0"></span>
                            <span class="leading-relaxed">With our service providers for technical or security
                                purposes</span>
                        </li>
                    </ul>
                </div>

                <div class="space-y-8 mb-10">
                    <div>
                        <h2 class="font-display text-xl font-bold text-slate-900 mb-2">Security</h2>
                        <p class="leading-relaxed">We apply industry-standard encryption and security practices to
                            protect your personal information from unauthorized access.</p>
                    </div>
                    <div>
                        <h2 class="font-display text-xl font-bold text-slate-900 mb-2">Your Rights</h2>
                        <p class="leading-relaxed mb-2">You can:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>View and edit your account data</li>
                            <li>Delete your account upon request</li>
                            <li>Revoke access from your Google Account settings</li>
                        </ul>
                    </div>
                    <div>
                        <h2 class="font-display text-xl font-bold text-slate-900 mb-2">Third-Party Services</h2>
                        <p class="leading-relaxed mb-2">Our application integrates with:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Google Calendar API</li>
                            <li>Google OAuth (Sign-in)</li>
                            <li>Third-party payment gateway</li>
                        </ul>
                        <p class="mt-2 text-sm text-slate-500">Use of these services is governed by their own privacy
                            policies.</p>
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
                    <p class="text-slate-500 mb-3">If you have read and understood our Privacy Policy</p>
                    <a :href="termsUrl"
                        class="inline-flex items-center text-violet-600 font-bold hover:text-violet-700 hover:underline">
                        please proceed to the Terms and Conditions
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