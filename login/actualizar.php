<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "colegio");

if ($conexion->connect_error) {
    die("Error al conectar con la base de datos: " . $conexion->connect_error);
}

// Obtener todos los usuarios
$sql = "SELECT ID_Usuario, Contraseña FROM usuarios";
$result = $conexion->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Encriptar la contraseña si no está encriptada
        if (!password_get_info($row["contraseña"])["algo"]) {
            $hashed_password = password_hash($row["Contraseña"], PASSWORD_DEFAULT);
            $update_sql = "UPDATE usuarios SET Contraseña = ? WHERE ID_Usuario = ?";
            $update_stmt = $conexion->prepare($update_sql);
            $update_stmt->bind_param("si", $hashed_password, $row["ID_Usuario"]);
            $update_stmt->execute();
            $update_stmt->close();
        }
    }
}

$conexion->close();
?>
