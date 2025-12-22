<script setup>
import { Link } from "@inertiajs/vue3";
import { ArrowRight } from "lucide-vue-next";

defineProps({
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        default: "",
    },
    primaryButtonText: {
        type: String,
        default: "Get Started",
    },
    primaryButtonLink: {
        type: String,
        default: "#",
    },
    secondaryButtonText: {
        type: String,
        default: "",
    },
    secondaryButtonLink: {
        type: String,
        default: "#",
    },
});
</script>

<template>
    <section
        class="relative py-32 px-6 text-center bg-slate-900 overflow-hidden"
    >
        <!-- Glow effect background -->
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-blue-500/20 rounded-full blur-[120px] -z-10"
        ></div>

        <div class="max-w-4xl mx-auto">
            <h2
                class="text-4xl md:text-6xl font-bold text-white mb-8 tracking-tight"
            >
                {{ title }}
            </h2>

            <p v-if="description" class="text-xl text-slate-300 mb-12">
                {{ description }}
            </p>

            <div
                class="flex flex-col sm:flex-row items-center justify-center gap-4"
            >
                <Link
                    :href="primaryButtonLink"
                    class="inline-flex items-center gap-3 px-10 py-5 bg-blue-600 text-white rounded-full font-bold text-xl hover:bg-blue-700 hover:scale-105 transition-all shadow-[0_0_40px_rgba(59,130,246,0.4)] hover:shadow-[0_0_60px_rgba(59,130,246,0.6)]"
                >
                    {{ primaryButtonText }}
                    <ArrowRight class="w-6 h-6" />
                </Link>

                <Link
                    v-if="secondaryButtonText"
                    :href="secondaryButtonLink"
                    class="inline-flex items-center gap-3 px-10 py-5 bg-slate-800 text-white border-2 border-slate-600 rounded-full font-bold text-xl hover:bg-slate-700 hover:border-blue-500 hover:shadow-[0_0_20px_rgba(59,130,246,0.3)] transition-all"
                >
                    {{ secondaryButtonText }}
                </Link>
            </div>
        </div>
    </section>
</template>
