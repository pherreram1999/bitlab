<script setup>
import { ref, provide } from 'vue';
import axios from 'axios';
import DialogModal from '@/Components/DialogModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

// Create a dedicated axios instance
const axiosInstance = axios.create();

// Reactive state for the transaction lifecycle
const modalState = ref({
    show: false,
    status: 'idle', // 'idle' | 'loading' | 'success' | 'error'
    message: '',
});

// Request Interceptor
axiosInstance.interceptors.request.use((config) => {
    modalState.value = {
        show: true,
        status: 'loading',
        message: 'Procesando...',
    };
    return config;
}, (error) => {
    modalState.value = {
        show: true,
        status: 'error',
        message: 'Algo ha fallado al iniciar la petición.',
    };
    return Promise.reject(error);
});

// Response Interceptor
axiosInstance.interceptors.response.use((response) => {
    modalState.value = {
        show: true,
        status: 'success',
        message: 'Transacción correcta',
    };
    return response;
}, (error) => {
    const errorMessage = error.response?.data?.message || error.message || 'Algo ha fallado';
    modalState.value = {
        show: true,
        status: 'error',
        message: errorMessage,
    };
    return Promise.reject(error);
});

// Provide the axios instance to children
provide('axios', axiosInstance);

// Close modal handler
const closeModal = () => {
    // Prevent closing while loading
    if (modalState.value.status === 'loading') return;
    
    modalState.value.show = false;
    setTimeout(() => {
        modalState.value.status = 'idle';
        modalState.value.message = '';
    }, 200); // Wait for transition
};
</script>

<template>
    <div>
        <!-- Main Content Slot -->
        <slot />

        <!-- Feedback Modal -->
        <DialogModal 
            :show="modalState.show" 
            :closeable="modalState.status !== 'loading'"
            @close="closeModal"
            max-width="md"
        >
            <template #title>
                <span v-if="modalState.status === 'loading'">Cargando</span>
                <span v-else-if="modalState.status === 'success'">¡Éxito!</span>
                <span v-else-if="modalState.status === 'error'">Error</span>
            </template>

            <template #content>
                <div class="flex flex-col items-center justify-center p-4 space-y-4">
                    
                    <!-- Loading State -->
                    <div v-if="modalState.status === 'loading'" class="flex flex-col items-center">
                        <svg class="animate-spin h-10 w-10 text-slate-600 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-slate-600 font-medium">{{ modalState.message }}</p>
                    </div>

                    <!-- Success State -->
                    <div v-else-if="modalState.status === 'success'" class="flex flex-col items-center text-center">
                        <div class="text-5xl mb-2">🎉</div>
                        <p class="text-lg text-slate-800 font-semibold">{{ modalState.message }}</p>
                    </div>

                    <!-- Error State -->
                    <div v-else-if="modalState.status === 'error'" class="flex flex-col items-center text-center">
                        <div class="text-4xl mb-2 text-red-500">⚠️</div>
                        <p class="text-slate-700">{{ modalState.message }}</p>
                    </div>

                </div>
            </template>

            <template #footer>
                <SecondaryButton 
                    v-if="modalState.status !== 'loading'" 
                    @click="closeModal"
                >
                    Cerrar
                </SecondaryButton>
            </template>
        </DialogModal>
    </div>
</template>
