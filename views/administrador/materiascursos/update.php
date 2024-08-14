<?php
require_once '../../../config/db.php';

// Obtener el ID de la asignación que se va a editar
if (isset($_GET['id'])) {
    $id_materia = $_GET['id'];

    // Obtener la asignación actual
    $query = "SELECT * FROM materias_cursos WHERE ID_materia = :id_materia";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id_materia', $id_materia);
    $stmt->execute();

    $asignacion = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si la asignación no existe, redirigir
    if (!$asignacion) {
        header("Location: index.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}

// Obtener listas de materias y cursos para los selectores
$queryMaterias = "SELECT * FROM materias";
$queryCursos = "SELECT * FROM cursos";
$resultMaterias = $db->query($queryMaterias);
$resultCursos = $db->query($queryCursos);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_materia = $_POST['id_materia'];
    $id_curso = $_POST['id_curso'];

    // Actualizar la relación en la tabla materias_cursos
    $queryUpdate = "UPDATE materias_cursos SET ID_curso = :id_curso WHERE ID_materia = :id_materia";
    $stmt = $db->prepare($queryUpdate);
    $stmt->bindParam(':id_materia', $id_materia);
    $stmt->bindParam(':id_curso', $id_curso);

    if ($stmt->execute()) {
        // Redirigir al índice después de la actualización
        header("Location: index.php");
        exit();
    } else {
        echo "Error al intentar actualizar la asignación.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Asignación</title>
    <link rel="stylesheet" type="text/css" href="../../../assets/styles.css">
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="../../../assets/Logo.png" alt="Logo de la empresa" class="logo">
        </div>
        <div class="title">
            <h1>Actualizar Asignación de Materia a Curso</h1>
        </div>
    </header>
    <section class="update">
        <div class="subtitulo-update">
            <h2>Actualizar Asignación</h2>
        </div>
        <form method="POST" action="">
            <div class="form-group">
                <label for="id_materia">Materia:</label>
                <select name="id_materia" id="id_materia" required>
                    <?php
                    while ($row = $resultMaterias->fetch(PDO::FETCH_ASSOC)) {
                        $selected = $row['ID_materia'] == $asignacion['ID_materia'] ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($row['ID_materia']) . "' $selected>" . htmlspecialchars($row['Nombre_materia']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="id_curso">Curso:</label>
                <select name="id_curso" id="id_curso" required>
                    <?php
                    while ($row = $resultCursos->fetch(PDO::FETCH_ASSOC)) {
                        $selected = $row['ID_curso'] == $asignacion['ID_curso'] ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($row['ID_curso']) . "' $selected>" . htmlspecialchars($row['Nombre_curso']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="button boton-centrado">Actualizar</button>
        </form>
        <div class="regresar">
            <a href="index.php" class="button boton-centrado">Regresar</a>
        </div>
    </section>
    <footer>
        <p>Todos los derechos reservados</p>
    </footer>
</body>
</html>
