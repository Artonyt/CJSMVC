<?php
    // Conectar a la base de datos
    require_once '../../../config/db.php';
    $db = Database::connect();

    // Inicializar la variable de mensaje de error
    $errorMessage = '';

    // Procesar el formulario si se ha enviado
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nombres = $_POST['nombres'];
        $apellidos = $_POST['apellidos'];
        $identificacion = $_POST['identificacion'];
        $contraseña = $_POST['contraseña'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $correo_electronico = $_POST['correo_electronico'];
        $id_rol = 'Docente'; // Asignar el rol de docente

        // Validar los campos requeridos
        if (empty($nombres) || empty($apellidos) || empty($identificacion) || empty($contraseña) || empty($correo_electronico)) {
            $errorMessage = 'Por favor complete todos los campos obligatorios.';
        } else {
            // Insertar el nuevo docente en la base de datos
            $query = "INSERT INTO usuarios (Nombres, Apellidos, Identificacion, Contraseña, Direccion, Telefono, Correo_electronico, ID_rol) 
                      VALUES (:nombres, :apellidos, :identificacion, :contraseña, :direccion, :telefono, :correo_electronico, :id_rol)";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':nombres', $nombres);
            $stmt->bindParam(':apellidos', $apellidos);
            $stmt->bindParam(':identificacion', $identificacion);
            $stmt->bindParam(':contraseña', $contraseña);
            $stmt->bindParam(':direccion', $direccion);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':correo_electronico', $correo_electronico);
            $stmt->bindParam(':id_rol', $id_rol);

            if ($stmt->execute()) {
                // Enviar respuesta JSON al frontend
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                exit(); // Terminar la ejecución del script después de enviar la respuesta JSON
            } else {
                $errorMessage = 'Error al intentar crear el docente.';
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - Crear Nuevo Docente</title>
    <link rel="stylesheet" type="text/css" href="../../../assets/styles.css">
    <!-- Incluir SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<header>
    <div class="logo-container">
        <img src="../../assets/Logo-Sena.jpg" alt="Logo de la empresa" class="logo">
    </div>
    <div class="title">
        <h1>Crear Nuevo Docente</h1>
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
<section class="create-docente" id="section-create-docente">
    <form id="createDocenteForm" action="create.php" method="POST">
        <?php
        if (!empty($errorMessage)) {
            echo '<p class="error-message">' . htmlspecialchars($errorMessage) . '</p>';
        }
        ?>
        <label for="nombres">Nombres:</label><br>
        <input type="text" id="nombres" name="nombres" required><br><br>

        <label for="apellidos">Apellidos:</label><br>
        <input type="text" id="apellidos" name="apellidos" required><br><br>

        <label for="identificacion">Identificación:</label><br>
        <input type="text" id="identificacion" name="identificacion" required><br><br>

        <label for="contraseña">Contraseña:</label><br>
        <input type="password" id="contraseña" name="contraseña" required><br><br>

        <label for="direccion">Dirección:</label><br>
        <input type="text" id="direccion" name="direccion"><br><br>

        <label for="telefono">Teléfono:</label><br>
        <input type="text" id="telefono" name="telefono"><br><br>

        <label for="correo_electronico">Correo Electrónico:</label><br>
        <input type="email" id="correo_electronico" name="correo_electronico" required><br><br>

        <button type="submit">Crear Docente</button>
    </form>
</section>
<footer>
    <p>Sena todos los derechos reservados </p>
</footer>

<script>
document.getElementById('createDocenteForm').addEventListener('submit', function(event) {
    event.preventDefault();

    var formData = new FormData(this);

    // Enviar solicitud al controlador
    fetch('create.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log('Respuesta del servidor:', response); // Verificar la respuesta del servidor
        if (!response.ok) {
            throw new Error('Error de red o respuesta no exitosa: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        console.log('Datos recibidos:', data); // Verificar los datos recibidos del servidor
        if (data.success) {
            // Procesar la respuesta exitosa
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: 'El docente ha sido creado exitosamente',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'index.php'; // Redirigir a la página de listado de docentes
            });
        } else {
            // Mostrar alerta de error desde el servidor
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'No se pudo crear el docente. Por favor, intenta de nuevo',
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        console.error('Error al procesar la solicitud:', error); // Mostrar el error en consola
        // Mostrar alerta de error de conexión
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un problema al procesar la solicitud. Por favor, verifica tu conexión e intenta de nuevo.',
            confirmButtonText: 'OK'
        });
    });
});
</script>

</body>
</html>
