import { defineStore } from 'pinia'
import axios from 'axios'
import { ref } from 'vue'

import { useGlobalStore } from './global'

export const useSedesStore = defineStore('sedes', () => {

    
    const global = useGlobalStore()

    const headers = [
        { title : 'id', key : 'id_sede', type : 'numeric' },
        { title : 'zona', key : 'zona.descripcion' },
        { title : 'descripcion', key : 'descripcion' },
        { title : 'direccion', key : 'direccion' },
        { title : 'distrito', key : 'distrito' },
        { title : 'estatus', key : 'estatus', width : '10px', align : 'center' },
        { title : 'creación', key : 'fechau', type : 'date' },
        { title : 'acciones', key : 'actions', width : '10px', align : 'center' },
    ]
    
    const sedes = ref([])
    const sede = ref({})
    const zonas = ref([])

    const loading = ref({
        fetch : false,
        show : false,
        store : false,
        update : false,
        destroy :false,
        fetchZona : false,
    })

    const modal = ref({
        new : false,
        edit : false,
        delete : false,
    })

    const errors = ref([])

    async function fetchZonas () {
        loading.value.fetchZona = true
        try {
            const response = await axios.get('zonas')
            zonas.value = response.data
        } catch (error) {
            if(error.response.data.hasOwnProperty('message') && error.response.data.hasOwnProperty('line')) {
                global.setAlert(error.response.data.message,'danger')
            }else if(error.response.data.hasOwnProperty('errors') && error.response.data.hasOwnProperty('message')){
                errors.value = error.response.data.errors
            }else{
                console.error(error.response.data)
            }
        }finally {
            loading.value.fetchZona = false
        }
    }

    async function fetch () {
        loading.value.fetch = true
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
            loading.value.fetch = false
        }
    }

    async function show (id_sede) {
        loading.value.show = true
        try {
            const response = await axios.get('sedes/'+id_sede)
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
            loading.value.show = false
        }
    }

    async function store() {
        loading.value.store = true
        try {
            const response = await axios.post('sedes',sede.value)
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
            const response = await axios.put('sedes/' + sede.value.id_sede, sede.value)
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
            const response = await axios.delete('sedes/' + sede.value.id_sede)
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
        sede.value = item
        modal.value.edit = true
    }

    function deleteItem (item) {
        sede.value = item
        modal.value.delete = true
    }

    function resetData () {
        sede.value = {}
        modal.value = {
            new : false,
            edit : false,
            delete : false,
        }

        errors.value = []
    }

    return {
        headers,
        sedes,
        sede,
        zonas,
        loading,
        modal,
        errors,

        fetchZonas,
        fetch,
        store,
        update,
        destroy,
        edit,
        deleteItem,
        resetData,
    }
})
