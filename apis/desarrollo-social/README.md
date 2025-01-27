# API REST Desarrollo Social

Este proyecto está construido en **Laravel 10**. Para su correcto funcionamiento se requieren los siguientes elementos:

## Lenguaje

- **PHP 8.3 TS**

## Extensiones del lenguaje requeridas

- `ctype`
- `curl`
- `exif`
- `fileinfo`
- `gd`
- `hash`
- `intl`
- `mbstring`
- `oci8_19`
- `openssl`
- `pcre`
- `session`
- `tokenizer`
- `xml`
- `xsl`
- `zip`
- `dom`

## Dependencias adicionales

- **Composer** v2.8.5 o superior: Para el manejo de paquetes.
- **Oracle Instant Client** v23: Para la conexión con la base de datos.

---

## Guía de preparación del proyecto

Para utilizar el proyecto en producción, se deben ejecutar los siguientes comandos:

### 1. Instalar dependencias
 
    composer install
Instala todas las librerias del archivo composer.json

    php artisan key:generate
Genear auna nueva clave con la que se encripta el proyecto.

    php artisan storage:link
Crear un enlace simbolico en la carpeta public hacia la carpeta storage/app

## Crear llaves privadas y publicas

Primero se crea un directorio llamado keys dentro del directorio storage y se ingresa a el, despues de ejecutan los siguientes comandos para crear las llaves dentro del directorio anteriomente mencionado.

    openssl genpkey -algorithm RSA -out private_key.pem -aes256 
Crear la clave privada y necesita contraseña para encriptarla

    openssl rsa -in private_key.pem -pubout -out public_key.pem
Extrae la clave publica de la privada

los nombres de las llaves son:
- `private_key.pem`
- `public_key.pem`

## Permisos de lectura y escritura

Al proyecto se le dan permisos de lectura y escritura a las carpetas 

- `bootstrap y subdirectorios`
- `storage/logs/`










