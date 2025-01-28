import { defineStore } from 'pinia'
import axios from 'axios'
import { ref } from 'vue'

import { useGlobalStore } from './global'
import Cursos from '@/views/Cursos.vue'

export const useClasesStore = defineStore('clases', () => {

    const global = useGlobalStore()

    const headers = [
        { title : 'id', key : 'id_clase', type : 'numeric' },
        { title : 'programa', key : 'programa' },
        { title : 'nivel', key : 'nivel' },
        { title : 'curso', key : 'curso' },
        { title : 'seccion', key : 'seccion', width : '10px', align : 'center' },
        { title : 'instructor', key : 'instructor' },
        { title : 'sede', key : 'sede' },
        { title : 'horario', key : 'full' },
        { title : 'temporalidad', key : 'temporalidad' },
        { title : 'modalidad', key : 'modalidad', width : '10px', align : 'center' },
        { title : 'capacidad', key : 'capacidad', width : '10px', align : 'center' },
        { title : 'estatus', key : 'estatus', width : '10px', align : 'center' },
        { title : 'creación', key : 'fechau', type : 'date' },
        { title : '', key : 'actions', width : '10px', align : 'center' },
    ]

    const instructores = ref([])
    const sedes = ref([])
    const temporalidad = ref([])
    const horarios = ref([])
    const niveles = ref([])
    
    const clases = ref([])

    const portafolio = ref({
        clases : []
    })
    
    const clase = ref({
        instructor : {},
        sede : {},
        temporalidad : {},
        seccion : '',
        capacidad : '',
        modalidad : '',
        horario : {},
    })

    const loading = ref({
        fetch : false,
        fetchHorarios : false,
        fetchInstructores : false,
        fetchSedes : false,
        fetchTemporalidad : false,
        fetchPrograma : false,
        show : false,
        store : false,
        update : false,
        destroy :false,
    })

    const modal = ref({
        new : false,
        edit : false,
        delete : false,
    })

    const errors = ref([])
    
    async function fetchInstructores () {

        loading.value.fetchInstructores = true
        try {
            const response = await axios.get('instructores')
            instructores.value = response.data
        } catch (error) {
            if(error.response.data.hasOwnProperty('message') && error.response.data.hasOwnProperty('line')) {
                global.setAlert(error.response.data.message,'danger')
            }else if(error.response.data.hasOwnProperty('errors') && error.response.data.hasOwnProperty('message')){
                errors.value = error.response.data.errors
            }else{
                console.error(error.response.data)
            } 
        }finally {
            loading.value.fetchInstructores = false
        }
    }

    async function fetchHorarios () {
        loading.value.fetchHorarios = true
        try {
            const response = await axios.get('horarios')
            horarios.value = response.data
        } catch (error) {
            if(error.response.data.hasOwnProperty('message') && error.response.data.hasOwnProperty('line')) {
                global.setAlert(error.response.data.message,'danger')
            }else if(error.response.data.hasOwnProperty('errors') && error.response.data.hasOwnProperty('message')){
                errors.value = error.response.data.errors
            }else{
                console.error(error.response.data)
            }
        }finally {
            loading.value.fetchHorarios = false
        }
    }

    async function fetchSedes () {
        loading.value.fetchSedes = true
        try {
            const response = await axios.get('sedes')
            sedes.value = response.data
        } catch (error) {
            if(error.response.data.hasOwnProperty('message') && error.response.data.hasOwnProperty('line')) {
                global.setAlert(error.response.data.message,'danger')
            }else if(error.response.data.hasOwnProperty('errors') && error.response.data.hasOwnProperty('message')){
                errors.value = error.response.data.errors
            }else{
                console.error(error.response.data)
            }
        }finally {
            loading.value.fetchSedes = false
        }
    }

    async function fetchTemporalidad () {
        loading.value.fetchTemporalidad = true
        try {
            const response = await axios.get('temporalidad')
            temporalidad.value = response.data
        } catch (error) {
            if(error.response.data.hasOwnProperty('message') && error.response.data.hasOwnProperty('line')) {
                global.setAlert(error.response.data.message,'danger')
            }else if(error.response.data.hasOwnProperty('errors') && error.response.data.hasOwnProperty('message')){
                errors.value = error.response.data.errors
            }else{
                console.error(error.response.data)
            }
        }finally {
            loading.value.fetchTemporalidad = false
        }
    }

    async function fetchPrograma (id_programa) {
        loading.value.fetchPrograma = true
        try {
            const response = await axios.get('programas/' + id_programa)
            niveles.value = response.data.levels
        } catch (error) {
            if(error.response.data.hasOwnProperty('message') && error.response.data.hasOwnProperty('line')) {
                global.setAlert(error.response.data.message,'danger')
            }else if(error.response.data.hasOwnProperty('errors') && error.response.data.hasOwnProperty('message')){
                errors.value = error.response.data.errors
            }else{
                console.error(error.response.data)
            }
        }finally {
            loading.value.fetchPrograma = false
        }
    }

    async function fetch () {
        loading.value.fetch = true
        try {
            const response = await axios.get('clases')
            clases.value = response.data
        } catch (error) {
            if(error.response.data.hasOwnProperty('message') && error.response.data.hasOwnProperty('line')) {
                global.setAlert(error.response.data.message,'danger')
            }else if(error.response.data.hasOwnProperty('errors') && error.response.data.hasOwnProperty('message')){
                errors.value = error.response.data.errors
            }else{
                console.error(error.response.data)
            }
        }finally {
            loading.value.fetch = false
        }
    }

    async function show (id_clase) {
        loading.value.show = true
        try {
            const response = await axios.get('clases/'+id_clase)
            clases.value = response.data
        } catch (error) {
            if(error.response.data.hasOwnProperty('message') && error.response.data.hasOwnProperty('line')) {
                global.setAlert(error.response.data.message,'danger')
            }else if(error.response.data.hasOwnProperty('errors') && error.response.data.hasOwnProperty('message')){
                errors.value = error.response.data.errors
            }else{
                console.error(error.response.data)
            }
        }finally {
            loading.value.show = false
        }
    }

    async function store() {
        loading.value.store = true
        try {
            const response = await axios.post('clases',portafolio.value)
            fetch()
            global.setAlert(response.data,'success')
            resetData()
        } catch (error) {
            if(error.response.data.hasOwnProperty('message') && error.response.data.hasOwnProperty('line')) {
                global.setAlert(error.response.data.message,'danger')
            }else if(error.response.data.hasOwnProperty('errors') && error.response.data.hasOwnProperty('message')){
                errors.value = error.response.data.errors
            }else {
                global.setAlert(error.response.data,'danger')
            }
        } finally {
            loading.value.store = false
        }
    }

    async function update() {
        loading.value.update = true
        try {
            const response = await axios.put('clases/' + 1, portafolio.value)
            fetch()
            global.setAlert(response.data,'success')
            resetData()
        } catch (error) {
            if(error.response.data.hasOwnProperty('message') && error.response.data.hasOwnProperty('line')) {
                global.setAlert(error.response.data.message,'danger')
            }else if(error.response.data.hasOwnProperty('errors') && error.response.data.hasOwnProperty('message')){
                errors.value = error.response.data.errors
            }else{
                console.error(error.response.data)
            }
        } finally {
            loading.value.update = false
        }
    }

    async function destroy() {
        loading.value.destroy = true
        try {
            const response = await axios.delete('clases/' + clase.value.id_clase)
            fetch()
            global.setAlert(response.data,'success')
            resetData()
        } catch (error) {
            if(error.response.data.hasOwnProperty('message') && error.response.data.hasOwnProperty('line')) {
                global.setAlert(error.response.data.message,'danger')
            }else if(error.response.data.hasOwnProperty('errors') && error.response.data.hasOwnProperty('message')){
                errors.value = error.response.data.errors
            }else{
                console.error(error.response.data)
            }
        } finally {
            loading.value.destroy = false
        }
    }

    async function edit (item) {
        loading.value.show = true
        try {
            const response = await axios.get('clases/'+item.id_clase)
            portafolio.value.id_programa = response.data.id_programa
            portafolio.value.id_nivel = response.data.id_nivel
            portafolio.value.id_curso = response.data.id_curso
            portafolio.value.estatus = response.data.estatus
            portafolio.value.clases.push(response.data)
            modal.value.edit = true
        } catch (error) {
            if(error.response.data.hasOwnProperty('message') && error.response.data.hasOwnProperty('line')) {
                global.setAlert(error.response.data.message,'danger')
            }else if(error.response.data.hasOwnProperty('errors') && error.response.data.hasOwnProperty('message')){
                errors.value = error.response.data.errors
            }else{
                console.error(error.response.data)
            }
        }finally {
            loading.value.show = false
        }
    }

    function deleteItem (item) {
        portafolio.value = item
        modal.value.delete = true
    }

    function addClase () {

        if(clase.value.instructor.id_instructor &&
            clase.value.sede.id_sede &&
            clase.value.temporalidad.id_temporalidad &&
            clase.value.horario.id_horario &&
            clase.value.modalidad &&
            clase.value.seccion &&
            clase.value.capacidad
         ) {

             let objeto = clase.value
             portafolio.value.clases.unshift(objeto)
             clase.value = {
                 instructor : {},
                 sede : {},
                 temporalidad : {},
                 seccion : '',
                 capacidad : '',
                 modalidad : '',
                 horario : {},
             }
         } else {
            errors.value = {
                'clases' : ["No hay elementos seleccionados para asignar"]   
            }
         }
    }

    function editClase (item) {
        clase.value = item
    }

    function saveClase () {
        clase.value = {
            instructor : {},
            sede : {},
            temporalidad : {},
            seccion : '',
            capacidad : '',
            modalidad : '',
            horario : {},
        }
    }

    function removeClase (index) {
        portafolio.value.clases.splice(index ,1)
    }

    function resetData () {
        clase.value = {
            instructor : {},
            sede : {},
            temporalidad : {},
            seccion : '',
            capacidad : '',
            modalidad : '',
            horario : {},
        }
        portafolio.value = {
            clases : []
        }
        niveles.value = []
        modal.value = {
            new : false,
            edit : false,
            delete : false,
        }

        errors.value = []
    }

    return {
        headers,
        instructores,
        sedes,
        temporalidad,
        horarios,
        niveles,

        clase,
        clases,
        portafolio,
        loading,
        modal,
        errors,

        fetchInstructores,
        fetchSedes,
        fetchTemporalidad,
        fetchHorarios,
        fetchPrograma,

        fetch,
        store,
        update,
        destroy,
        edit,
        deleteItem,

        addClase,
        editClase,
        saveClase,
        removeClase,
        
        resetData,
    }
})
