<script setup>
    import { computed, onMounted, ref } from 'vue'
    
    import { useAuthStore } from '@/stores/auth'
    import { useBeneficiariosStore } from '@/stores/beneficiarios'
    import { useCatalogosStore } from '@/stores/catalogos'
    import { useBitacoraStore } from '@/stores/bitacora'

    import DatosPersonales from './datos/DatosPersonales.vue'
    import Domicilios from './datos/Domicilios.vue'
    import Responsables from './datos/Responsables.vue'
    import Emergencia from './datos/Emergencia.vue'
    import DatosAcademicos from './datos/DatosAcademicos.vue'
    import DatosMedicos from './datos/DatosMedicos.vue'
    import { watchEffect } from 'vue'
import ServerSide from '@/components/ServerSide.vue'


    const auth = useAuthStore()
    const store = useBeneficiariosStore()
    const catalogos = useCatalogosStore()
    const bitacora = useBitacoraStore()

    const programa = ref({
        pemsum : null
    })

    function refresh (item) {
        store.reload = item
    }

    function verifyCui () {
        const cui = store.cui;
        clearCui()
        if(!cui){
            store.messageCui = 'Ingrese cui'
            store.success = false
            return false 
        }

        if (cui.length !== 13 || !/^[0-9]{4}\s?[0-9]{5}\s?[0-9]{4}$/.test(cui)) {
            store.messageCui = 'Cui invalido'
            store.success = false
            return false
        }

        const cleanCui = cui.replace(/\s/g, '');
        const depto = parseInt(cleanCui.substring(9, 11), 10);
        const muni = parseInt(cleanCui.substring(11, 13), 10);
        const numero = cleanCui.substring(0, 8);
        const verificador = parseInt(cleanCui.substring(8, 9), 10);

        const munisPorDepto = [
            { id: 1, cantidad: 17 }, { id: 2, cantidad: 8 }, { id: 3, cantidad: 16 },
            { id: 4, cantidad: 16 }, { id: 5, cantidad: 13 }, { id: 6, cantidad: 14 },
            { id: 7, cantidad: 19 }, { id: 8, cantidad: 8 }, { id: 9, cantidad: 24 },
            { id: 10, cantidad: 21 }, { id: 11, cantidad: 9 }, { id: 12, cantidad: 30 },
            { id: 13, cantidad: 32 }, { id: 14, cantidad: 21 }, { id: 15, cantidad: 8 },
            { id: 16, cantidad: 17 }, { id: 17, cantidad: 14 }, { id: 18, cantidad: 5 },
            { id: 19, cantidad: 11 }, { id: 20, cantidad: 11 }, { id: 21, cantidad: 7 },
            { id: 22, cantidad: 17 }
        ];

        if (depto === 0 || muni === 0 || depto > munisPorDepto.length || muni > munisPorDepto[depto - 1].cantidad) {
            store.messageCui = 'Cui invalido'
            store.success = false
            return false
        }

        const total = numero.split('').reduce((acc, digit, index) => acc + digit * (index + 2), 0)


        if (total % 11 === verificador) {
            store.fetchBeneficiarioUnico(cleanCui)
            store.beneficiario.cui = cleanCui    
            return true
        }

        store.messageCui = 'Cui invalido'
        store.success = false
        return false
    }

    onMounted(() => {
        catalogos.fetch()
    })

    function clearCui() {
        if(store.cui == '') {
            store.beneficiario = {
                    sexo : 'M',
                domicilios : {
                    grupo_x_zona : {}
                },
                responsables : {
                    sexo : 'M',
                },
                emergencia : {
                    sexo : 'M',
                },
                datos_academicos : {
                    tipo_establecimiento : 'PU'
                },
                datos_medicos : {},
            }
        }
    }

    watchEffect(() => {

        catalogos.fetchGrupoZona()

        let id_programa = store.asignacion.id_programa

        if(id_programa) {

            programa.value = catalogos.catalogos.programas.find(programa => programa.id_programa == id_programa)            
            catalogos.fetchNiveles(id_programa)
            
        } else {
            catalogos.niveles = []
        }

        let id_nivel = store.asignacion.id_nivel

        if(id_nivel && programa.value.pensum == null) { //cursos
            catalogos.fetchCursos(id_programa, id_nivel)
        } else {
            catalogos.cursos = []
        }

        if(id_nivel && programa.value.pensum != null) { // sedes
            catalogos.fetchSedes(id_programa, id_nivel)
        } else {
            catalogos.sedes = []
        }

        let id_curso = store.asignacion.id_curso

        if(id_curso) {
            catalogos.fetchClases(id_programa, id_nivel, id_curso)
        } else {
            catalogos.clases = []
        }

        if(catalogos.catalogos.hasOwnProperty('departamentos')) {
            catalogos.fetchMunicipiosDepartamento()
        }
    })
</script>

<template>
    <Card class="bg-white pt-8 pb-2 px-2 lg:px-8">
        <h1 class="text-2xl text-gray-500 font-semibold">Beneficiarios</h1>
        <div v-if="auth.checkPermission('crear beneficiario')" class="flex justify-center">
            <Tool-Tip message="Crear nuevo beneficiario" class="-mt-8 text-color-4">
                <Button @click="store.modal.new = true" icon="fas fa-plus" class="btn-primary" />
            </Tool-Tip>
        </div>
        <ServerSide v-if="auth.checkPermission('ver beneficiarios')" :headers="store.headers" src="get-beneficiarios" :reload="store.reload" @reloadData="refresh">
            <template #sexo="{item}">
                <Icon :icon="item.sexo == 'M' ? 'fas fa-person' : 'fas fa-person-dress'" :class="item.sexo == 'M' ? 'text-blue-500' : 'text-fuchsia-400'" />
            </template>
            <template #estatus="{item}">
                <Icon :icon="item.estatus == 'A' ? 'fas fa-check' : 'fas fa-xmark'" :class="item.estatus == 'A' ? 'text-green-500': 'text-red-500'" />
            </template>
            <template #actions="{item}">
                <div class="relative">
                    <Drop-Down-Button icon="fas fa-ellipsis-vertical">
                        <ul class="text-violet-500 text-nowrap">
                            <li v-if="auth.checkPermission('editar beneficiario')" @click="store.edit(item.id_persona)">
                                Editar
                            </li>
                            <li v-if="auth.checkPermission('asignar curso beneficiario')" @click="store.openSync(item)">
                                Asignar curso
                            </li>
                            <li v-if="auth.checkPermission('observaciones beneficiario')" @click="bitacora.observacion(item)">
                                Observaciones
                            </li>
                            <li v-if="auth.checkPermission('eliminar beneficiario')" @click="store.deleteItem(item)">
                                Deshabilitar beneficiario
                            </li>
                            <hr>
                            <li v-if="auth.checkPermission('ver bitacora beneficiario')" @click="bitacora.fetchBitacoras(item.id_persona)">
                                Historial
                            </li>
                            <!-- <li v-if="auth.checkPermission('cambio estado beneficiario')" @click="store.openStatus(item)">
                                Cambiar estado
                            </li> -->
                        </ul>
                    </Drop-Down-Button>
                </div>
            </template>
        </ServerSide>
    </Card>

    <!-- AREA DE MODALES -->
    <Modal :open="store.modal.new" title="Nuevo beneficiario" icon="fas fa-user-plus">
        <template #close>
            <Icon @click="store.resetData()" icon="fas fa-xmark" class="text-white text-2xl cursor-pointer" />
        </template>
        <div>
            <div>
                <span class="uppercase text-color-4">Busqueda por cui</span>
                <div class="flex items-center justify-between gap-2">
                    <input @keyup="verifyCui()" v-model="store.cui" placeholder="Ingrese cui" autofocus type="search" maxlength="13" minlength="13" class="input focus:outline-none" :class="{'focus:border-red-400 border-red-400 focus:outline-red-400': !store.success, 'focus:border-green-500 border-green-500 focus:outline-green-400' : store.success }" required >
                    <Button @click="verifyCui()" text="Buscar cui" icon="fas fa-search" class="btn-primary rounded-lg text-nowrap" :loading="store.loading.searchBeneficiario" />
                </div>
            </div>
            <small :class="store.success ? 'text-green-400' : 'text-red-400'">{{ store.messageCui }}</small>
            <br>
            <div class="text-color-4 grid gap-4">
                <DatosPersonales />
                <Domicilios />
                <DatosMedicos />
                <DatosAcademicos />
                <Responsables v-if="parseInt(store.beneficiario.edad) < 18" />
                <Emergencia />
            </div>
        </div>
        <Validate-Errors v-if="store.errors != 0" :errors="store.errors" />
        <template #footer>
            <Button @click="store.resetData()" text="Cancelar" class="btn-secondary" icon="fas fa-xmark" />
            <Button @click="store.store()" text="Guardar" class="btn-primary" icon="fas fa-save" :loading="store.loading.store" />
        </template>
    </Modal>

    <Modal :open="store.modal.edit" title="Actualizar beneficiario" icon="fas fa-user-pen">
        <template #close>
            <Icon @click="store.resetData()" icon="fas fa-xmark" class="text-white text-2xl cursor-pointer" />
        </template>
        
        <Loading-Bar v-if="store.loading.show" class="h-1 bg-color-4" />
        
        <div v-if="store.beneficiario.hasOwnProperty('primer_nombre')" class="text-color-4 grid gap-4">
            <div v-if="store.beneficiario.estatus === 'I'" class="flex justify-end">
                <div class="flex gap-3 text-gray-500">
                    <span>Activo</span>
                    <Switch :values="['A','I']" v-model="store.beneficiario.estatus" class="bg-gray-400 w-12 has-[:checked]:bg-color-4"/>
                    <span>Inactivo</span>
                </div>
            </div>
            <DatosPersonales />
            <Domicilios />
            <DatosMedicos />
            <DatosAcademicos />
            <Responsables v-if="parseInt(store.beneficiario.edad) < 18" />
            <Emergencia />
        </div>
        <Validate-Errors v-if="store.errors != 0" :errors="store.errors" />
        <template #footer>
            <Button @click="store.resetData()" text="Cancelar" class="btn-secondary" icon="fas fa-xmark" />
            <Button @click="store.update()"  text="actualizar" class="btn-primary" icon="fas fa-arrows-rotate" :loading="store.loading.update" />
        </template>  
    </Modal>

    <Modal :open="store.modal.delete" title="Desactivar beneficiario" icon="fas fa-user-xmark">
        <template #close>
            <Icon @click="store.resetData()" icon="fas fa-xmark" class="text-white text-2xl cursor-pointer" />
        </template>
        <div class="flex items-center gap-3">
            <Icon icon="fas fa-triangle-exclamation" class="text-red-500 text-7xl" />
            <div>
                <p class="text-xl ">¿Esta seguro de desactivar al beneficiario?</p>
                <strong>{{ store.beneficiario.nombre_completo }}</strong>
            </div>
        </div>
        <template #footer>
            <Button @click="store.resetData()" text="Cancelar" class="btn-secondary" icon="fas fa-xmark" />
            <Button @click="store.destroy()" text="Sí, desactivar" class="btn-danger" icon="fas fa-user-xmark" :loading="store.loading.delete" />
        </template> 
    </Modal>

    <Modal :open="bitacora.modal.bitacora" title="Historial" icon="fas fa-book">
        <template #close>
            <Icon @click="bitacora.resetData()" icon="fas fa-xmark" class="text-white text-2xl cursor-pointer" />
        </template>
        <details :open="true" class="border rounded-lg border-color-4 p-4">
            <summary class="text-lg font-semibold text-color-4 px-3">Bitacora de asignaciones</summary>
            <Data-Table :headers="bitacora.headersAsignaciones" :data="bitacora.asignaciones" :loading="bitacora.loading.fetch" :filterAdvance="false" :export="false" />
        </details>
        <br>
        <details :open="true" class="border rounded-lg border-color-4 p-4">
            <summary class="text-lg font-semibold text-color-4 px-3">Bitacora de observaciones</summary>
            <Data-Table :headers="bitacora.headersHistorial" :data="bitacora.observaciones" :loading="bitacora.loading.bitacora" :filterAdvance="false" :export="false" />
        </details>
        <br>
        <details :open="true" class="border rounded-lg border-color-4 p-4">
            <summary class="text-lg font-semibold text-color-4 px-3">Bitacora de acciones</summary>
            <Data-Table :headers="bitacora.headersHistorial" :data="bitacora.acciones" :loading="bitacora.loading.bitacora" :filterAdvance="false" :export="false" />
        </details>
        <template #footer>
            <Button @click="bitacora.resetData()" text="Cancelar" class="btn-secondary" icon="fas fa-xmark" />
        </template> 
    </Modal>

    <Modal :open="bitacora.modal.observacion" title="Observación" icon="fas fa-message">
        <template #close>
            <Icon @click="bitacora.resetData()" icon="fas fa-xmark" class="text-white text-2xl cursor-pointer" />
        </template>
        <div class="grid gap-4">
            <Input option="label" title="Beneficiario" v-model="bitacora.historial.nombre_completo" readonly disabled />
            <div>
                <Input option="text-area" title="Observaciones" maxlength="500" rows="7" v-model="bitacora.historial.descripcion" :error="bitacora.errors.hasOwnProperty('descripcion')" />
            </div>
        </div>
        <Validate-Errors :errors="bitacora.errors" v-if="bitacora.errors != 0" />
        <template #footer>
            <Button @click="bitacora.resetData()" text="Cancelar" class="btn-secondary" icon="fas fa-xmark" />
            <Button @click="bitacora.store()" text="Guardar Observación" class="btn-primary" icon="fas fa-message" :loading="bitacora.loading.store" />
        </template> 
    </Modal>

    <Modal :open="store.modal.asignacion" title="Asignar cursos" icon="fas fa-book">
        <template #close>
            <Icon @click="store.resetData()" icon="fas fa-xmark" class="text-white text-2xl cursor-pointer" />
        </template>
        <div class="grid gap-4">
            <Input option="label" title="Beneficiario" v-model="store.asignacion.nombre_completo" readonly disabled />
            <Input option="select" title="Programas" v-model="store.asignacion.id_programa">
                <option value=""> --seleccione -- </option>
                <template v-for="programa in catalogos.catalogos?.programas">
                    <option v-if="programa.estatus == 'A'" :value="programa.id_programa">{{ programa.nombre }}</option>
                </template>
            </Input>
            <Input option="select" title="Niveles" v-model="store.asignacion.id_nivel">
                <option value=""> --seleccione -- </option>
                <template v-for="nivel in catalogos.niveles">
                    <option :value="nivel.id_nivel">{{ nivel.descripcion }}</option>
                </template>
            </Input>
            <Input v-if="programa.pensum == null" option="select" title="Cursos" v-model="store.asignacion.id_curso">
                <option value=""> --seleccione -- </option>
                <template v-for="curso in catalogos.cursos">
                    <option :value="curso.id_curso">{{ curso.nombre }}</option>
                </template>
            </Input>

            <Input v-else option="select" title="Sedes" v-model="store.asignacion.id_sede">
                <option value=""> --seleccione -- </option>
                <template v-for="sede in catalogos.sedes">
                    <option :value="sede.id_sede">{{ sede.descripcion }}</option>
                </template>
            </Input>
            
            <div class="grid gap-2">
                <div class="flex items-center gap-3" v-for="clase in catalogos.clases">
                    <Card class="bg-gray-100 p-2 w-full" >
                        <div class="grid grid-cols-2 text-xs">
                            <div class="flex gap-3">
                                <span class="uppercase font-medium">instructor: </span>
                                <span>{{ clase.instructor?.nombre }}</span>
                            </div>
                            <div class="flex gap-3">
                                <span class="uppercase font-medium">sede: </span>
                                <span>{{ clase.sede?.descripcion }}</span>
                            </div>
                            <div class="flex gap-3">
                                <span class="uppercase font-medium">temporalidad: </span>
                                <span>{{ clase.temporalidad?.descripcion }}</span>
                            </div>
                            <div class="flex gap-3">
                                <span class="uppercase font-medium">sección: </span>
                                <span>{{ clase.seccion }}</span>
                            </div>
                            <div class="flex gap-3">
                                <span class="uppercase font-medium">modalidad: </span>
                                <span>{{ clase.modalidad }}</span>
                            </div>
                            <div class="col-span-2 flex gap-3">
                                <span class="uppercase font-medium">horario: </span>
                                <span>{{ clase.horario?.full }}</span>
                            </div>
                        </div>
                    </Card>
                    <input type="radio" name="clase" :value="clase.id_clase" v-model="store.asignacion.id_clase">
                </div>
            </div>
        </div>
        <Validate-Errors :errors="store.errors" v-if="store.errors != 0" />
        <template #footer>
            <Button @click="store.resetData()" text="Cancelar" class="btn-secondary" icon="fas fa-xmark" />
            <Button @click="store.asignarCurso()" text="Asignar curso" class="btn-primary" icon="fas fa-user-check" :loading="store.loading.status" />
        </template> 
    </Modal>

    <!-- <Modal :open="store.modal.status" title="Cambiar estado" icon="fas fa-rotate">
        <template #close>
            <Icon @click="store.resetData()" icon="fas fa-xmark" class="text-white text-2xl cursor-pointer" />
        </template>
        <div class="grid gap-4">
            <Input option="label" title="Beneficiario" v-model="store.beneficiario.fullname" readonly disabled />
            <div>
                <label class="text-sm uppercase text-violet-400">Cursos disponibles</label>
                <Input option="select" title="Seleccione estados" v-model="store.beneficiario.status_id" :error="store.errors.hasOwnProperty('status_id')">
                    <option value=""></option>
                    <option v-for="status in catalogos.statuses" :value="status.id">{{ status.nombre }}</option>
                </Input>
            </div>
        </div>
        <Validate-Errors :errors="store.errors" v-if="store.errors != 0" />
        <template #footer>
            <Button @click="store.resetData()" text="Cancelar" class="btn-secondary" icon="fas fa-xmark" />
            <Button @click="store.changeStatus()" text="Cambiar estado" class="btn-primary" icon="fas fa-rotate" />
        </template> 
    </Modal> -->

</template>

<style scoped>
    li {
        @apply py-1 px-2 hover:font-medium cursor-pointer text-nowrap hover:bg-violet-100 hover:text-violet-500 rounded-lg ;
    }
</style>