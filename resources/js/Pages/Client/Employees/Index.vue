<script setup>
import { Head, useForm, router } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import {
    Users,
    Mail,
    UserPlus,
    Trash2,
    Clock,
    Check,
    AlertCircle,
    Copy,
    Link,
} from "lucide-vue-next";
import { ref, computed } from "vue";

defineOptions({ layout: DashboardLayout });

const props = defineProps({
    client: Object,
    employees: Array,
    pendingInvites: Array,
    stats: Object,
    flash: Object,
});

const inviteForm = useForm({
    email: "",
});

const showInviteModal = ref(false);
const copiedLink = ref(null);

const canInvite = computed(() => {
    return props.stats.current + props.stats.pendingCount < props.stats.limit;
});

const submitInvite = () => {
    inviteForm.post(route("employees.invite"), {
        preserveScroll: true,
        onSuccess: () => {
            inviteForm.reset();
            showInviteModal.value = false;
        },
    });
};

const revokeInvite = (id) => {
    if (confirm("Are you sure you want to revoke this invite?")) {
        router.delete(route("employees.revoke", id), {
            preserveScroll: true,
        });
    }
};

const copyLink = (token) => {
    const url = `${window.location.origin}/join/${token}`;
    navigator.clipboard.writeText(url);
    copiedLink.value = token;
    setTimeout(() => (copiedLink.value = null), 2000);
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString("id-ID", {
        day: "numeric",
        month: "short",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};
</script>

<template>
    <Head title="Employee Management" />

    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">
                Employee Management
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1">
                Manage your company employees and invitations
            </p>
        </div>

        <button
            @click="showInviteModal = true"
            :disabled="!canInvite"
            class="flex items-center gap-2 px-4 py-2 bg-blue-500 hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed text-white font-medium rounded-lg transition-colors"
        >
            <UserPlus class="w-5 h-5" />
            Invite Employee
        </button>
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

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div
            class="p-4 rounded-xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700"
        >
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-100 dark:bg-blue-500/20 rounded-lg">
                    <Users class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                </div>
                <div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Total Employees
                    </p>
                    <p
                        class="text-xl font-bold text-slate-900 dark:text-slate-100"
                    >
                        {{ stats.current }} / {{ stats.limit }}
                    </p>
                </div>
            </div>
        </div>

        <div
            class="p-4 rounded-xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700"
        >
            <div class="flex items-center gap-3">
                <div class="p-2 bg-amber-100 dark:bg-amber-500/20 rounded-lg">
                    <Clock class="w-5 h-5 text-amber-600 dark:text-amber-400" />
                </div>
                <div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Pending Invites
                    </p>
                    <p
                        class="text-xl font-bold text-slate-900 dark:text-slate-100"
                    >
                        {{ stats.pendingCount }}
                    </p>
                </div>
            </div>
        </div>

        <div
            class="p-4 rounded-xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700"
        >
            <div class="flex items-center gap-3">
                <div
                    class="p-2 bg-emerald-100 dark:bg-emerald-500/20 rounded-lg"
                >
                    <Check
                        class="w-5 h-5 text-emerald-600 dark:text-emerald-400"
                    />
                </div>
                <div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Available Slots
                    </p>
                    <p
                        class="text-xl font-bold text-slate-900 dark:text-slate-100"
                    >
                        {{ stats.limit - stats.current - stats.pendingCount }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Invites -->
    <div
        v-if="pendingInvites.length > 0"
        class="mb-8 p-6 rounded-xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700"
    >
        <h3
            class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-4 flex items-center gap-2"
        >
            <Mail class="w-5 h-5" />
            Pending Invites
        </h3>

        <div class="space-y-3">
            <div
                v-for="invite in pendingInvites"
                :key="invite.id"
                class="flex items-center justify-between p-3 rounded-lg bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/30"
            >
                <div>
                    <p class="font-medium text-slate-900 dark:text-slate-100">
                        {{ invite.email }}
                    </p>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Expires: {{ formatDate(invite.expires_at) }}
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <button
                        @click="copyLink(invite.token)"
                        class="flex items-center gap-1 px-3 py-1.5 text-sm rounded-lg transition-colors"
                        :class="
                            copiedLink === invite.token
                                ? 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400'
                                : 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-700 dark:text-slate-300'
                        "
                    >
                        <Copy
                            v-if="copiedLink !== invite.token"
                            class="w-4 h-4"
                        />
                        <Check v-else class="w-4 h-4" />
                        {{
                            copiedLink === invite.token
                                ? "Copied!"
                                : "Copy Link"
                        }}
                    </button>
                    <button
                        @click="revokeInvite(invite.id)"
                        class="p-1.5 text-red-500 hover:bg-red-100 dark:hover:bg-red-500/20 rounded-lg transition-colors"
                        title="Revoke invite"
                    >
                        <Trash2 class="w-4 h-4" />
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Employee List -->
    <div
        class="p-6 rounded-xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700"
    >
        <h3
            class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-4 flex items-center gap-2"
        >
            <Users class="w-5 h-5" />
            Employees
        </h3>

        <div v-if="employees.length === 0" class="text-center py-8">
            <Users
                class="w-12 h-12 mx-auto text-slate-300 dark:text-slate-600 mb-3"
            />
            <p class="text-slate-500 dark:text-slate-400">
                No employees yet. Start by inviting team members.
            </p>
        </div>

        <div v-else class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-700">
                        <th
                            class="text-left py-3 px-4 text-sm font-medium text-slate-500 dark:text-slate-400"
                        >
                            Name
                        </th>
                        <th
                            class="text-left py-3 px-4 text-sm font-medium text-slate-500 dark:text-slate-400"
                        >
                            Email
                        </th>
                        <th
                            class="text-left py-3 px-4 text-sm font-medium text-slate-500 dark:text-slate-400"
                        >
                            Role
                        </th>
                        <th
                            class="text-left py-3 px-4 text-sm font-medium text-slate-500 dark:text-slate-400"
                        >
                            Joined
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="employee in employees"
                        :key="employee.id"
                        class="border-b border-slate-100 dark:border-slate-700/50"
                    >
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <img
                                    :src="
                                        employee.picture ||
                                        `https://ui-avatars.com/api/?name=${employee.name}&background=3b82f6&color=fff`
                                    "
                                    class="w-8 h-8 rounded-full object-cover"
                                    alt=""
                                />
                                <span
                                    class="font-medium text-slate-900 dark:text-slate-100"
                                    >{{ employee.name }}</span
                                >
                            </div>
                        </td>
                        <td
                            class="py-3 px-4 text-slate-600 dark:text-slate-400"
                        >
                            {{ employee.email }}
                        </td>
                        <td class="py-3 px-4">
                            <span
                                class="px-2 py-1 text-xs rounded-full capitalize"
                                :class="
                                    employee.company_role === 'hrd'
                                        ? 'bg-purple-100 text-purple-700 dark:bg-purple-500/20 dark:text-purple-400'
                                        : 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300'
                                "
                            >
                                {{ employee.company_role || "employee" }}
                            </span>
                        </td>
                        <td
                            class="py-3 px-4 text-slate-600 dark:text-slate-400"
                        >
                            {{ formatDate(employee.created_at) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Invite Modal -->
    <Teleport to="body">
        <div
            v-if="showInviteModal"
            class="fixed inset-0 z-50 flex items-center justify-center"
        >
            <div
                class="absolute inset-0 bg-black/50"
                @click="showInviteModal = false"
            ></div>
            <div
                class="relative w-full max-w-md p-6 rounded-2xl bg-white dark:bg-slate-800 shadow-2xl"
            >
                <h3
                    class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-4"
                >
                    Invite Employee
                </h3>
                <p class="text-slate-500 dark:text-slate-400 mb-6">
                    Enter the email address of the employee you want to invite.
                    They will receive a link valid for 24 hours.
                </p>

                <form @submit.prevent="submitInvite">
                    <div class="mb-4">
                        <label
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1"
                            >Email Address</label
                        >
                        <input
                            v-model="inviteForm.email"
                            type="email"
                            placeholder="employee@company.com"
                            class="w-full px-4 py-2 rounded-lg border bg-white dark:bg-slate-900/50 border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                        <p
                            v-if="inviteForm.errors.email"
                            class="text-red-500 text-xs mt-1"
                        >
                            {{ inviteForm.errors.email }}
                        </p>
                    </div>

                    <div class="flex gap-3">
                        <button
                            type="button"
                            @click="showInviteModal = false"
                            class="flex-1 px-4 py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            :disabled="inviteForm.processing"
                            class="flex-1 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors disabled:opacity-50"
                        >
                            {{
                                inviteForm.processing
                                    ? "Sending..."
                                    : "Send Invite"
                            }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </Teleport>
</template>
