import { defineStore } from 'pinia'
import { ref } from 'vue'
import { useGlobalStore } from './global'
import { useBeneficiariosStore } from './beneficiarios'
import axios from 'axios'

export const useCatalogosStore = defineStore('catalogos', () => {
    
    const global = useGlobalStore()
    const store = useBeneficiariosStore()

    const catalogos = ref([])
    const loading = ref({
        fetch : false,
    })

    const niveles = ref([])
    const cursos = ref([])
    const sedes = ref([])
    const clases = ref([])


    const errors = ref([])

    async function fetch() {
        loading.value.fetch = true
        try {
            const response = await axios.get('beneficiarios/catalogos')
            catalogos.value = response.data
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

    async function fetchGrupoZona () {

        try {

            if(store.beneficiario.domicilios.hasOwnProperty('zona') && store.beneficiario.domicilios.grupo_x_zona.hasOwnProperty('id_grupo') ) {

                const id_zona = store.beneficiario.domicilios.zona
                const id_grupo = store.beneficiario.domicilios.grupo_x_zona.id_grupo

                const response = await axios.get('grupo-zona/'+ id_zona +'/'+ id_grupo)
                catalogos.value.grupos_x_zona = response.data
            }
            
        } catch (error) {
            console.error(error)
        }
    }

    async function fetchNiveles (id_programa) {
        loading.value.fetchPrograma = true
        try {
            const response = await axios.get('programas/' + id_programa)
            niveles.value = response.data.levels
        } catch (error) {
            if(error.response.data.hasOwnProperty('message') && error.response.data.hasOwnProperty('line')) {
                global.setAlert(error.response.data.message,'danger')
            }else if(error.response.data.hasOwnProperty('errors') && error.response.data.hasOwnProperty('message')){
                errors.value = error.response.data.errors
            }else{
                console.error(error.response.data)
            }
        }finally {
            loading.value.fetchPrograma = false
        }
    }

    async function fetchCursos (id_programa, id_nivel) {
        loading.value.fetchPrograma = true
        try {
            const response = await axios.post('clases/clase',{
                id_programa : id_programa,
                id_nivel : id_nivel,
                field : 'id_curso'
            })
            cursos.value = response.data.map(clase => clase.curso)
        } catch (error) {
            if(error.response.data.hasOwnProperty('message') && error.response.data.hasOwnProperty('line')) {
                global.setAlert(error.response.data.message,'danger')
            }else if(error.response.data.hasOwnProperty('errors') && error.response.data.hasOwnProperty('message')){
                errors.value = error.response.data.errors
            }else{
                console.error(error.response.data)
            }
        }finally {
            loading.value.fetchPrograma = false
        }
    }

    async function fetchSedes (id_programa, id_nivel) {
        loading.value.fetchPrograma = true
        try {
            const response = await axios.post('clases/clase',{
                id_programa : id_programa,
                id_nivel : id_nivel,
                field : 'id_sede'
            })
            sedes.value = response.data.map(clase => clase.sede)
        } catch (error) {
            if(error.response.data.hasOwnProperty('message') && error.response.data.hasOwnProperty('line')) {
                global.setAlert(error.response.data.message,'danger')
            }else if(error.response.data.hasOwnProperty('errors') && error.response.data.hasOwnProperty('message')){
                errors.value = error.response.data.errors
            }else{
                console.error(error.response.data)
            }
        }finally {
            loading.value.fetchPrograma = false
        }
    }

    async function fetchClases (id_programa, id_nivel, id_curso = null, id_sede = null) {
        loading.value.fetchPrograma = true
        try {
            const response = await axios.post('clases/clases',{
                id_programa : id_programa,
                id_nivel : id_nivel,
                id_curso : id_curso,
                id_sede : id_sede,
            })
            clases.value = response.data
        } catch (error) {
            if(error.response.data.hasOwnProperty('message') && error.response.data.hasOwnProperty('line')) {
                global.setAlert(error.response.data.message,'danger')
            }else if(error.response.data.hasOwnProperty('errors') && error.response.data.hasOwnProperty('message')){
                errors.value = error.response.data.errors
            }else{
                console.error(error.response.data)
            }
        }finally {
            loading.value.fetchPrograma = false
        }
    }

    return {
        catalogos,
        niveles,
        cursos,
        sedes,
        clases,

        fetch,
        fetchGrupoZona,
        fetchNiveles,
        fetchCursos,
        fetchClases,
        fetchSedes,
    }
})
