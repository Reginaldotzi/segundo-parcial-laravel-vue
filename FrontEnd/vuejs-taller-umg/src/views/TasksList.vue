<template>
  <div>
    <v-btn color="primary" class="mb-4" @click="descargarExcel">DESCARGAR FORMULARIO</v-btn>
    <v-data-table
      :items="tareas"
      :headers="headers"
      :loading="loading"
      class="elevation-1"
    >
      <template #no-data>
        <div class="pa-6 text-center">No hay tareas para mostrar.</div>
      </template>
    </v-data-table>
    <v-btn to="/tareas/nueva" color="secondary" class="mb-4 ml-2">Agregar tarea</v-btn>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import api from '@/services/api'

type Tarea = {
  id: number
  titulo: string
  descripcion?: string
  estado: string
  fecha_vencimiento?: string
  usuario?: { id: number; nombre: string }
}

const tareas = ref<Tarea[]>([])
const loading = ref(false)

const headers = [
  { title: 'Título', value: 'titulo' },
  { title: 'Descripción', value: 'descripcion' },
  { title: 'Estado', value: 'estado' },
  { title: 'Usuario asignado', value: 'usuario.nombre' },
  { title: 'Fecha vencimiento', value: 'fecha_vencimiento' },
]

const fetchTareas = async () => {
  loading.value = true
  try {
    const { data } = await api.get<Tarea[]>('/tareas')
    tareas.value = data
  } finally {
    loading.value = false
  }
}

onMounted(fetchTareas)

const descargarExcel = () => {
  window.open(import.meta.env.VITE_API_URL + '/tareas/descargar-pendientes', '_blank')
}
</script>