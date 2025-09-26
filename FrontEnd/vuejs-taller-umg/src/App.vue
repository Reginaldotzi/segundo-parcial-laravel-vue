<template>
  <v-app>
    <v-app-bar v-if="isAuthenticated" app color="primary" dark>
      <v-toolbar-title>Panel de administración</v-toolbar-title>
      <v-spacer />
      <v-btn to="/usuarios" text>Usuarios</v-btn>
      <v-btn to="/usuarios/nuevo" text>Agregar usuario</v-btn>
      <v-btn to="/tareas" text>Tareas</v-btn>
      <v-btn to="/tareas/nueva" text>Agregar tarea</v-btn>
      
      <v-menu>
        <template v-slot:activator="{ props }">
          <v-btn v-bind="props" text>
            <v-icon left>mdi-account</v-icon>
            {{ user?.nombre || 'Usuario' }}
          </v-btn>
        </template>
        <v-list>
          <v-list-item>
            <v-list-item-title>{{ user?.email }}</v-list-item-title>
            <v-list-item-subtitle>{{ user?.rol }}</v-list-item-subtitle>
          </v-list-item>
          <v-divider />
          <v-list-item @click="logout">
            <v-list-item-title>
              <v-icon left>mdi-logout</v-icon>
              Cerrar sesión
            </v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-app-bar>
    
    <v-main>
      <router-view />
    </v-main>
  </v-app>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '@/composables/useAuth'

const router = useRouter()
const { initAuth, logout: authLogout, user } = useAuth()

const isAuthenticated = computed(() => {
  return !!localStorage.getItem('token')
})

const logout = async () => {
  await authLogout()
}

onMounted(() => {
  initAuth()
})
</script>