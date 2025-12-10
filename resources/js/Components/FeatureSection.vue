<script setup>
// Kita menggunakan gambar statis dari folder public Anda
const features = [
    {
        title: 'Sinkronisasi Kalender Otomatis',
        desc: 'Jangan pernah khawatir jadwal bentrok lagi. keyPerson tersambung langsung dengan Google Calendar Anda secara 2 arah (Two-way sync).',
        image: '/assets/images/apps/app-calendar.jpg', // Menggunakan file yang ada
        align: 'left'
    },
    {
        title: 'Pembayaran Terintegrasi',
        desc: 'Terima pembayaran sebelum sesi dimulai. Mendukung QRIS, Virtual Account, dan E-Wallet. Dana langsung masuk ke dashboard Anda.',
        image: '/assets/images/products/payment.svg', // Menggunakan file yang ada
        align: 'right'
    },
    {
        title: 'Dashboard Manajemen Lengkap',
        desc: 'Pantau semua janji temu, pendapatan, dan data klien dalam satu dashboard yang intuitif dan mudah digunakan.',
        image: '/assets/images/dashboards/dashboard1.js', // Sepertinya ini JS, kita pakai gambar placeholder dashboard
        // Ganti path di bawah jika Anda punya screenshot dashboard yang lebih bagus
        image: '/assets/images/apps/app-ecommerce-list.jpg',
        align: 'left'
    }
];
</script>

<template>
    <section class="py-20 bg-slate-50 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <h2 class="font-display text-3xl md:text-4xl font-bold text-slate-900 mb-4">
                    Semua yang Anda butuhkan untuk layanan profesional
                </h2>
                <p class="text-slate-600 text-lg">
                    Fokus pada klien Anda, biarkan keyPerson menangani administrasi, pembayaran, dan teknis lainnya.
                </p>
            </div>

            <div class="space-y-24">
                <div v-for="(feature, index) in features" :key="index"
                    class="flex flex-col lg:flex-row items-center gap-12 lg:gap-20">

                    <div class="w-full lg:w-1/2" :class="feature.align === 'right' ? 'lg:order-2' : ''">
                        <div class="relative rounded-2xl bg-white p-2 shadow-xl border border-slate-200">
                            <div class="absolute -top-4 -right-4 w-24 h-24 bg-dots-pattern opacity-20"></div>
                            <img :src="feature.image" :alt="feature.title"
                                class="rounded-xl w-full h-auto object-cover shadow-inner bg-slate-100">
                        </div>
                    </div>

                    <div class="w-full lg:w-1/2" :class="feature.align === 'right' ? 'lg:order-1' : ''">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="w-12 h-1 bg-violet-600 rounded-full"></span>
                            <span class="text-violet-600 font-bold uppercase tracking-wider text-sm">Fitur
                                Unggulan</span>
                        </div>
                        <h3 class="text-3xl font-bold text-slate-900 mb-6 font-display">{{ feature.title }}</h3>
                        <p class="text-lg text-slate-600 leading-relaxed mb-8">
                            {{ feature.desc }}
                        </p>
                        <ul class="space-y-3">
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-green-500 mt-1" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-slate-700">Mudah digunakan di Mobile & Desktop</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-green-500 mt-1" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-slate-700">Notifikasi Real-time via Email</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.bg-dots-pattern {
    background-image: radial-gradient(#6366f1 1px, transparent 1px);
    background-size: 10px 10px;
}
</style>