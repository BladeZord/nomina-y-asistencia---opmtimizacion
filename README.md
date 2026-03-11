# Control de Asistencia y Sistema de Nómina

Sistema PHP para registro de asistencia (entrada/salida) y administración de empleados, horarios, deducciones, anticipos, horas extra y nómina.

## Requisitos

- **PHP** 8.2+ (extensiones: mysqli)
- **MySQL** 5.7+
- **Apache** con mod_rewrite (o servidor equivalente)

## Configuración

### Variables de entorno

Puede configurar la aplicación mediante variables de entorno. En Docker se definen en `docker-compose.yml`; en otro entorno puede usar un archivo `.env` (no incluido en el repositorio).

Copie `.env.example` a `.env` y ajuste los valores:

| Variable       | Descripción              | Por defecto                          |
|----------------|--------------------------|--------------------------------------|
| `DB_HOST`      | Host de la base de datos | `localhost`                          |
| `DB_USER`      | Usuario MySQL            | `root`                               |
| `DB_PASSWORD`  | Contraseña MySQL         | (vacío)                              |
| `DB_NAME`      | Nombre de la base        | `apsystem`                           |
| `APP_NAME`     | Nombre de la aplicación  | Control de Asistencia y Sistema de Nómina |
| `APP_TIMEZONE` | Zona horaria             | `America/Bogota`                     |
| `APP_DEBUG`    | Modo depuración (1/0)     | `0`                                  |

### Estructura de configuración

- **`config/app.php`** – Nombre de la app, zona horaria, charset y modo debug.
- **`config/database.php`** – Conexión MySQL (una sola instancia para todo el proyecto).
- **`config/init.php`** – Carga app, base de datos y helpers (incluir una vez por request).
- **`config/helpers.php`** – Funciones globales: `h()` (escape HTML), `csrf_token()`, `csrf_field()`, `validate_csrf()`, `handle_photo_upload()`.

La conexión a la base de datos y la zona horaria están centralizadas: no use archivos duplicados de conexión ni `timezone.php` salvo por compatibilidad (este ya delega en `config/app.php`).

## Ejecución con Docker

```bash
docker compose up -d
```

- Aplicación: http://localhost:9001  
- Login público (asistencia): raíz del sitio.  
- Panel admin: http://localhost:9001/admin/ (credenciales según `database/apsystem.sql`).

## Estructura del proyecto (resumida)

```
/
├── config/           # Configuración central (app, DB, helpers)
├── admin/            # Panel de administración
│   ├── includes/     # Cabecera, pie, navbar, menú, modales, session, alertas
│   ├── *.php         # CRUD: empleados, posiciones, horarios, asistencia, nómina, etc.
├── database/         # Esquema SQL inicial
├── images/           # Fotos de empleados (subidas)
├── index.php         # Pantalla pública de registro de asistencia
├── attendance.php    # API de registro de entrada/salida (POST)
├── conn.php          # Carga config/init (conexión + timezone)
├── timezone.php      # Delega en config/app.php
├── .env.example      # Plantilla de variables de entorno
└── README.md
```

## Seguridad implementada

- **Prepared statements** en login, sesión, asistencia y CRUD de empleados (evita inyección SQL).
- **Escape de salida** con `h()` en vistas para reducir riesgo XSS.
- **Tokens CSRF** en formularios críticos (login, alta/edición/borrado de empleados, foto).
- **Subida de fotos** restringida a extensiones permitidas y nombres aleatorios (sin ejecución en `images/`).

## Nuevos requerimientos / mantenimiento

- Añadir nuevas pantallas: reutilizar `includes/session.php`, `includes/header.php`, `includes/alerts.php` y `includes/footer.php`; usar `csrf_field()` y `validate_csrf()` en formularios; consultas con prepared statements.
- Cambiar zona horaria o nombre de la app: usar variables de entorno o editar `config/app.php`.
- Añadir más opciones configurables: definir constantes o arrays en `config/app.php` y leer desde ahí en el código.
