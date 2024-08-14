<?php
class DocenteModel {
    private $conn;
    private $table_name = "usuarios";

    public $id;
    public $nombres;
    public $apellidos;
    public $identificacion;
    public $direccion;
    public $telefono;
    public $correo_electronico;
    public $rol;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readDocente($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? AND rol = 'Docente'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
