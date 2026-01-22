import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

/**
 * Composable untuk menentukan dashboard route berdasarkan role user
 * Digunakan di: Sidebar, AppNavbar, AccessDenied, dll
 */
export function useDashboardRoute() {
    const page = usePage();

    const userRoles = computed(() => page.props.auth?.user?.roles || []);
    const isAdmin = computed(() => userRoles.value.includes("administrator"));
    const isClient = computed(() => userRoles.value.includes("client"));
    const isExpert = computed(() => userRoles.value.includes("expert"));

    const dashboardRoute = computed(() => {
        if (isAdmin.value) return "dashboard.administrator.index";
        if (isClient.value) return "dashboard.client.index";
        if (isExpert.value) return "dashboard.expert.index";
        // Fallback untuk user biasa
        return "dashboard.user.index";
    });

    return {
        userRoles,
        isAdmin,
        isClient,
        isExpert,
        dashboardRoute,
    };
}
