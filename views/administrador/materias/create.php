<?php
require_once '../../../config/db.php';
require_once '../../../router.php';

// Inicializar variables y manejar errores
$nombre_materia = '';
$id_asignatura = '';
$error = '';
$success = false;
$asignaturas = [];

// Obtener asignaturas para el menú desplegable
$query_asignaturas = "SELECT ID_asignatura, Nombre_asignatura FROM asignaturas";
$stmt_asignaturas = $db->prepare($query_asignaturas);
$stmt_asignaturas->execute();
$asignaturas = $stmt_asignaturas->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_materia = trim($_POST['Nombre_materia']);
    $id_asignatura = trim($_POST['ID_asignatura']);

    // Validar los campos del formulario
    if (empty($nombre_materia) || empty($id_asignatura)) {
        $error = "Todos los campos son obligatorios.";
    } else {
        // Insertar datos en la base de datos
        $query = "INSERT INTO materias (Nombre_materia, ID_asignatura) VALUES (:nombre_materia, :id_asignatura)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':nombre_materia', $nombre_materia);
        $stmt->bindParam(':id_asignatura', $id_asignatura);

        if ($stmt->execute()) {
            $success = true;
        } else {
            $error = "Error al crear la materia.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nueva Materia</title>
    <link rel="stylesheet" type="text/css" href="../../../assets/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
   
        .formulario {
            max-width: 500px;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        .form-group button {
            background-color: #6f42c1;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            box-sizing: border-box;
        }
        .form-group button:hover {
            background-color: #5a2d91;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="../../../assets/Logo.png" alt="Logo de la empresa" class="logo">
        </div>
        <div class="title">
            <h1>Crear Nueva Materia</h1>
        </div>
    </header>
    <section class="admin">
        <div class="subtitulo-admin">
            <h2>Nueva Materia</h2>
        </div>
        <?php if (!empty($error)): ?>
            <div class="error">
                <p><?php echo htmlspecialchars($error); ?></p>
            </div>
        <?php endif; ?>
        <form method="POST" action="create.php" class="formulario" id="formulario">
            <div class="form-group">
                <label for="Nombre_materia">Nombre de la Materia:</label>
                <input type="text" id="Nombre_materia" name="Nombre_materia" value="<?php echo htmlspecialchars($nombre_materia); ?>" required>
            </div>
            <div class="form-group">
                <label for="ID_asignatura">Asignatura:</label>
                <select id="ID_asignatura" name="ID_asignatura" required>
                    <option value="">Seleccione una asignatura</option>
                    <?php foreach ($asignaturas as $asignatura): ?>
                        <option value="<?php echo htmlspecialchars($asignatura['ID_asignatura']); ?>">
                            <?php echo htmlspecialchars($asignatura['Nombre_asignatura']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <button type="submit">Crear Materia</button>
            </div>
        </form>
        <div class="regresar">
            <a href="index.php" class="button boton-centrado" id="btn-regresar">Regresar</a>
        </div>
    </section>
    <footer>
        <p>Todos los derechos reservados</p>
    </footer>

    <script>
        // Mostrar alerta de éxito o error según el resultado de la inserción
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($success): ?>
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'Materia creada con éxito.',
                    timer: 2000, 
                    timerProgressBar: true,
                    showConfirmButton: false
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.timer) {
                        window.location.href = 'index.php';
                    }
                });
            <?php elseif (!empty($error)): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '<?php echo addslashes($error); ?>',
                    timer: 5000, // 5 segundos
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            <?php endif; ?>
        });
    </script>
</body>
</html>
