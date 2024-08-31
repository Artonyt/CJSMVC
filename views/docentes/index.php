<?php
// Conectar a la base de datos
require_once '../../config/db.php';
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
    margin: 5px;
    text-align: center;
}
</style>
<script>

        function flyBell() {
            var bellImage = document.getElementById("bellImage");
            bellImage.classList.add("flying");
        }
        function flyBellAndShowPopup() {
            var bellImage = document.getElementById("bellImage");
            bellImage.classList.add("flying");
            document.getElementById("popup").style.display = "block"; // Muestra la ventana emergente
        }

        function closePopup() {
            document.getElementById("popup").style.display = "none"; // Oculta la ventana emergente
            location.reload();
        }

</script>

    <header>
        <div class="logo-container">
            <img src="../../assets/logo.png" alt="Logo de la empresa" class="logo">
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
    // Construir la URL adecuada para los botones
    $urls = [
        '/dashboard/cjs/views/docentes/asistencias/CursosAsistencias.php' => 'Control de Asistencias',
        '/dashboard/cjs/views/docentes/notas/CursosNotas.php' => 'Control de Notas',
        
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
        <button id="btn_salir">Salir</button>
    </div>

    <footer>
        <p>Todos los derechos reservados</p>
    </footer>
    <script src="../assets/menu.js"></script>
</body>
</html>
