<?php
// Conectar a la base de datos
require_once '../../config/conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Docente</title>
    <link rel="stylesheet" type="text/css" href="../../assets/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<style>
.button-row {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    margin-bottom: 10px;
}
.button-admin {
    flex: 1;
    margin: 10px;
    text-align: center;
}
.salir {
    position: fixed;
    bottom: 20px;
    right: 20px;
}
#btn_salir {
    background-color: #f44336; /* Color rojo */
    color: white;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    border-radius: 5px;
}
#btn_salir:hover {
    background-color: #d32f2f; /* Rojo más oscuro al pasar el mouse */
}
</style>
<header>
    <div class="logo-container">
        <img src="../../Assets/Imagenes/logo (2).png" alt="Logo de la empresa" class="logo">
    </div>
    <div class="title">
        <h1>Panel del Docente</h1>
    </div>
    <div class="datetime">
        <?php
            date_default_timezone_set('America/Bogota');
            $fechaActual = date("d/m/Y");
            $horaActual = date("h:i a");
        ?>
        <div class="datetime">
            <div class="fecha">
                <p>Fecha actual: <?php echo $fechaActual; ?></p>
            </div>
            <div class="hora">
                <p>Hora actual: <?php echo $horaActual; ?></p>
            </div>
        </div>
    </div>
</header>
<section class="admin">
<div class="subtitulo-admin">
    <h2>Docente</h2>
</div>
<div class="botones-admin">
<?php
$urls = [
    '/CJS/Views/docente/CursosAsistencias.php' => 'Control de Asistencias',
    '/CJS/Views/docente/CursosNotas.php' => 'Control de Notas',
];
$i = 0;
foreach ($urls as $url => $label) {
    if ($i % 3 == 0) {
        if ($i > 0) echo '</div>';
        echo '<div class="button-row">';
    }
    echo '<a href="' . $url . '" class="button-admin">' . $label . '</a>';
    $i++;
}
if ($i % 3 != 0) {
    echo '</div>';
}
?>
</div>
</section>
<div class="salir">
    <form id="logoutForm" action="logout.php" method="POST">
        <button type="submit" id="btn_salir">Salir</button>
    </form>
</div>
<form action="views/docentes/asistencias/CursosAsistencias.php" method="GET">
    <label for="curso_id">ID del Curso:</label>
    <input type="text" id="curso_id" name="curso_id">
    <button type="submit">Validar Curso</button>
</form>
<footer>
    <p>Todos los derechos reservados</p>
</footer>
<script src="../assets/menu.js"></script>
</body>
</html>
