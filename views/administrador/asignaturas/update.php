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

// Inicializar variables
$id_asignatura = null;
$nombre_asignatura = '';
$mensaje = '';

// Procesar la actualización si se recibe un ID válido por GET
if (isset($_GET['id'])) {
    $id_asignatura = $_GET['id'];

    // Obtener los datos actuales de la asignatura
    $query = "SELECT * FROM asignaturas WHERE ID_asignatura = :id";
    $statement = $db->prepare($query);
    $statement->bindParam(':id', $id_asignatura);
    $statement->execute();
    
    // Verificar si se encontró la asignatura
    if ($statement->rowCount() > 0) {
        $asignatura = $statement->fetch(PDO::FETCH_ASSOC);
        $nombre_asignatura = $asignatura['Nombre_asignatura'];
    } else {
        // Manejar caso donde no se encuentre la asignatura
        $mensaje = "No se encontró la asignatura.";
    }
}

// Procesar el envío del formulario de actualización por POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_asignatura'])) {
    $id_asignatura = $_POST['id_asignatura'];
    $nombre_asignatura = $_POST['nombre_asignatura'];

    // Validar los datos recibidos (puedes agregar más validaciones según tus necesidades)

    // Actualizar la asignatura en la base de datos
    $query = "UPDATE asignaturas SET Nombre_asignatura = :nombre WHERE ID_asignatura = :id";
    $statement = $db->prepare($query);
    $statement->bindParam(':nombre', $nombre_asignatura);
    $statement->bindParam(':id', $id_asignatura);

    $resultado = $statement->execute();

    if ($resultado) {
        // Redirigir de vuelta a la página principal después de la actualización
        $mensaje = "Asignatura actualizada correctamente.";
    } else {
        $mensaje = "Error al intentar actualizar la asignatura.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - Actualizar Asignatura</title>
    <link rel="stylesheet" type="text/css" href="../../../assets/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        .mensaje {
            text-align: center;
            margin-top: 20px;
            color: red;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="../../../assets/Logo.png" alt="Logo de la empresa" class="logo">
        </div>
        <div class="title">
            <h1>Actualizar Asignatura</h1>
        </div>
        <div class="datetime">
            <?php
                date_default_timezone_set('America/Bogota');
                $fechaActual = date("d/m/Y");
                $horaActual = date("h:i a");
            ?>
            <div class="fecha">
                <p>Fecha actual: <?php echo $fechaActual; ?></p>
            </div>
            <div class="hora">
                <p>Hora actual: <?php echo $horaActual; ?></p>
            </div>
        </div>
    </header>
    <section class="create-ambiente">
        <div class="form-container">
            <form id="updateForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <input type="hidden" name="id_asignatura" value="<?php echo htmlspecialchars($id_asignatura); ?>">
                <div class="form-group">
                    <label for="nombre_asignatura">Nombre de la Asignatura:</label>
                    <input type="text" id="nombre_asignatura" name="nombre_asignatura" value="<?php echo htmlspecialchars($nombre_asignatura); ?>" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="button-admin">Actualizar Asignatura</button>
                </div>
            </form>
            <?php if (!empty($mensaje)): ?>
                <div class="mensaje"><?php echo $mensaje; ?></div>
            <?php endif; ?>
        </div>
        <div class="regresar">
            <a href="index.php" class="button boton-centrado" id="btn-regresar">Regresar </a>
        </div>
        <div class="salir">
            <button id="btn_salir">Salir</button>
        </div>
    </section>
    <footer>
        <p>Todos los derechos reservados</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($resultado) && $resultado): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Asignatura actualizada',
                    text: 'La asignatura se actualizó correctamente.',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true,
                    didClose: () => {
                        window.location.href = 'index.php'; // Redirige al index después de la notificación
                    }
                });
            <?php elseif (isset($mensaje) && $mensaje != ''): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '<?php echo htmlspecialchars($mensaje); ?>',
                    showConfirmButton: true
                });
            <?php endif; ?>
        });
    </script>
</body>
</html>
