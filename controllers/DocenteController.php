<?php
require_once '../../config/db.php';
require_once '../../models/DocenteModel.php';

class DocentesController {
    private $db;
    private $docenteModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->docenteModel = new DocenteModel($this->db);
    }

    public function perfil($id) {
        $docente = $this->docenteModel->readDocente($id);
        if ($docente) {
            require_once '../../views/docentes/perfil.php';
        } else {
            echo "Docente no encontrado o no tiene permisos para ver esta página.";
        }
    }
}

// Obtener el ID del docente desde la sesión o una petición GET
$id_docente = $_GET['id'] ?? 1; // Ejemplo, reemplazar según corresponda

$controller = new DocentesController();
$controller->perfil($id_docente);
?>
