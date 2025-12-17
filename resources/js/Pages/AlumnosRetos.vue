<script setup>
import { ref } from "vue";
import GruposLayout from "@/Layouts/GruposLayout.vue";

const selectedChallenge = ref(0);
const challenges = [
    { title: "Reto de ejemplo", progress: "05/07/35", points: "120 pts", status: "70%" },
    { title: "Reto de ejemplo", progress: "05/07/35", points: "100 pts", status: "Pendiente" },
];

function onChangeTab(tab) {
    // aquí luego puedes navegar con Inertia a la ruta de miembros
    // router.visit(...)
    console.log("tab:", tab);
}
</script>

<template>
    <GruposLayout
        title="Retos"
        activeTab="retos"
        groupCode="18473938"
        studentName="Nombre del alumno"
        :totalPoints="220"
        :avgPercent="65"
        groupName="NOMBRE DEL GRUPO"
        groupDate="12/11/2025"
        @changeTab="onChangeTab"
    >
        <!-- Bloque Retos (solo este cambia por vista) -->
        <section class="mt-4 rounded-xl" :style="{ width: '850px', background: '#E5EDF9' }">
            <div class="space-y-5">
                <div
                    v-for="(c, idx) in challenges"
                    :key="idx"
                    class="challengeCard"
                    :class="{ 'challengeCard--active': selectedChallenge === idx }"
                    :style="{ width: '850px', height: '69px' }"
                    @click="selectedChallenge = idx"
                >
                    <div class="grid grid-cols-12 items-center h-full px-8">
                        <div class="col-span-4 font-semibold" :style="{ color: '#000000' }">{{ c.title }}</div>
                        <div class="col-span-3 text-center" :style="{ color: '#000000' }">{{ c.progress }}</div>
                        <div class="col-span-2 text-center font-semibold" :style="{ color: '#000000' }">{{ c.points }}</div>
                        <div class="col-span-2 text-center" :style="{ color: '#000000' }">{{ c.status }}</div>
                    </div>
                </div>
            </div>
        </section>
    </GruposLayout>
</template>

<style scoped>
.challengeCard{
    background: #FFFFFF;
    border: 3px solid #BBC2CF;   /* estado normal */
    border-radius: 12px;
    cursor: pointer;
    user-select: none;
    transition: border-color 120ms ease;
    box-sizing: border-box;
}

/* Hover: borde verde */
.challengeCard:hover{
    border-color: #3FD99E;
}

/* Activo (click): se queda verde */
.challengeCard--active{
    border-color: #3FD99E;
}
</style>
