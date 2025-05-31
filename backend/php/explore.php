<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}
$id = $_SESSION['user_id'];

include '../../backend/db/db.php';

// Obtener datos del usuario logueado
$sql = "SELECT * FROM usuarios WHERE id_usuario = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$nombre = $user['nombre_completo'];
$username = $user['nombre_usuario'];
$descripcion = $user['descripcion'];
$imagenPerfil = $user['imagen_perfil'] ?? null;

// Sugerencias con comprobación de seguimiento optimizada
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

// PROCESO DE BÚSQUEDA
$resultadoBusqueda = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $busqueda = trim($_POST['barraBusqueda']);
    if (!empty($busqueda)) {
        $sql = "SELECT nombre_usuario, imagen_perfil FROM usuarios WHERE nombre_usuario = :busqueda";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['busqueda' => $busqueda]);
        $resultadoBusqueda = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Explore</title>
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
        <li hidden><a href="messages.php" ><i class="bi bi-chat"></i>Mensajes</a></li>
        <li hidden><a href="notifications.php" ><i class="bi bi-bell"></i>Notificaciones</a></li>
        <li><a href="publish.php"><i class="bi bi-plus-square"></i>Publicar</a></li>
        <li><a href="profile.php"><i class="bi bi-person"></i>Perfil</a></li>
        <li><a href="settings.php"><i class="bi bi-gear"></i>Configuración</a></li>
    </ul>
</div>

<div class="main">
    <div class="post">
        <div class="post-header">
            <form class="busquedaBarra" method="POST" action="explore.php">
                <input type="search" name="barraBusqueda" id="barraBusqueda" placeholder="Buscar">
                <button type="submit" class="botonBusqueda">🔍</button>
            </form>
        </div>
    </div>
</div>

<div class="contenido">
    <?php if ($resultadoBusqueda): ?>
        <div class="resultado">
            <img src="<?= !empty($resultadoBusqueda['imagen_perfil']) ? $resultadoBusqueda['imagen_perfil'] : '../../public/assets/default/default-image.jpg' ?>" alt="Perfil" width="60" class="imagenes">
            <span><?= htmlspecialchars($resultadoBusqueda['nombre_usuario']) ?></span>
        </div>
    <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <p>No se encontró ningún usuario con ese nombre.</p>
    <?php endif; ?>
</div>

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

<script>
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
