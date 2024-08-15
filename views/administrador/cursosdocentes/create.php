<?php
require_once '../../../config/db.php';

// Inicializar variables
$success = false;
$error = '';

// Obtener listas de docentes y materias para mostrar en los formularios
$queryDocentes = "SELECT ID_usuario, CONCAT(Nombres, ' ', Apellidos) AS Nombre_docente FROM usuarios WHERE ID_rol = 'Docente'";
$queryMaterias = "SELECT * FROM materias";
$resultDocentes = $db->query($queryDocentes);
$resultMaterias = $db->query($queryMaterias);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_docente = $_POST['id_docente'];
    $id_materia = $_POST['id_materia'];

    // Verificar si la relación ya existe
    $queryCheck = "SELECT COUNT(*) FROM docentes_materias WHERE ID_docente = :id_docente AND ID_materia = :id_materia";
    $stmtCheck = $db->prepare($queryCheck);
    $stmtCheck->bindParam(':id_docente', $id_docente);
    $stmtCheck->bindParam(':id_materia', $id_materia);
    $stmtCheck->execute();
    $exists = $stmtCheck->fetchColumn();

    if ($exists > 0) {
        $error = "El docente ya está asignado a la materia seleccionada.";
    } else {
        // Insertar la nueva relación en la tabla docentes_materias
        $queryInsert = "INSERT INTO docentes_materias (ID_docente, ID_materia) VALUES (:id_docente, :id_materia)";
        $stmtInsert = $db->prepare($queryInsert);
        $stmtInsert->bindParam(':id_docente', $id_docente);
        $stmtInsert->bindParam(':id_materia', $id_materia);

        if ($stmtInsert->execute()) {
            $success = true;
        } else {
            $error = "Error al intentar asignar el docente a la materia.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Docente a Materia</title>
    <link rel="stylesheet" type="text/css" href="../../../assets/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
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
            <h1>Asignar Docente a Materia</h1>
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
                <label for="id_docente">Docente:</label>
                <select name="id_docente" id="id_docente" required>
                    <option value="">Seleccione un docente</option>
                    <?php
                    while ($row = $resultDocentes->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . htmlspecialchars($row['ID_usuario']) . "'>" . htmlspecialchars($row['Nombre_docente']) . "</option>";
                    }
                    ?>
                </select>
            </div>
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
                <button type="submit">Asignar Docente</button>
            </div>
        </form>
        <div class="regresar">
            <a href="index.php" class="button">Regresar</a>
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
                    text: 'Docente asignado a la materia con éxito.',
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
