<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['Identificacion'])) {
    // Si no ha iniciado sesión, redirigir al inicio de sesión
    header("Location: /dashboard/cjs/login/login.php");
    exit;
}

// Conectar a la base de datos
require_once '../../../config/db.php';
require_once '../../../router.php';

// Obtener el ID del administrador a actualizar desde la URL
$id = $_GET['id'] ?? '';

if (empty($id)) {
    echo "ID de administrador no proporcionado.";
    exit();
}

// Procesar el formulario de actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $identificacion = $_POST['identificacion'];
    $contrasena = $_POST['contrasena'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];

    // Validar que los campos obligatorios no estén vacíos
    if (!empty($nombres) && !empty($apellidos) && !empty($identificacion) && !empty($direccion) && !empty($telefono) && !empty($correo)) {
        
        // Iniciar la consulta SQL
        $query = "UPDATE usuarios SET Nombres = :nombres, Apellidos = :apellidos, Identificacion = :identificacion, Direccion = :direccion, Telefono = :telefono, Correo_electronico = :correo";

        // Si se ha proporcionado una nueva contraseña, añadirla a la consulta
        if (!empty($contrasena)) {
            $hashedPassword = password_hash($contrasena, PASSWORD_BCRYPT);
            $query .= ", contraseña = :contrasena";
        }

        $query .= " WHERE ID_usuario = :id AND ID_rol = 'Administrador'";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':nombres', $nombres);
        $stmt->bindParam(':apellidos', $apellidos);
        $stmt->bindParam(':identificacion', $identificacion);
        if (!empty($contrasena)) {
            $stmt->bindParam(':contrasena', $hashedPassword);
        }
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            // Redirigir después de la actualización
            header("Location: index.php");
            exit();
        } else {
            echo "Error al intentar actualizar el administrador.";
        }
    } else {
        echo "Todos los campos obligatorios deben ser llenados.";
    }
} else {
    // Obtener los datos actuales del administrador
    $query = "SELECT * FROM usuarios WHERE ID_usuario = :id AND ID_rol = 'Administrador'";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        echo "Administrador no encontrado.";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - Editar Administrador</title>
    <link rel="stylesheet" type="text/css" href="../../../assets/styles.css">
    <style>
       body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: url('../../../assets/fondo.jpg'); /* Reemplaza con la ruta de tu imagen */
            background-size: cover; /* Asegura que la imagen cubra todo el fondo */
            background-position: center; /* Centra la imagen */
            background-repeat: no-repeat; /* Evita que la imagen se repita */
            margin: 0;
            font-family: 'Roboto', sans-serif;
        }

        .form-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-group button {
            background-color: #6f42c1;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .form-group button:hover {
            background-color: #5a2d91;
        }

    </style>
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="../../../assets/Logo.png" alt="Logo de la empresa" class="logo">
        </div>
        <div class="title">
            <h1>Editar Administrador</h1>
        </div>
        <div class="datetime">
            <?php
                date_default_timezone_set('America/Bogota');
                $fechaActual = date("d/m/Y");
                $horaActual = date("h:i a");
            ?>
            <div class="datetime">
                <div class="fecha">
                    <p>Fecha actual: <?php echo $fechaActual; ?></p>
                </div>
                <div class="hora">
                    <p>Hora actual: <?php echo $horaActual; ?></p>
                </div>
            </div>
        </div>
    </header>
    <section class="admin">
        <div class="form-container">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . urlencode($id); ?>" method="post">
                <div class="form-group">
                    <label for="nombres">Nombres:</label>
                    <input type="text" id="nombres" name="nombres" value="<?php echo htmlspecialchars($admin['Nombres']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="apellidos">Apellidos:</label>
                    <input type="text" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($admin['Apellidos']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="identificacion">Identificación:</label>
                    <input type="text" id="identificacion" name="identificacion" value="<?php echo htmlspecialchars($admin['Identificacion']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="contrasena">Contraseña :</label>
                    <input type="password" id="contrasena" name="contrasena" placeholder="Ingrese una nueva contraseña solo si desea cambiarla">
                </div>
                <div class="form-group">
                    <label for="direccion">Dirección:</label>
                    <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($admin['Direccion']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($admin['Telefono']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="correo">Correo Electrónico:</label>
                    <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($admin['Correo_electronico']); ?>" required>
                </div>
                <div class="form-group">
                    <button type="submit">Actualizar Administrador</button>
                </div>
            </form>
            <div class="regresar">
                <a href="index.php" class="button boton-centrado">Regresar</a>
            </div>
        </div>
    </section>
    <footer>
        <p>Todos los derechos reservados</p>
    </footer>
</body>
</html>
