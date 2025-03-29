<?php
session_start();
include 'db.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger datos del formulario
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);

    // Verificar si el correo ya existe en la base de datos
    $sql = "SELECT id_usuario FROM Usuarios WHERE correo = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);

    if ($stmt->fetch()) {
        $error_message = "Este correo ya está registrado.";
    } else {
        // Hashear la contraseña antes de guardarla
        //$hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insertar el nuevo usuario
        $sql = "INSERT INTO Usuarios (correo, contrasena, nombre_completo, nombre_usuario) 
                VALUES (:email, :password, :name, :username)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'email' => $email,
            'password' => $password,
            'name' => $name,
            'username' => $username
        ]);

        // Iniciar sesión automáticamente después del registro
        //$_SESSION['user_id'] = $pdo->lastInsertId();
        //$_SESSION['username'] = $username;

        header("Location: index.php");
        exit;
    }
}
?>










<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1, minimum-scale=1">
    <!-- Custom CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="registerstyles.css">
    
</head>
<body class="bg-light">
   
    <header class="header">
        <h1 class="titulo">PuigGram</h1>
    </header>
    
    <main class="layout-container">
       
        <!-- Login Form -->
        <div class="login-form-container">
            
        <form action="" method="POST" class="login-form">

                <p class="titularForm">PuigGram</p>
                <div class="mb-2">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" id="email" class="form-control"  name="email" placeholder="Escribe tu correo">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" id="password" class="form-control"  name="password" placeholder="Escribe tu contraseña">
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="name" id="name" class="form-control" name="name" placeholder="Escribe tu nombre">
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Nombre de Usuario</label>
                    <input type="username" id="username" class="form-control"  name="username" placeholder="Escribe tu nombre">
                </div>
                <div class="d-flex">
                    <a href="/index.html" class="text-decoration-none">¿Ya estás registrad@?</a>
                </div>
                <div  class="d-flex">
                    <input type="checkbox" id="privacy-policy" name="privacy-policy" required>
                    <label for="privacy-policy"><a href="/privacyPolicy.html">Aceptar Política de Privacidad</a></label>
                </div>
                
                <button type="submit" class="btn btn-dark w-100">Sign In</button>
            </form>
        </div>
    </main>

    <!-- Bootstrap JS (si lo necesitas, si no puedes eliminarlo) -->
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
