<?php
include '../../backend/db/db.php';

session_start();
$id_usuario_actual = $_SESSION['user_id'] ?? 0; // Obtener ID del usuario actual

$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 1;

$stmt = $pdo->prepare("
    SELECT p.*, u.nombre_usuario 
    FROM publicaciones p 
    JOIN usuarios u ON p.id_usuario = u.id_usuario 
    WHERE p.id_usuario != :id_usuario_actual
    ORDER BY p.id_publicacion DESC 
    LIMIT :limit OFFSET :offset
");

$stmt->bindValue(':id_usuario_actual', $id_usuario_actual, PDO::PARAM_INT);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();

$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($posts) === 0) {
    // No hay publicación
    exit;
}

$publicacion = $posts[0];
$ruta = htmlspecialchars($publicacion['contenido']);
$descripcion = htmlspecialchars($publicacion['descripcion']);
$username = htmlspecialchars($publicacion['nombre_usuario']);

echo "
<div class='post-card'>
    <div class='media-box'>
        <img src='../../$ruta' alt='Publicación de @$username' class='post-img'>
    </div>
    <div class='description-box'>
        <p><strong>@$username:</strong> $descripcion</p>
    </div>
</div>
";
