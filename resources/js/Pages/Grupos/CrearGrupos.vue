<script setup>
import { useForm } from '@inertiajs/vue3';
import bitlabLogo from '../../../img/bitlab_icon.webp';
import bitlabIsologo from '../../../img/bitLab_isologo.webp';

const form = useForm({
    nombre: '',
    descripcion: ''
});

function guardarGrupo() {
    form.post('/grupos');
}
</script>

<template>
    <div class="min-h-screen bg-gray-100 flex flex-col font-sans">

        <header class="w-full bg-gray-100 px-6 py-4 flex justify-between items-center border-b md:border-none">
            <div class="flex items-center gap-2">
                <img :src="bitlabLogo" alt="bitlab logo" class="h-8 w-auto">
                <h1 class="text-xl font-bold text-gray-900 tracking-tight">BITLAB</h1>
            </div>

        </header>

        <main class="flex-1 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-xl w-full max-w-4xl p-8 md:p-12 flex flex-col md:flex-row gap-12 items-center relative">

                <div class="hidden md:flex w-1/3 justify-center">
                    <img :src="bitlabIsologo" alt="Mascota Bitlab" class="w-64 h-64 object-contain" />
                </div>

                <div class="flex-1 w-full max-w-md">
                    <h1 class="text-3xl font-bold text-orange-500 mb-8 text-center md:text-left">
                        Crear Grupo
                    </h1>

                    <div class="space-y-6">
                        <div class="space-y-1">
                            <input
                                v-model="form.nombre"
                                type="text"
                                placeholder="Nombre del grupo"
                                class="w-full border border-gray-400 rounded-lg px-4 py-3 text-gray-800 placeholder-gray-500 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none transition bg-white"
                            >
                            <div v-if="form.errors.nombre" class="text-red-500 text-xs ml-1">{{ form.errors.nombre }}</div>
                        </div>

                        <div class="space-y-1">
                            <textarea
                                v-model="form.descripcion"
                                placeholder="Descripción del grupo"
                                rows="5"
                                class="w-full border border-gray-400 rounded-lg px-4 py-3 text-gray-800 placeholder-gray-500 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none transition resize-none bg-white"
                            ></textarea>
                            <div v-if="form.errors.descripcion" class="text-red-500 text-xs ml-1">{{ form.errors.descripcion }}</div>
                        </div>
                    </div>

                    <div class="flex justify-between gap-4 mt-8">
                        <button class="flex-1 px-6 py-2 rounded-lg border border-gray-400 text-gray-900 hover:bg-gray-50 transition font-medium">
                            Cancelar
                        </button>

                        <button
                            @click="guardarGrupo"
                            :disabled="form.processing"
                            class="flex-1 px-6 py-2 rounded-lg bg-[#E07A00] text-white hover:bg-orange-700 transition font-medium shadow-md flex justify-center items-center"
                        >
                            <span v-if="form.processing">Creando...</span>
                            <span v-else>Crear</span>
                        </button>
                    </div>
                </div>
            </div>
        </main>

    </div>
</template>
