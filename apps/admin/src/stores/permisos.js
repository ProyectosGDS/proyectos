import { defineStore } from 'pinia'
import axios from 'axios'
import { ref } from 'vue'
import { useGlobalStore } from './global'

export const usePermisosStore = defineStore('permisos', () => {

    const global = useGlobalStore()

    const headers = [
        { title : 'id', key : 'id_permiso', type : 'numeric' },
        { title : 'permiso', key : 'nombre'},
        { title : 'descripcion', key : 'descripcion'},
        { title : 'app', key : 'app'},
        { title : 'actions', key : 'actions', width : '10px'},
    ]

    const permisos = ref([])
    const permiso = ref({})
    const loading = ref({
        fetch : false,
        store : false,
        update : false,
        destroy : false
    })

    const modal = ref({
        new : false,
        edit : false,
        delete : false,
    })

    const errors = ref([])
    
    async function fetch() {
        loading.value.fetch = true
        try {
            const response = await axios.get('permisos')
            permisos.value = response.data
        } catch (error) {
            if(error.response.data.hasOwnProperty('message') && error.response.data.hasOwnProperty('line')) {
                global.setAlert(error.response.data.message,'danger')
            }else if(error.response.data.hasOwnProperty('errors') && error.response.data.hasOwnProperty('message')){
                errors.value = error.response.data.errors
            }else{
                console.error(error.response.data)
            }
        } finally {
            loading.value.fetch = false
        }
    }

    async function store() {
        loading.value.store = true
        try {
            const response = await axios.post('permisos',permiso.value)
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
        loading.value.store = true
        try {
            const response = await axios.put('permisos/' + permiso.value.id_permiso,permiso.value)
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

    async function destroy() {
        loading.value.destroy = true
        try {
            const response = await axios.delete('permisos/' + permiso.value.id_permiso)
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

    const getGroupedPermisos = () => {
        return permisos.value.reduce((acc, item) => {
            if (!acc[item.app]) {
                acc[item.app] = []
            }
            acc[item.app].push(item)
            return acc
        }, {})
    }

    function edit(item) {
        permiso.value = item
        modal.value.edit = true
    }

    const deleteItem = (item) => {
        permiso.value = item
        modal.value.delete = true
    }

    function resetData() {
        permiso.value = {}
        modal.value = {
            new : false,
            edit : false,
            delete : false,
        }
        errors.value = []
    }


    return {
        headers,
        permisos,
        permiso,
        loading,
        modal,
        errors,

        fetch,
        store,
        update,
        destroy,
        getGroupedPermisos,
        edit,
        deleteItem,
        resetData,
    }
})
