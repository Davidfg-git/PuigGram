<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    exit(); // No mostrar nada si no hay sesión
}

include '../db/db.php';

$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 1;
$userId = $_SESSION['user_id']; // <-- esto es lo que agregas

$sql = "
    SELECT p.*, u.nombre_usuario, u.imagen_perfil
    FROM publicaciones p
    JOIN usuarios u ON p.id_usuario = u.id_usuario
    WHERE p.id_usuario != :user_id  -- <-- nueva condición
    ORDER BY p.fecha_publicacion DESC
    LIMIT :limit OFFSET :offset
";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $userId, PDO::PARAM_INT); // <-- nuevo parámetro
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($posts as $post) {
    $imagen = '../../' . $post['contenido']; // Asegúrate que sea ruta válida
    $descripcion = nl2br(htmlspecialchars($post['descripcion']));
    $usuario = htmlspecialchars($post['nombre_usuario']);
    $perfil = $post['imagen_perfil'] ?: '../../public/assets/default/default-image.jpg';

    echo <<<HTML
    <div class="publicacion">
        <div class="publicacion-usuario">
            <img src="$perfil" alt="Perfil" class="publicacion-avatar">
            <span class="publicacion-username">@{$usuario}</span>
        </div>
        <img class="publicacion-imagen" src="{$imagen}" alt="Publicación">
        <button class="btn-ver-imagen" data-src="{$imagen}">Ver imagen</button>
        <p class="publicacion-descripcion">{$descripcion}</p>
    </div>
    HTML;
}