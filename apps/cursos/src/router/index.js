import { createRouter, createWebHistory } from 'vue-router'
import NotFound from '@/views/404.vue'
import UnaAthorized from '@/views/401.vue'
import Layout from '@/layouts/Default.vue' 


const router = createRouter({
	history: createWebHistory(import.meta.env.VITE_MY_BASE),
	routes: [
		{
			path: '/',
			name: '',
			component: Layout,
			meta : {
				auth : true
			},
			children : [
				{
					path: 'menu-social',
					name: 'Menú social',
					component : () => import('@/views/Clases.vue'),
					meta : {
						auth : true
					}
				},
				{
					path: 'cursos',
					name: 'Cursos',
					component : () => import('@/views/Cursos.vue'),
					meta : {
						auth : true
					}
				},
				{
					path: 'niveles',
					name: 'Niveles',
					component : () => import('@/views/Niveles.vue'),
					meta : {
						auth : true
					}
				},
				{
					path: 'instructores',
					name: 'Instructores',
					component : () => import('@/views/Instructores.vue'),
					meta : {
						auth : true
					}
				},
				{
					path: 'horarios',
					name: 'Horarios',
					component : () => import('@/views/Horarios.vue'),
					meta : {
						auth : true
					}
				},
				{
					path: 'sedes',
					name: 'Sedes',
					component : () => import('@/views/Sedes.vue'),
					meta : {
						auth : true
					}
				},
				{
					path: 'requisitos',
					name: 'Requisitos',
					component : () => import('@/views/Requisitos.vue'),
					meta : {
						auth : true
					}
				},
				{
					path: 'programas',
					name: 'Programas',
					component : () => import('@/views/Programas.vue'),
					meta : {
						auth : true
					}
				},
			]
		},
		{
			path : '/401',
			name : '401',
			component : UnaAthorized
		},
		{
			//MANEJA TODAS LAS PAGINAS QUE NO EXISTEN Y LA REDIRIJE AL 404 NOT FOUND
			path: '/:catchAll(.*)',
			component: NotFound,
		}
	]
})

router.beforeEach((to, from) => {
	
	const accessToken = (document.cookie.split('=')[0] === btoa('access_token'))

	if (to.meta.auth) {

		if (!accessToken && to.name != 'Login') {		

			window.location.href = import.meta.env.VITE_MY_URL + 'login';
			
		}
	}
	
	return true

})

export default router
