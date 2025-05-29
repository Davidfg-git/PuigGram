<?php
session_start();
$id = $_SESSION['user_id'];
/*

FALTA INLCUIR QUE CUANDO NO EXISTA ID DE USUARIO (NO LOGGED)
SE REDIRIGA A INDEX PARA INICIAR SESIÓN

*/
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
$stmt = $pdo->query("SELECT nombre_usuario, imagen_perfil FROM usuarios WHERE id_usuario != $id LIMIT 3");
$sugerencias = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $sugerencias[] = [
        'nombre_usuario' => $row['nombre_usuario'],
        'src' => !empty($row['imagen_perfil']) ?  $row['imagen_perfil'] : null
    ];
}

// Si hay menos de 3 sugerencias, rellena con valores vacíos para evitar errores
while (count($sugerencias) < 3) {
    $sugerencias[] = ['nombre_usuario' => 'Usuario', 'src' => null];
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inicio</title>
<link rel="stylesheet" href="../../public/assets/styles/mainStyle.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">
</head>
<body>

<div class="sidebar">
    <h1>PuigGram</h1>
    <ul>
        <li><a href="mainPage.php"><i class="bi bi-house"></i> Inicio</a></li>
        <li><a href="explore.php"><i class="bi bi-search"></i>Explorar</a></li>
        <li><a href="messages.php"><i class="bi bi-chat"></i>Mensajes</a></li>
        <li><a href="notifications.php"><i class="bi bi-bell"></i>Notificaciones</a></li>
        <li><a href="publish.php"><i class="bi bi-plus-square"></i>Publicar</a></li>
        <li><a href="profile.php"><i class="bi bi-person"></i>Perfil</a></li>
        <li><a href="settings.php"><i class="bi bi-gear"></i>Configuración</a></li>
    </ul>
</div>

<div class="main">
    <div class="post">
        <div class="post-header">
            <p class="inicioText">PERFIL</p>
        </div>
    </div>
</div>

<div class="contenido" style="justify-content: center;">

<form action="changeProfile.php" method="POST" enctype="multipart/form-data" class="formularioConfiguracion">
    <section class="seccionesPerfil">
        <label for="file" class="formularioCambioPerfil">Imagen de perfil</label>
        <img class="imgPerfilPrev" id="perfilImagen" src="<?= $imagenPerfil . '?v=' . time(); ?>" alt="Profile Picture">
        
        <input type="file" name="imagen_perfil" id="imagenRedimensionadaInput" style="display: none;">
        <input type="file" id="fileInput" style="display: none;" name="imagen_perfil_real" accept="image/webp,image/avif,image/jpeg,image/png" onchange="actualizarImagen()">
        <button class="editarImagen" type="button" onclick="document.getElementById('fileInput').click();">Editar imagen</button>
        <button class="redimensionarImagen" type="button" onclick="prepareRedimensionar()">Redimensionar imagen</button>
    </section>

    <section class="seccionesPerfil">
        <label class="formularioCambioPerfil">Nombre</label>
        <input type="text" class="nombre" value="<?= $nombre ?>" id="nombre" name="nombre">
    </section>

    <section class="seccionesPerfil">
        <label class="formularioCambioPerfil">Nombre de usuario</label>
        <input type="text" class="nombre" id="userName" name="userName" value="<?= $username ?>">
    </section>

    <section class="seccionesPerfil">
        <label class="formularioCambioPerfil">Presentación</label>
        <input type="text" class="nombre" id="presentacion" name="presentacion" max="300" value="<?= $descripcion ?>">
    </section>

    <section class="seccionesPerfil">
        <button class="guardarCambios">Guardar Cambios</button>
    </section>
</form>

</div>
<div class="suggestions">
<h2>Sugerencias</h2>
     <ul>
         <li>
         <img src="<?= !empty($sugerencias[0]['src']) ? $sugerencias[0]['src'] : '../../public/assets/default/default-image.jpg' ?>" alt="Profile Picture">
         <span><?= htmlspecialchars($sugerencias[0]['nombre_usuario']) ?></span>
             <button>Seguir</button>
         </li>
         <li>
         <img src="<?= !empty($sugerencias[1]['src']) ? $sugerencias[1]['src'] : '../../public/assets/default/default-image.jpg' ?>" alt="Profile Picture">
             <span><?= htmlspecialchars($sugerencias[1]['nombre_usuario']) ?></span>
             <button>Seguir</button>
         </li>
         <li>
         <img src="<?= !empty($sugerencias[2]['src']) ? $sugerencias[2]['src'] : '../../public/assets/default/default-image.jpg' ?>" alt="Profile Picture">
             <span><?= htmlspecialchars($sugerencias[2]['nombre_usuario']) ?></span>
             <button>Seguir</button>
         </li>
     </ul>
 </div>
<script>
function actualizarImagen() {
    const fileInput = document.getElementById('fileInput');
    const perfilImagen = document.getElementById('perfilImagen');
    if (fileInput.files && fileInput.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            perfilImagen.src = e.target.result;
        };
        reader.readAsDataURL(fileInput.files[0]);
    }
    
}

function prepareRedimensionar() {
    const imagenSrc = document.getElementById('perfilImagen').src;
    if (imagenSrc.includes('data:image')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '../../redimensionador/redimensionador.php';
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'imagen';
        input.value = imagenSrc;
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    } else {
        window.location.href = `../../redimensionador/redimensionador.php?imagen=${encodeURIComponent(imagenSrc)}`;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const dataUrl = localStorage.getItem('imagenRedimensionada');
    const perfilImagen = document.getElementById('perfilImagen');
    const inputRedimensionada = document.getElementById('imagenRedimensionadaInput');

    if (dataUrl) {
        perfilImagen.src = dataUrl;

        // Convertir DataURL en archivo y asignarlo al input tipo file
        fetch(dataUrl)
            .then(res => res.blob())
            .then(blob => {
                const file = new File([blob], 'perfil_redimensionado.png', { type: 'image/png' });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                inputRedimensionada.files = dataTransfer.files;
            });

        localStorage.removeItem('imagenRedimensionada');
    }
});


</script>

</body>
</html>
