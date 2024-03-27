# Sistema de Gestión de Cursos

Este proyecto es una API implementada en Laravel para gestionar cursos, ediciones de cursos y empleados. Proporciona funcionalidades como autenticación, CRUD para cursos, empleados y ediciones, y consultas específicas para obtener información sobre los empleados y las ediciones.

## Requerimientos

- PHP >= 7.4
- Composer
- Laravel >= 10
- Base de datos compatible con Laravel (MySQL, PostgreSQL, SQLite, etc.)

## Instalación

1. Clona el repositorio:

```
git clone https://github.com/DDansAbelenda/sg-cursos-api.git
```

2. Instala las dependencias del proyecto con Composer:

```
composer install
```

3. Configura las variables de entorno creando un archivo `.env` basado en el archivo `.env.example`. Asegúrate de configurar las siguientes variables de entorno:

```
SESSION_DRIVER=cookie
SESSION_DOMAIN=localhost
SANCTUM_STATEFUL_DOMAINS=localhost:8080
```

4. Ejecuta las migraciones para crear la estructura de la base de datos:

```
php artisan migrate
```

5. Opcional: Si deseas poblar la base de datos con datos de prueba, ejecuta el seeder:

```
php artisan db:seed --class=DatabaseSeeder
```

## Uso

### Autenticación

- Para iniciar sesión, haz una solicitud POST a `/auth/login` con las credenciales de inicio de sesión.

```
POST /auth/login
{
    "email": "admin@example.com",
    "password": "password"
}
```

- Para cerrar sesión, haz una solicitud GET a `/auth/logout`.

```
GET /auth/logout
```

### CRUD

- Cursos: CRUD completo disponible en `/course`.
- Empleados: CRUD completo disponible en `/employee`.
- Ediciones: CRUD completo disponible en `/edition`.

### Consultas

- Determinar si un empleado es profesor: Envía una solicitud GET a `/isprofessor/{employee}`.
- Obtener todos los empleados profesores: Envía una solicitud GET a `/professor`.
- Obtener todos los cursos de un empleado: Envía una solicitud GET a `/employeeall/{employee}`.

## Documentación

- [Documentación de Laravel](https://laravel.com/docs)
- [Documentación de Laravel Sanctum](https://laravel.com/docs/10.x/sanctum)

## Capturas de pantalla

(Inserta aquí algunas capturas de pantalla de la aplicación en funcionamiento, si están disponibles)

## Contribuciones

¡Las contribuciones son bienvenidas! Si deseas contribuir a este proyecto, por favor abre un problema o envía una solicitud de extracción.

## Licencia

Este proyecto está bajo la Licencia MIT. Consulta el archivo [MIT license](https://opensource.org/licenses/MIT). para obtener más detalles.
