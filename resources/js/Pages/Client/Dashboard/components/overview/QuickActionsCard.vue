<script setup>
import { Link } from "@inertiajs/vue3";
import {
    Zap,
    Upload,
    FileText,
    CreditCard,
    Users,
    ChevronRight,
} from "lucide-vue-next";

const props = defineProps({
    actions: {
        type: Array,
        default: () => [
            {
                id: 1,
                title: "Bulk Invitation",
                description: "Invite multiple employees via CSV",
                icon: "upload",
                iconBg: "bg-blue-100 dark:bg-blue-500/20",
                iconColor: "text-blue-600 dark:text-blue-400",
                href: "#",
            },
            {
                id: 2,
                title: "Generate SPJ Report",
                description: "Download monthly analytics PDF",
                icon: "file",
                iconBg: "bg-emerald-100 dark:bg-emerald-500/20",
                iconColor: "text-emerald-600 dark:text-emerald-400",
                href: "#",
            },
            {
                id: 3,
                title: "Top Up Credits",
                description: "Purchase additional consultation hours",
                icon: "credit",
                iconBg: "bg-amber-100 dark:bg-amber-500/20",
                iconColor: "text-amber-600 dark:text-amber-400",
                href: "#",
            },
            {
                id: 4,
                title: "Employee Directory",
                description: "Manage and view all employees",
                icon: "users",
                iconBg: "bg-violet-100 dark:bg-violet-500/20",
                iconColor: "text-violet-600 dark:text-violet-400",
                href: "#",
            },
        ],
    },
});

// Get icon component based on icon name
const getIcon = (iconName) => {
    const icons = {
        upload: Upload,
        file: FileText,
        credit: CreditCard,
        users: Users,
    };
    return icons[iconName] || Upload;
};
</script>

<template>
    <div
        class="rounded-2xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 shadow-sm p-6 flex flex-col"
    >
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white">
                Quick Actions
            </h3>
            <div class="p-2 bg-violet-50 dark:bg-violet-500/10 rounded-xl">
                <Zap class="w-5 h-5 text-violet-500" />
            </div>
        </div>

        <!-- Actions List -->
        <div class="space-y-3 flex-1">
            <Link
                v-for="action in actions"
                :key="action.id"
                :href="action.href"
                class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-all duration-200 group"
            >
                <div class="p-2.5 rounded-lg" :class="action.iconBg">
                    <component
                        :is="getIcon(action.icon)"
                        class="w-4 h-4"
                        :class="action.iconColor"
                    />
                </div>
                <div class="flex-1">
                    <h4
                        class="text-sm font-semibold text-slate-900 dark:text-white"
                    >
                        {{ action.title }}
                    </h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        {{ action.description }}
                    </p>
                </div>
                <ChevronRight
                    class="w-4 h-4 text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300 transition-colors"
                />
            </Link>
        </div>
    </div>
</template>
