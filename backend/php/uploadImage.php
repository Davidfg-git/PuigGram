<?php
session_start();
include '../../backend/db/db.php'; // conexión PDO
include '../../backend/php/publicacionesDAO.php'; // supongo tienes o crearás este DAO
if (!isset($_SESSION['user_id'])) {
    // Redirigir o mostrar error
    header('Location: index.php');
    exit();
}
$id = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagen'])) {
    $imagen = $_FILES['imagen'];

    // Validaciones
    $allowedExtensions = ['jpg', 'jpeg', 'png'];
    $extension = strtolower(pathinfo($imagen['name'], PATHINFO_EXTENSION));
    $pesoMaximo = 5000000; // 5MB

    if (!in_array($extension, $allowedExtensions)) {
        echo "Error: Formato no permitido.";
        exit;
    }

    if ($imagen['size'] > $pesoMaximo) {
        echo "Error: La imagen supera el tamaño permitido.";
        exit;
    }

    // Crear carpeta si no existe
    $directorioUsuario = '../../public/assets/uploads/' . $id . '/';
    if (!file_exists($directorioUsuario)) {
        mkdir($directorioUsuario, 0777, true);
    }

    // Determinar siguiente número de imagen
    $archivosExistentes = glob($directorioUsuario . '*.{jpg,jpeg,png}', GLOB_BRACE);
    $numeroImagen = count($archivosExistentes) + 1;

    // Nombre del archivo
    $nombreArchivo = $numeroImagen . '.' . $extension;
    $rutaDestino = $directorioUsuario . $nombreArchivo;

    // Mover archivo
    if (move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {

        // Guardar publicación en la base de datos
        $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';

        $publicacionesDAO = new PublicacionesDAO($pdo);
        $tipo = 'imagen';
        $rutaRelativa = 'public/assets/uploads/' . $id . '/' . $nombreArchivo;

        $resultado = $publicacionesDAO->insertarPublicacion($id, $rutaRelativa, $tipo, $descripcion);

    if ($resultado) {
    header("Location: /PuigGram/backend/php/publish.php?mensaje=" . urlencode("Imagen subida correctamente."));
    exit();
} else {
    header("Location: /PuigGram/backend/php/publish.php?mensaje=" . urlencode("¡Error al guardar la publicación en la base de datos!"));
    exit();
}

    } else {
        echo "Error al mover la imagen.";
    }

} else {
    echo "No se ha enviado ninguna imagen.";
}
?>
