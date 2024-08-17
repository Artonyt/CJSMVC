<?php
$to = 'vanegasantonio452@gmail.com'; // Reemplaza con una dirección de correo válida
$subject = 'Prueba de correo';
$message = 'Esto es una prueba de correo electrónico.';
$headers = 'From: no-reply@tu_dominio.com';

if (mail($to, $subject, $message, $headers)) {
    echo 'Correo enviado correctamente.';
} else {
    echo 'Error al enviar el correo.';
}
?>
