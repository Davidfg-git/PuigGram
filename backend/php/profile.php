<?php
session_start();  // Iniciar sesión
    $id = $_SESSION['user_id'];
    //$id = 2; // Temporal
    include '../../backend/db/db.php';  // Incluir archivo de conexión a la base de datos
    //seleccionar datos de la base de datos
    $sql = "SELECT * FROM Usuarios WHERE id_usuario = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    // utilizar los datos seleccionados para mostrarlos en el placeholder del formulario
    $nombre = $user['nombre_completo']; 
    $username = $user['nombre_usuario'];
    $descripcion = $user['descripcion'];
    $imagen = $user['imagen_perfil'];   
   


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="../../public/assets/styles/mainStyle.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">
    
</head>
<body>
    <div class="sidebar">
        <h1>PuigGram</h1>
        <ul>
            <li><a href="mainPage.html"><i class="bi bi-house"></i> Inicio</a></li>
            <li><a href="explore.html"><i class="bi bi-search"></i>Explorar</a></li>
            <li><a href="messages.html"><i class="bi bi-chat"></i>Mensajes</a></li>
            <li><a href="notifications.html"><i class="bi bi-bell"></i>Notificaciones</a></li>
            <li><a href="publish.html"><i class="bi bi-plus-square"></i>Publicar</a></li>
            <li><a href="profile.php"><i class="bi bi-person"></i>Perfil</a></li>
            <li><a href="settings.html"><i class="bi bi-gear"></i>Configuración</a></li>
        </ul>
    </div>

    <div class="main">
        <div class="post">
            <div class="post-header">
                <p class="inicioText">PERFIL</p>
            </div>
        </div>
    </div>
    <div class="contenido" style="justify-content: center;">
    <form action="changeProfile.php" method="POST" enctype="multipart/form-data" class="formularioConfiguracion">
            <section class="seccionesPerfil">
            <label for="file" class="formularioCambioPerfil">Imagen de perfil </label> 
            
                <img class="imgPerfilPrev" src="./img/imgPerfil.webp" alt="Profile Picture">
            
            
            <input type="file" id="fileInput" style="display: none;"  name="imagen_perfil"  accept="image/*" onchange="mostrarNombreArchivo()">
            <!-- Botón que simula el input file -->
            <button class="editarImagen" type="button" onclick="document.getElementById('fileInput').click();">
              Editar imagen
            </button>
            <p id="fileNameDisplay" style="display: inline; margin-top: 10px; font-size: 10px; font-style: italic;"></p>
            <script>
                function mostrarNombreArchivo() {
                    const fileInput = document.getElementById('fileInput');
                    const fileNameDisplay = document.getElementById('fileNameDisplay');
                    if (fileInput.files.length > 0) {
                        fileNameDisplay.textContent = `Imagen seleccionada: ${fileInput.files[0].name}`;
                   
                    }
                }
            </script>
        
        </section>

        <section  class="seccionesPerfil">
            <label for=""  class="formularioCambioPerfil">Nombre </label> 
            <input type="text" class="nombre" value=<?php echo $nombre; ?> id="nombre" name="nombre">
        </section>
        
        <section  class="seccionesPerfil">
            <label for=""  class="formularioCambioPerfil" >Nombre de usuario</label> 
            <input type="text" class="nombre" id="userName"  name="userName" value=<?php echo $username; ?> >
        </section>

        <section  class="seccionesPerfil">
            <label for=""  class="formularioCambioPerfil">Presentación </label>
            <input type="text" class="nombre"  id="presentacion" name="presentacion" max=300 value="<?php echo $descripcion; ?>"> 
        </section>
    
        <section  class="seccionesPerfil">
           <button class="guardarCambios">Guardar Cambios</button>
        </section>
           </form>
    </div>
    <div class="suggestions">
        <h2>Sugerencias</h2>
        <ul>
            <li>
                <img src="profile-placeholder.png" alt="Profile Picture">
                <span>@Usuario1</span>
                <button>Seguir</button>
            </li>
            <li>
                <img src="./img/imgPerfil.webp" alt="Profile Picture">
                <span>@Usuario2</span>
                <button>Seguir</button>
            </li>
            <li>
                <img src="profile-placeholder.png" alt="Profile Picture">
                <span>@Usuario3</span>
                <button>Seguir</button>
            </li>
        </ul>
    </div>
    
</body>
</html>
