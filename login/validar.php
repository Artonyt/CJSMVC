<?php
session_start();

$error = ""; // Variable para almacenar el mensaje de error

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $password = $_POST["password"];

    // Establecer conexión a la base de datos
    $conexion = new mysqli("localhost", "root", "", "colegio");

    if ($conexion->connect_error) {
        die("Error al conectar con la base de datos: " . $conexion->connect_error);
    }

    // Consultar usuario por identificación usando consultas preparadas
    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE Identificacion = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultadoUsuario = $stmt->get_result();

    if ($resultadoUsuario->num_rows == 1) {
        // Usuario encontrado
        $usuarioData = $resultadoUsuario->fetch_assoc();
        
        // Verificar la contraseña
        if (password_verify($password, $usuarioData["contraseña"])) {
            // Contraseña correcta, iniciar sesión
            $_SESSION["Identificacion"] = $usuarioData["Identificacion"]; // Identificación del usuario
            $_SESSION["usuario"] = $usuarioData["Nombres"]; // Almacenar el nombre del usuario en sesión
            $_SESSION["ID_rol"] = $usuarioData["ID_rol"]; // Almacenar el ID del rol en sesión

            // Redirigir según el rol del usuario
            if ($usuarioData["ID_rol"] == "Administrador") {
                header("Location: ../views/administrador/index.php");
            } elseif ($usuarioData["ID_rol"] == "Docente") {
                header("Location: ../views/docentes/index.php");
            } else {
                // Si el rol no está definido, manejar error
                $error = "Rol de usuario no válido";
                $_SESSION["error"] = $error;
                header("Location: login.php");
            }
        } else {
            // Contraseña incorrecta
            $error = "Contraseña incorrecta";
            $_SESSION["error"] = $error;
            header("Location: login.php");
        }
    } else {
        // Usuario no encontrado
        $error = "Usuario no encontrado";
        $_SESSION["error"] = $error;
        header("Location: login.php");
    }

    // Cerrar conexión y sentencia
    $stmt->close();
    $conexion->close();

    exit();
}
?>
