<?php
session_start();
$id = $_SESSION['user_id'] ?? null;

if (!$id) {
    header("Location: ../../login.php");
    exit;
}

include '../backend/db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['imagen_redimensionada'])) {
    $_SESSION['imagen_redimensionada'] = $_POST['imagen_redimensionada'];
    header('Location: ../backend/php/profile.php');
    exit;
}

$src = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['imagen'])) {
    $src = $_POST['imagen'];
} elseif (isset($_GET['imagen'])) {
    $src = urldecode($_GET['imagen']);
} else {
    $sql = "SELECT imagen_perfil FROM Usuarios WHERE id_usuario = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && !empty($user['imagen_perfil'])) {
        $imagenCodificada = base64_encode($user['imagen_perfil']);
        $src = "data:image/png;base64," . $imagenCodificada;
    } else {
        $src = "../../public/assets/default/default-image.jpg";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Imagen de Perfil</title>
    <style>
        body {
            font-family: sans-serif;
            background: #f5f5f5;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px;
        }

        h2 {
            margin-bottom: 20px;
        }

        .editor-container {
            position: relative;
            width: 400px;
            height: 400px;
            background-color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 16px;
            overflow: hidden;
        }

        .editor-circle {
            position: absolute;
            width: 320px;
            height: 320px;
            border-radius: 50%;
            pointer-events: none;
            box-shadow: 0 0 0 9999px rgba(0,0,0,0.5);
        }

        #imagePreview {
            position: absolute;
            top: 50%;
            left: 50%;
            transform-origin: center center;
            max-width: none;
            max-height: none;
            user-select: none;
        }

        .controls {
            margin-top: 25px;
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        button {
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            background-color: #3897f0;
            color: white;
            cursor: pointer;
            font-size: 14px;
        }

        .save-btn {
            background-color: #4CAF50;
        }

        .cancel-btn {
            background-color: #f44336;
        }
    </style>
</head>
<body>
    <h2>Editar Imagen de Perfil</h2>

    <div class="editor-container" id="imageContainer">
        <img id="imagePreview" src="<?= htmlspecialchars($src) ?>" alt="Imagen a editar" draggable="false">
        <div class="editor-circle"></div>
    </div>

    <div class="controls">
        <button id="zoomIn">Zoom +</button>
        <button id="zoomOut">Zoom -</button>
        <button id="rotateLeft">⟲</button>
        <button id="rotateRight">⟳</button>
        <button class="save-btn" id="saveBtn">Guardar</button>
        <button class="cancel-btn" onclick="window.history.back()">Cancelar</button>
    </div>

    <script>
        const image = document.getElementById('imagePreview');
        const container = document.getElementById('imageContainer');

        let scale = 1;
        let rotation = 0;
        let offsetX = 0;
        let offsetY = 0;
        let isDragging = false;
        let lastX, lastY;

        image.onload = function () {
            centerImage();
        };

        function centerImage() {
            offsetX = 0;
            offsetY = 0;
            updateTransform();
        }

        function clampOffsets() {
            const imgWidth = image.naturalWidth * scale;
            const imgHeight = image.naturalHeight * scale;

            const maxOffsetX = Math.max(0, (imgWidth - 320) / 2);
            const maxOffsetY = Math.max(0, (imgHeight - 320) / 2);

            offsetX = Math.max(-maxOffsetX, Math.min(maxOffsetX, offsetX));
            offsetY = Math.max(-maxOffsetY, Math.min(maxOffsetY, offsetY));
        }

        function updateTransform() {
            clampOffsets();
            image.style.transform = `translate(calc(-50% + ${offsetX}px), calc(-50% + ${offsetY}px)) scale(${scale}) rotate(${rotation}deg)`;
        }

        container.addEventListener('mousedown', (e) => {
            isDragging = true;
            lastX = e.clientX;
            lastY = e.clientY;
            e.preventDefault();
        });

        window.addEventListener('mouseup', () => {
            isDragging = false;
        });

        window.addEventListener('mousemove', (e) => {
            if (!isDragging) return;

            const dx = e.clientX - lastX;
            const dy = e.clientY - lastY;

            offsetX += dx;
            offsetY += dy;

            lastX = e.clientX;
            lastY = e.clientY;

            updateTransform();
        });

        document.getElementById('zoomIn').addEventListener('click', () => {
            scale *= 1.1;
            updateTransform();
        });

        document.getElementById('zoomOut').addEventListener('click', () => {
            scale /= 1.1;
            updateTransform();
        });

        document.getElementById('rotateLeft').addEventListener('click', () => {
            rotation -= 15;
            updateTransform();
        });

        document.getElementById('rotateRight').addEventListener('click', () => {
            rotation += 15;
            updateTransform();
        });

        document.getElementById('saveBtn').addEventListener('click', () => {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');

            const diameter = 320;
            canvas.width = diameter;
            canvas.height = diameter;

            const tempImg = new Image();
            tempImg.crossOrigin = 'Anonymous';
            tempImg.onload = () => {
                ctx.save();
                ctx.beginPath();
                ctx.arc(diameter / 2, diameter / 2, diameter / 2, 0, 2 * Math.PI);
                ctx.clip();

                ctx.translate(diameter / 2 + offsetX, diameter / 2 + offsetY);
                ctx.rotate(rotation * Math.PI / 180);
                ctx.scale(scale, scale);
                ctx.drawImage(tempImg, -tempImg.width / 2, -tempImg.height / 2);
                ctx.restore();

                canvas.toBlob(blob => {
                    const reader = new FileReader();
                    reader.onloadend = function () {
                        const base64data = reader.result;
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = window.location.href;

                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'imagen_redimensionada';
                        input.value = base64data;

                        form.appendChild(input);
                        document.body.appendChild(form);
                        form.submit();
                    };
                    reader.readAsDataURL(blob);
                }, 'image/png');
            };

            tempImg.src = image.src;
        });

        // Evitar arrastrar imagen fuera del navegador
        image.addEventListener('dragstart', e => e.preventDefault());
    </script>
</body>
</html>
