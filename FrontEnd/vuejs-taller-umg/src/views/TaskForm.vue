<template>
  <v-container class="d-flex align-center justify-center fill-height">
    <v-card class="w-full max-w-md p-6 rounded-2xl">
      <h2 class="text-xl font-semibold mb-4">Agregar tarea</h2>
      <v-form v-model="valid" @submit.prevent="onSubmit">
        <v-text-field v-model="titulo" label="Título" :rules="[rules.required]" />
        <v-textarea v-model="descripcion" label="Descripción" />
        <v-select v-model="estado" :items="['pendiente','completada']" label="Estado" :rules="[rules.required]" />
        <v-text-field v-model="fecha_vencimiento" label="Fecha de vencimiento" type="date" />
        <v-select v-model="usuario_id" :items="usuarios" item-title="nombre" item-value="id" label="Asignar a usuario" :rules="[rules.required]" />
        <v-btn :loading="loading" :disabled="!valid || loading" type="submit" color="primary" class="w-full mt-4">Guardar</v-btn>
        <v-alert v-if="errorMsg" type="error" variant="tonal" class="mt-3" :text="errorMsg" />
      </v-form>
    </v-card>
  </v-container>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/services/api'

const router = useRouter()
const titulo = ref('')
const descripcion = ref('')
const estado = ref('pendiente')
const fecha_vencimiento = ref('')
const usuario_id = ref<number|null>(null)
const usuarios = ref<{id:number;nombre:string}[]>([])
const loading = ref(false)
const valid = ref(false)
const errorMsg = ref('')

const rules = {
  required: (v: any) => !!v || 'Requerido',
}

const fetchUsuarios = async () => {
  const { data } = await api.get('/usuarios/listUsers')
  usuarios.value = data
}

onMounted(fetchUsuarios)

const onSubmit = async () => {
  errorMsg.value = ''
  loading.value = true
  try {
    await api.post('/tareas/crear', {
      titulo: titulo.value,
      descripcion: descripcion.value,
      estado: estado.value,
      fecha_vencimiento: fecha_vencimiento.value,
      usuario_id: usuario_id.value,
    })
    router.push('/tareas')
  } catch (e: any) {
    errorMsg.value = e?.response?.data?.message || 'Error al crear tarea'
  } finally {
    loading.value = false
  }
}
</script>