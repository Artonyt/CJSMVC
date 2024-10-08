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

// Inicializar la variable de éxito
$success = false;

// Verificar si se ha proporcionado un ID de estudiante
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID de estudiante no proporcionado.";
    exit();
}

$id_estudiante = $_GET['id'];

// Recuperar la información del estudiante
$query = "SELECT * FROM estudiantes WHERE ID_estudiante = :id_estudiante";
$stmt = $db->prepare($query);
$stmt->bindParam(':id_estudiante', $id_estudiante);
$stmt->execute();
$estudiante = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$estudiante) {
    echo "Estudiante no encontrado.";
    exit();
}

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
    $query = "UPDATE estudiantes SET 
                Nombres = :nombres,
                Apellidos = :apellidos,
                Identificacion = :identificacion,
                Fecha_nacimiento = :fecha_nacimiento,
                Genero = :genero,
                Direccion = :direccion,
                Telefono = :telefono,
                Correo_electronico = :correo_electronico,
                ID_curso = :id_curso
              WHERE ID_estudiante = :id_estudiante";
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
    $stmt->bindParam(':id_estudiante', $id_estudiante);

    if ($stmt->execute()) {
        $success = true;
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
    <title>Actualizar Estudiante</title>
    <link rel="stylesheet" type="text/css" href="../../../assets/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($success): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Estudiante actualizado',
                    text: 'El estudiante se ha actualizado exitosamente.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = 'index.php';
                });
            <?php endif; ?>
        });
    </script>
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
            <h1>Actualizar Estudiante</h1>
        </div>
    </header>
    <section class="admin">
        <div class="formulario">
            <form action="update.php?id=<?php echo htmlspecialchars($id_estudiante); ?>" method="post">
                <label for="nombres">Nombres:</label>
                <input type="text" id="nombres" name="nombres" value="<?php echo htmlspecialchars($estudiante['Nombres']); ?>" required>

                <label for="apellidos">Apellidos:</label>
                <input type="text" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($estudiante['Apellidos']); ?>" required>

                <label for="identificacion">Identificación:</label>
                <input type="text" id="identificacion" name="identificacion" value="<?php echo htmlspecialchars($estudiante['Identificacion']); ?>" required>

                <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo htmlspecialchars($estudiante['Fecha_nacimiento']); ?>" required>

                <label for="genero">Género:</label>
                <select id="genero" name="genero" required>
                    <option value="Masculino" <?php if ($estudiante['Genero'] == 'Masculino') echo 'selected'; ?>>Masculino</option>
                    <option value="Femenino" <?php if ($estudiante['Genero'] == 'Femenino') echo 'selected'; ?>>Femenino</option>
                    <option value="Otro" <?php if ($estudiante['Genero'] == 'Otro') echo 'selected'; ?>>Otro</option>
                </select>

                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($estudiante['Direccion']); ?>" required>

                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($estudiante['Telefono']); ?>" required>

                <label for="correo_electronico">Correo Electrónico:</label>
                <input type="email" id="correo_electronico" name="correo_electronico" value="<?php echo htmlspecialchars($estudiante['Correo_electronico']); ?>" required>

                <label for="id_curso">Curso:</label>
                <select id="id_curso" name="id_curso" required>
                    <?php foreach ($cursos as $curso): ?>
                        <option value="<?php echo htmlspecialchars($curso['ID_curso']); ?>" <?php if ($curso['ID_curso'] == $estudiante['ID_curso']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($curso['Nombre_curso']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="button boton-centrado">Actualizar Estudiante</button>
            </form>
            <div class="regresar">
                <a href="index.php" class="button boton-centrado" id="btn-regresar">Regresar</a>
            </div>
        </div>
    </section>
    <footer>
        <p>Todos los derechos reservados</p>
    </footer>
</body>
</html>
