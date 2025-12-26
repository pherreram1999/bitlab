<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import nibbitAsk from "@/img/nibbit_ask.webp";
import {computed, ref} from "vue";

interface RetoAlternativa {
    inciso: string;
    texto: string;
    correcta: boolean;
}

interface RetoOpcion {
    texto: string;
    respuesta?: RetoAlternativa;
    alternativas: RetoAlternativa[]
}
interface Reto {
    titulo: string;
    descripcion: string;
    puntaje: string;
    opciones: RetoOpcion[]
}

interface Props {
    reto: Reto
}

const props = defineProps<Props>()

const indexReactivo = ref<number>(0)

const reactivos = ref(props.reto.opciones.map(op => ({...op, respuesta: null})))

const reactivoActual = computed<RetoOpcion>(() => reactivos.value[indexReactivo.value])

const changeOption = (step: number) => indexReactivo.value += step

</script>

<template>
    <app-layout>
        <div class="container mx-auto">
            <div>

            </div>
            <div class="flex bg-white p-4 rounded shadow">
                <div class="grow flex flex-col">
                    <div class="grow">
                        <h1 class="text-2xl font-bold text-center my-4">{{ reactivoActual.texto }}</h1>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 ask-container">
                            <button
                                :class="{'border-amber-400': reactivoActual?.respuesta?.inciso === alt.inciso}"
                                @click.prevent="reactivoActual.respuesta = alt"
                                class="px-4 py-2 rounded bg-stone-100 border-4 transition-all"
                                v-for="alt of reactivoActual.alternativas">
                                {{ alt.texto }}
                            </button>
                        </div>
                    </div>
                    <div class="shrink-0 flex justify-center gap-6">
                        <button class="flex gap-2"
                                @click.prevent="changeOption(-1)"
                                :disabled="indexReactivo <= 0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                            </svg>
                            Anterior
                        </button>
                        <span> {{ indexReactivo + 1 }} / {{ reactivos.length }}</span>
                        <button
                            :disabled="indexReactivo >= reactivos.length - 1"
                            @click.prevent="changeOption(1)"
                            class="flex gap-2">
                            siguiente
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>
                    </div>
                </div>
                <img class="hidden md:block w-[24rem] shrink-0" :src="nibbitAsk" alt="nibbit ask">
            </div>
        </div>
    </app-layout>
</template>

<style scoped>
/* Contenedor principal */


/* Estilo base de los botones */
.ask-container button {
    position: relative;
    display: flex;
    align-items: center;
    padding: 15px 15px 15px 60px; /* Mucho padding a la izquierda para la figura */
    border-radius: 8px;
    color: white;
    font-family: sans-serif;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    text-align: left;
}

/* Configuración común para todas las figuras (el pseudo-elemento) */
.ask-container button::before {
    content: "";
    position: absolute;
    left: 15px; /* Posicionado dentro del botón a la izquierda */
    width: 30px;
    height: 30px;
    background-color: rgba(255, 255, 255, 0.4); /* Efecto transparente de la foto */
}

/* --- Colores y Figuras específicas --- */

/* 1. Rojo - Pentágono */
.ask-container button:nth-child(1) { background-color: #D9534F; }
.ask-container button:nth-child(1)::before {
    clip-path: polygon(50% 0%, 100% 38%, 82% 100%, 18% 100%, 0% 38%);
}

/* 2. Azul - Rombo */
.ask-container button:nth-child(2) { background-color: #3498DB; }
.ask-container button:nth-child(2)::before {
    clip-path: polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%);
}

/* 3. Naranja - Círculo */
.ask-container button:nth-child(3) { background-color: #D6964E; }
.ask-container button:nth-child(3)::before {
    border-radius: 50%;
}

/* 4. Verde - Triángulo */
.ask-container button:nth-child(4) { background-color: #27AE60; }
.ask-container button:nth-child(4)::before {
    clip-path: polygon(50% 0%, 0% 100%, 100% 100%);
}

/* 5. Púrpura - Hexágono */
.ask-container button:nth-child(5) { background-color: #8E44AD; }
.ask-container button:nth-child(5)::before {
    clip-path: polygon(25% 0%, 75% 0%, 100% 50%, 75% 100%, 25% 100%, 0% 50%);
}

.ask-container button:nth-child(6) { background-color: #C2185B; }
.ask-container button:nth-child(6)::before {
    clip-path: polygon(50% 0%, 61% 39%, 100% 50%, 61% 61%, 50% 100%, 39% 61%, 0% 50%, 39% 39%);
}
</style>
