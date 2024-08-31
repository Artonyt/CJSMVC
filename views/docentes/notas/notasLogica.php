<?php
include '../db.php';

// Verificar si se pasó el id_curso como parámetro
if (!isset($_GET['id_curso']) || !is_numeric($_GET['id_curso'])) {
    die("ID de curso no especificado o inválido.");
}

$id_curso = intval($_GET['id_curso']); // Sanitizar el id_curso

// Consulta SQL para obtener los datos del curso seleccionado
$sql_curso = "SELECT ID_curso, Nombre_curso, ID_grado FROM cursos WHERE ID_curso = ?";
$stmt_curso = $conn->prepare($sql_curso);
$stmt_curso->bind_param("i", $id_curso);
$stmt_curso->execute();
$result_curso = $stmt_curso->get_result();

if ($result_curso->num_rows === 0) {
    die("Curso no encontrado.");
}

$curso = $result_curso->fetch_assoc();

// Consulta SQL para obtener los datos de estudiantes del curso seleccionado
$sql_estudiantes = "SELECT ID_estudiante, Nombres, Apellidos, Identificacion FROM estudiantes WHERE ID_curso = ?";
$stmt_estudiantes = $conn->prepare($sql_estudiantes);
$stmt_estudiantes->bind_param("i", $id_curso);
$stmt_estudiantes->execute();
$result_estudiantes = $stmt_estudiantes->get_result();

// Consulta SQL para obtener las notas de los estudiantes
$sql_notas = "SELECT ID_nota, ID_estudiante, ID_tarea, Calificacion, Fecha FROM notas WHERE ID_estudiante = ? ORDER BY Fecha ASC";
$stmt_notas = $conn->prepare($sql_notas);

// Procesar el formulario para guardar o actualizar las notas
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] == 'guardar_nota') {
    if (isset($_POST['estudiante_id'], $_POST['tarea_id'], $_POST['calificacion'], $_POST['fecha'])) {
        $estudiante_id = intval($_POST['estudiante_id']);
        $tarea_id = intval($_POST['tarea_id']);
        $calificacion = floatval($_POST['calificacion']);
        $fecha = $_POST['fecha'];

        // Validar la fecha
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
            $_SESSION['mensaje'] = "Fecha no válida.";
            header('Location: ' . $_SERVER['PHP_SELF'] . '?id_curso=' . $id_curso);
            exit;
        }

        // Verificar si ya existe una nota para este estudiante y tarea
        $stmt_verificar_nota = $conn->prepare("SELECT ID_nota FROM notas WHERE ID_estudiante = ? AND ID_tarea = ?");
        $stmt_verificar_nota->bind_param("ii", $estudiante_id, $tarea_id);
        $stmt_verificar_nota->execute();
        $resultado_verificar_nota = $stmt_verificar_nota->get_result();

        if ($resultado_verificar_nota->num_rows === 0) {
            // No hay nota registrada, proceder con la inserción
            $stmt = $conn->prepare("INSERT INTO notas (ID_estudiante, ID_tarea, Calificacion, Fecha) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iids", $estudiante_id, $tarea_id, $calificacion, $fecha);
            $stmt->execute();
            $stmt->close();
            $_SESSION['mensaje'] = "¡Nota guardada correctamente!";
        } else {
            // Ya existe una nota para este estudiante y tarea, proceder con la actualización
            $stmt = $conn->prepare("UPDATE notas SET Calificacion = ?, Fecha = ? WHERE ID_estudiante = ? AND ID_tarea = ?");
            $stmt->bind_param("dssi", $calificacion, $fecha, $estudiante_id, $tarea_id);
            $stmt->execute();
            $stmt->close();
            $_SESSION['mensaje'] = "¡Nota actualizada correctamente!";
        }
    } else {
        $_SESSION['mensaje'] = "Error: No se enviaron todos los datos necesarios desde el formulario.";
    }
    // Redirigir para evitar reenvío de formulario
    header('Location: ' . $_SERVER['PHP_SELF'] . '?id_curso=' . $id_curso);
    exit;
}

// Eliminar el mensaje de sesión después de mostrarlo para que no se muestre nuevamente después de refrescar la página
if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);
}
?>
