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

    public function getDb() {
        return $this->db;
    }

    public function setDb($db) {
        $this->db = $db;
    }
    public function __toString() {
        $usuarios = $this->getAllUsuarios();
        $output = "";
        foreach ($usuarios as $usuario) {
            $output .= implode(", ", $usuario) . "\n";
        }
        return $output;
    }
}
?>
