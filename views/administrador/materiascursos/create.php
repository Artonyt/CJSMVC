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

// Obtener listas de materias y cursos para mostrar en los formularios
$queryMaterias = "SELECT * FROM materias";
$queryCursos = "SELECT * FROM cursos";
$resultMaterias = $db->query($queryMaterias);
$resultCursos = $db->query($queryCursos);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_materia = $_POST['id_materia'];
    $id_curso = $_POST['id_curso'];

    // Verificar si la relación ya existe
    $queryCheck = "SELECT COUNT(*) FROM materias_cursos WHERE ID_materia = :id_materia AND ID_curso = :id_curso";
    $stmtCheck = $db->prepare($queryCheck);
    $stmtCheck->bindParam(':id_materia', $id_materia);
    $stmtCheck->bindParam(':id_curso', $id_curso);
    $stmtCheck->execute();
    $exists = $stmtCheck->fetchColumn();

    if ($exists > 0) {
        $error = "La materia ya está asignada al curso seleccionado.";
    } else {
        // Insertar la nueva relación en la tabla materias_cursos
        $queryInsert = "INSERT INTO materias_cursos (ID_materia, ID_curso) VALUES (:id_materia, :id_curso)";
        $stmtInsert = $db->prepare($queryInsert);
        $stmtInsert->bindParam(':id_materia', $id_materia);
        $stmtInsert->bindParam(':id_curso', $id_curso);

        if ($stmtInsert->execute()) {
            $success = true;
            // Mostrar alerta de éxito y redirigir después de un temporizador
        } else {
            $error = "Error al intentar asignar la materia al curso.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Materia a Curso</title>
    <link rel="stylesheet" type="text/css" href="../../../assets/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            width: auto; /* Cambiado de 100% a auto para que el botón no ocupe todo el ancho */
            margin: 0 auto; /* Centrará el botón horizontalmente */
            display: block; /* Asegura que el margen auto funcione para centrar el botón */
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
            <h1>Asignar Materia a Curso</h1>
        </div>
    </header>
    <section class="create">
        <?php if (!empty($error)): ?>
            <div class="error">
                <p><?php echo htmlspecialchars($error); ?></p>
            </div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="id_materia">Materia:</label>
                <select name="id_materia" id="id_materia" required>
                    <option value="">Seleccione una materia</option>
                    <?php
                    while ($row = $resultMaterias->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . htmlspecialchars($row['ID_materia']) . "'>" . htmlspecialchars($row['Nombre_materia']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="id_curso">Curso:</label>
                <select name="id_curso" id="id_curso" required>
                    <option value="">Seleccione un curso</option>
                    <?php
                    while ($row = $resultCursos->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . htmlspecialchars($row['ID_curso']) . "'>" . htmlspecialchars($row['Nombre_curso']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <button type="submit">Crear Materia</button>
            </div>
        </form>
        <div class="regresar">
            <a href="index.php" class="button boton-centrado">Regresar</a>
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
                    text: 'Materia asignada al curso con éxito.',
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
