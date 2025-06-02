<?php
session_start();  // Iniciar sesión
if (!isset($_SESSION['user_id'])) {
    // Redirigir o mostrar error
    header('Location: index.php');
    exit();
}
$id = $_SESSION['user_id'];if (isset($_GET['mensaje'])) {
    echo "<script>alert('" . htmlspecialchars($_GET['mensaje']) . "');</script>";
}
include '../../backend/db/db.php';
$sql = "SELECT * FROM Usuarios WHERE id_usuario = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$nombre = $user['nombre_completo'];
$username = $user['nombre_usuario'];
$descripcion = $user['descripcion'];
$imagenPerfil = $user['imagen_perfil'];
if (empty($imagenPerfil)) {
    $imagenPerfil = null;
}
// Ejemplo de consulta para obtener sugerencias (ajusta según tu lógica y estructura de base de datos)
$sql = "
    SELECT u.id_usuario, u.nombre_usuario, u.imagen_perfil, 
           CASE WHEN s.id_usuario IS NOT NULL THEN 1 ELSE 0 END AS ya_sigue
    FROM usuarios u
    LEFT JOIN seguidores s 
        ON s.id_usuario = :id_sesion AND s.id_seguido = u.id_usuario
    WHERE u.id_usuario != :id_sesion
    LIMIT 5
";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id_sesion' => $id]);
$sugerencias = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="icon" href="../../../icono.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../public/assets/styles/mainStyle.css">
    <script defer src="scripts.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">
 
</head>
<body>
    <div class="sidebar">
        <h1>PuigGram</h1>
        <ul>
            <li><a href="mainPage.php"><i class="bi bi-house"></i> Inicio</a></li>
        <li><a href="explore.php"><i class="bi bi-search"></i>Explorar</a></li>
        <li hidden><a href="messages.php"><i class="bi bi-chat"></i>Mensajes</a></li>
        <li hidden><a href="notifications.php"><i class="bi bi-bell"></i>Notificaciones</a></li>
        <li><a href="publish.php"><i class="bi bi-plus-square"></i>Publicar</a></li>
        <li><a href="profile.php"><i class="bi bi-person"></i>Perfil</a></li>
        <li><a href="settings.php"><i class="bi bi-gear"></i>Configuración</a></li>
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
</div>

        </div>
        </div>
   
    
    <div class="suggestions">
    <h2>Sugerencias</h2>
    <div class="suggestions">
    <h2>Sugerencias</h2>
    <ul>
    <?php foreach ($sugerencias as $sugerencia): ?>
        <li>
            <img src="<?= !empty($sugerencia['imagen_perfil']) ? $sugerencia['imagen_perfil'] : '../../public/assets/default/default-image.jpg' ?>" alt="">
            <?= htmlspecialchars($sugerencia['nombre_usuario']) ?>
            <button 
    class="follow-btn <?= $sugerencia['ya_sigue'] ? 'following' : '' ?>" 
    data-id="<?= $sugerencia['id_usuario'] ?>"
    <?= $sugerencia['ya_sigue'] ? 'disabled' : '' ?>>
    <?= $sugerencia['ya_sigue'] ? 'Siguiendo' : 'Seguir' ?>
</button>

        </li>
    <?php endforeach; ?>
    </ul>
</div>
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
          <textarea id="descripcion-textarea" maxlength="43" placeholder="Escribe una descripción (43 carácteres permitidos)"></textarea>
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
element.style.removeProperty('justify-content');
document.addEventListener("DOMContentLoaded", () => {
    const botonesSeguir = document.querySelectorAll(".follow-btn");

    botonesSeguir.forEach(button => {
        // Si ya está siguiendo (deshabilitado o con clase 'following'), no añadas listener
        if (button.classList.contains("following") || button.disabled) {
            return;
        }

        button.addEventListener("click", () => {
            const id_seguido = button.getAttribute("data-id");

            fetch("../../backend/php/seguir_usuario.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "id_seguido=" + encodeURIComponent(id_seguido)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    button.textContent = "Siguiendo";
                    button.disabled = true;
                    button.classList.add("following");
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error("Error:", error);
            });
        });
    });
});
</script>
</body>
</html>
