<script setup>
import { computed, ref } from "vue";
import { Link, useForm } from "@inertiajs/vue3";
import SidebarOnlyLayout from "@/Layouts/SidebarOnlyLayout.vue";

const props = defineProps({
    grupos: { type: Array, default: () => [] }, // [{id,nombre,fecha,...}]
});

/* Modal */
const showJoin = ref(false);

/* Form (Inertia) */
const form = useForm({
    codigo: "",
});

const hasGroups = computed(() => (props.grupos?.length ?? 0) > 0);

function openJoin() {
    showJoin.value = true;
    form.clearErrors();
    form.codigo = "";
}

function closeJoin() {
    showJoin.value = false;
}

function submitJoin() {
    form.post("/alumnos/grupos/unirse", {
        preserveScroll: true,
        onSuccess: () => {
            // si tu controller redirige a retos del grupo, el modal ya no importa
            showJoin.value = false;
        },
    });
}

function fmtDate(d) {
    if (!d) return "";
    return d;
}
</script>

<template>
    <SidebarOnlyLayout
        title="Grupos"
        :groups="props.grupos"
        :activeGroupId="null"
        hrefHome="/dashboard"
    >
        <div class="mx-auto" :style="{ width: '850px' }">
            <section :style="{ width: '850px' }">
                <div class="groupsGrid">
                    <Link
                        v-for="g in props.grupos"
                        :key="g.id"
                        class="groupCard"
                        :href="`/alumnos/grupos/${g.id}/retos`"
                    >
                        <div class="cardInner">
                            <div class="cardText">
                                <div class="cardTitle">{{ g.nombre ?? "Grupo" }}</div>
                                <div class="cardDate">{{ fmtDate(g.fecha ?? g.created_at) }}</div>
                            </div>

                            <button
                                type="button"
                                class="kebabBtn"
                                aria-label="Opciones del grupo"
                                @click.prevent
                            >
                                <span class="kebabDot"></span>
                                <span class="kebabDot"></span>
                                <span class="kebabDot"></span>
                            </button>
                        </div>
                    </Link>

                    <div v-if="!hasGroups" class="emptyBox">
                        <h2 class="emptyTitle">Aún no tienes grupos</h2>
                        <p class="emptyText">
                            Únete con el botón <span :style="{ color: '#E17101', fontWeight: 800 }">+</span>
                            usando el código que te compartió tu profesor.
                        </p>
                    </div>
                </div>
            </section>

            <!-- FAB "+" más pequeño -->
            <button class="fabBtn" type="button" aria-label="Unirse a un grupo" @click="openJoin">
                <span class="plusV"></span>
                <span class="plusH"></span>
            </button>

            <!-- MODAL -->
            <div v-if="showJoin" class="modalOverlay" @click.self="closeJoin">
                <div class="modalCard">
                    <div class="modalHeader">
                        <h2 class="modalTitle">Unirme a un grupo</h2>
                        <button class="modalClose" type="button" aria-label="Cerrar" @click="closeJoin">×</button>
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
                        <button class="btnSecondary" type="button" @click="closeJoin">
                            Cancelar
                        </button>

                        <button class="btnPrimary" type="button" :disabled="form.processing" @click="submitJoin">
                            Unirme
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </SidebarOnlyLayout>
</template>

<style scoped>
/* Grid */
.groupsGrid{
    display:grid;
    grid-template-columns: repeat(2, 1fr);
    gap:22px;
}

/* Card grupo */
.groupCard{
    height:130px;
    background:#2B2E36;
    border-radius:14px;
    text-decoration:none;
    display:block;
    overflow:hidden;
    transition: transform 120ms ease, box-shadow 120ms ease;
}
.groupCard:hover{
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.08);
}
.cardInner{
    height:100%;
    padding:18px;
    display:flex;
    align-items:center;
    justify-content:space-between;
}
.cardTitle{ font-weight:700; font-size:18px; color:#FFFFFF; }
.cardDate{ margin-top:6px; font-size:14px; color:#DADDE0; }

/* 3 puntos */
.kebabBtn{
    width:40px; height:60px; border:none; background:transparent;
    display:flex; flex-direction:column; justify-content:center; align-items:center;
    gap:6px; cursor:pointer;
}
.kebabDot{ width:5px; height:5px; border-radius:999px; background:#E17101; }

/* Empty */
.emptyBox{
    grid-column: 1 / -1;
    background:#FFFFFF;
    border:1px solid #BBC2CF;
    border-radius:14px;
    padding:24px;
}
.emptyTitle{ font-size:22px; font-weight:800; color:#2B2E36; }
.emptyText{ margin-top:8px; color:#4B556B; }

/* FAB más pequeño: 84x84 */
.fabBtn{
    position: fixed;
    right: 70px;
    bottom: 70px;
    width: 84px;
    height: 84px;
    border-radius: 999px;
    background: #3B3F48;
    border: none;
    cursor: pointer;
    display:flex;
    align-items:center;
    justify-content:center;
}

/* Cruz más pequeña */
.plusV{
    position:absolute;
    width: 8px;
    height: 44px;
    background:#E17101;
    border-radius: 10px;
}
.plusH{
    position:absolute;
    width: 44px;
    height: 8px;
    background:#E17101;
    border-radius: 10px;
}

/* Modal */
.modalOverlay{
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.25);
    display:flex;
    align-items:center;
    justify-content:center;
    padding: 24px;
}

.modalCard{
    width: 520px;
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

/* Responsive */
@media (max-width: 1100px){
    .groupsGrid{ grid-template-columns: 1fr; }
}
</style>
