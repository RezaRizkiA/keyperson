<script setup>
import { computed } from "vue";

const props = defineProps({
    icon: {
        type: String,
        required: true,
    },
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        required: true,
    },
    color: {
        type: String,
        default: "blue-500",
    },
    features: {
        type: Array,
        default: () => [],
    },
});

// Parse color untuk background dan text
const colorClasses = computed(() => {
    const colorMap = {
        "blue-500": "bg-blue-500/10 border-blue-500/20 text-blue-400",
        "cyan-500": "bg-cyan-500/10 border-cyan-500/20 text-cyan-400",
        "violet-500": "bg-violet-500/10 border-violet-500/20 text-violet-400",
        "fuchsia-500":
            "bg-fuchsia-500/10 border-fuchsia-500/20 text-fuchsia-400",
    };
    return colorMap[props.color] || colorMap["blue-500"];
});

const iconColorClass = computed(() => {
    const colorMap = {
        "blue-500": "text-blue-400",
        "cyan-500": "text-cyan-400",
        "violet-500": "text-violet-400",
        "fuchsia-500": "text-fuchsia-400",
    };
    return colorMap[props.color] || colorMap["blue-500"];
});
</script>

<template>
    <div class="card-feature-dark group">
        <!-- Icon -->
        <div
            class="w-12 h-12 rounded-2xl flex items-center justify-center mb-6 border"
            :class="colorClasses"
        >
            <!-- Slot for icon component or emoji -->
            <slot name="icon">
                <span class="text-2xl" :class="iconColorClass">{{ icon }}</span>
            </slot>
        </div>

        <!-- Title -->
        <h3 class="text-xl font-bold text-white mb-3">
            {{ title }}
        </h3>

        <!-- Description -->
        <p class="text-slate-400 text-sm leading-relaxed mb-4">
            {{ description }}
        </p>

        <!-- Feature List (optional) -->
        <ul v-if="features.length > 0" class="space-y-2 mb-4">
            <li
                v-for="(feature, index) in features"
                :key="index"
                class="flex items-center gap-2 text-sm text-slate-300"
            >
                <svg
                    class="w-4 h-4"
                    :class="iconColorClass"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 13l4 4L19 7"
                    />
                </svg>
                {{ feature }}
            </li>
        </ul>

        <!-- Slot for additional content or CTA -->
        <slot />
    </div>
</template>
