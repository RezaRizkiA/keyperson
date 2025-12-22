<script setup>
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";
import MainLayout from "../Layouts/MainLayout.vue";
import HeroSection from "../Components/HeroSection.vue";
import FAQAccordion from "../Components/FAQAccordion.vue";
import { MessageCircle, MapPin, Phone, Send, Briefcase } from "lucide-vue-next";

// Form data
const form = ref({
    firstName: "",
    lastName: "",
    email: "",
    topic: "general",
    message: "",
});

const submitting = ref(false);

const submitForm = async () => {
    submitting.value = true;
    // Simulate API call
    setTimeout(() => {
        alert("Message sent successfully!");
        form.value = {
            firstName: "",
            lastName: "",
            email: "",
            topic: "general",
            message: "",
        };
        submitting.value = false;
    }, 1000);
};

// Contact methods
const contactMethods = [
    {
        icon: "message",
        title: "Chat with us",
        description: "Speak to our friendly team regarding general inquiries.",
        link: "hello@keyperson.id",
        linkText: "hello@keyperson.id",
        isEmail: true,
    },
    {
        icon: "map",
        title: "Visit us",
        description: "Come say hello at our office HQ.",
        link: "#",
        address:
            "100 Innovation Blvd, Tech District  \nSan Francisco, CA 94102",
        isEmail: false,
    },
    {
        icon: "phone",
        title: "Call us",
        description: "Mon-Fri from 8am to 5pm.",
        link: "tel:+16505550606",
        linkText: "+1 (650) 555-0606",
        isEmail: false,
    },
];

// FAQ data
const faqItems = [
    {
        question: "How do I book a session?",
        answer: "Simply browse our network of experts, select a professional that matches your needs, and click on an available slot in their calendar. The session link will be sent to your Google Calendar automatically.",
    },
    {
        question: "Can I reschedule my appointment?",
        answer: "Yes, you can reschedule up to 24 hours before the session starts through your dashboard. Later rescheduling is subject to the expert's policy, but we'll do our best to accommodate you.",
    },
    {
        question: "How does payment work?",
        answer: "Payments are securely processed via Stripe at the time of booking. The funds are held in escrow and only released to the expert after the successful completion of the session, ensuring your satisfaction.",
    },
    {
        question: "How do I apply to be a keyPerson?",
        answer: 'We accept applications from industry leaders, certified coaches, and mentors. Click "Join as Expert" in the navigation, complete your professional profile, and our vetting team will review your application within 5-7 business days.',
    },
];
</script>

<template>
    <Head title="Contact Us - Get in Touch" />

    <MainLayout>
        <div>
            <!-- Hero Section -->
            <HeroSection
                title="Get in Touch"
                subtitle="Whether you're an individual seeking guidance or a seasoned expert looking to join our network, our team is here to assist you."
                align="center"
            />

            <!-- Main Contact Section -->
            <section class="relative py-24 px-6 bg-slate-900">
                <div class="max-w-7xl mx-auto">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                        <!-- Left Sidebar: Contact Methods + CTA -->
                        <div class="lg:col-span-1 space-y-6">
                            <!-- Contact Methods -->
                            <div
                                v-for="(method, index) in contactMethods"
                                :key="index"
                                class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-2xl p-6 hover:border-blue-500/50 hover:shadow-[0_0_30px_rgba(59,130,246,0.15)] transition-all"
                            >
                                <!-- Icon -->
                                <div
                                    class="w-12 h-12 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center mb-4"
                                >
                                    <MessageCircle
                                        v-if="method.icon === 'message'"
                                        class="w-6 h-6 text-blue-400"
                                    />
                                    <MapPin
                                        v-else-if="method.icon === 'map'"
                                        class="w-6 h-6 text-blue-400"
                                    />
                                    <Phone
                                        v-else
                                        class="w-6 h-6 text-blue-400"
                                    />
                                </div>

                                <!-- Title -->
                                <h3 class="text-lg font-bold text-white mb-2">
                                    {{ method.title }}
                                </h3>

                                <!-- Description -->
                                <p class="text-sm text-slate-400 mb-4">
                                    {{ method.description }}
                                </p>

                                <!-- Link/Address -->
                                <div v-if="method.isEmail">
                                    <a
                                        :href="'mailto:' + method.link"
                                        class="text-blue-400 hover:text-blue-300 font-medium transition-colors"
                                    >
                                        {{ method.linkText }}
                                    </a>
                                </div>
                                <div
                                    v-else-if="method.address"
                                    class="text-sm text-slate-300 whitespace-pre-line"
                                >
                                    {{ method.address }}
                                </div>
                                <div v-else>
                                    <a
                                        :href="method.link"
                                        class="text-blue-400 hover:text-blue-300 font-medium transition-colors"
                                    >
                                        {{ method.linkText }}
                                    </a>
                                </div>
                            </div>

                            <!-- Apply as Expert CTA -->
                            <div
                                class="bg-linear-to-br from-blue-600/20 to-cyan-600/20 border border-blue-500/30 rounded-2xl p-6 backdrop-blur-sm"
                            >
                                <div
                                    class="w-12 h-12 rounded-xl bg-blue-500/20 border border-blue-500/40 flex items-center justify-center mb-4"
                                >
                                    <Briefcase class="w-6 h-6 text-blue-400" />
                                </div>
                                <h3 class="text-lg font-bold text-white mb-2">
                                    Apply as an Expert?
                                </h3>
                                <p class="text-sm text-slate-300 mb-4">
                                    Join our curated network of industry leaders
                                    and mentors.
                                </p>
                                <button
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition-all shadow-lg shadow-blue-500/20"
                                >
                                    Apply Now
                                </button>
                            </div>
                        </div>

                        <!-- Right Side: Contact Form -->
                        <div class="lg:col-span-2">
                            <form
                                @submit.prevent="submitForm"
                                class="space-y-6"
                            >
                                <!-- Name Fields -->
                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 gap-6"
                                >
                                    <div>
                                        <label class="label-text-dark"
                                            >First name</label
                                        >
                                        <input
                                            v-model="form.firstName"
                                            type="text"
                                            placeholder="Jane"
                                            required
                                            class="input-field-dark"
                                        />
                                    </div>
                                    <div>
                                        <label class="label-text-dark"
                                            >Last name</label
                                        >
                                        <input
                                            v-model="form.lastName"
                                            type="text"
                                            placeholder="Doe"
                                            required
                                            class="input-field-dark"
                                        />
                                    </div>
                                </div>

                                <!-- Email -->
                                <div>
                                    <label class="label-text-dark">Email</label>
                                    <input
                                        v-model="form.email"
                                        type="email"
                                        placeholder="jane@company.com"
                                        required
                                        class="input-field-dark"
                                    />
                                </div>

                                <!-- Topic -->
                                <div>
                                    <label class="label-text-dark">Topic</label>
                                    <select
                                        v-model="form.topic"
                                        class="input-field-dark"
                                    >
                                        <option value="general">
                                            General Inquiry
                                        </option>
                                        <option value="support">
                                            Technical Support
                                        </option>
                                        <option value="billing">
                                            Billing Question
                                        </option>
                                        <option value="expert">
                                            Expert Application
                                        </option>
                                        <option value="partnership">
                                            Partnership
                                        </option>
                                    </select>
                                </div>

                                <!-- Message -->
                                <div>
                                    <label class="label-text-dark"
                                        >Message</label
                                    >
                                    <textarea
                                        v-model="form.message"
                                        rows="6"
                                        placeholder="How can we help you?"
                                        required
                                        class="input-field-dark resize-none"
                                    ></textarea>
                                </div>

                                <!-- Submit Button -->
                                <button
                                    type="submit"
                                    :disabled="submitting"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl transition-all shadow-lg shadow-blue-500/20 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                                >
                                    <span v-if="!submitting">Send Message</span>
                                    <span v-else>Sending...</span>
                                    <Send class="w-5 h-5" />
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FAQ Section -->
            <section class="relative py-24 px-6 bg-slate-950">
                <div class="max-w-4xl mx-auto">
                    <div class="text-center mb-12">
                        <p
                            class="text-sm font-bold text-blue-400 uppercase tracking-wider mb-4"
                        >
                            SUPPORT
                        </p>
                        <h2
                            class="text-3xl md:text-4xl font-bold text-white mb-4"
                        >
                            Frequently Asked Questions
                        </h2>
                    </div>

                    <FAQAccordion :items="faqItems" />
                </div>
            </section>
        </div>
    </MainLayout>
</template>
