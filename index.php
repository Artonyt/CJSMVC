<?php
// Verificar si se envió una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el controlador y la acción desde los datos del formulario
    $controllerName = isset($_POST['controller']) ? $_POST['controller'] : 'HomeController'; // Controlador por defecto
    $actionName = isset($_POST['action']) ? $_POST['action'] : 'index'; // Acción por defecto

    // Incluir el archivo del controlador
    require_once 'controllers/' . $controllerName . '.php';

    // Crear una instancia del controlador y llamar a la acción
    $controllerInstance = new $controllerName();
    if (method_exists($controllerInstance, $actionName)) {
        $controllerInstance->$actionName();
    } else {
        echo "La acción '$actionName' no existe en el controlador '$controllerName'.";
    }
}
?>
