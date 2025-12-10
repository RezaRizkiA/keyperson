<script setup>
import { ref } from 'vue';

const faqs = [
    {
        question: 'Apa itu Keyperson.id?',
        answer: 'Platform yang membantu Anda menemukan dan memesan jadwal konsultasi dengan profesional (konselor, mentor, guru) secara mudah. Semua jadwal otomatis tersinkronisasi dengan Google Calendar.'
    },
    {
        question: 'Apakah saya butuh akun Google?',
        answer: 'Ya. Anda perlu masuk menggunakan akun Google untuk memesan jadwal. Sistem kami menggunakan Google Calendar untuk memastikan tidak ada jadwal yang bentrok.'
    },
    {
        question: 'Apakah ada biaya penggunaan?',
        answer: 'Mendaftar dan mencari profesional itu GRATIS. Anda hanya membayar biaya sesi konsultasi sesuai tarif yang ditentukan oleh masing-masing profesional.'
    },
    {
        question: 'Bagaimana jika saya ingin membatalkan jadwal?',
        answer: 'Bisa. Kebijakan pembatalan tergantung pada masing-masing profesional. Tombol reschedule/cancel tersedia di dashboard Anda setelah booking berhasil.'
    }
];

const openIndex = ref(0);

const toggleFaq = (index) => {
    openIndex.value = openIndex.value === index ? null : index;
};
</script>

<template>
    <section class="py-20 lg:py-28 bg-white font-sans">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="font-display text-3xl md:text-4xl font-bold text-slate-900 mb-4">
                    Pertanyaan yang Sering Diajukan
                </h2>
                <p class="text-slate-500 text-lg">
                    Hal-hal yang perlu Anda ketahui sebelum memulai.
                </p>
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
                            class="shrink-0 ml-4 w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center transition-transform duration-300"
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
</template>