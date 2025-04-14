<?php
session_start();
include '../../backend/db/db.php';
include '../../backend/php/usuariosDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_SESSION['user_id'];
    $nombre = $_POST['nombre'];
    $nombreUsuario = $_POST['userName'];
    $presentacion = $_POST['presentacion'];

    $imagenPerfil = null;

    // Verifica si se ha subido una nueva imagen correctamente
    if (isset($_FILES['imagen_perfil']) && $_FILES['imagen_perfil']['error'] === UPLOAD_ERR_OK) {
        // Leer contenido binario para base de datos
        $imagenPerfil = file_get_contents($_FILES['imagen_perfil']['tmp_name']);

        // (Opcional) Guardar copia en disco, si lo necesitas
        $nombreArchivo = basename($_FILES['imagen_perfil']['name']);
        $rutaDestino = '../../public/assets/uploads/' . $nombreArchivo;

        // Asegúrate de que la carpeta exista
        if (!is_dir('../../public/assets/uploads')) {
            mkdir('../../public/assets/uploads', 0755, true);
        }

        move_uploaded_file($_FILES['imagen_perfil']['tmp_name'], $rutaDestino);
    }

    // Crear instancia del DAO y actualizar
    $usuariosDAO = new UsuariosDAO($pdo);
    $resultado = $usuariosDAO->updatePerfilUsuario($id, $imagenPerfil, $nombre, $nombreUsuario, $presentacion);

    if ($resultado) {
        header("Location: profile.php?success=1");
        exit;
    } else {
        echo "Error al actualizar el perfil.";
    }
}
?>
