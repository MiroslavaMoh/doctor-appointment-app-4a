# Doctor Appointment App miros

Aplicación web para la gestión de citas médicas, construida con **Laravel 10** y usando **Jetstream** para autenticación y perfiles de usuario.

---

## Tecnologías

- **Backend:** Laravel 10, PHP 8.2  
- **Frontend:** Blade + Tailwind CSS, Flowbite  
- **Base de datos:** MySQL/PostgreSQL (configurable en `.env`)  
- **Almacenamiento de archivos:** `public/storage` para fotos de perfil y otros assets

---

## Instalación general

1. Clona el repositorio:
    git clone <REPO_URL>

2. Instala dependencias de PHP:
    composer install

3. Configura variables de entorno:
    cp .env.example .env
    php artisan key:generate

4. Configura la base de datos en .env.

5. Ejecuta migraciones:
    php artisan migrate

6. Crea el symlink para archivos públicos (Windows suele tener problemas con esto, afectapara visualizar elementos de la base de dataos asi como la imagen del perfil)
    php artisan storage:link

7. Inicia el servidor:
    php artisan serve


**#La aplicación estará disponible en: http://127.0.0.1:8000**

## Documentación de Integración addicional
1. Crear el nuevo layout: Se creó un archivo Blade para el layout de administrador:
    resources/views/layouts/admin.blade.php
 
2.  Integrar Flowbite: Se instaló Flowbite usando npm
    npm install flowbite --save
De no tenerlo ejecutar 
    npm install
    npm run build


    