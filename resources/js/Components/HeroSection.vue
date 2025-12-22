<script setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
    subtitle: {
        type: String,
        default: "",
    },
    badge: {
        type: String,
        default: "",
    },
    align: {
        type: String,
        default: "center", // 'left', 'center', 'right'
        validator: (value) => ["left", "center", "right"].includes(value),
    },
});

const alignClass = (align) => {
    const classes = {
        left: "text-left items-start",
        center: "text-center items-center",
        right: "text-right items-end",
    };
    return classes[align] || classes.center;
};
</script>

<template>
    <section
        class="relative pt-32 pb-20 px-6 overflow-hidden bg-slate-950"
        :class="alignClass(align)"
    >
        <!-- Dark gradient overlay -->
        <div
            class="absolute inset-0 bg-linear-to-b from-slate-900 via-slate-950 to-slate-900 -z-10"
        ></div>

        <!-- Animated Glow Background -->
        <div
            class="absolute inset-0 bg-glow-gradient-hero opacity-40 -z-10"
        ></div>
        <div
            class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[800px] bg-blue-500/20 rounded-full blur-[150px] -z-10 animate-pulse-glow"
        ></div>

        <!-- Additional glow accents -->
        <div
            class="absolute top-20 right-0 w-[400px] h-[400px] bg-cyan-500/10 rounded-full blur-[120px] -z-10"
        ></div>
        <div
            class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-blue-600/10 rounded-full blur-[120px] -z-10"
        ></div>

        <div class="max-w-5xl mx-auto flex flex-col" :class="alignClass(align)">
            <!-- Badge -->
            <div v-if="badge" class="badge-glow mb-6">
                {{ badge }}
            </div>

            <!-- Title -->
            <h1
                class="text-5xl md:text-7xl font-bold tracking-tight text-white mb-8 leading-[1.1]"
            >
                {{ title }}
            </h1>

            <!-- Subtitle -->
            <p
                v-if="subtitle"
                class="text-xl text-slate-300 mb-10 max-w-2xl leading-relaxed"
            >
                {{ subtitle }}
            </p>

            <!-- Slot for CTA buttons or other content -->
            <slot />
        </div>
    </section>
</template>
