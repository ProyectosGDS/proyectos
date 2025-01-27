import { defineStore } from 'pinia'
import axios from 'axios'
import { ref } from 'vue'
import { useGlobalStore } from './global'

export const useUsuariosStore = defineStore('usuarios', () => {
    
    const global = useGlobalStore()

    const headers = [
        { title : 'id', key : 'id_usuario', type: 'numeric' },
        { title : 'cui', key : 'cui'},
        { title : 'nombre', key : 'nombre'},
        { title : 'dependencia', key : 'dependencia.nombre'},
        { title : 'role', key : 'role.nombre'},
        { title : 'actions', key : 'actions', width : '10px', align : 'center'},
    ]

    const dependencias = ref([])
    const usuarios = ref([])
    const usuario = ref({})
    const roles = ref([])
    const menuPaginas = ref([])
    const paginas = ref([])
    const loading = ref({
        fetch : false,
        show : false,
        dependencias : false,
        store : false,
        update : false,
        destroy : false,
        fetchRoles : false,
        fetchPages : false,
        restartPassword : false,
    })

    const modal = ref({
        new : false,
        edit : false,
        delete : false,
        assignPages : false,
        restart : false,
    })

    const errors = ref([])

    async function fetchPages() {
        loading.value.fetchPages = true
        try {
            const response = await axios.get('menu-paginas')
            menuPaginas.value = response.data
        } catch (error) {
            if(error.response.data.hasOwnProperty('message') && error.response.data.hasOwnProperty('line')) {
                global.setAlert(error.response.data.message,'danger')
            }else if(error.response.data.hasOwnProperty('errors') && error.response.data.hasOwnProperty('message')){
                errors.value = error.response.data.errors
            }else{
                console.error(error.response.data)
            }
        } finally {
            loading.value.fetchPages = false
        }
    }

    async function fetchRoles() {
        loading.value.fetchRoles = true
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
            loading.value.fetchRoles = false
        }
    }

    async function fetchDependencias() {
        loading.value.dependencias = true
        try {
            const response = await axios.get('dependencias')
            dependencias.value = response.data
        } catch (error) {
            if(error.response.data.hasOwnProperty('message') && error.response.data.hasOwnProperty('line')) {
                global.setAlert(error.response.data.message,'danger')
            }else if(error.response.data.hasOwnProperty('errors') && error.response.data.hasOwnProperty('message')){
                errors.value = error.response.data.errors
            }else{
                console.error(error.response.data)
            }
        } finally {
            loading.value.dependencias = false
        }
    }

    async function fetch() {
        loading.value.fetch = true
        try {
            const response = await axios.get('usuarios')
            usuarios.value = response.data
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

    async function show(id_usuario) {
        loading.value.fetch = true
        try {
            const response = await axios.get('usuarios/' + id_usuario)
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
            loading.value.fetch = false
        }
    }

    async function store() {
        loading.value.store = true
        try {
            const response = await axios.post('usuarios',usuario.value)
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
            const response = await axios.put('usuarios/' + usuario.value.id_usuario,usuario.value)
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
            const response = await axios.delete('usuarios/' + usuario.value.id_usuario)
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

    async function restartPassword() {
        loading.value.restartPassword = true
        try {
            const response = await axios.put('usuarios/reiniciar-password/' + usuario.value.id_usuario)
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
            loading.value.restartPassword = false
        }
    }

    async function edit(item) {
        usuario.value = item
        modal.value.edit = true
    }

    async function assign(item) {
        usuario.value = await show(item.id_usuario)
        paginas.value = usuario.value.menus.map(pagina => pagina.id_menu)
        modal.value.assignPages = true
    }

    async function assignPagesUser() {
        loading.value.update = true
        try {
            const response = await axios.put('usuarios/asignar-paginas/' + usuario.value.id_usuario,{
                paginas : paginas.value
            })
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



    function deleteItem (item) {
        usuario.value = item
        modal.value.delete = true
    }

    function restart (item) {
        usuario.value = item
        modal.value.restart = true
    }


    function resetData() {
        usuario.value = {}
        modal.value = {
            new : false,
            edit : false,
            delete : false,
            assignPages : false,
            restart : false,
        }
        errors.value = []
        paginas.value = []
    }

    return {
        headers,
        dependencias,
        usuarios,
        usuario,
        roles,
        menuPaginas,
        paginas,
        loading,
        modal,
        errors,

        fetch,
        fetchDependencias,
        fetchRoles,
        fetchPages,
        store,
        update,
        destroy,
        restartPassword,
        edit,
        assign,
        deleteItem,
        restart,
        assignPagesUser,
        resetData,
    }
})
