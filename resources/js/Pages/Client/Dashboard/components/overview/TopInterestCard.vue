<script setup>
import { Link } from "@inertiajs/vue3";
import { TrendingUp } from "lucide-vue-next";

const props = defineProps({
    categories: {
        type: Array,
        default: () => [
            { name: "Mental Health", percentage: 40, color: "bg-blue-600" },
            {
                name: "Career Development",
                percentage: 30,
                color: "bg-blue-600",
            },
            {
                name: "Financial Wellness",
                percentage: 25,
                color: "bg-slate-400",
            },
        ],
    },
    href: {
        type: String,
        default: "#",
    },
});
</script>

<template>
    <Link
        :href="href"
        class="block py-6 px-5 rounded-2xl shadow-sm transition-all duration-300 hover:shadow-lg hover:scale-[1.02] cursor-pointer group relative overflow-hidden bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50"
    >
        <!-- Header -->
        <div class="flex items-center justify-between mb-5">
            <h4 class="text-base font-bold text-slate-900 dark:text-white">
                Top Interest Categories
            </h4>
            <div class="p-2 bg-violet-50 dark:bg-violet-500/10 rounded-xl">
                <TrendingUp class="w-5 h-5 text-violet-500" />
            </div>
        </div>

        <!-- Categories List -->
        <div class="space-y-4">
            <div v-for="(category, index) in categories" :key="index">
                <div class="flex items-center justify-between mb-1.5">
                    <span
                        class="text-sm font-medium text-slate-700 dark:text-slate-300"
                    >
                        {{ category.name }}
                    </span>
                    <span
                        class="text-sm font-semibold text-slate-900 dark:text-white"
                    >
                        {{ category.percentage }}%
                    </span>
                </div>
                <div
                    class="h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden"
                >
                    <div
                        class="h-full rounded-full transition-all duration-500"
                        :class="category.color"
                        :style="{ width: category.percentage + '%' }"
                    ></div>
                </div>
            </div>
        </div>
    </Link>
</template>
