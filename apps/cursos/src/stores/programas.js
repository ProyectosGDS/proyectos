import { defineStore } from 'pinia'
import axios from 'axios'
import { ref } from 'vue'

import { useGlobalStore } from './global'

export const useProgramasStore = defineStore('programas', () => {

    const global = useGlobalStore()

    const headers = [
        { title : 'id', key : 'id_programa', type : 'numeric' },
        { title : 'programa', key : 'nombre' },
        { title : 'dependencia', key : 'dependencia.nombre' },
        { title : 'estatus', key : 'estatus', width : '10px', align : 'center' },
        { title : 'creación', key : 'fechau', type : 'date' },
        { title : 'acciones', key : 'actions', width : '10px', align : 'center' },
    ]
    const dependencias = ref([])
    const escuelas = ref([])
    const niveles = ref([])
    const niveles_seleccionados = ref([])
    const programas = ref([])
    const programa = ref({
        escuela : {}
    })
    const loading = ref({
        fetch : false,
        fetchDepetendencias : false,
        fetchNiveles : false,
        fetchEscuelas : false,
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
    
    async function fetchDependencias () {
        loading.value.fetchDepetendencias = true
        try {
            const response = await axios.get('dependencias')
            dependencias.value = response.data
        } catch (error) {
            if(error.response.data.hasOwnProperty('message')) {
                global.setAlert(error.response.data.message,'danger')
            }else{
                console.error(error.response.data)
            }
        }finally {
            loading.value.fetchDepetendencias = false
        }
    }

    async function fetchNiveles () {
        loading.value.fetchNiveles = true
        try {
            const response = await axios.get('niveles')
            niveles.value = response.data
        } catch (error) {
            if(error.response.data.hasOwnProperty('message')) {
                global.setAlert(error.response.data.message,'danger')
            }else{
                console.error(error.response.data)
            }
        }finally {
            loading.value.fetchNiveles = false
        }
    }

    async function fetchEscuelas () {
        loading.value.fetchEscuelas = true
        try {
            const response = await axios.get('escuelas')
            escuelas.value = response.data
        } catch (error) {
            if(error.response.data.hasOwnProperty('message')) {
                global.setAlert(error.response.data.message,'danger')
            }else{
                console.error(error.response.data)
            }
        }finally {
            loading.value.fetchEscuelas = false
        }
    }

    async function fetch () {
        loading.value.fetch = true
        try {
            const response = await axios.get('programas')
            programas.value = response.data
        } catch (error) {
            if(error.response.data.hasOwnProperty('message')) {
                global.setAlert(error.response.data.message,'danger')
            }else{
                console.error(error.response.data)
            }
        }finally {
            loading.value.fetch = false
        }
    }

    async function store() {
        loading.value.store = true
        programa.value.niveles = niveles_seleccionados.value
        try {
            const response = await axios.post('programas',programa.value)
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
            loading.value.store = false
        }
    }

    async function update() {
        loading.value.update = true
        programa.value.niveles = niveles_seleccionados.value
        try {
            const response = await axios.put('programas/' + programa.value.id_programa, programa.value)
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
            const response = await axios.delete('programas/' + programa.value.id_programa)
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

    function edit (item) {
        programa.value = item
        niveles_seleccionados.value = item.niveles.map(nivel => nivel.id_nivel)

        if (!item.escuela) {
            programa.value.escuela = {}
        }

        modal.value.edit = true
    }

    function deleteItem (item) {
        programa.value = item
        modal.value.delete = true
    }

    function resetData () {
        programa.value = {
            escuela : {}
        }
        modal.value = {
            new : false,
            edit : false,
            delete : false,
        }
        niveles_seleccionados.value = []
        errors.value = []
    }


    return {
        headers,
        dependencias,
        escuelas,
        niveles,
        niveles_seleccionados,
        programas,
        programa,
        loading,
        modal,
        errors,

        fetchDependencias,
        fetchNiveles,
        fetchEscuelas,
        fetch,
        store,
        update,
        destroy,
        edit,
        deleteItem,
        resetData,
    }
})
