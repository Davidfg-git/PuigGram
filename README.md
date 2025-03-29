# PuigGram

PuigGram es una aplicación web sencilla para compartir fotos, inspirada en plataformas populares de redes sociales. Permite a los usuarios subir, compartir y visualizar fotos de manera fácil y rápida.

## Características

- **Autenticación de usuarios**: Registro e inicio de sesión seguro.
- **Gestión de perfiles**: Personalización de perfiles de usuario.
- **Subida y compartición de fotos**: Los usuarios pueden subir imágenes y compartirlas con otros.
- **Feed de fotos**: Visualización de las fotos compartidas por otros usuarios.
- **Interacciones sociales**: Posibilidad de dar "me gusta" y comentar en las fotos.

## Requisitos previos

Antes de comenzar, asegúrate de tener lo siguiente instalado en tu sistema:

- Un servidor local como [XAMPP](https://www.apachefriends.org/) o [WAMP](https://www.wampserver.com/).
- PHP (versión 7.4 o superior).
- MySQL o MariaDB para la base de datos.
- Un navegador web moderno.

## Instalación

Sigue estos pasos para configurar el proyecto en tu entorno local:

1. Clona el repositorio:
    ```bash
    git clone https://github.com/tu-usuario/PuigGram.git
    ```

2. Navega al directorio del proyecto:
    ```bash
    cd PuigGram
    ```

3. Configura tu servidor local (por ejemplo, XAMPP) y asegúrate de que Apache y MySQL estén activos.

4. Importa el archivo SQL proporcionado en tu base de datos:
    - Abre phpMyAdmin o tu herramienta de gestión de bases de datos preferida.
    - Crea una nueva base de datos (por ejemplo, `puiggram`).
    - Importa el archivo `puiggram.sql` incluido en el proyecto.

5. Actualiza la configuración de conexión a la base de datos en el archivo de configuración del proyecto:
    - Abre el archivo `config.php` (o equivalente).
    - Asegúrate de que los valores de host, usuario, contraseña y nombre de la base de datos sean correctos.

## Uso

1. Inicia tu servidor local.
2. Abre la aplicación en tu navegador web:
    ```
    http://localhost/PuigGram
    ```
3. Regístrate o inicia sesión para comenzar a compartir fotos.

## Estructura del Proyecto

- **`/assets`**: Contiene los archivos estáticos como CSS, JavaScript e imágenes.
- **`/includes`**: Archivos PHP reutilizables como encabezados y pies de página.
- **`/uploads`**: Carpeta donde se almacenan las fotos subidas por los usuarios.
- **`config.php`**: Archivo de configuración para la conexión a la base de datos.
- **`index.php`**: Página principal de la aplicación.

## Contribuciones

¡Las contribuciones son bienvenidas! Si deseas colaborar, sigue estos pasos:

1. Haz un fork del repositorio.
2. Crea una nueva rama para tu funcionalidad o corrección:
    ```bash
    git checkout -b nombre-de-tu-rama
    ```
3. Realiza tus cambios y haz un commit:
    ```bash
    git commit -m "Descripción de los cambios"
    ```
4. Envía tus cambios al repositorio remoto:
    ```bash
    git push origin nombre-de-tu-rama
    ```
5. Abre un pull request en GitHub.

## Licencia

Este proyecto está licenciado bajo la Licencia MIT. Consulta el archivo `LICENSE` para más detalles.

## Contacto

Si tienes preguntas o sugerencias, no dudes en ponerte en contacto con nosotros a través de [tu-email@ejemplo.com](mailto:tu-email@ejemplo.com).

¡Gracias por usar PuigGram!