<?php
include '../../../config/db.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['Identificacion'])) {
    die("Usuario no autenticado.");
}

$identificacion = $_SESSION['Identificacion'];

// Consulta SQL para obtener los datos del usuario
$sql_usuario = "SELECT Nombres, Apellidos, Correo_electronico FROM usuarios WHERE Identificacion = ?";
$stmt_usuario = $db->prepare($sql_usuario);
$stmt_usuario->bindParam(1, $identificacion, PDO::PARAM_STR); // Utiliza PDO::PARAM_STR para campos VARCHAR
$stmt_usuario->execute();
$usuario = $stmt_usuario->fetch(PDO::FETCH_ASSOC);

$nombreUsuario = $usuario['Nombres'];
$apellidoUsuario = $usuario['Apellidos'];
$correoUsuario = $usuario['Correo_electronico'];

// Verificar si se pasó el id_curso como parámetro
if (!isset($_GET['id_curso'])) {
    die("ID de curso no especificado.");
}

$id_curso = intval($_GET['id_curso']); // Sanitizar el id_curso

// Obtener información del curso
$stmt_curso = $db->prepare("SELECT Nombre_curso FROM cursos WHERE ID_curso = ?");
$stmt_curso->execute([$id_curso]);
$curso = $stmt_curso->fetch(PDO::FETCH_ASSOC);

if (!$curso) {
    die("Curso no encontrado.");
}

// Inicializar variables
$mensaje = "";
$fecha_seleccionada = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d'); // Obtener la fecha de la URL o usar la fecha actual

// Procesar el formulario para guardar o editar la asistencia
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['accion'])) {
        if ($_POST['accion'] == 'guardar') {
            if (isset($_POST['estudiante_id'], $_POST['asignatura_id'], $_POST['asistencia'], $_POST['fecha'])) {
                $estudiante_id = $_POST['estudiante_id'];
                $asignatura_id = $_POST['asignatura_id'];
                $estado_asistencia = $_POST['asistencia'];
                $fecha = $_POST['fecha'];
                $archivo_destino = null;

                // Verificar si se subió un archivo de excusa
                if ($estado_asistencia == 'excusa' && isset($_FILES['excusa_archivo']) && $_FILES['excusa_archivo']['error'] === UPLOAD_ERR_OK) {
                    $archivo_nombre = $_FILES['excusa_archivo']['name'];
                    $archivo_temp = $_FILES['excusa_archivo']['tmp_name'];
                    $directorio_destino = 'C:/xampp/htdocs/CJS/Views/docente/';

                    // Mover el archivo subido
                    $archivo_destino = $directorio_destino . $archivo_nombre;
                    if (!move_uploaded_file($archivo_temp, $archivo_destino)) {
                        die("Error al mover el archivo de excusa. Verifica que el directorio 'uploads' exista y tenga permisos de escritura.");
                    }
                }

                // Verificar si ya existe una asistencia para este estudiante en esta fecha
                $stmt_verificar_asistencia = $db->prepare("SELECT ID_asistencia FROM asistencia WHERE ID_estudiante = ? AND Fecha = ?");
                $stmt_verificar_asistencia->execute([$estudiante_id, $fecha]);
                $resultado_verificar_asistencia = $stmt_verificar_asistencia->fetch(PDO::FETCH_ASSOC);

                if (!$resultado_verificar_asistencia) {
                    $stmt = $db->prepare("INSERT INTO asistencia (ID_estudiante, ID_materia, Fecha, Estado, Excusa_imagen) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$estudiante_id, $asignatura_id, $fecha, $estado_asistencia, $archivo_destino]);
                    $_SESSION['mensaje'] = "¡Asistencia guardada correctamente!";
                } else {
                    $mensaje = "Ya existe una asistencia registrada para este estudiante en esta fecha.";
                }
            } else {
                $mensaje = "Error: No se enviaron todos los datos necesarios desde el formulario.";
            }
        } elseif ($_POST['accion'] == 'editar') {
            if (isset($_POST['asistencia_id'], $_POST['asistencia'], $_POST['fecha'])) {
                $asistencia_id = $_POST['asistencia_id'];
                $estado_asistencia = $_POST['asistencia'];
                $fecha = $_POST['fecha'];
                $archivo_destino = null;

                // Verificar si se subió un archivo de excusa
                if ($estado_asistencia == 'excusa' && isset($_FILES['excusa_archivo']) && $_FILES['excusa_archivo']['error'] === UPLOAD_ERR_OK) {
                    $archivo_nombre = $_FILES['excusa_archivo']['name'];
                    $archivo_temp = $_FILES['excusa_archivo']['tmp_name'];
                    $directorio_destino = 'C:/xampp/htdocs/CJS/Views/docente/';

                    // Mover el archivo subido
                    $archivo_destino = $directorio_destino . $archivo_nombre;
                    if (!move_uploaded_file($archivo_temp, $archivo_destino)) {
                        die("Error al mover el archivo de excusa. Verifica que el directorio 'uploads' exista y tenga permisos de escritura.");
                    }
                }

                // Actualizar el registro de asistencia
                $stmt = $db->prepare("UPDATE asistencia SET Estado = ?, Excusa_imagen = ? WHERE ID_asistencia = ?");
                $stmt->execute([$estado_asistencia, $archivo_destino, $asistencia_id]);
                $_SESSION['mensaje'] = "¡Asistencia actualizada correctamente!";
            } else {
                $mensaje = "Error: No se enviaron todos los datos necesarios desde el formulario de edición.";
            }
        }
        header('Location: ' . $_SERVER['PHP_SELF'] . '?id_curso=' . $id_curso . '&fecha=' . urlencode($fecha_seleccionada));
        exit;
    }
}

if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);
}

// Consulta SQL para obtener los datos de estudiantes del curso seleccionado
$sql_estudiantes = "SELECT ID_estudiante, Nombres, Apellidos, Identificacion FROM estudiantes WHERE ID_curso = ?";
$stmt_estudiantes = $db->prepare($sql_estudiantes);
$stmt_estudiantes->execute([$id_curso]);
$result_estudiantes = $stmt_estudiantes->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asistencias - <?php echo htmlspecialchars($curso['Nombre_curso']); ?></title>
    <link rel="stylesheet" href="../../../assets/style.css">
    <style>
        .mensaje-anuncio {
            position: fixed;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #efdeef;
            color: #333;
            padding: 10px 20px;
            border-radius: 5px;
            z-index: 1000;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .excusa-archivo {
            display: none;
        }
        .search-container {
            margin: 20px 0;
        }
        #searchInput {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .fecha-container {
            text-align: right;
            margin-bottom: 10px;
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        #fechaSeleccionada {
            font-size: 16px;
            padding: 5px;
        }
    </style>
    <div style="position: fixed; bottom: 20px; right: 20px;">
    <form action="descargar_asistencias.php?id_curso=<?php echo $id_curso; ?>&fecha=<?php echo urlencode($fecha_seleccionada); ?>" method="post">
        <button type="submit" style="padding: 10px 20px; background-color: #6a0dad; color: white; border: none; border-radius: 5px; cursor: pointer;">Descargar Asistencias</button>
    </form>
</div>

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
                    <img src="../../../assets/logo (2).png" alt="" width="40%">
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
                    <a href="CursosAsistencias.php">
                        <ion-icon name="document-text-outline"></ion-icon>
                        <span>Cursos</span>
                    </a>
                </li>
                <br>
                <h4>Planillas</h4>
                <br>
                <li>
                    <a href="Asistencias.php">
                        <ion-icon name="paper-plane-outline"></ion-icon>
                        <span>Asistencias</span>
                    </a>
                </li>
            </ul>
        </nav>
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
                    <span class="nombre"><?php echo htmlspecialchars($nombreUsuario . ' ' . $apellidoUsuario); ?></span>
                    <span class="email"><?php echo htmlspecialchars($correoUsuario); ?></span>
                </div>
                <ion-icon name="ellipsis-vertical-outline"></ion-icon>
            </div>
        </div>
    </div>
    
    <main class="main">
        <h1>Asistencia para <?php echo htmlspecialchars($curso['Nombre_curso']); ?></h1>
        <?php if (!empty($mensaje)): ?>
        <div class="mensaje-anuncio">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
        <?php endif; ?>
        
        <div class="fecha-container">
            Fecha: <input type="date" id="fechaSeleccionada" value="<?php echo htmlspecialchars($fecha_seleccionada); ?>">
        </div>

        <div class="search-container">
            <input type="text" id="searchInput" placeholder="Buscar estudiantes...">
        </div>
        
        <table class="table" id="studentsTable">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Identificación</th>
                    <th>Asistencia</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result_estudiantes as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['Nombres'] . ' ' . $row['Apellidos']); ?></td>
                    <td><?php echo htmlspecialchars($row['Identificacion']); ?></td>
                    <td>
                        <?php
                        // Comprobar la asistencia en la fecha seleccionada
                        $stmt_verificar_asistencia = $db->prepare("SELECT ID_asistencia, Estado FROM asistencia WHERE ID_estudiante = ? AND Fecha = ?");
                        $stmt_verificar_asistencia->execute([$row['ID_estudiante'], $fecha_seleccionada]);
                        $resultado_verificar_asistencia = $stmt_verificar_asistencia->fetch(PDO::FETCH_ASSOC);

                        if ($resultado_verificar_asistencia) {
                            echo htmlspecialchars($resultado_verificar_asistencia['Estado']);
                            echo '<form method="post" enctype="multipart/form-data">';
                            echo '<input type="hidden" name="asistencia_id" value="' . htmlspecialchars($resultado_verificar_asistencia['ID_asistencia']) . '">';
                            echo '<input type="hidden" name="estudiante_id" value="' . htmlspecialchars($row['ID_estudiante']) . '">';
                            echo '<input type="hidden" name="asignatura_id" value="' . htmlspecialchars($id_curso) . '">';
                            echo '<input type="hidden" name="fecha" value="' . htmlspecialchars($fecha_seleccionada) . '">';
                            echo '<input type="hidden" name="accion" value="editar">';
                            echo '<select name="asistencia">';
                            echo '<option value="Presente" ' . ($resultado_verificar_asistencia['Estado'] == 'Presente' ? 'selected' : '') . '>Presente</option>';
                            echo '<option value="Ausente" ' . ($resultado_verificar_asistencia['Estado'] == 'Ausente' ? 'selected' : '') . '>Ausente</option>';
                            echo '<option value="Tardanza" ' . ($resultado_verificar_asistencia['Estado'] == 'Tardanza' ? 'selected' : '') . '>Tardanza</option>';
                            echo '<option value="Excusa" ' . ($resultado_verificar_asistencia['Estado'] == 'Excusa' ? 'selected' : '') . '>Excusa</option>';
                            echo '</select>';
                            echo '<input type="file" name="excusa_archivo" class="excusa-archivo" />';
                            echo '<button type="submit">Editar</button>';
                            echo '</form>';
                        } else {
                            echo '<form method="post" enctype="multipart/form-data">';
                            echo '<input type="hidden" name="estudiante_id" value="' . htmlspecialchars($row['ID_estudiante']) . '">';
                            echo '<input type="hidden" name="asignatura_id" value="' . htmlspecialchars($id_curso) . '">';
                            echo '<input type="hidden" name="fecha" value="' . htmlspecialchars($fecha_seleccionada) . '">';
                            echo '<input type="hidden" name="accion" value="guardar">';
                            echo '<select name="asistencia">';
                            echo '<option value="Presente">Presente</option>';
                            echo '<option value="Ausente">Ausente</option>';
                            echo '<option value="Tardanza">Tardanza</option>';
                            echo '<option value="Excusa">Excusa</option>';
                            echo '</select>';
                            echo '<input type="file" name="excusa_archivo" class="excusa-archivo" />';
                            echo '<button type="submit">Guardar</button>';
                            echo '</form>';
                        }
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
    <div class="salir">
        <form method="POST" action="../../../logout.php">
            <button type="submit" class="salir-button">Salir</button>
        </form>
    </div>
    <script src="../../../assets/js/main.js"></script>
    <script>
        document.getElementById('fechaSeleccionada').addEventListener('change', function() {
            var fechaSeleccionada = this.value;
            var url = new URL(window.location.href);
            url.searchParams.set('fecha', fechaSeleccionada);
            window.location.href = url.toString(); // Redirigir a la misma URL con el nuevo parámetro de fecha
        });

        document.getElementById('searchInput').addEventListener('input', function() {
            var searchTerm = this.value.toLowerCase();
            var students = document.querySelectorAll('#studentsTable tbody tr');
            students.forEach(function(row) {
                var studentName = row.querySelector('td:first-child').textContent.toLowerCase();
                if (studentName.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        document.querySelectorAll('select[name="asistencia"]').forEach(function(selectElement) {
            selectElement.addEventListener('change', function() {
                var fileInput = this.parentNode.querySelector('.excusa-archivo');
                if (this.value === 'Excusa') {
                    fileInput.style.display = 'block';
                } else {
                    fileInput.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>