<script setup>
import { ref } from 'vue';

// Data FAQ (Hardcoded dari blade lama, tapi dalam struktur Array agar bersih)
const faqs = [
    {
        question: 'What is Keyperson.id?',
        answer: 'Keyperson.id is a platform that helps you find and book appointments with professionals like counselors, mentors, and teachers — all appointments are automatically synced with your Google Calendar.'
    },
    {
        question: 'Do I need a Google account to use Keyperson.id?',
        answer: 'Yes. You must sign in with your Google account to book appointments, as our system uses Google Calendar to manage and sync your bookings.'
    },
    {
        question: 'Is there a cost to use the platform?',
        answer: 'Browsing professionals is free, but booking an appointment requires payment. Prices are shown clearly before confirming any appointment.'
    },
    {
        question: 'How does the appointment system work?',
        answer: 'Once you find the right professional, you can choose an available time slot and confirm the appointment. It will automatically appear in both your Google Calendar and the professional’s.'
    },
    {
        question: 'Can I cancel or reschedule an appointment?',
        answer: 'Yes, but it depends on the cancellation policy of each professional. You’ll find the reschedule/cancel options in your dashboard after booking.'
    },
    {
        question: 'Who should I contact if I need help?',
        answer: 'You can reach out to our support team via WhatsApp or Email using the buttons above. We are happy to help!'
    }
];

// State untuk Accordion (Menyimpan index yang sedang terbuka)
const openIndex = ref(0); // Default terbuka yang pertama

const toggleFaq = (index) => {
    openIndex.value = openIndex.value === index ? null : index;
};
</script>

<template>
    <div class="font-sans text-slate-600 bg-white">

        <section class="relative pt-24 pb-20 lg:pt-32 lg:pb-24 overflow-hidden bg-slate-50">
            <div
                class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/4 w-[500px] h-[500px] bg-violet-100 rounded-full mix-blend-multiply filter blur-[80px] opacity-60 pointer-events-none">
            </div>
            <div
                class="absolute bottom-0 left-0 translate-y-1/2 -translate-x-1/4 w-[500px] h-[500px] bg-fuchsia-100 rounded-full mix-blend-multiply filter blur-[80px] opacity-60 pointer-events-none">
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">

                    <div>
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white border border-slate-200 text-violet-700 text-xs font-bold uppercase tracking-wider mb-6 shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-violet-500 animate-pulse"></span>
                            <span class="leading-none pt-[1px]">Support Center</span>
                        </div>
                        <h1 class="font-display text-4xl lg:text-5xl font-extrabold text-slate-900 mb-6 leading-tight">
                            We’re here to <br>
                            <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-violet-600 to-fuchsia-600">help
                                you grow.</span>
                        </h1>
                        <div class="space-y-4 text-lg text-slate-600 leading-relaxed mb-8">
                            <p>
                                We are a passionate team from <strong class="text-slate-900">PT Kastara Prama
                                    Nusantara</strong>, dedicated to building meaningful digital solutions for people
                                development.
                            </p>
                            <p>
                                We believe in the power of connection to unlock potential and create positive change.
                            </p>
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <a href="https://wa.me/6282299668860?text=Nama%20%3A%0AEmail%20%3A%0ASupport%20%3A"
                            target="_blank"
                            class="group bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-lg hover:border-violet-200 transition-all duration-300">
                            <div
                                class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-green-600 group-hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                                </svg>
                            </div>
                            <h3 class="font-display font-bold text-slate-900 text-lg mb-1">Chat via WhatsApp</h3>
                            <p class="text-sm text-slate-500 mb-4">Fastest response time.</p>
                            <span class="text-green-600 font-semibold text-sm flex items-center group-hover:underline">
                                Start Chat <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </span>
                        </a>

                        <a href="mailto:kastaraparama.idn@gmail.com?subject=Support%20Request&body=Nama%20:%0AEmail%20:%0ASupport%20:"
                            class="group bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-lg hover:border-violet-200 transition-all duration-300">
                            <div
                                class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="font-display font-bold text-slate-900 text-lg mb-1">Send Email</h3>
                            <p class="text-sm text-slate-500 mb-4">For detailed inquiries.</p>
                            <span class="text-blue-600 font-semibold text-sm flex items-center group-hover:underline">
                                Write Message <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </span>
                        </a>
                    </div>

                </div>
            </div>
        </section>

        <section class="py-20 lg:py-28 bg-white">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="font-display text-3xl md:text-4xl font-bold text-slate-900 mb-4">Frequently Asked
                        Questions</h2>
                    <p class="text-slate-500 text-lg">Everything you need to know about the platform.</p>
                </div>

                <div class="space-y-4">
                    <div v-for="(faq, index) in faqs" :key="index"
                        class="border border-slate-200 rounded-2xl overflow-hidden transition-all duration-300"
                        :class="openIndex === index ? 'bg-slate-50 border-violet-200 shadow-sm' : 'bg-white hover:border-slate-300'">
                        <button @click="toggleFaq(index)"
                            class="w-full flex justify-between items-center px-6 py-5 text-left focus:outline-none">
                            <span class="font-display font-semibold text-lg text-slate-900">
                                {{ faq.question }}
                            </span>
                            <span
                                class="flex-shrink-0 ml-4 w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center transition-transform duration-300"
                                :class="openIndex === index ? 'rotate-180 text-violet-600 border-violet-200' : 'text-slate-400'">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </span>
                        </button>

                        <div v-show="openIndex === index" class="px-6 pb-6 pt-0">
                            <p class="text-slate-600 leading-relaxed border-t border-slate-200/60 pt-4">
                                {{ faq.answer }}
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
</template>