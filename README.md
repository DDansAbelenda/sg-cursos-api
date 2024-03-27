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

3. Configura las variables de entorno creando un archivo `.env` a partir de una copia de `.env.example`. Asegúrate de configurar las siguientes variables de entorno al final del fichero:
```
SESSION_DRIVER=cookie
SESSION_DOMAIN=localhost
SANCTUM_STATEFUL_DOMAINS=localhost:8080
``` 
Además se debe sobreescribir la siguiente variable ubicada en la parte superior  para evitar errores:

```
APP_KEY=base64:PAjRyROTHQ9EX2xv2uIG7olDegJ1qmevcptNFadJq3I=
```
    

4. Configurar las variables de entorno de la base datos, no hay ningún sistema gestor de base datos de preferencia, puede utilizar cualquiera de los disponibles. Ejemplo de una configuración para postgres sería:

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=sg_cursos_api
DB_USERNAME=postgres
DB_PASSWORD=password
```
5. Crear de forma manual la base de datos en el gestor seleccionado.

6. Ejecuta las migraciones para crear la estructura de la base de datos:

```
php artisan migrate
```
Esto por lo general abre un servicio en `http:localhost:8000` aunque debe verificar el que se muestra en consola

7. Opcional: Si deseas poblar la base de datos con datos de prueba, ejecuta el seeder:

```
php artisan db:seed --class=DatabaseSeeder
```

8. Para iniciar la aplicación utilizarl el comando:

```
php artisan serve
```
## Uso

### Autenticación

- Para iniciar sesión, haz una solicitud POST a `/auth/login` con las credenciales de inicio de sesión.

```
POST /api/auth/login
{
    "email": "admin@example.com",
    "password": "password"
}
```

- Para cerrar sesión, haz una solicitud GET a `/auth/logout`.

```
GET /api/auth/logout
```

### CRUD

- Cursos: CRUD completo disponible en `/api/course`.
- Empleados: CRUD completo disponible en `/api/employee`.
- Ediciones: CRUD completo disponible en `/api/edition`.

### Consultas

- Determinar si un empleado es profesor: Envía una solicitud GET a `/api/isprofessor/{employee}`.
- Obtener todos los empleados profesores: Envía una solicitud GET a `/api/professor`.
- Obtener todos los cursos de un empleado: Envía una solicitud GET a `/api/employeeall/{employee}`.

## Documentación

- [Documentación de Laravel](https://laravel.com/docs)
- [Documentación de Laravel Sanctum](https://laravel.com/docs/10.x/sanctum)

## Contribuciones

¡Las contribuciones son bienvenidas! Si deseas contribuir a este proyecto, por favor abre un problema o envía una solicitud de extracción.

## Licencia

Este proyecto está bajo la Licencia MIT. Consulta el archivo [MIT license](https://opensource.org/licenses/MIT). para obtener más detalles.
