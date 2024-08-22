<?php
session_start(); // Iniciar la sesión

// Destruir todas las sesiones
session_unset();
session_destroy();

// Redirigir a la página de inicio de sesión
header("Location: /dashboard/cjs/login/login.php");
exit();
?>
