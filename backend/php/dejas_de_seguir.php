<?php
header("Content-Type: application/json");
session_start();
require_once "../db/db.php";

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Debes iniciar sesión para dejar de seguir usuarios."]);
    exit;
}
$id_usuario = $_SESSION['user_id'];
$id_seguido = $_POST['id_seguido'] ?? null;

if (!$id_seguido) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "ID de usuario a dejar de seguir no recibido."]);
    exit;
}
if ($id_usuario == $id_seguido) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "No puedes dejar de seguirte a ti mismo."]);
    exit;
}

$sqlDelete = "DELETE FROM seguidores WHERE id_usuario = :id_usuario AND id_seguido = :id_seguido";
$stmtDelete = $pdo->prepare($sqlDelete);

try {
    $stmtDelete->execute([
        'id_usuario' => $id_usuario,
        'id_seguido' => $id_seguido
    ]);
    echo json_encode(["status" => "success"]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Error en la base de datos: " . $e->getMessage()]);
}