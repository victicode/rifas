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
        component: () => import('@/view/client/supportPage.vue'),
        meta:{
          title: 'Te ayudamos!'
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
        path: '/winners', 
        name: 'winners',
        component: () => import('@/view/client/winnersPage.vue'),
        meta:{
          title: 'Ganadores'
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
      {
        path: '/finder/ticket/:id', 
        name: 'finder',
        component: () => import('@/view/client/ticketFinder.vue'),
        meta:{
          title: 'Consulta tus tickets'
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
        path:'/admin/rifas/:id/tickets',
        component: () => import('@/view/admin/listNumberInRifas.vue'),
        meta:{
          title: 'Rifas'
        }
      },
      {
        path:'/admin/pays',
        component: () => import('@/view/admin/pays.vue'),
        meta:{
          title: 'Bienvenido',
          titlePage: 'Ordenes de pago'
        }
      },
      {
        path:'/admin/data_pays',
        component: () => import('@/view/admin/payData.vue'),
        meta:{
          title: 'Datos de pago'
        }
      },
      {
        path:'/admin/pay_methods',
        component: () => import('@/view/admin/payMethods.vue'),
        meta:{
          title: 'Métodos de pago'
        }
      },
      {
        path:'/admin/clients',
        component: () => import('@/view/admin/clientsPage.vue'),
        meta:{
          title: 'Clientes'
        }
      },
      {
        path:'/admin/configuration',
        component: () => import('@/view/admin/systemPage.vue'),
        meta:{
          title: 'Configuración'
        }
      },
      {
        path:'/admin/reports',
        component: () => import('@/view/admin/reports.vue'),
        meta:{
          title: 'Reportes'
        }
      },
      {
        path:'/admin/winners',
        component: () => import('@/view/admin/winners.vue'),
        meta:{
          title: 'Ganadores'
        }
      },
      {
        path:'/admin/reports/tickets-buy',
        component: () => import('@/view/admin/reports/ticketsBuy.vue'),
        meta:{
          title: 'Top compras'
        }
      },
      {
        path:'/admin/reports/buy-by-day',
        component: () => import('@/view/admin/reports/sellsByDay.vue'),
        meta:{
          title: 'Compras en el dia'
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