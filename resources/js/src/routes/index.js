import { createWebHistory, createRouter } from 'vue-router'

import HomeView from '@/view/dashboard.vue'
import authLayout from '@/layouts/authLayout.vue'
import auth from './middlewares/auth'
import guest from './middlewares/guest'


const routes = [
  { 
    path: '/', 
    component: authLayout,
    beforeEnter: guest,
    children: [
      {
        path: '/dashboard', 
        component: HomeView, 
        meta:{
          title: 'Bienvenido'
        }
      }
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
        component: () => import('@/view/auth/login.vue'),
        meta:{
          title: 'Bienvenido'
        }
      },
      {
        path: '/home', 
        name: 'home',
        component: HomeView, 
        meta:{
          title: 'Bienvenido'
        }
      }
    ]
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router