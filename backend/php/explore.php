<?php
session_start();
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

// Sugerencias
$stmt = $pdo->query("SELECT nombre_usuario, imagen_perfil FROM usuarios WHERE id_usuario != $id LIMIT 3");
$sugerencias = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $sugerencias[] = [
        'nombre_usuario' => $row['nombre_usuario'],
        'src' => !empty($row['imagen_perfil']) ? $row['imagen_perfil'] : null
    ];
}
while (count($sugerencias) < 3) {
    $sugerencias[] = ['nombre_usuario' => 'Usuario', 'src' => null];
}

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
        <li>
            <img src="<?= !empty($sugerencias[0]['src']) ? $sugerencias[0]['src'] : '../../public/assets/default/default-image.jpg' ?>" alt="">
            <?= htmlspecialchars($sugerencias[0]['nombre_usuario']) ?>
            <button>Seguir</button>
        </li>
        <li>
            <img src="<?= !empty($sugerencias[1]['src']) ? $sugerencias[1]['src'] : '../../public/assets/default/default-image.jpg' ?>" alt="">
            <?= htmlspecialchars($sugerencias[1]['nombre_usuario']) ?>
            <button>Seguir</button>
        </li>
        <li>
            <img src="<?= !empty($sugerencias[2]['src']) ? $sugerencias[2]['src'] : '../../public/assets/default/default-image.jpg' ?>" alt="">
            <?= htmlspecialchars($sugerencias[2]['nombre_usuario']) ?>
            <button>Seguir</button>
        </li>
    </ul>
</div>

</body>
</html>
