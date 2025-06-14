import { createWebHistory, createRouter } from 'vue-router'

import authLayout from '@/layouts/authLayout.vue'
import auth from './middlewares/auth'
import guest from './middlewares/guest'
import ClientLayout from '@/layouts/clientLayout.vue'
import AdminLayout from '@/layouts/adminLayout.vue'


const routes = [
  {
    path: '/', 
    redirect: { name: 'home' },
  },
  { 
    path: '/', 
    component: ClientLayout,
    redirect: { name: 'home' },
    children: [
      {
        path: '/home', 
        name: 'home',
        component: () => import('@/view/client/homePage.vue'),
        meta:{
          title: 'Bienvenido'
        }
      },
      {
        path: '/support', 
        name: 'support',
        component: () => import('@/view/client/homePage.vue'),
        meta:{
          title: 'Bienvenido'
        }
      },
      {
        path: '/rifas', 
        name: 'rifas',
        component: () => import('@/view/client/homePage.vue'),
        meta:{
          title: 'Bienvenido'
        }
      },
      {
        path: '/rifa/:id', 
        name: 'rifaById',
        component: () => import('@/view/client/rifaByIdPage.vue'),
        meta:{
          title: 'Bienvenido'
        }
      },
      {
        path: '/order/finish/:id', 
        name: 'orderFinish',
        component: () => import('@/view/client/orderFinish.vue'),
        meta:{
          title: 'Bienvenido'
        }
      },
    ]
  },
  { 
    path: '/', 
    component: authLayout,
    beforeEnter: guest,
    redirect: { name: 'home' },
    children: [
      {
        path:'/login',
        component: () =>import('@/view/auth/login.vue'), 
        meta:{
          title: 'Bienvenido'
        }
      },
    ]
  },
  { 
    path: '/', 
    component: AdminLayout,
    beforeEnter: auth,
    children: [
      {
        path:'/admin/dashboard',
        component: () => import('@/view/admin/dashboard.vue'),
        meta:{
          title: 'Bienvenido'
        }
      },
      {
        path:'/admin/rifas',
        component: () => import('@/view/admin/rifas.vue'),
        meta:{
          title: 'Rifas'
        }
      },
      {
        path:'/admin/pagos',
        component: () => import('@/view/admin/dashboard.vue'),
        meta:{
          title: 'Bienvenido'
        }
      },
      {
        path:'/admin/pay_methods',
        component: () => import('@/view/admin/dashboard.vue'),
        meta:{
          title: 'Bienvenido'
        }
      },
      {
        path:'/admin/clients',
        component: () => import('@/view/admin/dashboard.vue'),
        meta:{
          title: 'Bienvenido'
        }
      },
      {
        path:'/admin/configuration',
        component: () => import('@/view/admin/dashboard.vue'),
        meta:{
          title: 'Bienvenido'
        }
      },
      {
        path:'/admin/report',
        component: () => import('@/view/admin/dashboard.vue'),
        meta:{
          title: 'Bienvenido'
        }
      },
    ]
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router