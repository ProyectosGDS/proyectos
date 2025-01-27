import { defineStore } from 'pinia'
import axios from 'axios'
import { ref } from 'vue'
import { useGlobalStore } from './global'

export const useRolesStore = defineStore('roles', () => {
    
    const global = useGlobalStore()

    const headers = [
        { title : 'id', key : 'id_rol', type : 'numeric' },
        { title : 'role', key : 'nombre'},
        { title : 'descripcion', key : 'descripcion'},
        { title : 'actions', key : 'actions', width : '10px'},
    ]

    const roles = ref([])
    const permisos = ref([])
    const role = ref({})
    const loading = ref({
        fetch : false,
        store : false,
        update : false,
        destroy : false,
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
            const response = await axios.get('roles')
            roles.value = response.data
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
        role.value.permisos = permisos.value
        try {
            const response = await axios.post('roles',role.value)
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
        role.value.permisos = permisos.value
        try {
            const response = await axios.put('roles/' + role.value.id_rol,role.value)
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
            const response = await axios.delete('roles/' + role.value.id_rol)
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

    function edit(item) {
        role.value = item
        permisos.value = role.value.permisos.map(permiso => permiso.id_permiso)
        modal.value.edit = true
    }

    const deleteItem = (item) => {
        role.value = item
        modal.value.delete = true
    }

    function resetData() {
        role.value = {}
        permisos.value = []
        modal.value = {
            new : false,
            edit : false,
            delete : false,
        }
        errors.value = []
    }

    return {
        headers,
        roles,
        permisos,
        role,
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
