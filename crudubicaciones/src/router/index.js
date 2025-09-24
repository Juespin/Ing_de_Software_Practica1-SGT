import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import Ubicaciones from '@/components/Ubicaciones.vue'
import Responsables from '@/components/Responsables.vue'
import Equiposmedicos from '@/components/Equiposmedicos.vue'

const routes = [
    {
    path: '/',
    name: 'home',
    component: HomeView
  },
  {
    path: '/ubicaciones',
    name: 'ubicaciones',
    component: Ubicaciones
  },
  
  {
    path: '/responsables',
    name: 'responsables',
    component: Responsables
  },
  {
    path: '/equiposmedicos',
    name: 'equiposmedicos',
    component: Equiposmedicos
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
