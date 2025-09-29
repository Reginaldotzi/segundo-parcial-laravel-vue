# 🔗 API Reference - Taller Laravel Vue

## 🔐 Autenticación

### POST /api/login
Iniciar sesión y obtener token de acceso.

**Request:**
```json
{
  "email": "usuario@example.com",
  "password": "password123"
}
```

**Response (200):**
```json
{
  "token": "1|eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
  "user": {
    "id": 1,
    "nombre": "Usuario Ejemplo", 
    "email": "usuario@example.com",
    "rol": "admin"
  }
}
```

**Response (401):**
```json
{
  "message": "Credenciales inválidas"
}
```

---

### POST /api/register  
Registrar nuevo usuario.

**Request:**
```json
{
  "nombre": "Nuevo Usuario",
  "email": "nuevo@example.com", 
  "password": "password123",
  "password_confirmation": "password123",
  "rol": "user"
}
```

**Response (201):**
```json
{
  "message": "Usuario registrado exitosamente",
  "user": {
    "id": 2,
    "nombre": "Nuevo Usuario",
    "email": "nuevo@example.com", 
    "rol": "user"
  },
  "token": "2|eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
}
```

---

### POST /api/logout
Cerrar sesión y revocar token.

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "message": "Sesión cerrada exitosamente"
}
```

---

### GET /api/user
Obtener información del usuario autenticado.

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "id": 1,
  "nombre": "Usuario Ejemplo",
  "email": "usuario@example.com",
  "rol": "admin",
  "created_at": "2025-09-28T00:00:00.000000Z",
  "updated_at": "2025-09-28T00:00:00.000000Z"
}
```

---

## 👥 Gestión de Usuarios

### GET /api/usuarios/listUsers
Listar todos los usuarios del tenant actual.

**Headers:**
```
Authorization: Bearer {token}
X-Tenant: empresa1 (opcional)
```

**Response (200):**
```json
[
  {
    "id": 1,
    "nombre": "Usuario 1",
    "email": "usuario1@example.com", 
    "rol": "admin",
    "created_at": "2025-09-28T00:00:00.000000Z"
  },
  {
    "id": 2, 
    "nombre": "Usuario 2",
    "email": "usuario2@example.com",
    "rol": "user",
    "created_at": "2025-09-28T00:00:00.000000Z"
  }
]
```

---

### GET /api/usuarios/getUser/{id}
Obtener información de un usuario específico.

**Headers:**
```
Authorization: Bearer {token}
X-Tenant: empresa1 (opcional)
```

**Response (200):**
```json
{
  "id": 1,
  "nombre": "Usuario Ejemplo",
  "email": "usuario@example.com",
  "rol": "admin", 
  "created_at": "2025-09-28T00:00:00.000000Z",
  "updated_at": "2025-09-28T00:00:00.000000Z"
}
```

**Response (404):**
```json
{
  "message": "Usuario no encontrado"
}
```

---

### POST /api/usuarios/addUser
Crear nuevo usuario.

**Headers:**
```
Authorization: Bearer {token}
X-Tenant: empresa1 (opcional)
```

**Request:**
```json
{
  "nombre": "Nuevo Usuario",
  "email": "nuevo@example.com",
  "password": "password123", 
  "rol": "user"
}
```

**Response (201):**
```json
{
  "message": "Usuario creado exitosamente",
  "usuario": {
    "id": 3,
    "nombre": "Nuevo Usuario", 
    "email": "nuevo@example.com",
    "rol": "user",
    "created_at": "2025-09-28T00:00:00.000000Z"
  }
}
```

**Response (422):**
```json
{
  "message": "Los datos proporcionados no son válidos",
  "errors": {
    "email": ["El email ya existe"],
    "nombre": ["El nombre es obligatorio"]
  }
}
```

---

### PUT /api/usuarios/updateUser/{id}
Actualizar usuario existente.

**Headers:**
```
Authorization: Bearer {token}
X-Tenant: empresa1 (opcional)
```

**Request:**
```json
{
  "nombre": "Usuario Actualizado",
  "email": "actualizado@example.com",
  "rol": "admin"
}
```

**Response (200):**
```json
{
  "message": "Usuario actualizado exitosamente",
  "usuario": {
    "id": 1,
    "nombre": "Usuario Actualizado",
    "email": "actualizado@example.com", 
    "rol": "admin",
    "updated_at": "2025-09-28T00:00:00.000000Z"
  }
}
```

---

### DELETE /api/usuarios/deleteUser/{id}
Eliminar usuario.

**Headers:**
```
Authorization: Bearer {token}
X-Tenant: empresa1 (opcional)
```

**Response (200):**
```json
{
  "message": "Usuario eliminado exitosamente"
}
```

**Response (404):**
```json
{
  "message": "Usuario no encontrado"
}
```

---

## 📋 Gestión de Tareas

### GET /api/tareas/
Listar todas las tareas del tenant actual.

**Headers:**
```
Authorization: Bearer {token}
X-Tenant: empresa1 (opcional)
```

**Response (200):**
```json
[
  {
    "id": 1,
    "titulo": "Tarea 1",
    "descripcion": "Descripción de la tarea",
    "estado": "pendiente",
    "usuario_id": 1,
    "created_at": "2025-09-28T00:00:00.000000Z"
  }
]
```

---

### POST /api/tareas/crear
Crear nueva tarea.

**Headers:**
```
Authorization: Bearer {token}
X-Tenant: empresa1 (opcional)
```

**Request:**
```json
{
  "titulo": "Nueva Tarea",
  "descripcion": "Descripción detallada",
  "estado": "pendiente",
  "usuario_id": 1
}
```

**Response (201):**
```json
{
  "message": "Tarea creada exitosamente", 
  "tarea": {
    "id": 2,
    "titulo": "Nueva Tarea",
    "descripcion": "Descripción detallada",
    "estado": "pendiente", 
    "usuario_id": 1,
    "created_at": "2025-09-28T00:00:00.000000Z"
  }
}
```

---

### GET /api/tareas/descargar-pendientes
Descargar reporte de tareas pendientes en Excel.

**Headers:**
```
Authorization: Bearer {token}
X-Tenant: empresa1 (opcional)
```

**Response:** Archivo Excel con tareas pendientes

---

## 🏢 Multitenancy

### Headers para Tenants
Para acceder a datos de un tenant específico, incluir header:

```
X-Tenant: empresa1
X-Tenant: empresa2  
X-Tenant: empresa3
```

### Comportamiento por Tenant
- **Sin X-Tenant:** Accede a base de datos principal
- **Con X-Tenant:** Accede a base de datos del tenant específico
- **Tenant inválido:** Usa empresa1 como fallback

### Tenants Disponibles
1. **empresa1** → Base de datos: `taller_empresa1`
2. **empresa2** → Base de datos: `taller_empresa2` 
3. **empresa3** → Base de datos: `taller_empresa3`

---

## ❌ Códigos de Error

### 401 Unauthorized
Token de autenticación inválido o ausente.

```json
{
  "message": "Unauthenticated."
}
```

### 403 Forbidden  
Token válido pero sin permisos suficientes.

```json
{
  "message": "Esta acción no está autorizada."
}
```

### 404 Not Found
Recurso no encontrado.

```json
{
  "message": "Recurso no encontrado"
}
```

### 422 Validation Error
Errores de validación en los datos enviados.

```json
{
  "message": "Los datos proporcionados no son válidos", 
  "errors": {
    "campo": ["Error específico del campo"]
  }
}
```

### 500 Internal Server Error
Error interno del servidor.

```json
{
  "message": "Error interno del servidor"
}
```

---

## 🧪 Ejemplos de Uso en Postman

### 1. Flujo Completo de Autenticación
```bash
# 1. Login
POST /api/login
{ "email": "admin@example.com", "password": "password" }

# 2. Usar token en siguientes peticiones
GET /api/user
Authorization: Bearer 1|token_aqui

# 3. Logout
POST /api/logout  
Authorization: Bearer 1|token_aqui
```

### 2. Flujo Multitenancy
```bash
# Sin tenant (BD principal)
GET /api/usuarios/listUsers
Authorization: Bearer token

# Con tenant empresa1
GET /api/usuarios/listUsers
Authorization: Bearer token
X-Tenant: empresa1

# Con tenant empresa2  
GET /api/usuarios/listUsers
Authorization: Bearer token
X-Tenant: empresa2
```

### 3. CRUD Completo Usuarios
```bash
# Crear
POST /api/usuarios/addUser
{ "nombre": "Test", "email": "test@test.com", "password": "123", "rol": "user" }

# Leer
GET /api/usuarios/getUser/1

# Actualizar  
PUT /api/usuarios/updateUser/1
{ "nombre": "Test Updated", "rol": "admin" }

# Eliminar
DELETE /api/usuarios/deleteUser/1
```