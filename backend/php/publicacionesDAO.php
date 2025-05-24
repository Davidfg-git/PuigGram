<?php
class PublicacionesDAO {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function insertarPublicacion($id_usuario, $contenido, $tipo, $descripcion) {
        $sql = "INSERT INTO publicaciones (id_usuario, contenido, tipo, descripcion, fecha_publicacion)
                VALUES (:id_usuario, :contenido, :tipo, :descripcion, NOW())";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':contenido', $contenido);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':descripcion', $descripcion);

        return $stmt->execute();
    }
}
?>
