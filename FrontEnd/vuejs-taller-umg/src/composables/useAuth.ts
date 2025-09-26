import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/services/api'

export interface User {
  id: number
  nombre: string
  email: string
  rol: string
}

export interface LoginCredentials {
  email: string
  password: string
}

export interface RegisterData {
  nombre: string
  email: string
  password: string
  password_confirmation: string
  rol?: string
}

const user = ref<User | null>(null)
const loading = ref(false)
const error = ref('')

export const useAuth = () => {
  const router = useRouter()

  // Verificar si hay token y usuario en localStorage al inicializar
  const initAuth = () => {
    const token = localStorage.getItem('token')
    const userData = localStorage.getItem('user')
    
    if (token && userData) {
      user.value = JSON.parse(userData)
    }
  }

  const login = async (credentials: LoginCredentials) => {
    try {
      loading.value = true
      error.value = ''
      
      const response = await api.post('/login', credentials)
      
      // Guardar token y usuario
      localStorage.setItem('token', response.data.token)
      localStorage.setItem('user', JSON.stringify(response.data.usuario))
      user.value = response.data.usuario
      
      return { success: true }
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Error al iniciar sesión'
      return { success: false, error: error.value }
    } finally {
      loading.value = false
    }
  }

  const register = async (data: RegisterData) => {
    try {
      loading.value = true
      error.value = ''
      
      const response = await api.post('/register', data)
      
      // Guardar token y usuario
      localStorage.setItem('token', response.data.token)
      localStorage.setItem('user', JSON.stringify(response.data.user))
      user.value = response.data.user
      
      return { success: true }
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Error al registrar usuario'
      return { success: false, error: error.value }
    } finally {
      loading.value = false
    }
  }

  const logout = async () => {
    try {
      await api.post('/logout')
    } catch (err) {
      // Continuar con logout local aunque falle la petición
      console.error('Error en logout del servidor:', err)
    } finally {
      // Limpiar datos locales
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      user.value = null
      router.push('/login')
    }
  }

  const isAuthenticated = () => {
    return !!localStorage.getItem('token') && !!user.value
  }

  const isAdmin = () => {
    return user.value?.rol === 'admin'
  }

  // Interceptor para manejar errores 401
  api.interceptors.response.use(
    (response) => response,
    (error) => {
      if (error.response?.status === 401) {
        logout()
      }
      return Promise.reject(error)
    }
  )

  return {
    user: user.value,
    loading,
    error,
    login,
    register,
    logout,
    isAuthenticated,
    isAdmin,
    initAuth
  }
}