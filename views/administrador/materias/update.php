<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['Identificacion'])) {
    // Si no ha iniciado sesión, redirigir al inicio de sesión
    header("Location: /dashboard/cjs/login/login.php");
    exit;
}
require_once '../../../config/db.php';
require_once '../../../router.php';

// Inicializar variables
$id_materia = '';
$nombre_materia = '';
$id_asignatura = '';
$asignaturas = [];
$error = '';
$success = false;

// Obtener la materia actual si se proporciona un ID válido
if (isset($_GET['id'])) {
    $id_materia = $_GET['id'];

    if (!empty($id_materia)) {
        // Obtener los datos de la materia
        $query = "SELECT Nombre_materia, ID_asignatura FROM materias WHERE ID_materia = :id_materia";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id_materia', $id_materia);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $nombre_materia = $row['Nombre_materia'];
            $id_asignatura = $row['ID_asignatura'];
        } else {
            $error = "Materia no encontrada.";
        }
    } else {
        $error = "ID de materia no proporcionado.";
    }
}

// Obtener la lista de asignaturas para el menú desplegable
$query_asignaturas = "SELECT ID_asignatura, Nombre_asignatura FROM asignaturas";
$stmt_asignaturas = $db->query($query_asignaturas);
$asignaturas = $stmt_asignaturas->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_materia = trim($_POST['Nombre_materia']);
    $id_asignatura = trim($_POST['ID_asignatura']);

    // Validar los campos del formulario
    if (empty($nombre_materia) || empty($id_asignatura)) {
        $error = "Todos los campos son obligatorios.";
    } else {
        // Actualizar datos en la base de datos
        $query_update = "UPDATE materias SET Nombre_materia = :nombre_materia, ID_asignatura = :id_asignatura WHERE ID_materia = :id_materia";
        $stmt_update = $db->prepare($query_update);
        $stmt_update->bindParam(':nombre_materia', $nombre_materia);
        $stmt_update->bindParam(':id_asignatura', $id_asignatura);
        $stmt_update->bindParam(':id_materia', $id_materia);

        if ($stmt_update->execute()) {
            $success = true;
        } else {
            $error = "Error al actualizar la materia.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Materia</title>
    <link rel="stylesheet" type="text/css" href="../../../assets/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($success): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Materia actualizada',
                    text: 'La materia se ha actualizado exitosamente.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = 'index.php';
                });
            <?php elseif (!empty($error)): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: <?php echo json_encode($error); ?>,
                    timer: 3000,
                    showConfirmButton: false
                });
            <?php endif; ?>
        });
    </script>
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
            <h1>Actualizar Materia</h1>
        </div>
    </header>
    <section class="admin">
        <div class="subtitulo-admin">
            <h2>Actualizar Materia</h2>
        </div>
        <?php if (!empty($error)): ?>
            <div class="error">
                <p><?php echo htmlspecialchars($error); ?></p>
            </div>
        <?php endif; ?>
        <form method="POST" action="update.php?id=<?php echo htmlspecialchars($id_materia); ?>" class="formulario">
            <div class="form-group">
                <label for="Nombre_materia">Nombre de la Materia:</label>
                <input type="text" id="Nombre_materia" name="Nombre_materia" value="<?php echo htmlspecialchars($nombre_materia); ?>" required>
            </div>
            <div class="form-group">
                <label for="ID_asignatura">Nombre de la Asignatura:</label>
                <select id="ID_asignatura" name="ID_asignatura" required>
                    <?php foreach ($asignaturas as $asignatura): ?>
                        <option value="<?php echo htmlspecialchars($asignatura['ID_asignatura']); ?>" <?php if ($asignatura['ID_asignatura'] == $id_asignatura) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($asignatura['Nombre_asignatura']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="button boton-centrado">Actualizar Materia</button>
            </div>
        </form>
        <div class="regresar">
            <a href="index.php" class="button boton-centrado" id="btn-regresar">Regresar</a>
        </div>
    </section>
    <footer>
        <p>Todos los derechos reservados</p>
    </footer>
</body>
</html>
