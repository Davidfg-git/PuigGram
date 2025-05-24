<?php
session_start();  // Iniciar sesión

if (isset($_GET['mensaje'])) {
    echo "<script>alert('" . htmlspecialchars($_GET['mensaje']) . "');</script>";
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="../../public/assets/styles/mainStyle.css">
    <script defer src="scripts.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">
 
</head>
<body>
    <div class="sidebar">
        <h1>PuigGram</h1>
        <ul>
            <li><a href="mainPage.html"><i class="bi bi-house"></i> Inicio</a></li>
            <li><a href="explore.html"><i class="bi bi-search"></i>Explorar</a></li>
            <li><a href="messages.html"><i class="bi bi-chat"></i>Mensajes</a></li>
            <li><a href="notifications.html"><i class="bi bi-bell"></i>Notificaciones</a></li>
            <li><a href="publish.html"><i class="bi bi-plus-square"></i>Publicar</a></li>
            <li><a href="profile.php"><i class="bi bi-person"></i>Perfil</a></li>
            <li><a href="settings.html"><i class="bi bi-gear"></i>Configuración</a></li>
        </ul>
    </div>

    <div class="main">
        <div class="post">
            <div class="post-header">
                <p class="inicioText">NUEVA PUBLICACIÓN</p>
            </div>
            
        </div>
    </div>
    <div class="contenido">
        
    <div class="ContenedorPublicacion">
        <div class="primeraSeleccion">
<div class="publicacion-container">
  <h2 class="titulo-publicacion">📸 Nueva publicación</h2>

  <form id="form-publicacion" action="../../backend/php/uploadImage.php" method="POST" enctype="multipart/form-data">
    <div class="upload-area">
      <label for="file-upload" class="upload-label">
        <div class="upload-icon">📤</div>
        <p class="upload-text">Haz clic o arrastra una imagen</p>
        <small class="formatos-info">Formatos admitidos: JPG, PNG</small>
        <input id="file-upload" name="imagen" type="file" accept="image/*" hidden required>
      </label>

      <div id="preview" class="preview-area"></div>
    </div>

    <!-- Este input oculto se rellena desde JS cuando se genera el textarea -->
    <input type="hidden" name="descripcion" id="descripcion-hidden">

    <button type="submit" class="btn-publicar">Publicar</button>
  </form>
</div>


        
        <!--<p class="selec_imgPub">Pon tus fotos o videos aquí</p><br>
        <div id="file-select" class="">
        <input type="file" id="file-input" accept="image/jpeg, video/mp4" class="subirImagen" style="display: none;">

<button type="button" class="botonera" onclick="document.getElementById('file-input').click();">
  SELECCIONAR IMAGEN O VIDEO
</button>-->
        </div>
        </div>
   
    </div>
    <div class="suggestions">
        <h2>Sugerencias</h2>
        <ul>
            <li>
                <img src="profile-placeholder.png" alt="Profile Picture">
                <span>@Usuario1</span>
                <button>Seguir</button>
            </li>
            <li>
                <img src="profile-placeholder.png" alt="Profile Picture">
                <span>@Usuario2</span>
                <button>Seguir</button>
            </li>
            <li>
                <img src="profile-placeholder.png" alt="Profile Picture">
                <span>@Usuario3</span>
                <button>Seguir</button>
            </li>
        </ul>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
  const fileInput = document.getElementById("file-upload");
  const previewArea = document.getElementById("preview");
  const uploadArea = document.querySelector(".upload-area");
  const form = document.getElementById("form-publicacion");
  const descripcionHidden = document.getElementById("descripcion-hidden");

  const handleFile = (file) => {
    if (file && file.type.startsWith("image/")) {
      const reader = new FileReader();

      reader.onload = (event) => {
        previewArea.innerHTML = `
          <img src="${event.target.result}" alt="Previsualización" class="preview-img">
          <textarea id="descripcion-textarea" maxlength="100" placeholder="Escribe una descripción (100 carácteres permitidos)"></textarea>
        `;
        previewArea.classList.add("mostrar-preview");
      };

      reader.readAsDataURL(file);
    }
  };

  fileInput.addEventListener("change", (e) => {
    handleFile(e.target.files[0]);
  });

  uploadArea.addEventListener("dragover", (e) => {
    e.preventDefault();
    uploadArea.classList.add("dragover");
  });

  uploadArea.addEventListener("dragleave", () => {
    uploadArea.classList.remove("dragover");
  });

  uploadArea.addEventListener("drop", (e) => {
    e.preventDefault();
    uploadArea.classList.remove("dragover");
    const file = e.dataTransfer.files[0];
    handleFile(file);
  });

  // Antes de enviar el formulario, pasar la descripción del textarea al input hidden
  form.addEventListener("submit", (e) => {
    const descripcionTextarea = document.getElementById("descripcion-textarea");
    if (descripcionTextarea) {
      descripcionHidden.value = descripcionTextarea.value;
    }
  });
});

</script>
</body>
</html>
