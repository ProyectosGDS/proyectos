<script setup>
    import { onMounted } from 'vue'

    import { useAuthStore } from '@/stores/auth'
    import { useProgramacionEventosStore } from '@/stores/programacion-eventos'
    
    import Badge from '@/components/Badge.vue'

    const auth = useAuthStore()
    const store = useProgramacionEventosStore()

    onMounted(() => {
        store.fetch()
        store.fetchTiposEventos()
        store.fetchStatuses()
    })
    
</script>

<template>
    <Card class="bg-white pt-8 pb-2 px-2 lg:px-8">
        <h1 class="text-2xl text-gray-500 font-semibold">Programación de eventos</h1>
        <div v-if="auth.checkPermission('crear evento')" class="flex justify-center">
            <Tool-Tip message="Crear nuevo evento" class="-mt-8 text-color-4">
                <Button @click="store.modal.new = true" icon="fas fa-plus" class="btn-primary" />
            </Tool-Tip>
        </div>
        <Data-Table v-if="auth.checkPermission('ver eventos')" :headers="store.headers" :data="store.eventos" :loading="store.loading.fetch" :export="auth.checkPermission('exportar eventos')">
            <template #estado_evento.descripcion="{item}">
                <Badge :item="item" :text="item.estado_evento.descripcion" />
            </template>
            <template #actions="{item}">
                <div class="relative">
                    <Drop-Down-Button icon="fas fa-ellipsis-vertical">
                        <ul class="grid gap-3 text-violet-500 text-nowrap">
                            <li v-if="auth.checkPermission('editar evento')" @click="store.edit(item)" class="hover:font-medium cursor-pointer">
                                Editar
                            </li>
                            <li v-if="auth.checkPermission('cambiar status evento')" @click="store.status(item)" class="hover:font-medium cursor-pointer">
                                Cambiar estatus
                            </li>
                            <!-- <template v-if="auth.checkPermission('eliminar evento')">
                                <li v-if="!item.deleted_at" @click="store.deleteItem(item)" class="hover:font-medium cursor-pointer">
                                    Eliminar
                                </li>
                            </template> -->
                        </ul>
                    </Drop-Down-Button>
                </div>
            </template>
        </Data-Table>
    </Card>

    <!-- AREA DE MODALES -->
    <Modal :open="store.modal.new" icon="fas fa-calendar-plus" title="Crear nuevo evento" class="xl:w-1/2">
        <template #close>
            <Icon @click="store.resetData" icon="fas fa-xmark" class="text-violet-200 text-xl hover:scale-125 cursor-pointer" />
        </template>

        <div class="grid gap-3 lg:grid-cols-2">
            <div class="lg:col-span-2">
                <Input v-model="store.evento.titulo" option="label" title="Título del evento" :error="store.errors.hasOwnProperty('titulo')" />
            </div>
            <div class="lg:col-span-2">
                <Input v-model="store.evento.descripcion" option="text-area" rows="7" title="Descripción del evento" :error="store.errors.hasOwnProperty('descripcion')" />
            </div>
            <div class="lg:col-span-2">
                <Input v-model="store.evento.ubicacion" option="label" title="Ubicación del evento" :error="store.errors.hasOwnProperty('ubicacion')" />
            </div>
            <Input v-model="store.evento.fecha_ini" option="label" type="date" title="Fecha de inicio del evento" :error="store.errors.hasOwnProperty('fecha_ini')" />
            <Input v-model="store.evento.fecha_fin" option="label" type="date" title="Fecha final del evento" :error="store.errors.hasOwnProperty('fecha_fin')" />
            <Input v-model="store.evento.hora_ini" option="label" type="time" title="Hora inicial" :error="store.errors.hasOwnProperty('hora_ini')" />
            <Input v-model="store.evento.hora_fin" option="label" type="time" title="Hora final" :error="store.errors.hasOwnProperty('hora_fin')" />
            <Input v-model="store.evento.responsable" option="label" title="Responsable" :error="store.errors.hasOwnProperty('responsable')" />
            
            <Input v-model="store.evento.id_tipo_evento" option="select" title="Tipo de evento" :error="store.errors.hasOwnProperty('id_tipo_evento')">
                <option value=""> -- SELECCIONE -- </option>
                <option v-for="tipo in store.tipos_eventos" :value="tipo.id_tipo_evento">{{ tipo.descripcion }}</option>
            </Input>
        </div>

        <Validate-Errors :errors="store.errors" v-if="store.errors != 0" />

        <template #footer>
            <Button @click="store.resetData" text="Cancelar" icon="fas fa-xmark" class="btn-secondary rounded-full" />
            <Button @click="store.store" text="Crear" icon="fas fa-plus" class="btn-primary rounded-full" :loading="store.loading.store" />
        </template>
    </Modal>

    <Modal :open="store.modal.edit" title="Editar evento" icon="fas fa-calendar-plus" class="w-1/2">
        <template #close>
            <Icon @click="store.resetData" icon="fas fa-xmark" class="text-violet-200 text-xl hover:scale-125 cursor-pointer" />
        </template>

        <div class="grid gap-3 lg:grid-cols-2">
            <div class="lg:col-span-2">
                <Input v-model="store.evento.titulo" option="label" title="Título del evento" :error="store.errors.hasOwnProperty('titulo')" />
            </div>
            <div class="lg:col-span-2">
                <Input v-model="store.evento.descripcion" option="text-area" rows="7" title="Descripcion del evento" :error="store.errors.hasOwnProperty('descripcion')" />
            </div>
            <div class="lg:col-span-2">
                <Input v-model="store.evento.ubicacion" option="label" title="Ubicacion del evento" :error="store.errors.hasOwnProperty('ubicacion')" />
            </div>
            <Input v-model="store.evento.fecha_ini" option="label" type="date" title="Fecha de inicio del evento" :error="store.errors.hasOwnProperty('fecha_ini')" />
            <Input v-model="store.evento.fecha_fin" option="label" type="date" title="Fecha final del evento" :error="store.errors.hasOwnProperty('fecha_fin')" />
            <Input v-model="store.evento.hora_ini" option="label" type="time" title="Hora inicial" :error="store.errors.hasOwnProperty('hora_ini')" />
            <Input v-model="store.evento.hora_fin" option="label" type="time" title="Hora final" :error="store.errors.hasOwnProperty('hora_fin')" />
            <Input v-model="store.evento.responsable" option="label" title="Responsable" :error="store.errors.hasOwnProperty('responsable')" />
            
            <Input v-model="store.evento.id_tipo_evento" option="select" title="Tipo de evento" :error="store.errors.hasOwnProperty('id_tipo_evento')">
                <option value=""> -- SELECCIONE -- </option>
                <option v-for="tipo in store.tipos_eventos" :value="tipo.id_tipo_evento">{{ tipo.descripcion }}</option>
            </Input>
        </div>

        <Validate-Errors :errors="store.errors" v-if="store.errors != 0" />
        
        <template #footer>
            <Button @click="store.resetData" text="Cancelar" icon="fas fa-xmark" class="btn-secondary rounded-full" />
            <Button @click="store.update" text="Actualizar" icon="fas fa-rotate" class="btn-primary rounded-full" :loading="store.loading.update" />
        </template>
    </Modal>
    
    <Modal :open="store.modal.status" title="Cambiar estatus evento" icon="fas fa-calendar-check">
        <template #close>
            <Icon @click="store.resetData" icon="fas fa-xmark" class="text-violet-200 text-xl hover:scale-125 cursor-pointer" />
        </template>

        <div class="grid gap-3 lg:grid-cols-2">
            <div class="lg:col-span-2">
                <Input v-model="store.evento.id_estatus" option="select" title="Estatus" :error="store.errors.hasOwnProperty('id_estatus')">
                    <option value=""> -- SELECCIONE -- </option>
                    <option v-for="status in store.statuses" :value="status.id_estatus">{{ status.descripcion }}</option>
                </Input>
            </div>
        </div>

        <Validate-Errors :errors="store.errors" v-if="store.errors != 0" />
        
        <template #footer>
            <Button @click="store.resetData" text="Cancelar" icon="fas fa-xmark" class="btn-secondary rounded-full" />
            <Button @click="store.changeStatusEvent" text="Actualizar" icon="fas fa-rotate" class="btn-primary rounded-full" :loading="store.loading.update" />
        </template>
    </Modal>

    <!-- <Modal :open="store.modal.delete">
        <div class="flex items-center gap-4">
            <Icon icon="fas fa-triangle-exclamation" class="text-6xl text-orange-500" />
            <div class="grid">
                <h1>¿Esta seguro de eliminar el evento?</h1>
                <h2 class="text-center font-semibold text-xl">{{ store.evento.titulo }}</h2>
            </div>
        </div>
        <template #footer>
            <Button @click="store.resetData" text="Cancelar" icon="fas fa-xmark" class="btn-secondary rounded-full" />
            <Button @click="store.destroy" text="Sí, estoy seguro" icon="fas fa-check" class="btn-danger rounded-full" :loading="store.loading.destoy" />
        </template>
    </Modal> -->
</template>


