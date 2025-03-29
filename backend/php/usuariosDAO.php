<?php
class UsuariosDAO{
    private $db;

public function createUsuario($nombre, $email, $password) {
        $query = "INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sss", $nombre, $email, $password);
        return $stmt->execute();
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