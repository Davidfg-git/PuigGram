<?php
/*Falta que la descripción e las publicaciones empiece en la izquierda y que la imagen de la publicaación sea algo más grande */
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$id = $_SESSION['user_id'];
include '../../backend/db/db.php';

$sql = "SELECT * FROM Usuarios WHERE id_usuario = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$nombre = $user['nombre_completo'];
$username = $user['nombre_usuario'];
$descripcion = $user['descripcion'];
$imagenPerfil = $user['imagen_perfil'] ?: null;

//sugerencias
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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Inicio</title>
    <link rel="icon" href="../../../icono.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../public/assets/styles/mainStyle.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet" />
    <style>
        /* Estilos para el carrusel de una sola publicación */
        
    </style>
</head>
<body>
    <div class="sidebar">
        <h1>PuigGram</h1>
        <ul>
            <li><a href="mainPage.php"><i class="bi bi-house"></i> Inicio</a></li>
            <li><a href="explore.php"><i class="bi bi-search"></i>Explorar</a></li>
            <li hidden><a href="messages.php"><i class="bi bi-chat"></i>Mensajes</a></li>
            <li hidden><a href="notifications.php"><i class="bi bi-bell"></i>Notificaciones</a></li>
            <li><a href="publish.php"><i class="bi bi-plus-square"></i>Publicar</a></li>
            <li><a href="profile.php"><i class="bi bi-person"></i>Perfil</a></li>
            <li><a href="settings.php"><i class="bi bi-gear"></i>Configuración</a></li>
        </ul>
    </div>

    <div class="main">
        <div class="post">
            <div class="post-header">
                <p class="inicioText">INICIO</p>
            </div>
        </div>
    </div>

    <div class="contenido" id="post-container">
        <div class="post-carousel">
            <button id="prev-post" class="nav-btn" disabled>&#8249;</button>
            <div class="post-view">
                <!-- Aquí se cargará la publicación actual -->
            </div>
            <button id="next-post" class="nav-btn">&#8250;</button>
        </div>
    </div>

    <div class="ver-mas-container">
        <button id="ver-mas" class="btn-ver-mas">Ver más</button>
    </div>

    <div class="suggestions">
    <h2>Sugerencias</h2>
    <ul>
    <?php foreach ($sugerencias as $sugerencia): ?>
        <li>
            <img src="<?= !empty($sugerencia['imagen_perfil']) ? $sugerencia['imagen_perfil'] : '../../public/assets/default/default-image.jpg' ?>" alt="">
            <?= htmlspecialchars($sugerencia['nombre_usuario']) ?>
            <button 
    class="follow-btn <?= $sugerencia['ya_sigue'] ? 'following' : '' ?>" 
    data-id="<?= $sugerencia['id_usuario'] ?>"
    <?= $sugerencia['ya_sigue'] ? 'disabled' : '' ?>>
    <?= $sugerencia['ya_sigue'] ? 'Siguiendo' : 'Seguir' ?>
</button>

        </li>
    <?php endforeach; ?>
    </ul>
</div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Seguir usuarios
            document.querySelectorAll(".follow-btn").forEach(button => {
                button.addEventListener("click", () => {
                    const id_seguido = button.getAttribute("data-id");
                    fetch("../../backend/php/seguir_usuario.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
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
                    .catch(error => console.error("Error:", error));
                });
            });
        });

        let offset = 0;
        const postView = document.querySelector('.post-view');
        const prevBtn = document.getElementById('prev-post');
        const nextBtn = document.getElementById('next-post');

        function cargarPublicacion(offsetVal) {
            fetch(`../../backend/php/cargarPublicaciones.php?offset=${offsetVal}&limit=1`)
                .then(res => res.text())
                .then(html => {
                    if(html.trim() === '') {
                        // No hay más publicaciones
                        if(offsetVal < offset) {
                            prevBtn.disabled = true;
                        } else {
                            nextBtn.disabled = true;
                        }
                        return;
                    }
                    // Efecto fade simple
                    postView.style.opacity = 0;
                    setTimeout(() => {
                        postView.innerHTML = html;
                        postView.style.opacity = 1;
                    }, 200);

                    offset = offsetVal;
                    prevBtn.disabled = (offset === 0);
                    nextBtn.disabled = false;
                });
        }

        prevBtn.addEventListener('click', () => {
            if(offset > 0) {
                cargarPublicacion(offset - 1);
            }
        });

        nextBtn.addEventListener('click', () => {
            cargarPublicacion(offset + 1);
        });

        // Cargar primera publicación al inicio
        cargarPublicacion(0);

        document.addEventListener("DOMContentLoaded", () => {
    const botonesSeguir = document.querySelectorAll(".follow-btn");

    botonesSeguir.forEach(button => {
        // Si ya está siguiendo (deshabilitado o con clase 'following'), no añadas listener
        if (button.classList.contains("following") || button.disabled) {
            return;
        }

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
