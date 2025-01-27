import axios from 'axios'
import { defineStore } from 'pinia'
import { ref } from 'vue'
import { useGlobalStore } from './global'

export const useProgramacionEventosStore = defineStore('programacion-eventos', () => {
    
    const global = useGlobalStore()

    const headers = [
        { title : 'id', key : 'id_evento' },
        { title : 'titulo', key : 'titulo' },
        { title : 'descripción', key : 'descripcion', class : 'text-xs' },
        { title : 'ubicación', key : 'ubicacion', class : 'text-xs' },
        { title : 'fecha', key : 'fecha', class : 'text-xs' },
        { title : 'hora', key : 'hora', class : 'text-xs' },
        { title : 'responsable', key : 'responsable' },
        { title : 'tipo', key : 'tipo_evento.descripcion' },
        { title : 'direccion', key : 'dependencia.nombre' },
        { title : 'estatus', key : 'estado_evento.descripcion' },
        { title : '', key : 'actions', width : '10px', align : 'center' },
    ]

    const eventos = ref([])
    const evento = ref({})
    const tipos_eventos = ref([])
    const statuses = ref([])
    const loading = ref({
        fetch : false,
        show : false,
        store : false,
        update : false,
        destroy : false,
        change : false,
    })
    const errors = ref([])
    const modal = ref({
        new : false,
        edit : false,
        delete : false,
        status : false,
    })

    async function fetchTiposEventos() {
        loading.value.fetch = true
        try {
            const response = await axios.get('tipos-eventos')
            tipos_eventos.value = response.data
        } catch (error) {
            if(error.response.data.hasOwnProperty('errors')){
                errors.value = error.response.data.errors
            }
        } finally {
            loading.value.fetch = false
        }
    }

    async function fetchStatuses() {
        loading.value.fetch = true
        try {
            const response = await axios.get('estatus-eventos')
            statuses.value = response.data
        } catch (error) {
            if(error.response.data.hasOwnProperty('errors')){
                errors.value = error.response.data.errors
            }
        } finally {
            loading.value.fetch = false
        }
    }

    async function fetch() {
        loading.value.fetch = true
        try {
            const response = await axios.get('eventos')
            eventos.value = response.data
        } catch (error) {
            if(error.response.data.hasOwnProperty('errors')){
                errors.value = error.response.data.errors
            }
        } finally {
            loading.value.fetch = false
        }
    }

    async function store() {
        loading.value.store = true
        try {
            const response = await axios.post('eventos',evento.value)
            fetch()
            global.setAlert(response.data,'success')
            resetData()
        } catch (error) {
            if(error.response.data.hasOwnProperty('errors')){
                errors.value = error.response.data.errors
            }
        } finally {
            loading.value.store = false
        }
    }

    async function update() {
        loading.value.update = true
        try {
            const response = await axios.put('eventos/' + evento.value.id_evento, evento.value)
            fetch()
            global.setAlert(response.data,'success')
            resetData()
        } catch (error) {
            if(error.response.data.hasOwnProperty('errors')){
                errors.value = error.response.data.errors
            }
        } finally {
            loading.value.update = false
        }
    }

    async function changeStatusEvent() {
        loading.value.change = true
        try {
            const response = await axios.put('eventos/cambiar-status-evento/' + evento.value.id_evento, evento.value)
            fetch()
            global.setAlert(response.data,'success')
            resetData()
        } catch (error) {
            if(error.response.data.hasOwnProperty('errors')){
                errors.value = error.response.data.errors
            }
        } finally {
            loading.value.change = false
        }
    }

    async function destroy() {
        loading.value.destroy = true
        try {
            const response = await axios.delete('eventos/'+ evento.value.id)
            fetch()
            global.setAlert(response.data,'success')
            resetData()
        } catch (error) {
            if(error.response.data.hasOwnProperty('errors')){
                errors.value = error.response.data.errors
            }
        } finally {
            loading.value.destroy = false
        }
    }

    function edit (item) {
        evento.value = item
        modal.value.edit = true
    }

    function status(item) {
        evento.value = item
        modal.value.status = true
    }

    function deleteItem (item) {
        evento.value = item
        modal.value.delete = true
    }

    function resetData () {
        evento.value = {}
        errors.value = []
        modal.value = {
            new : false,
            edit : false,
            delete : false,
        }
    }

    return {
        headers,
        eventos,
        evento,
        tipos_eventos,
        statuses,
        loading,
        errors,
        modal,

        fetchTiposEventos,
        fetchStatuses,
        fetch,
        store,
        update,
        changeStatusEvent,
        destroy,
        edit,
        status,
        deleteItem,
        resetData,
    }
})
