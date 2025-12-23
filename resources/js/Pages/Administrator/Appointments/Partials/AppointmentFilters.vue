<template>
    <div
        class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 p-6 mb-6"
    >
        <div class="flex flex-col md:flex-row gap-4">
            <!-- Search -->
            <div class="relative flex-1">
                <Search
                    class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                />
                <input
                    v-model="localFilters.search"
                    @keyup.enter="emit('apply-filters')"
                    type="text"
                    placeholder="Search by client, expert name..."
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-900/50 border border-slate-700 rounded-lg text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500"
                />
            </div>

            <!-- Status Filter -->
            <select
                v-model="localFilters.status"
                @change="emit('apply-filters')"
                class="px-4 py-2.5 bg-slate-900/50 border border-slate-700 rounded-lg text-sm text-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 cursor-pointer"
            >
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="need_confirmation">Need Confirmation</option>
                <option value="confirmed">Confirmed</option>
                <option value="progress">In Progress</option>
                <option value="scheduled">Scheduled</option>
                <option value="completed">Completed</option>
                <option value="declined">Declined</option>
                <option value="edited">Edited</option>
            </select>

            <!-- View Mode Toggle -->
            <div
                class="flex items-center gap-1 bg-slate-900/50 border border-slate-700 rounded-lg p-1"
            >
                <button
                    @click="setViewMode('list')"
                    :class="[
                        'p-2 rounded-md transition-colors',
                        localFilters.viewMode === 'list'
                            ? 'bg-slate-700 text-slate-200'
                            : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800',
                    ]"
                    title="List View"
                >
                    <List class="w-4 h-4" />
                </button>
                <button
                    @click="setViewMode('calendar')"
                    :class="[
                        'p-2 rounded-md transition-colors',
                        localFilters.viewMode === 'calendar'
                            ? 'bg-slate-700 text-slate-200'
                            : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800',
                    ]"
                    title="Calendar View"
                >
                    <Grid class="w-4 h-4" />
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from "vue";
import { Search, List, Grid } from "lucide-vue-next";

const props = defineProps({
    modelValue: {
        type: Object,
        required: true,
        default: () => ({
            search: "",
            status: "",
            viewMode: "list",
        }),
    },
});

const emit = defineEmits(["update:modelValue", "apply-filters"]);

// Local state to work with v-model
const localFilters = ref({ ...props.modelValue });

// Flag to prevent infinite loop
let isUpdatingFromParent = false;

// Sync with parent when props change (Inertia state sync - browser back button)
watch(
    () => props.modelValue,
    (newVal) => {
        // Only update if values are actually different (prevents echo)
        if (JSON.stringify(newVal) !== JSON.stringify(localFilters.value)) {
            isUpdatingFromParent = true;
            localFilters.value = { ...newVal };
            isUpdatingFromParent = false;
        }
    },
    { deep: true }
);

// Emit changes to parent in real-time (but not when updating from parent)
watch(
    localFilters,
    (val) => {
        if (!isUpdatingFromParent) {
            emit("update:modelValue", { ...val });
        }
    },
    { deep: true }
);

// Set view mode and trigger filter apply
const setViewMode = (mode) => {
    if (localFilters.value.viewMode !== mode) {
        localFilters.value.viewMode = mode;
        emit("apply-filters");
    }
};
</script>
