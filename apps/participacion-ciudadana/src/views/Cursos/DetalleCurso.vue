<script setup>
    import { useCursosStore } from '@/stores/cursos'
    import { useInscripcionStore } from '@/stores/inscripcion'

    import { computed, onBeforeMount, ref, watchEffect } from 'vue'
    
    import DatosPersonales from './Inscripcion/DatosPersonales.vue'
    import Domicilios from './Inscripcion/Domicilios.vue'
    import DatosMedicos from './Inscripcion/DatosMedicos.vue'
    import DatosAcademicos from './Inscripcion/DatosAcademicos.vue'
    import Responsables from './Inscripcion/Responsables.vue'
    import Emergencia from './Inscripcion/Emergencia.vue'


    const props = defineProps(['curso_id'])

    const store = useCursosStore()
    const inscripcion = useInscripcionStore()

    // const components = {
    //     DatosPersonales,
    //     Responsable,
    //     Otros
    // }

    // const cupo = computed(() => {
    //     return (store.curso.curso.cupo - store.curso.inscritos.length);
    // })


    watchEffect(() => {
        store.show(props.curso_id)
    })

    onBeforeMount(() => {

        // inscripcion.fetchCatalogos()
        // store.fieldFormByDirection()
        
    })
    
</script>

<template>
    <div class="p-2 md:p-4 lg:p-8" v-if="store.curso?.hasOwnProperty('curso')">
        <div class="flex">
            <div @click="store.router.go(-1)" class="flex items-center justify-center gap-2 text-blue-muni cursor-pointer">
                <Icon icon="fas fa-arrow-left" class="text-xl" />
                <span>REGRESAR</span>
            </div>
        </div>
        <br>
        <header class="w-full flex items-center justify-center h-48 bg-blue-muni rounded-lg overflow-hidden relative">
                <!-- <img :src="store.curso.curso.imagen ? store.curso.curso.urlImage : '/public/img/foto-card.jpg'" 
                         :alt="store.curso.curso.nombre" 
                         class=" object-cover w-full object-center absolute blur-sm"> -->
            <h1 class="text-white text-3xl lg:text-7xl uppercase text-center drop-shadow-xl">
                <!-- {{ store.curso?.curso.nombre + ' ' + store.curso.nivel?.nombre }} -->
                {{ store.curso?.curso.nombre }}
            </h1>
        </header>
        <br>
        <div class="grid lg:grid-cols-2 gap-4 text-gray-500">
            <div>
                <div>
                    <h1 class="text-3xl text-blue-muni">Información del curso</h1>
                    <br>
                    <ul class="uppercase">
                        
                        <li class="flex gap-3 items-center">
                            <Icon icon="fas fa-calendar-days" class=" text-[1.3rem]"/>
                            <span class="font-medium">Horario :</span>
                            <span>{{ store.curso?.horario?.full }}</span>
                        </li>
                        <li class="flex gap-3 items-center">
                            <Icon icon="fas fa-users"/>
                            <span class="font-medium">Cupo disponible :</span>
                            <span>{{ store.curso?.capacidad }}</span>
                            <!-- <span>{{ cupo }}</span> -->
                        </li>
                        <li class="flex gap-3 items-center">
                            <Icon icon="fas fa-chalkboard-user"/>
                            <span class="font-medium">Instructor :</span>
                            <span>{{ store.curso?.instructor?.nombre }}</span>
                        </li>
                        <li class="flex gap-3 items-center">
                            <Icon icon="fas fa-layer-group" class="text-lg"/>
                            <span class="font-medium">Modalidad :</span>
                            <span>{{ store.curso?.modalidad == 'P' ? 'PRESENCIAL' : (store.curso?.modalidad == 'V' ? 'VIRTUAL' : 'HIBRIDO')  }}</span>
                        </li>
                        <li class="flex gap-3 items-center">
                            <Icon icon="fas fa-city"/>
                            <span class="font-medium">Sede :</span>
                            <span>{{ `${store.curso?.sede?.direccion} ${store.curso?.sede?.zona?.descripcion} ${store.curso?.sede?.descripcion}` }}</span>
                        </li>
                    </ul>
                </div>
                <br>
                <div>
                    <h1 class="text-3xl text-blue-muni">Requisitos</h1>
                    <br>
                    <ul>
                        <li v-for="requisito in store.curso.curso.requisitos">
                            <label class="flex items-center gap-4">
                                <Icon icon="fas fa-check" />
                                <span class="uppercase text-sm">{{ requisito.nombre }}</span>
                            </label>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="grid grid-rows-2">
                <div>
                    <h3 class="text-3xl text-blue-muni">Descripción</h3>
                    <br>
                    <p>
                        {{ store.curso?.curso?.descripcion }}
                    </p>
                </div>
                <div class="flex justify-center items-center">
                    <Button @click="inscripcion.modal.new = true" icon="fas fa-thumbs-up" text="Inscribete" class="bg-blue-muni btn text-white rounded-full h-16 w-40 text-3xl self-center mx-auto" />
                </div>
            </div>
        </div>
    </div>
    <Modal :open="inscripcion.modal.new" title="Nuevo beneficiario" icon="fas fa-user-plus">
        <template #close>
            <Icon @click="inscripcion.resetData()" icon="fas fa-xmark" class="text-white text-2xl cursor-pointer" />
        </template>
        <div>
            <div class="text-blue-muni grid gap-4">
                <DatosPersonales />
                <Domicilios />
                <DatosMedicos />
                <DatosAcademicos />
                <Responsables v-if="parseInt(inscripcion.beneficiario.edad) < 18" />
                <Emergencia />
            </div>
        </div>
        <Validate-Errors v-if="inscripcion.errors != 0" :errors="inscripcion.errors" />
        <template #footer>
            <Button @click="inscripcion.resetData()" text="Cancelar" class="btn-secondary rounded-full" icon="fas fa-xmark" />
            <Button text="Guardar" class="btn-primary rounded-full" icon="fas fa-save" :loading="store.loading.store" />
        </template>
    </Modal>
</template>
