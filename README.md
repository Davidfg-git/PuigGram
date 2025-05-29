# PuigGram

PuigGram es una aplicación web para compartir fotos, inspirada en redes sociales modernas. Permite a los usuarios registrarse, iniciar sesión, personalizar su perfil, subir imágenes, explorar publicaciones y más.

---

## Características principales

- **Registro e inicio de sesión de usuarios**  
  Autenticación segura con sesiones PHP.
- **Gestión de perfiles**  
  Cambia tu nombre, nombre de usuario, presentación y foto de perfil (la imagen se almacena en `/public/images/profile` y la ruta en la base de datos).
- **Subida y publicación de imágenes**  
  Los usuarios pueden subir imágenes (JPG, PNG) que se almacenan en `/public/assets/uploads/{user_id}/` y la ruta se guarda en la base de datos.
- **Feed y exploración**  
  Visualiza publicaciones propias y de otros usuarios.
- **Interacciones sociales**  
  Sugerencias de usuarios, posibilidad de seguir a otros (estructura preparada).
- **Configuración y privacidad**  
  Cambia tu contraseña, elimina tu cuenta y ajusta la privacidad desde la sección de configuración.
- **Política de privacidad**  
  Página dedicada con información sobre el tratamiento de datos.

---

## Requisitos previos

- [XAMPP](https://www.apachefriends.org/) o similar (Apache + MySQL)
- PHP 7.4 o superior
- MySQL/MariaDB
- Navegador web moderno

---

## Instalación

1. **Clona el repositorio:**
    ```bash
    git clone https://github.com/tu-usuario/PuigGram.git
    ```
2. **Coloca la carpeta en tu directorio de XAMPP:**  
   Ejemplo: `C:\Users\david\Desktop\XAMPP\htdocs\PuigGram`
3. **Configura la base de datos:**
    - Inicia Apache y MySQL desde XAMPP.
    - Crea la base de datos `PuigGram` desde phpMyAdmin.
    - Importa el script `backend/db/ScriptCreacionBD.sql`.
4. **Configura la conexión en `backend/db/db.php`:**
    - Ajusta usuario, contraseña y nombre de la base de datos si es necesario.
5. **Asegúrate de que las carpetas de imágenes existen:**
    - `/public/images/profile/` para fotos de perfil.
    - `/public/assets/uploads/` para publicaciones.
    - Crea estas carpetas si no existen y otorga permisos de escritura.

---

## Estructura del Proyecto

```
PuigGram/
│
├── backend/
│   ├── db/
│   │   ├── db.php
│   │   └── ScriptCreacionBD.sql
│   └── php/
│       ├── index.php
│       ├── register.php
│       ├── profile.php
│       ├── changeProfile.php
│       ├── usuariosDAO.php
│       ├── publicacionesDAO.php
│       ├── publish.php
│       ├── uploadImage.php
│       ├── settings.html
│       ├── changePassword.html
│       ├── explore.html
│       ├── mainPage.html
│       ├── messages.html
│       ├── notifications.html
│       ├── privacyPolicy.html
│       └── ...
│
├── public/
│   ├── assets/
│   │   ├── styles/
│   │   │   └── mainStyle.css
│   │   ├── default/
│   │   │   └── default-image.jpg
│   │   └── uploads/
│   │       └── {user_id}/
│   └── images/
│       └── profile/
│
├── README.md
└── LICENSE
```

---

## Uso

1. Inicia tu servidor local (XAMPP).
2. Abre en tu navegador:  
   ```
   http://localhost/PuigGram/backend/php/index.php
   ```
3. Regístrate o inicia sesión para comenzar a compartir fotos.

---

## Notas técnicas

- **Fotos de perfil:**  
  Se suben a `/public/images/profile/` y solo se guarda la ruta en la base de datos (`imagen_perfil` tipo VARCHAR).
- **Publicaciones:**  
  Las imágenes se suben a `/public/assets/uploads/{user_id}/` y la ruta se guarda en la base de datos.
- **Configuración de PHP en VS Code:**  
  Si usas Windows y tienes PHP en tu escritorio, configura en tu `settings.json`:
  ```json
  "php.validate.executablePath": "C:\\Users\\david\\Desktop\\php\\php.exe"
  ```
- **Seguridad:**  
  Las contraseñas actualmente se almacenan en texto plano (mejora recomendada: usar `password_hash` y `password_verify`).
- **Sugerencias y seguidores:**  
  El sistema de sugerencias está preparado para mostrar usuarios, pero puedes expandirlo para implementar seguidores reales.

---

## Contribuciones

¡Las contribuciones son bienvenidas!  
1. Haz un fork del repositorio.
2. Crea una rama para tu funcionalidad:
    ```bash
    git checkout -b nombre-de-tu-rama
    ```
3. Realiza tus cambios y haz commit:
    ```bash
    git commit -m "Descripción de los cambios"
    ```
4. Envía tus cambios:
    ```bash
    git push origin nombre-de-tu-rama
    ```
5. Abre un pull request en GitHub.

---

## Licencia

Este proyecto está licenciado bajo la Licencia MIT. Consulta el archivo `LICENSE` para más detalles.

---

## Contacto

¿Dudas o sugerencias?  
Contáctanos a través de [tu-email@ejemplo.com](mailto:tu-email@ejemplo.com).

---

¡Gracias por usar PuigGram!