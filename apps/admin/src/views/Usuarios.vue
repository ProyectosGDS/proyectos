<script setup>
    import { onMounted } from 'vue'
    
    import { useUsuariosStore } from '@/stores/usuarios'
    import { useAuthStore } from '@/stores/auth'

    
    const store = useUsuariosStore()
    const auth = useAuthStore()

    onMounted(() => {
        store.fetch()
        store.fetchDependencias()
        store.fetchRoles()
        store.fetchPages()
    })
</script>

<template>
    <Card class="bg-white pt-8 pb-2 px-2 lg:px-8">
        <h1 class="text-2xl text-gray-500 font-semibold">Usuarios</h1>
        <div v-if="auth.checkPermission('crear usuario')" class="flex justify-center">
            <Tool-Tip message="Crear nuevo usuario" class="-mt-8 text-color-4">
                <Button @click="store.modal.new = true" icon="fas fa-plus" class="btn-primary" />
            </Tool-Tip>
        </div>
        <Data-Table v-if="auth.checkPermission('ver usuarios')" :headers="store.headers" :data="store.usuarios" :loading="store.loading.fetch" :export="auth.checkPermission('exportar usuarios')">
            <template #actions="{item}">
                <div class="relative">
                    <Drop-Down-Button icon="fas fa-ellipsis-vertical">
                        <ul class="grid gap-3 text-violet-500 text-nowrap">
                            <li v-if="auth.checkPermission('editar usuario')" @click="store.edit(item)" class="hover:font-medium cursor-pointer">
                                Editar
                            </li>
                            <li v-if="auth.checkPermission('asignar paginas usuario')" @click="store.assign(item)" class="hover:font-medium cursor-pointer">
                                Asignar paginas usuario
                            </li>
                            <li v-if="auth.checkPermission('reiniciar contraseña')" @click="store.restart(item)" class="hover:font-medium cursor-pointer">
                                Reiniciar contraseña
                            </li>
                            <li v-if="auth.checkPermission('eliminar usuario')" @click="store.deleteItem(item)" class="hover:font-medium cursor-pointer">
                                Eliminar
                            </li>
                        </ul>
                    </Drop-Down-Button>
                </div>
            </template>
        </Data-Table>
    </Card>

    <!-- AREA DE MODALES -->
    <Modal :open="store.modal.new" icon="fas fa-school" title="Crear nuevo usuario">
        <template #close>
            <Icon @click="store.resetData" icon="fas fa-xmark" class="text-violet-200 text-xl hover:scale-125 cursor-pointer" />
        </template>

        <div class="grid gap-3 text-color-4">
            <div class="grid gap-3">
                <Input v-model="store.usuario.id_dependencia" option="select" title="Seleccione dependencia" :error="store.errors.hasOwnProperty('id_dependencia')">
                    <option value=""> -- seleccione -- </option>
                    <option v-for="dependencia in store.dependencias" :value="dependencia.id_dependencia">{{ dependencia.nombre }}</option>
                </Input>
                <Input v-model="store.usuario.id_rol" option="select" title="Seleccione role" :error="store.errors.hasOwnProperty('id_rol')">
                    <option value=""> -- seleccione -- </option>
                    <option v-for="role in store.roles" :value="role.id_rol">{{ role.nombre }}</option>
                </Input>
                <Input v-model="store.usuario.nombre" option="label" title="Nombre completo" :error="store.errors.hasOwnProperty('nombre')" />
                <Input v-model="store.usuario.cui" maxlength="13" option="label" title="cui" :error="store.errors.hasOwnProperty('cui')" />
            </div>
        </div>
        <Validate-Errors :errors="store.errors" v-if="store.errors != 0" />

        <template #footer>
            <Button @click="store.resetData" text="Cancelar" icon="fas fa-xmark" class="btn-secondary rounded-full" />
            <Button @click="store.store" text="Crear" icon="fas fa-plus" class="btn-primary rounded-full" :loading="store.loading.store" />
        </template>
    </Modal>

    <Modal :open="store.modal.edit" title="Editar usuario" icon="fas fa-school">
        <template #close>
            <Icon @click="store.resetData" icon="fas fa-xmark" class="text-violet-200 text-xl hover:scale-125 cursor-pointer" />
        </template>

        <div class="grid gap-3 text-color-4">
            <div class="grid gap-3">
                <Input v-model="store.usuario.id_dependencia" option="select" title="Seleccione dependencia" :error="store.errors.hasOwnProperty('id_dependencia')">
                    <option value=""> -- seleccione -- </option>
                    <option v-for="dependencia in store.dependencias" :value="dependencia.id_dependencia">{{ dependencia.nombre }}</option>
                </Input>
                <Input v-model="store.usuario.id_rol" option="select" title="Seleccione role" :error="store.errors.hasOwnProperty('id_rol')">
                    <option value=""> -- seleccione -- </option>
                    <option v-for="role in store.roles" :value="role.id_rol">{{ role.nombre }}</option>
                </Input>
                <Input v-model="store.usuario.nombre" option="label" title="Nombre completo" :error="store.errors.hasOwnProperty('nombre')" />
                <Input v-model="store.usuario.cui" maxlength="13" option="label" title="cui" :error="store.errors.hasOwnProperty('cui')" />
            </div>
        </div>

        <Validate-Errors :errors="store.errors" v-if="store.errors != 0" />
        
        <template #footer>
            <Button @click="store.resetData" text="Cancelar" icon="fas fa-xmark" class="btn-secondary rounded-full" />
            <Button @click="store.update" text="Actualizar" icon="fas fa-rotate" class="btn-primary rounded-full" :loading="store.loading.update" />
        </template>
    </Modal>

    <Modal :open="store.modal.assignPages" title="Asignar paginas a usuario" icon="fas fa-school" class="w-1/3">
        <template #close>
            <Icon @click="store.resetData" icon="fas fa-xmark" class="text-violet-200 text-xl hover:scale-125 cursor-pointer" />
        </template>

        <div class="grid gap-3 text-color-4">
            <Input v-model="store.usuario.nombre" option="label" title="Asignar paginas a" disabled readonly />
            <h1 class="text-xl font-medium text-center uppercase">Paginas disponibles</h1>
            <template v-for="pages in store.menuPaginas">
                <details :open="true" v-if="pages.children.length > 0 " class="border p-4 rounded-lg border-color-4">
                    <summary>{{ pages.nombre_pagina }}</summary>
                    <ul class="grid gap-3 grid-cols-3">
                        <li v-for="page in pages.children">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" v-model="store.paginas" :value="page.id_menu">
                                {{ page.nombre_pagina }}
                            </label>
                        </li>
                    </ul>
                </details>
                <div v-else>
                    <label v-if="!pages.deleted_at" class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" v-model="store.paginas" :value="pages.id_menu">
                        {{ pages.nombre_pagina }}
                    </label>
                </div>
            </template>
        </div>
        
        <Validate-Errors :errors="store.errors" v-if="store.errors != 0" />
        
        <template #footer>
            <Button @click="store.resetData" text="Cancelar" icon="fas fa-xmark" class="btn-secondary rounded-full" />
            <Button @click="store.assignPagesUser" text="Actualizar" icon="fas fa-rotate" class="btn-primary rounded-full" :loading="store.loading.update" />
        </template>
    </Modal>

    <Modal :open="store.modal.restart">
        <div class="flex items-center gap-4">
            <Icon icon="fas fa-lock" class="text-6xl text-orange-500" />
            <div class="grid">
                <h1>¿Esta seguro de reinciar la contraseña a ?</h1>
                <h2 class="text-center font-semibold text-xl">{{ store.usuario.nombre }}</h2>
            </div>
        </div>
        <template #footer>
            <Button @click="store.resetData" text="Cancelar" icon="fas fa-xmark" class="btn-secondary rounded-full" />
            <Button @click="store.restartPassword" text="Sí, reiniciar" icon="fas fa-check" class="btn-danger rounded-full" :loading="store.loading.destoy" />
        </template>
    </Modal>

    <Modal :open="store.modal.delete">
        <div class="flex items-center gap-4">
            <Icon icon="fas fa-triangle-exclamation" class="text-6xl text-orange-500" />
            <div class="grid">
                <h1>¿Esta seguro de eliminar el usuario ?</h1>
                <h2 class="text-center font-semibold text-xl">{{ store.usuario.nombre }}</h2>
            </div>
        </div>
        <template #footer>
            <Button @click="store.resetData" text="Cancelar" icon="fas fa-xmark" class="btn-secondary rounded-full" />
            <Button @click="store.destroy" text="Sí, estoy seguro" icon="fas fa-check" class="btn-danger rounded-full" :loading="store.loading.destoy" />
        </template>
    </Modal>
</template>