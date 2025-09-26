<template>
  <v-container class="d-flex align-center justify-center fill-height">
    <v-card class="w-full max-w-md p-6 rounded-2xl">
      <h2 class="text-xl font-semibold mb-4">Agregar usuario</h2>
      <v-form v-model="valid" @submit.prevent="onSubmit">
        <v-text-field v-model="nombre" label="Nombre" :rules="[rules.required]" />
        <v-text-field v-model="email" label="Email" :rules="[rules.required, rules.email]" />
        <v-text-field v-model="password" label="Password" type="password" :rules="[rules.required, rules.min6]" />
        <v-select v-model="rol" :items="['admin','usuario']" label="Rol" :rules="[rules.required]" />
        <v-btn :loading="loading" :disabled="!valid || loading" type="submit" color="primary" class="w-full mt-4">Guardar</v-btn>
        <v-alert v-if="errorMsg" type="error" variant="tonal" class="mt-3" :text="errorMsg" />
      </v-form>
    </v-card>
  </v-container>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/services/api'

const router = useRouter()
const nombre = ref('')
const email = ref('')
const password = ref('')
const rol = ref('usuario')
const loading = ref(false)
const valid = ref(false)
const errorMsg = ref('')

const rules = {
  required: (v: string) => !!v || 'Requerido',
  email: (v: string) => /.+@.+\..+/.test(v) || 'Email inválido',
  min6: (v: string) => (v?.length ?? 0) >= 6 || 'Mínimo 6 caracteres',
}

const onSubmit = async () => {
  errorMsg.value = ''
  loading.value = true
  try {
    await api.post('/usuarios/addUser', {
      nombre: nombre.value,
      email: email.value,
      password: password.value,
      rol: rol.value,
    })
    router.push('/usuarios')
  } catch (e: any) {
    errorMsg.value = e?.response?.data?.message || 'Error al crear usuario'
  } finally {
    loading.value = false
  }
}
</script>