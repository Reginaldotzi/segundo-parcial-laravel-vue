# 🚀 Guía de Instalación y Configuración

## Requisitos del Sistema

### Software Necesario
- **PHP:** 8.1 o superior
- **MySQL:** 8.0 o superior  
- **Composer:** 2.0 o superior
- **Node.js:** 18 o superior
- **npm:** 9 o superior
- **Git:** Para control de versiones

### Verificar Requisitos
```bash
php --version
mysql --version
composer --version
node --version  
npm --version
git --version
```

---

## 📦 Instalación Paso a Paso

### 1. Clonar Repositorio
```bash
git clone [URL-del-repositorio]
cd Taller-Laravel-Vue
git checkout feature/segundoParcial
```

### 2. Configurar Backend (Laravel)
```bash
cd Backend/laravel-api
composer install
cp .env.example .env
php artisan key:generate
```

### 3. Configurar Base de Datos
```bash
# Editar .env con tus credenciales de MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1  
DB_PORT=3306
DB_DATABASE=laravel_taller
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Migrar Base de Datos Principal
```bash
php artisan migrate
php artisan db:seed
```

### 5. Configurar Multitenancy
```bash
# Crear y migrar bases de datos de tenants
php artisan tenant:migrate empresa1
php artisan tenant:migrate empresa2  
php artisan tenant:migrate empresa3
```

### 6. Configurar Frontend (Vue.js)
```bash
cd ../../FrontEnd/vuejs-taller-umg
npm install
```

### 7. Ejecutar Aplicación
```bash
# Terminal 1: Backend
cd Backend/laravel-api
php artisan serve

# Terminal 2: Frontend  
cd FrontEnd/vuejs-taller-umg
npm run dev
```

---

## 🔧 Configuración Avanzada

### Variables de Entorno (.env)
```env
APP_NAME="Taller Laravel Vue"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

# Base de datos principal
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_taller
DB_USERNAME=root
DB_PASSWORD=

# Configuración Sanctum
SANCTUM_STATEFUL_DOMAINS=localhost:5173,127.0.0.1:5173
```

### CORS para Frontend
El archivo `config/cors.php` ya está configurado para permitir:
- Origen: `http://localhost:5173` (Vite)
- Métodos: GET, POST, PUT, DELETE, OPTIONS
- Headers: Authorization, Content-Type, X-Tenant

---

## 🧪 Verificación de Instalación

### 1. Probar Backend
```bash
# Verificar que Laravel está funcionando
curl http://localhost:8000/api/
```

### 2. Probar Frontend  
```bash
# Abrir navegador en:
http://localhost:5173
```

### 3. Probar Autenticación
1. Ir a `/login` en el frontend
2. Usar credenciales de un usuario seeded
3. Verificar redirección a dashboard

### 4. Probar Multitenancy (Postman)
```
GET http://localhost:8000/api/usuarios/listUsers
Authorization: Bearer {token}
X-Tenant: empresa1
```

---

## ❌ Solución de Problemas

### Error: "Class not found"
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

### Error: "Migration not found"  
```bash
php artisan migrate:reset
php artisan migrate
```

### Error: CORS en Frontend
Verificar que `APP_URL` en `.env` coincida con el puerto de Laravel

### Error: Sanctum Token
Verificar que `SANCTUM_STATEFUL_DOMAINS` incluya el dominio del frontend

---

## 🎯 URLs de Acceso

### Frontend (Vue.js)
- **Desarrollo:** http://localhost:5173
- **Login:** http://localhost:5173/login  
- **Dashboard:** http://localhost:5173/usuarios

### Backend (Laravel)
- **API Base:** http://localhost:8000/api/
- **Login:** POST http://localhost:8000/api/login
- **Usuarios:** GET http://localhost:8000/api/usuarios/listUsers

---

## 📝 Credenciales de Prueba

### Usuarios por Defecto (Seeders)
```
Email: admin@example.com
Password: password

Email: user@example.com  
Password: password
```

### Tenants Disponibles
- `empresa1`
- `empresa2` 
- `empresa3`

Usar header `X-Tenant: empresa1` en peticiones API para multitenancy.