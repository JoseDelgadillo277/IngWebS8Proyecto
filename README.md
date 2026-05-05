# Arte Dental

Sistema web para la gestion de una clinica odontologica. El proyecto esta construido con Laravel, Blade, Tailwind CSS, Laravel Breeze y Spatie Permission para manejar autenticacion, roles, pacientes, citas, historias clinicas, RRHH, pagos y vistas administrativas.

## Contenido

- [Descripcion general](#descripcion-general)
- [Caracteristicas principales](#caracteristicas-principales)
- [Tecnologias](#tecnologias)
- [Requisitos](#requisitos)
- [Instalacion local](#instalacion-local)
- [Ejecucion con Docker](#ejecucion-con-docker)
- [Credenciales iniciales](#credenciales-iniciales)
- [Comandos utiles](#comandos-utiles)
- [Estructura del proyecto](#estructura-del-proyecto)
- [Modulos del sistema](#modulos-del-sistema)
- [Roles y permisos](#roles-y-permisos)
- [Base de datos](#base-de-datos)
- [Rutas principales](#rutas-principales)
- [Pruebas](#pruebas)
- [Notas de desarrollo](#notas-de-desarrollo)

## Descripcion General

Arte Dental centraliza las operaciones principales de una clinica dental:

- Registro y mantenimiento de pacientes.
- Programacion, reprogramacion, cancelacion y atencion de citas.
- Gestion de odontologos, asistentes, horarios, disponibilidades y ausencias.
- Historias clinicas por paciente y notas clinicas asociadas.
- Modulo financiero para registro y consulta de pagos.
- Paneles para procesos estrategicos, operativos y de soporte.
- Administracion de usuarios con roles.

La aplicacion usa rutas protegidas por sesion y rol, por lo que el acceso depende del tipo de usuario autenticado.

## Caracteristicas Principales

- Login, registro, recuperacion de contrasena y gestion de perfil con Laravel Breeze.
- Control de acceso con roles: `admin`, `recepcionista`, `odontologo`, `asistente` y `paciente`.
- CRUD de pacientes.
- CRUD de citas con acciones especiales:
  - Reprogramar cita.
  - Cancelar cita.
  - Check-in de paciente.
  - Atender cita.
  - Consulta de odontologos disponibles.
- Configuracion de odontologos:
  - Disponibilidad semanal.
  - Ausencias.
  - Asignacion de asistentes.
  - Horarios fijos.
- Gestion de historias clinicas y notas clinicas.
- Gestion de pagos en el modulo de finanzas.
- Vistas para procesos estrategicos, operativos y de soporte.
- Soporte para ejecucion local o mediante Docker.

## Tecnologias

### Backend

- PHP `^8.2`
- Laravel `^12.0`
- Laravel Breeze
- Spatie Laravel Permission
- Eloquent ORM
- MySQL

### Frontend

- Blade
- Tailwind CSS
- Alpine.js
- Vite
- Axios

### Desarrollo y pruebas

- Composer
- NPM
- PHPUnit
- Laravel Pint
- Docker y Docker Compose
- phpMyAdmin

## Requisitos

Para ejecucion local sin Docker:

- PHP 8.2 o superior
- Composer
- Node.js y NPM
- MySQL
- Extension PHP para MySQL: `pdo_mysql`

Para ejecucion con Docker:

- Docker
- Docker Compose

## Instalacion Local

1. Clonar o abrir el proyecto:

```bash
cd arte-dental
```

2. Instalar dependencias de PHP:

```bash
composer install
```

3. Instalar dependencias de Node:

```bash
npm install
```

4. Crear archivo de entorno:

```bash
copy .env.example .env
```

En PowerShell tambien puedes usar:

```powershell
Copy-Item .env.example .env
```

5. Generar la clave de Laravel:

```bash
php artisan key:generate
```

6. Configurar la base de datos en `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=artedental
DB_USERNAME=root
DB_PASSWORD=
```

7. Ejecutar migraciones y seeders:

```bash
php artisan migrate --seed
```

Si ya tienes tablas creadas y quieres reiniciar la base de datos:

```bash
php artisan migrate:fresh --seed
```

8. Levantar el servidor de Laravel:

```bash
php artisan serve
```

9. En otra terminal, levantar Vite:

```bash
npm run dev
```

10. Abrir la aplicacion:

```text
http://127.0.0.1:8000
```

## Ejecucion con Docker

El proyecto incluye `docker-compose.yml` con tres servicios:

- `app`: aplicacion Laravel en PHP 8.2.
- `db`: base de datos MySQL 8.
- `phpmyadmin`: administrador web para MySQL.

1. Construir y levantar contenedores:

```bash
docker compose up --build
```

2. Abrir la aplicacion:

```text
http://localhost:8000
```

3. Abrir phpMyAdmin:

```text
http://localhost:8080
```

4. Credenciales de base de datos en Docker:

```text
Host interno: db
Base de datos: artedental
Usuario: laravel
Contrasena: laravel
Root password: root
Puerto local MySQL: 3307
```

5. Ejecutar comandos dentro del contenedor:

```bash
docker compose exec app php artisan migrate --seed
docker compose exec app php artisan test
docker compose exec app php artisan cache:clear
```

6. Apagar contenedores:

```bash
docker compose down
```

7. Apagar y eliminar el volumen de base de datos:

```bash
docker compose down -v
```

## Credenciales Iniciales

El seeder `RolesAndAdminSeeder` crea los roles base y un usuario administrador:

```text
Email: admin@artedental.pe
Password: Admin#123
Rol: admin
```

Para cargar estos datos:

```bash
php artisan db:seed --class=RolesAndAdminSeeder
```

O junto con las migraciones:

```bash
php artisan migrate:fresh --seed
```

## Comandos Utiles

### Desarrollo

```bash
php artisan serve
npm run dev
```

Tambien existe un comando Composer que levanta servidor, cola, logs y Vite al mismo tiempo:

```bash
composer run dev
```

### Base de datos

```bash
php artisan migrate
php artisan migrate --seed
php artisan migrate:fresh --seed
php artisan db:seed
php artisan db:seed --class=RolesAndAdminSeeder
```

### Cache y configuracion

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```

### Rutas

```bash
php artisan route:list
php artisan route:list --path=citas
php artisan route:list --path=pacientes
```

### Frontend

```bash
npm run dev
npm run build
```

### Calidad y pruebas

```bash
php artisan test
composer test
vendor/bin/pint
```

## Estructura Del Proyecto

```text
app/
  Http/
    Controllers/      Controladores de modulos y autenticacion
    Requests/         Validaciones personalizadas
  Models/             Modelos Eloquent del sistema

database/
  migrations/         Estructura de tablas
  seeders/            Roles y usuario administrador inicial
  factories/          Factories para pruebas

resources/
  views/              Vistas Blade
  css/                Estilos principales
  js/                 JavaScript de la aplicacion

routes/
  web.php             Rutas web protegidas por autenticacion y roles
  auth.php            Rutas de autenticacion de Laravel Breeze

public/
  images/             Logo, imagenes y recursos visuales
  videos/             Video de fondo para login

config/
  permission.php      Configuracion de Spatie Permission

nginx/
  conf.d/             Configuracion de Nginx para despliegues
```

## Modulos Del Sistema

### Dashboard

Controlador: `DashboardController`

Vista principal despues del login. Resume informacion operativa y funciona como punto de entrada del sistema.

### Pacientes

Controlador: `PacienteController`

Permite crear, listar, editar y eliminar pacientes. El modelo `Paciente` incluye relaciones con citas e historia clinica.

Rutas principales:

```text
GET    /pacientes
GET    /pacientes/create
POST   /pacientes
GET    /pacientes/{paciente}/edit
PUT    /pacientes/{paciente}
DELETE /pacientes/{paciente}
```

### Citas

Controlador: `CitaController`

Administra la agenda de atenciones. Incluye filtros, creacion, edicion y acciones propias del flujo de atencion.

Acciones destacadas:

```text
PATCH /citas/{cita}/reprogramar
PATCH /citas/{cita}/cancelar
PATCH /citas/{cita}/checkin
GET   /citas/{cita}/atender
GET   /citas/odontologos-disponibles
```

### RRHH

Controladores:

- `RRHHController`
- `OdontologoConfigController`
- `HorarioController`

Gestiona informacion relacionada con odontologos y asistentes:

- Disponibilidades.
- Ausencias.
- Asignacion de asistentes.
- Horarios fijos.

### Historias Clinicas

Controladores:

- `HistoriaClinicaController`
- `NotaClinicaController`

Permite crear y consultar historias clinicas por paciente, actualizar datos clinicos, cerrar historias y registrar notas clinicas.

Rutas destacadas:

```text
GET  /pacientes/{paciente}/historia
GET  /pacientes/{paciente}/historia/crear
POST /pacientes/{paciente}/historia
PUT  /historias/{historia}
PUT  /historias/{historia}/cerrar
POST /historias/{historia}/notas
```

### Finanzas

Controladores:

- `Soporte\FinanzasController`
- `Soporte\PagoController`

Permite registrar pagos y consultar pagos registrados.

Rutas destacadas:

```text
GET  /soporte/finanzas
GET  /soporte/finanzas/pagos/crear
POST /soporte/finanzas/pagos
GET  /soporte/finanzas/pagos/registrados
```

### Administracion

Controlador: `Soporte\AdministracionController`

Modulo de apoyo administrativo disponible para usuarios autorizados.

### Usuarios

Controlador: `UserController`

CRUD de usuarios protegido para el rol `admin`.

Rutas principales:

```text
GET    /users
GET    /users/create
POST   /users
GET    /users/{user}/edit
PUT    /users/{user}
DELETE /users/{user}
```

### Procesos Estrategicos

Controlador: `EstrategicoController`

Vistas informativas o de gestion para:

- Planeamiento.
- Calidad.
- Innovacion.

### Procesos Operativos

Controlador: `OperativosController`

Vistas para el flujo clinico:

- Diagnostico.
- Tratamiento.
- Seguimiento.

## Roles Y Permisos

El proyecto usa Spatie Permission. Los roles base son:

```text
admin
recepcionista
odontologo
asistente
paciente
```

Acceso general protegido:

```text
admin | recepcionista | odontologo | asistente
```

Accesos especificos:

| Modulo | Roles |
| --- | --- |
| Dashboard | admin, recepcionista, odontologo, asistente |
| Pacientes | admin, recepcionista, odontologo, asistente |
| Citas | admin, recepcionista, odontologo, asistente |
| RRHH | admin, recepcionista |
| Configuracion de odontologos | admin, recepcionista |
| Horarios de odontologos | admin, recepcionista |
| Finanzas | admin, recepcionista |
| Administracion | admin, recepcionista, asistente |
| Usuarios | admin |

## Base De Datos

Tablas principales creadas por migraciones:

- `users`
- `roles`, `permissions` y tablas pivot de Spatie
- `pacientes`
- `citas`
- `odontologo_profiles`
- `odontologo_disponibilidades`
- `odontologo_ausencias`
- `odontologo_asistente`
- `horarios`
- `historias_clinicas`
- `notas_clinicas`
- `pagos`
- `cache`
- `jobs`

Relaciones importantes:

- Un paciente puede tener muchas citas.
- Un paciente puede tener una historia clinica.
- Una historia clinica puede tener muchas notas clinicas.
- Una cita pertenece a un paciente y a un odontologo.
- Un odontologo puede tener disponibilidades, ausencias, horarios y asistentes.
- Un pago pertenece a un paciente y al usuario que lo registro.

## Rutas Principales

| Ruta | Descripcion |
| --- | --- |
| `/` | Redirecciona al dashboard |
| `/dashboard` | Panel principal |
| `/profile` | Perfil del usuario |
| `/pacientes` | Gestion de pacientes |
| `/citas` | Gestion de citas |
| `/rrhh` | Modulo de recursos humanos |
| `/odontologos/config` | Configuracion de odontologos |
| `/odontologos/horarios` | Horarios fijos de odontologos |
| `/estrategicos` | Procesos estrategicos |
| `/operativos/diagnostico` | Proceso operativo de diagnostico |
| `/operativos/tratamiento` | Proceso operativo de tratamiento |
| `/operativos/seguimiento` | Proceso operativo de seguimiento |
| `/soporte/finanzas` | Modulo financiero |
| `/soporte/administracion` | Modulo administrativo |
| `/users` | Administracion de usuarios |

Para ver todas las rutas disponibles:

```bash
php artisan route:list
```

## Pruebas

Ejecutar todas las pruebas:

```bash
php artisan test
```

O usando el script de Composer:

```bash
composer test
```

Las pruebas se encuentran en:

```text
tests/Feature
tests/Unit
```

## Notas De Desarrollo

- La mayoria de rutas del sistema estan protegidas por `auth` y middleware de roles.
- Las rutas de autenticacion estan separadas en `routes/auth.php`.
- Los assets frontend se compilan con Vite.
- Si cambias rutas, vistas o configuracion y algo no se refleja, limpia cache con:

```bash
php artisan optimize:clear
```

- Si agregas nuevos roles o permisos, revisa:

```text
database/seeders/RolesAndAdminSeeder.php
config/permission.php
routes/web.php
```

## Licencia

Proyecto academico desarrollado sobre Laravel.
