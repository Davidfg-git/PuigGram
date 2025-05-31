<?php
session_start();
include '../db/db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo "No autorizado";
    exit;
}

$id_usuario = $_SESSION['user_id'];
$id_seguido = $_POST['id_seguido'] ?? null;

if (!$id_seguido) {
    http_response_code(400);
    echo "ID de seguido no recibido";
    exit;
}

// Comprobamos si ya existe ese seguimiento
$sqlCheck = "SELECT * FROM seguidores WHERE id_usuario = :id_usuario AND id_seguido = :id_seguido";
$stmtCheck = $pdo->prepare($sqlCheck);
$stmtCheck->execute(['id_usuario' => $id_usuario, 'id_seguido' => $id_seguido]);

if ($stmtCheck->rowCount() > 0) {
    echo "Ya sigues a este usuario";
    exit;
}

// Insertamos el seguimiento
$sql = "INSERT INTO seguidores (id_usuario, id_seguido) VALUES (:id_usuario, :id_seguido)";
$stmt = $pdo->prepare($sql);

try {
    $stmt->execute(['id_usuario' => $id_usuario, 'id_seguido' => $id_seguido]);
    echo "OK";
} catch (PDOException $e) {
    http_response_code(500);
    echo "Error: " . $e->getMessage();
}
