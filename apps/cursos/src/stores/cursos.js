import { defineStore } from 'pinia'
import axios from 'axios'
import { ref } from 'vue'

import { useGlobalStore } from './global'

export const useCursosStore = defineStore('cursos', () => {

    
    const global = useGlobalStore()

    const headers = [
        { title : 'id', key : 'id_curso', type : 'numeric' },
        { title : 'curso', key : 'nombre' },
        { title : 'estatus', key : 'estatus', width : '10px', align : 'center' },
        { title : 'creación', key : 'fechau', type : 'date' },
        { title : 'acciones', key : 'actions', width : '10px', align : 'center' },
    ]
    
    const cursos = ref([])
    const curso = ref({})
    const requisitos = ref([])

    const loading = ref({
        fetch : false,
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

    async function fetch () {
        loading.value.fetch = true
        try {
            const response = await axios.get('cursos')
            cursos.value = response.data
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

    async function store() {
        loading.value.store = true
        curso.value.requisitos = requisitos.value
        try {
            const response = await axios.post('cursos',curso.value)
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
        curso.value.requisitos = requisitos.value
        try {
            const response = await axios.put('cursos/' + curso.value.id_curso, curso.value)
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
            const response = await axios.delete('cursos/' + curso.value.id_curso)
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
        curso.value = item
        requisitos.value = curso.value.requisitos.map(requisito => requisito.id_requisito)
        modal.value.edit = true
    }

    function deleteItem (item) {
        curso.value = item
        modal.value.delete = true
    }

    function resetData () {
        curso.value = {}
        modal.value = {
            new : false,
            edit : false,
            delete : false,
        }

        errors.value = []
        requisitos.value = []
    }

    return {
        headers,
        cursos,
        curso,
        requisitos,
        loading,
        modal,
        errors,

        fetch,
        store,
        update,
        destroy,
        edit,
        deleteItem,
        
        resetData,
    }
})
