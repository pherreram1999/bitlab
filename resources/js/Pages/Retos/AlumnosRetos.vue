<script setup>
import SidebarOnlyLayout from "@/Layouts/SidebarOnlyLayout.vue";
import GruposRightLayout from "@/Layouts/GruposRightLayout.vue";

const props = defineProps({
    groups: { type: Array, default: () => [] },
    activeGroupId: { type: [Number, String], default: null },

    group: { type: Object, default: () => ({}) }, // { id, nombre, clave, created_at ... }
    retos: { type: Array, default: () => [] },    // lista ya formateada del backend
});

// helpers
function fmtDate(str) {
    if (!str) return "";
    // si ya te llega como "12/11/2025" desde backend, no lo toques
    return str;
}
</script>

<template>
    <SidebarOnlyLayout
        title="Retos"
        :groups="props.groups"
        :activeGroupId="props.activeGroupId"
        activeTab="retos"
        baseGroupHref="/alumnos/grupos"
        hrefHome="/dashboard"
    >
        <GruposRightLayout
            activeTab="retos"
            :hrefRetos="`/alumnos/grupos/${props.group.id}/retos`"
            :hrefMiembros="`/alumnos/grupos/${props.group.id}/miembros`"
            :groupCode="props.group.clave"
            :groupName="props.group.nombre"
            :groupDate="fmtDate(props.group.fecha ?? props.group.created_at)"
        >
            <!-- Lista retos -->
            <section class="mt-4 rounded-xl" :style="{ width: '850px', background: '#E5EDF9' }">
                <div class="py-6 space-y-5">
                    <div
                        v-for="r in props.retos"
                        :key="r.id"
                        class="challengeCard"
                        :style="{ width: '850px', height: '69px' }"
                    >
                        <div class="grid grid-cols-12 items-center h-full px-8">
                            <div class="col-span-4 font-semibold" :style="{ color: '#000000' }">{{ r.titulo }}</div>
                            <div class="col-span-3 text-center" :style="{ color: '#000000' }">{{ r.progreso }}</div>
                            <div class="col-span-2 text-center font-semibold" :style="{ color: '#000000' }">{{ r.puntos }}</div>
                            <div class="col-span-2 text-center" :style="{ color: '#000000' }">{{ r.estado }}</div>
                        </div>
                    </div>
                </div>
            </section>
        </GruposRightLayout>
    </SidebarOnlyLayout>
</template>

<style scoped>
.challengeCard{
    background:#FFFFFF;
    border:3px solid #BBC2CF;
    border-radius:12px;
    cursor:pointer;
    user-select:none;
    transition:border-color 120ms ease;
    box-sizing:border-box;
}
.challengeCard:hover{
    border-color:#3FD99E; /* SOLO hover */
}
</style>
