<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['Identificacion'])) {
    // Si no ha iniciado sesión, redirigir al inicio de sesión
    header("Location: /dashboard/cjs/login/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Panel Administrativo</title>
    <link rel="stylesheet" href="../../assets/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f0f0f0;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .admin {
            text-align: center;
            background-color: #c2c2c2;
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
</head>
<body>
    <section class="admin">
        <div class="subtitulo-admin">
            <h2>Administrador</h2>
        </div>
        <div class="botones-admin">
            <?php
            // Construir la URL adecuada para los botones
            $urls = [
                '/dashboard/cjs/views/administrador/asignaturas' => 'Gestión de Asignaturas',
                '/dashboard/cjs/views/administrador/cursos' => 'Gestión de Cursos',
                '/dashboard/cjs/views/administrador/cursosdocentes' => 'Gestión de Docentes Materias',
                '/dashboard/cjs/views/administrador/docentes' => 'Gestión de Docentes',
                '/dashboard/cjs/views/administrador/estudiantes' => 'Gestión de Estudiantes',
                '/dashboard/cjs/views/administrador/materias' => 'Gestión de Materias',
                '/dashboard/cjs/views/administrador/materiascursos' => 'Gestión Materias Cursos',
                '/dashboard/cjs/views/administrador/administradores' => 'Agregar Administrador',
            ];

            foreach ($urls as $url => $label) {
                echo '<a href="' . $url . '" class="button-admin">' . $label . '</a>';
            }
            ?>
        </div>
    </section>
    <div class="salir">
        <button id="btn_salir" onclick="window.location.href='/dashboard/cjs/login/logout.php'">Salir</button>
    </div>

    <footer>
        <p>Todos los derechos reservados</p>
    </footer>
</body>
</html>
