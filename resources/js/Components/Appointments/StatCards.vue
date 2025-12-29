<template>
    <div
        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8"
        v-if="stats"
    >
        <StatCard
            label="Total Appointments"
            :value="stats.total"
            :icon="Briefcase"
            iconColor="blue"
        />

        <StatCard
            label="Completed"
            :value="stats.completed"
            :icon="CheckCircle"
            iconColor="green"
        />

        <StatCard
            label="Scheduled"
            :value="stats.scheduled"
            :icon="Clock"
            iconColor="cyan"
        />

        <StatCard
            label="Cancelled"
            :value="stats.cancelled"
            :icon="XCircle"
            iconColor="red"
        />
    </div>
</template>

<script setup>
import StatCard from "@/Components/Dashboard/StatCard.vue";
import { Briefcase, CheckCircle, Clock, XCircle } from "lucide-vue-next";

defineProps({
    stats: {
        type: Object,
        required: false,
        default: null,
    },
});
</script>
