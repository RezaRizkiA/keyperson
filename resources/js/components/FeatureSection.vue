<script setup>
const features = [
    {
        title: 'Easy Scheduling',
        desc: 'No more back-and-forth emails. Clients can see your availability and book in seconds.',
        icon: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'
    },
    {
        title: 'Google Calendar Sync',
        desc: 'Automatic two-way sync keeps your schedule up-to-date and prevents double booking.',
        icon: 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'
    },
    {
        title: 'Secure Payments',
        desc: 'Handle payments automatically before the session starts. Supports various local payment methods.',
        icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' // Icon check
    },
    {
        title: 'Video Integration',
        desc: 'Meeting links are automatically generated and sent to both parties via email and dashboard.',
        icon: 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z'
    }
];
</script>

<template>
    <section class="py-16 lg:py-24 bg-slate-50 font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="font-display text-3xl md:text-4xl font-bold text-slate-900 mb-4">
                    Everything you need to run your practice
                </h2>
                <p class="text-slate-600 text-lg">
                    Focus on your clients, not the admin work. KeyPerson handles the logistics so you can deliver value.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div 
                    v-for="(feature, index) in features" 
                    :key="index"
                    class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group"
                >
                    <div class="w-12 h-12 bg-violet-50 rounded-xl flex items-center justify-center mb-6 group-hover:bg-violet-600 transition-colors duration-300">
                        <svg 
                            class="w-6 h-6 text-violet-600 group-hover:text-white transition-colors duration-300" 
                            fill="none" 
                            viewBox="0 0 24 24" 
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="feature.icon" />
                        </svg>
                    </div>

                    <h3 class="font-display text-xl font-bold text-slate-900 mb-3">
                        {{ feature.title }}
                    </h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        {{ feature.desc }}
                    </p>
                </div>
            </div>

        </div>
    </section>
</template>