<script setup>
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import {
    User,
    Mail,
    Phone,
    Lock,
    Eye,
    EyeOff,
    ArrowRight,
    Loader2,
} from "lucide-vue-next";
import RegistrationStepHeader from "@/Components/Registration/RegistrationStepHeader.vue";

// Ambil shared data (Logo)
const page = usePage();
const googleIconUrl = computed(() => page.props.assets.googleIconUrl);

// Form Handling dengan Inertia
const form = useForm({
    name: "",
    email: "",
    phone: "",
    password: "",
    password_confirmation: "",
});

const showPassword = ref(false);
const showPasswordConfirm = ref(false);

const submit = () => {
    form.post(route("client_register.store_step_one"), {
        onFinish: () => {
            form.reset("password", "password_confirmation");
        },
    });
};
</script>

<template>
    <Head title="Daftar sebagai Client - Step 1" />

    <div class="min-h-screen bg-slate-100">
        <!-- Step Header -->
        <RegistrationStepHeader
            :current-step="1"
            :total-steps="2"
            step-title="Personal Info"
            step-subtitle="Enter your personal details to create your account"
        />

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-6 py-10">
            <!-- Form Card - Wide like reference -->
            <div
                class="bg-white rounded-2xl border border-slate-200 shadow-lg overflow-hidden"
            >
                <div class="p-10 md:p-12 lg:p-16">
                    <!-- Form Title -->
                    <div class="mb-10">
                        <h2 class="text-3xl font-bold text-slate-900 mb-3">
                            Create your account
                        </h2>
                        <p class="text-slate-500 text-lg">
                            Fill in your personal information to get started
                            with KeyPerson.
                        </p>
                    </div>

                    <!-- Content Grid - Two Column on larger screens -->
                    <div class="grid lg:grid-cols-2 gap-12 lg:gap-16">
                        <!-- Left Column - Form -->
                        <div>
                            <!-- Google Sign Up -->
                            <div class="mb-8">
                                <a
                                    :href="route('google.login')"
                                    class="group flex w-full items-center justify-center gap-3 rounded-xl px-5 py-4 text-sm font-semibold text-slate-700 transition-all duration-300 bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 hover:shadow-sm"
                                >
                                    <img
                                        :src="googleIconUrl"
                                        class="h-5 w-5 transition-transform group-hover:scale-110 duration-300"
                                        alt="Google"
                                    />
                                    <span>Continue with Google</span>
                                </a>
                            </div>

                            <!-- Divider -->
                            <div class="relative mb-8">
                                <div
                                    class="absolute inset-0 flex items-center"
                                    aria-hidden="true"
                                >
                                    <div
                                        class="w-full border-t border-slate-200"
                                    ></div>
                                </div>
                                <div class="relative flex justify-center">
                                    <span
                                        class="bg-white px-4 text-xs font-medium text-slate-400 uppercase tracking-widest"
                                    >
                                        or continue with email
                                    </span>
                                </div>
                            </div>

                            <!-- Form -->
                            <form @submit.prevent="submit">
                                <div class="space-y-6">
                                    <!-- Name -->
                                    <div>
                                        <label
                                            for="name"
                                            class="block text-sm font-semibold text-slate-700 mb-2"
                                            >Full Name</label
                                        >
                                        <div class="relative">
                                            <div
                                                class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"
                                            >
                                                <User class="w-5 h-5" />
                                            </div>
                                            <input
                                                id="name"
                                                v-model="form.name"
                                                type="text"
                                                autocomplete="name"
                                                required
                                                class="block w-full rounded-xl border border-slate-200 bg-slate-50 pl-12 pr-4 py-4 text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 text-sm transition-all duration-200"
                                                :class="{
                                                    'border-red-300 focus:border-red-500 focus:ring-red-500/10':
                                                        form.errors.name,
                                                }"
                                                placeholder="Enter your full name"
                                            />
                                        </div>
                                        <p
                                            v-if="form.errors.name"
                                            class="mt-2 text-sm text-red-500"
                                        >
                                            {{ form.errors.name }}
                                        </p>
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <label
                                            for="email"
                                            class="block text-sm font-semibold text-slate-700 mb-2"
                                            >Email Address</label
                                        >
                                        <div class="relative">
                                            <div
                                                class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"
                                            >
                                                <Mail class="w-5 h-5" />
                                            </div>
                                            <input
                                                id="email"
                                                v-model="form.email"
                                                type="email"
                                                autocomplete="email"
                                                required
                                                class="block w-full rounded-xl border border-slate-200 bg-slate-50 pl-12 pr-4 py-4 text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 text-sm transition-all duration-200"
                                                :class="{
                                                    'border-red-300 focus:border-red-500 focus:ring-red-500/10':
                                                        form.errors.email,
                                                }"
                                                placeholder="example@email.com"
                                            />
                                        </div>
                                        <p
                                            v-if="form.errors.email"
                                            class="mt-2 text-sm text-red-500"
                                        >
                                            {{ form.errors.email }}
                                        </p>
                                    </div>

                                    <!-- Phone -->
                                    <div>
                                        <label
                                            for="phone"
                                            class="block text-sm font-semibold text-slate-700 mb-2"
                                            >Phone Number</label
                                        >
                                        <div class="relative">
                                            <div
                                                class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"
                                            >
                                                <Phone class="w-5 h-5" />
                                            </div>
                                            <input
                                                id="phone"
                                                v-model="form.phone"
                                                type="tel"
                                                autocomplete="tel"
                                                required
                                                class="block w-full rounded-xl border border-slate-200 bg-slate-50 pl-12 pr-4 py-4 text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 text-sm transition-all duration-200"
                                                :class="{
                                                    'border-red-300 focus:border-red-500 focus:ring-red-500/10':
                                                        form.errors.phone,
                                                }"
                                                placeholder="08123456789"
                                            />
                                        </div>
                                        <p
                                            v-if="form.errors.phone"
                                            class="mt-2 text-sm text-red-500"
                                        >
                                            {{ form.errors.phone }}
                                        </p>
                                    </div>

                                    <!-- Password -->
                                    <div>
                                        <label
                                            for="password"
                                            class="block text-sm font-semibold text-slate-700 mb-2"
                                            >Password</label
                                        >
                                        <div class="relative">
                                            <div
                                                class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"
                                            >
                                                <Lock class="w-5 h-5" />
                                            </div>
                                            <input
                                                id="password"
                                                v-model="form.password"
                                                :type="
                                                    showPassword
                                                        ? 'text'
                                                        : 'password'
                                                "
                                                autocomplete="new-password"
                                                required
                                                class="block w-full rounded-xl border border-slate-200 bg-slate-50 pl-12 pr-12 py-4 text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 text-sm transition-all duration-200"
                                                :class="{
                                                    'border-red-300 focus:border-red-500 focus:ring-red-500/10':
                                                        form.errors.password,
                                                }"
                                                placeholder="Minimum 8 characters"
                                            />
                                            <button
                                                type="button"
                                                @click="
                                                    showPassword = !showPassword
                                                "
                                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors focus:outline-none"
                                            >
                                                <Eye
                                                    v-if="!showPassword"
                                                    class="h-5 w-5"
                                                />
                                                <EyeOff
                                                    v-else
                                                    class="h-5 w-5"
                                                />
                                            </button>
                                        </div>
                                        <p
                                            v-if="form.errors.password"
                                            class="mt-2 text-sm text-red-500"
                                        >
                                            {{ form.errors.password }}
                                        </p>
                                    </div>

                                    <!-- Confirm Password -->
                                    <div>
                                        <label
                                            for="password_confirmation"
                                            class="block text-sm font-semibold text-slate-700 mb-2"
                                            >Confirm Password</label
                                        >
                                        <div class="relative">
                                            <div
                                                class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"
                                            >
                                                <Lock class="w-5 h-5" />
                                            </div>
                                            <input
                                                id="password_confirmation"
                                                v-model="
                                                    form.password_confirmation
                                                "
                                                :type="
                                                    showPasswordConfirm
                                                        ? 'text'
                                                        : 'password'
                                                "
                                                autocomplete="new-password"
                                                required
                                                class="block w-full rounded-xl border border-slate-200 bg-slate-50 pl-12 pr-12 py-4 text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 text-sm transition-all duration-200"
                                                placeholder="Repeat your password"
                                            />
                                            <button
                                                type="button"
                                                @click="
                                                    showPasswordConfirm =
                                                        !showPasswordConfirm
                                                "
                                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors focus:outline-none"
                                            >
                                                <Eye
                                                    v-if="!showPasswordConfirm"
                                                    class="h-5 w-5"
                                                />
                                                <EyeOff
                                                    v-else
                                                    class="h-5 w-5"
                                                />
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Info Box -->
                                <div
                                    class="mt-8 p-5 bg-blue-50 border border-blue-100 rounded-xl"
                                >
                                    <div class="flex gap-3">
                                        <div
                                            class="w-6 h-6 rounded-full bg-blue-500 flex items-center justify-center shrink-0"
                                        >
                                            <svg
                                                class="w-3.5 h-3.5 text-white"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </div>
                                        <p
                                            class="text-sm text-blue-700 leading-relaxed"
                                        >
                                            Your information will be used to set
                                            up your company profile in the next
                                            step. You can update these details
                                            later in your profile settings.
                                        </p>
                                    </div>
                                </div>

                                <!-- Footer Actions -->
                                <div
                                    class="mt-10 pt-8 border-t border-slate-100 flex items-center justify-between"
                                >
                                    <Link
                                        :href="route('login')"
                                        class="text-sm font-medium text-slate-500 hover:text-slate-700 transition-colors"
                                    >
                                        Already have an account? Sign in
                                    </Link>

                                    <button
                                        type="submit"
                                        :disabled="form.processing"
                                        class="inline-flex items-center gap-2.5 px-8 py-3.5 bg-blue-600 text-white font-semibold text-sm rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-blue-500/20"
                                    >
                                        <Loader2
                                            v-if="form.processing"
                                            class="animate-spin h-4 w-4"
                                        />
                                        <span>{{
                                            form.processing
                                                ? "Processing..."
                                                : "Continue"
                                        }}</span>
                                        <ArrowRight
                                            v-if="!form.processing"
                                            class="w-4 h-4"
                                        />
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Right Column - Info/Illustration (optional, for visual balance) -->
                        <div
                            class="hidden lg:flex flex-col justify-center items-center"
                        >
                            <div class="text-center max-w-md">
                                <!-- Decorative Element -->
                                <div class="mb-8">
                                    <div
                                        class="w-32 h-32 mx-auto rounded-3xl bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center border border-blue-100"
                                    >
                                        <svg
                                            class="w-16 h-16 text-blue-500"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="1.5"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"
                                            />
                                        </svg>
                                    </div>
                                </div>

                                <h3
                                    class="text-xl font-bold text-slate-900 mb-3"
                                >
                                    Join KeyPerson Today
                                </h3>
                                <p class="text-slate-500 mb-8">
                                    Create your account and get access to our
                                    network of professional experts for your
                                    business needs.
                                </p>

                                <!-- Features -->
                                <div class="space-y-4 text-left">
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center shrink-0 mt-0.5"
                                        >
                                            <svg
                                                class="w-3.5 h-3.5 text-green-600"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </div>
                                        <span class="text-sm text-slate-600"
                                            >Access to thousands of certified
                                            experts</span
                                        >
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center shrink-0 mt-0.5"
                                        >
                                            <svg
                                                class="w-3.5 h-3.5 text-green-600"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </div>
                                        <span class="text-sm text-slate-600"
                                            >Manage quotas and employees</span
                                        >
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center shrink-0 mt-0.5"
                                        >
                                            <svg
                                                class="w-3.5 h-3.5 text-green-600"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </div>
                                        <span class="text-sm text-slate-600"
                                            >Complete analytics dashboard</span
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Terms -->
            <div class="mt-8 text-center text-sm text-slate-500">
                By registering, you agree to our
                <Link
                    :href="route('terms')"
                    class="underline hover:text-slate-700 transition-colors"
                    >Terms & Conditions</Link
                >
                and
                <Link
                    :href="route('privacy')"
                    class="underline hover:text-slate-700 transition-colors"
                    >Privacy Policy</Link
                >.
            </div>
        </div>
    </div>
</template>
