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
    ArrowLeft,
    Save,
    Loader2,
    Check,
    X,
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

const handleInput = () => {
  form.clearErrors();
};

const showPassword = ref(false);
const showPasswordConfirm = ref(false);

// Password Strength Validation
const passwordRequirements = computed(() => [
    {
        label: "Minimal 8 karakter",
        met: form.password.length >= 8,
    },
    {
        label: "Huruf kecil (a-z)",
        met: /[a-z]/.test(form.password),
    },
    {
        label: "Huruf besar (A-Z)",
        met: /[A-Z]/.test(form.password),
    },
    {
        label: "Angka (0-9)",
        met: /[0-9]/.test(form.password),
    },
    {
        label: "Simbol (!@#$%^&*)",
        met: /[!@#$%^&*(),.?":{}|<>[\]\\;'`~_+=-]/.test(form.password),
    },
    {
        label: "Tanpa spasi",
        met: form.password.length > 0 && !/\s/.test(form.password),
    },
]);

const allRequirementsMet = computed(() =>
    passwordRequirements.value.every((req) => req.met)
);

const passwordStrengthPercent = computed(() => {
    const metCount = passwordRequirements.value.filter((req) => req.met).length;
    return (metCount / passwordRequirements.value.length) * 100;
});


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

    <div class="min-h-screen bg-slate-50">
        <!-- Step Header -->
        <RegistrationStepHeader
            :current-step="1"
            :total-steps="2"
            step-title="Personal Info"
            step-subtitle="Enter your personal details to create your account"
        />

        <!-- Main Content -->
        <div class="max-w-5xl mx-auto px-6 py-8">
            <!-- Form Card - Wide like Step 2 -->
            <div
                class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden"
            >
                <div class="p-8">
                    <!-- Form Title -->
                    <h2 class="text-xl font-bold text-slate-900 mb-6">
                        Create your account
                    </h2>
                    <p class="text-slate-500 mb-8 -mt-4">
                        Fill in your personal information to get started with
                        KeyPerson.
                    </p>

                    <!-- Google Sign Up -->
                    <div class="mb-6">
                        <a
                            :href="route('google.login')"
                            class="group flex w-full items-center justify-center gap-3 rounded-xl px-5 py-3 text-sm font-semibold text-slate-700 transition-all duration-300 bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 hover:shadow-sm"
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
                    <div class="relative mb-6">
                        <div
                            class="absolute inset-0 flex items-center"
                            aria-hidden="true"
                        >
                            <div class="w-full border-t border-slate-200"></div>
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
                            <!-- Row 1: Name & Email -->
                            <div class="grid md:grid-cols-2 gap-4">
                                <!-- Name -->
                                <div>
                                    <label
                                        for="name"
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2"
                                    >
                                        Full Name
                                        <span class="text-red-400">*</span>
                                    </label>
                                    <div class="relative">
                                        <User
                                            class="absolute left-3 top-3 w-5 h-5 text-slate-400"
                                        />
                                        <input
                                            id="name"
                                            v-model="form.name"
                                            type="text"
                                            autocomplete="name"
                                            required
                                            class="w-full bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all text-sm py-3 pl-10 pr-4"
                                            :class="{
                                                'border-red-300':
                                                    form.errors.name,
                                            }"
                                            placeholder="Enter your full name"
                                            @input="handleInput('name')"
                                        />
                                    </div>
                                    <p
                                        v-if="form.errors.name"
                                        class="text-red-500 text-xs mt-1"
                                    >
                                        {{ form.errors.name }}
                                    </p>
                                </div>

                                <!-- Email -->
                                <div>
                                    <label
                                        for="email"
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2"
                                    >
                                        Email Address
                                        <span class="text-red-400">*</span>
                                    </label>
                                    <div class="relative">
                                        <Mail
                                            class="absolute left-3 top-3 w-5 h-5 text-slate-400"
                                        />
                                        <input
                                            id="email"
                                            v-model="form.email"
                                            type="email"
                                            autocomplete="email"
                                            required
                                            class="w-full bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all text-sm py-3 pl-10 pr-4"
                                            :class="{
                                                'border-red-300':
                                                    form.errors.email,
                                            }"
                                            placeholder="example@email.com"
                                            @input="handleInput('email')"
                                        />
                                    </div>
                                    <p
                                        v-if="form.errors.email"
                                        class="text-red-500 text-xs mt-1"
                                    >
                                        {{ form.errors.email }}
                                    </p>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div>
                                <label
                                    for="phone"
                                    class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2"
                                >
                                    Phone Number
                                    <span class="text-red-400">*</span>
                                </label>
                                <div class="relative">
                                    <Phone
                                        class="absolute left-3 top-3 w-5 h-5 text-slate-400"
                                    />
                                    <input
                                        id="phone"
                                        v-model="form.phone"
                                        type="tel"
                                        autocomplete="tel"
                                        required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all text-sm py-3 pl-10 pr-4"
                                        :class="{
                                            'border-red-300': form.errors.phone,
                                        }"
                                        placeholder="08123456789"
                                        @input="handleInput('phone')"
                                    />
                                </div>
                                <p
                                    v-if="form.errors.phone"
                                    class="text-red-500 text-xs mt-1"
                                >
                                    {{ form.errors.phone }}
                                </p>
                            </div>

                            <!-- Row 2: Password & Confirm -->
                            <div class="grid md:grid-cols-2 gap-4">
                                <!-- Password -->
                                <div>
                                    <label
                                        for="password"
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2"
                                    >
                                        Password
                                        <span class="text-red-400">*</span>
                                    </label>
                                    <div class="relative">
                                        <Lock
                                            class="absolute left-3 top-3 w-5 h-5 text-slate-400"
                                        />
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
                                            class="w-full bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all text-sm py-3 pl-10 pr-12"
                                            :class="{
                                                'border-red-300':
                                                    form.errors.password,
                                                'border-green-300':
                                                    allRequirementsMet &&
                                                    form.password.length > 0,
                                            }"
                                            placeholder="Buat password yang kuat"
                                            @input="handleInput('password')"
                                        />
                                        <button
                                            type="button"
                                            @click="
                                                showPassword = !showPassword
                                            "
                                            class="absolute right-3 top-3 text-slate-400 hover:text-slate-600 transition-colors focus:outline-none"
                                        >
                                            <Eye
                                                v-if="!showPassword"
                                                class="h-5 w-5"
                                            />
                                            <EyeOff v-else class="h-5 w-5" />
                                        </button>
                                    </div>

                                    <!-- Password Strength Indicator -->
                                    <div
                                        v-if="form.password.length > 0"
                                        class="mt-3 space-y-2"
                                    >
                                        <!-- Progress Bar -->
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="flex-1 h-1.5 bg-slate-200 rounded-full overflow-hidden"
                                            >
                                                <div
                                                    class="h-full transition-all duration-300 rounded-full"
                                                    :class="{
                                                        'bg-red-500':
                                                            passwordStrengthPercent <=
                                                            40,
                                                        'bg-yellow-500':
                                                            passwordStrengthPercent >
                                                                40 &&
                                                            passwordStrengthPercent <=
                                                                80,
                                                        'bg-green-500':
                                                            passwordStrengthPercent >
                                                            80,
                                                    }"
                                                    :style="{
                                                        width:
                                                            passwordStrengthPercent +
                                                            '%',
                                                    }"
                                                ></div>
                                            </div>
                                            <span
                                                class="text-xs font-medium"
                                                :class="{
                                                    'text-red-500':
                                                        passwordStrengthPercent <=
                                                        40,
                                                    'text-yellow-600':
                                                        passwordStrengthPercent >
                                                            40 &&
                                                        passwordStrengthPercent <=
                                                            80,
                                                    'text-green-600':
                                                        passwordStrengthPercent >
                                                        80,
                                                }"
                                            >
                                                {{
                                                    passwordStrengthPercent <=
                                                    40
                                                        ? "Lemah"
                                                        : passwordStrengthPercent <=
                                                          80
                                                        ? "Sedang"
                                                        : "Kuat"
                                                }}
                                            </span>
                                        </div>

                                        <!-- Requirements Checklist -->
                                        <div
                                            class="grid grid-cols-2 gap-x-4 gap-y-1"
                                        >
                                            <div
                                                v-for="(
                                                    req, index
                                                ) in passwordRequirements"
                                                :key="index"
                                                class="flex items-center gap-1.5"
                                            >
                                                <Check
                                                    v-if="req.met"
                                                    class="w-3.5 h-3.5 text-green-500"
                                                />
                                                <X
                                                    v-else
                                                    class="w-3.5 h-3.5 text-slate-300"
                                                />
                                                <span
                                                    class="text-xs"
                                                    :class="
                                                        req.met
                                                            ? 'text-green-600'
                                                            : 'text-slate-400'
                                                    "
                                                >
                                                    {{ req.label }}
                                                </span>
                                            </div>
                                        </div>
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
                                        for="password_confirmation"
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2"
                                    >
                                        Confirm Password
                                        <span class="text-red-400">*</span>
                                    </label>
                                    <div class="relative">
                                        <Lock
                                            class="absolute left-3 top-3 w-5 h-5 text-slate-400"
                                        />
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
                                            class="w-full bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all text-sm py-3 pl-10 pr-12"
                                            placeholder="Repeat your password"
                                        />
                                        <button
                                            type="button"
                                            @click="
                                                showPasswordConfirm =
                                                    !showPasswordConfirm
                                            "
                                            class="absolute right-3 top-3 text-slate-400 hover:text-slate-600 transition-colors focus:outline-none"
                                        >
                                            <Eye
                                                v-if="!showPasswordConfirm"
                                                class="h-5 w-5"
                                            />
                                            <EyeOff v-else class="h-5 w-5" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Info Box -->
                        <div
                            class="mt-8 p-4 bg-blue-50 border border-blue-100 rounded-xl"
                        >
                            <div class="flex gap-3">
                                <div
                                    class="w-5 h-5 rounded-full bg-blue-100 flex items-center justify-center shrink-0 mt-0.5"
                                >
                                    <svg
                                        class="w-3 h-3 text-blue-600"
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
                                <p class="text-sm text-blue-700">
                                    Your information will be used to set up your
                                    company profile in the next step. You can
                                    update these details later in your profile
                                    settings.
                                </p>
                            </div>
                        </div>

                        <!-- Footer Actions - 3 Button Spread Layout -->
                        <div
                            class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-between"
                        >
                            <!-- Back Button (Disabled on Step 1) -->
                            <Link
                                :href="route('login')"
                                class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-slate-500 hover:text-slate-700 transition-colors"
                            >
                                <ArrowLeft class="w-4 h-4" />
                                <span>Back to Login</span>
                            </Link>

                            <!-- Continue Button -->
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 text-white font-semibold text-sm rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-blue-500/20"
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
            </div>

            <!-- Terms -->
            <div class="mt-6 text-center text-sm text-slate-500">
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
