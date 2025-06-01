<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // Redirigir o mostrar error
    header('Location: index.php');
    exit();
}
$id = $_SESSION['user_id'];/*

FALTA INLCUIR QUE CUANDO NO EXISTA ID DE USUARIO (NO LOGGED)
SE REDIRIGA A INDEX PARA INICIAR SESIÓN

*/
include '../../backend/db/db.php';
$sql = "SELECT * FROM Usuarios WHERE id_usuario = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$nombre = $user['nombre_completo'];
$username = $user['nombre_usuario'];
$descripcion = $user['descripcion'];
$imagenPerfil = $user['imagen_perfil'];
if (empty($imagenPerfil)) {
    $imagenPerfil = null;
}

// Ejemplo de consulta para obtener sugerencias (ajusta según tu lógica y estructura de base de datos)
$sql = "
    SELECT u.id_usuario, u.nombre_usuario, u.imagen_perfil, 
           CASE WHEN s.id_usuario IS NOT NULL THEN 1 ELSE 0 END AS ya_sigue
    FROM usuarios u
    LEFT JOIN seguidores s 
        ON s.id_usuario = :id_sesion AND s.id_seguido = u.id_usuario
    WHERE u.id_usuario != :id_sesion
    LIMIT 5
";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id_sesion' => $id]);
$sugerencias = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensajes</title>
    <link rel="icon" href="../../../icono.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../public/assets/styles/mainStyle.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">


</head>
<body>
    <div class="sidebar">
        <h1>PuigGram</h1>
        <ul>
            <li><a href="mainPage.php"><i class="bi bi-house"></i> Inicio</a></li>
        <li><a href="explore.php"><i class="bi bi-search"></i>Explorar</a></li>
        <li><a href="messages.php"><i class="bi bi-chat"></i>Mensajes</a></li>
        <li><a href="notifications.php"><i class="bi bi-bell"></i>Notificaciones</a></li>
        <li><a href="publish.php"><i class="bi bi-plus-square"></i>Publicar</a></li>
        <li><a href="profile.php"><i class="bi bi-person"></i>Perfil</a></li>
        <li><a href="settings.php"><i class="bi bi-gear"></i>Configuración</a></li>
        </ul>
    </div>

    <div class="main">
        <div class="post">
            <div class="post-header">
                
            </div>
           
        </div>
    </div>
    <div class="contenido">
        
    </div>
    <div class="suggestions">
    <h2>Sugerencias</h2>
    <div class="suggestions">
    <h2>Sugerencias</h2>
    <ul>
    <?php foreach ($sugerencias as $sugerencia): ?>
        <li>
            <img src="<?= !empty($sugerencia['src']) ? $sugerencia['src'] : '../../public/assets/default/default-image.jpg' ?>" alt="">
            <?= htmlspecialchars($sugerencia['nombre_usuario']) ?>
            <button class="follow-btn" data-id="<?= $sugerencia['id_usuario'] ?>">Seguir</button>
        </li>
    <?php endforeach; ?>
</ul>

</div>

</div>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const botonesSeguir = document.querySelectorAll("button[class^='follow']");

    botonesSeguir.forEach(button => {
        button.addEventListener("click", () => {
            const id_seguido = button.getAttribute("data-id");

            fetch("../../backend/php/seguir_usuario.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "id_seguido=" + encodeURIComponent(id_seguido)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    button.textContent = "Siguiendo";
                    button.disabled = true;
                    button.classList.add("following");
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error("Error:", error);
            });
        });
    });
});
</script>
</body>
</html>
