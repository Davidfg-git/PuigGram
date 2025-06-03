<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No has iniciado sesión.']);
    exit();
}

include '../db/db.php';


session_start();
include '../../backend/db/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

$id_usuario = $_SESSION['user_id'];
$offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
$limite = 6;

$sql = "SELECT contenido FROM publicaciones 
        WHERE id_usuario = :id AND tipo = 'imagen' 
        ORDER BY fecha_publicacion DESC 
        LIMIT :limite OFFSET :offset";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id_usuario, PDO::PARAM_INT);
$stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$imagenes = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo json_encode($imagenes);
