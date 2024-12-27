<?php
// Iniciar la sesión
session_start();
include 'connection.php';

// Eliminar todas las variables de sesión
session_unset();

// Destruir la sesión
session_destroy();

// Redirigir al usuario a la página de login
header("Location: login.php");
exit();
?>