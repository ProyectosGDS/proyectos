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
					path: 'home',
					name: 'Home',
					component : () => import('@/views/Home.vue'),
					meta : {
						auth : true
					}
				}
			]
		},
		{
			path: '/login',
			name: 'Login',
			component : () => import('@/views/Login.vue'),
			meta : {
				auth : true
			}
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
