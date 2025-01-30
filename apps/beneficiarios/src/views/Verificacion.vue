<script setup>

    import { onMounted, watchEffect } from 'vue';
    
    import { useAuthStore } from '@/stores/auth'
    import { useVerificacionStore } from '@/stores/verificacion'
    import { useCatalogosStore } from '@/stores/catalogos'
    import { useBeneficiariosStore } from '@/stores/beneficiarios'

    import DatosPersonales from './datos/DatosPersonales.vue'
    import Domicilios from './datos/Domicilios.vue'
    import DatosMedicos from './datos/DatosMedicos.vue'
    import DatosAcademicos from './datos/DatosAcademicos.vue'
    import Responsables from './datos/Responsables.vue'
    import Emergencia from './datos/Emergencia.vue'

    const auth = useAuthStore()
    const store = useVerificacionStore()
    const catalogos = useCatalogosStore()
    const beneficiario = useBeneficiariosStore()
    

    onMounted(() => {
        catalogos.fetch()
    })

    watchEffect(() => {

        catalogos.fetchGrupoZona()

        if(beneficiario.reload) {
            store.fetch()
            beneficiario.reload = false
        }

        let id_programa = store.paramsFetch.id_programa

        if(id_programa) {
            catalogos.fetchNiveles(id_programa)
        } else {
            catalogos.niveles = []
        }

        let id_nivel = store.paramsFetch.id_nivel

        if(id_nivel) {
            catalogos.fetchCursos(id_programa, id_nivel)
        } else {
            catalogos.cursos = []
        }
    })

</script>

<template>
    <Card class="bg-white pt-8 pb-2 px-2 lg:px-8">
        <h1 class="text-2xl text-gray-500 font-semibold">Verificación de datos de inscritos</h1>
        <div v-if="auth.checkPermission('ver beneficiarios por verificar')">
            <div class="flex items-center gap-4 my-4">
                <Input v-model="store.paramsFetch.id_programa" option="select" title="Programa">
                    <option value=""> -- seleccione -- </option>
                    <template v-for="programa in catalogos.catalogos.programas">
                        <option v-if="programa.estatus == 'A'" :value="programa.id_programa">{{ programa.nombre }}</option>
                    </template>
                </Input>
                <Input v-model="store.paramsFetch.id_nivel" option="select" title="Nivel">
                    <option value=""> -- seleccione -- </option>
                    <template v-for="nivel in catalogos.niveles">
                        <option v-if="nivel.estatus == 'A'" :value="nivel.id_nivel">{{ nivel.descripcion }}</option>
                    </template>
                </Input>
                <Input v-model="store.paramsFetch.id_curso" option="select" title="Curso">
                    <option value=""> -- seleccione -- </option>
                    <template v-for="curso in catalogos.cursos">
                        <option v-if="curso.estatus == 'A'" :value="curso.id_curso">{{ curso.nombre }}</option>
                    </template>
                </Input>
                <Button @click="store.fetch()" text="FILTRAR" icon="fas fa-filter" class="btn-primary" :loading="store.loading.fetch" />
            </div>
            <Data-Table :headers="store.headers" :data="store.verificaciones" :loading="store.loading.fetch" :export="auth.checkPermission('exportar parentescos')">
                <template #actions="{item}">
                    <div class="relative">
                        <Drop-Down-Button icon="fas fa-ellipsis-vertical">
                            <ul class="grid gap-3 text-violet-500">
                                <li v-if="auth.checkPermission('verificar datos de beneficiario')" @click="beneficiario.edit(item.id_persona)" class="hover:font-medium cursor-pointer">
                                    Verificar datos
                                </li>
                            </ul>
                        </Drop-Down-Button>
                    </div>
                </template>
            </Data-Table>
        </div>
    </Card>
    <!-- AREA DE MODALES -->
    <Modal :open="store.modal.new" icon="fas fa-person-breastfeeding" title="Crear nuevo parentesco">
        <template #close>
            <Icon @click="store.resetData" icon="fas fa-xmark" class="text-violet-200 text-xl hover:scale-125 cursor-pointer" />
        </template>
        <div class="grid gap-3">
            <Input v-model="store.parentesco.descripcion" option="label" title="Nombre parentesco" :error="store.errors.hasOwnProperty('descripcion')" />
        </div>

        <Validate-Errors :errors="store.errors" v-if="store.errors != 0" />

        <template #footer>
            <Button @click="store.resetData" text="Cancelar" icon="fas fa-xmark" class="btn-secondary" />
            <Button @click="store.store" text="Crear" icon="fas fa-plus" class="btn-primary" :loading="store.loading.store" />
        </template>
    </Modal>

    <Modal :open="beneficiario.modal.edit" title="Editar parentesco" icon="fas fa-person-breastfeeding">
        <template #close>
            <Icon @click="beneficiario.resetData" icon="fas fa-xmark" class="text-violet-200 text-xl hover:scale-125 cursor-pointer" />
        </template>

        <Loading-Bar v-if="beneficiario.loading.show" class="h-1 bg-color-4" />

        <div v-if="beneficiario.beneficiario.hasOwnProperty('primer_nombre')" class="text-color-4 grid gap-4">
            <DatosPersonales />
            <Domicilios />
            <DatosMedicos />
            <DatosAcademicos />
            <Responsables v-if="parseInt(beneficiario.beneficiario.edad) < 18" />
            <Emergencia />
        </div>

        <Validate-Errors :errors="beneficiario.errors" v-if="beneficiario.errors != 0" />
        
        <template #footer>
            <Button @click="beneficiario.resetData" text="Cancelar" icon="fas fa-xmark" class="btn-secondary" />
            <Button @click="beneficiario.update()" text="Actualizar" icon="fas fa-rotate" class="btn-primary" :loading="beneficiario.loading.update" />
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