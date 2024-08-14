<?php
$password = 'mi_contraseña';
$hash = password_hash($password, PASSWORD_BCRYPT);
echo "Hash para la contraseña '$password': " . $hash;
?>
