import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '@/views/HomeView.vue'
import UsersList from '@/views/UsersList.vue'
import UserForm from '@/views/UserForm.vue'
import TasksList from '@/views/TasksList.vue'
import TaskForm from '@/views/TaskForm.vue'

const routes = [
  { path: '/', redirect: '/usuarios' },
  { path: '/login', name: 'login', component: () => import('@/views/LoginView.vue') },

  { path: '/usuarios', name: 'usuarios', component: UsersList, meta: { requiresAuth: true } },
  { path: '/usuarios/nuevo', name: 'usuarios-nuevo', component: UserForm, meta: { requiresAuth: true } },
  { path: '/usuarios/:id/editar', name: 'usuarios-editar', component: UserForm, props: true, meta: { requiresAuth: true } },

  { path: '/tareas', name: 'tareas', component: TasksList, meta: { requiresAuth: true } },
  { path: '/tareas/nueva', name: 'tareas-nueva', component: TaskForm, meta: { requiresAuth: true } },

  { path: '/:pathMatch(.*)*', redirect: '/usuarios' },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to) => {
  const token = localStorage.getItem('token')
  if (to.meta.requiresAuth && !token) return { name: 'login', query: { redirect: to.fullPath } }
  if (to.name === 'login' && token) return { name: 'usuarios' }
  return true
})

export default router

