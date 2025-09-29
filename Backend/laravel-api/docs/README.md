# segundo parcial - laravel + vue.js
## proyecto completo con autenticacion, multitenancy y despliegue en aws

**estudiante:** reginaldo sebastian tzic fernández
**fecha:** septiembre 2025  
**rama:** feature/segundoparcial  

---

## 🎯 funcionalidades implementadas

### **sistema de autenticacion**
- login y registro implementado con laravel sanctum
- proteccion de rutas api mediante tokens de acceso
- integracion completa con frontend vue.js
- manejo de sesiones y estados de autenticacion

### **arquitectura multitenancy**  
- base de datos independiente por cada tenant/empresa
- middleware personalizado para deteccion automatica de tenant
- aislamiento completo de datos entre organizaciones
- compatible con el sistema de autenticacion existente

### **despliegue en aws ec2**
- servidor ubuntu 22.04 lts configurado en aws
- stack lamp (linux, apache, mysql, php) instalado
- backend laravel desplegado y funcional
- repositorio clonado desde github al servidor

---

## Arquitectura del Sistema

### **Frontend (Vue.js 3)**
```
FrontEnd/vuejs-taller-umg/
├── src/
│   ├── components/        # Componentes reutilizables
│   ├── views/            # Páginas principales
│   ├── router/           # Configuración de rutas
│   ├── composables/      # Lógica de autenticación
│   └── services/         # Servicios API
```

### **Backend (Laravel 10+)**
```
Backend/laravel-api/
├── app/
│   ├── Http/
│   │   ├── Controllers/Api/    # Controladores API
│   │   └── Middleware/         # Middleware personalizado
│   ├── Models/                 # Modelos Eloquent
│   └── Console/Commands/       # Comandos Artisan
├── config/database.php         # Configuración multitenancy
├── routes/api.php             # Rutas de API
└── docs/                      # Esta documentación
```

---

## 📋 proceso de despliegue aws

### **configuracion del servidor:**
```bash
# instalacion de dependencias
sudo apt update
sudo apt install apache2 mysql-server php php-mysql composer nodejs npm git

# clonado del repositorio
cd /var/www/html/
git clone https://github.com/reginaldotzi/segundo-parcial-laravel-vue.git
cd segundo-parcial-laravel-vue/Backend/laravel-api/

# configuracion laravel
composer install --ignore-platform-reqs
cp .env.example .env
php artisan key:generate
```

### **configuracion de base de datos:**
```sql
-- creacion de usuario y bases de datos
CREATE USER 'laravel_user'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON *.* TO 'laravel_user'@'localhost';
CREATE DATABASE laravel_taller;
CREATE DATABASE taller_empresa1;
CREATE DATABASE taller_empresa2;
CREATE DATABASE taller_empresa3;
```

### **migraciones ejecutadas:**
```bash
php artisan migrate
php artisan migrate --database=tenant_empresa1
php artisan migrate --database=tenant_empresa2
php artisan migrate --database=tenant_empresa3
```

---

## ☁️ despliegue en aws ec2

### **servidor configurado:**
- **ip publica:** 18.188.150.119
- **sistema:** ubuntu 22.04 lts
- **stack:** lamp (linux, apache, mysql, php)
- **repositorio:** clonado desde github

### **bases de datos creadas:**
- laravel_taller (principal)
- taller_empresa1 (tenant 1)
- taller_empresa2 (tenant 2)  
- taller_empresa3 (tenant 3)

### **estado actual del despliegue:**
- backend laravel: funcionando correctamente en puerto 8000
- base de datos mysql: configurada con todos los tenants
- autenticacion sanctum: operativa y validando tokens
- sistema multitenancy: funcional con separacion de datos
- frontend vue.js: presenta problemas de performance en aws

### **limitaciones encontradas:**
- problemas de conectividad intermitente durante la configuracion
- servidor frontend vite requiere optimizacion para entorno productivo
- necesidad de configuracion adicional de security groups para puertos personalizados

---

## 🔐 Sistema de Autenticación

### **Tecnología Utilizada: Laravel Sanctum**

Laravel Sanctum fue elegido por:
- ✅ Simplicidad de implementación
- ✅ Soporte nativo para SPAs
- ✅ Tokens seguros y escalables
- ✅ Integración perfecta con Vue.js

### **Flujo de Autenticación**

1. **Login:** `POST /api/login`
   ```json
   {
     "email": "usuario@example.com", 
     "password": "password123"
   }
   ```

2. **Respuesta:** Token de acceso
   ```json
   {
     "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
     "user": { "id": 1, "name": "Usuario", "email": "usuario@example.com" }
   }
   ```

3. **Uso:** Header en peticiones protegidas
   ```
   Authorization: Bearer {token}
   ```

### **APIs Protegidas**
Todas las siguientes rutas requieren autenticación:
- `GET /api/user` - Información del usuario
- `POST /api/logout` - Cerrar sesión  
- `GET /api/usuarios/listUsers` - Listar usuarios
- `POST /api/usuarios/addUser` - Crear usuario
- `PUT /api/usuarios/updateUser/{id}` - Actualizar usuario
- `DELETE /api/usuarios/deleteUser/{id}` - Eliminar usuario
- `GET /api/tareas/` - Listar tareas
- `POST /api/tareas/crear` - Crear tarea

### **Manejo de Errores**
- **401 Unauthorized:** Token inválido o ausente
- **403 Forbidden:** Token válido pero sin permisos
- **422 Validation Error:** Datos de entrada incorrectos

---

## 🏢 Sistema Multitenant

### **Arquitectura Implementada**

El sistema utiliza **separación por base de datos** donde cada tenant tiene su propio esquema de BD independiente.

### **Configuración de Tenants**

#### **Conexiones de Base de Datos**
```php
// config/database.php
'tenant_empresa1' => [
    'driver' => 'mysql',
    'database' => 'taller_empresa1',
    // ... configuración completa
],
'tenant_empresa2' => [
    'driver' => 'mysql', 
    'database' => 'taller_empresa2',
    // ... configuración completa
],
'tenant_empresa3' => [
    'driver' => 'mysql',
    'database' => 'taller_empresa3', 
    // ... configuración completa
]
```

### **Middleware Implementados**

#### **1. TenantMiddleware** 
**Archivo:** `app/Http/Middleware/TenantMiddleware.php`

**Propósito:** Detectar el tenant desde subdominio o header

**Funcionamiento:**
- Detecta subdominios como `empresa1.midominio.com`
- Alternativamente usa header `X-Tenant` para desarrollo
- Almacena información del tenant en el request

```php
// Detección por subdominio
if (preg_match('/^(empresa\d+)\./', $host, $matches)) {
    return $matches[1];
}

// Detección por header (desarrollo)
$tenant = $request->header('X-Tenant');
```

#### **2. SetTenantConnection**
**Archivo:** `app/Http/Middleware/SetTenantConnection.php`

**Propósito:** Cambiar conexión de BD después de la autenticación

**Funcionamiento:**
- Se ejecuta DESPUÉS de `auth:sanctum`
- Cambia la conexión por defecto al tenant específico
- Garantiza que las consultas usen la BD correcta

### **Flujo Multitenant**

```
Request → TenantMiddleware → auth:sanctum → SetTenantConnection → Controller
```

1. **TenantMiddleware:** Identifica el tenant
2. **auth:sanctum:** Valida token en BD original  
3. **SetTenantConnection:** Cambia a BD del tenant
4. **Controller:** Ejecuta consultas en BD del tenant

### **Comando de Migración**
**Archivo:** `app/Console/Commands/MigrateTenant.php`

```bash
php artisan tenant:migrate empresa1
php artisan tenant:migrate empresa2  
php artisan tenant:migrate empresa3
```

Este comando:
- Crea la base de datos del tenant si no existe
- Ejecuta todas las migraciones en la BD del tenant
- Ejecuta seeders para datos iniciales

### **Aislamiento de Datos**

✅ **Garantizado:** Los usuarios de `empresa1` NO pueden ver datos de `empresa2` o `empresa3`  
✅ **Verificado:** Cada tenant tiene usuarios y datos completamente independientes  
✅ **Probado:** Sistema funciona correctamente en Postman con headers X-Tenant

---

## 🧪 Testing y Validación

### **Pruebas en Postman**

#### **Sin Tenant (BD Original)**
```
GET /api/usuarios/listUsers
Authorization: Bearer {token}
```

#### **Con Tenant Específico**
```
GET /api/usuarios/listUsers
Authorization: Bearer {token}
X-Tenant: empresa1
```

### **Resultados Esperados**
- **Sin X-Tenant:** Muestra usuarios de BD original
- **X-Tenant: empresa1:** Muestra solo usuarios de empresa1
- **X-Tenant: empresa2:** Muestra solo usuarios de empresa2  
- **X-Tenant: empresa3:** Muestra solo usuarios de empresa3

### **Validaciones Realizadas**
✅ Autenticación funciona correctamente  
✅ Tokens válidos permiten acceso  
✅ Tokens inválidos retornan 401  
✅ Separación de datos por tenant  
✅ No hay filtración entre tenants  

---

## 🚀 Frontend Vue.js

### **Componentes Principales**

#### **LoginView.vue**
- Formulario de autenticación
- Manejo de errores de login
- Redirección post-autenticación

#### **UsuariosList.vue** 
- Lista paginada de usuarios
- Funciones CRUD completas
- Protegido por autenticación

### **Composables**

#### **useAuth.js**
```javascript
// Manejo centralizado de autenticación
export function useAuth() {
  const login = async (credentials) => { /* ... */ }
  const logout = () => { /* ... */ }
  const isAuthenticated = computed(() => /* ... */)
  
  return { login, logout, isAuthenticated }
}
```

### **Configuración de Rutas**
```javascript
// Protección de rutas
{
  path: '/usuarios',
  component: UsuariosList,
  meta: { requiresAuth: true }
}
```

---

## 📊 Base de Datos

### **Estructura Principal**

#### **Tabla: usuarios**
```sql
CREATE TABLE usuarios (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL, 
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

#### **Tabla: personal_access_tokens (Sanctum)**
```sql  
CREATE TABLE personal_access_tokens (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    tokenable_type VARCHAR(255) NOT NULL,
    tokenable_id BIGINT NOT NULL,
    name VARCHAR(255) NOT NULL,
    token VARCHAR(64) UNIQUE NOT NULL,
    abilities TEXT,
    last_used_at TIMESTAMP NULL,
    expires_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### **Bases de Datos por Tenant**
- **laravel_taller** - BD original/principal
- **taller_empresa1** - Tenant empresa1  
- **taller_empresa2** - Tenant empresa2
- **taller_empresa3** - Tenant empresa3

---

## 🔧 Instalación y Configuración

### **Requisitos del Sistema**
- PHP 8.1+
- MySQL 8.0+  
- Composer 2.0+
- Node.js 18+
- npm 9+

### **Instalación Backend**
```bash
cd Backend/laravel-api
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
```

### **Configuración Multitenancy**
```bash
# Migrar tenants
php artisan tenant:migrate empresa1
php artisan tenant:migrate empresa2
php artisan tenant:migrate empresa3
```

### **Instalación Frontend**  
```bash
cd FrontEnd/vuejs-taller-umg
npm install
npm run dev
```

### **Ejecución**
```bash
# Backend
php artisan serve

# Frontend (nueva terminal)
npm run dev
```

---

## 🎯 resumen del proyecto

### **componentes implementados:**
- sistema de autenticacion completo con laravel sanctum
- arquitectura multitenancy con separacion por base de datos
- frontend vue.js con integracion de apis protegidas
- despliegue parcial en aws ec2 con backend funcional

### **tecnologias utilizadas:**
- laravel 10 con sanctum para manejo de tokens
- vue.js 3 con composition api y router
- mysql con configuracion de multiples conexiones
- aws ec2 con ubuntu server 22.04 lts

### **urls del proyecto:**
- repositorio github: https://github.com/reginaldotzi/segundo-parcial-laravel-vue
- backend aws: http://18.188.150.119:8000
- documentacion: disponible en directorio /docs

### **estado final:**
el backend está completamente funcional en aws con todas las caracteristicas implementadas. el frontend presenta limitaciones de performance en el entorno cloud que requieren optimizacion adicional. debido a problemas de conectividad durante el desarrollo, no se completó la optimizacion del despliegue frontend.

---

**proyecto:** segundo parcial laravel + vue.js  
**fecha:** septiembre 2025  
**estudiante:** reginaldo sebastian tzic fernández