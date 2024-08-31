<?php
include '../../../config/db.php';

session_start();

// Verificar si se pasó el id_curso como parámetro
if (!isset($_GET['id_curso']) || !is_numeric($_GET['id_curso'])) {
    die("ID de curso no especificado o inválido.");
}

$id_curso = intval($_GET['id_curso']); // Sanitizar el id_curso

// Consulta SQL para obtener los datos del curso seleccionado
$sql_curso = "SELECT ID_curso, Nombre_curso, ID_grado FROM cursos WHERE ID_curso = ?";
$stmt_curso = $db->prepare($sql_curso);
$stmt_curso->bindParam(1, $id_curso, PDO::PARAM_INT);
$stmt_curso->execute();
$curso = $stmt_curso->fetch(PDO::FETCH_ASSOC);

if (!$curso) {
    die("Curso no encontrado.");
}

// Procesar el formulario para guardar una nueva tarea
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] == 'guardar_tarea') {
    if (isset($_POST['nombre_tarea'], $_POST['fecha_entrega'])) {
        $nombre_tarea = $_POST['nombre_tarea'];
        $fecha_entrega = $_POST['fecha_entrega'];
        $id_materia = $_POST['id_materia']; // Asumiendo que esta información se proporciona en el formulario o se puede derivar

        // Insertar nueva tarea en la base de datos
        $stmt = $db->prepare("INSERT INTO tareas (Nombre_tarea, Fecha_entrega, ID_materia) VALUES (?, ?, ?)");
        $stmt->bindParam(1, $nombre_tarea, PDO::PARAM_STR);
        $stmt->bindParam(2, $fecha_entrega, PDO::PARAM_STR);
        $stmt->bindParam(3, $id_materia, PDO::PARAM_INT);
        $stmt->execute();
        
        $_SESSION['mensaje'] = "¡Tarea guardada correctamente!";
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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Nueva Tarea</title>
    <link rel="stylesheet" href="../../../assets/style.css">
    <style>
        .mensaje-anuncio {
            position: fixed;
            top: 10px;
            left: 80%;
            transform: translateX(-50%);
            background-color: #efdeef;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            z-index: 1000;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="menu">
        <ion-icon name="menu-outline"></ion-icon>
        <ion-icon name="close-outline"></ion-icon>
    </div>
    <div class="barra-lateral">
        <div>
            <a href="perfil_docente.php">
                <div class="nombre-pagina">
                    <img src="../../../Assets/logo (2).png" alt="" width="40%">
                </div>
            </a>  
        </div>
        <nav class="navegacion">
            <ul>
                <li>
                    <a id="inbox" href="perfil_docente.php">
                        <ion-icon name="mail-unread-outline"></ion-icon>
                        <span>Perfil</span>
                    </a>
                </li>
                <li>
                    <a href="../index.php">
                        <ion-icon name="star-outline"></ion-icon>
                        <span>Menú</span>
                    </a>
                </li>
                <li>
                    <a href="CursosNotas.php">
                        <ion-icon name="document-text-outline"></ion-icon>
                        <span>Cursos</span>
                    </a>
                </li>
                <br>
                
            </ul>
        </nav>
        <div>
            <div class="linea"></div>
            <div class="modo-oscuro">
                <div class="info">
                    <ion-icon name="moon-outline"></ion-icon>
                    <span>Modo Oscuro</span>
                </div>
                <div class="switch">
                    <div class="base">
                        <div class="circulo"></div>
                    </div>
                </div>
            </div>
            <div class="usuario">
                <img src="../../../assets/profile.jpg" alt="">
                <div class="info-usuario">
                    <div class="nombre-email">
                        <span class="nombre"><?php echo htmlspecialchars($nombreUsuario); ?></span>
                        <span class="email"><?php echo htmlspecialchars($correoUsuario); ?></span>
                    </div>
                    <ion-icon name="ellipsis-vertical-outline"></ion-icon>
                </div>
            </div>
        </div>
    </div>
    <main>
        <h1>Registrar Tarea para <?php echo htmlspecialchars($curso['Nombre_curso']); ?></h1>
        <?php if (!empty($mensaje)): ?>
        <div class="mensaje-anuncio">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
        <?php endif; ?>
        <div class="container">
            <form method="post">
                <label for="nombre_tarea">Nombre de la tarea:</label>
                <input type="text" id="nombre_tarea" name="nombre_tarea" required>
                
                <label for="fecha_entrega">Fecha de entrega:</label>
                <input type="date" id="fecha_entrega" name="fecha_entrega" required>
                
                <input type="hidden" name="id_materia" value="1"> <!-- Asegúrate de ajustar este valor según sea necesario -->
                <input type="hidden" name="accion" value="guardar_tarea">
                <input type="submit" value="Guardar Tarea">
            </form>
        </div>
    </main>
</body>
</html>
