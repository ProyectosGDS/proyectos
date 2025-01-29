<script setup>

    import { useAuthStore } from '@/stores/auth'
    import { onMounted, watchEffect } from 'vue'
    import { useClasesStore } from '@/stores/clases'
    import { useProgramasStore } from '@/stores/programas'
    import { useCursosStore } from '@/stores/cursos'
    import SelectSearch from '@/components/SelectSearch.vue'

    const auth = useAuthStore()
    const store = useClasesStore()
    const programas = useProgramasStore()
    const cursos = useCursosStore()


    function refresh() {
        store.fetchInstructores()
        store.fetchSedes()
        store.fetchTemporalidad()
        store.fetchHorarios()
    }

    onMounted(() => {
        store.fetch()
        programas.fetch()
        cursos.fetch()
        store.fetchInstructores()
        store.fetchSedes()
        store.fetchTemporalidad()
        store.fetchHorarios()
    })

    watchEffect(() => {
        let id_programa = store.portafolio.id_programa

        if(id_programa) {
            store.fetchPrograma(id_programa)
        } else {
            store.niveles = []
        }

        
    })

</script>

<template>
    <Card class="bg-white pt-8 pb-2 px-2 lg:px-8">
        <h1 class="text-2xl text-gray-500 font-semibold">Menú social</h1>
        <div v-if="auth.checkPermission('crear clase')" class="flex justify-center">
            <Tool-Tip message="Crear nuevo clase" class="-mt-8 text-color-4">
                <Button @click="store.modal.new = true" icon="fas fa-plus" class="btn-primary" />
            </Tool-Tip>
        </div>
        <Data-Table v-if="auth.checkPermission('ver clases')" :headers="store.headers" :data="store.clases" :loading="store.loading.fetch" :export="auth.checkPermission('exportar clases')">
            <template #estatus="{item}">
                <Icon :icon="item.estatus === 'I' ? 'fas fa-xmark' : 'fas fa-check'" :class="item.estatus === 'I' ? 'text-red-500' : 'text-green-500'"/>
            </template>
            <template #modalidad="{item}">
                <span class="text-xs">
                    {{ item.modalidad == 'P' ? 'PRESENCIAL' : ( item.modalidad == 'V' ? 'VIRTUAL' : 'HIBRIDA') }}
                </span>
            </template>
            <template #actions="{item}">
                <div class="relative">
                    <Drop-Down-Button icon="fas fa-ellipsis-vertical">
                        <ul class="grid gap-3 text-violet-500">
                            <li v-if="auth.checkPermission('editar curso')" @click="store.edit(item)" class="hover:font-medium cursor-pointer">
                                Editar
                            </li>
                            <hr>
                            <template v-if="auth.checkPermission('eliminar curso')">
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
    <Modal :open="store.modal.new" icon="fas fa-book" title="Crear nueva clase" class="w-[80%]">
        <template #close>
            <Icon @click="store.resetData" icon="fas fa-xmark" class="text-violet-200 text-xl hover:scale-125 cursor-pointer" />
        </template>
        <div class="grid gap-3">
            <Input v-model="store.portafolio.id_programa" option="select" title="Programa" :error="store.errors.hasOwnProperty('id_programa')">
                <option value=""> --seleccione -- </option>
                <template v-for="programa in programas.programas">
                    <option v-if="programa.estatus == 'A'" :value="programa.id_programa">{{ programa.nombre }}</option>
                </template>
            </Input>
            <Input v-model="store.portafolio.id_nivel" option="select" title="Niveles del programa" :error="store.errors.hasOwnProperty('id_nivel')">
                <option value=""> --seleccione -- </option>
                <template v-for="nivel in store.niveles">
                    <option v-if="nivel.estatus == 'A'" :value="nivel.id_nivel">{{ nivel.descripcion }}</option>
                </template>
            </Input>

            <div class="flex items-center gap-3">
                <SelectSearch :items="cursos.cursos" v-model="store.portafolio.id_curso" :fields="['id_curso','nombre']" title="Seleccione curso" :error="store.errors.hasOwnProperty('id_curso')" />
                <Icon @click="cursos.modal.new = true" icon="fas fa-plus" class="icon-button bg-color-4" />
            </div>
            <div class="flex gap-3 items-center border p-4 rounded-lg" :class="{'border-red-400' : store.errors.hasOwnProperty('clases')}">
                <div>
                    <label class="text-color-4 text-xs uppercase">Instructor</label>
                    <select class="text-xs" v-model="store.clase.instructor">
                        <option :value="{}"> -- seleccione -- </option>
                        <template v-for="instructor in store.instructores">
                            <option v-if="instructor.estatus == 'A'" :value="instructor">{{ instructor.nombre }}</option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="text-color-4 text-xs uppercase">Sede</label>   
                    <select class="text-xs" v-model="store.clase.sede">
                        <option :value="{}"> -- seleccione -- </option>
                        <template v-for="sede in store.sedes">
                            <option v-if="sede.estatus == 'A'" :value="sede">{{ sede.descripcion }}</option>
                        </template>
                    </select>    
                </div>
                <div>
                    <label class="text-color-4 text-xs uppercase">Temporalidad</label>
                    <select class="text-xs" v-model="store.clase.temporalidad">
                        <option :value="{}"> -- seleccione -- </option>
                        <option v-for="tempo in store.temporalidad" :value="tempo">{{ tempo.descripcion }}</option>
                    </select>
                </div>
                <div>
                    <label class="text-color-4 text-xs uppercase">Sección</label>
                    <input v-model="store.clase.seccion" type="text" placeholder="Sección" maxlength="20">
                </div>
                <div>
                    <label class="text-color-4 text-xs uppercase">Capacidad</label>
                    <input v-model="store.clase.capacidad" type="number" placeholder="Capacidad"  min="0">
                </div>
                <div>
                    <label class="text-color-4 text-xs uppercase">Modalidad</label>
                    <select class="text-xs" v-model="store.clase.modalidad">
                        <option value=""> -- seleccione -- </option>
                        <option value="P">PRESENCIAL</option>
                        <option value="V">VIRTUAL</option>
                        <option value="H">HIBRIDO</option>
                    </select>
                </div>
                <div>
                    <label class="text-color-4 text-xs uppercase">Horario</label>
                    <select class="text-xs" v-model="store.clase.horario">
                        <option :value="{}"> -- seleccione -- </option>
                        <template v-for="horario in store.horarios">
                            <option v-if="horario.estatus == 'A'" :value="horario">{{ horario.full }}</option>
                        </template>
                    </select>
                </div>
                <div class="flex justify-center">
                    <div class="grid justify-items-center gap-4">
                        <Icon @click="store.addClase()" icon="fas fa-plus" class="icon-button bg-color-4" />
                        <Icon @click="refresh()" icon="fas fa-rotate" class="text-green-500 cursor-pointer" />
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="grid gap-2">
            <div class="flex items-center gap-3" v-for="(clase,index) in store.portafolio.clases">
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
                            <span class="uppercase font-medium">capacidad: </span>
                            <span>{{ clase.capacidad }}</span>
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
                <Icon @click="store.removeClase(index)" icon="fas fa-xmark" class="icon-button bg-red-400" />
            </div>
        </div>

        <Validate-Errors :errors="store.errors" v-if="store.errors != 0" />
        <Modal :open="cursos.modal.new" title="crear nuevo curso" icon="fas fa-book">
            <div class="grid gap-3">
                <Input v-model="cursos.curso.nombre" option="label" title="Nombre del curso" :error="cursos.errors.hasOwnProperty('nombre')" />
                <Input v-model="cursos.curso.descripcion" option="text-area" rows="7" maxlength="800" title="Descripcion del curso" :error="cursos.errors.hasOwnProperty('descripcion')" />
            </div>
            <Validate-Errors :errors="cursos.errors" v-if="cursos.errors != 0" />
            <template #footer>
                <Button @click="cursos.resetData" text="Cancelar" icon="fas fa-xmark" class="btn-secondary" />
                <Button @click="cursos.store" text="Crear" icon="fas fa-plus" class="btn-primary" :loading="cursos.loading.store" />
            </template>
        </Modal>
        <template #footer>
            <Button @click="store.resetData" text="Cancelar" icon="fas fa-xmark" class="btn-secondary" />
            <Button @click="store.store" text="Crear" icon="fas fa-plus" class="btn-primary" :loading="store.loading.store" />
        </template>
    </Modal>

    <Modal :open="store.modal.edit" title="Editar clase" icon="fas fa-book" class="w-[80%]">
        <template #close>
            <Icon @click="store.resetData" icon="fas fa-xmark" class="text-violet-200 text-xl hover:scale-125 cursor-pointer" />
        </template>
        
        <div class="grid gap-3">
            <div v-if="store.portafolio.estatus === 'I'" class="flex justify-end">
                <div class="flex gap-3 text-gray-500">
                    <span>Activo</span>
                    <Switch :values="['A','I']" v-model="store.portafolio.estatus" class="bg-gray-400 w-12 has-[:checked]:bg-color-4"/>
                    <span>Inactivo</span>
                </div>
            </div>

            <Input v-model="store.portafolio.id_programa" option="select" title="Programa" :error="store.errors.hasOwnProperty('id_programa')">
                <option value=""> --seleccione -- </option>
                <template v-for="programa in programas.programas">
                    <option v-if="programa.estatus == 'A'" :value="programa.id_programa">{{ programa.nombre }}</option>
                </template>
            </Input>
            <Input v-model="store.portafolio.id_nivel" option="select" title="Niveles del programa" :error="store.errors.hasOwnProperty('id_nivel')">
                <option value=""> --seleccione -- </option>
                <template v-for="nivel in store.niveles">
                    <option v-if="nivel.estatus == 'A'" :value="nivel.id_nivel">{{ nivel.descripcion }}</option>
                </template>
            </Input>
            <Input v-model="store.portafolio.id_curso" option="select" title="Cursos" :error="store.errors.hasOwnProperty('id_curso')">
                <option value=""> --seleccione -- </option>
                <template v-for="curso in cursos.cursos">
                    <option v-if="curso.estatus == 'A'" :value="curso.id_curso">{{ curso.nombre }}</option>
                </template>
            </Input>

            <div class="flex gap-3 items-center border p-4 rounded-lg" :class="{'border-red-400' : store.errors.hasOwnProperty('clases')}">
                <div>
                    <label class="text-color-4 text-xs uppercase">Instructor</label>
                    <select class="text-xs" v-model="store.clase.instructor">
                        <option :value="{}"> -- seleccione -- </option>
                        <template v-for="instructor in store.instructores">
                            <option v-if="instructor.estatus == 'A'" :value="instructor">{{ instructor.nombre }}</option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="text-color-4 text-xs uppercase">Sede</label>   
                    <select class="text-xs" v-model="store.clase.sede">
                        <option :value="{}"> -- seleccione -- </option>
                        <template v-for="sede in store.sedes">
                            <option v-if="sede.estatus == 'A'" :value="sede">{{ sede.descripcion }}</option>
                        </template>
                    </select>    
                </div>
                <div>
                    <label class="text-color-4 text-xs uppercase">Temporalidad</label>
                    <select class="text-xs" v-model="store.clase.temporalidad">
                        <option :value="{}"> -- seleccione -- </option>
                        <option v-for="tempo in store.temporalidad" :value="tempo">{{ tempo.descripcion }}</option>
                    </select>
                </div>
                <div>
                    <label class="text-color-4 text-xs uppercase">Sección</label>
                    <input v-model="store.clase.seccion" type="text" placeholder="Sección" maxlength="20">
                </div>
                <div>
                    <label class="text-color-4 text-xs uppercase">Capacidad</label>
                    <input v-model="store.clase.capacidad" type="number" placeholder="Capacidad"  min="0">
                </div>
                <div>
                    <label class="text-color-4 text-xs uppercase">Modalidad</label>
                    <select class="text-xs" v-model="store.clase.modalidad">
                        <option value=""> -- seleccione -- </option>
                        <option value="P">PRESENCIAL</option>
                        <option value="V">VIRTUAL</option>
                        <option value="H">HIBRIDO</option>
                    </select>
                </div>
                <div>
                    <label class="text-color-4 text-xs uppercase">Horario</label>
                    <select class="text-xs" v-model="store.clase.horario">
                        <option :value="{}"> -- seleccione -- </option>
                        <template v-for="horario in store.horarios">
                            <option v-if="horario.estatus == 'A'" :value="horario">{{ horario.full }}</option>
                        </template>
                    </select>
                </div>
                <div class="flex justify-center">
                    <div class="grid justify-items-center gap-4">
                        <Icon @click="store.addClase()" icon="fas fa-plus" class="icon-button bg-color-4" />
                        <Icon @click="store.saveClase()" icon="fas fa-save" class="icon-button bg-color-4" />
                        <Icon @click="refresh()" icon="fas fa-rotate" class="text-green-500 cursor-pointer" />
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="grid gap-2">
            <div class="flex items-center gap-3" v-for="clase in store.portafolio.clases">
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
                            <span class="uppercase font-medium">capacidad: </span>
                            <span>{{ clase.capacidad }}</span>
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
                <div>
                    <Icon @click="store.editClase(clase)" icon="fas fa-pencil" class="icon-button bg-color-4" />
                    <Icon @click="store.removeClase(index)" icon="fas fa-xmark" class="icon-button bg-red-400" />
                </div>
            </div>
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
                <h1>¿Esta seguro de eliminar el registro?</h1>
                <h2 class="text-center font-semibold text-xl">{{ store.portafolio.curso }}</h2>
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