<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    title: { type: String, default: "Dashboard" },

    // groups: [{ id, nombre }]
    groups: { type: Array, default: () => [] },

    // el grupo seleccionado
    activeGroupId: { type: [Number, String], default: null },

    // 'retos' | 'miembros' (para armar href de cada grupo en el sidebar)
    activeTab: { type: String, default: "retos" },

    // base para construir rutas: `${baseGroupHref}/${id}/${activeTab}`
    // ejemplo alumno: "/alumnos/grupos"
    // ejemplo profesor: "/profesor/grupos"
    baseGroupsIndexHref: { type: String, default: "/alumnos/grupos" },

    // a dónde manda Home
    hrefHome: { type: String, default: "/dashboard" },
});

function groupHref(g) {
    if (!g?.id) return "#";

    // Si estamos en la vista de grupos (no hay activeTab con contexto), vamos al index
    if (!props.activeTab || props.activeGroupId === null) {
        return props.baseGroupsIndexHref;
    }

    return `${props.baseGroupHref}/${g.id}/${props.activeTab}`;
}
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
                    <Link
                        :href="props.hrefHome"
                        class="sidebarBtn sidebarBtn--dark"
                        :style="{ width: '280px', height: '61px' }"
                    >
                        <svg class="homeIcon" viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M3 10.5 12 3l9 7.5V21a1 1 0 0 1-1 1h-5v-6h-6v6H4a1 1 0 0 1-1-1z"
                            />
                        </svg>
                        <span class="sidebarText">Home</span>
                    </Link>

                    <!-- GRUPOS -->
                    <Link
                        v-for="g in props.groups"
                        :key="g.id"
                        :href="groupHref(g)"
                        class="sidebarBtn"
                        :class="{ 'sidebarBtn--selected': String(props.activeGroupId) === String(g.id) }"
                        :style="{ width: '280px', height: '61px' }"
                    >
                        <span class="sidebarText">{{ g.nombre ?? "Grupo" }}</span>
                    </Link>

                    <!-- fallback si no hay grupos -->
                    <div
                        v-if="!props.groups?.length"
                        class="sidebarBtn"
                        :style="{ width: '280px', height: '61px', opacity: 0.75 }"
                    >
                        <span class="sidebarText">Sin grupos</span>
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
    text-decoration:none;

    transition: background 120ms ease, color 120ms ease, transform 120ms ease;
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

.homeIcon{
    width:35px;
    height:35px;
    fill:#E17101;
    flex-shrink:0;
}

.sidebarText{
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

/* Hover paleta sidebar */
aside nav > a.sidebarBtn:hover,
aside nav > div.sidebarBtn:hover{
    background:#D9D9D9 !important;
    color:#000000 !important;
}
</style>
