<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import nibbitAsk from "@/img/nibbit_ask.webp";
import nibbitHappy from "@/img/Nibbit_Happy.webp";
import nibbitSad from "@/img/Nibbit_Sad.webp";
import {computed, onUnmounted, ref} from "vue";
import { Link } from '@inertiajs/vue3';

interface RetoAlternativa {
    inciso: string;
    texto: string;
    correcta: boolean; // Definido por el docente
}

interface RetoOpcion {
    texto: string;
    respuesta?: RetoAlternativa | null;
    alternativas: RetoAlternativa[];
}

interface Reto {
    id: number;
    titulo: string;
    descripcion: string;
    puntaje: number;
    tiempo_limite: number;
    max_intentos: number;
    ayuda: string;
    opciones: RetoOpcion[];
}

const props = defineProps<{ reto: Reto }>()

// Estados
const hasStarted = ref(false);
const isFinished = ref(false);
const showSidebar = ref(false);
const indexReactivo = ref(0);
const attemptsMade = ref(0);
const timeLeft = ref(0);
let timerInterval: any = null;

// Reactivos con estado de respuesta del alumno
const reactivos = ref(props.reto.opciones.map(op => ({...op, respuesta: null})));

// Computados
const reactivoActual = computed(() => reactivos.value[indexReactivo.value]);
const allAnswered = computed(() => reactivos.value.every(r => r.respuesta !== null));

// Conteo de resultados (Basado en el booleano 'correcta')
const correctCount = computed(() => reactivos.value.filter(r => r.respuesta?.correcta === true).length);
const failureCount = computed(() => reactivos.value.length - correctCount.value);

// Lógica de mensaje: "si tiene mas fallas, mostrar ayuda, de lo contrario excelente"
const showHelpMessage = computed(() => failureCount.value > correctCount.value);

const formattedTime = computed(() => {
    const m = Math.floor(timeLeft.value / 60).toString().padStart(2, '0');
    const s = (timeLeft.value % 60).toString().padStart(2, '0');
    return `${m}:${s}`;
});

const canRetry = computed(() => attemptsMade.value < props.reto.max_intentos);

// Métodos
const startReto = () => {
    hasStarted.value = true;
    timeLeft.value = props.reto.tiempo_limite * 60;
    timerInterval = setInterval(() => {
        if (timeLeft.value > 0) {
            timeLeft.value--;
        } else {
            finishReto();
        }
    }, 1000);
};

const finishReto = () => {
    clearInterval(timerInterval);
    isFinished.value = true;
    attemptsMade.value++;
    saveResult();
};

const retryReto = () => {
    isFinished.value = false;
    hasStarted.value = false;
    indexReactivo.value = 0;
    reactivos.value = props.reto.opciones.map(op => ({...op, respuesta: null}));
};

const saveResult = () => {
    // Función para invocar al finalizar (aquí se puede integrar con la API)
    console.log("Invocando guardado de resultados...", {
        reto_id: props.reto.id,
        aciertos: correctCount.value,
        fallas: failureCount.value,
        completado: allAnswered.value,
        intento: attemptsMade.value
    });
};

onUnmounted(() => clearInterval(timerInterval));
</script>

<template>
    <app-layout :title="reto.titulo">
        <div class="min-h-[calc(100vh-65px)] bg-gray-50">
            
            <!-- 1. PANTALLA INICIAL -->
            <div v-if="!hasStarted" class="flex items-center justify-center p-4 min-h-[calc(100vh-65px)]">
                <div class="bg-white p-6 md:p-8 rounded-3xl shadow-xl max-w-xl w-full text-center border border-gray-200">
                    <h1 class="text-2xl md:text-3xl font-black text-gray-800 mb-4">{{ reto.titulo }}</h1>
                    <p class="text-gray-500 mb-6 md:mb-8 text-sm md:text-base">{{ reto.descripcion }}</p>
                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <div class="bg-orange-50 p-4 rounded-2xl border border-orange-100">
                            <span class="block text-2xl mb-1">⏱️</span>
                            <span class="text-sm font-bold text-orange-700">{{ reto.tiempo_limite }} min</span>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-2xl border border-blue-100">
                            <span class="block text-2xl mb-1">🔄</span>
                            <span class="text-sm font-bold text-blue-700">{{ reto.max_intentos }} Intentos</span>
                        </div>
                    </div>
                    <button @click="startReto" class="w-full py-4 bg-orange-600 text-white rounded-2xl font-bold text-xl hover:bg-orange-700 transition shadow-lg shadow-orange-200">
                        Comenzar Ahora
                    </button>
                </div>
            </div>

            <!-- 2. JUEGO ACTIVO -->
            <div v-else-if="hasStarted && !isFinished" class="flex flex-col md:flex-row h-full min-h-[calc(100vh-65px)]">
                <!-- Sidebar -->
                <aside :class="['fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform transition md:translate-x-0 md:static', showSidebar ? 'translate-x-0' : '-translate-x-full']">
                    <div class="p-4 border-b border-gray-200 font-bold flex justify-between items-center">
                        Preguntas 
                        <button @click="showSidebar=false" class="md:hidden text-gray-400">✕</button>
                    </div>
                    <nav class="p-2 space-y-1 overflow-y-auto max-h-[calc(100vh-120px)]">
                        <button v-for="(r, i) in reactivos" :key="i" @click="indexReactivo = i; showSidebar=false"
                            :class="['w-full text-left p-3 rounded-xl text-sm flex justify-between items-center transition border', indexReactivo === i ? 'bg-orange-600 text-white shadow-md border-orange-500' : 'hover:bg-gray-100 text-gray-600 border-transparent']">
                            <span class="truncate pr-2">{{ i + 1 }}. {{ r.texto }}</span>
                            <span v-if="r.respuesta" class="shrink-0 text-xs">✅</span>
                        </button>
                    </nav>
                </aside>

                <!-- Área Central -->
                <main class="flex-1 p-2 md:p-8 relative pb-20 md:pb-24 flex flex-col">
                    <div class="max-w-4xl mx-auto w-full mb-4 flex justify-between items-end">
                        <button @click="showSidebar=true" class="md:hidden bg-white px-3 py-2 rounded-lg shadow border border-gray-200 font-bold text-orange-600 flex items-center gap-1 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                            Preguntas
                        </button>
                        
                        <!-- Contador Visible arriba a la derecha de la carta -->
                        <div class="ml-auto bg-white px-4 py-1 md:px-6 md:py-2 rounded-2xl shadow-sm border border-orange-200 flex items-center gap-2 md:gap-3">
                            <span class="text-xs font-black text-orange-400 uppercase tracking-tighter">Tiempo</span>
                            <span class="font-mono text-xl md:text-2xl font-bold text-orange-600">{{ formattedTime }}</span>
                        </div>
                    </div>
                    
                    <div class="max-w-4xl mx-auto w-full bg-white rounded-3xl shadow-xl overflow-hidden flex flex-col md:flex-row border border-gray-200 min-h-[400px] md:min-h-[450px]">
                        <div class="p-4 md:p-12 flex-1 flex flex-col justify-between">
                            <div>
                                <span class="text-orange-500 font-bold text-xs md:text-sm uppercase tracking-widest">Pregunta {{ indexReactivo + 1 }}</span>
                                <h2 class="text-xl md:text-3xl font-bold text-gray-800 mt-2 mb-4 md:mb-8 leading-tight">{{ reactivoActual.texto }}</h2>
                                
                                <div class="space-y-2 md:space-y-3 ask-container">
                                    <button v-for="alt in reactivoActual.alternativas" :key="alt.inciso"
                                        @click="reactivoActual.respuesta = alt"
                                        :class="['w-full transition-all group border border-gray-200', reactivoActual.respuesta?.inciso === alt.inciso ? 'ring-2 md:ring-4 ring-orange-300 scale-[1.01] md:scale-[1.02]' : '']">
                                        <div class="relative z-10 flex items-center justify-between w-full">
                                            <span class="text-sm md:text-base">{{ alt.texto }}</span>
                                            <div v-if="reactivoActual.respuesta?.inciso === alt.inciso" class="bg-white/30 rounded-full p-1 border border-white/20">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                                    </button>
                                </div>
                            </div>

                            <div class="flex justify-between mt-8 md:mt-12 border-t border-gray-200 pt-4 md:pt-6">
                                <button @click="indexReactivo--" :disabled="indexReactivo===0" class="font-bold text-gray-400 disabled:opacity-20 flex items-center gap-1 md:gap-2 hover:text-gray-600 text-sm md:text-base">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                                    </svg>
                                    Anterior
                                </button>
                                <button @click="indexReactivo++" :disabled="indexReactivo===reactivos.length-1" class="font-bold text-orange-600 disabled:opacity-20 flex items-center gap-1 md:gap-2 hover:text-orange-700 text-sm md:text-base">
                                    Siguiente
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="hidden md:flex w-64 bg-gray-50 items-center justify-center border-l border-gray-200 p-8">
                            <img :src="nibbitAsk" class="w-full opacity-80" alt="Nibbit Ask" />
                        </div>
                    </div>

                    <!-- Footer Sticky -->
                    <div class="fixed bottom-0 left-0 md:left-64 right-0 bg-white p-3 md:p-4 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center shadow-[0_-4px_10px_rgba(0,0,0,0.05)] gap-3 md:gap-4 z-20">
                        <span class="text-xs md:text-sm font-bold text-gray-400">
                            <span class="text-orange-600">{{ reactivos.filter(r=>r.respuesta).length }}</span> de {{ reactivos.length }} respondidas
                        </span>
                        <button @click="finishReto" :disabled="!allAnswered"
                            :class="['px-8 py-2 md:px-10 md:py-3 rounded-xl font-bold text-white transition-all w-full sm:w-auto border border-transparent text-sm md:text-base', allAnswered ? 'bg-green-600 hover:bg-green-700 shadow-lg shadow-green-100' : 'bg-gray-300 cursor-not-allowed opacity-70']">
                            {{ allAnswered ? 'Finalizar Reto' : 'Faltan preguntas' }}
                        </button>
                    </div>
                </main>
            </div>

            <!-- 3. RESULTADOS -->
            <div v-else class="p-4 md:p-6 flex items-center justify-center min-h-[calc(100vh-65px)]">
                <div class="bg-white rounded-3xl shadow-2xl max-w-4xl w-full overflow-hidden flex flex-col md:flex-row border border-gray-200">
                    <!-- Score Card -->
                    <div :class="['p-8 md:p-10 md:w-1/3 text-center text-white flex flex-col items-center justify-center transition-colors duration-500', showHelpMessage ? 'bg-orange-600' : 'bg-green-600']">
                         <!-- Imagen Dinámica -->
                        <img 
                            :src="showHelpMessage ? nibbitSad : nibbitHappy" 
                            class="w-32 h-32 md:w-40 md:h-40 object-contain mb-4 drop-shadow-lg"
                            alt="Resultado"
                        />

                        <h2 class="text-3xl md:text-4xl font-black mb-2">{{ showHelpMessage ? '¡Ánimo!' : '¡Excelente!' }}</h2>
                        <div class="text-5xl md:text-7xl font-black my-4 md:my-6">{{ correctCount }}/{{ reactivos.length }}</div>
                        <p class="mb-6 md:mb-8 opacity-90 text-base md:text-lg">
                            {{ showHelpMessage ? 'Necesitas practicar un poco más.' : 'Has demostrado un gran dominio del tema.' }}
                        </p>
                        
                        <div v-if="showHelpMessage && reto.ayuda" class="bg-black/20 p-4 rounded-2xl text-sm text-left mb-6 w-full backdrop-blur-sm border border-white/10">
                            <strong class="block mb-1 text-xs uppercase opacity-70">💡 Ayuda del docente:</strong> 
                            {{ reto.ayuda }}
                        </div>

                        <button v-if="canRetry" @click="retryReto" class="w-full py-3 md:py-4 bg-white text-gray-900 rounded-2xl font-bold hover:bg-gray-100 transition shadow-xl flex items-center justify-center gap-2 border border-gray-200 text-sm md:text-base">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            Reintentar Reto
                        </button>
                        <p v-else class="text-sm opacity-60 italic mt-4">Has agotado tus intentos permitidos.</p>
                    </div>

                    <!-- Feedback List -->
                    <div class="p-6 md:p-8 md:w-2/3 max-h-[60vh] md:max-h-[80vh] overflow-y-auto bg-gray-50 border-l border-gray-200">
                        <h3 class="font-bold text-lg md:text-xl mb-6 text-gray-800 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .415.162.798.425 1.081.263.283.629.454 1.033.454.404 0 .77-.171 1.033-.454.263-.283.425-.666.425-1.081 0-.231-.035-.454-.1-.664m-5.801 0A2.251 2.251 0 0 1 13.5 2.25c1.035 0 1.912.7 2.153 1.652m-5.801 0A2.25 2.25 0 0 0 9 6.108V19.5a2.25 2.25 0 0 0 2.25 2.25h5.25a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08" />
                            </svg>
                            Resumen de respuestas
                        </h3>
                        <div class="space-y-4">
                            <div v-for="(r, i) in reactivos" :key="i" class="p-4 md:p-5 rounded-2xl border bg-white shadow-sm flex gap-3 md:gap-4 transition-all hover:shadow-md border-gray-200">
                                <div :class="['shrink-0 w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center text-base md:text-lg border', r.respuesta?.correcta ? 'bg-green-100 text-green-600 border-green-200' : 'bg-red-100 text-red-600 border-red-200']">
                                    {{ r.respuesta?.correcta ? '✓' : '✕' }}
                                </div>
                                <div class="flex-1">
                                    <p class="font-bold text-gray-800 leading-tight text-sm md:text-base">{{ i+1 }}. {{ r.texto }}</p>
                                    <div class="mt-2 space-y-1">
                                        <p class="text-xs md:text-sm text-gray-600">Tu respuesta: <span :class="r.respuesta?.correcta ? 'text-green-600 font-bold' : 'text-red-600 font-bold'">{{ r.respuesta?.texto || 'No respondida' }}</span></p>
                                        <p v-if="!r.respuesta?.correcta" class="text-xs md:text-sm p-2 md:p-3 bg-green-50 rounded-xl text-green-800 mt-2 border border-green-100">
                                            <span class="font-bold">Respuesta correcta:</span> {{ r.alternativas.find(a=>a.correcta)?.texto }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-8 md:mt-10 flex justify-center border-t border-gray-200 pt-6">
                            <Link :href="route('dashboard')" class="font-bold text-gray-400 hover:text-gray-600 transition flex items-center gap-2 text-sm md:text-base">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                                </svg>
                                Volver al Panel Principal
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </app-layout>
</template>

<style scoped>
.ask-container button {
    position: relative;
    display: flex;
    align-items: center;
    padding: 14px 14px 14px 50px; /* Reduced for mobile */
    border-radius: 12px;
    color: white;
    font-size: 1rem;
    font-weight: 700;
    text-align: left;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
}

/* Adjust padding for desktop */
@media (min-width: 768px) {
    .ask-container button {
        padding: 18px 18px 18px 65px;
        border-radius: 16px;
        font-size: 1.1rem;
    }
}

.ask-container button::before {
    content: ""; position: absolute; left: 15px; top: 50%; transform: translateY(-50%);
    width: 24px; height: 24px; background-color: rgba(255, 255, 255, 0.9);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

@media (min-width: 768px) {
    .ask-container button::before {
        left: 20px;
        width: 32px; height: 32px;
    }
}

/* Figuras Geométricas */
.ask-container button:nth-child(1) { background-color: #EF4444; } /* Rojo - Pentágono */
.ask-container button:nth-child(1)::before { clip-path: polygon(50% 0%, 100% 38%, 82% 100%, 18% 100%, 0% 38%); }

.ask-container button:nth-child(2) { background-color: #3B82F6; } /* Azul - Rombo */
.ask-container button:nth-child(2)::before { clip-path: polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%); }

.ask-container button:nth-child(3) { background-color: #F59E0B; } /* Naranja - Círculo */
.ask-container button:nth-child(3)::before { border-radius: 50%; }

.ask-container button:nth-child(4) { background-color: #10B981; } /* Verde - Triángulo */
.ask-container button:nth-child(4)::before { clip-path: polygon(50% 0%, 0% 100%, 100% 100%); }

.ask-container button:nth-child(5) { background-color: #8B5CF6; } /* Púrpura - Hexágono */
.ask-container button:nth-child(5)::before { clip-path: polygon(25% 0%, 75% 0%, 100% 50%, 75% 100%, 25% 100%, 0% 50%); }

.ask-container button:nth-child(6) { background-color: #EC4899; } /* Rosa - Estrella */
.ask-container button:nth-child(6)::before { clip-path: polygon(50% 0%, 61% 39%, 100% 50%, 61% 61%, 50% 100%, 39% 61%, 0% 50%, 39% 39%); }
</style>
