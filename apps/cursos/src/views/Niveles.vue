<script setup>

    import { useAuthStore } from '@/stores/auth'
    import { onMounted} from 'vue'
    import { useNivelesStore } from '@/stores/niveles'

    const auth = useAuthStore()
    const store = useNivelesStore()

    onMounted(() => {
        store.fetch()
    })

</script>

<template>
    <Card class="bg-white pt-8 pb-2 px-2 lg:px-8">
        <h1 class="text-2xl text-gray-500 font-semibold">Niveles</h1>
        <div v-if="auth.checkPermission('crear nivel')" class="flex justify-center">
            <Tool-Tip message="Crear nuevo nivel" class="-mt-8 text-color-4">
                <Button @click="store.modal.new = true" icon="fas fa-plus" class="btn-primary" />
            </Tool-Tip>
        </div>
        <Data-Table v-if="auth.checkPermission('ver niveles')" :headers="store.headers" :data="store.niveles" :loading="store.loading.fetch" :export="auth.checkPermission('exportar niveles')">
            <template #estatus="{item}">
                <Icon :icon="item.estatus === 'I' ? 'fas fa-xmark' : 'fas fa-check'" :class="item.estatus === 'I' ? 'text-red-500' : 'text-green-500'"/>
            </template>
            <template #actions="{item}">
                <div class="relative">
                    <Drop-Down-Button icon="fas fa-ellipsis-vertical">
                        <ul class="grid gap-3 text-violet-500">
                            <li v-if="auth.checkPermission('editar nivel')" @click="store.edit(item)" class="hover:font-medium cursor-pointer">
                                Editar
                            </li>
                            <hr>
                            <template v-if="auth.checkPermission('eliminar nivel')">
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
    <Modal :open="store.modal.new" icon="fas fa-book" title="Crear nuevo nivel">
        <template #close>
            <Icon @click="store.resetData" icon="fas fa-xmark" class="text-violet-200 text-xl hover:scale-125 cursor-pointer" />
        </template>
        <div class="grid gap-3">
            <Input v-model="store.nivel.descripcion" option="label" title="Nombre nivel" :error="store.errors.hasOwnProperty('descripcion')" />
        </div>

        <Validate-Errors :errors="store.errors" v-if="store.errors != 0" />

        <template #footer>
            <Button @click="store.resetData" text="Cancelar" icon="fas fa-xmark" class="btn-secondary" />
            <Button @click="store.store" text="Crear" icon="fas fa-plus" class="btn-primary" :loading="store.loading.store" />
        </template>
    </Modal>

    <Modal :open="store.modal.edit" title="Editar nivel" icon="fas fa-book">
        <template #close>
            <Icon @click="store.resetData" icon="fas fa-xmark" class="text-violet-200 text-xl hover:scale-125 cursor-pointer" />
        </template>
        <div class="grid gap-3">
            <div v-if="store.nivel.estatus === 'I'" class="flex justify-end">
                <div class="flex gap-3 text-gray-500">
                    <span>Activo</span>
                    <Switch :values="['I','A']" v-model="store.nivel.estatus" class="bg-gray-400 w-12 has-[:checked]:bg-color-4"/>
                    <span>Inactivo</span>
                </div>
            </div>
            <Input v-model="store.nivel.descripcion" option="label" title="Nombre nivel" :error="store.errors.hasOwnProperty('descripcion')" />
        </div>

        <Validate-Errors :errors="store.errors" v-if="store.errors != 0" />
        
        <template #footer>
            <Button @click="store.resetData" text="Cancelar" icon="fas fa-xmark" class="btn-secondary" />
            <Button @click="store.update()" text="Actualizar" icon="fas fa-rotate" class="btn-primary" :loading="store.loading.update" />
        </template>
    </Modal>

    <Modal :open="store.modal.delete">
        <div class="flex items-center gap-4">
            <Icon icon="fas fa-triangle-exclamation" class="text-6xl text-orange-500" />
            <div class="grid">
                <h1>¿Esta seguro de eliminar el nivel?</h1>
                <h2 class="text-center font-semibold text-xl">{{ store.nivel.descripcion }}</h2>
            </div>
        </div>
        <template #footer>
            <Button @click="store.resetData" text="Cancelar" icon="fas fa-xmark" class="btn-secondary" />
            <Button @click="store.destroy" text="Sí, estoy seguro" icon="fas fa-check" class="btn-danger" :loading="store.loading.destroy" />
        </template>
    </Modal>
</template>

<style scoped>
    select, input {
        @apply border border-gray-300 rounded-lg w-full px-2 h-9;
    }

    th {
        @apply text-center uppercase font-normal text-sm;
    }

    td {
        @apply px-2;
    }
</style>