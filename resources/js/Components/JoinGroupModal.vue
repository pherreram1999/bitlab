<script setup>
import { useForm } from "@inertiajs/vue3";
import { watch } from "vue";

const props = defineProps({
    show: Boolean,
});

const emit = defineEmits(["close"]);

const form = useForm({
    codigo: "",
});

watch(() => props.show, (newVal) => {
    if (newVal) {
        form.clearErrors();
        form.codigo = "";
    }
});

function submitJoin() {
    form.post("/alumnos/grupos/unirse", {
        preserveScroll: true,
        onSuccess: () => {
            emit("close");
        },
    });
}

function close() {
    emit("close");
}
</script>

<template>
    <div v-if="show" class="modalOverlay" @click.self="close">
        <div class="modalCard">
            <div class="modalHeader">
                <h2 class="modalTitle">Unirme a un grupo</h2>
                <button class="modalClose" type="button" aria-label="Cerrar" @click="close">×</button>
            </div>

            <p class="modalText">
                Ingresa el código del grupo que tu profesor te compartió.
            </p>

            <div class="mt-5">
                <label class="block font-semibold mb-2" :style="{ color: '#000000' }">
                    Código del grupo
                </label>

                <input
                    v-model="form.codigo"
                    type="text"
                    class="modalInput"
                    placeholder="Ej: 18473938"
                    @keydown.enter.prevent="submitJoin"
                />

                <div v-if="form.errors.codigo" class="modalError">
                    {{ form.errors.codigo }}
                </div>
            </div>

            <div class="modalActions">
                <button class="btnSecondary" type="button" @click="close">
                    Cancelar
                </button>

                <button class="btnPrimary" type="button" :disabled="form.processing" @click="submitJoin">
                    Unirme
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Modal */
.modalOverlay{
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.25);
    display:flex;
    align-items:center;
    justify-content:center;
    padding: 16px;
    z-index: 100;
}

.modalCard{
    width: 100%;
    max-width: 520px;
    background:#FFFFFF;
    border-radius: 16px;
    border: 1px solid #BBC2CF;
    padding: 18px 18px 16px 18px;
}

.modalHeader{
    display:flex;
    align-items:center;
    justify-content:space-between;
}

.modalTitle{
    font-size: 22px;
    font-weight: 800;
    color:#2B2E36;
}

.modalClose{
    width: 40px;
    height: 40px;
    border-radius: 10px;
    border: 1px solid #BBC2CF;
    background: #E5EDF9;
    cursor: pointer;
    font-size: 22px;
    line-height: 0;
}

.modalText{
    margin-top: 10px;
    color:#4B556B;
}

.modalInput{
    width: 100%;
    border-radius: 12px;
    padding: 12px 14px;
    border: 1px solid #BBC2CF;
    background: #E5EDF9;
    color: #000000;
    outline: none;
}

.modalError{
    margin-top: 8px;
    color: #E17101;
    font-size: 13px;
    font-weight: 700;
}

.modalActions{
    margin-top: 16px;
    display:flex;
    justify-content:flex-end;
    gap: 10px;
}

.btnSecondary{
    padding: 10px 14px;
    border-radius: 12px;
    border: 1px solid #3B3F48;
    background: #FFFFFF;
    font-weight: 800;
    cursor: pointer;
}

.btnPrimary{
    padding: 10px 16px;
    border-radius: 12px;
    border: none;
    background: #E17101;
    color: #FFFFFF;
    font-weight: 900;
    cursor: pointer;
}
.btnPrimary:disabled{
    opacity: 0.7;
    cursor: not-allowed;
}
</style>
