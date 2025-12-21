<script setup>
import { Head, usePage, Link } from "@inertiajs/vue3";
import MainLayout from "../Layouts/MainLayout.vue";
import { route } from "ziggy-js";

import {
  Calendar,
  Video,
  CheckCircle,
  BarChart3,
  Globe,
  Bell,
  CreditCard,
  ArrowRight,
  ShieldCheck,
  BadgeCheck, // <-- Tambahan Import Icon
} from "lucide-vue-next";

// Props dari Controller
defineProps({
  hero: Object,
  features: Array,
  bentoFeatures: Array,
});

const { props } = usePage();
const routes = props.routes;

// Helper icon mapping (Wajib sinkron dengan Controller)
const iconMap = {
  calendar: Calendar,
  "badge-check": BadgeCheck, // <-- Icon Baru
  "shield-check": ShieldCheck, // <-- Icon Baru
  video: Video, // <-- Icon Baru
  "chart-bar": BarChart3,
  globe: Globe,
  bell: Bell,
  "credit-card": CreditCard,
};
</script>

<template>
  <Head title="Smart Scheduling for Professionals" />

  <MainLayout>
    <div class="bg-white text-slate-900 font-sans selection:bg-violet-100">
      <section class="pt-32 pb-20 px-6 text-center max-w-5xl mx-auto">
        <div
          class="inline-block mb-6 px-4 py-1.5 rounded-full bg-violet-50 text-violet-700 font-bold text-xs tracking-wide uppercase">
          New: Group Booking Available
        </div>
        <h1
          class="text-5xl md:text-7xl font-bold tracking-tight text-slate-900 mb-8 leading-[1.1]">
          {{ hero.title }}
        </h1>
        <p
          class="text-xl text-slate-500 mb-10 max-w-2xl mx-auto leading-relaxed">
          {{ hero.subtitle }}
        </p>
        <div
          class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16">
          <Link
            :href="routes.register_client"
            class="px-8 py-4 bg-violet-600 text-white rounded-xl font-bold text-lg hover:bg-violet-700 hover:shadow-lg hover:shadow-violet-200 transition-all flex items-center justify-center gap-2">
            Untuk Perusahaan <ArrowRight class="w-5 h-5" />
          </Link>
          <Link
            :href="route('expert_create')"
            class="px-8 py-4 bg-white text-slate-700 border border-slate-200 rounded-xl font-bold text-lg hover:bg-slate-50 transition-all">
            Gabung Expert
          </Link>
        </div>

        <div
          class="relative mx-auto max-w-4xl rounded-3xl overflow-hidden shadow-2xl border border-slate-100 bg-slate-50 aspect-[16/9] group">
          <div
            class="absolute inset-0 flex items-center justify-center text-slate-300">
            <div
              class="w-full h-full bg-linear-to-br from-white to-slate-100 p-8">
              <div
                class="w-full h-full border-2 border-dashed border-slate-200 rounded-xl flex items-center justify-center">
                <span class="font-medium">Dashboard Overview Mockup Area</span>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="py-24 px-6 overflow-hidden">
        <div
          v-for="(feature, index) in features"
          :key="index"
          class="max-w-6xl mx-auto flex flex-col md:flex-row items-center gap-16 mb-32 last:mb-0"
          :class="{ 'md:flex-row-reverse': feature.align === 'left' }">
          <div class="flex-1 space-y-6 text-center md:text-left">
            <h2
              class="text-3xl md:text-5xl font-bold text-slate-900 leading-tight">
              {{ feature.title }}
            </h2>
            <p class="text-lg text-slate-500 leading-relaxed">
              {{ feature.description }}
            </p>
            <ul
              v-if="index === 0"
              class="space-y-3 pt-4 inline-block text-left">
              <li class="flex items-center gap-3 text-slate-700 font-medium">
                <CheckCircle class="w-5 h-5 text-emerald-500" /> Instant Booking
              </li>
              <li class="flex items-center gap-3 text-slate-700 font-medium">
                <CheckCircle class="w-5 h-5 text-emerald-500" /> GCalendar Sync
              </li>
            </ul>
          </div>

          <div class="flex-1 w-full relative">
            <div
              class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] bg-gradient-to-tr from-violet-100/50 to-fuchsia-50/50 rounded-full blur-3xl -z-10"></div>

            <div
              v-if="feature.image === 'calendar_ui'"
              class="bg-white rounded-2xl shadow-xl border border-slate-100 p-6 rotate-1 hover:rotate-0 transition-transform duration-500">
              <div class="flex justify-between items-center mb-6">
                <div class="font-bold text-lg">September 2025</div>
                <div class="flex gap-2">
                  <div class="w-8 h-8 rounded-full bg-slate-100"></div>
                  <div
                    class="w-8 h-8 rounded-full bg-violet-100 text-violet-600 flex items-center justify-center font-bold text-xs">
                    +3
                  </div>
                </div>
              </div>
              <div
                class="grid grid-cols-7 gap-2 text-center text-sm text-slate-400 mb-2">
                <span>M</span><span>T</span><span>W</span><span>T</span
                ><span>F</span><span>S</span><span>S</span>
              </div>
              <div class="grid grid-cols-7 gap-2">
                <div
                  v-for="n in 31"
                  :key="n"
                  class="aspect-square rounded-lg flex items-center justify-center text-sm"
                  :class="[
                    n === 12
                      ? 'bg-violet-600 text-white shadow-lg shadow-violet-200'
                      : n > 12 && n < 16
                      ? 'bg-violet-50 text-violet-600'
                      : 'text-slate-600 hover:bg-slate-50',
                  ]">
                  {{ n }}
                </div>
              </div>
              <div
                class="absolute -bottom-6 -left-6 bg-white p-4 rounded-xl shadow-lg border border-slate-100 flex items-center gap-3 animate-bounce"
                style="animation-duration: 3s">
                <div
                  class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                  <Video class="w-5 h-5" />
                </div>
                <div>
                  <div class="text-xs font-bold text-slate-400">
                    Next Meeting
                  </div>
                  <div class="font-bold text-slate-900">10:00 AM</div>
                </div>
              </div>
            </div>

            <div
              v-if="feature.image === 'dashboard_ui'"
              class="bg-white rounded-2xl shadow-xl border border-slate-100 p-6 -rotate-1 hover:rotate-0 transition-transform duration-500">
              <div class="flex gap-4 mb-6">
                <div class="flex-1 bg-violet-50 p-4 rounded-xl">
                  <div class="text-xs text-violet-600 font-bold uppercase">
                    Total Sesi
                  </div>
                  <div class="text-2xl font-bold text-slate-900">1,240</div>
                </div>
                <div class="flex-1 bg-emerald-50 p-4 rounded-xl">
                  <div class="text-xs text-emerald-600 font-bold uppercase">
                    Kepuasan
                  </div>
                  <div class="text-2xl font-bold text-slate-900">4.9/5</div>
                </div>
              </div>
              <div class="space-y-3">
                <div
                  class="h-2 bg-slate-100 rounded-full w-full overflow-hidden">
                  <div class="h-full bg-violet-500 w-3/4"></div>
                </div>
                <div class="flex justify-between text-xs text-slate-500">
                  <span>Budget Utilization</span>
                  <span>75%</span>
                </div>
              </div>
            </div>

            <div
              v-if="feature.image === 'meeting_ui'"
              class="bg-slate-900 rounded-2xl shadow-2xl p-4 rotate-1 hover:rotate-0 transition-transform duration-500 text-white">
              <div
                class="aspect-video bg-slate-800 rounded-xl mb-4 relative overflow-hidden">
                <div class="absolute inset-0 flex items-center justify-center">
                  <div
                    class="w-20 h-20 rounded-full bg-slate-700 flex items-center justify-center">
                    <span class="font-bold text-2xl">JS</span>
                  </div>
                </div>
                <div
                  class="absolute bottom-4 left-4 bg-black/50 px-3 py-1 rounded-lg text-sm backdrop-blur-sm">
                  Expert John
                </div>
              </div>
              <div class="flex gap-4">
                <div
                  class="flex-1 h-24 bg-slate-800 rounded-xl relative overflow-hidden">
                  <div
                    class="absolute bottom-2 left-2 text-xs bg-black/50 px-2 py-0.5 rounded">
                    You
                  </div>
                </div>
                <div
                  class="flex-1 h-24 bg-slate-800 rounded-xl flex items-center justify-center">
                  <span class="text-slate-500 text-xs">+2 Others</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="py-24 bg-slate-50 px-6">
        <div class="max-w-6xl mx-auto">
          <div class="text-center max-w-2xl mx-auto mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">
              Semua Fitur yang Anda Butuhkan
            </h2>
            <p class="text-slate-500">
              Platform lengkap untuk operasional konsultasi Anda.
            </p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div
              v-for="(bento, i) in bentoFeatures"
              :key="i"
              class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
              <div
                :class="`w-12 h-12 rounded-2xl flex items-center justify-center mb-6 ${bento.color}`">
                <component :is="iconMap[bento.icon]" class="w-6 h-6" />
              </div>
              <h3 class="text-xl font-bold text-slate-900 mb-2">
                {{ bento.title }}
              </h3>
              <p class="text-slate-500 text-sm leading-relaxed mb-4">
                {{ bento.desc }}
              </p>

              <div
                class="flex items-center gap-2 text-sm font-bold opacity-0 group-hover:opacity-100 transition-opacity"
                :class="bento.color.split(' ')[1]">
                Learn more <ArrowRight class="w-4 h-4" />
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="py-32 px-6 text-center">
        <div class="max-w-4xl mx-auto">
          <h2
            class="text-4xl md:text-6xl font-bold text-slate-900 mb-8 tracking-tight">
            Wujudkan potensi
            <span
              class="text-transparent bg-clip-text bg-gradient-to-r from-violet-600 to-fuchsia-600"
              >bisnis Anda.</span
            >
          </h2>
          <p class="text-xl text-slate-500 mb-12">
            Satu platform, nol kerumitan. Bergabung dengan 15.000+ expert
            lainnya.
          </p>

          <Link
            :href="routes.register_expert"
            class="inline-flex items-center gap-3 px-10 py-5 bg-slate-900 text-white rounded-full font-bold text-xl hover:bg-slate-800 hover:scale-105 transition-all shadow-2xl">
            Daftar Sekarang - Gratis
            <ArrowRight class="w-6 h-6" />
          </Link>
        </div>
      </section>
    </div>
  </MainLayout>
</template>
