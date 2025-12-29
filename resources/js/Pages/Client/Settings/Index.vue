<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import { User, Lock, Camera, Check, AlertCircle } from "lucide-vue-next";
import { ref, computed } from "vue";

defineOptions({ layout: DashboardLayout });

const props = defineProps({
    user: Object,
    flash: Object,
});

// Profile Form
const profileForm = useForm({
    name: props.user?.name || "",
    gender: props.user?.gender || "",
    phone: props.user?.phone || "",
    address: props.user?.address || "",
    slug_name: props.user?.client?.slug || "",
});

// Password Form
const passwordForm = useForm({
    current_password: "",
    new_password: "",
    new_password_confirmation: "",
});

// Picture Form
const pictureForm = useForm({
    picture: null,
});

const picturePreview = ref(null);

const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        pictureForm.picture = file;
        picturePreview.value = URL.createObjectURL(file);
    }
};

const submitProfile = () => {
    profileForm.post(route("profile.update"), {
        preserveScroll: true,
    });
};

const submitPassword = () => {
    passwordForm.post(route("profile.password.update"), {
        preserveScroll: true,
        onSuccess: () => passwordForm.reset(),
    });
};

const submitPicture = () => {
    pictureForm.post(route("profile.picture.update"), {
        preserveScroll: true,
        onSuccess: () => {
            picturePreview.value = null;
            pictureForm.reset();
        },
    });
};

const currentPicture = computed(() => {
    return picturePreview.value || props.user?.picture_url;
});
</script>

<template>
    <Head title="Client Settings" />

    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">
            Account Settings
        </h2>
        <p class="text-slate-500 dark:text-slate-400 mt-1">
            Manage your company account settings and preferences
        </p>
    </div>

    <!-- Flash Messages -->
    <div
        v-if="flash?.success"
        class="mb-6 p-4 rounded-lg bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/30 flex items-center gap-3"
    >
        <Check class="w-5 h-5 text-green-600 dark:text-green-400" />
        <span class="text-green-700 dark:text-green-400">{{
            flash.success
        }}</span>
    </div>
    <div
        v-if="flash?.error"
        class="mb-6 p-4 rounded-lg bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 flex items-center gap-3"
    >
        <AlertCircle class="w-5 h-5 text-red-600 dark:text-red-400" />
        <span class="text-red-700 dark:text-red-400">{{ flash.error }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Picture Card -->
        <div
            class="backdrop-blur-sm rounded-xl border p-6 bg-white dark:bg-slate-800/50 border-slate-200 dark:border-slate-700/50"
        >
            <h3
                class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-6 flex items-center gap-2"
            >
                <Camera class="w-5 h-5" />
                Profile Picture
            </h3>

            <form @submit.prevent="submitPicture" class="text-center">
                <div class="mb-4">
                    <img
                        :src="currentPicture"
                        class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-slate-200 dark:border-slate-700"
                        alt="Profile"
                    />
                </div>

                <input
                    type="file"
                    accept="image/*"
                    @change="handleFileChange"
                    class="hidden"
                    id="picture-input"
                />
                <label
                    for="picture-input"
                    class="inline-block px-4 py-2 text-sm font-medium rounded-lg border cursor-pointer transition-colors bg-slate-50 dark:bg-slate-900/50 hover:bg-slate-100 dark:hover:bg-slate-900 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-700"
                >
                    Choose Photo
                </label>

                <button
                    v-if="picturePreview"
                    type="submit"
                    :disabled="pictureForm.processing"
                    class="mt-3 w-full px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-colors disabled:opacity-50"
                >
                    {{ pictureForm.processing ? "Uploading..." : "Upload" }}
                </button>
            </form>
        </div>

        <!-- Profile Form -->
        <div
            class="lg:col-span-2 backdrop-blur-sm rounded-xl border p-6 bg-white dark:bg-slate-800/50 border-slate-200 dark:border-slate-700/50"
        >
            <h3
                class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-6 flex items-center gap-2"
            >
                <User class="w-5 h-5" />
                Profile Information
            </h3>

            <form @submit.prevent="submitProfile" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1"
                            >Full Name</label
                        >
                        <input
                            v-model="profileForm.name"
                            type="text"
                            class="w-full px-4 py-2 rounded-lg border bg-white dark:bg-slate-900/50 border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                        <p
                            v-if="profileForm.errors.name"
                            class="text-red-500 text-xs mt-1"
                        >
                            {{ profileForm.errors.name }}
                        </p>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1"
                            >Gender</label
                        >
                        <select
                            v-model="profileForm.gender"
                            class="w-full px-4 py-2 rounded-lg border bg-white dark:bg-slate-900/50 border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                            <option value="">Select Gender</option>
                            <option value="L">Male</option>
                            <option value="P">Female</option>
                        </select>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1"
                            >Phone</label
                        >
                        <input
                            v-model="profileForm.phone"
                            type="text"
                            class="w-full px-4 py-2 rounded-lg border bg-white dark:bg-slate-900/50 border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1"
                            >Profile URL Slug</label
                        >
                        <input
                            v-model="profileForm.slug_name"
                            type="text"
                            class="w-full px-4 py-2 rounded-lg border bg-white dark:bg-slate-900/50 border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                    </div>
                </div>

                <div>
                    <label
                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1"
                        >Address</label
                    >
                    <textarea
                        v-model="profileForm.address"
                        rows="3"
                        class="w-full px-4 py-2 rounded-lg border bg-white dark:bg-slate-900/50 border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    ></textarea>
                </div>

                <button
                    type="submit"
                    :disabled="profileForm.processing"
                    class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-colors disabled:opacity-50"
                >
                    {{ profileForm.processing ? "Saving..." : "Save Profile" }}
                </button>
            </form>
        </div>
    </div>

    <!-- Password Section -->
    <div
        class="mt-6 backdrop-blur-sm rounded-xl border p-6 bg-white dark:bg-slate-800/50 border-slate-200 dark:border-slate-700/50"
    >
        <h3
            class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-6 flex items-center gap-2"
        >
            <Lock class="w-5 h-5" />
            Change Password
        </h3>

        <form
            @submit.prevent="submitPassword"
            class="grid grid-cols-1 md:grid-cols-3 gap-4"
        >
            <div>
                <label
                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1"
                    >Current Password</label
                >
                <input
                    v-model="passwordForm.current_password"
                    type="password"
                    class="w-full px-4 py-2 rounded-lg border bg-white dark:bg-slate-900/50 border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    :placeholder="user?.has_password ? '••••••••' : 'Not set'"
                />
                <p
                    v-if="passwordForm.errors.current_password"
                    class="text-red-500 text-xs mt-1"
                >
                    {{ passwordForm.errors.current_password }}
                </p>
            </div>

            <div>
                <label
                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1"
                    >New Password</label
                >
                <input
                    v-model="passwordForm.new_password"
                    type="password"
                    class="w-full px-4 py-2 rounded-lg border bg-white dark:bg-slate-900/50 border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                <p
                    v-if="passwordForm.errors.new_password"
                    class="text-red-500 text-xs mt-1"
                >
                    {{ passwordForm.errors.new_password }}
                </p>
            </div>

            <div>
                <label
                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1"
                    >Confirm Password</label
                >
                <input
                    v-model="passwordForm.new_password_confirmation"
                    type="password"
                    class="w-full px-4 py-2 rounded-lg border bg-white dark:bg-slate-900/50 border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
            </div>

            <div class="md:col-span-3">
                <button
                    type="submit"
                    :disabled="passwordForm.processing"
                    class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-colors disabled:opacity-50"
                >
                    {{
                        passwordForm.processing
                            ? "Updating..."
                            : "Update Password"
                    }}
                </button>
            </div>
        </form>
    </div>
</template>
