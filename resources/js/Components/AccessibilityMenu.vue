<script setup>
import { ref, onMounted } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';

// Estados
const fontSizeLevel = ref(0); // 0: Normal, 1: Mediano, 2: Grande, 3: Extra
const highContrast = ref(false);

const fontSizes = [
    { label: 'Normal', class: '' },
    { label: 'Mediana', class: 'a11y-font-md' },
    { label: 'Grande', class: 'a11y-font-lg' },
    { label: 'Extra', class: 'a11y-font-xl' }
];

// Aplicar cambios al DOM
const applySettings = () => {
    const html = document.documentElement;

    // Limpiar clases anteriores
    fontSizes.forEach(s => {
        if (s.class) html.classList.remove(s.class);
    });
    html.classList.remove('a11y-high-contrast');

    // Aplicar nuevas
    const currentSize = fontSizes[fontSizeLevel.value];
    if (currentSize.class) html.classList.add(currentSize.class);

    if (highContrast.value) {
        html.classList.add('a11y-high-contrast');
    }

    // Guardar en LocalStorage
    localStorage.setItem('a11y-preferences', JSON.stringify({
        fontSizeLevel: fontSizeLevel.value,
        highContrast: highContrast.value
    }));
};

// Acciones
const toggleContrast = () => {
    highContrast.value = !highContrast.value;
    applySettings();
};

const changeFontSize = (delta) => {
    const newLevel = fontSizeLevel.value + delta;
    if (newLevel >= 0 && newLevel < fontSizes.length) {
        fontSizeLevel.value = newLevel;
        applySettings();
    }
};

const resetSettings = () => {
    fontSizeLevel.value = 0;
    highContrast.value = false;
    applySettings();
};

// Cargar preferencias al montar
onMounted(() => {
    const saved = localStorage.getItem('a11y-preferences');
    if (saved) {
        try {
            const parsed = JSON.parse(saved);
            fontSizeLevel.value = parsed.fontSizeLevel || 0;
            highContrast.value = parsed.highContrast || false;
            applySettings();
        } catch (e) {
            console.error('Error cargando preferencias de accesibilidad', e);
        }
    }
});
</script>

<template>
    <div class="relative ms-3">
        <Dropdown align="right" width="60">
            <template #trigger>
                <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150" title="Opciones de Accesibilidad">
                    <!-- Icono de Visibilidad (Ojo) - Más limpio y estético -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-universal-access-circle" viewBox="0 0 16 16">
                        <path d="M8 4.143A1.071 1.071 0 1 0 8 2a1.071 1.071 0 0 0 0 2.143m-4.668 1.47 3.24.316v2.5l-.323 4.585A.383.383 0 0 0 7 13.14l.826-4.017c.045-.18.301-.18.346 0L9 13.139a.383.383 0 0 0 .752-.125L9.43 8.43v-2.5l3.239-.316a.38.38 0 0 0-.047-.756H3.379a.38.38 0 0 0-.047.756Z"/>
                        <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0M1 8a7 7 0 1 1 14 0A7 7 0 0 1 1 8"/>
                    </svg>
                </button>
            </template>

            <template #content>
                <div class="p-4 w-64 bg-white">
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">
                        Accesibilidad
                    </div>

                    <!-- Control de Texto -->
                    <div class="mb-4">
                        <div class="text-sm text-gray-700 mb-2 font-bold flex justify-between">
                            <span>Tamaño de Texto</span>
                            <span class="text-primary font-normal">{{ fontSizes[fontSizeLevel].label }}</span>
                        </div>
                        <div class="flex items-center justify-between bg-gray-100 rounded-lg p-1">
                            <button @click="changeFontSize(-1)" :disabled="fontSizeLevel === 0"
                                class="w-10 h-10 flex items-center justify-center rounded-md hover:bg-white text-gray-600 disabled:opacity-30 transition font-bold text-sm">
                                A-
                            </button>
                            <span class="text-xs text-gray-400">|</span>
                            <button @click="changeFontSize(1)" :disabled="fontSizeLevel === fontSizes.length - 1"
                                class="w-10 h-10 flex items-center justify-center rounded-md hover:bg-white text-gray-800 disabled:opacity-30 transition font-bold text-lg">
                                A+
                            </button>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 my-3"></div>

                    <!-- Control de Contraste -->
                    <div class="mb-4">
                        <button @click="toggleContrast"
                            class="w-full flex items-center justify-between px-3 py-2 rounded-lg transition border"
                            :class="highContrast ? 'bg-black text-white border-black' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50'">
                            <span class="text-sm font-medium flex items-center gap-2">
                                <span>👁️</span>
                                Alto Contraste (B/N)
                            </span>
                            <div class="w-10 h-5 bg-gray-200 rounded-full relative ml-2 transition-colors" :class="{ 'bg-gray-600': highContrast }">
                                <div class="absolute top-1 left-1 w-3 h-3 bg-white rounded-full transition-transform"
                                    :class="{ 'translate-x-5': highContrast }"></div>
                            </div>
                        </button>
                    </div>

                    <div class="border-t border-gray-100 my-3"></div>

                    <button @click="resetSettings" class="w-full text-center text-xs text-red-500 hover:text-red-400 hover:underline">
                        Restablecer valores
                    </button>
                </div>
            </template>
        </Dropdown>
    </div>
</template>
