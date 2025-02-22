<?php
class Usuarios {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllUsuarios() {
        $query = "SELECT * FROM usuarios";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getUsuarioById($id) {
        $query = "SELECT * FROM usuarios WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

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
}
?>
