<?php
include '../../backend/db/db.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo json_encode(['error' => 'ID inválido']);
    exit;
}
$sql = "SELECT * FROM usuarios WHERE id_usuario = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    echo json_encode(['error' => 'Usuario no encontrado']);
    exit;
}

// Traer imágenes de publicaciones del usuario
$sqlImgs = "SELECT contenido, descripcion FROM publicaciones WHERE id_usuario = :id AND tipo = 'imagen' AND contenido IS NOT NULL ORDER BY id_publicacion DESC";
$stmtImgs = $pdo->prepare($sqlImgs);
$stmtImgs->execute(['id' => $id]);
$imagenes = $stmtImgs->fetchAll(PDO::FETCH_ASSOC);

// Añadir las imágenes al array de respuesta
$user['imagenes'] = $imagenes;

echo json_encode($user);