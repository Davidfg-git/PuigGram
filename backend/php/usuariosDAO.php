<?php


class UsuariosDAO{
    private $db;


    

    public function __construct($db) {
        $this->db = $db; // Asignar la conexión a la base de datos
    }

    public function createUsuario($nombre, $email, $password) {
        $query = "INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sss", $nombre, $email, $password);
        return $stmt->execute();
    }
    
    // actualizar perfil
    public function updatePerfilUsuario($id, $imagenPerfil, $nombre, $nombreUsuario, $presentacion) {
        if ($imagenPerfil === null) {
            // Si no se subió una nueva imagen, no actualizar la columna `imagen_perfil`
            $query = "UPDATE usuarios 
                      SET nombre_completo = :nombre, 
                          nombre_usuario = :nombreUsuario, 
                          descripcion = :presentacion 
                      WHERE id_usuario = :id";
            $stmt = $this->db->prepare($query);
            $resultado = $stmt->execute([
                'nombre' => $nombre,
                'nombreUsuario' => $nombreUsuario,
                'presentacion' => $presentacion,
                'id' => $id
            ]);
        } else {
            // Si se subió una nueva imagen, actualizar la columna `imagen_perfil`
            $query = "UPDATE usuarios 
                      SET imagen_perfil = :imagenPerfil, 
                          nombre_completo = :nombre, 
                          nombre_usuario = :nombreUsuario, 
                          descripcion = :presentacion 
                      WHERE id_usuario = :id";
            $stmt = $this->db->prepare($query);
            $resultado = $stmt->execute([
                'imagenPerfil' => $imagenPerfil,
                'nombre' => $nombre,
                'nombreUsuario' => $nombreUsuario,
                'presentacion' => $presentacion,
                'id' => $id
            ]);
        }
    
        return $resultado;
    }
    
    public function updateUsuario($id, $nombre, $email, $password) {
        $query = "UPDATE usuarios SET nombre = ?, email = ?, password = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sssi", $nombre, $email, $password, $id);
        return $stmt->execute();
    }

    public function deleteUsuario($id) {
        $query = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function updateUsuarioNombre($id, $nuevoNombre) {
        $query = "UPDATE usuarios SET nombre = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $nuevoNombre, $id);
        return $stmt->execute();
    }

    public function updateUsuarioEmail($id, $nuevoEmail) {
        $query = "UPDATE usuarios SET email = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $nuevoEmail, $id);
        return $stmt->execute();
    }
    
    public function updateUsuarioPassword($id, $passwordOld, $passwordNew) {
    $query = "SELECT contrasena FROM usuarios WHERE id_usuario = :id";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $passwordActual = $result['contrasena'];

        if ($passwordActual === $passwordOld) {
            $query = "UPDATE usuarios SET contrasena = :newPassword WHERE id_usuario = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':newPassword', $passwordNew, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute(); // Devuelve true si se actualizó correctamente
        }
    }

    return false; // Si no coincide o no existe el usuario
}

}


    ?>