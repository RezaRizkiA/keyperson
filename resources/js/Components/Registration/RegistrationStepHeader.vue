<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { Grid3x3, CheckCircle2 } from "lucide-vue-next";

const props = defineProps({
    currentStep: {
        type: Number,
        required: true,
        validator: (value) => [1, 2].includes(value),
    },
    totalSteps: {
        type: Number,
        default: 2,
    },
    registrationId: {
        type: String,
        default: null,
    },
    stepTitle: {
        type: String,
        required: true,
    },
    stepSubtitle: {
        type: String,
        default: "",
    },
});

// Calculate progress segments (3 segments total for visual)
const progressSegments = computed(() => {
    const totalSegments = 3;
    const filledSegments =
        props.currentStep === 1 ? 1 : props.currentStep === 2 ? 2 : 0;
    return {
        total: totalSegments,
        filled: filledSegments,
    };
});

const steps = computed(() => [
    {
        id: 1,
        name: "Personal Info",
        completed: props.currentStep > 1,
        current: props.currentStep === 1,
    },
    {
        id: 2,
        name: "Review & Submit",
        completed: props.currentStep > 2,
        current: props.currentStep === 2,
    },
]);
</script>

<template>
    <!-- Header -->
    <header
        class="bg-white border-b border-slate-200 px-6 py-4 sticky top-0 z-50"
    >
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <!-- Logo -->
            <Link
                :href="route('home')"
                class="flex items-center gap-2.5 hover:opacity-80 transition-opacity"
            >
                <div
                    class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-sm"
                >
                    <Grid3x3 class="w-5 h-5 text-white" />
                </div>
                <span class="text-lg font-bold text-slate-900"
                    >ClientPortal</span
                >
            </Link>

            <!-- Right Side -->
            <div class="flex items-center gap-5">
                <span
                    v-if="registrationId"
                    class="text-sm text-slate-500 font-medium"
                >
                    REGISTRATION ID:
                    <span class="text-slate-700 font-semibold"
                        >#{{ registrationId }}</span
                    >
                </span>

                <button
                    type="button"
                    class="text-sm text-slate-600 hover:text-slate-900 font-medium transition-colors"
                >
                    Help
                </button>

                <Link
                    :href="route('home')"
                    class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold rounded-lg transition-colors"
                >
                    Save & Exit
                </Link>
            </div>
        </div>
    </header>

    <!-- Step Progress Section -->
    <div class="border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <!-- Step Title Row -->
            <div class="flex items-start justify-between mb-5">
                <div>
                    <p
                        class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-2"
                    >
                        Step {{ currentStep }} of {{ totalSteps }}
                    </p>
                    <h1 class="text-2xl font-bold text-slate-900 mb-1">
                        {{ stepTitle }}
                    </h1>
                    <p v-if="stepSubtitle" class="text-sm text-slate-500">
                        {{ stepSubtitle }}
                    </p>
                </div>

                <div
                    class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-full shadow-sm"
                >
                    <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                    <span class="text-sm font-medium text-slate-700"
                        >In Progress</span
                    >
                </div>
            </div>

            <!-- Segmented Progress Bar -->
            <div class="flex gap-1.5 mb-4">
                <div
                    v-for="i in progressSegments.total"
                    :key="i"
                    class="flex-1 h-1.5 rounded-full transition-all duration-500"
                    :class="
                        i <= progressSegments.filled
                            ? 'bg-blue-500'
                            : 'bg-slate-200'
                    "
                ></div>
            </div>

            <!-- Breadcrumb Steps -->
            <div class="flex items-center justify-between">
                <div
                    v-for="(step, index) in steps"
                    :key="step.id"
                    class="flex items-center gap-2"
                >
                    <!-- Completed step -->
                    <template v-if="step.completed">
                        <CheckCircle2 class="w-4 h-4 text-blue-500" />
                        <span class="text-sm font-medium text-blue-600">{{
                            step.name
                        }}</span>
                    </template>

                    <!-- Current step -->
                    <template v-else-if="step.current">
                        <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                        <span class="text-sm font-medium text-blue-600">{{
                            step.name
                        }}</span>
                    </template>

                    <!-- Future step -->
                    <template v-else>
                        <span
                            class="w-3 h-3 rounded-full border-2 border-slate-300"
                        ></span>
                        <span class="text-sm text-slate-400">{{
                            step.name
                        }}</span>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>
