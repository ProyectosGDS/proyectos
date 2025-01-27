<script setup>

    import { onMounted } from 'vue'
    import { useAuthStore } from '@/stores/auth'
    import { usePermisosStore } from '@/stores/permisos'
    

    const store = usePermisosStore()
    const auth = useAuthStore()

    onMounted(() => {
        
            store.fetch()
        
    })
</script>

<template>
    <Card class="bg-white pt-8 pb-2 px-2 lg:px-8">
        <h1 class="text-2xl text-gray-500 font-semibold">Permisos</h1>
        <div v-if="auth.checkPermission('crear permiso')" class="flex justify-center">
            <Tool-Tip message="Crear nuevo permiso" class="-mt-8 text-color-4">
                <Button @click="store.modal.new = true" icon="fas fa-plus" class="btn-primary" />
            </Tool-Tip>
        </div>
        <Data-Table v-if="auth.checkPermission('ver permisos')" :headers="store.headers" :data="store.permisos" :loading="store.loading.fetch" :export="auth.checkPermission('exportar permisos')">
            <template #actions="{item}">
                <div class="relative">
                    <Drop-Down-Button icon="fas fa-ellipsis-vertical">
                        <ul class="grid gap-3 text-violet-500 text-nowrap">
                            <li v-if="auth.checkPermission('editar permiso')" @click="store.edit(item)" class="hover:font-medium cursor-pointer">
                                Editar
                            </li>
                            <template v-if="auth.checkPermission('eliminar permiso')">
                                <li @click="store.deleteItem(item)" class="hover:font-medium cursor-pointer">
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
    <Modal :open="store.modal.new" icon="fas fa-school" title="Crear nuevo permiso" class="w-96">
        <template #close>
            <Icon @click="store.resetData" icon="fas fa-xmark" class="text-violet-200 text-xl hover:scale-125 cursor-pointer" />
        </template>

        <div class="grid gap-3">
            <Input v-model="store.permiso.nombre" option="label" title="Nombre del permiso" :error="store.errors.hasOwnProperty('nombre')" />
            <Input v-model="store.permiso.app" option="label" title="Aplicación a la que pertenece" :error="store.errors.hasOwnProperty('app')" />
            <Input v-model="store.permiso.descripcion" option="text-area" rows="7" title="Descripcion" :error="store.errors.hasOwnProperty('descripcion')" />
        </div>

        <Validate-Errors :errors="store.errors" v-if="store.errors != 0" />

        <template #footer>
            <Button @click="store.resetData" text="Cancelar" icon="fas fa-xmark" class="btn-secondary rounded-full" />
            <Button @click="store.store" text="Crear" icon="fas fa-plus" class="btn-primary rounded-full" :loading="store.loading.store" />
        </template>
    </Modal>

    <Modal :open="store.modal.edit" title="Editar permiso" icon="fas fa-school" class="w-96">
        <template #close>
            <Icon @click="store.resetData" icon="fas fa-xmark" class="text-violet-200 text-xl hover:scale-125 cursor-pointer" />
        </template>

        <div class="grid gap-3">
            <Input v-model="store.permiso.nombre" option="label" title="Nombre del permiso" :error="store.errors.hasOwnProperty('nombre')" />
            <Input v-model="store.permiso.app" option="label" title="Aplicación a la que pertenece" :error="store.errors.hasOwnProperty('app')" />
            <Input v-model="store.permiso.descripcion" option="text-area" rows="7" title="Descripcion" :error="store.errors.hasOwnProperty('descripcion')" />
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
                <h1>¿Esta seguro de eliminar el permiso?</h1>
                <h2 class="text-center font-semibold text-xl">{{ store.permiso.nombre }}</h2>
            </div>
        </div>
        <template #footer>
            <Button @click="store.resetData" text="Cancelar" icon="fas fa-xmark" class="btn-secondary rounded-full" />
            <Button @click="store.destroy" text="Sí, estoy seguro" icon="fas fa-check" class="btn-danger rounded-full" :loading="store.loading.destoy" />
        </template>
    </Modal>
</template>