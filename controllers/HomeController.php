<?php
// HomeController.php
include_once '../models/LoginModel.php'; // Asegúrate de que el modelo esté incluido

class HomeController {

    private $loginModel;

    public function __construct() {
        // Instancia el modelo de login
        $this->loginModel = new LoginModel();
    }

    public function index() {
        // Verificar si el usuario está autenticado
        if ($this->isUserLoggedIn()) {
            echo "Bienvenido a la página principal";
        } else {
            // Redirigir a la página de login si el usuario no está autenticado
            header("Location: login.php");
            exit();
        }
    }

    public function about() {
        // Verificar si el usuario está autenticado
        if ($this->isUserLoggedIn()) {
            echo "Esta es la página 'Acerca de nosotros'";
        } else {
            // Redirigir a la página de login si el usuario no está autenticado
            header("Location: login.php");
            exit();
        }
    }

    private function isUserLoggedIn() {
        // Verificar si la sesión del usuario está activa
        return isset($_SESSION["usuario"]);
    }

    // Otros métodos de acción según lo necesites para tu aplicación
}
?>
