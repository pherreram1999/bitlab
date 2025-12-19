<script setup>
import SidebarOnlyLayout from "@/Layouts/SidebarOnlyLayout.vue";
import GruposRightLayout from "@/Layouts/GruposRightLayout.vue";

// Dummy data (solo UI)
const members = [
    { name: "Miembro 2", percent: "70%" },
    { name: "Miembro 1", percent: "30%" },
];
</script>

<template>
    <SidebarOnlyLayout title="Miembros">
        <GruposRightLayout
            activeTab="miembros"
            groupCode="18473938"
            groupName="NOMBRE DEL GRUPO"
            groupDate="12/11/2025"
        >
            <!-- Bloque Miembros -->
            <section class="mt-4 rounded-xl" :style="{ width: '850px', background: '#E5EDF9' }">
                <div class="py-6 space-y-5">
                    <div
                        v-for="(m, idx) in members"
                        :key="idx"
                        class="memberCard"
                        :style="{ width: '850px', height: '69px' }"
                    >
                        <div class="grid grid-cols-12 items-center h-full px-8">
                            <div class="col-span-7 text-center font-semibold" :style="{ color: '#000000' }">
                                {{ m.name }}
                            </div>

                            <div class="col-span-2 text-center font-semibold" :style="{ color: '#000000' }">
                                {{ m.percent }}
                            </div>

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
        </GruposRightLayout>
    </SidebarOnlyLayout>
</template>

<style scoped>
.memberCard{
    background: #FFFFFF;
    border: 3px solid #BBC2CF;
    border-radius: 12px;
    cursor: pointer;
    user-select: none;
    transition: border-color 120ms ease;
    box-sizing: border-box;
}
.memberCard:hover{
    border-color: #3FD99E;
}
</style>
