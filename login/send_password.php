<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "colegio";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    error_log("Conexión fallida: " . $conn->connect_error);
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    $stmt = $conn->prepare("SELECT ID_usuario, Nombres FROM usuarios WHERE Correo = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $new_password = bin2hex(random_bytes(4));
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("UPDATE usuarios SET contraseña = ? WHERE ID_usuario = ?");
        $stmt->bind_param("si", $hashed_password, $row["ID_usuario"]);
        if ($stmt->execute()) {
            $subject = "Recuperación de contraseña";
            $message = "Hola " . $row["Nombres"] . ",\n\nTu nueva contraseña temporal es: " . $new_password . "\n\nPor favor, cámbiala después de iniciar sesión.";
            $headers = "From: no-reply@tu_dominio.com";

            if (mail($email, $subject, $message, $headers)) {
                echo json_encode(["success" => true, "message" => "Correo enviado con la nueva contraseña."]);
            } else {
                error_log("Error al enviar el correo.");
                echo json_encode(["success" => false, "message" => "Error al enviar el correo."]);
            }
        } else {
            error_log("Error al actualizar la contraseña.");
            echo json_encode(["success" => false, "message" => "Error al actualizar la contraseña."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Correo electrónico no encontrado."]);
    }

    $stmt->close();
}

$conn->close();
?>
