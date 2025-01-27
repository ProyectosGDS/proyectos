<script setup>

    import { computed, onMounted } from 'vue'
    import { useAuthStore } from '@/stores/auth'
    import { useRolesStore } from '@/stores/roles'
    import { usePermisosStore } from '@/stores/permisos'

    const auth = useAuthStore()
    const store = useRolesStore()
    const permisos = usePermisosStore()

    const groupPermissions = computed(() => permisos.getGroupedPermisos())

    onMounted(() => {
        store.fetch()
        permisos.fetch()
    })
</script>

<template>
    <Card class="bg-white pt-8 pb-2 px-2 lg:px-8">
        <h1 class="text-2xl text-gray-500 font-semibold">Roles</h1>
        <div v-if="auth.checkPermission('crear role')" class="flex justify-center">
            <Tool-Tip message="Crear nuevo role" class="-mt-8 text-color-4">
                <Button @click="store.modal.new = true" icon="fas fa-plus" class="btn-primary" />
            </Tool-Tip>
        </div>
        <Data-Table v-if="auth.checkPermission('ver roles')" :headers="store.headers" :data="store.roles" :loading="store.loading.fetch" :export="auth.checkPermission('exportar roles')">
            <template #actions="{item}">
                <div class="relative">
                    <Drop-Down-Button icon="fas fa-ellipsis-vertical">
                        <ul class="grid gap-3 text-violet-500 text-nowrap">
                            <li v-if="auth.checkPermission('editar role')" @click="store.edit(item)" class="hover:font-medium cursor-pointer">
                                Editar
                            </li>
                            <template v-if="auth.checkPermission('eliminar role')">
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
    <Modal :open="store.modal.new" icon="fas fa-user-tag" title="Crear nuevo role" class="w-1/2">
        <template #close>
            <Icon @click="store.resetData" icon="fas fa-xmark" class="text-violet-200 text-xl hover:scale-125 cursor-pointer" />
        </template>

        <div class="grid gap-3">
            <Input v-model="store.role.nombre" option="label" title="Nombre del role" :error="store.errors.hasOwnProperty('nombre')" />
            <Input v-model="store.role.descripcion" option="text-area" rows="7" title="Descripcion" :error="store.errors.hasOwnProperty('descripcion')" />
            <div>
                <h1 class="text-center font-medium text-color-4 text-lg">Permisos de los proyectos disponibles</h1>
                <template v-for="(group , app) in groupPermissions">
                    <details :open="true" class="border border-color-4 p-3 rounded-lg text-color-4 my-2">
                        <summary class="cursor-pointer">PROYECTO: {{ app }}</summary>
                        <div class="grid grid-cols-5 gap-2">
                            <label v-for="permission in group" class="flex items-center gap-1 text-sm">
                                <input type="checkbox" v-model="store.permisos" :value="permission.id_permiso" >
                                {{ permission.nombre }}
                            </label>
                        </div>
                    </details>
                </template>
            </div>
        </div>
        <Validate-Errors :errors="store.errors" v-if="store.errors != 0" />

        <template #footer>
            <Button @click="store.resetData" text="Cancelar" icon="fas fa-xmark" class="btn-secondary rounded-full" />
            <Button @click="store.store" text="Crear" icon="fas fa-plus" class="btn-primary rounded-full" :loading="store.loading.store" />
        </template>
    </Modal>

    <Modal :open="store.modal.edit" title="Editar role" icon="fas fa-user-tag" class="w-1/2">
        <template #close>
            <Icon @click="store.resetData" icon="fas fa-xmark" class="text-violet-200 text-xl hover:scale-125 cursor-pointer" />
        </template>

        <div class="grid gap-3">
            <Input v-model="store.role.nombre" option="label" title="Nombre del role" :error="store.errors.hasOwnProperty('nombre')" />
            <Input v-model="store.role.descripcion" option="text-area" rows="7" title="Descripcion" :error="store.errors.hasOwnProperty('descripcion')" />
            <div>
                <h1 class="text-center font-medium text-color-4 text-lg">Permisos de los proyectos disponibles</h1>
                <template v-for="(group , app) in groupPermissions">
                    <details :open="true" class="border border-color-4 p-3 rounded-lg text-color-4 my-2">
                        <summary class="cursor-pointer">PROYECTO: {{ app }}</summary>
                        <div class="grid grid-cols-5 gap-2">
                            <label v-for="permission in group" class="flex items-center gap-1 text-sm">
                                <input type="checkbox" v-model="store.permisos" :value="permission.id_permiso">
                                {{ permission.nombre }}
                            </label>
                        </div>
                    </details>
                </template>
            </div>
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
                <h1>¿Esta seguro de eliminar el role?</h1>
                <h2 class="text-center font-semibold text-xl">{{ store.role.nombre }}</h2>
            </div>
        </div>
        <template #footer>
            <Button @click="store.resetData" text="Cancelar" icon="fas fa-xmark" class="btn-secondary rounded-full" />
            <Button @click="store.destroy" text="Sí, estoy seguro" icon="fas fa-check" class="btn-danger rounded-full" :loading="store.loading.destoy" />
        </template>
    </Modal>
</template>