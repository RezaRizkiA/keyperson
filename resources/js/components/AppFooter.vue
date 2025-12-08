<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    routes: Object,
    logoUrl: String,
    logoSmallUrl: String
});

const currentYear = new Date().getFullYear();

const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
};
</script>

<template>
    <footer class="bg-slate-900 text-slate-300 mt-auto relative font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-10 lg:gap-12">
                
                <div class="col-span-2 md:col-span-1">
                    <img :src="logoUrl" class="w-36 mb-8 brightness-0 invert" alt="KeyPerson Logo">
                    
                    <ul class="flex flex-col gap-4">
                        <li>
                            <Link :href="routes.home" class="text-base hover:text-white hover:translate-x-1 transition-all duration-200 inline-block">
                                Home
                            </Link>
                        </li>
                        <li>
                            <Link :href="routes.about" class="text-base hover:text-white hover:translate-x-1 transition-all duration-200 inline-block">
                                About List
                            </Link>
                        </li>
                        <li>
                            <Link :href="routes.support" class="text-base hover:text-white hover:translate-x-1 transition-all duration-200 inline-block">
                                Support
                            </Link>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-white font-bold text-lg mb-7">Forms</h3>
                    <ul class="flex flex-col gap-4">
                        <li>
                            <Link :href="routes.terms" class="text-base hover:text-white hover:translate-x-1 transition-all duration-200 inline-block">
                                Terms Of Service
                            </Link>
                        </li>
                        <li>
                            <Link :href="routes.privacy" class="text-base hover:text-white hover:translate-x-1 transition-all duration-200 inline-block">
                                Privacy Policy
                            </Link>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-white font-bold text-lg mb-7">Other Application</h3>
                    <ul class="flex flex-col gap-4">
                        <li>
                            <a href="https://trainingstudio.id/" target="_blank" class="text-base hover:text-violet-400 transition-colors flex items-center gap-2 group">
                                trainingstudio.id
                                <svg class="w-3 h-3 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                        </li>
                        <li>
                            <a href="https://kerjapintar.id/" target="_blank" class="text-base hover:text-violet-400 transition-colors flex items-center gap-2 group">
                                kerjapintar.id
                                <svg class="w-3 h-3 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                        </li>
                        <li>
                            <a href="https://profiling.id/" target="_blank" class="text-base hover:text-violet-400 transition-colors flex items-center gap-2 group">
                                profiling.id
                                <svg class="w-3 h-3 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                        </li>
                        <li>
                            <a href="https://shiokaya.com/" target="_blank" class="text-base hover:text-violet-400 transition-colors flex items-center gap-2 group">
                                shiokaya.com
                                <svg class="w-3 h-3 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-white font-bold text-lg mb-7">Follow us</h3>
                    <div class="flex gap-4">
                        <a href="javascript:void(0)" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-[#1877F2] hover:text-white transition-all duration-300 group">
                            <svg class="w-5 h-5 fill-slate-400 group-hover:fill-white transition-colors" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.791-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        
                        <a href="javascript:void(0)" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-black hover:text-white transition-all duration-300 group">
                            <svg class="w-5 h-5 fill-slate-400 group-hover:fill-white transition-colors" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.84 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>

                        <a href="javascript:void(0)" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-pink-600 hover:text-white transition-all duration-300 group">
                            <svg class="w-5 h-5 fill-slate-400 group-hover:fill-white transition-colors" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.069-4.85.069-3.204 0-3.584-.012-4.849-.069-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-800 mt-16 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-3">
                    
                    <img :src="logoSmallUrl" class="w-10 opacity-80" alt="logo small">
                    
                    <p class="text-slate-500 text-sm mb-0">
                        All rights reserved {{ currentYear }}
                    </p>
                </div>
                
                <div class="text-sm text-slate-500 text-center md:text-right">
                    <span class="opacity-70">Produced by</span>
                    <a href="" class="text-white hover:text-violet-400 font-medium ml-1 transition-colors">Kastara Parama Nusantara</a>
                </div>
            </div>
        </div>

        <button 
            @click="scrollToTop"
            class="fixed bottom-8 right-8 z-50 bg-violet-600 text-white w-12 h-12 rounded-full shadow-lg flex items-center justify-center hover:bg-violet-700 transition-all transform hover:-translate-y-1 hover:shadow-violet-500/50 focus:outline-none"
            aria-label="Back to top"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
            </svg>
        </button>
    </footer>
</template>