<?php
// Incluir el archivo de conexión a la base de datos
require_once '../../../config/db.php';

// Inicializar variables para almacenar los valores del formulario y el mensaje de error
$nombres = $apellidos = $identificacion = $contraseña = $direccion = $telefono = $correo_electronico = '';
$mensaje = '';

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir y sanitizar los datos del formulario
    $nombres = htmlspecialchars($_POST['nombres'] ?? '');
    $apellidos = htmlspecialchars($_POST['apellidos'] ?? '');
    $identificacion = htmlspecialchars($_POST['identificacion'] ?? '');
    $contraseña = htmlspecialchars($_POST['contraseña'] ?? '');
    $direccion = htmlspecialchars($_POST['direccion'] ?? '');
    $telefono = htmlspecialchars($_POST['telefono'] ?? '');
    $correo_electronico = htmlspecialchars($_POST['correo_electronico'] ?? '');

    // Validar campos requeridos
    if (empty($nombres) || empty($apellidos) || empty($identificacion) || empty($contraseña) || empty($correo_electronico)) {
        $mensaje = 'error'; // Mensaje de error si faltan campos
    } else {
        try {
            // Verificar si la identificación, el teléfono o el correo electrónico ya existen
            $query = "SELECT COUNT(*) FROM usuarios WHERE Identificacion = ? OR Telefono = ? OR Correo_electronico = ?";
            $stmt = $db->prepare($query);
            $stmt->bindParam(1, $identificacion);
            $stmt->bindParam(2, $telefono);
            $stmt->bindParam(3, $correo_electronico);
            $stmt->execute();
            
            if ($stmt->fetchColumn() > 0) {
                $mensaje = 'exists'; // Mensaje de error si ya existen
            } else {
                // Encriptar la contraseña
                $contraseñaHash = password_hash($contraseña, PASSWORD_BCRYPT);

                // Preparar la consulta SQL para insertar un nuevo usuario
                $query = "INSERT INTO usuarios (Nombres, Apellidos, Identificacion, Contraseña, Direccion, Telefono, Correo_electronico, ID_rol) 
                          VALUES (:nombres, :apellidos, :identificacion, :contrasena, :direccion, :telefono, :correo_electronico, 'Docente')";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':nombres', $nombres);
                $stmt->bindParam(':apellidos', $apellidos);
                $stmt->bindParam(':identificacion', $identificacion);
                $stmt->bindParam(':contrasena', $contraseñaHash);
                $stmt->bindParam(':direccion', $direccion);
                $stmt->bindParam(':telefono', $telefono);
                $stmt->bindParam(':correo_electronico', $correo_electronico);

                // Ejecutar la consulta
                if ($stmt->execute()) {
                    $mensaje = 'success'; // Mensaje de éxito si se crea el usuario correctamente
                } else {
                    $mensaje = 'error'; // Mensaje de error si falla la creación
                }
            }
        } catch(PDOException $e) {
            $mensaje = 'error'; // Mensaje de error en caso de excepción
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
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
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .form-container {
            margin: auto;
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            margin-bottom: 10px;
        }
        .form-group button {
            width: 100%;
            padding: 10px;
            background-color: #6f42c1;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
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
            <h1>Crear Nuevo Docente</h1>
        </div>
    </header>
    <section class="admin">
        <div class="form-container">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-group">
                    <label for="nombres">Nombres:</label>
                    <input type="text" id="nombres" name="nombres" value="<?php echo htmlspecialchars($nombres); ?>" required>
                </div>
                <div class="form-group">
                    <label for="apellidos">Apellidos:</label>
                    <input type="text" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($apellidos); ?>" required>
                </div>
                <div class="form-group">
                    <label for="identificacion">Identificación:</label>
                    <input type="text" id="identificacion" name="identificacion" value="<?php echo htmlspecialchars($identificacion); ?>" required>
                </div>
                <div class="form-group">
                    <label for="contraseña">Contraseña:</label>
                    <input type="password" id="contraseña" name="contraseña" required>
                </div>
                <div class="form-group">
                    <label for="direccion">Dirección:</label>
                    <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($direccion); ?>">
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($telefono); ?>">
                </div>
                <div class="form-group">
                    <label for="correo_electronico">Correo Electrónico:</label>
                    <input type="email" id="correo_electronico" name="correo_electronico" value="<?php echo htmlspecialchars($correo_electronico); ?>" required>
                </div>
                <div class="form-group">
                    <button type="submit">Guardar Usuario</button>
                </div>
            </form>
        </div>
        <div class="regresar">
            <a href="index.php" class="button boton-centrado">Regresar</a>
        </div>
    </section>
    <footer>
        <p>Todos los derechos reservados</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($mensaje == 'success'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Usuario Creado',
                    text: 'El usuario se creó correctamente.',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true,
                    didClose: () => {
                        window.location.href = 'index.php'; // Redirige al index después de la notificación
                    }
                });
            <?php elseif ($mensaje == 'error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al crear el usuario. Intenta de nuevo.',
                    showConfirmButton: true
                });
            <?php elseif ($mensaje == 'exists'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'La identificación, el teléfono o el correo electrónico ya están registrados.',
                    showConfirmButton: true
                });
            <?php endif; ?>
        });
    </script>
</body>
</html>
