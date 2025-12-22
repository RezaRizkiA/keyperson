<script setup>
import { Star } from "lucide-vue-next";
import { Link } from "@inertiajs/vue3";

defineProps({
    expert: {
        type: Object,
        required: true,
    },
    reviews: {
        type: Array,
        default: () => [],
    },
    reviewsCount: {
        type: Number,
        default: 0,
    },
});
</script>

<template>
    <div>
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2
                    class="text-sm font-bold text-blue-400 uppercase tracking-wider"
                >
                    CLIENT REVIEWS
                </h2>
                <p class="text-slate-400 text-sm mt-1">
                    {{ reviewsCount }} total reviews · Average rating:
                    {{ expert.expert?.rating || 0 }}
                </p>
            </div>
        </div>

        <!-- Reviews Grid -->
        <div
            v-if="reviews && reviews.length > 0"
            class="grid grid-cols-1 md:grid-cols-2 gap-6"
        >
            <div
                v-for="review in reviews"
                :key="review.id"
                class="bg-slate-900/50 backdrop-blur-sm border border-blue-500/20 rounded-2xl p-6 hover:border-blue-500/40 transition-all"
            >
                <!-- Reviewer Info -->
                <div class="flex items-start gap-4 mb-4">
                    <div
                        class="w-12 h-12 bg-slate-800 rounded-full flex items-center justify-center shrink-0"
                    >
                        <span class="text-lg font-bold text-blue-400">
                            {{
                                review.user?.client?.company_name?.charAt(0) ||
                                review.user?.name?.charAt(0) ||
                                "U"
                            }}
                        </span>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-white">
                            {{
                                review.user?.client?.company_name ||
                                review.user?.name ||
                                "Anonymous"
                            }}
                        </h4>
                        <p class="text-sm text-slate-400">
                            {{ review.skill?.name || "General Session" }} ·
                            {{
                                new Date(review.created_at).toLocaleDateString(
                                    "id-ID",
                                    {
                                        year: "numeric",
                                        month: "short",
                                    }
                                )
                            }}
                        </p>
                    </div>
                    <!-- Stars -->
                    <div class="flex gap-0.5">
                        <Star
                            v-for="i in 5"
                            :key="i"
                            :class="
                                i <= review.rating
                                    ? 'fill-yellow-400 text-yellow-400'
                                    : 'text-slate-700'
                            "
                            class="w-4 h-4"
                        />
                    </div>
                </div>

                <!-- Review Text -->
                <p
                    v-if="review.review_text"
                    class="text-slate-300 leading-relaxed"
                >
                    "{{ review.review_text }}"
                </p>
            </div>
        </div>

        <!-- Empty State -->
        <div
            v-else
            class="bg-slate-900/50 backdrop-blur-sm border border-blue-500/20 rounded-2xl p-12 text-center"
        >
            <div
                class="w-16 h-16 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4"
            >
                <Star class="w-8 h-8 text-slate-600" />
            </div>
            <h3 class="text-xl font-bold text-white mb-2">No Reviews Yet</h3>
            <p class="text-slate-400">
                Be the first to leave a review after your session!
            </p>
        </div>
    </div>
</template>
