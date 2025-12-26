<script setup lang="ts">
import SidebarOnlyLayout from "@/Layouts/SidebarOnlyLayout.vue";
import {Grupo} from "@/interfaces.ts";
import {Link} from "@inertiajs/vue3";
import {onBeforeMount, ref, shallowRef} from "vue";
import {useAxios} from "@/composable/useAxios";
import {useUser} from "@/composable/useUser";
import {router} from "@inertiajs/vue3";

const { axios: client } = useAxios()

const user = useUser()

interface Props {
    grupo: Grupo
}

const props = defineProps<Props>()

enum Tabs {
    Miembros,
    Retos
}

const miembros = shallowRef([])
const retos = shallowRef([])

const tab = ref<Tabs>(Tabs.Retos)

const getMembers = async () => {
    const {data} = await client.post(`/grupo/${props.grupo.id}/miembros`)
    miembros.value = data
}

const getRetos = async () => {
    const {data} = await client.post(`/grupo/${props.grupo.id}/retos`)
    retos.value = data
}


const select = async (t: Tabs) => {
    switch (t) {
        case Tabs.Miembros:
            await getMembers()
            tab.value = Tabs.Miembros
            break
        case Tabs.Retos:
            await getRetos()
            tab.value = Tabs.Retos
            break
    }
}

// por defecto debemos cargar los retos

onBeforeMount(() => {
    getRetos()
})

const abrirReto = (r: any) => {
    // depediento el ROL
    if (user.rol.clave === 'PROFESOR') // es una vista pendiente
        router.visit('/construction')
    // retornamos al vista para resolver el reto
    router.visit(`/reto/${r.id}`)
}

</script>

<template>
    <SidebarOnlyLayout>
        <section class="mt-4">
            <div class="rounded-xl overflow-hidden flex items-center" :style="{ background: '#2B2E36', height: '110px' }">
                <div class="px-8">
                    <div class="text-4xl font-bold tracking-wide" :style="{ color: '#FFFFFF' }">
                        {{ grupo.nombre }}
                    </div>
                    <div class="mt-2 text-lg" :style="{ color: '#FFFFFF' }">
                        {{ grupo.created_at }}
                    </div>
                </div>
            </div>
        </section>
        <section class="rounded bg-white mt-4">
            <div class="flex items-center justify-between">
                <div class="flex items-end">
                    <button
                        @click.prevent="select(Tabs.Retos)"
                        :class="[tab === Tabs.Retos ? 'border-orange-500' : 'border-gray-200']"
                        class="tabBase px-4 py-2 border-b-4">
                        Retos
                    </button>

                    <button
                        @click.prevent="select(Tabs.Miembros)"
                        :class="[tab === Tabs.Miembros ? 'border-orange-500' : 'border-gray-200']"
                        class="tabBase px-4 py-2 border-b-4">
                        Miembros
                    </button>
                </div>

                <div class="codePill px-4 py-2">
                    Codigo del grupo: <span class="font-extrabold bg-stone-200 px-4 py-1 rounded-lg select-all">{{ grupo.clave }}</span>
                </div>
            </div>
        </section>
        <Transition>
            <section v-if="tab === Tabs.Miembros" class="mt-4">
                <div class="px-4 py-2 bg-white rounded-lg" v-for="m of miembros">
                    {{ m.nombre }}
                </div>
            </section>
            <section class="mt-4" v-else>
                <div @click.prevent="abrirReto(r)"
                    class="px-4 py-2  bg-white my-2 rounded-lg flex justify-between
                    hover:shadow-lg cursor-pointer transition-all transform hover:-translate-y-0.5"
                     v-for="r of retos">
                    <h2 class="font-semibold px-4 py-2">{{ r.titulo }}</h2>
                    <time>{{ r.fecha_limite }}</time>
                    <div>

                    </div>
                </div>
                <!-- Enlace para crear un reto !-->
                <Link class="fabBtn" v-if="user.rol.clave === 'PROFESOR'"
                      :href="`/retos/${grupo.id}/crear`"
                      aria-label="Unirse a un grupo">
                    <span class="plusV"></span>
                    <span class="plusH"></span>
                </Link>
            </section>
        </Transition>



    </SidebarOnlyLayout>
</template>

<style scoped>
.tabBase{
    position:relative;
    display:inline-block;
    font-weight:600;
    color:#000000;
    padding-bottom:10px;
    cursor:pointer;
    text-decoration:none;
}
.tabBase::after{
    content:"";
    position:absolute;
    left:0;
    bottom:0;
    width:100%;
    height:6px;
    background:#E17101;
    border-radius:4px;
    transform:scaleX(0);
    transform-origin:left;
    transition:transform 120ms ease;
}
</style>
