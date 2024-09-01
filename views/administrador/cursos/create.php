<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['Identificacion'])) {
    // Si no ha iniciado sesión, redirigir al inicio de sesión
    header("Location: /dashboard/cjs/login/login.php");
    exit;
}
// Conectar a la base de datos
require_once '../../../config/db.php';

// Inicializar variable para almacenar los grados
$grados = [];

// Consultar grados desde la base de datos
$sql = "SELECT ID_grado, Nombre_grado FROM grados";
$stmt = $db->query($sql);

if ($stmt) {
    // Obtener todos los resultados como un array asociativo
    $grados = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Error al intentar consultar los grados.";
    exit();
}

// Inicializar variable para mensajes
$mensaje = '';

// Procesar el formulario cuando se envíe
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar y obtener los datos del formulario
    $nombre_curso = $_POST['nombre_curso'];
    $id_grado = $_POST['id_grado'];

    // Insertar el curso en la base de datos
    $query = "INSERT INTO cursos (Nombre_curso, ID_grado) VALUES (:nombre_curso, :id_grado)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':nombre_curso', $nombre_curso);
    $stmt->bindParam(':id_grado', $id_grado);

    if ($stmt->execute()) {
        // Éxito: se creó el curso
        $mensaje = 'success';
    } else {
        // Error al crear el curso
        $mensaje = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Curso</title>
    <link rel="stylesheet" type="text/css" href="../../../assets/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Estilos específicos para este formulario */
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
    
        .logo-container {
            display: flex;
            justify-content: center;
            margin-bottom: 10px;
        }
        .logo {
            max-width: 150px;
        }
        .title {
            text-align: center;
            margin-bottom: 20px;
        }
        .admin {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .subtitulo-admin {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
        }
        .crear-asignatura {
            text-align: center;
            margin-bottom: 20px;
        }
        .crear-asignatura a.button {
            display: inline-block;
            background-color: #28a745;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .crear-asignatura a.button:hover {
            background-color: #218838;
        }
        .form-container {
            max-width: 400px;
            margin: auto;
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .form-group input[type="text"], .form-group select {
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        .form-group button {
            width: 100%;
            padding: 10px;
            background-color: #6f42c1;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
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
            <h1>Crear Nuevo Curso</h1>
        </div>
    </header>
    <section class="admin">
        <section class="cursos" id="section-cursos">
            <div class="form-container">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <div class="form-group">
                        <label for="nombre_curso">Nombre del Curso:</label>
                        <input type="text" id="nombre_curso" name="nombre_curso" required>
                    </div>
                    <div class="form-group">
                        <label for="id_grado">ID Grado:</label>
                        <select id="id_grado" name="id_grado" required>
                            <option value="">Seleccione Grado</option>
                            <?php foreach ($grados as $grado): ?>
                                <option value="<?php echo $grado['ID_grado']; ?>"><?php echo $grado['Nombre_grado']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="button boton-centrado">Crear</button>
                    </div>
                </form>
            </div>
            <div class="regresar">
                <a href="index.php" class="button boton-centrado" id="btn-regresar">Regresar</a>
            </div>
            <div class="salir">
                <button id="btn_salir">Salir</button>
            </div>
        </section>
    </section>
    <footer>
        <p>Todos los derechos reservados</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($mensaje == 'success'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Curso Creado',
                    text: 'El curso se creó correctamente.',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true,
                    didClose: () => {
                        window.location.href = 'index.php'; // Redirige al index después de la notificación
                    }
                });
            <?php elseif ($mensaje == 'error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al crear el curso. Intenta de nuevo.',
                    showConfirmButton: true
                });
            <?php endif; ?>
        });
    </script>
</body>
</html>
