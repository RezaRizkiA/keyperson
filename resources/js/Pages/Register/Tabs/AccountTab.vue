<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";

const props = defineProps({
    user: Object,
});

const assets = computed(() => usePage().props.assets);

// Profile Picture Form
const pictureForm = useForm({
    picture: null,
});

const picturePreview = ref(null);

const updatePicture = () => {
    pictureForm.post(route("renew_picture"), {
        preserveScroll: true,
        onSuccess: () => {
            pictureForm.reset();
            picturePreview.value = null;
        },
    });
};

const handlePictureChange = (event) => {
    const file = event.target.files[0];
    pictureForm.picture = file;
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            picturePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

// Password Form
const passwordForm = useForm({
    current_password: "",
    new_password: "",
    new_password_confirmation: "",
});

const updatePassword = () => {
    passwordForm.post(route("renew_password"), {
        preserveScroll: true,
        onSuccess: () => passwordForm.reset(),
    });
};

// Profile Details Form
const profileForm = useForm({
    name: props.user.name,
    gender: props.user.gender || "L",
    phone: props.user.phone,
    address: props.user.address,
    slug_name: props.user.slug,
});

const updateProfile = () => {
    profileForm.post(route("renew_profile"), {
        preserveScroll: true,
    });
};
</script>

<template>
    <div class="space-y-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Change Profile Picture -->
            <div
                class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm"
            >
                <h4 class="text-lg font-bold text-slate-900 mb-2">
                    Change Profile Picture
                </h4>
                <p class="text-sm text-slate-500 mb-6">
                    Change your profile picture from here
                </p>

                <form @submit.prevent="updatePicture" class="text-center">
                    <div class="mb-6 flex justify-center">
                        <img
                            :src="
                                picturePreview ||
                                (user.picture
                                    ? `/storage/${user.picture}`
                                    : assets.userPlaceholderUrl)
                            "
                            alt="Profile"
                            class="w-32 h-32 rounded-full object-cover border-4 border-slate-50"
                        />
                    </div>

                    <input
                        type="file"
                        @change="handlePictureChange"
                        class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100"
                        accept="image/jpeg,image/png,image/gif"
                    />
                    <div
                        v-if="pictureForm.errors.picture"
                        class="text-red-500 text-xs mt-1 text-left"
                    >
                        {{ pictureForm.errors.picture }}
                    </div>

                    <div class="flex items-center justify-center gap-4 mt-6">
                        <button
                            type="submit"
                            class="px-6 py-2 rounded-xl bg-violet-600 text-white font-bold hover:bg-violet-700 transition-colors shadow-lg shadow-violet-200"
                            :disabled="
                                pictureForm.processing || !pictureForm.picture
                            "
                        >
                            Upload
                        </button>
                        <button
                            type="button"
                            @click="
                                picturePreview = null;
                                pictureForm.reset();
                            "
                            class="px-6 py-2 rounded-xl bg-red-50 text-red-600 font-bold hover:bg-red-100 transition-colors"
                            :disabled="!picturePreview"
                        >
                            Reset
                        </button>
                    </div>
                    <p class="text-xs text-slate-400 mt-4">
                        Allowed JPG, GIF or PNG. Max size of 800K
                    </p>
                </form>
            </div>

            <!-- Change Password -->
            <div
                class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm"
            >
                <h4 class="text-lg font-bold text-slate-900 mb-2">
                    Change Password
                </h4>
                <p class="text-sm text-slate-500 mb-6">
                    To change your password please confirm here
                </p>

                <form @submit.prevent="updatePassword" class="space-y-4">
                    <div v-if="user.password">
                        <label
                            class="block text-sm font-medium text-slate-700 mb-1"
                            >Current Password</label
                        >
                        <input
                            v-model="passwordForm.current_password"
                            type="password"
                            class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 outline-none transition-all"
                        />
                        <div
                            v-if="passwordForm.errors.current_password"
                            class="text-red-500 text-xs mt-1"
                        >
                            {{ passwordForm.errors.current_password }}
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-slate-700 mb-1"
                            >New Password</label
                        >
                        <input
                            v-model="passwordForm.new_password"
                            type="password"
                            class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 outline-none transition-all"
                        />
                        <div
                            v-if="passwordForm.errors.new_password"
                            class="text-red-500 text-xs mt-1"
                        >
                            {{ passwordForm.errors.new_password }}
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-slate-700 mb-1"
                            >Confirm Password</label
                        >
                        <input
                            v-model="passwordForm.new_password_confirmation"
                            type="password"
                            class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 outline-none transition-all"
                        />
                    </div>

                    <div class="text-center pt-2">
                        <button
                            type="submit"
                            class="px-6 py-2 rounded-xl bg-violet-600 text-white font-bold hover:bg-violet-700 transition-colors shadow-lg shadow-violet-200"
                            :disabled="passwordForm.processing"
                        >
                            Change Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Personal Details -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <h4 class="text-lg font-bold text-slate-900 mb-2">
                Personal Details
            </h4>
            <p class="text-sm text-slate-500 mb-6">
                To change your personal detail, edit and save from here
            </p>

            <form @submit.prevent="updateProfile">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <div class="mb-4">
                            <label
                                class="block text-sm font-medium text-slate-700 mb-1"
                                >Your Name</label
                            >
                            <input
                                v-model="profileForm.name"
                                type="text"
                                class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 outline-none transition-all"
                            />
                            <div
                                v-if="profileForm.errors.name"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ profileForm.errors.name }}
                            </div>
                        </div>
                        <div class="mb-4">
                            <label
                                class="block text-sm font-medium text-slate-700 mb-1"
                                >Sex</label
                            >
                            <select
                                v-model="profileForm.gender"
                                class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 outline-none transition-all"
                            >
                                <option value="L">Male</option>
                                <option value="P">Female</option>
                            </select>
                            <div
                                v-if="profileForm.errors.gender"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ profileForm.errors.gender }}
                            </div>
                        </div>
                        <div class="mb-4">
                            <label
                                class="block text-sm font-medium text-slate-700 mb-1"
                                >Email</label
                            >
                            <input
                                :value="user.email"
                                type="email"
                                class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 text-slate-500 cursor-not-allowed"
                                disabled
                            />
                        </div>
                    </div>

                    <div>
                        <div class="mb-4">
                            <label
                                class="block text-sm font-medium text-slate-700 mb-1"
                                >Unique URL</label
                            >
                            <div class="flex items-center">
                                <span
                                    class="px-4 py-2 bg-slate-100 border border-r-0 border-slate-200 rounded-l-xl text-slate-500 text-sm"
                                    >keyperson.id /</span
                                >
                                <input
                                    v-model="profileForm.slug_name"
                                    type="text"
                                    class="flex-1 px-4 py-2 rounded-r-xl border border-slate-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 outline-none transition-all"
                                />
                            </div>
                            <div
                                v-if="profileForm.errors.slug_name"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ profileForm.errors.slug_name }}
                            </div>
                        </div>
                        <div class="mb-4">
                            <label
                                class="block text-sm font-medium text-slate-700 mb-1"
                                >Phone</label
                            >
                            <input
                                v-model="profileForm.phone"
                                type="text"
                                class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 outline-none transition-all"
                            />
                            <div
                                v-if="profileForm.errors.phone"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ profileForm.errors.phone }}
                            </div>
                        </div>
                    </div>

                    <div class="col-span-1 lg:col-span-2">
                        <div class="mb-4">
                            <label
                                class="block text-sm font-medium text-slate-700 mb-1"
                                >Address</label
                            >
                            <input
                                v-model="profileForm.address"
                                type="text"
                                class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 outline-none transition-all"
                            />
                            <div
                                v-if="profileForm.errors.address"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ profileForm.errors.address }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <a
                        :href="route('profile')"
                        class="px-6 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 font-bold hover:bg-slate-50 transition-colors"
                    >
                        Cancel
                    </a>
                    <button
                        type="submit"
                        class="px-6 py-2 rounded-xl bg-violet-600 text-white font-bold hover:bg-violet-700 transition-colors shadow-lg shadow-violet-200"
                        :disabled="profileForm.processing"
                    >
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
