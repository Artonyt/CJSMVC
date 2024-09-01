<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['Identificacion'])) {
    // Si no ha iniciado sesión, redirigir al inicio de sesión
    header("Location: /dashboard/cjs/login/login.php");
    exit;
}
require_once '../../../config/db.php';

if (isset($_GET['id'])) {
    // `id` es el identificador único de la fila en `materias_cursos`
    $id = $_GET['id'];

    // Obtener la asignación actual usando el ID único
    $query = "SELECT * FROM materias_cursos WHERE ID = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $asignacion = $stmt->fetch(PDO::FETCH_ASSOC);

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
    $id_materia_post = $_POST['id_materia'];
    $id_curso_post = $_POST['id_curso'];

    try {
        // Iniciar una transacción
        $db->beginTransaction();

        // Actualizar la asignación usando el ID único
        $queryUpdate = "UPDATE materias_cursos SET ID_materia = :id_materia, ID_curso = :id_curso WHERE ID = :id";
        $stmtUpdate = $db->prepare($queryUpdate);
        $stmtUpdate->bindParam(':id_materia', $id_materia_post, PDO::PARAM_INT);
        $stmtUpdate->bindParam(':id_curso', $id_curso_post, PDO::PARAM_INT);
        $stmtUpdate->bindParam(':id', $id, PDO::PARAM_INT);
        $stmtUpdate->execute();

        // Confirmar la transacción
        $db->commit();

        // Redirigir al índice después de la actualización
        header("Location: index.php");
        exit();
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $db->rollBack();
        echo "Error al intentar actualizar la asignación: " . $e->getMessage();
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

        .formulario button {
            margin-top: 20px;
            padding: 10px;
            font-size: 18px;
            border: none;
            border-radius: 4px;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
        .formulario button:hover {
            background-color: #45a049;
        }

        .formulario select {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .formulario label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .button.boton-centrado1 {
            display: block;
            width: 100%;
            text-align: center;
            background-color: #6f42c1;
            color: white;
            padding: 10px;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .button.boton-centrado:hover {
            background-color: #5a2d91;
        }
        .form-group button {
            background-color: #6f42c1;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .form-group button:hover {
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
            <h1>Actualizar Asignación de Materia a Curso</h1>
        </div>
    </header>
    <section class="update">
        <div class="subtitulo-update">
            <h2>Actualizar Asignación</h2>
        </div>
        <div class="form-container">
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
                <button type="submit" class="button boton-centrado1">Actualizar</button>
            </form>
        </div>
        <div class="regresar">
                <a href="http://localhost/dashboard/cjs/views/administrador/index.php" class="button boton-centrado" id="btn-regresar">Regresar</a>
            </div>
    </section>
    <footer>
        <p>Todos los derechos reservados</p>
    </footer>
</body>
</html>
