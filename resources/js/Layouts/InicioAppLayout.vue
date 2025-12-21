<script setup lang="ts">

import {Link} from "@inertiajs/vue3";
import ApplicationMark from "@/Components/ApplicationMark.vue";
</script>

<template>
    <nav class="border border-transparent border-b-[#BBC2CF] bg-[#E5EDF9] fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <Link :href="'/'">
                    <ApplicationMark class="block h-9 w-auto" />
                </Link>

                <!-- Menu Toggle (Mobile) -->
                <button @click="toggleMenu" class="hidden text-[#006699] text-2xl focus:outline-none">
                    <span v-if="!menuOpen">☰</span>
                    <span v-else>✕</span>
                </button>

                <!-- Desktop Navigation -->
                <div class="md:flex items-center gap-8">
                    <slot name="inic"/>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div v-show="menuOpen" class="md:hidden pb-4 space-y-3">
                <a @click="closeMenu" href="#features" class="block text-gray-600 hover:text-[#006699] transition-colors py-2">Características</a>
                <a @click="closeMenu" href="#about" class="block text-gray-600 hover:text-[#006699] transition-colors py-2">Acerca de</a>
                <a href="/login" class="block w-full text-center px-6 py-2 bg-[#006699] text-white rounded-lg hover:bg-gray-700 transition-colors">
                    Iniciar Sesión
                </a>
            </div>
        </div>
    </nav>
    <div>
        <slot/>
    </div>
</template>

<style scoped>

</style>
