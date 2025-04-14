<?php
$host = 'localhost';  // El servidor está en localhost
$port = 3306;         // El puerto por defecto para MySQL
$dbname = 'PuigGram'; // Nombre de la base de datos
$username = 'root';    // El nombre de usuario para la base de datos
$password = 'Davidfg04.';  // La contraseña de MySQL

try {
    // Establecer la conexión a la base de datos con PDO
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Configurar PDO para manejar errores
} catch (PDOException $e) {
    // Si hay error en la conexión, mostrarlo
    die("Conexión fallida: " . $e->getMessage());
}
?>
    