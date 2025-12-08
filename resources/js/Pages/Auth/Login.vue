<script setup>
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { computed, ref, onMounted, onUnmounted } from "vue";

// Ambil shared data (Logo)
const page = usePage();
const logoUrl = computed(() => page.props.assets.logoUrl);
const googleIconUrl = computed(() => page.props.assets.googleIconUrl);

// Form Handling dengan Inertia
const form = useForm({
    email: "",
    password: "",
    remember: true,
});

const submit = () => {
    form.post(route("login_post"), {
        onFinish: () => form.reset("password"),
    });
};

// Logic Sederhana untuk Carousel/Slider di sebelah kanan
const slides = [
    {
        image: "/image/choose-expert.png", // Pastikan path ini benar di folder public
        title: "Choose Professional Expert",
        desc: "Tentukan expert yang sedang dibutuhkan, kenali profile dan jadwal available-nya.",
    },
    {
        image: "/image/complate-payment.png",
        title: "Payment & Make Appointment",
        desc: "Selesaikan pembayaran lalu buat janji dengan profesional yang dibutuhkan.",
    },
    {
        image: "/image/confirm-schedule.png",
        title: "Confirmation Schedule",
        desc: "Setelah appointment terbuat, konfirmasi dengan expert untuk jadwal fix.",
    },
];

const currentSlide = ref(0);
let intervalId;

const nextSlide = () => {
    currentSlide.value = (currentSlide.value + 1) % slides.length;
};

onMounted(() => {
    intervalId = setInterval(nextSlide, 5000); // Ganti slide tiap 5 detik
});

onUnmounted(() => {
    clearInterval(intervalId);
});
</script>

<template>
    <Head title="Sign In" />

    <div class="min-h-screen flex font-sans bg-white text-slate-600">
        <div
            class="flex-1 flex flex-col justify-center px-4 sm:px-6 lg:px-20 xl:px-24 bg-white relative z-10"
        >
            <div class="mx-auto w-full max-w-sm lg:w-96">
                <div class="mb-10">
                    <Link :href="route('home')">
                        <img
                            :src="logoUrl"
                            alt="KeyPerson Logo"
                            class="h-10 w-auto"
                        />
                    </Link>
                </div>

                <div class="mb-8">
                    <h2 class="font-display text-3xl font-bold text-slate-900">
                        Let's get you signed in
                    </h2>
                    <p class="mt-2 text-sm text-slate-500">
                        Welcome back! Please enter your details.
                    </p>
                </div>

                <div class="mb-6">
                    <a
                        :href="route('google.login')"
                        class="flex w-full items-center justify-center gap-3 rounded-xl bg-white px-3 py-3 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-all"
                    >
                        <img :src="googleIconUrl" class="h-5 w-5" alt="Google" />
                        <span class="text-sm">Sign in with Google</span>
                    </a>
                </div>

                <div class="relative mb-6">
                    <div
                        class="absolute inset-0 flex items-center"
                        aria-hidden="true"
                    >
                        <div class="w-full border-t border-slate-200"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span
                            class="bg-white px-2 text-xs text-slate-400 uppercase tracking-wider"
                            >Or with email</span
                        >
                    </div>
                </div>

                <form @submit.prevent="submit">
                    <div class="space-y-5">
                        <div>
                            <label
                                for="email"
                                class="block text-sm font-medium text-slate-700"
                                >Email Address</label
                            >
                            <div class="mt-1">
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    autocomplete="email"
                                    required
                                    class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:text-sm py-3 transition-colors"
                                    :class="{
                                        'border-red-300 focus:border-red-500 focus:ring-red-500':
                                            form.errors.email,
                                    }"
                                    placeholder="Enter your email"
                                />
                            </div>
                            <p
                                v-if="form.errors.email"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ form.errors.email }}
                            </p>
                        </div>

                        <div>
                            <div class="flex items-center justify-between">
                                <label
                                    for="password"
                                    class="block text-sm font-medium text-slate-700"
                                    >Password</label
                                >
                                <div class="text-sm">
                                    <a
                                        href="#"
                                        class="font-medium text-violet-600 hover:text-violet-500"
                                        >Forgot password?</a
                                    >
                                </div>
                            </div>
                            <div class="mt-1">
                                <input
                                    id="password"
                                    v-model="form.password"
                                    type="password"
                                    autocomplete="current-password"
                                    required
                                    class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:text-sm py-3 transition-colors"
                                    :class="{
                                        'border-red-300 focus:border-red-500 focus:ring-red-500':
                                            form.errors.password,
                                    }"
                                    placeholder="Enter your password"
                                />
                            </div>
                            <p
                                v-if="form.errors.password"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ form.errors.password }}
                            </p>
                        </div>

                        <div class="flex items-center">
                            <input
                                id="remember-me"
                                v-model="form.remember"
                                type="checkbox"
                                class="h-4 w-4 rounded border-slate-300 text-violet-600 focus:ring-violet-500"
                            />
                            <label
                                for="remember-me"
                                class="ml-2 block text-sm text-slate-600"
                            >
                                I agree to the
                                <Link
                                    :href="route('terms')"
                                    class="font-bold text-slate-800 hover:underline"
                                    >Terms</Link
                                >
                                and
                                <Link
                                    :href="route('privacy')"
                                    class="font-bold text-slate-800 hover:underline"
                                    >Privacy</Link
                                >
                            </label>
                        </div>

                        <div>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="flex w-full justify-center rounded-xl bg-slate-900 px-3 py-3.5 text-sm font-bold leading-6 text-white shadow-sm hover:bg-slate-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-600 transition-all disabled:opacity-70 disabled:cursor-not-allowed"
                            >
                                <svg
                                    v-if="form.processing"
                                    class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <circle
                                        class="opacity-25"
                                        cx="12"
                                        cy="12"
                                        r="10"
                                        stroke="currentColor"
                                        stroke-width="4"
                                    ></circle>
                                    <path
                                        class="opacity-75"
                                        fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                    ></path>
                                </svg>
                                {{
                                    form.processing
                                        ? "Signing in..."
                                        : "Sign In"
                                }}
                            </button>
                        </div>
                    </div>
                </form>

                <p class="mt-8 text-center text-sm text-slate-500">
                    Don’t have an account?
                    <a
                        :href="route('google.login')"
                        class="font-bold text-violet-600 hover:text-violet-500"
                        >Sign Up with Google</a
                    >
                </p>
            </div>
        </div>

        <div class="hidden lg:flex relative flex-1 bg-slate-50 overflow-hidden">
            <div
                class="absolute inset-0 bg-gradient-to-br from-violet-100 to-fuchsia-100 opacity-50"
            ></div>
            <div
                class="absolute -top-24 -right-24 w-96 h-96 bg-violet-200 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob"
            ></div>
            <div
                class="absolute -bottom-24 -left-24 w-96 h-96 bg-fuchsia-200 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-2000"
            ></div>

            <div
                class="relative w-full h-full flex flex-col items-center justify-center p-12"
            >
                <transition name="fade" mode="out-in">
                    <div :key="currentSlide" class="text-center max-w-md">
                        <div class="mb-10 relative">
                            <div
                                class="relative bg-white p-4 rounded-3xl shadow-xl border border-slate-100 transform rotate-1"
                            >
                                <img
                                    :src="slides[currentSlide].image"
                                    :alt="slides[currentSlide].title"
                                    class="w-full h-auto rounded-2xl"
                                />
                            </div>
                        </div>
                        <h3
                            class="font-display text-2xl font-bold text-slate-900 mb-3"
                        >
                            {{ slides[currentSlide].title }}
                        </h3>
                        <p class="text-slate-500 leading-relaxed">
                            {{ slides[currentSlide].desc }}
                        </p>
                    </div>
                </transition>

                <div class="absolute bottom-12 flex space-x-2">
                    <button
                        v-for="(slide, index) in slides"
                        :key="index"
                        @click="currentSlide = index"
                        class="w-2.5 h-2.5 rounded-full transition-all duration-300"
                        :class="
                            currentSlide === index
                                ? 'bg-violet-600 w-8'
                                : 'bg-slate-300 hover:bg-slate-400'
                        "
                    ></button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Transisi Slider */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease, transform 0.5s ease;
}

.fade-enter-from {
    opacity: 0;
    transform: translateX(20px);
}

.fade-leave-to {
    opacity: 0;
    transform: translateX(-20px);
}

.animate-blob {
    animation: blob 10s infinite;
}
.animation-delay-2000 {
    animation-delay: 5s;
}
@keyframes blob {
    0% {
        transform: translate(0px, 0px) scale(1);
    }
    33% {
        transform: translate(30px, -50px) scale(1.1);
    }
    66% {
        transform: translate(-20px, 20px) scale(0.9);
    }
    100% {
        transform: translate(0px, 0px) scale(1);
    }
}
</style>
