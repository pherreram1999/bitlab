<script setup>
import { ref } from "vue";
import GruposLayout from "@/Layouts/GruposLayout.vue";

// Selección de miembro (borde verde hover + click)
const selectedMember = ref(0);

// Dummy data (solo UI)
const members = ref([
    { name: "Miembro 2", percent: "70%" },
    { name: "Miembro 1", percent: "30%" },
]);

function onChangeTab(tab) {
    // Aquí luego navegas con Inertia al cambiar tab
    // Ejemplo (cuando lo conectes):
    // if (tab === "retos") router.visit(route('alumno.retos', { group: ... }));
    // if (tab === "miembros") router.visit(route('alumno.miembros', { group: ... }));
    console.log("tab:", tab);
}
</script>

<template>
    <GruposLayout
        title="Miembros"
        activeTab="miembros"
        groupCode="18473938"
        studentName="Nombre del alumno"
        :totalPoints="220"
        :avgPercent="65"
        groupName="NOMBRE DEL GRUPO"
        groupDate="12/11/2025"
        @changeTab="onChangeTab"
    >
        <!-- Bloque Miembros (solo este cambia) -->
        <section class="mt-4 rounded-xl" :style="{ width: '850px', background: '#E5EDF9' }">
            <div class="space-y-5">
                <div
                    v-for="(m, idx) in members"
                    :key="idx"
                    class="memberCard"
                    :class="{ 'memberCard--active': selectedMember === idx }"
                    :style="{ width: '850px', height: '69px' }"
                    @click="selectedMember = idx"
                >
                    <div class="grid grid-cols-12 items-center h-full px-8">
                        <!-- Nombre -->
                        <div class="col-span-7 text-center font-semibold" :style="{ color: '#000000' }">
                            {{ m.name }}
                        </div>

                        <!-- Porcentaje -->
                        <div class="col-span-2 text-center font-semibold" :style="{ color: '#000000' }">
                            {{ m.percent }}
                        </div>

                        <!-- Estrella -->
                        <div class="col-span-3 flex justify-end">
                            <svg viewBox="0 0 24 24" class="w-10 h-10" :style="{ fill: '#D9D9D9' }">
                                <path
                                    d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2
                  9.19 8.63 2 9.24l5.46 4.73L5.82 21z"
                                />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </GruposLayout>
</template>

<style scoped>
/* Cards de miembros: mismas reglas que retos */
.memberCard{
    background: #FFFFFF;
    border: 3px solid #BBC2CF;   /* normal */
    border-radius: 12px;
    cursor: pointer;
    user-select: none;
    transition: border-color 120ms ease;
    box-sizing: border-box;
}

/* Hover: verde */
.memberCard:hover{
    border-color: #3FD99E;
}

/* Activo: verde */
.memberCard--active{
    border-color: #3FD99E;
}
</style>
