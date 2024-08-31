<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['Identificacion'])) {
    // Si no ha iniciado sesión, redirigir al inicio de sesión
    header("Location: /dashboard/cjs/login/login.php");
    exit;
}
require_once '../../../config/db.php';

// Inicializar variables
$success = false;
$error = '';

// Obtener listas de docentes y materias para mostrar en los formularios
$queryDocentes = "SELECT ID_usuario, CONCAT(Nombres, ' ', Apellidos) AS Nombre_docente FROM usuarios WHERE ID_rol = 'Docente'";
$queryMaterias = "SELECT * FROM materias";
$resultDocentes = $db->query($queryDocentes);
$resultMaterias = $db->query($queryMaterias);

// Verificar si se ha enviado el ID de la asignación para actualizar
if (isset($_GET['id'])) {
    $id_docente_materia = $_GET['id'];

    // Obtener la asignación actual
    $queryCurrent = "SELECT * FROM docentes_materias WHERE ID_docente_materia = :id_docente_materia";
    $stmtCurrent = $db->prepare($queryCurrent);
    $stmtCurrent->bindParam(':id_docente_materia', $id_docente_materia);
    $stmtCurrent->execute();
    $currentAssignment = $stmtCurrent->fetch(PDO::FETCH_ASSOC);

    if (!$currentAssignment) {
        $error = "Asignación no encontrada.";
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id_docente = $_POST['id_docente'];
        $id_materia = $_POST['id_materia'];

        // Verificar si la relación ya existe
        $queryCheck = "SELECT COUNT(*) FROM docentes_materias WHERE ID_docente = :id_docente AND ID_materia = :id_materia AND ID_docente_materia != :id_docente_materia";
        $stmtCheck = $db->prepare($queryCheck);
        $stmtCheck->bindParam(':id_docente', $id_docente);
        $stmtCheck->bindParam(':id_materia', $id_materia);
        $stmtCheck->bindParam(':id_docente_materia', $id_docente_materia);
        $stmtCheck->execute();
        $exists = $stmtCheck->fetchColumn();

        if ($exists > 0) {
            $error = "El docente ya está asignado a la materia seleccionada.";
        } else {
            // Actualizar la asignación
            $queryUpdate = "UPDATE docentes_materias SET ID_docente = :id_docente, ID_materia = :id_materia WHERE ID_docente_materia = :id_docente_materia";
            $stmtUpdate = $db->prepare($queryUpdate);
            $stmtUpdate->bindParam(':id_docente', $id_docente);
            $stmtUpdate->bindParam(':id_materia', $id_materia);
            $stmtUpdate->bindParam(':id_docente_materia', $id_docente_materia);

            if ($stmtUpdate->execute()) {
                $success = true;
            } else {
                $error = "Error al intentar actualizar la asignación.";
            }
        }
    }
} else {
    $error = "ID de asignación no proporcionado.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Asignación de Docente a Materia</title>
    <link rel="stylesheet" type="text/css" href="../../../assets/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: url('../../assets/fondo.jpg'); /* Reemplaza con la ruta de tu imagen */
            background-size: cover; /* Asegura que la imagen cubra todo el fondo */
            background-position: center; /* Centra la imagen */
            background-repeat: no-repeat; /* Evita que la imagen se repita */
            margin: 0;
            font-family: 'Roboto', sans-serif;
        }
       
        header .logo-container img {
            max-width: 150px;
        }
        header .title h1 {
            margin: 0;
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
            width: auto;
            margin: 0 auto;
            display: block;
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
       
        .button {
            padding: 10px 20px;
            background-color: #6f42c1;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .button:hover {
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
            <h1>Actualizar Asignación de Docente a Materia</h1>
        </div>
    </header>
    <section class="create">
        <?php if (!empty($error)): ?>
            <div class="error">
                <p><?php echo htmlspecialchars($error); ?></p>
            </div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success">
                <p>Asignación actualizada con éxito.</p>
            </div>
        <?php endif; ?>
        <?php if ($currentAssignment): ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="id_docente">Docente:</label>
                    <select name="id_docente" id="id_docente" required>
                        <?php
                        // Mostrar la opción seleccionada en el desplegable
                        while ($row = $resultDocentes->fetch(PDO::FETCH_ASSOC)) {
                            $selected = ($row['ID_usuario'] == $currentAssignment['ID_docente']) ? 'selected' : '';
                            echo "<option value='" . htmlspecialchars($row['ID_usuario']) . "' $selected>" . htmlspecialchars($row['Nombre_docente']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="id_materia">Materia:</label>
                    <select name="id_materia" id="id_materia" required>
                        <?php
                        // Mostrar la opción seleccionada en el desplegable
                        while ($row = $resultMaterias->fetch(PDO::FETCH_ASSOC)) {
                            $selected = ($row['ID_materia'] == $currentAssignment['ID_materia']) ? 'selected' : '';
                            echo "<option value='" . htmlspecialchars($row['ID_materia']) . "' $selected>" . htmlspecialchars($row['Nombre_materia']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit">Actualizar Asignación</button>
                </div>
            </form>
        <?php else: ?>
            <p>No se encontró la asignación para actualizar.</p>
        <?php endif; ?>
        <div class="regresar">
            <a href="index.php" class="button">Regresar</a>
        </div>
    </section>
    <footer>
        <p>Todos los derechos reservados</p>
    </footer>

    <script>
        // Mostrar alerta de éxito o error según el resultado de la actualización
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($success): ?>
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'Asignación actualizada con éxito.',
                    timer: 2000, // 2 segundos
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
