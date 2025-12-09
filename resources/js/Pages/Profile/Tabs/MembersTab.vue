<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const assets = computed(() => usePage().props.assets);
</script>

<template>
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-slate-900">User Members</h3>
            <span
                class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-xs font-bold"
                >20</span
            >
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Dummy Member -->
            <div
                class="flex items-center gap-4 p-4 rounded-2xl border border-slate-100 hover:border-violet-200 transition-colors"
            >
                <img
                    :src="assets.userPlaceholderUrl"
                    alt="User"
                    class="w-10 h-10 rounded-full"
                />
                <div>
                    <h5 class="font-bold text-slate-900 text-sm">
                        Ibnu Haris Al Mutaqin
                    </h5>
                    <p class="text-xs text-slate-500">al.ibnuharis@gmail.com</p>
                </div>
            </div>
        </div>
    </div>
</template>
