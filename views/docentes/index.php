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
   <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: url('../../assets/fondo.jpg'); /* Reemplaza con la ruta de tu imagen */
            background-size: cover; /* Asegura que la imagen cubra todo el fondo */
            background-position: center; /* Centra la imagen */
            background-repeat: no-repeat; /* Evita que la imagen se repita */
            margin: 0;
            font-family: 'Roboto', sans-serif;
        }


        .admin {
            text-align: center;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }

        .subtitulo-admin h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .botones-admin {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .button-admin {
            display: inline-block;
            padding: 15px 25px;
            background-color: #6f42c1;
            color: white;
            text-decoration: none;
            border-radius: 50px;
            transition: transform 0.2s, background-color 0.2s;
        }

        .button-admin:hover {
            transform: scale(1.05);
            background-color: #5a2d91;
        }

        .button-admin:active {
            transform: scale(0.95);
        }

        .salir {
            margin-top: 10px;
        }

        #btn_salir {
            padding: 10px 20px;
            background-color: #ff5722;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        #btn_salir:hover {
            background-color: #e64a19;
        }

        footer {
            margin-top: 30px;
            text-align: center;
            color: #888;
            font-size: 14px;
        }
    </style>

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
