<?php
session_start();  // Iniciar sesión

// Verificar si el usuario ya está logueado
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

include '../db/db.php';  // Incluir el archivo de conexión a la base de datos

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recibir datos del formulario
    $email = $_POST['username'];  // Correo electrónico
    $password = $_POST['password'];  // Contraseña

    // Consultar si el usuario existe en la base de datos usando el correo
    $sql = "SELECT * FROM Usuarios WHERE correo = :email";  // Usar 'correo' en lugar de 'nombre_usuario'
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si el usuario existe y la contraseña es correcta (comparación directa en texto plano)
    if ($user && $password === $user['contrasena']) {  // Comparar contraseñas en texto plano
        // Iniciar sesión
        $_SESSION['user_id'] = $user['id_usuario'];  // Cambiar 'id' a 'id_usuario'
        $_SESSION['username'] = $user['nombre_usuario'];  // Cambiar 'username' a 'nombre_usuario'

        // Redirigir a la página principal (mainPage.html)
        header("Location: mainPage.php");
        exit;
    } else {
        // Si las credenciales no son correctas
        $error_message = "Correo o contraseña incorrectos.";
    }
}

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1, minimum-scale=1">
    <link rel="icon" href="../../../icono.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/assets/styles/styles.css">
    
</head>
<body class="bg-light">
   
    <header class="header">
        <h1 class="titulo">PuigGram</h1>
    </header>
    
    <main class="layout-container">
        <div class="phone-container">
            <img src="../../public/assets/images/imagenIndex.png" alt="Imagen del móvil" class="phone-image">
        </div>

        <!-- Formulario de Login -->
        <div class="login-form-container">
            <form action="index.php" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" name="username" id="email" class="form-control" placeholder="Escribe tu correo" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Escribe tu contraseña" required>
                </div>
                <div class="d-flex">
                    <a href="changePassword.php" class="text-decoration-none">¿Has olvidado la contraseña?</a><br><br>
                    <a href="register.php" class="text-decoration-none">¿Aún no te has registrado?</a>
                </div>
                <button type="submit" class="btn btn-dark w-100">Sign In</button>
            </form>

            <?php
            // Mostrar el mensaje de error si las credenciales no son correctas
            if (isset($error_message)) {
                echo '<p style="color: red;">' . $error_message . '</p>';
            }
            ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
