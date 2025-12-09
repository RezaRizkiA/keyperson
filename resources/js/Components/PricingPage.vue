<script setup>
const props = defineProps({
    registerUrl: String
});
</script>

<template>
    <div class="bg-white font-sans text-slate-600">

        <section class="pt-24 pb-16 lg:pt-32 lg:pb-24 text-center px-4">
            <div
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-50 border border-slate-200 text-violet-700 text-xs font-bold uppercase tracking-wider mb-6">
                Simple & Transparent
            </div>
            <h1 class="font-display text-4xl md:text-5xl lg:text-6xl font-bold text-slate-900 mb-6 tracking-tight">
                No subscription fees. <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-600 to-fuchsia-600">Pay only
                    when you book.</span>
            </h1>
            <p class="text-xl text-slate-500 max-w-2xl mx-auto leading-relaxed">
                Join Keyperson.id for free. Browse professionals, check their schedules, and only pay the session fee
                when you confirm a booking.
            </p>
        </section>

        <section class="pb-24 px-4 sm:px-6 lg:px-8">
            <div class="max-w-5xl mx-auto grid md:grid-cols-2 gap-8 items-start">

                <div class="p-8 rounded-3xl border border-slate-200 bg-slate-50">
                    <h3 class="font-display text-2xl font-bold text-slate-900 mb-2">Explorer</h3>
                    <p class="text-slate-500 mb-6 h-12">Perfect for browsing and finding the right match.</p>

                    <div class="flex items-baseline gap-1 mb-8">
                        <span class="text-4xl font-bold text-slate-900">Free</span>
                        <span class="text-slate-500">/ forever</span>
                    </div>

                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Browse all professional profiles</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span>View availability & schedules</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Read reviews and ratings</span>
                        </li>
                    </ul>
                </div>

                <div
                    class="relative p-8 rounded-3xl border border-violet-100 bg-white shadow-xl shadow-violet-100 ring-1 ring-violet-200">
                    <div
                        class="absolute top-0 right-0 bg-violet-600 text-white text-xs font-bold px-3 py-1 rounded-bl-xl rounded-tr-2xl">
                        POPULAR
                    </div>

                    <h3 class="font-display text-2xl font-bold text-slate-900 mb-2">Client</h3>
                    <p class="text-slate-500 mb-6 h-12">Ready to grow and schedule sessions.</p>

                    <div class="flex items-baseline gap-1 mb-8">
                        <span class="text-4xl font-bold text-slate-900">Rp 0</span>
                        <span class="text-slate-500">/ month</span>
                    </div>
                    <p
                        class="text-sm text-slate-500 -mt-6 mb-8 bg-violet-50 p-2 rounded-lg border border-violet-100 inline-block">
                        Pay per session (price set by professional)
                    </p>

                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="font-semibold text-slate-900">Everything in Explorer</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Book unlimited appointments</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Google Calendar Sync</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Secure Payment Gateway</span>
                        </li>
                    </ul>

                    <a :href="registerUrl"
                        class="block w-full text-center py-4 rounded-xl bg-violet-600 text-white font-bold hover:bg-violet-700 hover:shadow-lg transition-all">
                        Register for Free
                    </a>
                </div>

            </div>

            <div class="mt-16 text-center border-t border-slate-100 pt-10">
                <p class="text-sm text-slate-400 font-medium uppercase tracking-widest mb-6">Supported Payment Methods
                </p>
                <div
                    class="flex flex-wrap justify-center gap-8 opacity-60 grayscale hover:grayscale-0 transition-all duration-500">
                    <span class="font-display font-bold text-slate-600 text-xl">QRIS</span>
                    <span class="font-display font-bold text-slate-600 text-xl">BCA</span>
                    <span class="font-display font-bold text-slate-600 text-xl">Mandiri</span>
                    <span class="font-display font-bold text-slate-600 text-xl">GoPay</span>
                    <span class="font-display font-bold text-slate-600 text-xl">OVO</span>
                </div>
            </div>
        </section>

    </div>
</template>