<script setup>
import { computed, ref } from "vue";
import { Link } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";

/* PROPS */
const props = defineProps({
    title: { type: String, default: "Dashboard" },

    activeTab: { type: String, required: true }, // 'retos' | 'miembros'

    studentName: { type: String, default: "Nombre del alumno" },
    totalPoints: { type: [Number, String], default: 220 },
    avgPercent: { type: [Number, String], default: 65 },

    groupName: { type: String, default: "NOMBRE DEL GRUPO" },
    groupDate: { type: String, default: "12/11/2025" },
});

/* Hover temporal de tabs */
const hoverTab = ref(null);

/* Estados visuales de tabs */
const isRetosActive = computed(() => {
    if (hoverTab.value === "miembros") return false;
    if (hoverTab.value === "retos") return true;
    return props.activeTab === "retos";
});

const isMiembrosActive = computed(() => {
    if (hoverTab.value === "retos") return false;
    if (hoverTab.value === "miembros") return true;
    return props.activeTab === "miembros";
});
</script>

<template>
    <app-layout :title="props.title">
        <div class="min-h-screen flex" :style="{ background: '#E5EDF9' }">

            <!-- ================= SIDEBAR ================= -->
            <aside
                class="border-r shrink-0"
                :style="{ width: '351px', background: '#E5EDF9', borderColor: '#BBC2CF' }"
            >
                <nav class="pt-6 flex flex-col items-center" :style="{ gap: '17px' }">

                    <!-- HOME -->
                    <div class="sidebarBtn sidebarBtn--dark" :style="{ width: '280px', height: '61px' }">
                        <span class="homeSquare"></span>
                        <span class="sidebarText">Home</span>
                    </div>

                    <!-- Grupo seleccionado -->
                    <div class="sidebarBtn sidebarBtn--selected" :style="{ width: '280px', height: '61px' }">
                        <span class="sidebarText">Grupo ejemplo 1</span>
                    </div>

                    <!-- Otros grupos -->
                    <div
                        v-for="i in 3"
                        :key="i"
                        class="sidebarBtn"
                        :style="{ width: '280px', height: '61px' }"
                    >
                        <span class="sidebarText">Grupo ejemplo 1</span>
                    </div>

                </nav>
            </aside>

            <!-- ================= MAIN ================= -->
            <main class="flex-1 p-8">
                <div class="mx-auto" :style="{ width: '850px' }">

                    <!-- ===== Header Alumno (850x210) ===== -->
                    <section
                        class="rounded-xl overflow-hidden"
                        :style="{ background: '#2B2E36', width: '850px', height: '210px' }"
                    >
                        <div class="grid grid-cols-12 h-full">
                            <div class="col-span-9 px-8 flex flex-col justify-center">
                                <h1 class="text-5xl font-bold leading-none" :style="{ color: '#E17101' }">
                                    {{ props.studentName }}
                                </h1>

                                <div class="mt-8 space-y-3 text-xl" :style="{ color: '#FFFFFF' }">
                                    <div class="flex items-center gap-4">
                                        <span>Puntaje total:</span>
                                        <span class="font-semibold">{{ props.totalPoints }} pts</span>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <span>Porcentaje promedio:</span>
                                        <span class="font-semibold">{{ props.avgPercent }}%</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-span-3 flex items-center justify-center">
                                <svg viewBox="0 0 24 24" class="w-20 h-20" :style="{ fill: '#DADDE0' }">
                                    <path
                                        d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2
                    9.19 8.63 2 9.24l5.46 4.73L5.82 21z"
                                    />
                                </svg>
                            </div>
                        </div>
                    </section>

                    <!-- ===== Tabs Retos / Miembros ===== -->
                    <section
                        class="mt-4 rounded-xl"
                        :style="{ width: '850px', background: '#FFFFFF', border: '1px solid #BBC2CF' }"
                    >
                        <div class="px-8 py-4 flex items-center justify-between">
                            <div class="flex items-end gap-10">
                                <Link
                                    href="/alumnos/retos"
                                    class="tabBase"
                                    :class="{ tabActive: isRetosActive }"
                                    @mouseenter="hoverTab = 'retos'"
                                    @mouseleave="hoverTab = null"
                                >
                                    Retos
                                </Link>

                                <Link
                                    href="/alumnos/miembros"
                                    class="tabBase"
                                    :class="{ tabActive: isMiembrosActive }"
                                    @mouseenter="hoverTab = 'miembros'"
                                    @mouseleave="hoverTab = null"
                                >
                                    Miembros
                                </Link>
                            </div>
                        </div>
                    </section>

                    <!-- ===== Header Grupo (850x110) ===== -->
                    <section class="mt-4" :style="{ width: '850px' }">
                        <div
                            class="rounded-xl overflow-hidden flex items-center"
                            :style="{ background: '#2B2E36', width: '850px', height: '110px' }"
                        >
                            <div class="px-8">
                                <div class="text-4xl font-bold tracking-wide" :style="{ color: '#FFFFFF' }">
                                    {{ props.groupName }}
                                </div>
                                <div class="mt-2 text-lg" :style="{ color: '#FFFFFF' }">
                                    {{ props.groupDate }}
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- ===== CONTENIDO VARIABLE ===== -->
                    <slot />

                </div>
            </main>
        </div>
    </app-layout>
</template>

<style scoped>
/* ========== SIDEBAR ========== */
.sidebarBtn{
    display:flex;
    align-items:center;
    justify-content:flex-start;
    gap:12px;
    padding-left:18px;

    border-radius:12px;
    cursor:pointer;
    user-select:none;
    font-weight:600;

    color:#000000;
    background:#E5EDF9;
    border:1px solid #BBC2CF;
}

.sidebarBtn--dark{
    background:#2B2E36;
    color:#FFFFFF;
    border:none;
}

.sidebarBtn--selected{
    background:#D8934E;
    color:#000000;
    border:none;
}

.homeSquare{
    width:23px;
    height:23px;
    border-radius:2px;
    background:#E17101;
    flex-shrink:0;
}

.sidebarText{
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

aside nav > div:hover{
    background:#D9D9D9 !important;
    color:#000000 !important;
}

/* ========== TABS ========== */
.tabBase{
    position:relative;
    display:inline-block;
    font-weight:600;
    color:#000000;
    padding-bottom:10px;
    cursor:pointer;
    text-decoration:none;
}

.tabBase::after{
    content:"";
    position:absolute;
    left:0;
    bottom:0;
    width:100%;
    height:6px;
    background:#E17101;
    border-radius:4px;
    transform:scaleX(0);
    transform-origin:left;
    transition:transform 120ms ease;
}

.tabActive{
    color:#E17101;
}

.tabActive::after{
    transform:scaleX(1);
}
</style>
