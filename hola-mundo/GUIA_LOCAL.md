# Hola mundo en Laravel

Ejercicio de la guía básica: rutas, controlador y vista Blade, con la práctica de carrera y bienvenida.

## Ejecutar en PowerShell

Abre una terminal en esta carpeta y ejecuta los comandos uno por uno:

~~~powershell
php artisan --version
php artisan route:list
php artisan serve
~~~

Visita http://127.0.0.1:8000/hola. Las rutas /, /hola y /bienvenida muestran la misma página.
Conserva abierta la terminal del servidor y usa Ctrl+C para detenerlo.
Si el puerto 8000 está ocupado, ejecuta php artisan serve --port=8001 y abre ese puerto.
No necesitas iniciar MySQL ni ejecutar npm para este ejercicio.
Usa dos guiones normales en --version y --port.

## Personalizar

Edita $nombre, $mensaje, $asignatura y $carrera en app/Http/Controllers/SaludoController.php.
El nombre configurado es Fernando. El aspecto de la página está en resources/views/hola.blade.php.

## Comprobar

~~~powershell
php artisan test
~~~

Las pruebas comprueban que las tres rutas responden y muestran los datos procesados por Blade.
La configuración local utiliza archivos para sesiones y caché, y ejecución síncrona de colas.

## Recorrido de la petición

El navegador envía GET /hola; routes/web.php selecciona SaludoController@index.
El controlador prepara los datos y llama a view('hola', [...]). Blade procesa
resources/views/hola.blade.php y Laravel devuelve HTML al navegador.

Para la entrega conserva .gitignore y excluye .env, vendor y node_modules.
La publicación en GitHub y la entrega en Moodle se realizan con tu cuenta y repositorio.
