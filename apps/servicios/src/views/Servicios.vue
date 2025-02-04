<script setup>
    import { useAuthStore } from '@/stores/auth'
    import { computed, onMounted } from 'vue'
    import { useServiciosStore } from '@/stores/servicios'

    const auth = useAuthStore()
    const store = useServiciosStore()

    const searchables = ['id','cui','nombre','fecha_nacimiento','edad','estado_civil','sexo','etnia','direccion','zona','celular']

    const participantes = computed(() => {
        
        return store.servicio.participantes.filter((item) => {
            return searchables.some((column) => {
                const value = getObjectValue(item, column)
                return String(value).toLowerCase().includes(store.search.toLowerCase())
            })
        })
    } , { cache: true } )

    const getObjectValue  = (object, key) => {
        const keys = key.split('.')
        return keys.reduce((value, currentKey) => {
            return value && value[currentKey]
        }, object)
    }

    onMounted(() => store.fetch())
</script>

<template>
    <Card class="bg-white pt-8 pb-2 px-2 lg:px-8">
        <h1 class="text-2xl text-gray-500 font-semibold">Servicios</h1>
        <div class="flex justify-center">
            <Tool-Tip message="Crear nuevo programa" class="-mt-8 text-color-4">
                <Button @click="store.modal.new = true" icon="fas fa-plus" class="btn-primary" />
            </Tool-Tip>
        </div>
        <Data-Table :headers="store.headers" :data="store.servicios" :loading="store.loading.fetch" :export="true">
            <template #estatus="{item}">
                <Icon :icon="item.estatus === 'I' ? 'fas fa-xmark' : 'fas fa-check'" :class="item.estatus === 'I' ? 'text-red-500' : 'text-green-500'"/>
            </template>
            <template #actions="{item}">
                <div class="relative">
                    <Drop-Down-Button icon="fas fa-ellipsis-vertical">
                        <ul class="grid gap-3 text-violet-500">
                            <li @click="store.edit(item)" class="hover:font-medium cursor-pointer">
                                Editar
                            </li>
                            <li @click="store.assign(item)" class="hover:font-medium cursor-pointer text-nowrap">
                                Asignar participantes
                            </li>
                            <hr>
                            <li v-if="item.estatus==='A'" @click="store.deleteItem(item)" class="hover:font-medium cursor-pointer">
                                Eliminar
                            </li>
                        </ul>
                    </Drop-Down-Button>
                </div>
            </template>
        </Data-Table>
    </Card>
    <!-- MODALES -->

    <Modal :open="store.modal.assign" icon="fas fa-person-circle-plus" title="Asignar participantes" class="w-1/2">
        <template #close>
            <Icon @click="store.resetData" icon="fas fa-xmark" class="text-violet-200 text-xl hover:scale-125 cursor-pointer" />
        </template>
        <div class="grid grid-cols-2">
            <span class="flex gap-2">
                <span class="font-medium">Id: </span>
                <span>{{ store.servicio.id }}</span>
            </span>
            <span class="flex gap-2">
                <span class="font-medium">Responsable: </span>
                <span>{{ store.servicio.responsable }}</span>
            </span>
            <span class="flex gap-2">
                <span class="font-medium">Servicio: </span>
                <span>{{ store.servicio.servicio }}</span>
            </span>
            <span class="flex gap-2">
                <span class="font-medium">Fecha: </span>
                <span>{{ store.servicio.fecha }}</span>
            </span>
            <span class="flex gap-2">
                <span class="font-medium">Lugar: </span>
                <span>{{ store.servicio.lugar }}</span>
            </span>
            <span class="flex gap-2">
                <span class="font-medium">Zona: </span>
                <span>{{ store.servicio.zona }}</span>
            </span>
            <span class="flex gap-2">
                <span class="font-medium">Programa: </span>
                <span>{{ store.servicio.programa }}</span>
            </span>
            
        </div>
        <hr class="my-3">
        <div class="grid grid-cols-2 gap-3">
            <div class="space-y-2">
                <Input v-model="store.persona.cui" option="label" title="Cui" maxlength="13" />
                <Input v-model="store.persona.nombre" option="label" title="Nombre completo" />
                <Input v-model="store.persona.fecha_nacimiento" option="label" type="date" title="Fecha nacimiento" />
                <Input v-model="store.persona.edad" option="label" type="number" title="Edad" />
                <Input v-model="store.persona.estado_civil" option="select" title="Estado civil">
                    <option value=""> --seleccione -- </option>
                    <option value="Soltero">Soltero</option>
                    <option value="Casado">Casado</option>
                    <option value="Unido">Unido</option>
                    <option value="Viudo">Viudo</option>
                    <option value="Divorciado">Divorciado</option>
                </Input>
                <div class="flex items-center gap-3">
                    <Icon icon="fas fa-person-dress" class="text-fuchsia-500 text-5xl" />
                    <Switch class="w-14 h-7 bg-blue-500 has-[:checked]:bg-fuchsia-500" :values="['F','M']" v-model="store.persona.sexo" />
                    <Icon icon="fas fa-person" class="text-blue-500 text-5xl" />
                </div>
                <Input v-model="store.persona.etnia" option="select" title="Etnia">
                    <option value=""> --seleccione -- </option>
                    <option value="Ladino / Mestizo">Ladino / Mestizo</option>
                    <option value="Maya">Maya</option>
                    <option value="Xinca">Xinca</option>
                    <option value="Garifuna">Garifuna</option>
                </Input>
                <Input v-model="store.persona.direccion" option="label" title="Dirección" />
                <Input v-model="store.persona.zona" option="select" title="Zona">
                    <option value=""> --seleccione -- </option>
                    <option v-for="i in 25" :value="i">{{ 'Zona ' + i }}</option>
                </Input>
                <Input v-model="store.persona.celular" option="label" type="number" title="Celular" />
                <div class="flex justify-center">
                    <Tool-Tip message="Agregar persona" class="-mt-6">
                        <Button @click="store.addPerson()" icon="fas fa-user-plus" class="btn-primary" />
                    </Tool-Tip>
                    <Tool-Tip message="Actualizar datos persona" class="-mt-6">
                        <Button @click="store.updatePerson()" icon="fas fa-arrows-rotate" class="btn-secondary" />
                    </Tool-Tip>
                </div>
            </div>
            <div class="flex flex-col space-y-2 overflow-auto h-[40rem]">
                <Input v-model="store.search" icon="search" type="search" placeholder="Buscar persona" />
                <div v-for="(participante, index) in participantes">
                    <div class="flex items-center gap-1">
                        <div class="text-xs grid grid-cols-2 bg-gray-200 p-3 rounded-lg w-full">
                            <span class="flex gap-2 col-span-2">
                                <span class="font-medium">Nombre: </span>
                                <span>{{ participante.nombre }}</span>
                            </span>
                            <span class="flex gap-2">
                                <span class="font-medium">Cui: </span>
                                <span>{{ participante.cui }}</span>
                            </span>
                            <span class="flex gap-2">
                                <span class="font-medium">Fecha nacimiento: </span>
                                <span>{{ participante.fecha_nacimiento }}</span>
                            </span>
                            <span class="flex gap-2">
                                <span class="font-medium">Edad: </span>
                                <span>{{ participante.edad }}</span>
                            </span>
                            <span class="flex gap-2">
                                <span class="font-medium">Estado civil: </span>
                                <span>{{ participante.estado_civil }}</span>
                            </span>
                            <span class="flex gap-2">
                                <span class="font-medium">Sexo: </span>
                                <span>{{ participante.sexo }}</span>
                            </span>
                            <span class="flex gap-2">
                                <span class="font-medium">Etnia: </span>
                                <span>{{ participante.etnia }}</span>
                            </span>
                            <span class="flex gap-2">
                                <span class="font-medium">Celular: </span>
                                <span>{{ participante.celular }}</span>
                            </span>
                            <span class="flex gap-2 col-span-2">
                                <span class="font-medium">Dirección: </span>
                                <span>{{ participante.direccion }}</span>
                            </span>
                            <span class="flex gap-2">
                                <span class="font-medium">Zona: </span>
                                <span>{{ participante.zona }}</span>
                            </span>
                        </div>
                        <div class="">
                            <Icon @click="store.editPerson(participante)" icon="fas fa-pencil" class="icon-button bg-blue-400" />
                            <Icon @click="store.removePerson(index)" icon="fas fa-xmark" class="icon-button bg-red-400" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr class="my-3">
        <span class="flex gap-2 justify-center">
            <span>Cantidad de personas registradas : </span>
            <span class="font-bold">
                {{ store.servicio.participantes.length }}
            </span>
        </span>

        <Validate-Errors :errors="store.errors" v-if="store.errors != 0" />

        <template #footer>
            <Button @click="store.resetData" text="Cancelar" icon="fas fa-xmark" class="btn-secondary" />
            <Button @click="store.store" text="Guardar" icon="fas fa-save" class="btn-primary" :loading="store.loading.store" />
        </template>
    </Modal>

</template>

<style scoped>
    span {
        @apply select-none ;
    }
</style>