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
        if ($imagenPerfil === null){
            $query = "UPDATE usuarios 
                  SET  nombre_completo = :nombre, 
                      nombre_usuario = :nombreUsuario,
                      descripcion = :presentacion 
                  WHERE id_usuario = :id";
    
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':nombre' => $nombre,
            ':nombreUsuario' => $nombreUsuario,
            ':presentacion' => $presentacion,
            ':id' => $id
        ]);
        } else {
        $query = "UPDATE usuarios 
                  SET imagen_perfil = :imagenPerfil,  
                      nombre_completo = :nombre, 
                      nombre_usuario = :nombreUsuario,
                      descripcion = :presentacion 
                  WHERE id_usuario = :id";
    
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':imagenPerfil' => $imagenPerfil,
            ':nombre' => $nombre,
            ':nombreUsuario' => $nombreUsuario,
            ':presentacion' => $presentacion,
            ':id' => $id
        ]);
    }
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
    
    public function updateUsuarioPassword($id, $nuevaContrasena) {
        $query = "UPDATE usuarios SET password = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $nuevaContrasena, $id);
        return $stmt->execute();
    }

    
    
}


    ?>