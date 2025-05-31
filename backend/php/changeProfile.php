<?php
session_start();
include '../../backend/db/db.php';
include '../../backend/php/usuariosDAO.php';
if (!isset($_SESSION['user_id'])) {
    // Redirigir o mostrar error
    header('Location: index.php');
    exit();
}
$id = $_SESSION['user_id'];
if (!empty($_FILES['imagen_perfil_real']['name'])) {
    $imagen = $_FILES['imagen_perfil_real'];
    $nombreDeImagen = 'imagen_perfil_real';
} else {
    $imagen = $_FILES['imagen_perfil'];
    $nombreDeImagen = 'imagen_perfil';
}
if (!empty($_FILES[$nombreDeImagen]['tmp_name'])) {
    
    // Validar tamaño
    if ($_FILES["imagen_perfil"]["size"] > 5000000) { // 5 MB
        echo "La imagen es demasiado grande.";
        exit;
    }

    // Obtener extensión y generar nombre único
    $extension = pathinfo($_FILES[$nombreDeImagen]['name'], PATHINFO_EXTENSION);
    $nombreArchivo = $id . '.' . $extension;
    $rutaDestino = '../../public/assets/images/profile/' . $nombreArchivo;
    $pattern = '../../public/assets/images/profile/' . $id . '.*';
    $archivosExistentes = glob($pattern);
    foreach ($archivosExistentes as $archivo) {
        unlink($archivo);
    }
    // Mover el archivo a la carpeta de destino
    if (move_uploaded_file($_FILES[$nombreDeImagen]['tmp_name'], $rutaDestino)) {
        // Guardar la ruta relativa
       
        $imagenPerfil = '../../public/images/profile/' . $nombreArchivo;
    } else {
        echo "Error al subir la imagen.";
        exit;
    }
} else {
    // Si no se subió una nueva imagen, conservar la actual
    $imagenPerfil = $_SESSION[$nombreDeImagen];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $nombreUsuario = $_POST['userName'];
    $presentacion = $_POST['presentacion'];
    $imagenPerfil = $rutaDestino;

   

    // Crear instancia del DAO y actualizar
    $usuariosDAO = new UsuariosDAO($pdo);
    $resultado = $usuariosDAO->updatePerfilUsuario($id, $imagenPerfil, $nombre, $nombreUsuario, $presentacion);

    if ($resultado) {
        // También puedes actualizar la variable de sesión si la usas en otros lados
        $_SESSION[$nombreDeImagen] = $imagenPerfil;
        header("Location: profile.php?success=1");
        exit;
    } else {
        echo "Error al actualizar el perfil.";
    }
}
?>
