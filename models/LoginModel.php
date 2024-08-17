<?php
include_once 'config/db.php';
// Ejemplo de archivo index.php
include_once 'controllers/HomeController.php';
session_start(); // Asegúrate de iniciar la sesión

$controller = new HomeController();
$controller->index(); // Llama al método que necesitas


class LoginModel {

    public function ingreso($identificacion, $password){
        $conn = Database::connect();
        
        // Consulta para obtener el usuario por identificación
        $sql = "SELECT * FROM usuarios WHERE identificacion = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $identificacion);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            // Verificar la contraseña utilizando password_verify()
            if (password_verify($password, $row['clave'])) {
                // Inicio de sesión exitoso
                return true;
            } else {
                // La contraseña es incorrecta
                return false;
            }
        } else {
            // El usuario no existe
            return false;
        }
    }
}
?>
