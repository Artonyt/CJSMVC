<?php
// Incluir el archivo de conexión a la base de datos
require_once '../../../config/db.php';

// Variables para almacenar los valores del formulario
$nombres = $apellidos = $identificacion = $contraseña = $direccion = $telefono = $correo_electronico = '';
$errorMessage = '';

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir y sanitizar los datos del formulario
    $nombres = htmlspecialchars($_POST['nombres'] ?? '');
    $apellidos = htmlspecialchars($_POST['apellidos'] ?? '');
    $identificacion = htmlspecialchars($_POST['identificacion'] ?? '');
    $contraseña = htmlspecialchars($_POST['contraseña'] ?? '');
    $direccion = htmlspecialchars($_POST['direccion'] ?? '');
    $telefono = htmlspecialchars($_POST['telefono'] ?? '');
    $correo_electronico = htmlspecialchars($_POST['correo_electronico'] ?? '');

    // Validar campos requeridos (puedes agregar más validaciones según tus necesidades)
    if (empty($nombres) || empty($apellidos) || empty($identificacion) || empty($contraseña) || empty($correo_electronico)) {
        $errorMessage = 'Por favor complete todos los campos obligatorios.';
    } else {
        try {
            // Preparar la consulta SQL para insertar un nuevo usuario
            $query = "INSERT INTO usuarios (Nombres, Apellidos, Identificacion, Contraseña, Direccion, Telefono, Correo_electronico, ID_rol) 
                      VALUES (:nombres, :apellidos, :identificacion, :contrasena, :direccion, :telefono, :correo_electronico, 'Docente')";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':nombres', $nombres);
            $stmt->bindParam(':apellidos', $apellidos);
            $stmt->bindParam(':identificacion', $identificacion);
            $stmt->bindParam(':contrasena', $contraseña);
            $stmt->bindParam(':direccion', $direccion);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':correo_electronico', $correo_electronico);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Éxito: redirigir con mensaje
                $successMessage = 'Usuario creado exitosamente.';
                echo "<script>
                        alert('{$successMessage}');
                        window.location.href = 'index.php'; // Redireccionar a la página de inicio
                      </script>";
                exit();
            } else {
                $errorMessage = 'Error al intentar crear el usuario.';
            }
        } catch(PDOException $e) {
            $errorMessage = 'Error de conexión: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - Crear Nuevo Docente</title>
    <link rel="stylesheet" type="text/css" href="../../../assets/styles.css">
    <style>
        /* Estilos específicos para este formulario */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
    
        .logo-container {
            display: flex;
            justify-content: center;
            margin-bottom: 10px;
        }
        .logo {
            max-width: 150px;
        }
        .title {
            text-align: center;
            margin-bottom: 20px;
        }
        .admin {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .subtitulo-admin {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
        }
        .crear-asignatura {
            text-align: center;
            margin-bottom: 20px;
        }
        .crear-asignatura a.button {
            display: inline-block;
            background-color: #28a745;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .crear-asignatura a.button:hover {
            background-color: #218838;
        }
        .form-container {
            max-width: 400px;
            margin: auto;
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .form-group input[type="text"] {
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        .form-group button {
            width: 100%;
            padding: 10px;
            background-color: #66BB6A;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .form-group button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<header>
    <div class="logo-container">
        <img src="../../../assets/Logo.png" alt="Logo de la empresa" class="logo">
    </div>
    <div class="title">
        <h1>Crear Nuevo Docente</h1>
    </div>
</header>
<section class="admin">
<section class="asignaturas" id="section-asignaturas">
<div class="descripcion-ambiente">
                <p>Nuevo Docente</p>
            </div>
    <form id="createUsuarioForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <?php if (!empty($errorMessage)) : ?>
            <p class="error-message"><?php echo $errorMessage; ?></p>
        <?php endif; ?>
        <label for="nombres">Nombres:</label><br>
        <input type="text" id="nombres" name="nombres" value="<?php echo $nombres; ?>" required><br><br>

        <label for="apellidos">Apellidos:</label><br>
        <input type="text" id="apellidos" name="apellidos" value="<?php echo $apellidos; ?>" required><br><br>

        <label for="identificacion">Identificación:</label><br>
        <input type="text" id="identificacion" name="identificacion" value="<?php echo $identificacion; ?>" required><br><br>

        <label for="contraseña">Contraseña:</label><br>
        <input type="password" id="contraseña" name="contraseña" required><br><br>

        <label for="direccion">Dirección:</label><br>
        <input type="text" id="direccion" name="direccion" value="<?php echo $direccion; ?>"><br><br>

        <label for="telefono">Teléfono:</label><br>
        <input type="text" id="telefono" name="telefono" value="<?php echo $telefono; ?>"><br><br>

        <label for="correo_electronico">Correo Electrónico:</label><br>
        <input type="email" id="correo_electronico" name="correo_electronico" value="<?php echo $correo_electronico; ?>" required><br><br>

        <button type="submit">Guardar Usuario</button>
    </form>
</div>
<div class="regresar">
    <a href="index.php" class="button boton-centrado" id="btn-regresar">Regresar </a>
</div>
<div class="salir">
    <button id="btn_salir">Salir</button>
</div>
</section>
</section>
<footer>
    <p>Sena todos los derechos reservados </p>
</footer>
</body>
</html>
