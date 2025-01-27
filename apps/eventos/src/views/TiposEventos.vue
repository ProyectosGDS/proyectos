<script setup>
    import { useAuthStore } from '@/stores/auth'
    import { onMounted } from 'vue'
    import { useTiposEventosStore } from '@/stores/tipos-eventos'

    const auth = useAuthStore()
    const store = useTiposEventosStore()

    onMounted(() => store.fetch())
</script>

<template>
    <Card class="bg-white pt-8 pb-2 px-2 lg:px-8">
        <h1 class="text-2xl text-gray-500 font-semibold">Tipos de evento</h1>
        <div v-if="auth.checkPermission('crear tipo evento')" class="flex justify-center">
            <Tool-Tip message="Crear nuevo evento" class="-mt-8 text-color-4">
                <Button @click="store.modal.new = true" icon="fas fa-plus" class="btn-primary" />
            </Tool-Tip>
        </div>
        <Data-Table v-if="auth.checkPermission('ver tipos eventos')" :headers="store.headers" :data="store.tipos_eventos" :loading="store.loading.fetch" :export="auth.checkPermission('exportar tipos eventos')">
            <template #actions="{item}">
                <div class="relative">
                    <Drop-Down-Button icon="fas fa-ellipsis-vertical">
                        <ul class="grid gap-3 text-violet-500 text-nowrap">
                            <li v-if="auth.checkPermission('editar tipo evento')" @click="store.edit(item)" class="hover:font-medium cursor-pointer">
                                Editar
                            </li>
                            <template v-if="auth.checkPermission('eliminar tipo evento')">
                                <li v-if="!item.deleted_at" @click="store.deleteItem(item)" class="hover:font-medium cursor-pointer">
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
    <Modal :open="store.modal.new" icon="fas fa-school" title="Crear nuevo evento" class="w-96">
        <template #close>
            <Icon @click="store.resetData" icon="fas fa-xmark" class="text-violet-200 text-xl hover:scale-125 cursor-pointer" />
        </template>

        <div class="grid gap-3">
            <Input v-model="store.tipo_evento.nombre" option="label" title="Nombre del evento" :error="store.errors.hasOwnProperty('nombre')" />
        </div>

        <Validate-Errors :errors="store.errors" v-if="store.errors != 0" />

        <template #footer>
            <Button @click="store.resetData" text="Cancelar" icon="fas fa-xmark" class="btn-secondary rounded-full" />
            <Button @click="store.store" text="Crear" icon="fas fa-plus" class="btn-primary rounded-full" :loading="store.loading.store" />
        </template>
    </Modal>

    <Modal :open="store.modal.edit" title="Editar evento" icon="fas fa-school" class="w-96">
        <template #close>
            <Icon @click="store.resetData" icon="fas fa-xmark" class="text-violet-200 text-xl hover:scale-125 cursor-pointer" />
        </template>

        <div class="grid gap-3">
            <div v-if="store.tipo_evento.deleted_at" class="flex justify-end">
                <div class="flex gap-3 text-gray-500">
                    <span>Activo</span>
                    <Switch :values="[null,0]" v-model="store.tipo_evento.deleted_at" class="bg-gray-400 w-12 has-[:checked]:bg-violet-400"/>
                    <span>Inactivo</span>
                </div>
            </div>
            <Input v-model="store.tipo_evento.nombre" option="label" title="Nombre del evento" :error="store.errors.hasOwnProperty('nombre')" :disabled="store.tipo_evento.deleted_at" />
        </div>

        <Validate-Errors :errors="store.errors" v-if="store.errors != 0" />
        
        <template #footer>
            <Button @click="store.resetData" text="Cancelar" icon="fas fa-xmark" class="btn-secondary rounded-full" />
            <Button @click="store.update" text="Actualizar" icon="fas fa-rotate" class="btn-primary rounded-full" :loading="store.loading.update" />
        </template>
    </Modal>

    <Modal :open="store.modal.delete">
        <div class="flex items-center gap-4">
            <Icon icon="fas fa-triangle-exclamation" class="text-6xl text-orange-500" />
            <div class="grid">
                <h1>¿Esta seguro de eliminar el evento?</h1>
                <h2 class="text-center font-semibold text-xl">{{ store.tipo_evento.nombre }}</h2>
            </div>
        </div>
        <template #footer>
            <Button @click="store.resetData" text="Cancelar" icon="fas fa-xmark" class="btn-secondary rounded-full" />
            <Button @click="store.destroy" text="Sí, estoy seguro" icon="fas fa-check" class="btn-danger rounded-full" :loading="store.loading.destoy" />
        </template>
    </Modal>
</template>