<?php
session_start();
include '../../backend/db/db.php';

$id_usuario_logeado = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;
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
$sqlImgs = "SELECT contenido, descripcion FROM publicaciones WHERE id_usuario = :id AND tipo = 'imagen' AND contenido IS NOT NULL ORDER BY id_publicacion DESC LIMIT 3";
$stmtImgs = $pdo->prepare($sqlImgs);
$stmtImgs->execute(['id' => $id]);
$imagenes = $stmtImgs->fetchAll(PDO::FETCH_ASSOC);

// Añadir las imágenes al array de respuesta
$user['imagenes'] = $imagenes;

// Contar número de seguidores (usuarios que lo siguen)
$sqlSeguidores = "SELECT COUNT(*) AS num_seguidores FROM seguidores WHERE id_seguido = :id";
$stmtSeguidores = $pdo->prepare($sqlSeguidores);
$stmtSeguidores->execute(['id' => $id]);
$num_seguidores = $stmtSeguidores->fetch(PDO::FETCH_ASSOC);
$user['num_seguidores'] = $num_seguidores['num_seguidores'];

// Contar número de seguidos (usuarios a los que sigue)
$sqlSeguidos = "SELECT COUNT(*) AS num_seguidos FROM seguidores WHERE id_usuario = :id";
$stmtSeguidos = $pdo->prepare($sqlSeguidos);
$stmtSeguidos->execute(['id' => $id]);
$num_seguidos = $stmtSeguidos->fetch(PDO::FETCH_ASSOC);
$user['num_seguidos'] = $num_seguidos['num_seguidos'];

// Saber si el usuario logueado ya sigue a este usuario (solo si está logueado y no es el mismo usuario)
if ($id_usuario_logeado > 0 && $id_usuario_logeado !== $id) {
    $sql = "SELECT COUNT(*) FROM seguidores WHERE id_usuario = :yo AND id_seguido = :el";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['yo' => $id_usuario_logeado, 'el' => $id]);
    $user['ya_sigue'] = ($stmt->fetchColumn() > 0);
} else {
    $user['ya_sigue'] = false;
}

header('Content-Type: application/json');
echo json_encode($user);