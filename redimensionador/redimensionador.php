<?php
session_start();
$id = $_SESSION['user_id'] ?? 1; // ID de prueba si no hay sesión

include '../backend/db/db.php';

// Procesar imagen si viene del cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $tempDir = '../public/temp/';
    if (!file_exists($tempDir)) {
        mkdir($tempDir, 0777, true);
    }

    $fileTmpPath = $_FILES['image']['tmp_name'];
    $fileName = $id . ".png";
    $destination = $tempDir . $fileName;

    if (move_uploaded_file($fileTmpPath, $destination)) {
        echo "Imagen guardada exitosamente.";
    } else {
        echo "Error al mover el archivo.";
    }
    exit;
}

// Obtener la imagen actual del usuario
$sql = "SELECT imagen_perfil FROM Usuarios WHERE id_usuario = :id";  // HAY QUE CAMBIARLO POR EL SRC DE LA PROPIA WEB Y NO DE BBDD
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$imagen = $user['imagen_perfil'];
$imagenCodificada = base64_encode($imagen);
$src = "data:image/png;base64," . $imagenCodificada;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Redimensionar Imagen</title>
    <style>
        body {
            font-family: sans-serif;
            text-align: center;
            margin: 0;
            padding: 30px;
        }
        #imagePreview {
            max-width: 100%;
            max-height: 400px;
            transition: transform 0.3s;
        }
        .controls {
            margin: 20px;
        }
        button {
            padding: 8px 15px;
            margin: 5px;
            background-color: #3897f0;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .save-btn {
            background-color: #4CAF50;
        }
    </style>
</head>
<body>
<script>
  const userId = <?= json_encode($id) ?>;
</script>
    <h2>Editar imagen de perfil</h2>
    <img id="imagePreview" src="<?= $src ?>" alt="Imagen a redimensionar">
    
    <div class="controls">
        <button id="zoomIn">+</button>
        <button id="zoomOut">-</button>
        <button id="rotateLeft">↺</button>
        <button id="rotateRight">↻</button>
        <button class="save-btn" id="saveBtn">Guardar</button>
    </div>

    <script>
        const image = document.getElementById('imagePreview');
        let scale = 1;
        let rotation = 0;

        document.getElementById('zoomIn').addEventListener('click', () => {
            scale += 0.1;
            applyTransform();
        });

        document.getElementById('zoomOut').addEventListener('click', () => {
            if (scale > 0.2) {
                scale -= 0.1;
                applyTransform();
            }
        });

        document.getElementById('rotateLeft').addEventListener('click', () => {
            rotation -= 15;
            applyTransform();
        });

        document.getElementById('rotateRight').addEventListener('click', () => {
            rotation += 15;
            applyTransform();
        });

        function applyTransform() {
            image.style.transform = `scale(${scale}) rotate(${rotation}deg)`;
        }

        document.getElementById('saveBtn').addEventListener('click', () => {
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    const tempImg = new Image();
    const width = 300;
    const height = 300;

    canvas.width = width;
    canvas.height = height;

    tempImg.onload = () => {
        ctx.clearRect(0, 0, width, height);
        ctx.translate(width / 2, height / 2);
        ctx.rotate((rotation * Math.PI) / 180);
        ctx.scale(scale, scale);
        ctx.drawImage(tempImg, -tempImg.width / 2, -tempImg.height / 2);

        canvas.toBlob(blob => {
            const formData = new FormData();
            formData.append('image', blob, `${userId}.png`); // ← aquí se usa el ID como nombre

            fetch("", {
                method: 'POST',
                body: formData
            })
            .then(res => res.text())
            .then(msg => {
                alert(msg);
                window.location.href = '../backend/php/profile.php';
            })
            .catch(err => {
                console.error(err);
                alert('Error al guardar la imagen');
            });
        }, 'image/png');
    };

    tempImg.src = image.src;
});

    </script>
</body>
</html>
