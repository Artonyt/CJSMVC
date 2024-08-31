<?php
include '../../../config/db.php';

// Verificar si se pasó el id_curso como parámetro
if (!isset($_GET['id_curso']) || !isset($_GET['fecha'])) {
    die("ID de curso o fecha no especificados.");
}

$id_curso = intval($_GET['id_curso']);
$fecha = $_GET['fecha'];

// Obtener información de las asistencias
$stmt = $db->prepare("SELECT e.Nombres, e.Apellidos, e.Identificacion, a.Estado 
                       FROM asistencia a 
                       JOIN estudiantes e ON a.ID_estudiante = e.ID_estudiante 
                       WHERE a.ID_materia = ? AND a.Fecha = ?");
$stmt->execute([$id_curso, $fecha]);
$asistencias = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Definir el nombre del archivo y tipo de contenido
$nombre_archivo = "asistencias_" . $id_curso . "_" . date('Ymd') . ".csv";
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $nombre_archivo . '"');

// Abrir el archivo en modo de escritura
$output = fopen('php://output', 'w');

// Escribir el encabezado del CSV
fputcsv($output, ['Nombre', 'Identificación', 'Estado']);

// Escribir las filas de asistencias
foreach ($asistencias as $asistencia) {
    fputcsv($output, [
        $asistencia['Nombres'] . ' ' . $asistencia['Apellidos'],
        $asistencia['Identificacion'],
        $asistencia['Estado']
    ]);
}

// Cerrar el archivo
fclose($output);
exit;
