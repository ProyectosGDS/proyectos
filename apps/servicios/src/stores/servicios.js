
import { defineStore } from 'pinia'
import axios from 'axios'
import { ref } from 'vue'
import { useGlobalStore } from './global'

export const useServiciosStore = defineStore('servicios', () => {

    const global = useGlobalStore()

    const headers = [
        { title : 'id', key : 'id', type : 'numeric' },
        { title : 'servicio', key : 'servicio' },
        { title : 'lugar', key : 'lugar' },
        { title : 'zona', key : 'zona' },
        { title : 'responsable', key : 'responsable' },
        { title : 'programa', key : 'programa' },
        { title : 'fecha', key : 'fecha' },
        { title : '', key : 'actions', align : 'center', width : '10px' },
    ]

    const servicios = ref([])
    const servicio = ref({})
    const persona = ref({})
    const search = ref('')
    const modal = ref({
        new : false,
        edit :false,
        delete : false,
        assign : false
    })
    const errors = ref([])
    const loading = ref({
        fetch : false,
        show :false,
        store : false,
        update : false,
        sync : false,
    })

    async function fetch () {
        loading.value.fetch = true
        try {
            servicios.value = [
                {
                    id : 1,
                    servicio : 'Zumba',
                    lugar : '1 calle 14 avenida parque Jocotenango',
                    zona : 'Zona 2',
                    responsable : 'Victor Martinez',
                    programa : 'Zumba para todos',
                    fecha : '2025-02-25',
                    estatus : 'A',
                    participantes : [
                        {
                            cui : '2733271000101',
                            nombre : 'Nelson Ovidio Vásquez Ventura',
                            fecha_nacimiento : '1988-06-23',
                            edad : 36,
                            estado_civil : 'Unido',
                            sexo : 'M',
                            etnia : 'Maya',
                            direccion : '2 calle 1-02',
                            zona : 3,
                            celular : '48840150',
                        }
                    ]
                }
            ]
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

    function edit (item) {
        servicio.value = item
        modal.value.edit = true
    }

    function assign (item) {
        servicio.value = item
        modal.value.assign = true
    }

    function deleteItem (item) {
        servicio.value = item
        modal.value.delete = true
    }

    function resetData () {
        search.value = ''
        servicio.value = {}
        modal.value = {
            new : false,
            edit : false,
            delete : false,
            assign :false,
        }
        persona.value = {}
        errors.value = []
    }

    function addPerson() {
        servicio.value.participantes.unshift(persona.value)
        persona.value = {}
    }

    function updatePerson() {
        persona.value = {}
    }

    function editPerson(item) {
        persona.value = item
    }

    function removePerson(index) {
        servicio.value.participantes.splice(index ,1)
    }
    
    return {
        headers,
        search,
        servicios,
        servicio,
        persona,
        modal,
        loading,
        errors,

        fetch,
        edit,
        assign,
        addPerson,
        editPerson,
        updatePerson,
        removePerson,
        deleteItem,
        resetData,
    }
})
