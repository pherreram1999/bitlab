<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import ApplicationMark from "@/Components/ApplicationMark.vue";

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in" />
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
                    <a href="/register" class="inline-flex items-center px-2 sm:px-4 py-2 bg-[#E17101] border border-transparent rounded-md font-semibold text-[10px] sm:text-xs text-white tracking-widest hover:bg-[#e78728] focus:bg-[#e78728] active:bg-[#c46100] focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                        ¿No tienes cuenta? Registrate
                    </a>
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
    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>
        <div class="text-3xl sm:text-4xl font-bold pb-6 text-[#E17101] flex justify-center">
            ¡Te damos la bienvenida!
        </div>
        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Correo" />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    :placeholder="'usuario@ejemplo.com'"
                    required
                    autofocus
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Contraseña" />
                <TextInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    :placeholder="'12345678'"
                    required
                    autocomplete="current-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center mt-1">
                    <Checkbox v-model:checked="form.remember" name="remember" />
                    <span class="ms-2 text-sm text-gray-600">Recuerdame</span>
                </label>
            </div>

            <div class="flex items-center justify-center mt-10 lg:mt-4 gap-2 sm:gap-7 lg:gap-0">
                <Link v-if="canResetPassword" :href="route('password.request')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Forgot your password?
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Iniciar sesión
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>
