<?php
session_start();
include '../../backend/db/db.php';
include '../../backend/php/usuariosDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_SESSION['user_id'];
    $nombre = $_POST['nombre'];
    $nombreUsuario = $_POST['userName'];
    $presentacion = $_POST['presentacion'];
    //$imagenPerfil = file_get_contents($_FILES['imagen_perfil']['tmp_name']);
    if (empty(($_FILES['imagen_perfil']['tmp_name']))) {
        $imagenPerfil = null; // Si no se subió una imagen, asignar null
    } else {
        $imagenPerfil = file_get_contents($_FILES['imagen_perfil']['tmp_name']);
    }

    // Crear instancia del DAO y actualizar
    $usuariosDAO = new UsuariosDAO($pdo);
    if ($imagenPerfil === null) {
        $imagenPerfil = $_SESSION['imagen_perfil']; // Mantener la imagen actual si no se subió una nueva
    
    }
    $resultado = $usuariosDAO->updatePerfilUsuario($id, $imagenPerfil, $nombre, $nombreUsuario, $presentacion);

    if ($resultado) {
        header("Location: profile.php?success=1");
        exit;
    } else {
        echo "Error al actualizar el perfil.";
    }
}
?>
