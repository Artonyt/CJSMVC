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

// Inicializar variables para mensajes
$success = false;
$errorMessage = '';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Verificar si se envió el formulario para actualizar
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recibir y validar los datos del formulario
        $nombres = $_POST['nombres'];
        $apellidos = $_POST['apellidos'];
        $identificacion = $_POST['identificacion'];
        $contraseña = $_POST['contraseña'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $correo = $_POST['correo_electronico']; // Asegúrate de que coincida con el nombre en el formulario

        // Encriptar la contraseña
        $contraseña_encriptada = password_hash($contraseña, PASSWORD_BCRYPT);

        // Actualizar los datos en la base de datos
        $query = "UPDATE usuarios SET Nombres = :nombres, Apellidos = :apellidos, Identificacion = :identificacion, contraseña = :contrasena, Direccion = :direccion, Telefono = :telefono, Correo_electronico = :correo WHERE ID_usuario = :id AND ID_rol = 'Docente'";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':nombres', $nombres);
        $stmt->bindParam(':apellidos', $apellidos);
        $stmt->bindParam(':identificacion', $identificacion);
        $stmt->bindParam(':contrasena', $contraseña_encriptada); // Cambiado a contrasena según la columna en tu base de datos
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':id', $id);

        // Ejecutar la actualización
        try {
            $stmt->execute();
            $success = true; // Actualización exitosa
            header("Location: update.php?id=$id&success=true");
            exit();
        } catch (PDOException $e) {
            $errorMessage = "Error al intentar actualizar el docente: " . $e->getMessage();
            header("Location: update.php?id=$id&error=true");
            exit();
        }
    } else {
        // Obtener los datos actuales del docente para mostrar en el formulario
        $query = "SELECT * FROM usuarios WHERE ID_usuario = :id AND ID_rol = 'Docente'";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $docente = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$docente) {
            echo "Docente no encontrado.";
            exit();
        }

        // Mostrar el formulario de actualización con los datos actuales del docente
    }
} else {
    echo "ID de docente no especificado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - Actualizar Docente</title>
    <link rel="stylesheet" type="text/css" href="../../../assets/styles.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Estilos específicos para la sección admin */
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
        .form-group input[type="text"], .form-group input[type="password"], .form-group input[type="email"] {
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
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
        .error-message {
            color: red;
            font-weight: bold;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="../../../assets/Logo.png" alt="Logo de la empresa" class="logo">
        </div>
        <div class="title">
            <h1>Actualizar Docente</h1>
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
        <section class="actualizar-docente-form" id="section-actualizar-docente">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $id; ?>" method="POST" class="form-container">
                <?php
                if (!empty($errorMessage)) {
                    echo '<p class="error-message">' . htmlspecialchars($errorMessage) . '</p>';
                }
                ?>
                <div class="form-group">
                    <label for="nombres">Nombres:</label>
                    <input type="text" name="nombres" id="nombres" value="<?php echo htmlspecialchars($docente['Nombres']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="apellidos">Apellidos:</label>
                    <input type="text" name="apellidos" id="apellidos" value="<?php echo htmlspecialchars($docente['Apellidos']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="identificacion">Identificación:</label>
                    <input type="text" name="identificacion" id="identificacion" value="<?php echo htmlspecialchars($docente['Identificacion']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="contraseña">Contraseña:</label>
                    <input type="password" name="contraseña" id="contraseña" value="" required>
                </div>
                <div class="form-group">
                    <label for="direccion">Dirección:</label>
                    <input type="text" name="direccion" id="direccion" value="<?php echo htmlspecialchars($docente['Direccion']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" name="telefono" id="telefono" value="<?php echo htmlspecialchars($docente['Telefono']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="correo_electronico">Correo electrónico:</label>
                    <input type="email" name="correo_electronico" id="correo_electronico" value="<?php echo htmlspecialchars($docente['Correo_electronico']); ?>" required>
                </div>
                <div class="form-group">
                    <button type="submit">Actualizar</button>
                </div>
            </form>
        </section>
    </section>
    <script>
        // Mostrar notificaciones SweetAlert2 según parámetros en URL
        <?php if (isset($_GET['success']) && $_GET['success'] == 'true'): ?>
            Swal.fire({
                title: 'Éxito',
                text: 'Docente actualizado correctamente.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'index.php'; // Redirigir a la página de índice
                }
            });
        <?php endif; ?>

        <?php if (isset($_GET['error']) && $_GET['error'] == 'true'): ?>
            Swal.fire({
                title: 'Error',
                text: 'No se pudo actualizar el docente. Intente nuevamente.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>
    </script>
</body>
</html>
