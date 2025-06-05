    <?php
    header("Content-Type: application/json");
    session_start();
    require_once "../db/db.php"; // Asegúrate de que la ruta es correcta según tu proyecto

    // Comprobar si el usuario está logueado
    if (!isset($_SESSION['user_id'])) {
        http_response_code(403);
        echo json_encode(["status" => "error", "message" => "Debes iniciar sesión para seguir usuarios."]);
        exit;
    }

    $id_usuario = $_SESSION['user_id'];
    $id_seguido = $_POST['id_seguido'] ?? null;

    // Validación de datos
    if (!$id_seguido) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "ID de usuario a seguir no recibido."]);
        exit;
    }

    if ($id_usuario == $id_seguido) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "No puedes seguirte a ti mismo."]);
        exit;
    }

    // Comprobar si ya lo sigue
    $sqlCheck = "SELECT * FROM seguidores WHERE id_usuario = :id_usuario AND id_seguido = :id_seguido";
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->execute([
        'id_usuario' => $id_usuario,
        'id_seguido' => $id_seguido
    ]);

    if ($stmtCheck->rowCount() > 0) {
        echo json_encode(["status" => "error", "message" => "Ya sigues a este usuario."]);
        exit;
    }

    // Insertar en la tabla seguidores
    $sqlInsert = "INSERT INTO seguidores (id_usuario, id_seguido) VALUES (:id_usuario, :id_seguido)";
    $stmtInsert = $pdo->prepare($sqlInsert);

    try {
        $stmtInsert->execute([
            'id_usuario' => $id_usuario,
            'id_seguido' => $id_seguido
        ]);
        echo json_encode(["status" => "success"]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Error en la base de datos: " . $e->getMessage()]);
    }
    ?>
