<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit();
}

require_once '../../backend/db/db.php';

if (isset($_POST['contenido'])) {
    $contenido = $_POST['contenido'];
    $idUsuario = $_SESSION['user_id'];

    // Verificar que la imagen pertenezca al usuario
    $stmt = $pdo->prepare("SELECT id_publicacion FROM publicaciones WHERE contenido = :contenido AND id_usuario = :id");
    $stmt->execute(['contenido' => $contenido, 'id' => $idUsuario]);
    $publicacion = $stmt->fetch();

    if ($publicacion) {
        // Borrar publicación
        $deleteStmt = $pdo->prepare("DELETE FROM publicaciones WHERE id_publicacion = :id");
        $deleteStmt->execute(['id' => $publicacion['id_publicacion']]);
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Publicación no encontrada o no pertenece al usuario']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Faltan datos']);
}
