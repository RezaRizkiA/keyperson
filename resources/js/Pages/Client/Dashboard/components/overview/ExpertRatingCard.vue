<script setup>
import { Link } from "@inertiajs/vue3";
import { Star } from "lucide-vue-next";

const props = defineProps({
    rating: {
        type: Number,
        default: 4.8,
    },
    totalSessions: {
        type: Number,
        default: 118,
    },
    href: {
        type: String,
        default: "#",
    },
});

// Calculate filled stars (out of 5)
const filledStars = Math.floor(props.rating);
const hasHalfStar = props.rating % 1 >= 0.5;
</script>

<template>
    <Link
        :href="href"
        class="block py-6 px-5 rounded-2xl shadow-sm transition-all duration-300 hover:shadow-lg hover:scale-[1.02] cursor-pointer group relative overflow-hidden bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50"
    >
        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-base font-bold text-slate-900 dark:text-white">
                Avg. Expert Rating
            </h4>
            <div class="p-2 bg-amber-50 dark:bg-amber-500/10 rounded-xl">
                <Star class="w-5 h-5 text-amber-500" />
            </div>
        </div>

        <!-- Rating Display -->
        <div class="text-center py-2">
            <h3
                class="text-5xl font-extrabold text-slate-900 dark:text-white mb-3"
            >
                {{ rating.toFixed(1) }}
            </h3>
            <!-- Stars -->
            <div class="flex items-center justify-center gap-1 mb-3">
                <Star
                    v-for="i in 5"
                    :key="i"
                    :class="[
                        'w-6 h-6',
                        i <= filledStars
                            ? 'text-amber-400 fill-amber-400'
                            : 'text-slate-200 dark:text-slate-600',
                    ]"
                />
            </div>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Based on {{ totalSessions }} sessions
            </p>
        </div>
    </Link>
</template>
