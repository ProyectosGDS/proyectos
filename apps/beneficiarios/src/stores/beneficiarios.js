import { defineStore } from 'pinia'
import axios from 'axios'
import { ref } from 'vue'

import { useGlobalStore } from './global'

export const useBeneficiariosStore = defineStore('beneficiarios', () => {


    const global = useGlobalStore()

    const headers = [
        { title : 'id', key : 'id_persona', type : 'numeric' },
        { title : 'dpi', key : 'cui' },
        { title : 'nombre', key : 'nombre_completo' },
        { title : 'edad', key : 'edad', width : '10px', align : 'center', sort : false },
        { title : 'sexo', key : 'sexo', width : '10px', align : 'center' },
        { title : 'celular', key : 'celular' },
        { title : 'correo', key : 'email', class : 'lowercase' },
        { title : 'estatus', key : 'estatus', width : '10px', align : 'center' },
        { title : '', key : 'actions', width : '10px', align : 'center', sort : false },
    ]

    const beneficiarios = ref([])
    const beneficiario = ref({
        sexo : 'M',
        domicilios : {
            grupo_x_zona : {},
            departamento_id : 7,
            municipio_id : 108,
        },
        responsables : {
            sexo : 'M',
        },
        emergencia : {
            sexo : 'M',
        },
        datos_academicos : {
            tipo_establecimiento : 'PU'
        },
        datos_medicos : {},
    })

    const enfermedades = ref([])

    const asignacion = ref({})
    const copyBeneficiario = ref({})

    const loading = ref({
        fetch : false,
        show : false,
        store : false,
        update : false,
        destroy : false,
        searchBeneficiario: false,
        sync : false,
    })

    const reload = ref(false)

    const errors = ref([])
    const messageCui = ref('Ingrese cui')
    const modal = ref({
        new : false,
        edit : false,
        delete : false,
        bitacora : false,
        comentarios :false,
        asignacion : false,
    })

    const cui = ref('')
    const success = ref(false)

    async function fetch() {
        loading.value.fetch = true
        try {
            const response = await axios.get('beneficiarios')
            beneficiarios.value = response.data
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

    async function show(id_persona) {
        loading.value.show = true
        try {
            const response = await axios.get('beneficiarios/'+id_persona)
            return response.data
        } catch (error) {
            if(error.response.data.hasOwnProperty('message') && error.response.data.hasOwnProperty('line')) {
                global.setAlert(error.response.data.message,'danger')
            }else if(error.response.data.hasOwnProperty('errors') && error.response.data.hasOwnProperty('message')){
                errors.value = error.response.data.errors
            }else{
                console.error(error.response.data)
            }
        } finally {
            loading.value.show = false
        }
    }

    async function store() {
        loading.value.store = true
        if(enfermedades.value.length > 0) {
            beneficiario.value.datos_medicos.enfermedades = enfermedades.value
        }
        try {
            const response = await axios.post('beneficiarios',beneficiario.value)
            reload.value = true
            global.setAlert(response.data,'success')
            resetData()
        } catch (error) {
            if(error.response.data.hasOwnProperty('message') && error.response.data.hasOwnProperty('line')) {
                global.setAlert(error.response.data.message,'danger')
            }else if(error.response.data.hasOwnProperty('errors')){
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
        beneficiario.value.datos_medicos.enfermedades = enfermedades.value
        try {
            if (Object.keys(compareObjects(copyBeneficiario.value,beneficiario.value)).length > 0) {
                beneficiario.value.estatus = 'A'
                const response = await axios.put('beneficiarios/' + beneficiario.value.id_persona,beneficiario.value)
                reload.value = true
                global.setAlert(response.data,'success')
            }
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
            const response = await axios.delete('beneficiarios/' + beneficiario.value.id_persona)
            // fetch()
            reload.value = true
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

    async function edit(id_persona) {

        beneficiario.value = await show(id_persona)

        beneficiario.value.domicilios = beneficiario.value.domicilios ?? { grupo_x_zona : {}, departamento_id : 7 }        
        beneficiario.value.domicilios.grupo_x_zona = beneficiario.value.domicilios.grupo_x_zona ?? {}
        beneficiario.value.datos_academicos = beneficiario.value.datos_academicos ?? {}        
        beneficiario.value.datos_medicos = beneficiario.value.datos_medicos ?? {}        
        beneficiario.value.responsables = beneficiario.value.responsables ?? {}        
        beneficiario.value.emergencia = beneficiario.value.emergencia ?? {}

        if(beneficiario.value.datos_medicos.enfermedades_x_persona) {
            enfermedades.value = beneficiario.value.datos_medicos.enfermedades_x_persona.map(enfermedad => enfermedad.id_enfermedad)
        }
        
        copyBeneficiario.value = JSON.parse(JSON.stringify(beneficiario.value))
        
        modal.value.edit = true
        
    }

    const deleteItem = (item) => {
        beneficiario.value = item
        modal.value.delete = true
    }

    async function fetchBeneficiarioUnico (cui) {
        try {
            loading.value.searchBeneficiario = true
            const response = await axios.post('beneficiarios/consulta-back-up',{cui : cui})
            
            if(response.data.hasOwnProperty('cui')){
                messageCui.value = 'Se encontro información en sistema antiguo'
                success.value = true
                updatePropertyDatos(response.data)
            }else {
                messageCui.value = 'No se encontro información en sistema antiguo'
                success.value = true
            }
        } catch (error) {
            messageCui.value = 'No se encontro información en sistema antiguo'
            success.value = true
            console.error(error)
        }finally{
            loading.value.searchBeneficiario = false
        }
    }

    function updatePropertyDatos (newData){
        Object.assign(beneficiario.value,newData)
    }

    function compareObjects(obj1, obj2) {
        const changes = {};
    
        function compareHelper(o1, o2, path = '') {
            const keys = new Set([...Object.keys(o1 || {}), ...Object.keys(o2 || {})]);
            keys.forEach(key => {
                const newPath = path ? `${path}.${key}` : key;
                if (typeof o1[key] === 'object' && typeof o2[key] === 'object' && o1[key] !== null && o2[key] !== null) {
                    compareHelper(o1[key], o2[key], newPath);
                } else if (o1[key] !== o2[key]) {
                    changes[newPath] = { oldValue: o1[key], newValue: o2[key] };
                }
            });
        }
    
        compareHelper(obj1, obj2);
        return changes;
    }

    function resetData() {
        beneficiario.value = {
            sexo : 'M',
            domicilios : {
                grupo_x_zona : {},
                departamento_id : 7,
                municipio_id : 108,
            },
            responsables : {
                sexo : 'M',
            },
            emergencia : {
                sexo : 'M',
            },
            datos_academicos : {
                tipo_establecimiento : 'PU'
            },
            datos_medicos : {},
        }
        modal.value = {
            new : false,
            edit : false,
            delete : false,
            asignacion : false,
        }
        errors.value = []
        messageCui.value = ''
        cui.value = ''
        success.value = false
        copyBeneficiario.value = {}
        asignacion.value = {}
        enfermedades.value = []
    }

    function openSync (item) {
        asignacion.value.id_persona = item.id_persona
        asignacion.value.nombre_completo = item.nombre_completo
        modal.value.asignacion = true
    }

    async function asignarCurso() {
        loading.value.sync = true
        try {
            const response = await axios.post('beneficiarios/asignar-curso/'+asignacion.value.id_persona,asignacion.value)
            fetch()
            global.setAlert(response.data,'success')
            resetData()
        } catch (error) {
            if(error.response.data.hasOwnProperty('message') && error.response.data.hasOwnProperty('line')) {
                global.setAlert(error.response.data.message,'danger')
            }else if(error.response.data.hasOwnProperty('errors')){
                errors.value = error.response.data.errors
            }else{
                console.error(error.response.data)
            }
        } finally {
            loading.value.sync = false
        }
    }

    return {
        headers,
        cui,
        beneficiarios,
        beneficiario,
        asignacion,
        enfermedades,
        loading,
        reload,
        modal,
        errors,
        messageCui,
        success,
        copyBeneficiario,

        fetch,
        store,
        update,
        destroy,
        edit,
        deleteItem,
        fetchBeneficiarioUnico,
        openSync,
        resetData,
        asignarCurso,
    }
})
