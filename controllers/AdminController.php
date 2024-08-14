<?php
// AdminController.php

require_once 'models/AdminModel.php';

class AdminController {
    
    public function actualizarAsignatura() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Validar y obtener los datos del formulario
            $id_asignatura = $_POST['id_asignatura'];
            $nombre_asignatura = $_POST['nombre_asignatura'];

            // Actualizar la asignatura utilizando el modelo AdminModel
            $resultado = AdminModel::actualizarAsignatura($id_asignatura, $nombre_asignatura);

            if ($resultado) {
                // Redirigir de vuelta a la página principal después de la actualización
                header("Location: index.php"); // Ajusta la ruta según tu estructura
                exit();
            } else {
                echo "Error al intentar actualizar la asignatura.";
            }
        } else {
            // Manejar cualquier acceso incorrecto o directo a esta acción
            echo "Acceso no permitido.";
        }
    }

}
?>
