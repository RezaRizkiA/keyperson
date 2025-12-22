<script setup>
defineProps({
    skills: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <div>
        <h2
            class="text-sm font-bold text-blue-400 uppercase tracking-wider mb-6"
        >
            AREAS OF EXPERTISE
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
                v-for="skill in skills"
                :key="skill.id"
                class="bg-slate-900/50 backdrop-blur-sm border border-blue-500/20 hover:border-blue-500/60 rounded-2xl p-6 transition-all group"
            >
                <!-- Category Badge -->
                <div
                    class="flex items-center gap-2 text-xs text-slate-500 uppercase tracking-wider mb-4"
                >
                    <svg
                        class="w-3 h-3"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"
                        />
                    </svg>
                    <span v-if="skill.sub_category">
                        {{ skill.sub_category.category?.name }} /
                        {{ skill.sub_category.name }}
                    </span>
                </div>

                <!-- Skill Name -->
                <h4
                    class="text-xl font-bold text-white mb-2 group-hover:text-blue-400 transition-colors"
                >
                    {{ skill.name }}
                </h4>

                <!-- Description if available -->
                <p v-if="skill.description" class="text-sm text-slate-400">
                    {{ skill.description }}
                </p>
            </div>
        </div>

        <!-- Empty State -->
        <div
            v-if="!skills || skills.length === 0"
            class="bg-slate-900/50 backdrop-blur-sm border border-blue-500/20 rounded-2xl p-12 text-center"
        >
            <div
                class="w-16 h-16 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4"
            >
                <svg
                    class="w-8 h-8 text-slate-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"
                    />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">No Skills Listed</h3>
            <p class="text-slate-400">
                This expert hasn't added their expertise areas yet.
            </p>
        </div>
    </div>
</template>
