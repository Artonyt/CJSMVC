<?php
require_once '../../../config/db.php';

// Obtener listas de materias y cursos para mostrar en los formularios
$queryMaterias = "SELECT * FROM materias";
$queryCursos = "SELECT * FROM cursos";
$resultMaterias = $db->query($queryMaterias);
$resultCursos = $db->query($queryCursos);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_materia = $_POST['id_materia'];
    $id_curso = $_POST['id_curso'];

    // Insertar la nueva relación en la tabla materias_cursos
    $queryInsert = "INSERT INTO materias_cursos (ID_materia, ID_curso) VALUES (:id_materia, :id_curso)";
    $stmt = $db->prepare($queryInsert);
    $stmt->bindParam(':id_materia', $id_materia);
    $stmt->bindParam(':id_curso', $id_curso);

    if ($stmt->execute()) {
        // Redirigir al índice después de la inserción
        header("Location: index.php");
        exit();
    } else {
        echo "Error al intentar asignar la materia al curso.";
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
</head>
<style>body {
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
        <div class="subtitulo-create">
            <h2>Asignar Materia a un Curso</h2>
        </div>
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
            <button type="submit" class="button boton-centrado">Asignar</button>
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
