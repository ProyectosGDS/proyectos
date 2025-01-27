import axios from 'axios'
import { defineStore } from 'pinia'
import { ref } from 'vue'

import { useGlobalStore } from './global'


export const useBitacoraStore = defineStore('bitacora', () => {
    
    const global = useGlobalStore()

    const headersAsignaciones = [
        { title : 'id_clase', key: 'id_clase', type : 'numeric' },
        { title : 'curso', key: 'clase.curso.nombre' },
        { title : 'sede', key: 'clase.sede.descripcion' },
        { title : 'creado por', key: 'creado_por.nombre' },
        { title : 'fecha creacion', key: 'fechau', type : 'date' },
    ]

    const headersHistorial = [
        { title : 'accion', key : 'accion' },
        { title : 'descripcion', key : 'descripcion' },
        { title : 'usuario', key : 'creado_por.nombre' },
        { title : 'fecha', key : 'fechau', type : 'date' },
    ]


    const asignaciones = ref([])
    const observaciones = ref([])
    const acciones = ref([])
    const historial = ref({})

    const loading = ref({
        inscripciones : false,
        bitacora : false,
        store : false,
    })

    const errors = ref([])
    const modal = ref({
        bitacora : false,
        observacion : false,
    })


    async function fetchHistoriales(id_persona) {
        loading.value.bitacora = true
        try {
            const response = await axios.get('beneficiarios/bitacoras/'+id_persona)
            observaciones.value = response.data.observaciones
            acciones.value = response.data.acciones
            asignaciones.value = response.data.asignaciones
        } catch (error) {
            if(error.response.data.hasOwnProperty('message') && error.response.data.hasOwnProperty('line')) {
                global.setAlert(error.response.data.message,'danger')
            }else if(error.response.data.hasOwnProperty('errors') && error.response.data.hasOwnProperty('message')){
                errors.value = error.response.data.errors
            }else{
                console.error(error.response.data)
            }
        } finally {
            loading.value.bitacora = false
        }
    }

    async function fetchBitacoras(id_persona) {
        modal.value.bitacora = true
        try {
            fetchHistoriales(id_persona)
        } catch (error) {
            console.error(error)
        }
    }

    async function store() {
        loading.value.store = true
        try {
            const response = await axios.post('historial',historial.value)
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

    function observacion(item) {
        const data = JSON.parse(JSON.stringify(item))
        historial.value = data
        historial.value.accion = 'OBSERVACION BENEFICIARIO'
        modal.value.observacion = true
    }

    function resetData() {

        modal.value = {
            bitacora : false,
        }

        inscripciones.value = []
        observaciones.value = []
        acciones.value = []
        historial.value = {}
        errors.value = []
    }

    return {
        headersAsignaciones,
        headersHistorial,
        historial,
        observaciones,
        acciones,
        asignaciones,
        loading,
        errors,
        modal,

        fetchBitacoras,
        store,
        observacion,
        resetData,
    }
})
