<template>
  <div>
    <v-text-field v-model="searchTerm" label="Buscar usuario" class="mb-4" />
    <v-data-table
      :items="filtered"
      :headers="headers"
      :loading="loading"
      class="elevation-1"
    >
      <template #no-data>
        <div class="pa-6 text-center">No hay usuarios para mostrar.</div>
      </template>
    </v-data-table>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import api from '@/services/api'

type Usuario = { id:number; nombre:string; email:string; rol:'admin'|'usuario'; created_at:string }

const items = ref<Usuario[]>([])
const loading = ref(false)
const searchTerm = ref('')

const headers = [
  { title: 'Nombre', value: 'nombre' },
  { title: 'Email',  value: 'email' },
  { title: 'Rol',    value: 'rol' },
  { title: 'Fecha creación', value: 'created_at' },
]

const fetchUsers = async () => {
  loading.value = true
  try {
    const { data } = await api.get<Usuario[]>('/usuarios/listUsers')
    items.value = data
  } finally {
    loading.value = false
  }
}

onMounted(fetchUsers)

const filtered = computed(() => {
  const q = searchTerm.value.toLowerCase().trim()
  if (!q) return items.value
  return items.value.filter(u =>
    u.nombre.toLowerCase().includes(q) ||
    u.email.toLowerCase().includes(q) ||
    u.rol.toLowerCase().includes(q)
  )
})
</script>