<script setup>
const steps = [
    {
        num: '01',
        title: 'Buat Profil & Atur Jadwal',
        desc: 'Tentukan kapan Anda tersedia. Hubungkan Google Calendar agar tidak bentrok.'
    },
    {
        num: '02',
        title: 'Bagikan Link Booking',
        desc: 'Kirim link profil Anda ke klien via WhatsApp, Instagram, atau Email.'
    },
    {
        num: '03',
        title: 'Klien Memilih & Bayar',
        desc: 'Klien memilih slot waktu yang kosong dan melakukan pembayaran aman.'
    },
    {
        num: '04',
        title: 'Sesi Dimulai',
        desc: 'Link video meeting (Zoom/GMeet) otomatis terkirim ke email Anda dan Klien.'
    }
];
</script>

<template>
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-display font-bold text-slate-900">Cara Kerja MeetPro</h2>
                <p class="mt-4 text-slate-600">Hanya butuh 2 menit untuk mulai menerima booking.</p>
            </div>

            <div class="grid md:grid-cols-4 gap-8">
                <div v-for="(step, index) in steps" :key="index" class="relative group">
                    <div v-if="index !== steps.length - 1"
                        class="hidden md:block absolute top-8 left-1/2 w-full h-0.5 bg-slate-100 -z-10"></div>

                    <div class="flex flex-col items-center text-center">
                        <div
                            class="w-16 h-16 rounded-2xl bg-violet-50 text-violet-600 font-bold text-xl flex items-center justify-center mb-6 shadow-sm group-hover:bg-violet-600 group-hover:text-white transition-colors duration-300">
                            {{ step.num }}
                        </div>
                        <h3 class="font-bold text-slate-900 text-lg mb-3">{{ step.title }}</h3>
                        <p class="text-sm text-slate-500 leading-relaxed px-2">{{ step.desc }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>