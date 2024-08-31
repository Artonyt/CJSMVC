<?php
session_start();

// Inicializar variables
$nombreUsuario = $apellidoUsuario = $identificacionUsuario = $direccionUsuario = $correoUsuario = '';
$error = '';

// Verificar si se ha enviado el formulario de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $password = $_POST["password"];

    // Incluir el archivo de conexión a la base de datos
    include("../../../config/db.php");

    if (!$db) {
        $error = "Error de conexión a la base de datos.";
    } else {
        try {
            // Consultar la base de datos usando una consulta preparada
            $consultaDocente = $db->prepare("SELECT * FROM usuarios WHERE Identificacion = :usuario");
            $consultaDocente->bindParam(':usuario', $usuario);
            $consultaDocente->execute();

            if ($consultaDocente->rowCount() > 0) {
                $row = $consultaDocente->fetch(PDO::FETCH_ASSOC);

                // Verificar la contraseña
                if (password_verify($password, $row['contraseña'])) {
                    // Obtener los datos del usuario
                    $nombreUsuario = $row['Nombres'];
                    $apellidoUsuario = $row['Apellidos'];
                    $identificacionUsuario = $row['Identificacion'];
                    $direccionUsuario = $row['Direccion'];
                    $correoUsuario = $row['Correo_electronico'];
                } else {
                    $error = "Usuario o contraseña incorrectos";
                }
            } else {
                $error = "Usuario no encontrado";
            }
        } catch (PDOException $e) {
            $error = "Error en la consulta: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Docente</title>
    <link rel="stylesheet" href="../../../assets/style.css">
</head>
<body>
    <div class="menu">
        <ion-icon name="menu-outline"></ion-icon>
        <ion-icon name="close-outline"></ion-icon>
    </div>

    <div class="barra-lateral">
        <div>
            <a href="">
                <div class="nombre-pagina">
                    <img src="../../../assets/logo (2).png" alt="" width="40%">
                </div>
            </a>  
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
                        <span>Menu</span>
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
        <div>
            <div class="linea"></div>

            <div class="modo-oscuro">
                <div class="info">
                    <ion-icon name="moon-outline"></ion-icon>
                    <span>Modo Oscuro </span>
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
        <br><br>
        <center><h1>Colegio Codema IED</h1></center>
        <br><br>

        <h1>Perfil</h1>
        <span class="nav_image">
            <img src="../../../assets/profile.jpg" logo_img" width="15%" />
        </span>
        
        <br>
        <br>
        <p>Nombres: <?php echo isset($nombreUsuario) ? htmlspecialchars($nombreUsuario) : 'No disponible'; ?></p>
        <p>Apellidos: <?php echo isset($apellidoUsuario) ? htmlspecialchars($apellidoUsuario) : 'No disponible'; ?></p>
        <p>Identificación: <?php echo isset($identificacionUsuario) ? htmlspecialchars($identificacionUsuario) : 'No disponible'; ?></p>
        <p>Dirección: <?php echo isset($direccionUsuario) ? htmlspecialchars($direccionUsuario) : 'No disponible'; ?></p>
        <p>Correo electrónico: <?php echo isset($correoUsuario) ? htmlspecialchars($correoUsuario) : 'No disponible'; ?></p>
    </main>
|   <div class="salir">
        <form method="POST" action="../../../logout.php">
            <button type="submit" class="salir-button">Salir</button>
        </form>
    </div>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="../../Assets/js/script.js"></script>
</body>
</html>
