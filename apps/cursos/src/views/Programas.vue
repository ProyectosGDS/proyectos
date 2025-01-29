<script setup>
    import { useAuthStore } from '@/stores/auth'
    import { computed, onMounted, ref, watchEffect } from 'vue'
    import { useProgramasStore } from '@/stores/programas'

    const auth = useAuthStore()
    const store = useProgramasStore()
    
    const search = ref('')

    const searchNiveles = computed(() => {
        return store.niveles.filter(nivel => nivel.descripcion.toLowerCase().match(search.value.toLocaleLowerCase()))
    })

    const tiene_escuelas = ref('N')

    watchEffect(() => {
        if ( store.dependencias.filter(dependencia => dependencia.id_dependencia == store.programa.id_dependencia)[0]?.tiene_escuelas == 'S') {
            tiene_escuelas.value = 'S'
        } else {
            store.programa.escuela = {}
            tiene_escuelas.value = 'N'
        }
    })

    onMounted(() => {
        store.fetch()
        store.fetchDependencias()
        store.fetchNiveles()
        store.fetchEscuelas()
    })

</script>

<template>
    <Card class="bg-white pt-8 pb-2 px-2 lg:px-8">
        <h1 class="text-2xl text-gray-500 font-semibold">Programas</h1>
        <div v-if="auth.checkPermission('crear programa')" class="flex justify-center">
            <Tool-Tip message="Crear nuevo programa" class="-mt-8 text-color-4">
                <Button @click="store.modal.new = true" icon="fas fa-plus" class="btn-primary" />
            </Tool-Tip>
        </div>
        <Data-Table v-if="auth.checkPermission('ver programas')" :headers="store.headers" :data="store.programas" :loading="store.loading.fetch" :export="auth.checkPermission('exportar programas')">
            <template #estatus="{item}">
                <Icon :icon="item.estatus === 'I' ? 'fas fa-xmark' : 'fas fa-check'" :class="item.estatus === 'I' ? 'text-red-500' : 'text-green-500'"/>
            </template>
            <template #actions="{item}">
                <div class="relative">
                    <Drop-Down-Button icon="fas fa-ellipsis-vertical">
                        <ul class="grid gap-3 text-violet-500">
                            <li v-if="auth.checkPermission('editar programa')" @click="store.edit(item)" class="hover:font-medium cursor-pointer">
                                Editar
                            </li>
                            <hr>
                            <template v-if="auth.checkPermission('eliminar programa')">
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
    <Modal :open="store.modal.new" icon="fas fa-clipboard-list" title="Crear nuevo programa" class="w-1/3">
        <template #close>
            <Icon @click="store.resetData" icon="fas fa-xmark" class="text-violet-200 text-xl hover:scale-125 cursor-pointer" />
        </template>

        <div class="grid gap-3">
            <Input v-model="store.programa.nombre" option="label" title="Nombre programa" :error="store.errors.hasOwnProperty('nombre')" />
            <Input v-model="store.programa.id_dependencia" option="select" title="Seleccione dependencia" :error="store.errors.hasOwnProperty('id_dependencia')">
                <option value=""> -- Seleccione -- </option>
                <option v-for="dependencia in store.dependencias" :value="dependencia.id_dependencia">{{ dependencia.nombre }}</option>
            </Input>
            <Input v-if="tiene_escuelas === 'S'" v-model="store.programa.escuela.id_escuela" option="select" title="Seleccione escuela" :error="store.errors.hasOwnProperty('escuela.id_escuela')">
                <option value=""> -- Seleccione -- </option>
                <option v-for="escuela in store.escuelas" :value="escuela.id_escuela">{{ escuela.nombre }}</option>
            </Input>
            <fieldset class="border rounded-lg p-4">
                <legend class="px-2 text-color-4"> NIVELES </legend>
                <Input type="search" icon="fas fa-search" placeholder="Buscar nivel" v-model="search" />
                <div>
                    <template v-for="nivel in searchNiveles">
                        <label class="flex gap-2 cursor-pointer">
                            <input type="checkbox" v-model="store.niveles_seleccionados" :value="nivel.id_nivel">
                            <span class="text-sm">{{ nivel.descripcion }}</span>
                        </label>
                    </template>
                </div>
            </fieldset>
        </div> 

        <Validate-Errors :errors="store.errors" v-if="store.errors != 0" />

        <template #footer>
            <Button @click="store.resetData" text="Cancelar" icon="fas fa-xmark" class="btn-secondary" />
            <Button @click="store.store" text="Crear" icon="fas fa-plus" class="btn-primary" :loading="store.loading.store" />
        </template>
    </Modal>

    <Modal :open="store.modal.edit" title="Editar programa" icon="fas fa-clipboard-list" class="w-1/3">
        <template #close>
            <Icon @click="store.resetData" icon="fas fa-xmark" class="text-violet-200 text-xl hover:scale-125 cursor-pointer" />
        </template>
        <div class="grid gap-3">
            <div v-if="store.programa.estatus === 'I'" class="flex justify-end">
                <div class="flex gap-3 text-gray-500">
                    <span>Activo</span>
                    <Switch :values="['I','A']" v-model="store.programa.estatus" class="bg-gray-400 w-12 has-[:checked]:bg-violet-400"/>
                    <span>Inactivo</span>
                </div>
            </div>
            <Input v-model="store.programa.nombre" option="label" title="Nombre programa" :error="store.errors.hasOwnProperty('nombre')" />
            <Input v-model="store.programa.id_dependencia" option="select" title="Seleccione dependencia" :error="store.errors.hasOwnProperty('id_dependencia')">
                <option value=""> -- Seleccione -- </option>
                <option v-for="dependencia in store.dependencias" :value="dependencia.id_dependencia">{{ dependencia.nombre }}</option>
            </Input>
            <Input v-if="tiene_escuelas === 'S'" v-model="store.programa.escuela.id_escuela" option="select" title="Seleccione escuela" :error="store.errors.hasOwnProperty('id_escuela')">
                <option value=""> -- Seleccione -- </option>
                <option v-for="escuela in store.escuelas" :value="escuela.id_escuela">{{ escuela.nombre }}</option>
            </Input>
            <fieldset class="border rounded-lg p-4">
                <legend class="px-2 text-color-4"> NIVELES </legend>
                <Input type="search" icon="fas fa-search" placeholder="Buscar nivel" v-model="search" />
                <div>
                    <template v-for="nivel in searchNiveles">
                        <label class="flex gap-2 cursor-pointer">
                            <input type="checkbox" v-model="store.niveles_seleccionados" :value="nivel.id_nivel">
                            <span class="text-sm">{{ nivel.descripcion }}</span>
                        </label>
                    </template>
                </div>
            </fieldset>
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
                <h1>¿Esta seguro de eliminar el programa?</h1>
                <h2 class="text-center font-semibold text-xl">{{ store.programa.nombre }}</h2>
            </div>
        </div>
        <template #footer>
            <Button @click="store.resetData" text="Cancelar" icon="fas fa-xmark" class="btn-secondary" />
            <Button @click="store.destroy" text="Sí, estoy seguro" icon="fas fa-check" class="btn-danger" :loading="store.loading.destroy" />
        </template>
    </Modal>
</template>