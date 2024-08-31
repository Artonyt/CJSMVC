<?php
include '../../../config/db.php';

session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['ID_usuario'])) {
    die("No estás autenticado.");
}

$user_id = $_SESSION['ID_usuario'];

// Consulta SQL para obtener los datos del usuario
$sql_usuario = "SELECT Nombres, Apellidos, Correo_electronico FROM usuarios WHERE ID_usuario = ?";
$stmt_usuario = $db->prepare($sql_usuario);
$stmt_usuario->bindParam(1, $user_id, PDO::PARAM_INT);
$stmt_usuario->execute();
$usuario = $stmt_usuario->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    die("Usuario no encontrado.");
}

$nombreUsuario = $usuario['Nombres'];
$apellidoUsuario = $usuario['Apellidos'];
$correoUsuario = $usuario['Correo_electronico'];

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

// Consulta SQL para obtener los datos de estudiantes del curso seleccionado
$sql_estudiantes = "SELECT ID_estudiante, Nombres, Apellidos, Identificacion FROM estudiantes WHERE ID_curso = ?";
$stmt_estudiantes = $db->prepare($sql_estudiantes);
$stmt_estudiantes->bindParam(1, $id_curso, PDO::PARAM_INT);
$stmt_estudiantes->execute();
$result_estudiantes = $stmt_estudiantes->fetchAll(PDO::FETCH_ASSOC);


// Consulta SQL para obtener las notas de los estudiantes
$sql_notas = "SELECT ID_nota, ID_estudiante, ID_tarea, Calificacion, Fecha FROM notas WHERE ID_estudiante = ? ORDER BY Fecha ASC";
$stmt_notas = $db->prepare($sql_notas);

// Procesar el formulario para guardar o actualizar las notas
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion'])) {
    if ($_POST['accion'] == 'guardar_nota') {
        if (isset($_POST['estudiante_id'], $_POST['tarea_id'], $_POST['calificacion'], $_POST['fecha'])) {
            $estudiante_id = intval($_POST['estudiante_id']);
            $tarea_id = intval($_POST['tarea_id']);
            $calificacion = floatval($_POST['calificacion']);
            $fecha = $_POST['fecha'];

            // Validar la fecha
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
                $_SESSION['mensaje'] = "Fecha no válida.";
                header('Location: ' . $_SERVER['PHP_SELF'] . '?id_curso=' . $id_curso);
                exit;
            }

            // Verificar si ya existe una nota para este estudiante y tarea
            $stmt_verificar_nota = $db->prepare("SELECT ID_nota FROM notas WHERE ID_estudiante = ? AND ID_tarea = ?");
            $stmt_verificar_nota->bindParam(1, $estudiante_id, PDO::PARAM_INT);
            $stmt_verificar_nota->bindParam(2, $tarea_id, PDO::PARAM_INT);
            $stmt_verificar_nota->execute();
            $resultado_verificar_nota = $stmt_verificar_nota->fetch(PDO::FETCH_ASSOC);

            if (!$resultado_verificar_nota) {
                // No hay nota registrada, proceder con la inserción
                $stmt = $db->prepare("INSERT INTO notas (ID_estudiante, ID_tarea, Calificacion, Fecha) VALUES (?, ?, ?, ?)");
                $stmt->bindParam(1, $estudiante_id, PDO::PARAM_INT);
                $stmt->bindParam(2, $tarea_id, PDO::PARAM_INT);
                $stmt->bindParam(3, $calificacion, PDO::PARAM_STR);
                $stmt->bindParam(4, $fecha, PDO::PARAM_STR);
                $stmt->execute();
                $_SESSION['mensaje'] = "¡Nota guardada correctamente!";
            } else {
                // Ya existe una nota para este estudiante y tarea, proceder con la actualización
                $stmt = $db->prepare("UPDATE notas SET Calificacion = ?, Fecha = ? WHERE ID_estudiante = ? AND ID_tarea = ?");
                $stmt->bindParam(1, $calificacion, PDO::PARAM_STR);
                $stmt->bindParam(2, $fecha, PDO::PARAM_STR);
                $stmt->bindParam(3, $estudiante_id, PDO::PARAM_INT);
                $stmt->bindParam(4, $tarea_id, PDO::PARAM_INT);
                $stmt->execute();
                $_SESSION['mensaje'] = "¡Nota actualizada correctamente!";
            }
        } else {
            $_SESSION['mensaje'] = "Error: No se enviaron todos los datos necesarios desde el formulario.";
        }
    } elseif ($_POST['accion'] == 'eliminar_nota') {
        if (isset($_POST['nota_id'])) {
            $nota_id = intval($_POST['nota_id']);

            // Eliminar la nota
            $stmt = $db->prepare("DELETE FROM notas WHERE ID_nota = ?");
            $stmt->bindParam(1, $nota_id, PDO::PARAM_INT);
            $stmt->execute();
            $_SESSION['mensaje'] = "¡Nota eliminada correctamente!";
        } else {
            $_SESSION['mensaje'] = "Error: ID de nota no especificado.";
        }
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
    <title>Notas del Curso</title>
    <link rel="stylesheet" href="../../../assets/style.css">
    <style>
        .mensaje-anuncio {
            position: fixed;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #efdeef;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            z-index: 1000;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        .editable-input {
            width: 60px;
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
        .btn-agregar-tarea {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
        }
        .btn-agregar-tarea:hover {
            background-color: #0056b3;
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
                <h4>Planillas</h4>
                <br>
                <li>
                    <a href="">
                        <ion-icon name="paper-plane-outline"></ion-icon>
                        <span>Tarea 1</span>
                    </a>
                </li>
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
                        <span class="nombre"><?php echo htmlspecialchars($nombreUsuario) . ' ' . htmlspecialchars($apellidoUsuario); ?></span>
                        <span class="email"><?php echo htmlspecialchars($correoUsuario); ?></span>
                    </div>
                    <ion-icon name="ellipsis-vertical-outline"></ion-icon>
                </div>
            </div>
        </div>
    </div>
    <main>
        <h1>Notas para <?php echo htmlspecialchars($curso['Nombre_curso']); ?></h1>
        <?php if (!empty($mensaje)): ?>
        <div class="mensaje-anuncio">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
        <?php endif; ?>
        <div class="container">
            <!-- Formulario de búsqueda -->
            <div class="search-container">
                <input type="text" id="searchInput" placeholder="Buscar...">
            </div>

            <table>
                <thead>
                    <tr>
                        <th>IDENTIFICACIÓN</th>
                        <th>NOMBRES</th>
                        <th>APELLIDOS</th>
                        <th>Notas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($result_estudiantes) > 0): ?>
                        <?php foreach($result_estudiantes as $row): ?>
                        <tr class="fila">
                            <td><?php echo htmlspecialchars($row['Identificacion']); ?></td>
                            <td><?php echo htmlspecialchars($row['Nombres']); ?></td>
                            <td><?php echo htmlspecialchars($row['Apellidos']); ?></td>
                            <td>
                                <?php
                                // Obtener las notas del estudiante
                                $stmt_notas->bindParam(1, $row['ID_estudiante'], PDO::PARAM_INT);
                                $stmt_notas->execute();
                                $result_notas = $stmt_notas->fetchAll(PDO::FETCH_ASSOC);

                                if (count($result_notas) > 0) {
                                    // Mostrar las notas ordenadas por fecha
                                    usort($result_notas, function($a, $b) {
                                        return strtotime($a['Fecha']) - strtotime($b['Fecha']);
                                    });

                                    foreach ($result_notas as $nota) {
                                        echo htmlspecialchars($nota['Calificacion']) . " (" . htmlspecialchars($nota['Fecha']) . ")<br>";

                                        // Mostrar botones para editar y eliminar la nota existente
                                        echo '<form method="post" style="display:inline-block; margin-top:5px;">';
                                        echo '<input type="hidden" name="estudiante_id" value="' . htmlspecialchars($row['ID_estudiante']) . '">';
                                        echo '<input type="hidden" name="tarea_id" value="' . htmlspecialchars($nota['ID_tarea']) . '">';
                                        echo '<input type="hidden" name="nota_id" value="' . htmlspecialchars($nota['ID_nota']) . '">';
                                        echo '<input type="hidden" name="accion" value="guardar_nota">';
                                        echo '<input type="number" step="0.01" name="calificacion" value="' . htmlspecialchars($nota['Calificacion']) . '" class="editable-input">';
                                        echo '<input type="date" name="fecha" value="' . htmlspecialchars($nota['Fecha']) . '">';
                                        echo '<input type="submit" value="Actualizar">';
                                        echo '</form>';

                                        echo '<form method="post" style="display:inline-block; margin-top:5px;">';
                                        echo '<input type="hidden" name="nota_id" value="' . htmlspecialchars($nota['ID_nota']) . '">';
                                        echo '<input type="hidden" name="accion" value="eliminar_nota">';
                                        echo '<input type="submit" value="Eliminar" onclick="return confirm(\'¿Estás seguro de que deseas eliminar esta nota?\');">';
                                        echo '</form><br>';
                                    }
                                } else {
                                    // Mostrar formulario para capturar nueva nota
                                    ?>
                                    <form method="post" style="margin-top:5px;">
                                        <input type="hidden" name="estudiante_id" value="<?php echo htmlspecialchars($row['ID_estudiante']); ?>">
                                        <input type="hidden" name="tarea_id" value="1"> <!-- Debes ajustar el ID de la tarea -->
                                        <input type="hidden" name="accion" value="guardar_nota">
                                        <input type="number" step="0.01" name="calificacion" placeholder="Calificación" class="editable-input">
                                        <input type="date" name="fecha" value="<?php echo date('Y-m-d'); ?>" />
                                        <input type="submit" value="Guardar Nota">
                                    </form>
                                    <?php
                                }
                                ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No hay estudiantes en este curso.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- Botón para agregar nueva tarea -->
        <a href="AgregarTareas.php?id_curso=<?php echo htmlspecialchars($id_curso); ?>" class="btn-agregar-tarea">Agregar Nueva Tarea</a>

    </main>
    <div class="salir">
        <form method="POST" action="../../../logout.php">
            <button type="submit" class="salir-button">Salir</button>
        </form>
    </div>
    <script>
        // Filtrar la tabla basada en la búsqueda del usuario
        document.getElementById('searchInput').addEventListener('keyup', function() {
            var searchTerm = this.value.toLowerCase();
            var filas = document.querySelectorAll('.fila');
            filas.forEach(function(fila) {
                var textoFila = fila.textContent.toLowerCase();
                if (textoFila.indexOf(searchTerm) > -1) {
                    fila.style.display = '';
                } else {
                    fila.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
