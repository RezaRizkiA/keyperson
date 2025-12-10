<script setup>
import { ref } from 'vue';

const faqs = [
    {
        question: 'Apakah aplikasi ini gratis?',
        answer: 'Kami menawarkan paket gratis untuk pemula. Untuk fitur advanced seperti pembayaran otomatis dan integrasi kalender penuh, ada biaya layanan kecil per transaksi.'
    },
    {
        question: 'Bagaimana sistem pembayarannya?',
        answer: 'Klien membayar saat booking menggunakan QRIS, E-Wallet, atau Transfer Bank. Dana akan diteruskan ke akun Anda setelah sesi selesai.'
    },
    {
        question: 'Apakah bisa reschedule jadwal?',
        answer: 'Ya, Anda bisa mengatur kebijakan reschedule sendiri. Klien dapat mengubah jadwal sesuai ketersediaan waktu Anda.'
    },
    {
        question: 'Apakah mendukung video call?',
        answer: 'Tentu. Link Google Meet atau Zoom akan digenerate otomatis dan dikirim ke email kedua belah pihak.'
    }
];

const openIndex = ref(0); // Buka yang pertama secara default
</script>

<template>
    <section class="py-20 bg-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6">
            <h2 class="text-3xl font-bold text-center text-slate-900 mb-12 font-display">Pertanyaan yang Sering Diajukan
            </h2>

            <div class="space-y-4">
                <div v-for="(faq, index) in faqs" :key="index"
                    class="border border-slate-200 rounded-xl overflow-hidden">
                    <button @click="openIndex = openIndex === index ? null : index"
                        class="flex items-center justify-between w-full p-5 text-left bg-white hover:bg-slate-50 transition-colors">
                        <span class="font-bold text-slate-800">{{ faq.question }}</span>
                        <svg class="w-5 h-5 text-slate-500 transform transition-transform duration-300"
                            :class="openIndex === index ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div v-show="openIndex === index"
                        class="p-5 pt-0 bg-white text-slate-600 leading-relaxed border-t border-slate-100">
                        <div class="mt-3">{{ faq.answer }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>