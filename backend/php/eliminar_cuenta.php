<?php
session_start();
require_once '../../backend/db/db.php'; // Ajusta la ruta si es distinta

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$id_usuario = $_SESSION['user_id'];

// Primero eliminamos el usuario
$sql = "DELETE FROM usuarios WHERE id_usuario = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id_usuario]);

// Luego cerramos la sesión
session_unset();
session_destroy();

// Redirige al inicio con un mensaje (opcional, por GET)
header("Location: index.php?cuenta_eliminada=1");
exit();
?>