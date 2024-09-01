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
    require_once '../../../router.php';

    // Definir variables y mensajes de error
    $id_curso = $nombre_curso = "";
    $nombre_curso_err = "";
    $success = false;

    // Procesar formulario cuando se envíe
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtener ID del curso desde el formulario oculto (si es necesario)
        $id_curso = $_POST["id_curso"];

        // Validar nombre del curso
        $input_nombre_curso = trim($_POST["nombre_curso"]);
        if (empty($input_nombre_curso)) {
            $nombre_curso_err = "Por favor, ingrese el nombre del curso.";
        } else {
            $nombre_curso = $input_nombre_curso;
        }

        // Verificar si hay errores de entrada antes de actualizar en la base de datos
        if (empty($nombre_curso_err)) {
            // Preparar la actualización del curso en la base de datos
            $sql = "UPDATE cursos SET Nombre_curso = :nombre_curso WHERE ID_curso = :id_curso";

            if ($stmt = $db->prepare($sql)) {
                // Vincular variables a la declaración preparada como parámetros
                $stmt->bindParam(":nombre_curso", $param_nombre_curso);
                $stmt->bindParam(":id_curso", $param_id_curso);

                // Establecer parámetros
                $param_nombre_curso = $nombre_curso;
                $param_id_curso = $id_curso;

                // Intentar ejecutar la declaración preparada
                if ($stmt->execute()) {
                    // Cambiar el estado de éxito a verdadero
                    $success = true;
                } else {
                    echo "Oops! Algo salió mal. Por favor, intenta nuevamente más tarde.";
                }
            }

            // Cerrar declaración
            unset($stmt);
        }

        // Cerrar conexión
        unset($db);
    } else {
        // Verificar el parámetro ID pasado por GET y obtener detalles del curso
        if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
            // Obtener el ID del curso desde la URL
            $id_curso = trim($_GET["id"]);

            // Consultar detalles del curso desde la base de datos
            $sql = "SELECT * FROM cursos WHERE ID_curso = :id_curso";
            if ($stmt = $db->prepare($sql)) {
                // Vincular variables a la declaración preparada como parámetros
                $stmt->bindParam(":id_curso", $param_id_curso);

                // Establecer parámetros
                $param_id_curso = $id_curso;

                // Intentar ejecutar la declaración preparada
                if ($stmt->execute()) {
                    // Verificar si existe un curso con ese ID
                    if ($stmt->rowCount() == 1) {
                        // Obtener fila de resultados como un array asociativo
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        $nombre_curso = $row["Nombre_curso"];
                    } else {
                        // No se encontró ningún curso válido con el ID proporcionado
                        header("location: error.php");
                        exit();
                    }
                } else {
                    echo "Oops! Algo salió mal. Por favor, intenta nuevamente más tarde.";
                }
            }

            // Cerrar declaración
            unset($stmt);
        } else {
            // Si no se proporciona un ID válido en la URL, redirigir a la página de error
            header("location: error.php");
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Curso</title>
    <link rel="stylesheet" type="text/css" href="../../../assets/styles.css">
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
        .form-group input[type="text"] {
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
        .mensaje {
            text-align: center;
            margin-top: 20px;
            color: red;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="../../../assets/Logo.png" alt="Logo de la empresa" class="logo">
        </div>
        <div class="title">
            <h1>Editar Curso</h1>
        </div>
    </header>
    <section class="admin">
        <div class="formulario-curso">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="hidden" name="id_curso" value="<?php echo htmlspecialchars($id_curso); ?>">
                
                <div class="form-group <?php echo (!empty($nombre_curso_err)) ? 'has-error' : ''; ?>">
                    <label>Nombre Curso</label>
                    <input type="text" name="nombre_curso" value="<?php echo htmlspecialchars($nombre_curso); ?>">
                    <span class="help-block"><?php echo $nombre_curso_err; ?></span>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="button-admin">Actualizar Curso</button>
                </div>
            </form>
        </div>
        <div class="regresar">
            <a href="index.php" class="button boton-centrado" id="btn-regresar">Regresar </a>
        </div>
        <div class="salir">
            <button id="btn_salir">Salir</button>
        </div>
    </section>
    <footer>
        <p>Todos los derechos reservados</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($success): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Curso actualizado',
                    text: 'El curso se ha actualizado exitosamente.',
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
