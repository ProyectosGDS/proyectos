import { defineStore } from 'pinia'
import axios from 'axios'
import { ref } from 'vue'

import { useGlobalStore } from './global'

export const useVerificacionStore = defineStore('verificacion', () => {

    
    const global = useGlobalStore()

    const headers = [
        { title : 'id inscripcion', key : 'id_correlativo', type : 'numeric' },
        { title : 'beneficiario', key : 'nombre_completo' },
        { title : 'programa', key : 'programa' },
        { title : 'nivel', key : 'nivel' },
        { title : 'curso', key : 'curso' },
        { title : 'sede', key : 'sede' },
        { title : 'estado', key : 'estatus' },
        { title : '', key : 'actions', width : '10px', align : 'center' },
    ]
    
    const verificaciones = ref([])
    const verificacion = ref({})

    const paramsFetch = ref({
        id_programa : null,
        id_nivel : null,
        id_curso : null,
    })


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
            const response = await axios.get('verificaciones',{
                params : {
                    id_programa : paramsFetch.value.id_programa,
                    id_nivel : paramsFetch.value.id_nivel,
                    id_curso : paramsFetch.value.id_curso
                }
            })
            verificaciones.value = response.data
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

    async function show (id_verificacion) {
        loading.value.show = true
        try {
            const response = await axios.get('verificacion/'+id_verificacion)
            verificacion.value = response.data
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
            const response = await axios.post('verificacion',verificacion.value)
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
            const response = await axios.put('verificacion/' + verificacion.value.id_verificacion, verificacion.value)
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
            const response = await axios.delete('verificacion/' + verificacion.value.id_verificacion)
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
        verificacion.value = item
        modal.value.edit = true
    }

    function deleteItem (item) {
        verificacion.value = item
        modal.value.delete = true
    }

    function resetData () {
        verificacion.value = {}
        modal.value = {
            new : false,
            edit : false,
            delete : false,
        }

        errors.value = []
    }

    return {
        headers,
        verificaciones,
        verificacion,
        paramsFetch,
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
