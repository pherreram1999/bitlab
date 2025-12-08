<script setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    titulo: '',
    puntaje: 10,
    opciones: [
        { texto: '', info_adicional: '', tipo: 'libre' }
    ]
});
const indiceActual = ref(0);
const totalPreguntas = computed(() => form.opciones.length);
const preguntaActual = computed(() => form.opciones[indiceActual.value]);
function siguientePregunta() {
    if (indiceActual.value < totalPreguntas.value - 1) {
        indiceActual.value++;
    }
}
function anteriorPregunta() {
    if (indiceActual.value > 0) {
        indiceActual.value--;
    }
}
function agregarPregunta() {
    form.opciones.push({
        texto: '',
        info_adicional: '',
        tipo: 'libre'
    });
    indiceActual.value = form.opciones.length - 1;
}
function eliminarPreguntaActual() {
    if (totalPreguntas.value > 1) {
        form.opciones.splice(indiceActual.value, 1);
        if (indiceActual.value >= totalPreguntas.value) {
            indiceActual.value = totalPreguntas.value - 1;
        }
    }
}
function guardarReto() {
    form.post('/retos');
}
</script>

<template>
    <div class="min-h-screen bg-gray-100 flex flex-col md:flex-row">

        <header class="w-full md:w-auto bg-gray-100 p-4 flex justify-between items-center md:items-start md:flex-col border-b md:border-b-0">
            <div class="flex items-center gap-2">
                <img src="/img/bitlab_icon.webp" alt="Bitlab Icono" class="h-12 w-12 object-contain" />
                <span class="text-xl font-bold text-gray-800 tracking-wide hidden md:block">BITLAB</span>
            </div>

        </header>

        <main class="flex-1 flex items-center justify-center p-4">

            <div class="bg-white rounded-3xl shadow-xl w-full max-w-4xl p-8 md:p-12 min-h-[600px] flex flex-col md:flex-row gap-8 relative">
                <div class="hidden md:flex md:w-1/3 flex-col justify-center items-center opacity-90">
                    <img src="/img/BitLab_Isologo.webp" alt="Bitlab Logo" class="w-48 h-48 object-contain mb-4" />
                </div>

                <div class="flex-1 flex flex-col justify-center">
                    <h1 class="text-3xl font-bold text-orange-600 mb-8 text-center md:text-left">
                        Crear reto
                    </h1>
                    <div class="mb-4">
                        <input
                            v-model="form.titulo"
                            type="text"
                            placeholder="Título del reto (Ej. Examen Parcial)"
                            class="w-full border-b-2 border-gray-200 focus:border-orange-500 outline-none py-2 text-gray-600 bg-transparent transition"
                        >
                    </div>
                    <div class="space-y-6">
                        <div>
                            <input
                                v-model="preguntaActual.texto"
                                type="text"
                                :placeholder="`Pregunta ${indiceActual + 1}`"
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-200 focus:border-orange-500 outline-none transition"
                            >
                        </div>
                        <div>
                            <textarea
                                v-model="preguntaActual.info_adicional"
                                placeholder="Información adicional"
                                rows="4"
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-200 focus:border-orange-500 outline-none transition resize-none"
                            ></textarea>
                        </div>
                        <div class="flex items-center gap-6 mt-2">
                            <span class="text-gray-700 font-medium">Tipo de pregunta:</span>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" value="libre" v-model="preguntaActual.tipo" class="text-orange-500 focus:ring-orange-500 h-5 w-5">
                                <span class="text-gray-700">Libre</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" value="multiple" v-model="preguntaActual.tipo" class="text-orange-500 focus:ring-orange-500 h-5 w-5">
                                <span class="text-gray-700">Opción múltiple</span>
                            </label>
                        </div>
                    </div>
                    <div class="flex items-center justify-center mt-8 gap-4 select-none">
                        <button
                            @click="anteriorPregunta"
                            :disabled="indiceActual === 0"
                            class="text-gray-500 hover:text-orange-600 disabled:opacity-30 disabled:hover:text-gray-500 font-bold text-xl"
                        >
                            &lt;
                        </button>
                        <span class="text-gray-600 font-medium">
                            pregunta
                            <span class="border border-gray-300 rounded px-2 py-1 mx-1">{{ indiceActual + 1 }}</span>
                            de {{ totalPreguntas }}
                        </span>
                        <button
                            @click="siguientePregunta"
                            :disabled="indiceActual === totalPreguntas - 1"
                            class="text-gray-500 hover:text-orange-600 disabled:opacity-30 disabled:hover:text-gray-500 font-bold text-xl"
                        >
                            &gt;
                        </button>
                        <button
                            @click="agregarPregunta"
                            class="ml-2 w-8 h-8 rounded-full border-2 border-gray-400 text-gray-500 hover:border-orange-500 hover:text-orange-500 flex items-center justify-center transition"
                            title="Agregar nueva pregunta"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex justify-end gap-4 mt-12">
                        <button class="px-6 py-2 rounded-lg border border-gray-400 text-gray-600 hover:bg-gray-50 transition font-medium">
                            Cancelar
                        </button>
                        <button
                            @click="guardarReto"
                            :disabled="form.processing"
                            class="px-8 py-2 rounded-lg bg-orange-600 text-white hover:bg-orange-700 transition font-medium shadow-lg shadow-orange-200"
                        >
                            Terminar
                        </button>
                    </div>

                </div>
            </div>
        </main>
    </div>
</template>
