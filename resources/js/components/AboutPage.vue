<script setup>
const props = defineProps({
    registerClientUrl: String,
    registerExpertUrl: String
});

const values = [
    {
        title: 'Ease of Access',
        desc: 'Just sign in with your Google account and start scheduling instantly. No complex setups required.',
        icon: 'M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1',
        color: 'text-blue-600',
        bg: 'bg-blue-50'
    },
    {
        title: 'Transparency',
        desc: 'No hidden fees. Prices, schedules, and professional profiles are clearly displayed upfront.',
        icon: 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z',
        color: 'text-violet-600',
        bg: 'bg-violet-50'
    },
    {
        title: 'Privacy & Security',
        desc: 'Your data is encrypted and protected. We never share your personal information without explicit consent.',
        icon: 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z',
        color: 'text-emerald-600',
        bg: 'bg-emerald-50'
    }
];
</script>

<template>
    <div class="font-sans text-slate-600 bg-white">

        <section class="relative pt-24 pb-20 lg:pt-32 lg:pb-32 overflow-hidden">
            <div
                class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full max-w-7xl opacity-40 pointer-events-none">
                <div
                    class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-violet-200 rounded-full mix-blend-multiply filter blur-[100px] animate-blob">
                </div>
                <div
                    class="absolute top-0 right-1/4 w-[500px] h-[500px] bg-fuchsia-200 rounded-full mix-blend-multiply filter blur-[100px] animate-blob animation-delay-2000">
                </div>
            </div>

            <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-50 border border-slate-200 text-slate-600 text-xs font-bold uppercase tracking-wider mb-8">
                    <span class="w-2 h-2 rounded-full bg-violet-500"></span>
                    <span class="leading-none pt-[1px]">About Us</span>
                </div>

                <h1
                    class="font-display text-5xl md:text-6xl lg:text-7xl font-extrabold text-slate-900 tracking-tight mb-8 leading-[1.1]">
                    Empowering Growth through <br class="hidden md:block" />
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-violet-600 to-fuchsia-600">Meaningful
                        Connections.</span>
                </h1>

                <p class="text-xl md:text-2xl text-slate-500 max-w-2xl mx-auto mb-10 leading-relaxed font-light">
                    Keyperson.id is a smart platform connecting you with counselors, mentors, and coaches to support
                    your personal & professional journey.
                </p>

                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a :href="registerClientUrl"
                        class="inline-flex justify-center items-center px-8 py-4 text-base font-bold font-display text-white transition-all bg-slate-900 rounded-full hover:bg-slate-800 hover:shadow-lg hover:-translate-y-1">
                        Start as Organizer
                    </a>
                    <a :href="registerExpertUrl"
                        class="inline-flex justify-center items-center px-8 py-4 text-base font-bold font-display text-slate-700 transition-all bg-white border border-slate-200 rounded-full hover:border-violet-300 hover:bg-violet-50">
                        Join as Professional
                    </a>
                </div>

                <p class="mt-8 text-sm font-semibold text-slate-400 uppercase tracking-widest">
                    Built by PT. Kastara Parama Nusantara
                </p>
            </div>
        </section>

        <section class="py-20 lg:py-28 bg-slate-50 border-y border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <div>
                        <h2 class="font-display text-3xl font-bold text-slate-900 mb-6">
                            Simplifying how you find <br> the right person.
                        </h2>
                        <div class="space-y-6 text-lg text-slate-600 leading-relaxed">
                            <p>
                                We believe that finding a mentor or counselor shouldn't be complicated. Our goal is to
                                remove the friction of scheduling, payments, and administrative tasks.
                            </p>
                            <p>
                                With <span class="font-bold text-slate-900">KeyPerson</span>, appointments are scheduled
                                online and automatically synced to both your Google Calendar and the professional’s.
                                Simple, fast, and hassle-free.
                            </p>
                        </div>
                    </div>

                    <div class="relative">
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-violet-600 to-fuchsia-600 rounded-3xl transform rotate-3 opacity-20">
                        </div>
                        <div class="relative bg-white p-10 rounded-3xl shadow-xl border border-slate-100">
                            <svg class="w-10 h-10 text-violet-200 mb-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M14.017 21L14.017 18C14.017 16.0547 14.017 15.082 14.6025 14.4965C15.1881 13.9109 16.1607 13.9109 18.1061 13.9109H18.9897V11.9109C17.0444 11.9109 16.0717 11.9109 15.4862 11.3254C14.9006 10.7398 14.9006 9.76722 14.9006 7.82186V5.99996L9.84204 11.0001V13.9109H9.98972V18H14.017V21ZM5.01735 18H9.04473V13.9109H4.98975V11.0001L9.93121 5.99996H6.11029V7.82186C6.11029 9.76722 6.11029 10.7398 5.52476 11.3254C4.93922 11.9109 3.96659 11.9109 2.02123 11.9109V13.9109H2.90485C4.85022 13.9109 5.82285 13.9109 6.40838 14.4965C6.99392 15.082 6.99392 16.0547 6.99392 18V21H5.01735V18Z" />
                            </svg>
                            <h3 class="font-display text-2xl lg:text-3xl font-bold text-slate-900 leading-normal">
                                "To connect individuals and organizations with the right professionals, quickly and
                                efficiently."
                            </h3>
                            <div class="mt-8 flex items-center gap-3">
                                <div class="h-px flex-1 bg-slate-200"></div>
                                <span class="text-xs font-bold uppercase tracking-widest text-slate-400">Our
                                    Mission</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-24 bg-white relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-20">
                    <h2 class="font-display text-4xl font-bold text-slate-900 mb-6">Our Core Values</h2>
                    <p class="text-lg text-slate-500">
                        We build with principles that ensure trust, clarity, and ease for every user on our platform.
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div v-for="(val, idx) in values" :key="idx"
                        class="group relative bg-white rounded-[2rem] p-10 border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-500 overflow-hidden">
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-white via-white to-slate-50 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        </div>

                        <div class="relative z-10">
                            <div
                                :class="`w-16 h-16 ${val.bg} rounded-2xl flex items-center justify-center mb-8 group-hover:scale-110 transition-transform duration-500`">
                                <svg :class="`w-8 h-8 ${val.color}`" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        :d="val.icon" />
                                </svg>
                            </div>

                            <h3 class="font-display text-2xl font-bold text-slate-900 mb-4">{{ val.title }}</h3>
                            <p class="text-slate-600 leading-relaxed font-medium">
                                {{ val.desc }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-12 px-4 sm:px-6 lg:px-8 bg-white">
            <div class="max-w-7xl mx-auto bg-slate-900 rounded-[3rem] overflow-hidden relative shadow-2xl">
                <div class="absolute inset-0 opacity-20">
                    <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <path d="M0 100 C 20 0 50 0 100 100 Z" fill="url(#grad1)" />
                        <defs>
                            <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" style="stop-color:#7c3aed;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#c026d3;stop-opacity:1" />
                            </linearGradient>
                        </defs>
                    </svg>
                </div>

                <div class="relative z-10 px-8 py-20 md:p-24 grid lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <div
                            class="inline-block px-4 py-1 rounded-full border border-slate-700 bg-slate-800/50 text-slate-300 text-sm font-semibold mb-6">
                            The Team Behind
                        </div>
                        <h2 class="font-display text-4xl md:text-5xl font-bold text-white mb-8">
                            Who We Are
                        </h2>
                        <div class="space-y-6 text-lg text-slate-300 font-light">
                            <p>
                                Keyperson.id is proudly developed and managed by <br>
                                <span class="text-white font-bold text-2xl">PT Kastara Prama Nusantara</span>.
                            </p>
                            <p class="opacity-80">
                                We are a technology team committed to building impactful digital solutions, particularly
                                in the areas of human development, education, and professional services.
                            </p>
                        </div>
                    </div>

                    <div class="hidden lg:flex justify-end">
                        <div
                            class="w-64 h-64 border-4 border-slate-800 rounded-full flex items-center justify-center relative">
                            <div
                                class="absolute inset-0 rounded-full border border-slate-700 animate-[spin_10s_linear_infinite]">
                            </div>
                            <div class="text-center">
                                <span class="block text-5xl font-bold text-white mb-2 font-display">100%</span>
                                <span class="text-slate-400 text-sm uppercase tracking-widest">Commitment</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<style scoped>
.animate-blob {
    animation: blob 10s infinite;
}

.animation-delay-2000 {
    animation-delay: 5s;
}

@keyframes blob {
    0% {
        transform: translate(0px, 0px) scale(1);
    }

    33% {
        transform: translate(30px, -50px) scale(1.1);
    }

    66% {
        transform: translate(-20px, 20px) scale(0.9);
    }

    100% {
        transform: translate(0px, 0px) scale(1);
    }
}
</style>