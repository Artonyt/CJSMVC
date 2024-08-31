<?php
include '../../../config/db.php'; // Asegúrate de que db.php esté configurado para usar PDO
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['ID_usuario'])) {
    die("Usuario no autenticado.");
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

// Consulta para obtener los cursos y materias asignadas al usuario
$sql_cursos = "SELECT c.ID_curso, c.Nombre_curso, m.Nombre_materia 
               FROM cursos c 
               JOIN materias_cursos mc ON c.ID_curso = mc.ID_curso 
               JOIN materias m ON mc.ID_materia = m.ID_materia 
               JOIN docentes_materias dm ON m.ID_materia = dm.ID_materia 
               WHERE dm.ID_docente = ?";

$stmt_cursos = $db->prepare($sql_cursos);
$stmt_cursos->bindParam(1, $user_id, PDO::PARAM_INT);
$stmt_cursos->execute();
$cursos = $stmt_cursos->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CJS - Cursos</title>
    <link rel="stylesheet" href="../../../assets/style.css">
    <link rel="icon" href="../../../assets/favicon.ico" type="image/vnd.microsoft.icon">
</head>
<body>
    <div class="menu">
        <ion-icon name="menu-outline"></ion-icon>
        <ion-icon name="close-outline"></ion-icon>
    </div>
    <div class="barra-lateral">
        <div id="cloud">
            <div class="nombre-pagina">
                <img src="../../../assets/logo (2).png" alt="" width="30%">
            </div>
        </div>
        <nav class="navegacion">
            <ul>
                <li>
                    <a href="perfil_docente.php">
                        <ion-icon name="person-outline"></ion-icon>
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
    <main>
        <h1>Cursos Asignados</h1>
        <div class="container">
            <table class="table table-striped table-dark table_id" border="1" id="tabla-cursos">
                <thead>
                    <tr>
                        <th>Nombre Curso</th>
                        <th>Nombre Materia</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (count($cursos) > 0) {
                        foreach ($cursos as $row) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['Nombre_curso']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Nombre_materia']) . "</td>";
                            echo "<td>
                                    <a href='Asistencias.php?id_curso=" . $row['ID_curso'] . "'>
                                        <button class='add-button'>Ver Asistencias</button>
                                    </a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No hay cursos registrados</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>
    <div class="salir">
        <form method="POST" action="../../../logout.php">
            <button type="submit" class="salir-button">Salir</button>
        </form>
    </div>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="../../../Assets/script.js"></script>
</body>
</html>

<?php
// Cerrar la conexión
$stmt_usuario = null;
$stmt_cursos = null;
$db = null;
?>
