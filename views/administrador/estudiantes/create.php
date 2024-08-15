<?php
require_once '../../../config/db.php';

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $identificacion = $_POST['identificacion'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $genero = $_POST['genero'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $correo_electronico = $_POST['correo_electronico'];
    $id_curso = $_POST['id_curso'];

    // Validar y sanitizar los datos según sea necesario
    $query = "INSERT INTO estudiantes (Nombres, Apellidos, Identificacion, Fecha_nacimiento, Genero, Direccion, Telefono, Correo_electronico, ID_curso) 
              VALUES (:nombres, :apellidos, :identificacion, :fecha_nacimiento, :genero, :direccion, :telefono, :correo_electronico, :id_curso)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':nombres', $nombres);
    $stmt->bindParam(':apellidos', $apellidos);
    $stmt->bindParam(':identificacion', $identificacion);
    $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento);
    $stmt->bindParam(':genero', $genero);
    $stmt->bindParam(':direccion', $direccion);
    $stmt->bindParam(':telefono', $telefono);
    $stmt->bindParam(':correo_electronico', $correo_electronico);
    $stmt->bindParam(':id_curso', $id_curso);

    if ($stmt->execute()) {
        // Redirigir de vuelta a la página de gestión de estudiantes después de crear el estudiante
        header("Location: index.php");
        exit();
    } else {
        echo "Error al intentar crear el estudiante.";
    }
}

// Obtener los cursos para el menú desplegable
$query = "SELECT ID_curso, Nombre_curso FROM cursos";
$stmt = $db->prepare($query);
$stmt->execute();
$cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Estudiante</title>
    <link rel="stylesheet" type="text/css" href="../../../assets/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .formulario {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f2f2f2;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .formulario form {
            display: flex;
            flex-direction: column;
        }
        .formulario label {
            margin-top: 15px;
            font-weight: bold;
        }
        .formulario input, .formulario select {
            margin-top: 5px;
            padding: 10px;
            font-size: 16px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        .formulario button {
            margin-top: 20px;
            padding: 10px;
            font-size: 18px;
            border: none;
            border-radius: 4px;
            background-color: #6f42c1;
            color: white;
            cursor: pointer;
        }
        .formulario button:hover {
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
            <h1>Crear Nuevo Estudiante</h1>
        </div>
    </header>
    <section class="admin">
        <div class="formulario">
            <form action="create.php" method="post">
                <label for="nombres">Nombres:</label>
                <input type="text" id="nombres" name="nombres" required>

                <label for="apellidos">Apellidos:</label>
                <input type="text" id="apellidos" name="apellidos" required>

                <label for="identificacion">Identificación:</label>
                <input type="text" id="identificacion" name="identificacion" required>

                <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>

                <label for="genero">Género:</label>
                <select id="genero" name="genero" required>
                    <option value="Masculino">Masculino</option>
                    <option value="Femenino">Femenino</option>
                    <option value="Otro">Otro</option>
                </select>

                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion" required>

                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" required>

                <label for="correo_electronico">Correo Electrónico:</label>
                <input type="email" id="correo_electronico" name="correo_electronico" required>

                <label for="id_curso">Curso:</label>
                <select id="id_curso" name="id_curso" required>
                    <?php foreach ($cursos as $curso): ?>
                        <option value="<?php echo htmlspecialchars($curso['ID_curso']); ?>">
                            <?php echo htmlspecialchars($curso['Nombre_curso']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="button">Crear Estudiante</button>
            </form>
            <div class="regresar">
                <a href="index.php" class="button boton-centrado" id="btn-regresar">Regresar</a>
            </div>
        </div>
    </section>
    <footer>
        <p>Todos los derechos reservados</p>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Estudiante creado',
                    text: 'El estudiante se ha creado exitosamente.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = 'index.php';
                });
            <?php endif; ?>
        });
    </script>
</body>
</html>
