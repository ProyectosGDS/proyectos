<script setup>

    import { useAuthStore } from '@/stores/auth'
    import { onMounted} from 'vue'
    import { useHorariosStore } from '@/stores/horarios'

    const auth = useAuthStore()
    const store = useHorariosStore()

    onMounted(() => {
        store.fetch()
    })

</script>

<template>
    <Card class="bg-white pt-8 pb-2 px-2 lg:px-8">
        <h1 class="text-2xl text-gray-500 font-semibold">Horarios</h1>
        <div v-if="auth.checkPermission('crear horario')" class="flex justify-center">
            <Tool-Tip message="Crear nuevo horario" class="-mt-8 text-color-4">
                <Button @click="store.modal.new = true" icon="fas fa-plus" class="btn-primary" />
            </Tool-Tip>
        </div>
        <Data-Table v-if="auth.checkPermission('ver horarios')" :headers="store.headers" :data="store.horarios" :loading="store.loading.fetch" :export="auth.checkPermission('exportar horarios')">
            <template #estatus="{item}">
                <Icon :icon="item.estatus === 'I' ? 'fas fa-xmark' : 'fas fa-check'" :class="item.estatus === 'I' ? 'text-red-500' : 'text-green-500'"/>
            </template>
            <template #actions="{item}">
                <div class="relative">
                    <Drop-Down-Button icon="fas fa-ellipsis-vertical">
                        <ul class="grid gap-3 text-violet-500">
                            <li v-if="auth.checkPermission('editar horario')" @click="store.edit(item)" class="hover:font-medium cursor-pointer">
                                Editar
                            </li>
                            <hr>
                            <template v-if="auth.checkPermission('eliminar horario')">
                                <li v-if="item.estatus==='A'" @click="store.deleteItem(item)" class="hover:font-medium cursor-pointer">
                                    Eliminar
                                </li>
                            </template>
                        </ul>
                    </Drop-Down-Button>
                </div>
            </template>
        </Data-Table>
    </Card>
    <!-- AREA DE MODALES -->
    <Modal :open="store.modal.new" icon="fas fa-clock" title="Crear nuevo horario" class="w-1/3">
        <template #close>
            <Icon @click="store.resetData" icon="fas fa-xmark" class="text-violet-200 text-xl hover:scale-125 cursor-pointer" />
        </template>
        <div class="grid gap-3 grid-cols-2">
            <Input v-model="store.horario.hora_ini" option="label" type="time" title="Hora inicial" />
            <Input v-model="store.horario.hora_fin" option="label" type="time" title="Hora final" />
        </div>
        <hr class="my-4">
        <div class="flex gap-4">
            <label class="flex gap-1 cursor-pointer">
                <input v-model="store.dias" type="checkbox" value="LUN">
                <span>LUN</span>
            </label>
            <label class="flex gap-1 cursor-pointer">
                <input v-model="store.dias" type="checkbox" value="MAR">
                <span>MAR</span>
            </label>
            <label class="flex gap-1 cursor-pointer">
                <input v-model="store.dias" type="checkbox" value="MIE">
                <span>MIE</span>
            </label>
            <label class="flex gap-1 cursor-pointer">
                <input v-model="store.dias" type="checkbox" value="JUE">
                <span>JUE</span>
            </label>
            <label class="flex gap-1 cursor-pointer">
                <input v-model="store.dias" type="checkbox" value="VIE">
                <span>VIE</span>
            </label>
            <label class="flex gap-1 cursor-pointer">
                <input v-model="store.dias" type="checkbox" value="SAB">
                <span>SAB</span>
            </label>
            <label class="flex gap-1 cursor-pointer">
                <input v-model="store.dias" type="checkbox" value="DOM">
                <span>DOM</span>
            </label>
        </div>
        <Validate-Errors :errors="store.errors" v-if="store.errors != 0" />

        <template #footer>
            <Button @click="store.resetData" text="Cancelar" icon="fas fa-xmark" class="btn-secondary" />
            <Button @click="store.store" text="Crear" icon="fas fa-plus" class="btn-primary" :loading="store.loading.store" />
        </template>
    </Modal>

    <Modal :open="store.modal.edit" title="Editar horario" icon="fas fa-clock">
        <template #close>
            <Icon @click="store.resetData" icon="fas fa-xmark" class="text-violet-200 text-xl hover:scale-125 cursor-pointer" />
        </template>
        <div class="grid gap-3">
            <div v-if="store.horario.estatus === 'I'" class="flex justify-end">
                <span>Activo</span>
                <div class="flex gap-1 cursor-pointer text-gray-500">
                    <Switch :values="['A','I']" v-model="store.horario.estatus" class="bg-gray-400 w-12 has-[:checked]:bg-color-4"/>
                </div>
                <span>Inactivo</span>
            </div>
            <Input v-model="store.horario.hora_ini" option="label" type="time" title="Hora inicial" />
            <Input v-model="store.horario.hora_fin" option="label" type="time" title="Hora final" />
        </div>
        <hr class="my-4">
        <div class="flex gap-4">
            <label class="flex gap-1 cursor-pointer">
                <input v-model="store.dias" type="checkbox" value="LUN">
                <span>LUN</span>
            </label>
            <label class="flex gap-1 cursor-pointer">
                <input v-model="store.dias" type="checkbox" value="MAR">
                <span>MAR</span>
            </label>
            <label class="flex gap-1 cursor-pointer">
                <input v-model="store.dias" type="checkbox" value="MIE">
                <span>MIE</span>
            </label>
            <label class="flex gap-1 cursor-pointer">
                <input v-model="store.dias" type="checkbox" value="JUE">
                <span>JUE</span>
            </label>
            <label class="flex gap-1 cursor-pointer">
                <input v-model="store.dias" type="checkbox" value="VIE">
                <span>VIE</span>
            </label>
            <label class="flex gap-1 cursor-pointer">
                <input v-model="store.dias" type="checkbox" value="SAB">
                <span>SAB</span>
            </label>
            <label class="flex gap-1 cursor-pointer">
                <input v-model="store.dias" type="checkbox" value="DOM">
                <span>DOM</span>
            </label>
        </div>
        <Validate-Errors :errors="store.errors" v-if="store.errors != 0" />
        
        <template #footer>
            <Button @click="store.resetData" text="Cancelar" icon="fas fa-xmark" class="btn-secondary" />
            <Button @click="store.update" text="Actualizar" icon="fas fa-rotate" class="btn-primary" :loading="store.loading.update" />
        </template>
    </Modal>

    <Modal :open="store.modal.delete">
        <div class="flex items-center gap-4">
            <Icon icon="fas fa-triangle-exclamation" class="text-6xl text-orange-500" />
            <div class="grid">
                <h1>¿Esta seguro de eliminar el horario?</h1>
                <h2 class="text-center font-semibold text-xl">{{ store.horario.full }}</h2>
            </div>
        </div>
        <template #footer>
            <Button @click="store.resetData" text="Cancelar" icon="fas fa-xmark" class="btn-secondary" />
            <Button @click="store.destroy" text="Sí, estoy seguro" icon="fas fa-check" class="btn-danger" :loading="store.loading.destroy" />
        </template>
    </Modal>
</template>
