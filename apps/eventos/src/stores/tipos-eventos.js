import axios from 'axios'
import { defineStore } from 'pinia'
import { ref } from 'vue'
import { useGlobalStore } from './global'

export const useTiposEventosStore = defineStore('tipos-eventos', () => {
    
    const global = useGlobalStore()
    
    const headers = [
        { title : 'id', key : 'id' },
        { title : 'nombre', key : 'nombre' },
        { title : 'status', key : 'deleted_at' },
        { title : '', key : 'actions' },
    ]

    const tipos_eventos = ref([])
    const tipo_evento = ref({})
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
            const response = await axios.get('tipo-eventos')
            tipos_eventos.value = response.data
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
            const response = await axios.post('tipo-eventos',tipo_evento.value)
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
            const response = await axios.put('tipo-eventos/' + tipo_evento.value.id, tipo_evento.value)
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

    async function destroy() {
        loading.value.destroy = true
        try {
            const response = await axios.delete('tipo-eventos/'+ tipo_evento.value.id)
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
        tipo_evento.value = item
        modal.value.edit = true
    }

    function deleteItem (item) {
        tipo_evento.value = item
        modal.value.delete = true
    }

    function resetData () {
        tipo_evento.value = {}
        errors.value = []
        modal.value = {
            new : false,
            edit : false,
            delete : false,
        }
    }

    return {

        headers,
        tipos_eventos,
        tipo_evento,
        loading,
        errors,
        modal,

        fetch,
        store,
        update,
        destroy,
        edit,
        deleteItem,
        resetData,
        
    }
})
