<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "colegio";

try {
    $db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Conexión exitosa"; // Comentar o eliminar esta línea en producción
} catch(PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>
