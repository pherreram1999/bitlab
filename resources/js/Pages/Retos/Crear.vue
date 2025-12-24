<script setup>
import { ref, computed } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import bitlabLogo from '../../../img/bitlab_icon.webp';
import bitlabIsologo from '../../../img/bitLab_isologo.webp';

const form = useForm({
    titulo: '',
    descripcion: '',
    puntaje: 10,
    max_intentos: 1,
    fecha_limite: '',
    ayuda: '',
    opciones: [
        {
            texto: '',
            info_adicional: '',
            tipo: 'libre',
            alternativas: [
                {inciso: 'A', texto: '', correcta: false},
                {inciso: 'B', texto: '', correcta: false}
            ]
        }
    ]
});

const indiceActual = ref(0);
const totalPreguntas = computed(() => form.opciones.length);
const preguntaActual = computed(() => form.opciones[indiceActual.value]);

function marcarCorrecta(indexSeleccionado) {
    preguntaActual.value.alternativas.forEach((alt, i) => {
        alt.correcta = (i === indexSeleccionado);
    });
}

function siguientePregunta() {
    if (indiceActual.value < totalPreguntas.value - 1) indiceActual.value++;
}
function anteriorPregunta() {
    if (indiceActual.value > 0) indiceActual.value--;
}

function agregarPregunta() {
    form.opciones.push({
        texto: '',
        info_adicional: '',
        tipo: 'libre',
        alternativas: [
            {inciso: 'A', texto: '', correcta: false},
            {inciso: 'B', texto: '', correcta: false}
        ]
    });
    indiceActual.value = form.opciones.length - 1;
}

function agregarInciso(){
    const letras = ['A','B','C','D','E','F'];
    const pregunta = preguntaActual.value;
    const siguienteLetra = letras[pregunta.alternativas.length] || '?';
    pregunta.alternativas.push({ inciso: siguienteLetra, texto: '', correcta: false });
}

function eliminarInciso(index){
    const pregunta = preguntaActual.value;
    if (pregunta.alternativas.length > 2) {
        pregunta.alternativas.splice(index, 1);
        pregunta.alternativas.forEach((alt, i) => { alt.inciso = ['A','B','C','D','E','F'][i]; });
    }
}

function eliminarPreguntaActual() {
    if (totalPreguntas.value > 1) {
        form.opciones.splice(indiceActual.value, 1);
        if (indiceActual.value >= totalPreguntas.value) indiceActual.value = totalPreguntas.value - 1;
    }
}

function guardarReto() {
    form.post(route('retos.store'));
}
</script>

<template>
    <div class="min-h-screen bg-gray-100 flex flex-col md:flex-row">

        <header class="w-full md:w-auto bg-gray-100 p-4 flex justify-between items-center md:items-start md:flex-col border-b md:border-b-0">
            <div class="flex items-center gap-2">
                <img :src="bitlabLogo" alt="bitlab logo" class="h-10 w-auto">
                <h1 class="text-xl font-bold text-gray-800">BITLAB</h1>
            </div>
        </header>

        <main class="flex-1 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-xl w-full max-w-6xl p-6 md:p-12 min-h-[600px] flex flex-col md:flex-row gap-8 relative">

                <div class="hidden md:flex md:w-1/4 flex-col justify-center items-center opacity-90 border-r border-gray-100 pr-4">
                    <img :src="bitlabIsologo" alt="Mascota Bitlab" class="w-56 h-56 object-contain" />
                    <p class="mt-4 text-center text-gray-400 text-sm">Configura tu reto y añade las preguntas necesarias.</p>
                </div>

                <div class="flex-1 flex flex-col">
                    <h1 class="text-3xl font-bold text-orange-600 mb-6 text-center md:text-left">
                        Crear nuevo reto
                    </h1>

                    <div class="bg-gray-50 p-6 rounded-2xl mb-8 border border-gray-200">
                        <h2 class="text-sm font-bold text-gray-500 uppercase tracking-wide mb-4">Configuración General</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 mb-1">Título del Reto</label>
                                <input v-model="form.titulo" type="text" placeholder="Ej. Examen Parcial" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-orange-500 outline-none transition">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 mb-1">Descripción General</label>
                                <textarea v-model="form.descripcion" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-orange-500 outline-none transition resize-none"></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1">Puntaje Total</label>
                                <input v-model="form.puntaje" type="number" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-orange-500 outline-none transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1">Intentos Máximos</label>
                                <input v-model="form.max_intentos" type="number" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-orange-500 outline-none transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1">Fecha y Hora Límite</label>
                                <input
                                    v-model="form.fecha_limite"
                                    type="datetime-local"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-orange-500 outline-none transition text-gray-600"
                                >
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1">Ayuda</label>
                                <input v-model="form.ayuda" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-orange-500 outline-none transition">
                            </div>
                        </div>
                    </div>

                    <div class="flex-1">
                        <div class="space-y-4">
                            <input v-model="preguntaActual.texto" type="text" :placeholder="`Pregunta ${indiceActual + 1}`" class="w-full text-lg border-b-2 border-gray-300 px-2 py-2 focus:border-orange-500 outline-none">
                            <textarea v-model="preguntaActual.info_adicional" rows="3" class="w-full border border-gray-300 rounded-xl px-4 py-3" placeholder="Info adicional"></textarea>

                            <div class="flex items-center gap-6 pt-2">
                                <span class="text-gray-700 font-medium text-sm">Respuesta:</span>
                                <label class="flex items-center gap-2 cursor-pointer select-none text-sm">
                                    <input type="radio" value="libre" v-model="preguntaActual.tipo" class="text-orange-500 focus:ring-orange-500 h-4 w-4">
                                    <span>Texto Libre</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer select-none text-sm">
                                    <input type="radio" value="multiple" v-model="preguntaActual.tipo" class="text-orange-500 focus:ring-orange-500 h-4 w-4">
                                    <span>Opción Múltiple</span>
                                </label>
                            </div>

                            <div v-if="preguntaActual.tipo === 'multiple'" class="bg-orange-50 p-4 rounded-xl border border-orange-100 mt-2">
                                <div v-for="(alt, index) in preguntaActual.alternativas" :key="index" class="flex items-center gap-2 mb-2">
                                    <span class="font-bold text-orange-600 w-6 text-center">{{ alt.inciso }}</span>

                                    <input v-model="alt.texto" type="text" class="flex-1 border border-gray-300 rounded px-3 py-1 text-sm focus:border-orange-500 outline-none" placeholder="Escribe la opción...">

                                    <label class="flex items-center cursor-pointer text-xs text-gray-500 gap-1 select-none">
                                        <input
                                            type="radio"
                                            :name="`correcta_pregunta_${indiceActual}`"
                                            :checked="alt.correcta"
                                            @change="marcarCorrecta(index)"
                                            class="text-orange-600 focus:ring-orange-500 cursor-pointer"
                                        >
                                        <span :class="alt.correcta ? 'font-bold text-orange-700' : ''">Correcta</span>
                                    </label>

                                    <button @click="eliminarInciso(index)" v-if="preguntaActual.alternativas.length > 2" class="text-red-400 hover:text-red-600 font-bold px-2">✕</button>
                                </div>
                                <button @click="agregarInciso" v-if="preguntaActual.alternativas.length < 6" class="text-xs font-bold text-orange-600 hover:text-orange-800 mt-1 flex items-center gap-1 pl-8">
                                    + Agregar opción
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-center mt-8 gap-4 select-none pt-6 border-t border-gray-100">
                        <button @click="anteriorPregunta" :disabled="indiceActual === 0" class="text-gray-400 hover:text-orange-600 disabled:opacity-30 font-bold text-2xl transition">&lt;</button>
                        <span class="text-gray-500 font-medium text-sm">Pregunta {{ indiceActual + 1 }} de {{ totalPreguntas }}</span>
                        <button @click="siguientePregunta" :disabled="indiceActual === totalPreguntas - 1" class="text-gray-400 hover:text-orange-600 disabled:opacity-30 font-bold text-2xl transition">&gt;</button>
                        <button @click="agregarPregunta" class="ml-2 w-8 h-8 rounded-full border-2 border-gray-300 text-gray-400 hover:border-orange-500 hover:text-orange-500 flex items-center justify-center transition">+</button>
                    </div>

                    <div class="flex justify-end gap-4 mt-8">
                        <Link
                            :href="route('profesor.retos')"
                            class="px-6 py-2 rounded-lg border border-gray-300 text-gray-500 hover:bg-gray-50 transition font-medium text-sm flex items-center justify-center"
                        >
                            Cancelar
                        </Link>

                        <button @click="guardarReto" :disabled="form.processing" class="px-8 py-2 rounded-lg bg-orange-600 text-white hover:bg-orange-700 transition font-medium shadow-lg shadow-orange-200 text-sm">
                            {{ form.processing ? 'Guardando...' : 'Crear Reto' }}
                        </button>
                    </div>

                </div>
            </div>
        </main>
    </div>
</template>
