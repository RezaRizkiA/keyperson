<script setup>
import { Head, useForm, Link } from "@inertiajs/vue3";
import { Building2, Mail, Lock, User } from "lucide-vue-next";

const props = defineProps({
    invite: Object,
});

const form = useForm({
    name: "",
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.post(route("invite.register", props.invite.token));
};
</script>

<template>
    <Head title="Join Company" />

    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 p-4"
    >
        <div class="w-full max-w-md">
            <!-- Company Card -->
            <div
                class="mb-6 p-6 rounded-2xl bg-white/10 backdrop-blur-xl border border-white/20 text-center"
            >
                <div
                    class="w-16 h-16 mx-auto mb-4 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center"
                >
                    <Building2 class="w-8 h-8 text-white" />
                </div>
                <h2 class="text-xl font-bold text-white mb-2">
                    You're invited to join
                </h2>
                <p class="text-2xl font-bold text-blue-400">
                    {{ invite.company }}
                </p>
            </div>

            <!-- Registration Form -->
            <div class="p-6 rounded-2xl bg-white dark:bg-slate-800 shadow-2xl">
                <h3
                    class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-2"
                >
                    Create Your Account
                </h3>
                <p class="text-slate-500 dark:text-slate-400 mb-6">
                    Enter your details to complete the registration.
                </p>

                <form @submit.prevent="submit" class="space-y-4">
                    <!-- Email (readonly) -->
                    <div>
                        <label
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1"
                            >Email</label
                        >
                        <div
                            class="flex items-center gap-3 px-4 py-2 rounded-lg bg-slate-100 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700"
                        >
                            <Mail class="w-5 h-5 text-slate-400" />
                            <span class="text-slate-600 dark:text-slate-400">{{
                                invite.email
                            }}</span>
                        </div>
                    </div>

                    <!-- Name -->
                    <div>
                        <label
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1"
                            >Full Name</label
                        >
                        <div class="relative">
                            <User
                                class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"
                            />
                            <input
                                v-model="form.name"
                                type="text"
                                placeholder="Enter your name"
                                class="w-full pl-10 pr-4 py-2 rounded-lg border bg-white dark:bg-slate-900/50 border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            />
                        </div>
                        <p
                            v-if="form.errors.name"
                            class="text-red-500 text-xs mt-1"
                        >
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <!-- Password -->
                    <div>
                        <label
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1"
                            >Password</label
                        >
                        <div class="relative">
                            <Lock
                                class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"
                            />
                            <input
                                v-model="form.password"
                                type="password"
                                placeholder="Create a password"
                                class="w-full pl-10 pr-4 py-2 rounded-lg border bg-white dark:bg-slate-900/50 border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            />
                        </div>
                        <p
                            v-if="form.errors.password"
                            class="text-red-500 text-xs mt-1"
                        >
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1"
                            >Confirm Password</label
                        >
                        <div class="relative">
                            <Lock
                                class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"
                            />
                            <input
                                v-model="form.password_confirmation"
                                type="password"
                                placeholder="Confirm your password"
                                class="w-full pl-10 pr-4 py-2 rounded-lg border bg-white dark:bg-slate-900/50 border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            />
                        </div>
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full py-3 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-semibold rounded-lg transition-all disabled:opacity-50"
                    >
                        {{
                            form.processing
                                ? "Creating Account..."
                                : "Join Company"
                        }}
                    </button>
                </form>

                <p
                    class="mt-6 text-center text-sm text-slate-500 dark:text-slate-400"
                >
                    Already have an account?
                    <Link
                        :href="route('login')"
                        class="text-blue-500 hover:underline"
                        >Login here</Link
                    >
                </p>
            </div>
        </div>
    </div>
</template>
