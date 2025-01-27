import { defineStore } from 'pinia'
import axios from 'axios'
import { ref } from 'vue'

import { useGlobalStore } from './global'

export const useRequisitosStore = defineStore('requisitos', () => {

    
    const global = useGlobalStore()

    const headers = [
        { title : 'id', key : 'id_requisito', type : 'numeric' },
        { title : 'requisito', key : 'nombre' },
        { title : 'estatus', key : 'estatus', width : '10px', align : 'center' },
        { title : 'creación', key : 'fechau', type : 'date' },
        { title : 'acciones', key : 'actions', width : '10px', align : 'center' },
    ]
    
    const requisitos = ref([])
    const requisito = ref({})

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
            const response = await axios.get('requisitos')
            requisitos.value = response.data
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

    async function show (id_requisito) {
        loading.value.show = true
        try {
            const response = await axios.get('requisitos/'+id_requisito)
            requisitos.value = response.data
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
            const response = await axios.post('requisitos',requisito.value)
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
        try {
            const response = await axios.put('requisitos/' + requisito.value.id_requisito, requisito.value)
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
            const response = await axios.delete('requisitos/' + requisito.value.id_requisito)
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
        requisito.value = item
        modal.value.edit = true
    }

    function deleteItem (item) {
        requisito.value = item
        modal.value.delete = true
    }

    function resetData () {
        requisito.value = {}
        modal.value = {
            new : false,
            edit : false,
            delete : false,
        }

        errors.value = []
    }

    return {
        headers,
        requisitos,
        requisito,
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
