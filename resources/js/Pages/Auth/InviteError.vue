<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { AlertTriangle, ArrowLeft } from "lucide-vue-next";

const props = defineProps({
    error: String,
});
</script>

<template>
    <Head title="Invalid Invite" />

    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 via-red-900/20 to-slate-900 p-4"
    >
        <div class="w-full max-w-md text-center">
            <!-- Error Card -->
            <div
                class="p-8 rounded-2xl bg-white/10 backdrop-blur-xl border border-white/20"
            >
                <div
                    class="w-16 h-16 mx-auto mb-6 rounded-full bg-red-500/20 flex items-center justify-center"
                >
                    <AlertTriangle class="w-8 h-8 text-red-400" />
                </div>

                <h2 class="text-2xl font-bold text-white mb-4">
                    Invite Not Valid
                </h2>

                <p class="text-slate-300 mb-8">
                    {{ error }}
                </p>

                <Link
                    :href="route('login')"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-white/10 hover:bg-white/20 text-white font-medium rounded-lg transition-colors"
                >
                    <ArrowLeft class="w-5 h-5" />
                    Go to Login
                </Link>
            </div>

            <p class="mt-6 text-slate-400 text-sm">
                If you believe this is a mistake, please contact your HR
                department.
            </p>
        </div>
    </div>
</template>
