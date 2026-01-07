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
    Building2,
} from "lucide-vue-next";

// Ambil shared data (Logo)
const page = usePage();
const logoSmallUrl = computed(() => page.props.assets.logoSmallUrl);
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
    <Head title="Daftar sebagai Client" />

    <div class="min-h-screen flex font-sans bg-background text-foreground">
        <!-- Left Side: Form -->
        <div
            class="flex-1 flex flex-col justify-center px-4 sm:px-6 lg:px-20 xl:px-24 relative z-10"
        >
            <!-- Glow Background -->
            <div
                class="absolute top-0 left-0 w-[500px] h-[500px] bg-blue-500/10 rounded-full blur-[150px] -z-10"
            ></div>
            <div
                class="absolute bottom-0 right-0 w-[400px] h-[400px] bg-cyan-500/10 rounded-full blur-[120px] -z-10"
            ></div>

            <div class="mx-auto w-full max-w-sm lg:w-104">
                <!-- Logo -->
                <div class="mb-8">
                    <Link
                        :href="route('home')"
                        class="inline-flex items-center gap-2 group transition-transform hover:scale-105 duration-300"
                    >
                        <img
                            :src="logoSmallUrl"
                            alt="KeyPerson Logo"
                            class="h-10 w-auto"
                        />
                        <span
                            class="font-display font-bold text-xl tracking-tight text-white"
                        >
                            Key<span class="text-blue-400">Person</span>
                        </span>
                    </Link>
                </div>

                <!-- Header -->
                <div class="mb-8">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-500/10 border border-blue-500/20 mb-4"
                    >
                        <Building2 class="w-4 h-4 text-blue-400" />
                        <span class="text-sm font-medium text-blue-400"
                            >Client Registration</span
                        >
                    </div>
                    <h2
                        class="font-display text-3xl font-bold text-foreground tracking-tight"
                    >
                        Daftar sebagai Client
                    </h2>
                    <p class="mt-2 text-base text-muted">
                        Buat akun untuk mendaftarkan perusahaan Anda di
                        KeyPerson.
                    </p>
                </div>

                <!-- Google Sign Up -->
                <div class="mb-6">
                    <a
                        :href="route('google.login')"
                        class="group flex w-full items-center justify-center gap-3 rounded-2xl px-4 py-3.5 text-sm font-bold text-foreground shadow-lg transition-all duration-300 bg-surface backdrop-blur-sm border border-border hover:bg-surface-hover hover:border-blue-500/30 hover:shadow-[0_0_20px_rgba(59,130,246,0.2)]"
                    >
                        <img
                            :src="googleIconUrl"
                            class="h-5 w-5 transition-transform group-hover:scale-110 duration-300"
                            alt="Google"
                        />
                        <span>Daftar dengan Google</span>
                    </a>
                </div>

                <!-- Divider -->
                <div class="relative mb-6">
                    <div
                        class="absolute inset-0 flex items-center"
                        aria-hidden="true"
                    >
                        <div class="w-full border-t border-border"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span
                            class="bg-background px-4 text-xs font-medium text-muted uppercase tracking-widest"
                            >atau dengan email</span
                        >
                    </div>
                </div>

                <!-- Form -->
                <form @submit.prevent="submit">
                    <div class="space-y-5">
                        <!-- Name -->
                        <div>
                            <label
                                for="name"
                                class="block text-sm font-semibold text-muted mb-2"
                                >Nama Lengkap</label
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
                                    class="block w-full rounded-2xl border-0 bg-slate-800/50 backdrop-blur-sm pl-12 pr-4 py-4 text-white shadow-sm ring-1 ring-inset ring-slate-700/50 placeholder:text-slate-500 focus:bg-slate-800/70 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 transition-all duration-200"
                                    :class="{
                                        'ring-red-500/50 focus:ring-red-500 bg-red-500/10':
                                            form.errors.name,
                                    }"
                                    placeholder="Masukkan nama lengkap"
                                />
                            </div>
                            <p
                                v-if="form.errors.name"
                                class="mt-2 text-sm text-red-400 flex items-center gap-1"
                            >
                                {{ form.errors.name }}
                            </p>
                        </div>

                        <!-- Email -->
                        <div>
                            <label
                                for="email"
                                class="block text-sm font-semibold text-muted mb-2"
                                >Email</label
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
                                    class="block w-full rounded-2xl border-0 bg-slate-800/50 backdrop-blur-sm pl-12 pr-4 py-4 text-white shadow-sm ring-1 ring-inset ring-slate-700/50 placeholder:text-slate-500 focus:bg-slate-800/70 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 transition-all duration-200"
                                    :class="{
                                        'ring-red-500/50 focus:ring-red-500 bg-red-500/10':
                                            form.errors.email,
                                    }"
                                    placeholder="contoh@email.com"
                                />
                            </div>
                            <p
                                v-if="form.errors.email"
                                class="mt-2 text-sm text-red-400 flex items-center gap-1"
                            >
                                {{ form.errors.email }}
                            </p>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label
                                for="phone"
                                class="block text-sm font-semibold text-muted mb-2"
                                >Nomor Telepon</label
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
                                    class="block w-full rounded-2xl border-0 bg-slate-800/50 backdrop-blur-sm pl-12 pr-4 py-4 text-white shadow-sm ring-1 ring-inset ring-slate-700/50 placeholder:text-slate-500 focus:bg-slate-800/70 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 transition-all duration-200"
                                    :class="{
                                        'ring-red-500/50 focus:ring-red-500 bg-red-500/10':
                                            form.errors.phone,
                                    }"
                                    placeholder="08123456789"
                                />
                            </div>
                            <p
                                v-if="form.errors.phone"
                                class="mt-2 text-sm text-red-400 flex items-center gap-1"
                            >
                                {{ form.errors.phone }}
                            </p>
                        </div>

                        <!-- Password -->
                        <div>
                            <label
                                for="password"
                                class="block text-sm font-semibold text-slate-300 mb-2"
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
                                    :type="showPassword ? 'text' : 'password'"
                                    autocomplete="new-password"
                                    required
                                    class="block w-full rounded-2xl border-0 bg-slate-800/50 backdrop-blur-sm pl-12 pr-12 py-4 text-white shadow-sm ring-1 ring-inset ring-slate-700/50 placeholder:text-slate-500 focus:bg-slate-800/70 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 transition-all duration-200"
                                    :class="{
                                        'ring-red-500/50 focus:ring-red-500 bg-red-500/10':
                                            form.errors.password,
                                    }"
                                    placeholder="Minimal 8 karakter"
                                />
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-400 transition-colors focus:outline-none"
                                >
                                    <Eye v-if="!showPassword" class="h-5 w-5" />
                                    <EyeOff v-else class="h-5 w-5" />
                                </button>
                            </div>
                            <p
                                v-if="form.errors.password"
                                class="mt-2 text-sm text-red-400 flex items-center gap-1"
                            >
                                {{ form.errors.password }}
                            </p>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label
                                for="password_confirmation"
                                class="block text-sm font-semibold text-slate-300 mb-2"
                                >Konfirmasi Password</label
                            >
                            <div class="relative">
                                <div
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"
                                >
                                    <Lock class="w-5 h-5" />
                                </div>
                                <input
                                    id="password_confirmation"
                                    v-model="form.password_confirmation"
                                    :type="
                                        showPasswordConfirm
                                            ? 'text'
                                            : 'password'
                                    "
                                    autocomplete="new-password"
                                    required
                                    class="block w-full rounded-2xl border-0 bg-slate-800/50 backdrop-blur-sm pl-12 pr-12 py-4 text-white shadow-sm ring-1 ring-inset ring-slate-700/50 placeholder:text-slate-500 focus:bg-slate-800/70 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 transition-all duration-200"
                                    placeholder="Ulangi password"
                                />
                                <button
                                    type="button"
                                    @click="
                                        showPasswordConfirm =
                                            !showPasswordConfirm
                                    "
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-400 transition-colors focus:outline-none"
                                >
                                    <Eye
                                        v-if="!showPasswordConfirm"
                                        class="h-5 w-5"
                                    />
                                    <EyeOff v-else class="h-5 w-5" />
                                </button>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="group relative flex w-full justify-center items-center gap-2 rounded-2xl bg-blue-600 px-4 py-4 text-sm font-bold text-white shadow-lg shadow-blue-500/30 hover:bg-blue-500 hover:shadow-[0_0_30px_rgba(59,130,246,0.5)] hover:-translate-y-0.5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500 transition-all duration-300 disabled:opacity-70 disabled:cursor-not-allowed disabled:hover:translate-y-0 disabled:hover:shadow-none"
                            >
                                <svg
                                    v-if="form.processing"
                                    class="animate-spin h-5 w-5 text-white"
                                    xmlns="http://www.w3.org/2000/svg"
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
                                <span>{{
                                    form.processing
                                        ? "Memproses..."
                                        : "Lanjutkan"
                                }}</span>
                                <ArrowRight
                                    v-if="!form.processing"
                                    class="w-5 h-5"
                                />
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Login Link -->
                <p class="mt-8 text-center text-sm text-muted">
                    Sudah punya akun?
                    <Link
                        :href="route('login')"
                        class="font-bold text-blue-400 hover:text-blue-300 transition-colors"
                        >Masuk di sini</Link
                    >
                </p>

                <!-- Expert Link -->
                <p class="mt-3 text-center text-sm text-muted">
                    Ingin daftar sebagai Expert?
                    <Link
                        :href="route('expert_register.step_one')"
                        class="font-bold text-cyan-400 hover:text-cyan-300 transition-colors"
                        >Daftar Expert</Link
                    >
                </p>

                <!-- Terms -->
                <div class="mt-6 text-center text-xs text-muted-foreground">
                    Dengan mendaftar, Anda menyetujui
                    <Link
                        :href="route('terms')"
                        class="underline hover:text-slate-300 transition-colors"
                        >Syarat & Ketentuan</Link
                    >
                    dan
                    <Link
                        :href="route('privacy')"
                        class="underline hover:text-slate-300 transition-colors"
                        >Kebijakan Privasi</Link
                    >.
                </div>
            </div>
        </div>

        <!-- Right Side: Illustration -->
        <div class="hidden lg:flex relative flex-1 overflow-hidden">
            <!-- Gradient Background -->
            <div
                class="absolute inset-0 bg-linear-to-br from-slate-800 to-slate-900"
            ></div>

            <!-- Decorative Glow Blobs -->
            <div
                class="absolute top-0 right-0 -mr-20 -mt-20 w-[500px] h-[500px] bg-blue-500/20 rounded-full mix-blend-multiply filter blur-[100px] animate-blob"
            ></div>
            <div
                class="absolute bottom-0 left-0 -ml-20 -mb-20 w-[500px] h-[500px] bg-cyan-500/20 rounded-full mix-blend-multiply filter blur-[100px] animate-blob animation-delay-2000"
            ></div>

            <div
                class="relative w-full h-full flex flex-col items-center justify-center p-16"
            >
                <div class="text-center max-w-lg">
                    <!-- Icon -->
                    <div class="mb-8">
                        <div
                            class="w-24 h-24 mx-auto rounded-3xl bg-linear-to-br from-blue-500/20 to-cyan-500/20 flex items-center justify-center border border-blue-500/20"
                        >
                            <Building2 class="w-12 h-12 text-blue-400" />
                        </div>
                    </div>

                    <h3
                        class="font-display text-3xl font-bold text-white mb-4 tracking-tight"
                    >
                        Kelola Konsultasi Perusahaan
                    </h3>
                    <p class="text-lg text-slate-400 leading-relaxed mb-8">
                        Daftarkan perusahaan Anda dan akses ribuan expert
                        profesional untuk kebutuhan konsultasi bisnis.
                    </p>

                    <!-- Features List -->
                    <div class="space-y-4 text-left">
                        <div class="flex items-center gap-3 text-slate-300">
                            <div
                                class="w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center"
                            >
                                <svg
                                    class="w-4 h-4 text-blue-400"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                            </div>
                            <span>Akses ke ribuan expert bersertifikat</span>
                        </div>
                        <div class="flex items-center gap-3 text-slate-300">
                            <div
                                class="w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center"
                            >
                                <svg
                                    class="w-4 h-4 text-blue-400"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                            </div>
                            <span>Kelola kuota dan karyawan</span>
                        </div>
                        <div class="flex items-center gap-3 text-slate-300">
                            <div
                                class="w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center"
                            >
                                <svg
                                    class="w-4 h-4 text-blue-400"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                            </div>
                            <span>Dashboard analitik lengkap</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
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
