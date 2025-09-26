<template>
  <v-app>
    <v-app-bar app color="primary" dark>
      <v-toolbar-title>Panel de administración</v-toolbar-title>
      <v-spacer />
      <v-btn to="/usuarios" text>Usuarios</v-btn>
      <v-btn to="/usuarios/nuevo" text>Agregar usuario</v-btn>
      <v-btn to="/tareas" text>Tareas</v-btn>
      <v-btn to="/tareas/nueva" text>Agregar tarea</v-btn>
      <v-btn color="error" text @click="logout">Cerrar sesión</v-btn>
    </v-app-bar>
    <v-main>
      <router-view />
    </v-main>
  </v-app>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router'
const router = useRouter()
const logout = () => {
  localStorage.removeItem('token')
  router.push('/login')
}
</script>