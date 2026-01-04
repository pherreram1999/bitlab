<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    feedbacks: Object,
});

const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
    return new Date(dateString).toLocaleDateString('es-ES', options);
};
</script>

<template>
    <AppLayout title="Feedback del Sistema">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Feedback del Sistema
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <div v-if="feedbacks.data.length === 0" class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 text-center text-gray-500">
                    No hay feedback registrado aún.
                </div>

                <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="feedback in feedbacks.data" :key="feedback.id" class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 flex flex-col h-full border border-gray-100 transition hover:shadow-2xl">
                        
                        <!-- Header: Usuario y Fecha -->
                        <div class="flex items-center mb-4 pb-4 border-b border-gray-100">
                            <div class="shrink-0 mr-3">
                                <img class="h-10 w-10 rounded-full object-cover" 
                                     :src="feedback.user?.profile_photo_url || 'https://ui-avatars.com/api/?name=X&color=7F9CF5&background=EBF4FF'" 
                                     :alt="feedback.user?.nombre || 'Usuario Desconocido'" />
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800">{{ feedback.user?.nombre || 'Usuario Eliminado' }}</h3>
                                <p class="text-xs text-gray-500">{{ feedback.user?.email || 'No disponible' }}</p>
                            </div>
                        </div>

                        <!-- Contenido: Comentario -->
                        <div class="flex-grow text-gray-600 mb-4 italic relative pl-4 border-l-4 border-indigo-200">
                            "{{ feedback.comentarios }}"
                        </div>

                        <!-- Footer: Fecha y Detalles Sistema (Simulado/Si existe) -->
                        <div class="mt-auto pt-2 flex justify-between items-center text-xs text-gray-400">
                            <span>📅 {{ formatDate(feedback.created_at) }}</span>
                            <span class="bg-gray-100 px-2 py-1 rounded text-gray-500">ID: {{ feedback.id }}</span>
                        </div>
                    </div>
                </div>

                <!-- Paginación -->
                <div v-if="feedbacks.links.length > 3" class="mt-6 flex justify-center">
                    <div class="flex gap-1">
                        <template v-for="(link, key) in feedbacks.links" :key="key">
                            <div v-if="link.url === null" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded" v-html="link.label" />
                            <Link v-else class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500" :class="{ 'bg-blue-700 text-white': link.active }" :href="link.url" v-html="link.label" />
                        </template>
                    </div>
                </div>

            </div>
        </div>
    </AppLayout>
</template>
