<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({
    title: { type: String, default: "Dashboard" },
});
</script>

<template>
    <app-layout :title="props.title">
        <div class="min-h-screen flex" :style="{ background: '#E5EDF9' }">
            <!-- SIDEBAR -->
            <aside
                class="border-r shrink-0"
                :style="{ width: '351px', background: '#E5EDF9', borderColor: '#BBC2CF' }"
            >
                <nav class="pt-6 flex flex-col items-center" :style="{ gap: '17px' }">

                    <!-- HOME -->
                    <div class="sidebarBtn sidebarBtn--dark" :style="{ width: '280px', height: '61px' }">
                        <svg
                            class="homeIcon"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                        >
                            <path
                                d="M3 10.5 12 3l9 7.5V21a1 1 0 0 1-1 1h-5v-6h-6v6H4a1 1 0 0 1-1-1z"
                            />
                        </svg>
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

            <!-- CONTENIDO A LA DERECHA -->
            <main class="flex-1 p-8">
                <slot />
            </main>
        </div>
    </app-layout>
</template>

<style scoped>
/* =========================
   Sidebar base
   ========================= */
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
    transition: background 120ms ease, color 120ms ease;
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

/* =========================
   Ícono Home
   ========================= */
.homeIcon{
    width:35px;
    height:35px;
    fill:#E17101;
    flex-shrink:0;
}

/* =========================
   Texto
   ========================= */
.sidebarText{
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

/* =========================
   Hover
   ========================= */
aside nav > div:hover{
    background:#D9D9D9 !important;
    color:#000000 !important;
}
</style>
