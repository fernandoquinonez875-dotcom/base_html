# Personas Laravel — Guía 1: Login

Práctica de inicio y cierre de sesión con Laravel 13, vistas Blade y un usuario en MySQL.
Las sesiones y la caché se guardan en archivos del servidor. Esta guía llega hasta la pantalla
Inicio; el CRUD y la tabla de personas corresponden a la guía 2.

## Preparar la computadora

Requisitos: PHP 8.3 o superior compatible con Laravel 13, Composer 2, MySQL y la extensión
`pdo_mysql`. En Windows se puede utilizar PHP y MySQL de XAMPP.

Desde `base_html/personas-laravel`, instalar las dependencias y crear la configuración local
solo si todavía no existe:

```powershell
composer install
if (-not (Test-Path .env)) { Copy-Item .env.example .env }
```

La plantilla `.env.example` utiliza MySQL en `127.0.0.1:3306`, base `personas_laravel`,
usuario `root` y contraseña vacía como ejemplo local de XAMPP. Ajustar `DB_USERNAME` y
`DB_PASSWORD` en `.env` a la instalación utilizada. No definir una `DB_URL` que sustituya
estos valores. `SESSION_DRIVER=file`, `CACHE_STORE=file` y `QUEUE_CONNECTION=sync`
corresponden a la configuración indicada en la guía.

Iniciar MySQL y crear la base desde su cliente SQL:

```sql
CREATE DATABASE IF NOT EXISTS personas_laravel
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Si `APP_KEY` está vacío, ejecutar `php artisan key:generate` una sola vez. Conservar la clave
existente cuando el proyecto ya esté configurado. Después ejecutar:

```powershell
php artisan config:clear
php artisan migrate
php artisan migrate:status
php artisan db:seed
php artisan serve
```

Abrir `http://127.0.0.1:8000/login`. Estas credenciales de demostración provienen de la guía:

| Campo | Valor |
| --- | --- |
| Correo | `admin@clase.test` |
| Contraseña | `Clase2026!` |

No se necesita compilar recursos con npm: las vistas utilizan Blade y el CSS local de la
plantilla compartida. Para detener el servidor, presionar Ctrl+C en su terminal.

## Explicación de los conceptos solicitados

`php artisan migrate` prepara la estructura de la base de datos. Ejecuta las migraciones que
faltan y anota su ejecución en la tabla `migrations`. Por ejemplo, la migración incluida por
Laravel crea `users` con `id`, `name`, `email`, `password` y las fechas de creación y actualización.
Crear la tabla no crea automáticamente al usuario de práctica. Se conserva la migración
original de `users`; no hace falta crear otra ni borrar las tablas.

`php artisan db:seed` ejecuta las clases que cargan datos iniciales. `DatabaseSeeder` llama a
`UsuarioDemoSeeder`, que busca `admin@clase.test` y lo crea o actualiza. Así se puede repetir
el comando sin duplicar esa cuenta. Repetirlo restablece su nombre y contraseña de práctica;
no ejecuta migraciones pendientes.

La columna `password` guarda un hash, no la contraseña original. `Hash::make` calcula ese
valor de una sola dirección; no se descifra para recuperar la clave. Durante el login,
`Auth::attempt` comprueba la contraseña recibida contra el hash almacenado. Por eso se envía
la contraseña original del formulario a `Auth::attempt`, sin generar otro hash antes.

## Comprobación de la práctica

- Sin sesión, abrir `/inicio`: debe redirigir a `/login`.
- Usar una contraseña incorrecta: debe aparecer «Correo o contraseña incorrectos.» y continuar
  sin sesión. El formulario conserva el correo, pero no la contraseña.
- Usar las credenciales del seeder: debe abrir Inicio y mostrar el nombre del usuario.
- Recargar Inicio: la sesión debe continuar activa.
- Cerrar sesión y volver a `/inicio`: debe pedir login otra vez.
- En MySQL, comprobar las tablas `users` y `migrations`; `password` debe contener un hash.

Las pruebas automatizadas verifican además los campos obligatorios, el formato del correo,
la repetición del seeder, el escape del nombre en Blade y el límite de seis intentos por minuto:

```powershell
php artisan test --compact
```

La suite utiliza SQLite en memoria para aislar las pruebas; la ejecución local de la aplicación
utiliza MySQL. Las pruebas no borran ni modifican la base MySQL de la práctica.

## Entrega en GitHub

Publicar el código con el mensaje de commit `Login`, como solicita la guía. `.gitignore` excluye
`.env`, `vendor` y `node_modules`, además de archivos temporales. `.env.example` se publica
sin claves ni contraseñas reales. No se necesita cambiar los commits de clase para publicar
el trabajo de casa como un commit posterior.
