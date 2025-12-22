<script setup lang="ts">
import SidebarOnlyLayout from "@/Layouts/SidebarOnlyLayout.vue";
import {Grupo} from "@/interfaces.ts";

interface Props {
    grupo: Grupo
}

const props = defineProps()
</script>

<template>
    <sidebar-only-layout>

    </sidebar-only-layout>
</template>

<style scoped>

</style>
