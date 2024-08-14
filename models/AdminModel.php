<?php

require_once 'db.php'; // Asegúrate de que db.php contiene la clase Database y establece la conexión PDO

// AdminModel.php

class AdminModel {
    
    public static function actualizarAsignatura($id_asignatura, $nombre_asignatura) {
        try {
            require_once '../config/db.php'; // Ajusta la ruta según tu estructura
            $db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Preparar la consulta SQL para actualizar
            $query = "UPDATE asignaturas SET Nombre_asignatura = :nombre WHERE ID_asignatura = :id";
            $statement = $db->prepare($query);
            $statement->bindParam(':nombre', $nombre_asignatura);
            $statement->bindParam(':id', $id_asignatura);

            // Ejecutar la consulta
            return $statement->execute();
        } catch (PDOException $e) {
            // Manejar el error de base de datos
            echo "Error de base de datos: " . $e->getMessage();
            return false;
        }
    }

}
?>
