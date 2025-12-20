<script setup>
import SidebarOnlyLayout from "@/Layouts/SidebarOnlyLayout.vue";
import GruposRightLayout from "@/Layouts/GruposRightLayout.vue";

const challenges = [
    { title: "Reto de ejemplo", progress: "05/07/35", points: "120 pts", status: "70%" },
    { title: "Reto de ejemplo", progress: "05/07/35", points: "100 pts", status: "Pendiente" },
];
</script>

<template>
    <SidebarOnlyLayout title="Retos">
        <GruposRightLayout
            activeTab="retos"
            groupCode="18473938"
            groupName="NOMBRE DEL GRUPO"
            groupDate="12/11/2025"
        >
            <section class="mt-4 rounded-xl" :style="{ width: '850px', background: '#E5EDF9' }">
                <div class="py-6 space-y-5">
                    <div
                        v-for="(c, idx) in challenges"
                        :key="idx"
                        class="challengeCard"
                        :style="{ width: '850px', height: '69px' }"
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
    border-color:#3FD99E;
}
</style>
