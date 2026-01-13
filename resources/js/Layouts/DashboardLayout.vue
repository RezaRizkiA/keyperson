<script setup>
import { ref } from "vue";
import Sidebar from "@/Components/Dashboard/Sidebar.vue";
import Topbar from "@/Components/Dashboard/Topbar.vue";
import ToastNotification from "@/Components/ToastNotification.vue";

const isSidebarExpanded = ref(true);
const isMobileMenuOpen = ref(false);

const toggleSidebar = () => {
    isSidebarExpanded.value = !isSidebarExpanded.value;
};
</script>

<template>
    <!-- Updated: bg-background untuk responsive light/dark -->
    <div
        class="min-h-screen bg-slate-50 dark:bg-slate-950 font-sans flex transition-colors duration-300"
    >
        <Sidebar
            :is-sidebar-expanded="isSidebarExpanded"
            :is-mobile-menu-open="isMobileMenuOpen"
            @toggle-sidebar="toggleSidebar"
            @close-mobile-menu="isMobileMenuOpen = false"
        />

        <main
            class="flex-1 min-h-screen flex flex-col transition-all duration-300 ease-in-out"
            :class="isSidebarExpanded ? 'lg:ml-64' : 'lg:ml-20'"
        >
            <Topbar @open-mobile-menu="isMobileMenuOpen = true" />

            <div class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
                <div class="max-w-screen mx-auto h-full">
                    <slot />
                </div>
            </div>
        </main>

        <ToastNotification />
    </div>
</template>
