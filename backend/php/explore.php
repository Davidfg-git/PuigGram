<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}
$id = $_SESSION['user_id'];

include '../../backend/db/db.php';

// Obtener datos del usuario logueado
$sql = "SELECT * FROM usuarios WHERE id_usuario = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$nombre = $user['nombre_completo'];
$username = $user['nombre_usuario'];
$descripcion = $user['descripcion'];
$imagenPerfil = $user['imagen_perfil'] ?? null;





// Sugerencias con comprobación de seguimiento optimizada
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


// PROCESO DE BÚSQUEDA
$resultadoBusqueda = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $busqueda = trim($_POST['barraBusqueda']);
    if (!empty($busqueda)) {
    $sql = "SELECT id_usuario, nombre_usuario, imagen_perfil, descripcion 
            FROM usuarios 
            WHERE nombre_usuario LIKE :busqueda 
            LIMIT 6";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['busqueda' => $busqueda . '%']);
    $resultadoBusqueda = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}

/*
// Número de seguidores
$sqlSeguidores = "SELECT COUNT(*) AS total_seguidores FROM seguidores WHERE id_seguido = :id";
$stmtSeguidores = $pdo->prepare($sqlSeguidores);
$stmtSeguidores->execute(['id' => $id]);
$nseguidores = $stmtSeguidores->fetchColumn();

// Número de seguidos
$sqlSeguidos = "SELECT COUNT(*) AS total_seguidos FROM seguidores WHERE id_usuario = :id";
$stmtSeguidos = $pdo->prepare($sqlSeguidos);
$stmtSeguidos->execute(['id' => $id]);
$nseguidos = $stmtSeguidos->fetchColumn();


// Número de publicaciones
$sql = "SELECT COUNT(*) AS total FROM publicaciones WHERE id_usuario = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$npublicaciones = $stmt->fetchColumn();

// Obtener todas las imágenes del usuario ordenadas (ajusta el nombre de tabla y campo si es diferente)
$sqlImgs = "SELECT contenido FROM publicaciones WHERE id_usuario = :id AND tipo = 'imagen' AND contenido IS NOT NULL ORDER BY id_publicacion DESC";
$stmtImgs = $pdo->prepare($sqlImgs);
$stmtImgs->execute(['id' => $id]);
$imagenesPerfil = $stmtImgs->fetchAll(PDO::FETCH_COLUMN);

$sqlIds = "SELECT id_publicacion FROM publicaciones WHERE id_usuario = :id AND tipo = 'imagen' AND contenido IS NOT NULL ORDER BY id_publicacion DESC";
$stmtIds = $pdo->prepare($sqlIds);
$stmtIds->execute(['id' => $id]);
$idsPublicaciones = $stmtIds->fetchAll(PDO::FETCH_COLUMN);


$sqlDescripciones = "SELECT descripcion FROM publicaciones WHERE id_usuario = :id AND tipo = 'imagen' AND contenido IS NOT NULL ORDER BY id_publicacion DESC";
$stmtDescripciones = $pdo->prepare($sqlDescripciones);
$stmtDescripciones->execute(['id' => $id]);
$descripcionesPublicaciones = $stmtDescripciones->fetchAll(PDO::FETCH_COLUMN);
*/

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Explore</title>
    <link rel="icon" href="../../../icono.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../public/assets/styles/mainStyle.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">
    <style>
        #contenedor-imagenes-modal {
    display: grid;
    gap: 8px; /* Espacio entre imágenes, ajústalo a tu gusto */
    margin-top: 20px;
    grid-template-columns: repeat(3, 1fr); 
    
}
.btn-ver-descripcion{

 margin-left: -30%;

}
.contenedor-imagenes {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 0;
    margin: 0;
}
.imgPerfilPrev2{
width: 220px;
    height: 220px;
 margin-left: -9%;

}

.nombreUsuarioPopUp{
    color: black;
font-size: 30px;
margin-left: 120px;
}

.nombreCompletoPopUp{


display: none;
}

.descripcionPopUp{

margin-left: 120px;

margin-top: 100px;
}
  /* Estilos para el primer hijo */

.contenedor-imagenes :nth-child(1) {
margin-top: 35%;
  margin-left: -40%;
}
#seguidos {
    font-size: 20px;
    font-family: "Montserrat", Arial, Helvetica, sans-serif;
margin-left: 20%;
}
#seguidores{
font-size: 20px;
    font-family: "Montserrat", Arial, Helvetica, sans-serif;
margin-left: 20%;
}
/*seguidores y seguidos*/
.dores{
margin-left: 17%;
    color: black;
font-size: 20px;
}

.idos{ 
    position: absolute;
    font-size: 20px;
margin-left: 20%;
    color: black;

}
    </style>
</head>
<body>
<div class="sidebar">
    <h1>PuigGram</h1>
    <ul>
        <li><a href="mainPage.php"><i class="bi bi-house"></i> Inicio</a></li>
        <li><a href="explore.php"><i class="bi bi-search"></i>Explorar</a></li>
        <li hidden><a href="messages.php" ><i class="bi bi-chat"></i>Mensajes</a></li>
        <li hidden><a href="notifications.php" ><i class="bi bi-bell"></i>Notificaciones</a></li>
        <li><a href="publish.php"><i class="bi bi-plus-square"></i>Publicar</a></li>
        <li><a href="profile.php"><i class="bi bi-person"></i>Perfil</a></li>
        <li><a href="settings.php"><i class="bi bi-gear"></i>Configuración</a></li>
    </ul>
</div>

<div class="main">
    <div class="post">
        <div class="post-header">
            <form class="busquedaBarra" method="POST" action="explore.php">
                <input type="search" name="barraBusqueda" id="barraBusqueda" placeholder="Buscar">
                <button type="submit" class="botonBusqueda"><i id="botonBuscar" class="bi bi-search"></i></button>
            </form>
        </div>
    </div>
</div>

<div class="contenido">
    <?php if ($resultadoBusqueda): ?>
    <?php foreach ($resultadoBusqueda as $resultado): ?>
        <div class="resultado">
            <img src="<?= !empty($resultado['imagen_perfil']) ? $resultado['imagen_perfil'] : '../../public/assets/default/default-image.jpg' ?>" alt="Perfil" width="60" class="imagenes">
            <span><?= htmlspecialchars($resultado['nombre_usuario']) ?></span>
            
<?php
echo '<button class="verPerfil" type="button" onclick="mostrarModalUsuario(' . $resultado['id_usuario'] . ')">Ver Perfil</button>';
?><?php endforeach; ?>
<?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
    <p>No se encontró ningún usuario con ese nombre.</p>
<?php endif; ?>
</div>

<div class="suggestions">
        <h2>Sugerencias</h2>
        <ul>
            <?php foreach ($sugerencias as $sugerencia): ?>
                <li>
                    <img src="<?= !empty($sugerencia['imagen_perfil']) ? $sugerencia['imagen_perfil'] : '../../public/assets/default/default-image.jpg' ?>"
                        alt="">
                    <?= htmlspecialchars($sugerencia['nombre_usuario']) ?>
            <!--    -->
                    <button class="follow-btn <?= $sugerencia['ya_sigue'] ? 'following' : '' ?>"
                        data-id="<?= $sugerencia['id_usuario'] ?>" <?= $sugerencia['ya_sigue'] ? 'disabled' : '' ?>>
                        <?= $sugerencia['ya_sigue'] ? 'Siguiendo' : 'Seguir' ?>
                    </button>

                </li>
            <?php endforeach; ?>
        </ul>
    </div>

<script>
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

let imagenesModal = [];
let descripcionesModal = [];
let indiceInicioModal = 0;


    let mostrandoGaleriaPropia = true;
let imagenesUsuarioActual = [];
let descripcionesUsuarioActual = [];
let indiceInicioUsuarioActual = 0;
const IMAGENES_POR_CARGAR = 6;

function mostrarModalUsuario(idUsuario) {
    mostrandoGaleriaPropia = false;
    fetch('../../backend/php/get_usuario.php?id=' + idUsuario)
        .then(response => response.json())
        .then(usuario => {
            if (usuario.error) {
                alert(usuario.error);
                return;
            }
            document.getElementById('perfilImagen').src = usuario.imagen_perfil ? usuario.imagen_perfil : '../../public/assets/default/default-image.jpg';
            document.querySelector('.nombreUsuarioPopUp').textContent = usuario.nombre_usuario;
            document.querySelector('.nombreCompletoPopUp').textContent = usuario.nombre_completo || '';
            document.querySelector('.descripcionPopUp').textContent = usuario.descripcion || '';
            document.getElementById('seguidores').textContent = usuario.num_seguidores ?? '0';
            document.getElementById('seguidos').textContent = usuario.num_seguidos ?? '0';

            // Guardar imágenes y descripciones en variables temporales
            imagenesUsuarioActual = usuario.imagenes ? usuario.imagenes.map(img => img.contenido) : [];
            descripcionesUsuarioActual = usuario.imagenes ? usuario.imagenes.map(img => img.descripcion) : [];
            indiceInicioUsuarioActual = 0;

            paginarImagenesUsuarioActual();

            document.getElementById('modalPerfil').style.display = 'block';
        });
}

function paginarImagenesUsuarioActual() {
    const contenedor = document.getElementById('contenedor-imagenes-modal');
    contenedor.innerHTML = '';
    const btnCargarMas = document.getElementById('btn-cargar-mas');
    const btnCargarMenos = document.getElementById('btn-cargar-menos');

    const indiceFin = Math.min(indiceInicioUsuarioActual + IMAGENES_POR_CARGAR, imagenesUsuarioActual.length);

    for (let i = indiceInicioUsuarioActual; i < indiceFin; i++) {
        const divContenedor = document.createElement('div');
        divContenedor.classList.add('contenedor-imagenes');

        const figure = document.createElement('figure');
        const img = document.createElement('img');
        img.classList.add('imgPerfil', `img-${i % 6}`); // Clase única por imagen

        img.classList.add('imgPerfil');
        img.src = '../../' + imagenesUsuarioActual[i];
        img.alt = 'Imagen perfil usuario';

        figure.appendChild(img);
        divContenedor.appendChild(figure);

        const botonDescripcion = document.createElement('button');
        botonDescripcion.classList.add('btn-ver-descripcion');
        botonDescripcion.innerHTML = '<i class="bi bi-card-text"></i>';
        divContenedor.appendChild(botonDescripcion);
        botonDescripcion.addEventListener('click', (e) => {
            e.stopPropagation();
            const descripcion = descripcionesUsuarioActual[i] ? descripcionesUsuarioActual[i] : 'Sin descripción disponible';
            alert(descripcion);
        });

        contenedor.appendChild(divContenedor);
    }

    // Mostrar/ocultar botones según índice y cantidad de imágenes
    btnCargarMenos.style.display = indiceInicioUsuarioActual > 0 ? 'inline-block' : 'none';
    btnCargarMas.style.display = indiceFin < imagenesUsuarioActual.length ? 'inline-block' : 'none';
}

// Eventos para los botones de paginación del modal de otro usuario
document.getElementById('btn-cargar-mas').addEventListener('click', () => {
    if (indiceInicioUsuarioActual + IMAGENES_POR_CARGAR < imagenesUsuarioActual.length) {
        indiceInicioUsuarioActual += IMAGENES_POR_CARGAR;
        paginarImagenesUsuarioActual();
    }
});
document.getElementById('btn-cargar-menos').addEventListener('click', () => {
    if (indiceInicioUsuarioActual - IMAGENES_POR_CARGAR >= 0) {
        indiceInicioUsuarioActual -= IMAGENES_POR_CARGAR;
        paginarImagenesUsuarioActual();
    }
});
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
 function mostrarModal() {
            document.getElementById('modalPerfil').style.display = 'block';
        }

        function cerrarModal() {
            document.getElementById('modalPerfil').style.display = 'none';
        }

        // Opcional: cerrar haciendo clic fuera del contenido
        window.onclick = function (event) {
            const modal = document.getElementById('modalPerfil');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

</script>


<div id="modalPerfil" class="modal">
    <div class="modal-contenido">
        <span class="cerrar" onclick="cerrarModal()">&times;</span>
        <div class="infoPerfil">
            <img class="imgPerfilPrev2" id="perfilImagen" src="" alt="Profile Picture">
            <p class="nombreUsuarioPopUp"></p>
            <p class="nombreCompletoPopUp"></p>
            <p class="descripcionPopUp"></p> 
            <p class="dores">Seguidores:
            </p>
            <p> <span id="seguidores"></span></p>
            <p class="idos">Seguidos:</p>
            <p> <span id="seguidos"></span></p>
            <button id="btn-cargar-menos" class="btn-cargar-menos" style="display:none;">⮜</button>
            <div id="contenedor-imagenes-modal"></div>
            <button id="btn-cargar-mas" class="btn-cargar-mas" style="display:none;">⮞</button>
            
        </div>
    </div>
</div>
 <script>
        
        const imagenesPerfil = <?= json_encode($imagenesPerfil) ?>;
        const descripcionesPerfil = <?= json_encode($descripcionesPublicaciones) ?>;
        const contenedorImagenes = document.getElementById('contenedor-imagenes-modal');
        const btnCargarMas = document.getElementById('btn-cargar-mas');
        const btnCargarMenos = document.getElementById('btn-cargar-menos');

        let indiceInicio = 0;
        const IMAGENES_POR_CARGAR = 6;

        function cargarImagenes() {
            contenedorImagenes.innerHTML = '';

            const indiceFin = Math.min(indiceInicio + IMAGENES_POR_CARGAR, imagenesPerfil.length);

            for (let i = indiceInicio; i < indiceFin; i++) {
                const divContenedor = document.createElement('div');
                divContenedor.classList.add('contenedor-imagenes');

                const figure = document.createElement('figure');
                const img = document.createElement('img');
                img.classList.add('imgPerfil');
                img.src = '../../' + imagenesPerfil[i];
                img.alt = 'Imagen perfil usuario';

                figure.appendChild(img);
                divContenedor.appendChild(figure);


                // ⬇ Aquí añadimos el botón con el icono
          


                const botonDescripcion = document.createElement('button');
                botonDescripcion.classList.add('btn-ver-descripcion');
                botonDescripcion.innerHTML = '<i class="bi bi-card-text"></i>';
                divContenedor.appendChild(botonDescripcion);
                botonDescripcion.addEventListener('click', (e) => {
                    e.stopPropagation(); // para que no se dispare zoom ni nada raro

                    const descripcion = descripcionesPerfil[i] ? descripcionesPerfil[i] : 'Sin descripción disponible';
                    alert(descripcion);
                });




                contenedorImagenes.appendChild(divContenedor);


            }


            // Mostrar/ocultar botones según índice y cantidad de imágenes
            btnCargarMenos.style.display = indiceInicio > 0 ? 'inline-block' : 'none';
            btnCargarMas.style.display = indiceFin < imagenesPerfil.length ? 'inline-block' : 'none';
        }

        btnCargarMas.addEventListener('click', () => {
            if (indiceInicio + IMAGENES_POR_CARGAR < imagenesPerfil.length) {
                indiceInicio += IMAGENES_POR_CARGAR;
                cargarImagenes();
            }
        });

        btnCargarMenos.addEventListener('click', () => {
            if (indiceInicio - IMAGENES_POR_CARGAR >= 0) {
                indiceInicio -= IMAGENES_POR_CARGAR;
                cargarImagenes();
            }
        });

        // Carga inicial
        cargarImagenes();


        document.addEventListener('click', function (e) {
            const esImagen = e.target.matches('.contenedor-imagenes img');

            if (esImagen) {
                // Toggle clase zoomed solo en la imagen clicada
                e.target.classList.toggle('zoomed');
            } else {
                // Si clic fuera de imagen, quitar zoom a todas las imágenes
                document.querySelectorAll('.contenedor-imagenes img.zoomed').forEach(img => {
                    img.classList.remove('zoomed');
                });
            }
        });
        document.addEventListener('DOMContentLoaded', () => {
            if (localStorage.getItem('abrirModalPerfil') === 'true') {
                mostrarModal();
                localStorage.removeItem('abrirModalPerfil');
            }
        });
$.ajax({
    url: '../../php/get_usuario.php?id=' + id_usuario,
    type: 'GET',
    dataType: 'json',
    success: function(data) {
        if (!data.error) {
            $('#modalNombre').text(data.usuario);
            $('#seguidores').text(data.num_seguidores);
            $('#seguidos').text(data.num_seguidos);
            // Y lo que quieras más...
        }
    }
});

        document.getElementById('numSeguidores').textContent = totalSeguidores + ' Seguidores';
        document.getElementById('numSeguidos').textContent = totalSeguidos + ' Siguiendo';


    </script>




</body>
</html>
