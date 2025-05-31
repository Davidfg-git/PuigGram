<?php
session_start();
include '../../backend/db/db.php';
include 'usuariosDAO.php'; 
if (!isset($_SESSION['user_id'])) {
    // Redirigir o mostrar error
    header('Location: index.php');
    exit();
}
$id = $_SESSION['user_id'];
$usuariosDAO = new UsuariosDAO($pdo); // O $pdo si usas PDO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_SESSION['user_id']; 
    $passwordOld = $_POST['passwordOld'];
    $passwordNew = $_POST['passwordNew'];

    $resultado = $usuariosDAO->updateUsuarioPassword($id, $passwordOld, $passwordNew);

    if ($resultado) {
        // Redirigir antes de hacer echo
        header("Location: logout.php");
        exit;
    } else {
        $error = "La contraseña actual es incorrecta.";
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
    <link rel="stylesheet" href="../../public/assets/styles/registerstyles.css">
</head>
<body class="bg-light">
   
    <header class="header">
        <h1 class="titulo">PuigGram</h1>
    </header>
    
    <main class="layout-container">
       
        <!-- Login Form -->
        <div class="login-form-container">
            
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="login-form">
    <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

            <p class="titularForm">PuigGram</p>
            
            <div class="mb-2">
                <label for="passwordOld" class="form-label">Contraseña Actual </label>
                <input type="password" id="passwordOld" class="form-control" name="passwordOld" placeholder="Escribe tu contraseña">
            </div>

            <div class="mb-3">
                <label for="passwordNew" class="form-label">Contraseña Nueva</label>
                <input type="password" id="passwordNew" class="form-control" name="passwordNew" placeholder="Escribe tu contraseña">
            </div>
            
            <button type="submit" class="btn btn-dark w-100">Cambiar Contraseña</button>
        </form>
        </div>
    </main>

    <!-- Bootstrap JS (si lo necesitas, si no puedes eliminarlo) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
