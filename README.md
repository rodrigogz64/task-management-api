# Task Management API

Una API RESTful desarrollada con Laravel para gestionar tareas, permitiendo a los usuarios crear, asignar y gestionar tareas, con soporte para comentarios, seguimiento de tiempo y adjuntos de archivos.

## Requisitos

- PHP >= 8.1
- Composer
- MySQL o cualquier base de datos compatible con Laravel

## Instalación

1. Clonar el repositorio:
   ```bash
   git clone https://github.com/tu-usuario/task-management-api.git
   cd task-management-api

##

2. Instalar dependencias:
    ```bash
    composer install
    ```

##

3. Configurar el archivo .env:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

##

4. Configurar la base de datos en el archivo .env:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_management
DB_USERNAME=root
DB_PASSWORD=

##

5. Ejecutar migraciones:
    ```bash
    php artisan migrate 
    ```

##

6. Configurar el almacenamiento para archivos:
    ```bash
    php artisan storage:link
    ```

##

7. Iniciar el servidor:
    ```bash
    php artisan serve
    ```

##

8. Endpoints de la API
* Autenticación

    - POST /api/register - Registrar un nuevo usuario
    - POST /api/login - Iniciar sesión
    - POST /api/logout - Cerrar sesión (requiere autenticación)

* Tareas
    - GET /api/tasks - Listar todas las tareas
    - POST /api/tasks - Crear una nueva tarea
    - GET /api/tasks/{id} - Ver una tarea específica
    - PUT /api/tasks/{id} - Actualizar una tarea
    - DELETE /api/tasks/{id} - Eliminar una tarea

* Comentarios

    - GET /api/tasks/{id}/comments - Listar todos los comentarios de una tarea
    - POST /api/tasks/{id}/comments - Agregar un comentario a una tarea

* Registro de tiempo

    - GET /api/tasks/{id}/time-log - Obtener todos los registros de tiempo para una tarea
    - POST /api/tasks/{id}/time-log - Registrar tiempo dedicado a una tarea

* Archivos

    - GET /api/tasks/{id}/files - Listar todos los archivos adjuntos a una tarea
    - POST /api/tasks/{id}/upload - Subir un archivo a una tarea

##

9. Reglas de negocio

    - Solo los usuarios autenticados pueden crear tareas.
    - Solo el creador o el usuario asignado pueden actualizar una tarea.
    - Solo el usuario asignado puede cambiar el estado de una tarea a "completada".
    - Cualquier usuario autenticado puede agregar comentarios.
    - Solo el usuario asignado puede registrar tiempo.
    - Solo el creador o el usuario asignado pueden subir archivos.
    - Solo el creador puede eliminar una tarea.